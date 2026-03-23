<?php
/**
 * @section LICENSE
 * © 2017 Wikimedia Foundation and contributors
 *
 * @license GPL-2.0-or-later
 *
 * @file
 */

namespace MediaWiki\Extension\OAuth\Tests;

use MediaWiki\Config\HashConfig;
use MediaWiki\Config\MultiConfig;
use MediaWiki\Context\RequestContext;
use MediaWiki\Extension\OAuth\Backend\Consumer;
use MediaWiki\Extension\OAuth\Backend\MWOAuthRequest;
use MediaWiki\Extension\OAuth\Backend\MWOAuthToken;
use MediaWiki\Extension\OAuth\Backend\OAuth1Consumer;
use MediaWiki\Extension\OAuth\Backend\Utils;
use MediaWiki\Extension\OAuth\Control\ConsumerAcceptanceSubmitControl;
use MediaWiki\Extension\OAuth\Control\ConsumerSubmitControl;
use MediaWiki\Extension\OAuth\Entity\ClientEntity;
use MediaWiki\Extension\OAuth\Lib\OAuthSignatureMethodHmacSha1;
use MediaWiki\Extension\OAuth\Lib\OAuthUtil;
use MediaWiki\Extension\OAuth\OAuthConfigNames;
use MediaWiki\Extension\OAuth\SessionProvider;
use MediaWiki\MainConfigNames;
use MediaWiki\RecentChanges\RecentChange;
use MediaWiki\Request\FauxRequest;
use MediaWiki\Session\CookieSessionProvider;
use MediaWiki\Session\SessionInfo;
use MediaWiki\Session\SessionManager;
use MediaWiki\Status\Status;
use MediaWiki\Tests\Mocks\Json\PlainJsonJwtCodec;
use MediaWiki\Tests\Session\SessionProviderTestTrait;
use MediaWiki\User\CentralId\CentralIdLookup;
use MediaWiki\User\User;
use MediaWiki\Utils\MWRestrictions;
use MediaWiki\WikiMap\WikiMap;
use MediaWikiIntegrationTestCase;
use Psr\Log\LogLevel;
use Psr\Log\NullLogger;
use TestLogger;
use Wikimedia\LightweightObjectStore\ExpirationAwareness;
use Wikimedia\Timestamp\ConvertibleTimestamp;

/**
 * @covers \MediaWiki\Extension\OAuth\SessionProvider
 * @group OAuth
 * @group Database
 * @license GPL-2.0-or-later
 */
class SessionProviderTest extends MediaWikiIntegrationTestCase {
	use SessionProviderTestTrait;

	public function setUp(): void {
		$this->overrideConfigValues( [
			'MWOAuthCentralWiki' => WikiMap::getCurrentWikiId(),
			'OAuthAutoApprove' => [ [
				'grants' => [ 'mwoauth-authonly', 'mwoauth-authonlyprivate', 'basic' ],
			] ],
		] );
		$this->setGroupPermissions( [
			'user' => [
				'mwoauthproposeconsumer' => true,
				'mwoauthupdateownconsumer' => true,
				'mwoauthmanageconsumer' => true,
			],
		] );
	}

	public function testSafeAgainstCsrf() {
		$provider = $this->getMockBuilder( SessionProvider::class )
			->setMethodsExcept( [ 'safeAgainstCsrf' ] )
			->disableOriginalConstructor()
			->getMock();
		$this->assertTrue( $provider->safeAgainstCsrf() );
	}

	/**
	 * @dataProvider provideOnMarkPatrolledArguments
	 */
	public function testOnMarkPatrolled( $consumerId, $auto, $expectedExtraTag ) {
		$provider = $this->getMockBuilder( SessionProvider::class )
			->onlyMethods( [ 'getPublicConsumerId' ] )
			->disableOriginalConstructor()
			->getMock();
		$provider->expects( $this->once() )
			->method( 'getPublicConsumerId' )
			->willReturn( $consumerId );

		$originalTags = [ 'Unrelated tag' ];
		$tags = $originalTags;

		$provider->onMarkPatrolled( 1, $this->getTestUser()->getUser(), false, $auto, $tags );

		if ( $expectedExtraTag === null ) {
			$this->assertSame( $originalTags, $tags );
		} else {
			$expectedTags = $originalTags;
			$expectedTags[] = $expectedExtraTag;
			$this->assertSame( $expectedTags, $tags );
		}
	}

