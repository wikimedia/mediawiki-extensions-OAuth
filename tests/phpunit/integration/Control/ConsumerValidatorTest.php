<?php

namespace MediaWiki\Extension\OAuth\Tests\Integration\Control;

use MediaWiki\Config\SiteConfiguration;
use MediaWiki\Extension\OAuth\Backend\Consumer;
use MediaWiki\Extension\OAuth\Control\ConsumerValidator;
use MediaWiki\Extension\OAuth\Entity\ClientEntity;
use MediaWiki\Extension\OAuth\Lib\OAuthException;
use MediaWiki\Extension\OAuth\Lib\OAuthRequest;
use MediaWiki\Extension\OAuth\OAuthServices;
use MediaWiki\Extension\OAuth\Tests\MockOAuthSignatureMethodRsaSha1;
use MediaWiki\Utils\MWRestrictions;
use MediaWiki\WikiMap\WikiMap;
use MediaWikiIntegrationTestCase;

/**
 * @covers \MediaWiki\Extension\OAuth\Control\ConsumerValidator
 * @group Database
 */
class ConsumerValidatorTest extends MediaWikiIntegrationTestCase {

	private static function getConsumerData(): array {
		return [
			Consumer::FIELD_ID => 1234,
			Consumer::FIELD_CONSUMER_KEY => '1234567890abcdef1234567890abcdef',
			Consumer::FIELD_NAME => 'Test Consumer',
			Consumer::FIELD_USER_ID => 12345,
			Consumer::FIELD_VERSION => '1.0.0',
			Consumer::FIELD_CALLBACK_URL => 'https://example.com/callback',
			Consumer::FIELD_CALLBACK_IS_PREFIX => false,
			Consumer::FIELD_DESCRIPTION => 'A test consumer',
			Consumer::FIELD_EMAIL => 'test@example.com',
			Consumer::FIELD_EMAIL_AUTHENTICATED => '20050101000000',
			Consumer::FIELD_OAUTH_VERSION => Consumer::OAUTH_VERSION_2,
			Consumer::FIELD_DEVELOPER_AGREEMENT => true,
			Consumer::FIELD_OWNER_ONLY => false,
			Consumer::FIELD_WIKI => '*',
			Consumer::FIELD_GRANTS => [ 'editpage' ],
			Consumer::FIELD_REGISTRATION => '20150101000000',
			Consumer::FIELD_SECRET_KEY => bin2hex( random_bytes( 16 ) ),
			Consumer::FIELD_RSA_KEY => '',
			Consumer::FIELD_RESTRICTIONS => MWRestrictions::newDefault(),
			Consumer::FIELD_STAGE => Consumer::STAGE_APPROVED,
			Consumer::FIELD_STAGE_TIMESTAMP => '20250101000000',
			Consumer::FIELD_DELETED => false,
			Consumer::FIELD_OAUTH2_IS_CONFIDENTIAL => true,
			Consumer::FIELD_OAUTH2_GRANT_TYPES => [
				ClientEntity::GRANT_TYPE_AUTHORIZATION_CODE,
				ClientEntity::GRANT_TYPE_REFRESH_TOKEN,
			],
		];
	}

	private static function getOAuth1ConfigurationData(): array {
		return [
			Consumer::FIELD_OAUTH_VERSION => Consumer::OAUTH_VERSION_1,
		] + array_diff_key(
			self::getConsumerData(),
			self::getDbOnlyFields(),
			array_fill_keys( [
				Consumer::FIELD_CALLBACK_IS_PREFIX,
				Consumer::FIELD_OWNER_ONLY,
				Consumer::FIELD_WIKI,
				Consumer::FIELD_RSA_KEY,
				Consumer::FIELD_RESTRICTIONS,
				Consumer::FIELD_OAUTH2_IS_CONFIDENTIAL,
				Consumer::FIELD_OAUTH2_GRANT_TYPES,
			], true )
		);
	}

