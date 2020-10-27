<?php

namespace MediaWiki\Extensions\OAuth\Tests;

use DateTime;
use MediaWiki\Extensions\OAuth\AuthorizationProvider\AuthorizationProvider;
use MediaWiki\Extensions\OAuth\AuthorizationProvider\Grant\AuthorizationCodeAccessTokens;
use MediaWiki\Extensions\OAuth\AuthorizationProvider\Grant\AuthorizationCodeAuthorization;
use MediaWiki\Extensions\OAuth\AuthorizationProvider\Grant\ClientCredentials;
use MediaWiki\Extensions\OAuth\AuthorizationProvider\Grant\RefreshToken;
use MediaWiki\Extensions\OAuth\AuthorizationProvider\IAuthorizationProvider;
use MediaWiki\Extensions\OAuth\AuthorizationServerFactory;
use MediaWiki\MediaWikiServices;
use MediaWikiTestCase;
use Psr\Log\NullLogger;
use ReflectionClass;
use User;
use Wikimedia\TestingAccessWrapper;

/**
 * @covers \MediaWiki\Extensions\OAuth\AuthorizationProvider\Grant\AuthorizationCodeAuthorization
 * @covers \MediaWiki\Extensions\OAuth\AuthorizationProvider\Grant\AuthorizationCodeAccessTokens
 * @covers \MediaWiki\Extensions\OAuth\AuthorizationProvider\Grant\ClientCredentials
 * @covers \MediaWiki\Extensions\OAuth\AuthorizationProvider\Grant\RefreshToken
 * @group OAuth
 */
class AuthorizationProviderTest extends MediaWikiTestCase {

	protected function setUp() : void {
		parent::setUp();

		$this->setMwGlobals( [
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

	/**
	 * @dataProvider provideExpirationInterval
	 * @param string $global Value for setting
	 * @param int $expect Expected DateTimeInterval->getTimestamp()
	 */
	public function testGetGrantExpirationInterval( $global, $expect ) {
		$this->setMwGlobals( [ 'wgOAuth2GrantExpirationInterval' => $global ] );

		$server = $this->getServer();
		/** @var IAuthorizationProvider $authorizationProvider */
		$authorizationProvider = $this->getMockBuilder( AuthorizationProvider::class )
			->setConstructorArgs( [
				$this->getOAuthConfig(),
				$server,
				new NullLogger()
			] )
			->getMockForAbstractClass();

		$interval = TestingAccessWrapper::newFromObject( $authorizationProvider )
			->getGrantExpirationInterval();

		// No way to get the interval directly, so add it to a 0 timestamp then extract the timestamp...
		$actual = ( new DateTime( '@0' ) )->add( $interval )->getTimestamp();

		$this->assertSame( $expect, $actual );
	}

	/**
	 * @dataProvider provideExpirationInterval
	 * @param string $global Value for setting
	 * @param int $expect Expected DateTimeInterval->getTimestamp()
	 */
	public function testGetRefreshTokenTTL( $global, $expect ) {
		$this->setMwGlobals( [ 'wgOAuth2RefreshTokenTTL' => $global ] );

		$server = $this->getServer();
		/** @var IAuthorizationProvider $authorizationProvider */
		$authorizationProvider = $this->getMockBuilder( AuthorizationProvider::class )
			->setConstructorArgs( [
				$this->getOAuthConfig(),
				$server,
				new NullLogger()
			] )
			->getMockForAbstractClass();

		$interval = TestingAccessWrapper::newFromObject( $authorizationProvider )
			->getRefreshTokenTTL();

		// No way to get the interval directly, so add it to a 0 timestamp then extract the timestamp...
		$actual = ( new DateTime( '@0' ) )->add( $interval )->getTimestamp();

		$this->assertSame( $expect, $actual );
	}

	public function provideExpirationInterval() {
		return [
			[ 'P30D', 2592000 ],
			[ false, 9223371259704000000 ],
			[ 'infinity', 9223371259704000000 ],
		];
	}

}