	public static function provideOnMarkPatrolledArguments() {
		yield 'no consumer, manually patrolled' => [
			null,
			false,
			null,
		];

		yield 'no consumer, automatically patrolled' => [
			null,
			true,
			null,
		];

		yield 'consumer 123, manually patrolled' => [
			123,
			false,
			'OAuth CID: 123',
		];

		yield 'consumer 1234, automatically patrolled' => [
			1234,
			true,
			'OAuth CID: 1234',
		];
	}

	/**
	 * @dataProvider provideOnRecentChangeSave
	 */
	public function testOnRecentChangeSave( $expectedConsumerId ) {
		$provider = $this->getMockBuilder( SessionProvider::class )
			->setMethodsExcept( [ 'onRecentChange_save' ] )
			->onlyMethods( [ 'getPublicConsumerId' ] )
			->disableOriginalConstructor()
			->getMock();
		$provider->expects( $this->once() )
			->method( 'getPublicConsumerId' )
			->willReturn( $expectedConsumerId );
		$rc = $this->getMockBuilder( RecentChange::class )
			->onlyMethods( [ 'addTags', 'getPerformerIdentity' ] )
			->getMock();
		$rc->expects( $this->once() )
			->method( 'getPerformerIdentity' )
			->willReturn( $this->getTestUser()->getUser() );

		if ( $expectedConsumerId !== null ) {
			$rc->expects( $this->once() )
				->method( 'addTags' );
		}
		$this->assertTrue( $provider->onRecentChange_save( $rc ) );
	}

	public static function provideOnRecentChangeSave() {
		yield 'no consumer' => [
			null,
		];

		yield 'consumer 123' => [
			123,
		];
	}

	public function testBasics() {
		$provider = $this->getProvider();

		$this->assertInstanceOf( SessionProvider::class, $provider );
		$this->assertFalse( $provider->persistsSessionId() );
		$this->assertFalse( $provider->canChangeUser() );

		$this->assertNull( $provider->newSessionInfo() );
		$this->assertNull( $provider->newSessionInfo( 'aaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa' ) );
	}

	private function getProvider() {
		$manager = new SessionManager(
			new MultiConfig( [ $this->getConfig(), $this->getServiceContainer()->getMainConfig() ] ),
			new NullLogger,
			$this->getServiceContainer()->getCentralIdLookup(),
			$this->getServiceContainer()->getHookContainer(),
			$this->getServiceContainer()->getObjectFactory(),
			$this->getServiceContainer()->getProxyLookup(),
			$this->getServiceContainer()->getUrlUtils(),
			$this->getServiceContainer()->getUserNameUtils(),
			$this->getServiceContainer()->getSessionStore()
		);

		$this->setService( 'SessionManager', $manager );
		// Use PlainJsonJwtCodec mock so we don't run into JWT handling errors
		$this->setService( 'JwtCodec', new PlainJsonJwtCodec() );

		return $manager->getProvider( SessionProvider::class );
	}

	private function getConfig() {
		global $wgSessionProviders;

		$emptySessionProvider = array_filter(
			$wgSessionProviders,
			static fn ( $spec ) => $spec['class'] === CookieSessionProvider::class
		);
		$sessionProviders = [
				SessionProvider::class => [
					'class' => SessionProvider::class,
					'args' => [ [ 'priority' => 40,
						'sessionCookieOptions' => [],
						'isApiRequest' => true,
					] ],
					'services' => [ 'BlockManager', 'GrantsInfo' ],
				],
			] + $emptySessionProvider;

		return new HashConfig( [
			MainConfigNames::CookiePrefix => 'wgCookiePrefix',
			MainConfigNames::BlockDisablesLogin => false,
			MainConfigNames::SessionProviders => $sessionProviders,
			MainConfigNames::SessionCookieJwtExpiration => 10,
			MainConfigNames::SecretKey => 'secret',
			MainConfigNames::JwtSessionCookieIssuer => 'http://example.org',
		] );
	}

	public function testProvideSessionInfoWithoutRequestHeaders() {
		$request = new FauxRequest;
		$provider = $this->getProvider();

		// With an empty fake request above, we won't be able to get a SessionInfo object here since
		// we can't figure out the OAuth protocol version in ::provideSessionInfo(). Figuring out the
		// OAth version needs looking at the request headers and non is available for this request.
		$this->assertNull( $provider->provideSessionInfo( $request ) );
	}