	private static function getOAuth1OwnerOnlyConfigurationData(): array {
		return [
			Consumer::FIELD_OAUTH_VERSION => Consumer::OAUTH_VERSION_1,
			Consumer::FIELD_OWNER_ONLY => true,
		] + array_diff_key(
			self::getConsumerData(),
			self::getDbOnlyFields(),
			array_fill_keys( [
				Consumer::FIELD_CALLBACK_URL,
				Consumer::FIELD_CALLBACK_IS_PREFIX,
				Consumer::FIELD_WIKI,
				Consumer::FIELD_RSA_KEY,
				Consumer::FIELD_RESTRICTIONS,
				Consumer::FIELD_OAUTH2_IS_CONFIDENTIAL,
				Consumer::FIELD_OAUTH2_GRANT_TYPES,
			], true )
		);
	}

	private static function getOAuth2ConfigurationData(): array {
		return array_diff_key(
			self::getConsumerData(),
			self::getDbOnlyFields(),
			array_fill_keys( [
				Consumer::FIELD_CALLBACK_IS_PREFIX,
				Consumer::FIELD_OWNER_ONLY,
				Consumer::FIELD_WIKI,
				Consumer::FIELD_RSA_KEY,
				Consumer::FIELD_RESTRICTIONS,
			], true )
		);
	}

	private static function getOAuth2OwnerOnlyConfigurationData(): array {
		return [
			Consumer::FIELD_OWNER_ONLY => true,
		] + array_diff_key(
			self::getConsumerData(),
				self::getDbOnlyFields(),
			array_fill_keys( [
				Consumer::FIELD_CALLBACK_URL,
				Consumer::FIELD_CALLBACK_IS_PREFIX,
				Consumer::FIELD_WIKI,
				Consumer::FIELD_RSA_KEY,
				Consumer::FIELD_RESTRICTIONS,
				Consumer::FIELD_OAUTH2_GRANT_TYPES,
			], true )
		);
	}

	private static function getDbOnlyFields() {
		return [
			Consumer::FIELD_EMAIL => true,
			Consumer::FIELD_EMAIL_AUTHENTICATED => true,
			Consumer::FIELD_DEVELOPER_AGREEMENT => true,
			Consumer::FIELD_REGISTRATION => true,
			Consumer::FIELD_STAGE => true,
			Consumer::FIELD_STAGE_TIMESTAMP => true,
			Consumer::FIELD_DELETED => true,
		];
	}

	private function getConsumerValidator(): ConsumerValidator {
		return OAuthServices::wrap( $this->getServiceContainer() )->getConsumerValidator();
	}

	private function getCallback( string $fieldName ): callable {
		return ( $this->getConsumerValidator() )->getValidatorCallbacks()[$fieldName];
	}

	// generic

	public function testValidateFieldsAndThrowInvalidFieldThrowsError(): void {
		$this->expectException( OAuthException::class );
		( $this->getConsumerValidator() )->validateFieldsAndThrow( [
			Consumer::FIELD_OAUTH_VERSION => 3,
		] );
	}

	public function testValidateFieldsAndThrowKnownFieldDoesNotThrowError(): void {
		( $this->getConsumerValidator() )->validateFieldsAndThrow( [
			Consumer::FIELD_NAME => 'My App',
		] );
		$this->assertTrue( true, 'no exception' );
	}

	public function testValidateFieldsMultipleErrors(): void {
		$status = ( $this->getConsumerValidator() )->validateFields( [
			Consumer::FIELD_NAME => 'My App',
			Consumer::FIELD_OAUTH_VERSION => 'invalid',
			Consumer::FIELD_DEVELOPER_AGREEMENT => false,
		] );
		$this->assertStatusNotOK( $status );
		$this->assertCount( 2, $status->getMessages( 'error' ) );
	}

	public static function provideAlwaysValidFields(): array {
		return [
			'id' => [ Consumer::FIELD_ID ],
			'consumerKey' => [ Consumer::FIELD_CONSUMER_KEY ],
			'callbackIsPrefix' => [ Consumer::FIELD_CALLBACK_IS_PREFIX ],
			'ownerOnly' => [ Consumer::FIELD_OWNER_ONLY ],
			'oauth2IsConfidential' => [ Consumer::FIELD_OAUTH2_IS_CONFIDENTIAL ],
		];
	}

