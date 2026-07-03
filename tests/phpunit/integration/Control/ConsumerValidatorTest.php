<?php

namespace MediaWiki\Extension\OAuth\Tests\Integration\Control;

use MediaWiki\Config\SiteConfiguration;
use MediaWiki\Extension\OAuth\Backend\Consumer;
use MediaWiki\Extension\OAuth\Control\ConsumerValidator;
use MediaWiki\Extension\OAuth\Lib\OAuthRequest;
use MediaWiki\Extension\OAuth\Tests\MockOAuthSignatureMethodRsaSha1;
use MediaWiki\Utils\MWRestrictions;
use MediaWiki\WikiMap\WikiMap;
use MediaWikiIntegrationTestCase;

/**
 * @covers \MediaWiki\Extension\OAuth\Control\ConsumerValidator
 * @group Database
 */
class ConsumerValidatorTest extends MediaWikiIntegrationTestCase {

	private function getCallback( string $fieldName ): callable {
		return ( new ConsumerValidator() )->getValidatorCallbacks()[$fieldName];
	}

	// generic

	public function testValidateFieldsUnknownFieldReturnsError(): void {
		$status = ( new ConsumerValidator() )->validateFields( [ 'nonExistentField' => 'value' ] );
		$this->assertStatusNotOK( $status );
	}

	public function testValidateFieldsKnownFieldDoesNotReturnError(): void {
		$status = ( new ConsumerValidator() )->validateFields( [
			Consumer::FIELD_NAME => 'My App',
		] );
		$this->assertStatusGood( $status );
	}

	public function testValidateFieldsMultipleErrors(): void {
		$status = ( new ConsumerValidator() )->validateFields( [
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

}