	public function testOAuth1ProvideSessionInfoWithJwtCookie() {
		$centralIdMap = &$this->mockCentralIdLookup();

		$config = $this->getConfig();
		$config->set( MainConfigNames::UseSessionCookieJwt, true );
		$config->set( OAuthConfigNames::OAuthUseJwtCookie, true );
		$startTime = 1_000_000;
		ConvertibleTimestamp::setFakeTime( $startTime );
		$jwtExpiry = $config->get( MainConfigNames::SessionCookieJwtExpiration );
		// Let's ensure this issuer matches with wgMWOAuthCentralWiki which happens to
		// be a wiki ID and must match whatever URL we set here.
		$issuer = $this->getServiceContainer()->getUrlUtils()->getCanonicalServer();

		$defaultClaims = [
			'jti' => 'random123',
			'iss' => $issuer,
			'sxp' => $startTime + $jwtExpiry,
			'sub' => 'mw:mock::123',
		];
		$logger = new TestLogger( true );

		$provider = $this->getProvider();
		$this->initProvider( $provider, $logger, $config, $this->getServiceContainer()->getSessionManager() );

		$user = $this->getTestSysop()->getUser();
		$centralIdMap = [ $user->getName() => 123 ];
		$context = RequestContext::getMain();

		$consumer = $this->createOAuth1Consumer( $user );
		// Create a new request token in preparation to authorize the consumer.
		$requestToken = Utils::newMWOAuthDataStore()->new_request_token( $consumer );

		$cmrAc = new ConsumerAcceptanceSubmitControl( $context, [], $this->getDb(), $consumer->getOAuthVersion() );
		$cmrAc->registerValidators( [] );
		$cmrAc->setInputParameters( [
			'consumerKey' => $consumer->getConsumerKey(),
			'requestToken' => $requestToken->key,
			'confirmUpdate' => false,
			'action' => 'accept',
		] );

		// Authorize the consumer using the request token above.
		$res = $cmrAc->submit();
		$callbackUrl = $res->getValue()['result']['callbackUrl'];
		$params = [];
		parse_str( parse_url( $callbackUrl, PHP_URL_QUERY ), $params );

		// After authorization, create an access token using the verifier URL parameter.
		// This is the access token that will be used to make requests to the server.
		$accessToken = Utils::newMWOAuthDataStore()
			->new_access_token( $requestToken, $consumer, $params['oauth_verifier'] );

		// User with mismatching issuer
		$info = $provider->provideSessionInfo(
			$this->getRequestForOAuth1( [ 'iss' => 'http://evil.com' ] + $defaultClaims, $consumer, $accessToken )
		);
		// avoid printing a hundred-line diff when this assertion fails
		$this->assertNotNull( $info?->__toString() );
		$this->assertSame(
			[
				[ LogLevel::DEBUG, 'OAuth request for consumer {consumer} by user {user}' ],
				[ LogLevel::INFO, 'JWT validation failed: JWT error: wrong issuer' ],
			],
			$logger->getBuffer()
		);
		$logger->clearBuffer();

		// Anon JWT for non-anon user
		$info = $provider->provideSessionInfo( $this->getRequestForOAuth1(
			[ 'sub' => 'mw:' . SessionManager::JWT_SUB_ANON ] + $defaultClaims,
			$consumer,
			$accessToken
		) );
		$this->assertInstanceOf( SessionInfo::class, $info );
		$this->assertNotNull( $info?->__toString() );
		$this->assertNotEmpty( $info->getId() );
		$this->assertSame(
			[
				[ LogLevel::DEBUG, 'OAuth request for consumer {consumer} by user {user}' ],
				[ LogLevel::INFO, 'JWT validation failed: JWT error: wrong subject' ],
			],
			$logger->getBuffer()
		);
		$logger->clearBuffer();

		// User with valid JWT
		$info = $provider->provideSessionInfo( $this->getRequestForOAuth1( $defaultClaims, $consumer, $accessToken ) );
		$this->assertNotNull( $info?->__toString() );
		$this->assertNotEmpty( $info->getId() );
		$this->assertNotNull( $info->getUserInfo() );
		$this->assertSame( $user->getName(), $info->getUserInfo()->getName() );
		$this->assertTrue( $info->getUserInfo()->isVerified() );
		$this->assertFalse( $info->needsRefresh() );
		$this->assertFalse( $info->forceHTTPS() );
		$this->assertSame(
			[
				[ LogLevel::DEBUG, 'OAuth request for consumer {consumer} by user {user}' ],
			],
			$logger->getBuffer()
		);
		$logger->clearBuffer();

		// near-expired JWT
		ConvertibleTimestamp::setFakeTime( $startTime + $jwtExpiry - 1 );
		$info = $provider->provideSessionInfo( $this->getRequestForOAuth1( $defaultClaims, $consumer, $accessToken ) );
		$this->assertNotNull( $info );
		$this->assertTrue( $info->getProviderMetadata()['refreshJwtCookie'] );
		$this->assertSame(
			[
				[ LogLevel::DEBUG, 'OAuth request for consumer {consumer} by user {user}' ],
			],
			$logger->getBuffer()
		);
		$logger->clearBuffer();

		// JWT with valid hard-expiry
		ConvertibleTimestamp::setFakeTime( $startTime );
		$info = $provider->provideSessionInfo( $this->getRequestForOAuth1( $defaultClaims + [
				'exp' => $startTime + $jwtExpiry + ExpirationAwareness::TTL_MINUTE,
			], $consumer, $accessToken ) );
		$this->assertNotNull( $info );
		$this->assertNotNull( $info->getUserInfo() );
		$this->assertSame( $user->getName(), $info->getUserInfo()->getName() );
		$this->assertSame(
			[ [ LogLevel::DEBUG, 'OAuth request for consumer {consumer} by user {user}' ] ],
			$logger->getBuffer()
		);
		$logger->clearBuffer();

		// JWT with expired hard-expiry
		ConvertibleTimestamp::setFakeTime( $startTime + $jwtExpiry + ExpirationAwareness::TTL_MINUTE + 1 );
		$info = $provider->provideSessionInfo( $this->getRequestForOAuth1( $defaultClaims + [
				'exp' => $startTime + $jwtExpiry + ExpirationAwareness::TTL_MINUTE,
			], $consumer, $accessToken ) );
		$this->assertNotNull( $info?->__toString() );
		$this->assertSame(
			[
				[ LogLevel::DEBUG, 'OAuth request for consumer {consumer} by user {user}' ],
				[ LogLevel::INFO, 'JWT validation failed: The token is expired' ],
			],
			$logger->getBuffer()
		);
		$logger->clearBuffer();
	}