	/** @dataProvider provideAlwaysValidFields */
	public function testAlwaysValidField( string $fieldName ): void {
		$status = $this->getCallback( $fieldName )( 'anything', [] );
		$this->assertStatusGood( $status );
	}

	// FIELD_NAME

	public function testFieldNameEmpty(): void {
		$status = $this->getCallback( Consumer::FIELD_NAME )( '', [] );
		$this->assertStatusNotOK( $status );
	}

	public function testFieldNameTooLong(): void {
		$status = $this->getCallback( Consumer::FIELD_NAME )( str_repeat( 'x', 129 ), [] );
		$this->assertStatusNotOK( $status );
	}

	public function testFieldNameAtMaxLength(): void {
		$status = $this->getCallback( Consumer::FIELD_NAME )( str_repeat( 'x', 128 ), [] );
		$this->assertStatusGood( $status );
	}

	public function testFieldNameValid(): void {
		$status = $this->getCallback( Consumer::FIELD_NAME )( 'My OAuth App', [] );
		$this->assertStatusGood( $status );
	}

	// FIELD_USER_ID

	public function testFieldUserIdValidUser(): void {
		$this->overrideConfigValue( 'MWOAuthSharedUserSource', 'local' );
		$user = $this->getTestUser()->getUser();
		$status = $this->getCallback( Consumer::FIELD_USER_ID )( $user->getId(), [] );
		$this->assertStatusGood( $status );
	}

	public function testFieldUserIdZeroIsInvalid(): void {
		$status = $this->getCallback( Consumer::FIELD_USER_ID )( 0, [] );
		$this->assertStatusNotOK( $status );
	}

	public function testFieldUserIdNonexistentIsInvalid(): void {
		$status = $this->getCallback( Consumer::FIELD_USER_ID )( PHP_INT_MAX, [] );
		$this->assertStatusNotOK( $status );
	}

	// FIELD_VERSION

	public function testFieldVersionTooLong(): void {
		$status = $this->getCallback( Consumer::FIELD_VERSION )( str_repeat( '1', 33 ), [] );
		$this->assertStatusNotOK( $status );
	}

	public function testFieldVersionInvalidSemver(): void {
		$status = $this->getCallback( Consumer::FIELD_VERSION )( 'not-valid-semver!!', [] );
		$this->assertStatusNotOK( $status );
	}

	public function testFieldVersionValidSemver(): void {
		$status = $this->getCallback( Consumer::FIELD_VERSION )( '1.0.0', [] );
		$this->assertStatusGood( $status );
	}

	// FIELD_CALLBACK_URL

	private function makeCallbackUrlFields( array $overrides = [] ): array {
		return $overrides + [
				Consumer::FIELD_OAUTH_VERSION => Consumer::OAUTH_VERSION_1,
				Consumer::FIELD_OWNER_ONLY => false,
				Consumer::FIELD_OAUTH2_IS_CONFIDENTIAL => true,
				Consumer::FIELD_CALLBACK_IS_PREFIX => false,
			];
	}

	public function testFieldCallbackUrlTooLong(): void {
		$url = 'https://example.com/' . str_repeat( 'x', 2000 );
		$status = $this->getCallback( Consumer::FIELD_CALLBACK_URL )(
			$url, $this->makeCallbackUrlFields()
		);
		$this->assertStatusNotOK( $status );
	}

	public function testFieldCallbackUrlOwnerOnlySkipsUrlValidation(): void {
		$status = $this->getCallback( Consumer::FIELD_CALLBACK_URL )(
			'xyz',
			$this->makeCallbackUrlFields( [ Consumer::FIELD_OWNER_ONLY => true ] )
		);
		$this->assertStatusGood( $status );
	}

	public function testFieldCallbackUrlEmptyStringIsInvalid(): void {
		$status = $this->getCallback( Consumer::FIELD_CALLBACK_URL )(
			'', $this->makeCallbackUrlFields()
		);
		$this->assertStatusNotOK( $status );
	}

