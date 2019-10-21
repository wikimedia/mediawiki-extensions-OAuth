<?php

namespace MediaWiki\Extensions\OAuth\Tests;

use MediaWiki\Extensions\OAuth\AuthorizationProvider\Grant\AuthorizationCodeAccessTokens;
use MediaWiki\Extensions\OAuth\AuthorizationProvider\Grant\AuthorizationCodeAuthorization;
use MediaWiki\Extensions\OAuth\AuthorizationProvider\Grant\ClientCredentials;
use MediaWiki\Extensions\OAuth\AuthorizationProvider\Grant\RefreshToken;
use MediaWiki\Extensions\OAuth\AuthorizationProvider\IAuthorizationProvider;
use MediaWiki\Extensions\OAuth\AuthorizationServerFactory;
use MediaWiki\Extensions\OAuth\Tests\Lib\Mock_OAuthSignatureMethod_RSA_SHA1;
use MediaWiki\MediaWikiServices;
use MediaWikiTestCase;
use Psr\Log\NullLogger;
use ReflectionClass;
use User;

/**
 * @covers \MediaWiki\Extensions\OAuth\AuthorizationProvider\Grant\AuthorizationCodeAuthorization
 * @covers \MediaWiki\Extensions\OAuth\AuthorizationProvider\Grant\AuthorizationCodeAccessTokens
 * @covers \MediaWiki\Extensions\OAuth\AuthorizationProvider\Grant\ClientCredentials
 * @covers \MediaWiki\Extensions\OAuth\AuthorizationProvider\Grant\RefreshToken
 */
class AuthorizationProviderTest extends MediaWikiTestCase {

	protected function setUp() : void {
		parent::setUp();

		$signatureMethod = new Mock_OAuthSignatureMethod_RSA_SHA1();
		$request = null;
		$this->setMwGlobals( [
			'wgOAuth2PublicKey' => $signatureMethod->fetch_public_cert( $request ),
			'wgOAuth2PrivateKey' => $signatureMethod->fetch_private_cert( $request ),
			'wgOAuthSecretKey' => base64_encode( random_bytes( 32 ) )
		] );
	}

	protected function getServer() {
		$serverFactory = AuthorizationServerFactory::factory();

		return $serverFactory->getAuthorizationServer();
	}

	protected function getOAuthConfig() {
		$services = MediaWikiServices::getInstance();
		return $services->getConfigFactory()->makeConfig( 'mwoauth' );
	}

	/**
	 * @dataProvider provideGrantsArguments
	 */
	public function testSettingUser( $class ) {
		/** @var IAuthorizationProvider $authorizationProvider */
		$authorizationProvider = new $class(
			$this->getOAuthConfig(),
			$this->getServer(),
			new NullLogger()
		);

		$authReflection = new ReflectionClass( $class );
		$userProperty = $authReflection->getProperty( 'user' );
		$userProperty->setAccessible( true );

		$user = $this->getTestUser()->getUser();
		$authorizationProvider->setUser( $user );
		$actualValue = $userProperty->getValue( $authorizationProvider );
		$this->assertInstanceOf(
			User::class, $actualValue,
			"Value of user set must be an instance of " . User::class .
			", " . get_class( $actualValue ) . " found"
		);
		$this->assertSame(
			$user->getName(), $actualValue->getName(),
			"User passed to $class must be the same as the one actually set"
		);
	}

	/**
	 * @dataProvider provideGrantsArguments
	 */
	public function testGrants( $class, $grantName, $needsApproval ) {
		$server = $this->getServer();
		/** @var IAuthorizationProvider $authorizationProvider */
		$authorizationProvider = new $class(
			$this->getOAuthConfig(),
			$server,
			new NullLogger()
		);
		if ( $needsApproval ) {
			$this->assertTrue(
				$authorizationProvider->needsUserApproval(), "$class must require user approval"
			);
		} else {
			$this->assertFalse(
				$authorizationProvider->needsUserApproval(), "$class must not require user approval"
			);
		}

		// Test if the provider enabled corresponding grant on the server
		$serverReflection = new ReflectionClass( get_class( $server ) );
		$enabledGrantsProp = $serverReflection->getProperty( 'enabledGrantTypes' );
		$enabledGrantsProp->setAccessible( true );
		$enabledGrants = $enabledGrantsProp->getValue( $server );
		// In our case, each class is handling a single grant, so only that grant must be enabled
		$this->assertSame( 1, count( $enabledGrants ),
			'Authorization server must have exactly one grant enabled' );
		$this->assertArrayHasKey(
			$grantName, $enabledGrants, "Grant \"$grantName\" must be enabled for $class"
		);
	}

	public function provideGrantsArguments() {
		return [
			[ AuthorizationCodeAuthorization::class, 'authorization_code', true ],
			[ AuthorizationCodeAccessTokens::class, 'authorization_code', false ],
			[ ClientCredentials::class, 'client_credentials', false ],
			[ RefreshToken::class, 'refresh_token', false ],
		];
	}

}