	public function testOAuth2ProvideSessionInfoWithJwtCookie() {
		$centralIdMap = &$this->mockCentralIdLookup();

		$config = $this->getConfig();
		// Let's ensure this issuer matches with wgMWOAuthCentralWiki which happens to
		// be a wiki ID and must match whatever URL we set here.
		$issuer = $this->getServiceContainer()->getUrlUtils()->getCanonicalServer();
		$config->set( MainConfigNames::UseSessionCookieJwt, true );
		$config->set( OAuthConfigNames::OAuthUseJwtCookie, true );
		$config->set( MainConfigNames::CanonicalServer, $issuer );
		$startTime = 1_000_000;
		ConvertibleTimestamp::setFakeTime( $startTime );
		$jwtExpiry = $config->get( MainConfigNames::SessionCookieJwtExpiration );

		$defaultClaims = [
			'jti' => 'random123',
			'iss' => $issuer,
			'sxp' => $startTime + $jwtExpiry,
			'sub' => 'mw:mock::123',
		];
		$logger = new TestLogger( true );

		$provider = $this->getProvider();
		$this->initProvider( $provider, $logger, $config, $this->getServiceContainer()->getSessionManager() );

		$user = $this->getTestSysop()->getUser();
		$centralIdMap = [ $user->getName() => 123 ];

		$status = $this->createOAuth2Consumer( $user );
		$accessToken = $status->getValue()['result']['accessToken'];

		// User with mismatching issuer
		$info = $provider->provideSessionInfo(
			$this->getRequestForOAuth2( [ 'iss' => 'http://evil.com' ] + $defaultClaims,
				(string)$accessToken
			)
		);
		// avoid printing a hundred-line diff when this assertion fails
		$this->assertNotNull( $info?->__toString() );
		$this->assertSame(
			[
				[ LogLevel::DEBUG, 'OAuth request for consumer {consumer} by user {user}' ],
				[ LogLevel::INFO, 'JWT validation failed: JWT error: wrong issuer' ],
			],
			$logger->getBuffer()
		);
		$logger->clearBuffer();

		// Anon JWT for non-anon user
		$info = $provider->provideSessionInfo( $this->getRequestForOAuth2(
			[ 'sub' => 'mw:' . SessionManager::JWT_SUB_ANON ] + $defaultClaims,
			$accessToken
		) );
		$this->assertInstanceOf( SessionInfo::class, $info );
		$this->assertNotNull( $info?->__toString() );
		$this->assertNotEmpty( $info->getId() );
		$this->assertSame(
			[
				[ LogLevel::DEBUG, 'OAuth request for consumer {consumer} by user {user}' ],
				[ LogLevel::INFO, 'JWT validation failed: JWT error: wrong subject' ],
			],
			$logger->getBuffer()
		);
		$logger->clearBuffer();

		// User with valid JWT
		$info = $provider->provideSessionInfo( $this->getRequestForOAuth2( $defaultClaims, $accessToken ) );
		$this->assertNotNull( $info?->__toString() );
		$this->assertNotEmpty( $info->getId() );
		$this->assertNotNull( $info->getUserInfo() );
		$this->assertSame( $user->getName(), $info->getUserInfo()->getName() );
		$this->assertTrue( $info->getUserInfo()->isVerified() );
		$this->assertFalse( $info->needsRefresh() );
		$this->assertFalse( $info->forceHTTPS() );
		$this->assertSame(
			[
				[ LogLevel::DEBUG, 'OAuth request for consumer {consumer} by user {user}' ],
			],
			$logger->getBuffer()
		);
		$logger->clearBuffer();

		// near-expired JWT
		ConvertibleTimestamp::setFakeTime( $startTime + $jwtExpiry - 1 );
		$info = $provider->provideSessionInfo( $this->getRequestForOAuth2( $defaultClaims, $accessToken ) );
		$this->assertNotNull( $info );
		$this->assertTrue( $info->getProviderMetadata()['refreshJwtCookie'] );
		$this->assertSame(
			[
				[ LogLevel::DEBUG, 'OAuth request for consumer {consumer} by user {user}' ],
			],
			$logger->getBuffer()
		);
		$logger->clearBuffer();

		// JWT with valid hard-expiry
		ConvertibleTimestamp::setFakeTime( $startTime );
		$info = $provider->provideSessionInfo( $this->getRequestForOAuth2( $defaultClaims + [
				'exp' => $startTime + $jwtExpiry + ExpirationAwareness::TTL_MINUTE,
			], $accessToken ) );
		$this->assertNotNull( $info );
		$this->assertNotNull( $info->getUserInfo() );
		$this->assertSame( $user->getName(), $info->getUserInfo()->getName() );
		$this->assertSame(
			[ [ LogLevel::DEBUG, 'OAuth request for consumer {consumer} by user {user}' ] ],
			$logger->getBuffer()
		);
		$logger->clearBuffer();

		// JWT with expired hard-expiry
		ConvertibleTimestamp::setFakeTime( $startTime + $jwtExpiry + ExpirationAwareness::TTL_MINUTE + 1 );
		$info = $provider->provideSessionInfo( $this->getRequestForOAuth2( $defaultClaims + [
				'exp' => $startTime + $jwtExpiry + ExpirationAwareness::TTL_MINUTE,
			], $accessToken ) );
		$this->assertNotNull( $info?->__toString() );
		$this->assertSame(
			[
				[ LogLevel::DEBUG, 'OAuth request for consumer {consumer} by user {user}' ],
				[ LogLevel::INFO, 'JWT validation failed: The token is expired' ],
			],
			$logger->getBuffer()
		);
		$logger->clearBuffer();
	}