	public function testFieldCallbackUrlCustomSchemeOauth1IsRejected(): void {
		// OAuth 1.0 consumers are always confidential; custom schemes cannot be confidential
		$status = $this->getCallback( Consumer::FIELD_CALLBACK_URL )(
			'myapp://oauth/callback',
			$this->makeCallbackUrlFields( [
				Consumer::FIELD_OAUTH_VERSION => Consumer::OAUTH_VERSION_1,
			] )
		);
		$this->assertStatusNotOK( $status );
	}

	public function testFieldCallbackUrlCustomSchemeOauth2ConfidentialIsRejected(): void {
		$status = $this->getCallback( Consumer::FIELD_CALLBACK_URL )(
			'myapp://oauth/callback',
			$this->makeCallbackUrlFields( [
				Consumer::FIELD_OAUTH_VERSION => Consumer::OAUTH_VERSION_2,
				Consumer::FIELD_OAUTH2_IS_CONFIDENTIAL => true,
			] )
		);
		$this->assertStatusNotOK( $status );
	}

	public function testFieldCallbackUrlCustomSchemeOauth2PublicIsAllowed(): void {
		// Public (non-confidential) OAuth 2 clients may use custom schemes (native apps)
		// T412542: Custom scheme with a period (RFC 8252 reverse domain name) is allowed without warning
		$status = $this->getCallback( Consumer::FIELD_CALLBACK_URL )(
			'com.example.myapp://oauth/callback',
			$this->makeCallbackUrlFields( [
				Consumer::FIELD_OAUTH_VERSION => Consumer::OAUTH_VERSION_2,
				Consumer::FIELD_OAUTH2_IS_CONFIDENTIAL => false,
			] )
		);
		$this->assertStatusGood( $status );
	}

	public function testFieldCallbackUrlCustomSchemeWithoutPeriodGivesWarning(): void {
		// T412542: Custom scheme without a period violates RFC 8252 recommendation; warning issued
		$status = $this->getCallback( Consumer::FIELD_CALLBACK_URL )(
			'myapp://oauth/callback',
			$this->makeCallbackUrlFields( [
				Consumer::FIELD_OAUTH_VERSION => Consumer::OAUTH_VERSION_2,
				Consumer::FIELD_OAUTH2_IS_CONFIDENTIAL => false,
			] )
		);
		$this->assertStatusOK( $status );
		$this->assertStatusNotGood( $status );
	}

	public function testFieldCallbackUrlHttpOauth2IsRejected(): void {
		// OAuth 2 spec requires encrypted transport; plain HTTP is not acceptable
		$status = $this->getCallback( Consumer::FIELD_CALLBACK_URL )(
			'http://example.com/callback',
			$this->makeCallbackUrlFields( [
				Consumer::FIELD_OAUTH_VERSION => Consumer::OAUTH_VERSION_2,
				Consumer::FIELD_OAUTH2_IS_CONFIDENTIAL => true,
			] )
		);
		$this->assertStatusNotOK( $status );
	}

	public function testFieldCallbackUrlHttpsOauth2IsAllowed(): void {
		$status = $this->getCallback( Consumer::FIELD_CALLBACK_URL )(
			'https://example.com/callback',
			$this->makeCallbackUrlFields( [
				Consumer::FIELD_OAUTH_VERSION => Consumer::OAUTH_VERSION_2,
				Consumer::FIELD_OAUTH2_IS_CONFIDENTIAL => true,
			] )
		);
		$this->assertStatusGood( $status );
	}

	public function testFieldCallbackUrlLocalhostHttpOauth2IsAllowed(): void {
		// localhost is a secure context even over plain HTTP
		$status = $this->getCallback( Consumer::FIELD_CALLBACK_URL )(
			'http://localhost:8080/callback',
			$this->makeCallbackUrlFields( [
				Consumer::FIELD_OAUTH_VERSION => Consumer::OAUTH_VERSION_2,
				Consumer::FIELD_OAUTH2_IS_CONFIDENTIAL => true,
			] )
		);
		$this->assertStatusGood( $status );
	}

	public function testFieldCallbackUrlLoopbackIpOauth2IsAllowed(): void {
		$status = $this->getCallback( Consumer::FIELD_CALLBACK_URL )(
			'http://127.0.0.1:8080/callback',
			$this->makeCallbackUrlFields( [
				Consumer::FIELD_OAUTH_VERSION => Consumer::OAUTH_VERSION_2,
				Consumer::FIELD_OAUTH2_IS_CONFIDENTIAL => true,
			] )
		);
		$this->assertStatusGood( $status );
	}

