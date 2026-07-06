<?php

namespace MediaWiki\Extension\OAuth\Control;

use Composer\Semver\VersionParser;
use MediaWiki\Api\ApiMessage;
use MediaWiki\Extension\OAuth\Backend\Consumer;
use MediaWiki\Extension\OAuth\Backend\Utils;
use MediaWiki\Json\FormatJson;
use MediaWiki\Parser\Sanitizer;
use MediaWiki\WikiMap\WikiMap;
use StatusValue;
use UnexpectedValueException;

class ConsumerValidator {

	/**
	 * MySQL Blob Size is 2^16 - 1 = 65535 as per "L + 2 bytes, where L < 216" on
	 * https://dev.mysql.com/doc/refman/8.0/en/storage-requirements.html
	 */
	public const BLOB_SIZE = 65535;

	/**
	 * Takes an array of consumer data and returns a validation status.
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
			if ( !$callback ) {
				$status->merge( $this->getGenericErrorStatus( $fieldName ) );
			} else {
				$status->merge( $callback( $fieldValue, $fields ) );
			}
		}
		return $status;
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
				return ( $agreed == true )
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

}
