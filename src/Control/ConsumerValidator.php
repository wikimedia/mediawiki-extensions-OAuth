<?php

namespace MediaWiki\Extension\OAuth\Control;

use Composer\Semver\VersionParser;
use MediaWiki\Api\ApiMessage;
use MediaWiki\Config\ServiceOptions;
use MediaWiki\Context\RequestContext;
use MediaWiki\Extension\OAuth\Backend\Consumer;
use MediaWiki\Extension\OAuth\Backend\Utils;
use MediaWiki\Extension\OAuth\Entity\ClientEntity;
use MediaWiki\Json\FormatJson;
use MediaWiki\Language\FormatterFactory;
use MediaWiki\MainConfigNames;
use MediaWiki\Parser\Sanitizer;
use MediaWiki\SpecialPage\SpecialPage;
use MediaWiki\Status\StatusFormatter;
use MediaWiki\Utils\MWCryptRand;
use MediaWiki\Utils\MWRestrictions;
use MediaWiki\WikiMap\WikiMap;
use StatusValue;
use UnexpectedValueException;
use Wikimedia\Assert\Assert;
use Wikimedia\Assert\ParameterTypeException;
use Wikimedia\NormalizedException\NormalizedException;
use Wikimedia\Timestamp\ConvertibleTimestamp;

/**
 * Validates data sets representing Consumer objects. The data is either from a web request
 * (e.g. Special:OAuthConsumerRegistration submission) or from configuration (e.g. $wgOAuthStaticApps),
 * with significant differences in how they are validated.
 */
class ConsumerValidator {

	public const SERVICE_OPTIONS = [
		MainConfigNames::NoReplyAddress,
	];

	/**
	 * MySQL Blob Size is 2^16 - 1 = 65535 as per "L + 2 bytes, where L < 216" on
	 * https://dev.mysql.com/doc/refman/8.0/en/storage-requirements.html
	 */
	public const BLOB_SIZE = 65535;

	/**
	 * Field name => Wikimedia\Assert type(s)
	 */
	private const FIELD_TYPES = [
		Consumer::FIELD_ID => 'integer',
		Consumer::FIELD_CONSUMER_KEY => 'string',
		Consumer::FIELD_NAME => 'string',
		Consumer::FIELD_USER_ID => 'integer',
		Consumer::FIELD_VERSION => 'string',
		Consumer::FIELD_CALLBACK_URL => 'string',
		Consumer::FIELD_CALLBACK_IS_PREFIX => 'boolean',
		Consumer::FIELD_DESCRIPTION => 'string',
		Consumer::FIELD_EMAIL => 'string',
		Consumer::FIELD_OAUTH_VERSION => 'integer',
		Consumer::FIELD_OWNER_ONLY  => 'boolean',
		Consumer::FIELD_WIKI => 'string',
		Consumer::FIELD_GRANTS => 'array',
		Consumer::FIELD_SECRET_KEY => 'string',
		Consumer::FIELD_RSA_KEY => 'string',
		Consumer::FIELD_OAUTH2_IS_CONFIDENTIAL => 'boolean',
		Consumer::FIELD_RESTRICTIONS => MWRestrictions::class,
	];

	public function __construct(
		private ServiceOptions $options,
		private FormatterFactory $formatterFactory,
	) {
		$this->options->assertRequiredOptions( self::SERVICE_OPTIONS );
	}

	/**
	 * Takes an array of consumer data and returns a validation status.
	 *
	 * Validation only checks the values of the provided fields - it does not check whether
	 * the data is sufficient to define a consumer, and it ignores unknown fields. It also
	 * cannot handle a value that is the wrong PHP type. Use expandConsumerData() for that.
	 *
	 * Warnings should be surfaced to the user when submitting a new consumer, but the user
	 * is allowed to ignore. Errors means this consumer should not be usable.
	 *
	 * @param array $fields <field name> => <value>; field names should match the
	 *   Consumer::FIELD_* constants.
	 */
	public function validateFields( array $fields ): StatusValue {
		$validatorCallbacks = $this->getValidatorCallbacks();
		$status = StatusValue::newGood();
		foreach ( $fields as $fieldName => $fieldValue ) {
			$callback = $validatorCallbacks[$fieldName] ?? null;
			if ( $callback ) {
				$status->merge( $callback( $fieldValue, $fields ) );
			}
		}
		return $status;
	}