	public function testFieldCallbackUrlBareDomainOauth1GivesWarning(): void {
		// A bare domain with no path is valid but suspicious; the validator warns about it
		$status = $this->getCallback( Consumer::FIELD_CALLBACK_URL )(
			'https://example.com',
			$this->makeCallbackUrlFields( [
				Consumer::FIELD_OAUTH_VERSION => Consumer::OAUTH_VERSION_1,
				Consumer::FIELD_CALLBACK_IS_PREFIX => false,
			] )
		);
		$this->assertStatusOK( $status );
		$this->assertStatusNotGood( $status );
	}

	public function testFieldCallbackUrlBareDomainOauth2GivesWarning(): void {
		$status = $this->getCallback( Consumer::FIELD_CALLBACK_URL )(
			'https://example.com',
			$this->makeCallbackUrlFields( [
				Consumer::FIELD_OAUTH_VERSION => Consumer::OAUTH_VERSION_2,
				Consumer::FIELD_OAUTH2_IS_CONFIDENTIAL => true,
			] )
		);
		$this->assertStatusOK( $status );
		$this->assertStatusNotGood( $status );
	}

	public function testFieldCallbackUrlBareDomainWithCallbackIsPrefixOauth1IsGood(): void {
		// callbackIsPrefix=true means the bare domain is intentional (prefix match)
		$status = $this->getCallback( Consumer::FIELD_CALLBACK_URL )(
			'https://example.com',
			$this->makeCallbackUrlFields( [
				Consumer::FIELD_OAUTH_VERSION => Consumer::OAUTH_VERSION_1,
				Consumer::FIELD_CALLBACK_IS_PREFIX => true,
			] )
		);
		$this->assertStatusGood( $status );
	}

	// FIELD_DESCRIPTION

	public function testFieldDescriptionValid(): void {
		$status = $this->getCallback( Consumer::FIELD_DESCRIPTION )( 'A short description.', [] );
		$this->assertStatusGood( $status );
	}

	public function testFieldDescriptionEmpty(): void {
		$status = $this->getCallback( Consumer::FIELD_DESCRIPTION )( '', [] );
		$this->assertStatusGood( $status );
	}

	public function testFieldDescriptionTooLong(): void {
		$status = $this->getCallback( Consumer::FIELD_DESCRIPTION )(
			str_repeat( 'x', ConsumerValidator::BLOB_SIZE ), []
		);
		$this->assertStatusNotOK( $status );
	}

	// FIELD_EMAIL

	public function testFieldEmailValid(): void {
		$status = $this->getCallback( Consumer::FIELD_EMAIL )( 'user@example.com', [] );
		$this->assertStatusGood( $status );
	}

	public function testFieldEmailInvalid(): void {
		$status = $this->getCallback( Consumer::FIELD_EMAIL )( 'not-an-email', [] );
		$this->assertStatusNotOK( $status );
	}

	// FIELD_OAUTH_VERSION

	public function testFieldOauthVersionOne(): void {
		$status = $this->getCallback( Consumer::FIELD_OAUTH_VERSION )( Consumer::OAUTH_VERSION_1, [] );
		$this->assertStatusGood( $status );
	}

	public function testFieldOauthVersionTwo(): void {
		$status = $this->getCallback( Consumer::FIELD_OAUTH_VERSION )( Consumer::OAUTH_VERSION_2, [] );
		$this->assertStatusGood( $status );
	}

	public function testFieldOauthVersionInvalid(): void {
		$status = $this->getCallback( Consumer::FIELD_OAUTH_VERSION )( 3, [] );
		$this->assertStatusNotOK( $status );
	}

	// FIELD_DEVELOPER_AGREEMENT

	public function testFieldDeveloperAgreementAccepted(): void {
		$status = $this->getCallback( Consumer::FIELD_DEVELOPER_AGREEMENT )( true, [] );
		$this->assertStatusGood( $status );
	}