	public function testSessionProviderEmitsJwtCookie() {
		$centralIdMap = &$this->mockCentralIdLookup();

		$config = $this->getConfig();
		$config->set( MainConfigNames::UseSessionCookieJwt, true );
		$config->set( OAuthConfigNames::OAuthUseJwtCookie, true );
		$config->set(
			MainConfigNames::CanonicalServer,
			$this->getServiceContainer()->getUrlUtils()->getCanonicalServer()
		);

		$provider = $this->getProvider();
		$this->initProvider(
			$provider,
			new TestLogger( true ),
			$config,
			$this->getServiceContainer()->getSessionManager()
		);

		$user = $this->getTestSysop()->getUser();
		$centralIdMap = [ $user->getName() => 123 ];
		$context = RequestContext::getMain();

		// -- OAuth1 emission of sessionJwt cookie --
		$consumer = $this->createOAuth1Consumer( $user );
		// Create a new request token in preparation to authorize the consumer.
		$requestToken = Utils::newMWOAuthDataStore()->new_request_token( $consumer );

		$cmrAc = new ConsumerAcceptanceSubmitControl( $context, [], $this->getDb(), $consumer->getOAuthVersion() );
		$cmrAc->registerValidators( [] );
		$cmrAc->setInputParameters( [
			'consumerKey' => $consumer->getConsumerKey(),
			'requestToken' => $requestToken->key,
			'confirmUpdate' => false,
			'action' => 'accept',
		] );

		// Authorize the consumer using the request token above.
		$res = $cmrAc->submit();
		$callbackUrl = $res->getValue()['result']['callbackUrl'];
		$params = [];
		parse_str( parse_url( $callbackUrl, PHP_URL_QUERY ), $params );

		// After authorization, create an access token using the verifier URL parameter.
		// This is the access token that will be used to make requests to the server.
		$accessToken = Utils::newMWOAuthDataStore()
			->new_access_token( $requestToken, $consumer, $params['oauth_verifier'] );

		// Confirm we don't have any sessionJwt set by now.
		$requestOAuth1 = $this->getOAuth1RequestWithNoJwtHeader( $consumer, $accessToken );
		$responseOAuth1 = $requestOAuth1->response();
		$this->assertArrayNotHasKey( 'sessionJwt', $responseOAuth1->getCookies() );

		$info = $provider->provideSessionInfo( $requestOAuth1 );
		$info->getProvider()->sessionWasAttachedToRequest( $info, $requestOAuth1 );

		$responseOAuth1 = RequestContext::getMain()->getRequest()->response();
		$this->assertArrayHasKey( 'sessionJwt', $responseOAuth1->getCookies() );

		// -- OAuth2-owner-only emission of sessionJwt cookie --
		$status = $this->createOAuth2Consumer( $user );
		$accessToken = $status->getValue()['result']['accessToken'];

		$requestOAuth2 = $this->getOAuth2RequestWithNoJwtHeader( $accessToken );
		$responseOAuth2 = $requestOAuth2->response();
		$this->assertArrayNotHasKey( 'sessionJwt', $responseOAuth2->getCookies() );

		$info = $provider->provideSessionInfo( $requestOAuth2 );
		$info->getProvider()->sessionWasAttachedToRequest( $info, $requestOAuth2 );

		$responseOAuth2 = RequestContext::getMain()->getRequest()->response();
		$this->assertArrayHasKey( 'sessionJwt', $responseOAuth2->getCookies() );
	}