	/**
	 * Like validateFields() but throws on error.
	 *
	 * @throws NormalizedException
	 */
	public function validateFieldsAndThrow( array $fields ): void {
		$status = $this->validateFields( $fields );
		if ( !$status->isOK() ) {
			throw new NormalizedException( 'Consumer {consumer_key} is invalid: {reason}', [
				'consumer_key' => $fields[Consumer::FIELD_CONSUMER_KEY] ?? '',
				'reason' => $this->getStatusFormatter()->getWikiText( $status, [ 'lang' => 'en' ] ),
			] );
		}
	}

	/**
	 * Returns an array of validator callbacks, indexed by field names.
	 *
	 * Callbacks take the field value and the array of all field values as arguments,
	 * and return a status which is either good or has a single warning or fatal error message
	 * in it. The single message is always an ApiMessage. Warnings are informational and can be
	 * ignored by the caller.
	 *
	 * PHP type validation (e.g. a field having a scalar value when it should be an array) is the
	 * caller's responsibility. Note that field types might differ in type-juggling-compatible
	 * ways between callers.
	 *
	 * @phan-return array<string,callable(mixed,array):StatusValue>
	 */
	public function getValidatorCallbacks(): array {
		$alwaysValid = static function (): StatusValue {
			return StatusValue::newGood();
		};
		$validateBlobSize = function ( $fieldName, $fieldValue ): StatusValue {
			if ( strlen( $fieldValue ?? '' ) < self::BLOB_SIZE ) {
				return StatusValue::newGood();
			} else {
				return $this->getTooLongErrorStatus( $fieldName, self::BLOB_SIZE - 1 );
			}
		};

		return [
			Consumer::FIELD_ID => $alwaysValid,
			Consumer::FIELD_CONSUMER_KEY => $alwaysValid,
			Consumer::FIELD_NAME => function ( string $name ): StatusValue {
				$len = strlen( $name );
				if ( !$len ) {
					// unlikely to be reached due to HTMLForm validation, don't bother with a nice error
					return $this->getGenericErrorStatus( Consumer::FIELD_NAME );
				} elseif ( $len > 128 ) {
					return $this->getTooLongErrorStatus( Consumer::FIELD_NAME, 128 );
				}
				return StatusValue::newGood();
			},
			Consumer::FIELD_USER_ID => function ( int $userId ): StatusValue {
				$userName = Utils::getCentralUserNameFromId( $userId, 'raw' );
				return ( $userName === false )
					? $this->getErrorStatus( Consumer::FIELD_USER_ID, 'mwoauth-invalid-field-userId' )
					: StatusValue::newGood();
			},
			Consumer::FIELD_VERSION => function ( string $version ): StatusValue {
				if ( strlen( $version ) > 32 ) {
					return $this->getTooLongErrorStatus( Consumer::FIELD_VERSION, 32 );
				}
				$parser = new VersionParser();
				try {
					$parser->normalize( $version );
				} catch ( UnexpectedValueException $e ) {
					// can't be localized - probably still more useful than not providing details
					$error = wfEscapeWikiText( $e->getMessage() );
					return $this->getGenericErrorStatus( Consumer::FIELD_VERSION, $error );
				}
				return StatusValue::newGood();
			},
			Consumer::FIELD_CALLBACK_URL => $this->validateCallbackUrl( ... ),
			Consumer::FIELD_CALLBACK_IS_PREFIX => $alwaysValid,
			Consumer::FIELD_DESCRIPTION => static function ( $description ) use ( $validateBlobSize ): StatusValue {
				return $validateBlobSize( Consumer::FIELD_DESCRIPTION, $description );
			},
			Consumer::FIELD_EMAIL => function ( $email ): StatusValue {
				return Sanitizer::validateEmail( $email )
					? StatusValue::newGood()
					// can't happen, don't bother with a nice error
					: $this->getGenericErrorStatus( Consumer::FIELD_EMAIL );
			},
			// EMAIL_AUTHENTICATED is omitted because it is never sent by the user
			Consumer::FIELD_OAUTH_VERSION => function ( $version ): StatusValue {
				return in_array( $version, [ Consumer::OAUTH_VERSION_1, Consumer::OAUTH_VERSION_2 ] )
					? StatusValue::newGood()
					: $this->getGenericErrorStatus( Consumer::FIELD_OAUTH_VERSION );
			},
			Consumer::FIELD_DEVELOPER_AGREEMENT => function ( $agreed ): StatusValue {
				return $agreed
					? StatusValue::newGood()
					: $this->getGenericErrorStatus( Consumer::FIELD_DEVELOPER_AGREEMENT );
			},
			Consumer::FIELD_OWNER_ONLY => $alwaysValid,
			Consumer::FIELD_WIKI => function ( $wikiId ): StatusValue {
				global $wgConf;
				if ( $wikiId === '*'
					|| in_array( $wikiId, $wgConf->getLocalDatabases() )
					|| in_array( $wikiId, Utils::getAllWikiNames() )
				) {
					return StatusValue::newGood();
				} else {
					return $this->getGenericErrorStatus( Consumer::FIELD_WIKI );
				}
			},
			Consumer::FIELD_GRANTS => function ( $grants ): StatusValue {
				if ( is_string( $grants ) ) {
					$status = FormatJson::parse( $grants, FormatJson::FORCE_ASSOC );
					if ( !$status->isOK() ) {
						return $status;
					}
					$grants = $status->getValue();
				}
				if ( !is_array( $grants ) ) {
					return $this->getGenericErrorStatus( Consumer::FIELD_GRANTS );
				}
				$status = StatusValue::newGood();
				if ( !Utils::grantsAreValid( $grants, $status ) ) {
					return $status;
				}
				if ( strlen( FormatJson::encode( $grants ) ) >= self::BLOB_SIZE ) {
					return $this->getTooLongErrorStatus( Consumer::FIELD_GRANTS, self::BLOB_SIZE - 1 );
				}

				return StatusValue::newGood();
			},
			// REGISTRATION and SECRET_KEY are omitted because it is never sent by the user
			Consumer::FIELD_RSA_KEY => function ( $keyAsString ): StatusValue {
				if ( trim( $keyAsString ) === '' ) {
					return StatusValue::newGood();
				}
				if ( strlen( $keyAsString ) >= self::BLOB_SIZE ) {
					return $this->getTooLongErrorStatus( Consumer::FIELD_RSA_KEY, self::BLOB_SIZE - 1 );
				}
				$key = openssl_pkey_get_public( $keyAsString );
				if ( $key === false ) {
					$error = wfEscapeWikiText( openssl_error_string() );
					return $this->getGenericErrorStatus( Consumer::FIELD_RSA_KEY, $error );
				}
				$info = openssl_pkey_get_details( $key );
				if ( $info['type'] !== OPENSSL_KEYTYPE_RSA ) {
					return $this->getErrorStatus( Consumer::FIELD_RSA_KEY, 'mwoauth-invalid-field-rsaKey-not-rsa' );
				}
				return StatusValue::newGood();
			},
			Consumer::FIELD_RESTRICTIONS => static function ( $restrictions ) use ( $validateBlobSize ): StatusValue {
				return $validateBlobSize( Consumer::FIELD_RESTRICTIONS, (string)$restrictions );
			},
			// STAGE, STAGE_TIMESTAMP and DELETED are omitted because it is never sent by the user
			Consumer::FIELD_OAUTH2_IS_CONFIDENTIAL => $alwaysValid,
			Consumer::FIELD_OAUTH2_GRANT_TYPES => function ( $oauth2Grants, $fields ): StatusValue {
				if ( $fields['oauthVersion'] == Consumer::OAUTH_VERSION_1 ) {
					return StatusValue::newGood();
				}

				// OAuth 2 apps must have at least one grant type
				if ( !$oauth2Grants ) {
					return $this->getGenericErrorStatus( Consumer::FIELD_OAUTH2_GRANT_TYPES );
				}

				if ( strlen( FormatJson::encode( $oauth2Grants ) ) >= self::BLOB_SIZE ) {
					return $this->getTooLongErrorStatus( Consumer::FIELD_OAUTH2_GRANT_TYPES, self::BLOB_SIZE - 1 );
				}
				return StatusValue::newGood();
			},
		];
	}