	public function testFieldDeveloperAgreementNotAccepted(): void {
		$status = $this->getCallback( Consumer::FIELD_DEVELOPER_AGREEMENT )( false, [] );
		$this->assertStatusNotOK( $status );
	}

	// FIELD_WIKI

	public function testFieldWikiStarAlwaysValid(): void {
		$status = $this->getCallback( Consumer::FIELD_WIKI )( '*', [] );
		$this->assertStatusGood( $status );
	}

	public function testFieldWikiCurrentWikiIsValid(): void {
		$mockSiteConfiguration = new SiteConfiguration();
		$mockSiteConfiguration->wikis = [ WikiMap::getCurrentWikiId() ];
		$this->setMwGlobals( 'wgConf', $mockSiteConfiguration );
		$status = $this->getCallback( Consumer::FIELD_WIKI )( WikiMap::getCurrentWikiId(), [] );
		$this->assertStatusGood( $status );
	}

	public function testFieldWikiNonexistentWikiIsInvalid(): void {
		$status = $this->getCallback( Consumer::FIELD_WIKI )( 'nonexistent_wiki_xyz_123', [] );
		$this->assertStatusNotOK( $status );
	}

	// FIELD_GRANTS

	public function testFieldGrantsEmptyArrayIsValid(): void {
		$status = $this->getCallback( Consumer::FIELD_GRANTS )( [], [] );
		$this->assertStatusGood( $status );
	}

	public function testFieldGrantsKnownValidGrant(): void {
		$status = $this->getCallback( Consumer::FIELD_GRANTS )( [ 'basic', 'mwoauth-authonly' ], [] );
		$this->assertStatusGood( $status );
	}

	public function testFieldGrantsJsonStringValidGrants(): void {
		$status = $this->getCallback( Consumer::FIELD_GRANTS )( '["basic"]', [] );
		$this->assertStatusGood( $status );
	}

	public function testFieldGrantsInvalidJsonStringFails(): void {
		$status = $this->getCallback( Consumer::FIELD_GRANTS )( 'not valid json {', [] );
		$this->assertStatusNotOK( $status );
	}

	public function testFieldGrantsUnrecognizedGrantNameFails(): void {
		$status = $this->getCallback( Consumer::FIELD_GRANTS )( [ 'basic', 'nonexistent-grant-xyz-abc' ], [] );
		$this->assertStatusNotOK( $status );
	}

	// FIELD_RSA_KEY

	public function testFieldRsaKeyEmptyStringIsValid(): void {
		$status = $this->getCallback( Consumer::FIELD_RSA_KEY )( '', [] );
		$this->assertStatusGood( $status );
	}

	public function testFieldRsaKeyWhitespaceOnlyIsValid(): void {
		$status = $this->getCallback( Consumer::FIELD_RSA_KEY )( "  \t  ", [] );
		$this->assertStatusGood( $status );
	}

	public function testFieldRsaKeyInvalidKeyString(): void {
		$status = $this->getCallback( Consumer::FIELD_RSA_KEY )( 'this is not a valid key', [] );
		$this->assertStatusNotOK( $status );
	}

	public function testFieldRsaKeyTooLong(): void {
		$status = $this->getCallback( Consumer::FIELD_RSA_KEY )(
			str_repeat( 'x', ConsumerValidator::BLOB_SIZE ), []
		);
		$this->assertStatusNotOK( $status );
	}

	public function testFieldRsaKeyValidRsaCertificate(): void {
		$mockRequest = $this->createNoOpMock( OAuthRequest::class );
		$cert = ( new MockOAuthSignatureMethodRsaSha1() )->fetch_public_cert( $mockRequest );
		// openssl_pkey_get_public() accepts X.509 certificates and extracts the public key
		$status = $this->getCallback( Consumer::FIELD_RSA_KEY )( $cert, [] );
		$this->assertStatusGood( $status );
	}