	public function testProvideSessionInfoWithBadRequest() {
		$centralIdMap = &$this->mockCentralIdLookup();

		$config = $this->getConfig();
		$config->set( MainConfigNames::UseSessionCookieJwt, true );
		$config->set( OAuthConfigNames::OAuthUseJwtCookie, true );

		$logger = new TestLogger( true );
		$provider = $this->getProvider();
		$this->initProvider( $provider, $logger, $config, $this->getServiceContainer()->getSessionManager() );

		$user = $this->getTestSysop()->getUser();
		$centralIdMap = [ $user->getName() => 123 ];
		$context = RequestContext::getMain();

		$consumer = $this->createOAuth1Consumer( $user );
		// Create a new request token in preparation to authorize the consumer.
		$requestToken = Utils::newMWOAuthDataStore()->new_request_token( $consumer );

		$cmrAc = new ConsumerAcceptanceSubmitControl( $context, [], $this->getDb(), $consumer->getOAuthVersion() );
		$cmrAc->registerValidators( [] );
		$cmrAc->setInputParameters( [
			'consumerKey' => $consumer->getConsumerKey(),
			'requestToken' => $requestToken->key,
			'confirmUpdate' => false,
			'action' => 'accept',
		] );

		// Authorize the consumer using the request token above.
		$res = $cmrAc->submit();
		$callbackUrl = $res->getValue()['result']['callbackUrl'];
		$params = [];
		parse_str( parse_url( $callbackUrl, PHP_URL_QUERY ), $params );

		// After authorization, create an access token using the verifier URL parameter.
		// This is the access token that will be used to make requests to the server.
		$accessToken = Utils::newMWOAuthDataStore()
			->new_access_token( $requestToken, $consumer, $params['oauth_verifier'] );

		$request = new FauxRequest();
		// This request doesn't have sufficient request headers, but at least it has a header
		// to detect the protocol version, which is enough to test a bad request path for OAuth1.
		// The request doesn't have an OAuth signature, so it's a bad request during validation.
		$request->setHeader(
			'Authorization',
			'OAuth oauth_version="1.0", oauth_signature_method="HMAC-SHA1",' .
			'oauth_consumer_key="' . $consumer->getConsumerKey() . '", oauth_token="' . $accessToken->key . '"'
		);

		$info = $provider->provideSessionInfo( $request );
		$this->assertNotNull( $info?->__toString() );
		$this->assertSame(
			[ [ LogLevel::INFO, 'Bad OAuth request from {ip}' ] ],
			$logger->getBuffer()
		);
		$logger->clearBuffer();

		// OAuth2 owner-only
		$this->createOAuth2Consumer( $user );

		$request = new FauxRequest();
		$request->setHeader( 'Authorization', 'Bearer badtoken' );
		$info = $provider->provideSessionInfo( $request );

		$this->assertNotNull( $info?->__toString() );
		$this->assertSame(
			[ [ LogLevel::INFO, 'Bad OAuth request from {ip}' ] ],
			$logger->getBuffer()
		);
		$logger->clearBuffer();
	}