	/**
	 * Takes an array of Consumer data (in the same format as Consumer::toArray() but potentially
	 * with some of the fields missing) and expands it so all fields are present. Only fields
	 * that don't affect the consumer's behavior can be missing (e.g. OAuth 2 specific fields for
	 * an OAuth 1 consumer).
	 *
	 * It also validates that all required fields are present, all present fields are known, and
	 * all fields are of the correct type. It does not validate field values beyond that - use
	 * validateFields() for that.
	 *
	 * @param array $consumerData
	 * @return array
	 * @throws NormalizedException
	 */
	public function expandConsumerData( array $consumerData ): array {
		// check OAuth version first as it determines what fields are required
		if ( !array_key_exists( Consumer::FIELD_OAUTH_VERSION, $consumerData ) ) {
			$this->raiseInvalidConfigError( $consumerData, 'missing required field oauthVersion' );
		}
		$oauthVersion = $consumerData[Consumer::FIELD_OAUTH_VERSION];
		if ( !in_array( $oauthVersion, [ Consumer::OAUTH_VERSION_1, Consumer::OAUTH_VERSION_2 ], true ) ) {
			$this->raiseInvalidConfigError( $consumerData, 'oauthVersion must be 1 or 2' );
		}
		$isOwnerOnly = (bool)( $consumerData[Consumer::FIELD_OWNER_ONLY] ?? false );
		$oAuth2GrantTypes = (array)( $consumerData[Consumer::FIELD_OAUTH2_GRANT_TYPES] ?? [] );
		$hasAuthorizationCodeGrant = in_array( ClientEntity::GRANT_TYPE_AUTHORIZATION_CODE, $oAuth2GrantTypes, true );

		// check required and unknown / unexpected keys
		[ 'required' => $requiredKeys, 'optional' => $optionalKeys, 'unused' => $unusedKeys ]
			= $this->getConsumerFields( $oauthVersion, $isOwnerOnly, $hasAuthorizationCodeGrant );

		foreach ( $requiredKeys as $key => $_ ) {
			$consumerData[$key] ?? $this->raiseInvalidConfigError( $consumerData, "$key is required" );
		}
		$unknownKeys = array_keys( array_diff_key( $consumerData, $requiredKeys, $optionalKeys, $unusedKeys ) );
		if ( $unknownKeys ) {
			$this->raiseInvalidConfigError( $consumerData, 'unknown fields: ' . implode( ', ', $unknownKeys ) );
		}
		$unexpectedKeys = array_keys( array_diff_key( $consumerData, $requiredKeys, $optionalKeys ) );
		if ( $unexpectedKeys ) {
			$appType = "OAuth $oauthVersion " . ( $isOwnerOnly ? 'owner-only' : 'non-owner-only' );
			$this->raiseInvalidConfigError( $consumerData, "these fields cannot be used with $appType apps: "
				. implode( ', ', $unexpectedKeys ) );
		}

		foreach ( self::FIELD_TYPES as $field => $type ) {
			if ( array_key_exists( $field, $consumerData ) ) {
				try {
					Assert::parameterType( $type, $consumerData[$field], 'not used' );
				} catch ( ParameterTypeException ) {
					$this->raiseInvalidConfigError( $consumerData, "$field must be $type" );
				}
			}
		}

		$needsSecret = $oauthVersion === Consumer::OAUTH_VERSION_1
			|| $consumerData[Consumer::FIELD_OAUTH2_IS_CONFIDENTIAL];
		if ( $needsSecret
			 && !( $consumerData[Consumer::FIELD_SECRET_KEY] ?? null )
			 && !( $consumerData[Consumer::FIELD_RSA_KEY] ?? null )
		) {
			$this->raiseInvalidConfigError( $consumerData,
				'Either secretKey or rsaKey is required for confidential clients' );
		}

		$unknownOauth2GrantTypes = array_diff( $consumerData[Consumer::FIELD_OAUTH2_GRANT_TYPES] ?? [],
			ClientEntity::GRANT_TYPES );
		if ( $unknownOauth2GrantTypes ) {
			$this->raiseInvalidConfigError( $consumerData, 'Invalid OAuth 2 grant types: '
				. implode( ', ', $unknownOauth2GrantTypes ) );
		}

		$consumerData += $optionalKeys;
		$consumerData += $unusedKeys;
		return $consumerData;
	}