	public function testFieldRsaKeyNonRsaKeyIsRejected(): void {
		$ecKey = openssl_pkey_new( [
			'private_key_type' => OPENSSL_KEYTYPE_EC,
			'curve_name' => 'prime256v1',
		] );
		if ( $ecKey === false ) {
			$this->markTestSkipped( "Unable to generate key for testing: " . openssl_error_string() );
		}
		$ecPublicKey = openssl_pkey_get_details( $ecKey )['key'];
		$status = $this->getCallback( Consumer::FIELD_RSA_KEY )( $ecPublicKey, [] );
		$this->assertStatusNotOK( $status );
	}

	// FIELD_RESTRICTIONS

	public function testFieldRestrictionsValid(): void {
		$status = $this->getCallback( Consumer::FIELD_RESTRICTIONS )( '{"IPAddresses":[]}', [] );
		$this->assertStatusGood( $status );

		$status = $this->getCallback( Consumer::FIELD_RESTRICTIONS )( MWRestrictions::newDefault(), [] );
		$this->assertStatusGood( $status );
	}

	public function testFieldRestrictionsTooLong(): void {
		$status = $this->getCallback( Consumer::FIELD_RESTRICTIONS )(
			str_repeat( 'x', ConsumerValidator::BLOB_SIZE ), []
		);
		$this->assertStatusNotOK( $status );
	}

	// FIELD_OAUTH2_GRANT_TYPES

	public function testFieldOauth2GrantTypesOauth1AlwaysValid(): void {
		$fields = [ Consumer::FIELD_OAUTH_VERSION => Consumer::OAUTH_VERSION_1 ];
		$status = $this->getCallback( Consumer::FIELD_OAUTH2_GRANT_TYPES )( [], $fields );
		$this->assertStatusGood( $status );
	}

	public function testFieldOauth2GrantTypesOauth2EmptyGrantsFails(): void {
		$fields = [ Consumer::FIELD_OAUTH_VERSION => Consumer::OAUTH_VERSION_2 ];
		$status = $this->getCallback( Consumer::FIELD_OAUTH2_GRANT_TYPES )( [], $fields );
		$this->assertStatusNotOK( $status );
	}

	public function testFieldOauth2GrantTypesOauth2ValidGrant(): void {
		$fields = [ Consumer::FIELD_OAUTH_VERSION => Consumer::OAUTH_VERSION_2 ];
		$status = $this->getCallback( Consumer::FIELD_OAUTH2_GRANT_TYPES )( [ 'authorization_code' ], $fields );
		$this->assertStatusGood( $status );
	}

	public function testFieldOauth2GrantTypesTooLong(): void {
		$fields = [ Consumer::FIELD_OAUTH_VERSION => Consumer::OAUTH_VERSION_2 ];
		// Enough repetitions to exceed BLOB_SIZE when JSON-encoded
		$grants = array_fill( 0, 5000, 'authorization_code' );
		$status = $this->getCallback( Consumer::FIELD_OAUTH2_GRANT_TYPES )( $grants, $fields );
		$this->assertStatusNotOK( $status );
	}

	// expandConsumerData

	public static function provideExpandConsumerData(): iterable {
		return [
			'defaults are added' => [
				'consumerData' => self::getOAuth1ConfigurationData(),
				'expected' => [
					Consumer::FIELD_CALLBACK_IS_PREFIX => false,
					Consumer::FIELD_OWNER_ONLY => false,
					Consumer::FIELD_OAUTH2_IS_CONFIDENTIAL => true,
				],
			],
			'override works' => [
				'consumerData' => [
					Consumer::FIELD_CALLBACK_IS_PREFIX => true,
				] + self::getOAuth1ConfigurationData(),
				'expected' => [
					Consumer::FIELD_CALLBACK_IS_PREFIX => true,
				],
			],
			'no secret key required when RSA key provided'  => [
				'consumerData' => [
					Consumer::FIELD_RSA_KEY => 'this is a key',
				] + array_diff_key( self::getOAuth1ConfigurationData(), [
					Consumer::FIELD_SECRET_KEY => true,
				] ),
				'expected' => [],
			],
			'non-owner-only grant types' => [
				'consumerData' => self::getOAuth2ConfigurationData(),
				'expected' => [
					Consumer::FIELD_OAUTH2_GRANT_TYPES => [
						ClientEntity::GRANT_TYPE_AUTHORIZATION_CODE,
						ClientEntity::GRANT_TYPE_REFRESH_TOKEN,
					],
				],
			],
			'owner-only grant types' => [
				'consumerData' => self::getOAuth2OwnerOnlyConfigurationData(),
				'expected' => [
					Consumer::FIELD_OAUTH2_GRANT_TYPES => [
						ClientEntity::GRANT_TYPE_CLIENT_CREDENTIALS,
					],
				],
			],
		];
	}