	private function &mockCentralIdLookup(): array {
		$centralIdMap = [];
		// the class is abstract but the mocked methods aren't and that apparently breaks createNoOpAbstractMock
		$lookup = $this->createNoOpMock( CentralIdLookup::class,
			[ 'lookupOwnedUserNames', 'getScope', 'getProviderId' ] );
		$lookup->method( 'lookupOwnedUserNames' )->willReturnCallback(
			static function ( $nameToId ) use ( &$centralIdMap ) {
				return array_intersect_key( $centralIdMap, $nameToId );
			}
		);
		$lookup->method( 'getScope' )->willReturn( 'mock:' );
		$lookup->method( 'getProviderId' )->willReturn( 'mock' );
		$this->setService( 'CentralIdLookup', $lookup );
		return $centralIdMap;
	}

	/**
	 * A request object that doesn't have a JWT cookie set for
	 * OAuth1 requests.
	 *
	 * @param OAuth1Consumer $consumer
	 * @param MWOAuthToken $accessToken
	 *
	 * @return FauxRequest
	 */
	private function getOAuth1RequestWithNoJwtHeader( OAuth1Consumer $consumer, MWOAuthToken $accessToken ) {
		$context = RequestContext::getMain();
		$request = new FauxRequest();
		$request->setRequestURL( '' );
		$timestamp = time();
		$nonce = md5( microtime() . mt_rand() );

		// Prepare to sign the request server-side.
		$request->setHeader(
			'Authorization',
			'OAuth oauth_nonce="' . $nonce . '", oauth_timestamp="' . $timestamp .
			'", oauth_version="1.0", oauth_signature_method="HMAC-SHA1", oauth_consumer_key="'
			. $consumer->getConsumerKey() . '", oauth_token="' . $accessToken->key . '"'
		);

		$context->setRequest( $request );
		$oauthRequest = MWOAuthRequest::fromRequest( $request );
		$sigMethod = new OAuthSignatureMethodHmacSha1();

		// OAuth request would encode the signature when doing validation checks,
		// match that here so that the signature is consistent.
		$signature = OAuthUtil::urlencode_rfc3986(
			$oauthRequest->build_signature( $sigMethod, $consumer, $accessToken )
		);

		$request->setHeader(
			'Authorization',
			'OAuth oauth_nonce="' . $nonce . '", oauth_timestamp="' . $timestamp .
			'", oauth_version="1.0", oauth_signature_method="HMAC-SHA1", oauth_consumer_key="' .
			$consumer->getConsumerKey() . '", oauth_token="' . $accessToken->key .
			'", oauth_signature="' . $signature . '"'
		);

		$context->setRequest( $request );
		return $request;
	}

	/**
	 * Get OAuth2 request with necessary headers and without sessionJwt cookie.
	 *
	 * @param string $accessToken
	 *
	 * @return FauxRequest
	 */
	private function getOAuth2RequestWithNoJwtHeader( string $accessToken ) {
		$context = RequestContext::getMain();
		$request = new FauxRequest();
		$request->setRequestURL( '' );

		$request->setHeader( 'Authorization', 'Bearer ' . $accessToken );

		$context->setRequest( $request );
		return $request;
	}

	/**
	 * Get OAuth1 request with necessary headers and sessionJwt cookie.
	 *
	 * @param array $claims
	 * @param OAuth1Consumer $consumer
	 * @param MWOAuthToken $accessToken
	 *
	 * @return FauxRequest
	 */
	private function getRequestForOAuth1( array $claims, OAuth1Consumer $consumer, MWOAuthToken $accessToken ) {
		$codec = new PlainJsonJwtCodec();
		$context = RequestContext::getMain();
		$request = new FauxRequest();
		$request->setRequestURL( '' );
		$timestamp = time();
		$nonce = md5( microtime() . mt_rand() );
		$request->setCookies( [
			'sessionJwt' => $codec->create( $claims ),
		], prefix: '' );

		// Prepare to sign the request server-side.
		$request->setHeader(
			'Authorization',
			'OAuth oauth_nonce="' . $nonce . '", oauth_timestamp="' . $timestamp .
			'", oauth_version="1.0", oauth_signature_method="HMAC-SHA1", oauth_consumer_key="'
			. $consumer->getConsumerKey() . '", oauth_token="' . $accessToken->key . '"'
		);

		$context->setRequest( $request );
		$oauthRequest = MWOAuthRequest::fromRequest( $request );
		$sigMethod = new OAuthSignatureMethodHmacSha1();

		// OAuth request would encode the signature when doing validation checks,
		// match that here so that the signature is consistent.
		$signature = OAuthUtil::urlencode_rfc3986(
			$oauthRequest->build_signature( $sigMethod, $consumer, $accessToken )
		);

		$request->setHeader(
			'Authorization',
			'OAuth oauth_nonce="' . $nonce . '", oauth_timestamp="' . $timestamp .
			'", oauth_version="1.0", oauth_signature_method="HMAC-SHA1", oauth_consumer_key="' .
			$consumer->getConsumerKey() . '", oauth_token="' . $accessToken->key .
			'", oauth_signature="' . $signature . '"'
		);

		$context->setRequest( $request );
		return $request;
	}