	/**
	 * Checks the callback URL / redirect URI.
	 *
	 * @param string $url
	 * @param array $fields Full consumer data
	 * @return StatusValue
	 */
	private function validateCallbackUrl( string $url, array $fields ): StatusValue {
		$isOAuth1 = (int)$fields[Consumer::FIELD_OAUTH_VERSION] === Consumer::OAUTH_VERSION_1;
		$isOAuth2 = !$isOAuth1;
		$clientIsConfidential = $isOAuth1 || $fields[Consumer::FIELD_OAUTH2_IS_CONFIDENTIAL];

		if ( strlen( $url ) > 2000 ) {
			return $this->getTooLongErrorStatus( Consumer::FIELD_CALLBACK_URL, 2000 );
		} elseif ( $fields[Consumer::FIELD_OWNER_ONLY] ) {
			return StatusValue::newGood();
		}

		$urlUtils = Utils::getOAuthUrlUtils();
		$urlParts = $urlUtils->parse( $url );
		if ( !$urlParts ) {
			return $this->getGenericErrorStatus( Consumer::FIELD_CALLBACK_URL );
		}
		$isCustomProtocol = !in_array( $urlParts['scheme'], [ '', 'http', 'https' ], true );

		if ( $isCustomProtocol ) {
			if ( $clientIsConfidential ) {
				// Custom protocols are handled by an application installed on the device;
				// so it cannot possibly be confidential.
				return StatusValue::newFatal(
					new ApiMessage( 'mwoauth-error-callback-url-custom-protocol-nonconfidential',
						'invalid_callback_url' )
				);
			}
			// T412542: Follow RFC 8252 recommendation to use reverse domain name
			// format for custom URI schemes. Require at least one period to avoid generic
			// scheme names that could be confused with standard schemes.
			if ( !str_contains( $urlParts['scheme'], '.' ) ) {
				return StatusValue::newGood()->warning(
					new ApiMessage( 'mwoauth-error-callback-url-custom-scheme-no-period',
						'invalid_callback_url' )
				);
			}
		} elseif ( $isOAuth2 && !self::isSecureContext( $urlParts ) ) {
			// The OAuth 2 spec requires an encrypted transport.
			return StatusValue::newFatal(
				new ApiMessage( 'mwoauth-error-callback-url-must-be-https', 'invalid_callback_url' )
			);
		} elseif ( $clientIsConfidential && WikiMap::getWikiFromUrl( $url ) ) {
			// Reduce noise from clueless people using Wikipedia's URL as callback
			// (except for public clients; it can be valid e.g. for gadgets).
			return StatusValue::newGood()->warning(
				new ApiMessage( 'mwoauth-error-callback-server-url', 'invalid_callback_url' )
			);
		} elseif ( ( $isOAuth2 || !$fields[Consumer::FIELD_CALLBACK_IS_PREFIX] )
			&& in_array( $urlParts['path'] ?? '', [ '', '/' ], true )
			&& !( $urlParts['query'] ?? false )
			&& !( $urlParts['fragment'] ?? false )
		) {
			// Warn people using a bare domain name with no path or query part as
			// the exact callback URL. It is valid, but it's rare that they actually mean it.
			$message = $isOAuth1
				? 'mwoauth-error-callback-bare-domain-oauth1'
				: 'mwoauth-error-callback-bare-domain-oauth2';
			return StatusValue::newGood()->warning(
				new ApiMessage( $message, 'invalid_callback_url' )
			);
		}
		return StatusValue::newGood();
	}