	/**
	 * @dataProvider provideExpandConsumerData
	 */
	public function testExpandConsumerData( array $consumerData, array $expected ): void {
		$actual = $this->getConsumerValidator()->expandConsumerData( $consumerData );
		$this->assertArrayContains( $expected, $actual );
	}

	public static function provideExpandConsumerData_error(): iterable {
		return [
			'missing OAuth version' => [ array_diff_key(
				self::getOAuth1ConfigurationData(),
				[ Consumer::FIELD_OAUTH_VERSION => true ]
			) ],
			'invalid OAuth version' => [ [
				Consumer::FIELD_OAUTH_VERSION => 3,
			] + self::getOAuth1ConfigurationData() ],
			'unknown field' => [ [
				'noSuchKey' => true,
			] + self::getOAuth1ConfigurationData() ],
			'missing required OAuth 1 field' => [ array_diff_key(
				self::getOAuth1ConfigurationData(),
				[ Consumer::FIELD_CALLBACK_URL => true ]
			) ],
			'missing required OAuth 1 owner-only field' => [ array_diff_key(
				self::getOAuth1OwnerOnlyConfigurationData(),
				[ Consumer::FIELD_GRANTS => true ]
			) ],
			'missing required OAuth 2 field' => [ array_diff_key(
				self::getOAuth2ConfigurationData(),
				[ Consumer::FIELD_OAUTH2_GRANT_TYPES => true ]
			) ],
			'missing required OAuth 2 owner-only field' => [ array_diff_key(
				self::getOAuth2OwnerOnlyConfigurationData(),
				[ Consumer::FIELD_OAUTH2_IS_CONFIDENTIAL => true ]
			) ],
			'unexpected field for config consumers generally' => [ [
				Consumer::FIELD_REGISTRATION => '20150101000000',
			] + self::getOAuth1ConfigurationData() ],
			'unexpected field for OAuth 1' => [ [
				Consumer::FIELD_OAUTH2_IS_CONFIDENTIAL => true,
			] + self::getOAuth1ConfigurationData() ],
			'unexpected field for OAuth 1 owner-only' => [ [
				Consumer::FIELD_CALLBACK_URL => 'https://example.org',
			] + self::getOAuth1OwnerOnlyConfigurationData() ],
			'unexpected field for OAuth 2' => [ [
				Consumer::FIELD_CALLBACK_IS_PREFIX => true,
			] + self::getOAuth2ConfigurationData() ],
			'unexpected field for OAuth 2 owner-only' => [ [
				Consumer::FIELD_OAUTH2_GRANT_TYPES => [ ClientEntity::GRANT_TYPE_AUTHORIZATION_CODE ],
			] + self::getOAuth2OwnerOnlyConfigurationData() ],
			'auth code grant requires callback URL' => [ array_diff_key(
				self::getOAuth2ConfigurationData(),
				[ Consumer::FIELD_CALLBACK_URL => true ]
			) ],
			'invalid type' => [ [
				Consumer::FIELD_OAUTH_VERSION => '2',
			] + self::getOAuth2ConfigurationData() ],
			'missing secret key' => [ array_diff_key(
				self::getOAuth1ConfigurationData(),
				[ Consumer::FIELD_SECRET_KEY => true ]
			) ],
			'invalid OAuth2 grant type' => [ [
				Consumer::FIELD_OAUTH2_GRANT_TYPES => [ 'foo' ],
			] + self::getOAuth2ConfigurationData() ],
		];
	}

	/**
	 * @dataProvider provideExpandConsumerData_error
	 */
	public function testExpandConsumerData_error( array $consumerData ): void {
		$this->expectException( OAuthException::class );
		$this->getConsumerValidator()->expandConsumerData( $consumerData );
	}

}