	/**
	 * Get OAuth2 owner-only request with necessary headers and sessionJwt cookie.
	 *
	 * @param array $claims
	 * @param string $accessToken
	 *
	 * @return FauxRequest
	 */
	private function getRequestForOAuth2( array $claims, string $accessToken ) {
		$codec = new PlainJsonJwtCodec();
		$context = RequestContext::getMain();
		$request = new FauxRequest();
		$request->setRequestURL( '' );
		$request->setCookies( [
			'sessionJwt' => $codec->create( $claims ),
		], prefix: '' );

		$request->setHeader( 'Authorization', 'Bearer ' . $accessToken );

		$context->setRequest( $request );
		return $request;
	}

	/**
	 * Create and approve OAuth1 consumer.
	 * @return OAuth1Consumer
	 */
	private function createOAuth1Consumer( User $user ) {
		$context = RequestContext::getMain();
		$user->setEmail( 'owner@wiki.domain' );
		$user->confirmEmail();
		$user->saveSettings();
		$context->setUser( $user );

		$dbw = $this->getDb();
		$control = new ConsumerSubmitControl( $context, [], $dbw );
		$control->registerValidators( [] );
		$control->setInputParameters( [
			'oauthVersion' => Consumer::OAUTH_VERSION_1,
			'name' => 'OAuth1 consumer',
			'version' => '1.0',
			'description' => 'test',
			'ownerOnly' => false,
			'callbackUrl' => 'http://example.com/callback',
			'callbackIsPrefix' => false,
			'email' => 'owner@wiki.domain',
			'wiki' => '*',
			'oauth2IsConfidential' => null,
			'oauth2GrantTypes' => [],
			'granttype' => 'normal',
			'grants' => json_encode( [ 'basic', 'editpage' ] ),
			'restrictions' => MWRestrictions::newDefault(),
			'rsaKey' => '',
			'agreement' => true,
			'action' => 'propose',
		] );
		$actualStatus = $control->submit();
		/** @var OAuth1Consumer $consumer */
		$consumer = $actualStatus->getValue()['result']['consumer'];
		$this->assertInstanceOf( OAuth1Consumer::class, $consumer );

		$control = new ConsumerSubmitControl( $context, [], $dbw );
		$control->registerValidators( [] );
		$control->setInputParameters( [
			'consumerKey' => $consumer->getConsumerKey(),
			'reason' => 'foo bar',
			'changeToken' => $consumer->getChangeToken( $context ),
			'action' => 'approve',
		] );
		$actualStatus = $control->submit();

		return $actualStatus->getValue()['result'];
	}

	/**
	 * Create and approve OAuth2 consumer.
	 * @return Status
	 */
	private function createOAuth2Consumer( User $user ) {
		$context = RequestContext::getMain();
		$user->setEmail( 'owner@wiki.domain' );
		$user->confirmEmail();
		$user->saveSettings();
		$context->setUser( $user );

		$dbw = $this->getDb();
		$control = new ConsumerSubmitControl( $context, [], $dbw );
		$control->registerValidators( [] );
		$control->setInputParameters( [
			'oauthVersion' => Consumer::OAUTH_VERSION_2,
			'name' => 'OAuth2 owner-only consumer',
			'version' => '1.0',
			'description' => 'test',
			'ownerOnly' => true,
			'callbackUrl' => 'https://example.com/callback',
			'callbackIsPrefix' => false,
			'email' => 'owner@wiki.domain',
			'wiki' => '*',
			'oauth2IsConfidential' => true,
			'oauth2GrantTypes' => [ 'client_credentials' ],
			'granttype' => 'normal',
			'grants' => json_encode( [ 'basic', 'editpage' ] ),
			'restrictions' => MWRestrictions::newDefault(),
			'rsaKey' => '',
			'agreement' => true,
			'action' => 'propose',
		] );

		// NOTE: Owner-only consumers are approved on submission automatically, so no extra
		// approval step is needed.
		$actualStatus = $control->submit();
		/** @var ClientEntity $consumer */
		$consumer = $actualStatus->getValue()['result']['consumer'];
		$this->assertInstanceOf( ClientEntity::class, $consumer );

		return $actualStatus;
	}
}