	/**
	 * Decide whether the given (parsed) URL corresponds to a secure context.
	 * (This is only an approximation of the algorithm browsers use,
	 * since some considerations such as frames don't apply here.)
	 *
	 * @see https://developer.mozilla.org/en-US/docs/Web/Security/Secure_Contexts
	 *
	 * @param array $urlParts As returned by {@link UrlUtils::parse()}.
	 * @return bool
	 */
	private function isSecureContext( array $urlParts ): bool {
		if ( $urlParts['scheme'] === 'https' ) {
			return true;
		}

		$host = $urlParts['host'];
		if ( $host === 'localhost'
			|| $host === '127.0.0.1'
			|| $host === '[::1]'
			|| str_ends_with( $host, '.localhost' )
			// The wmftest.{com,net,org} domains hosted by the Wikimedia
			// Foundation include a '*.local IN A 127.0.0.1' that is used in
			// some local development environments.
			|| str_ends_with( $host, '.local.wmftest.com' )
			|| str_ends_with( $host, '.local.wmftest.net' )
			|| str_ends_with( $host, '.local.wmftest.org' )
		) {
			return true;
		}

		return false;
	}

	private function getErrorStatus( string $fieldName, string|array $msg, array $data = [] ): StatusValue {
		return StatusValue::newFatal( new ApiMessage( $msg, 'invalid_field', $data + [ 'field' => $fieldName ] ) );
	}

	private function getGenericErrorStatus( string $fieldName, ?string $details = null ): StatusValue {
		if ( $details === null ) {
			return $this->getErrorStatus( $fieldName, [ 'mwoauth-invalid-field', $fieldName ] );
		} else {
			return $this->getErrorStatus( $fieldName, [ 'mwoauth-invalid-field-with-details', $fieldName, $details ] );
		}
	}

	private function getTooLongErrorStatus( string $fieldName, int $maxLength ): StatusValue {
		return $this->getErrorStatus( $fieldName, [ 'mwoauth-invalid-field-too-long', $fieldName, $maxLength ] );
	}

	private function getStatusFormatter(): StatusFormatter {
		return $this->formatterFactory->getStatusFormatter( RequestContext::getMain() );
	}

	/**
	 * Returns what keys are required and optionally allowed for a given app type.
	 *
	 * Keys match Consumer properties.
	 * @return array Three arrays:
	 *   - 'required': required keys, in `<key> => true` format
	 *   - 'optional': optional keys, in `<key> => <default>` format
	 *   - 'unused': keys not used for this consumer type, in `<key> => <db default>` format
	 * @phan-return array{required:array,optional:array,unused:array}
	 * @see Consumer::getSchema()
	 * @see examples/configurationBasedApp.php
	 */
	private function getConsumerFields(
		int $oauthVersion,
		bool $isOwnerOnly,
		bool $hasAuthorizationCodeGrant,
	): array {
		// default values for non-required keys
		$dbDefaults = [
			Consumer::FIELD_OWNER_ONLY => false,
			// need an URL for validation purposes even if it won't be used
			// just in case, don't use anything that's not under our own control
			Consumer::FIELD_CALLBACK_URL => SpecialPage::getTitleFor( 'OAuth' )
				->getSubpage( 'verified' )->getCanonicalURL(),
			Consumer::FIELD_CALLBACK_IS_PREFIX => false,
			Consumer::FIELD_EMAIL => $this->options->get( MainConfigNames::NoReplyAddress ),
			Consumer::FIELD_EMAIL_AUTHENTICATED => true,
			Consumer::FIELD_DEVELOPER_AGREEMENT => true,
			Consumer::FIELD_WIKI => '*',
			Consumer::FIELD_REGISTRATION => ConvertibleTimestamp::now(),
			// to be in the safe side, set this to something unguessable even for
			// consumers that aren't supposed to use it
			Consumer::FIELD_SECRET_KEY => MWCryptRand::generateHex( 32 ),
			Consumer::FIELD_RSA_KEY => '',
			Consumer::FIELD_RESTRICTIONS => MWRestrictions::newDefault(),
			Consumer::FIELD_STAGE => Consumer::STAGE_APPROVED,
			Consumer::FIELD_STAGE_TIMESTAMP => ConvertibleTimestamp::now(),
			Consumer::FIELD_DELETED => false,
			Consumer::FIELD_OAUTH2_IS_CONFIDENTIAL => true,
		];
		$requiredKeys = [
			Consumer::FIELD_ID,
			Consumer::FIELD_CONSUMER_KEY,
			Consumer::FIELD_NAME,
			Consumer::FIELD_VERSION,
			Consumer::FIELD_DESCRIPTION,
			Consumer::FIELD_OAUTH_VERSION,
			Consumer::FIELD_GRANTS,
			// TODO make this not required
			Consumer::FIELD_USER_ID,
		];
		$optionalKeys = [
			Consumer::FIELD_OWNER_ONLY,
			Consumer::FIELD_SECRET_KEY,
			Consumer::FIELD_WIKI,
			Consumer::FIELD_RESTRICTIONS,
		];

		if ( $oauthVersion === Consumer::OAUTH_VERSION_1 ) {
			$optionalKeys[] = Consumer::FIELD_RSA_KEY;
			if ( !$isOwnerOnly ) {
				$requiredKeys[] = Consumer::FIELD_CALLBACK_URL;
				$optionalKeys[] = Consumer::FIELD_CALLBACK_IS_PREFIX;
			}
		} else {
			$requiredKeys[] = Consumer::FIELD_OAUTH2_IS_CONFIDENTIAL;
			if ( !$isOwnerOnly ) {
				$requiredKeys[] = Consumer::FIELD_OAUTH2_GRANT_TYPES;
				if ( $hasAuthorizationCodeGrant ) {
					$requiredKeys[] = Consumer::FIELD_CALLBACK_URL;
				}
			}
		}

		if ( $isOwnerOnly ) {
			$dbDefaults += [
				Consumer::FIELD_OAUTH2_GRANT_TYPES => [
					ClientEntity::GRANT_TYPE_CLIENT_CREDENTIALS,
				],
			];
		} else {
			$dbDefaults += [
				Consumer::FIELD_OAUTH2_GRANT_TYPES => [
					ClientEntity::GRANT_TYPE_AUTHORIZATION_CODE,
					ClientEntity::GRANT_TYPE_REFRESH_TOKEN,
				],
			];
		}

		// More complex conditions checked elsewhere:
		// - either SECRET_KEY or RSA_KEY is required for OAuth 1 apps and confidential OAuth 2 apps
		// - TODO cannot set confidential=false for client credentials

		$toAssoc = static fn ( array $a ) => array_fill_keys( $a, true );
		return [
			'required' => $toAssoc( $requiredKeys ),
			'optional' => array_intersect_key( $dbDefaults, $toAssoc( $optionalKeys ) ),
			'unused' => array_diff_key( $dbDefaults, $toAssoc( $requiredKeys ), $toAssoc( $optionalKeys ) ),
		];
	}

	private function raiseInvalidConfigError( array $consumerData, string $reason ): never {
		$consumerKey = $consumerData[Consumer::FIELD_CONSUMER_KEY] ?? 'missing key';
		throw new NormalizedException( 'Invalid configuration-based OAuth app ({consumerKey}): {reason}', [
			'consumerKey' => $consumerKey,
			'reason' => $reason,
		] );
	}

}
