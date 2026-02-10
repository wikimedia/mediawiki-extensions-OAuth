<?php

namespace MediaWiki\Extension\OAuth\Tests\Integration\Frontend;

use CentralAuthTestUser;
use ExtensionRegistry;
use MediaWiki\Config\SiteConfiguration;
use MediaWiki\Context\IContextSource;
use MediaWiki\Context\RequestContext;
use MediaWiki\Exception\ErrorPageError;
use MediaWiki\Exception\PermissionsError;
use MediaWiki\Exception\UserNotLoggedIn;
use MediaWiki\Extension\CentralAuth\User\CentralAuthUser;
use MediaWiki\Extension\OAuth\Backend\Consumer;
use MediaWiki\Extension\OAuth\Backend\OAuth1Consumer;
use MediaWiki\Extension\OAuth\Control\ConsumerSubmitControl;
use MediaWiki\Extension\OAuth\Entity\ClientEntity;
use MediaWiki\Extension\OAuth\Frontend\SpecialPages\SpecialMWOAuthConsumerRegistration;
use MediaWiki\MainConfigNames;
use MediaWiki\SpecialPage\SpecialPage;
use MediaWiki\Status\Status;
use MediaWiki\Title\Title;
use MediaWiki\User\User;
use MediaWiki\Utils\MWRestrictions;
use MediaWiki\WikiMap\WikiMap;
use MediaWikiIntegrationTestCase;
use StatusValue;

/**
 * @covers \MediaWiki\Extension\OAuth\Frontend\SpecialPages\SpecialMWOAuthConsumerRegistration
 * @group Database
 */
class SpecialMWOAuthConsumerRegistrationTest extends MediaWikiIntegrationTestCase {

	protected function setUp(): void {
		parent::setUp();
		$this->overrideConfigValues( [
			'MWOAuthCentralWiki' => 'metawiki-unittest_',
			MainConfigNames::DBname => 'metawiki',
			'MWOAuthSharedUserSource' => 'local',
		] );

		$this->setGroupPermissions( [
			'user' => [
				'mwoauthproposeconsumer' => true,
				'mwoauthupdateownconsumer' => true,
			]
		] );
	}

	/** This will mark the tests as skipped if CentralAuth is not loaded. */
	private function assertCentralAuthExtensionIsLoaded(): void {
		if ( !ExtensionRegistry::getInstance()->isLoaded( 'CentralAuth' ) ) {
			$this->markTestSkipped( 'CentralAuth is not loaded' );
		}
	}

	private function newSpecialPage(): SpecialPage {
		$services = $this->getServiceContainer();

		return new SpecialMWOAuthConsumerRegistration(
			$services->getPermissionManager(),
			$services->getGrantsInfo(),
			$services->getGrantsLocalization(),
			$services->getUrlUtils()
		);
	}

	private function createCentralUserAccount( User $user ): void {
		// Ensure we have a central user, if not, create one.
		$caUser = CentralAuthUser::getPrimaryInstanceByName( $user->getName() );
		if ( $caUser->exists() ) {
			// do nothing.
		} else {
			$centralUser = new CentralAuthTestUser(
				$user->getName(),
				'GUCP@ssword',
				[],
				[ [ WikiMap::getCurrentWikiId(), 'primary' ] ]
			);
			$centralUser->save( $this->getDb() );
		}
	}

	private function prepareRequestContext( User $user ): IContextSource {
		$user->setEmail( 'owner@wiki.domain' );
		$user->confirmEmail();
		$user->saveSettings();
		$context = RequestContext::getMain();
		$context->setUser( $user );
		$context->setTitle( Title::makeTitleSafe( NS_MAIN, 'Foo' ) );

		return $context;
	}

	/** @return OAuth1Consumer|ClientEntity|null */
	private function registerOAuthConsumer( User $user, array $data, StatusValue $expectedStatus ) {
		$this->overrideConfigValues( [
			'OAuthAutoApprove' => [ [
				'grants' => [ 'mwoauth-authonly', 'mwoauth-authonlyprivate', 'basic' ],
			] ],
		] );

		$dbw = $this->getDb();
		$control = new ConsumerSubmitControl( $this->prepareRequestContext( $user ), [], $dbw );
		$control->registerValidators( [] );
		$control->setInputParameters( $data );
		$actualStatus = $control->submit();
		$expectedStatus->isOK()
			? $this->assertStatusOK( $actualStatus )
			: $this->assertStatusNotOK( $actualStatus );
		$expectedStatus->isGood()
			? $this->assertStatusGood( $actualStatus )
			: $this->assertStatusNotOK( $actualStatus );
		$this->assertSame( $expectedStatus->isGood(), $actualStatus->isGood() );
		if ( !$expectedStatus->isGood() ) {
			$this->assertSame(
				Status::wrap( $expectedStatus )->getWikiText( false, false, 'en' ),
				Status::wrap( $actualStatus )->getWikiText( false, false, 'en' ) );
		}

		return $actualStatus->getValue()['result']['consumer'] ?? null;
	}

	public function testDoesWrite(): void {
		$this->assertTrue( $this->newSpecialPage()->doesWrites() );
	}

	public static function provideEmailStatus(): \Generator {
		yield 'Unconfirmed email: user can not execute' => [ false, false ];
		yield 'Confirmed email: user can execute' => [ true, true ];
	}

	/**
	 * @dataProvider provideEmailStatus
	 */
	public function testUserCanExecute( bool $emailStatus, bool $userCanExecute ): void {
		$user = $this->createMock( User::class );
		$user->method( 'getId' )->willReturn( 1 );
		$user->method( 'isEmailConfirmed' )->willReturn( $emailStatus );

		$this->assertSame( $userCanExecute, $this->newSpecialPage()->userCanExecute( $user ) );
	}

	public function testDisplayRestrictionError(): void {
		$specialConReg = $this->newSpecialPage();
		$user = $this->getTestSysop()->getUser();
		$context = RequestContext::getMain();
		$context->setUser( $user );
		$specialConReg->setContext( $context );

		$this->expectException( PermissionsError::class );
		$this->expectExceptionMessage( "You must confirm your email address before creating OAuth applications.\n" .
			"Please set and validate your email address through your [[Special:Preferences|user preferences]]." );

		$specialConReg->execute( 'list' );
	}

	private function setupAnotherWiki(): void {
		$conf = new SiteConfiguration();
		$conf->settings = [
			'wgServer' => [
				'enwiki' => 'http://en.example.org',
			],
			'wgArticlePath' => [
				'enwiki' => '/w/$1',
			],
		];
		$conf->suffixes = [ 'wiki' ];
		$this->setMwGlobals( 'wgConf', $conf );
		$this->overrideConfigValues( [
			MainConfigNames::LocalDatabases => [ 'enwiki' ],
			MainConfigNames::DBname => 'enwiki',
			MainConfigNames::DBprefix => ''
		] );
	}

	public function testExecuteNotOnCentralWiki(): void {
		$this->overrideConfigValue( 'MWOAuthCentralWiki', false );
		$this->setupAnotherWiki();

		$specialConReg = $this->newSpecialPage();
		$specialConReg->execute( null );

		$pageHtml = $specialConReg->getContext()->getOutput()->getHTML();
		$this->assertStringContainsString(
			'Consumers can only be managed on the central wiki', $pageHtml
		);
		// The URL in the assertion should match the wiki wgServer in the setupAnotherWiki() above.
		$this->assertStringContainsString( '>Go to this page on en.example.org<', $pageHtml );
	}

	public function testExecuteAnonUsers(): void {
		$this->expectException( UserNotLoggedIn::class );

		$specialConReg = $this->newSpecialPage();
		$specialConReg->execute( 'propose' );
	}

	public function testExecuteNoSubpage(): void {
		$specialConReg = $this->newSpecialPage();
		$user = $this->getTestSysop()->getUser();
		$specialConReg->setContext( $this->prepareRequestContext( $user ) );

		$specialConReg->execute( 'propose' );

		$pageHtml = $specialConReg->getContext()->getOutput()->getHTML();
		$this->assertStringContainsString( '>Propose an OAuth 1.0a consumer<', $pageHtml );
		$this->assertStringContainsString( '>Propose an OAuth 2.0 client<', $pageHtml );
	}

	public function testExecuteProposeWithoutProposeRights(): void {
		$this->setGroupPermissions( [
			'user' => [
				'mwoauthproposeconsumer' => false,
			]
		] );

		$specialConReg = $this->newSpecialPage();
		// This user should be non-admin, so it shouldn't have the appropriate
		// permissions.
		$user = $this->getTestUser( 'user' )->getUser();
		$specialConReg->setContext( $this->prepareRequestContext( $user ) );

		$this->expectException( PermissionsError::class );
		$specialConReg->execute( 'propose' );
	}

	/**
	 * Always redirect to Special:OAuthConsumerRegistration/propose if
	 * the given subpage to the /propose endpoint is invalid.
	 */
	public function testExecuteInvalidProposeSubpage(): void {
		$specialConReg = $this->newSpecialPage();
		$user = $this->getTestSysop()->getUser();
		$specialConReg->setContext( $this->prepareRequestContext( $user ) );

		$specialConReg->execute( 'propose/invalidSubpage' );

		$outputPage = $specialConReg->getContext()->getOutput();
		$this->assertSame( 'Special:OAuthConsumerRegistration/propose', $outputPage->getRedirect() );
	}

	public function testExecuteInvalidSpecialPageSubpage(): void {
		$specialConReg = $this->newSpecialPage();
		$user = $this->getTestSysop()->getUser();
		$specialConReg->setContext( $this->prepareRequestContext( $user ) );

		$specialConReg->execute( 'unknown' );

		$pageHtml = $specialConReg->getContext()->getOutput()->getHTML();
		$this->assertStringContainsString(
			'This page is for letting developers propose and update OAuth consumer applications',
			$pageHtml
		);
		$this->assertStringContainsString(
			'>Request a token for a new OAuth 1.0a consumer<', $pageHtml
		);
		$this->assertStringContainsString( '>Request a token for a new OAuth 2.0 client<', $pageHtml );
		$this->assertStringContainsString( '>Manage your existing consumers<', $pageHtml );
	}

	public function testExecuteListNoConsumers(): void {
		$specialConReg = $this->newSpecialPage();
		$user = $this->getTestSysop()->getUser();
		$specialConReg->setContext( $this->prepareRequestContext( $user ) );
		$specialConReg->execute( 'list' );

		$outputPage = $specialConReg->getContext()->getOutput();
		$this->assertStringContainsString(
			'>You do not control any OAuth consumers.', $outputPage->getHTML()
		);
	}

	public function testExecuteListOAuth1Consumers(): void {
		$this->assertCentralAuthExtensionIsLoaded();

		$oauth1Spec = [
			'oauthVersion' => Consumer::OAUTH_VERSION_1,
			'name' => 'test consumer',
			'version' => '1.0',
			'description' => 'test',
			'ownerOnly' => false,
			'callbackUrl' => 'https://example.com/oauth',
			'callbackIsPrefix' => false,
			'email' => 'owner@wiki.domain',
			'wiki' => '*',
			'oauth2IsConfidential' => null,
			'oauth2GrantTypes' => [],
			'granttype' => 'normal',
			'grants' => json_encode( [ 'editpage' ] ),
			'restrictions' => MWRestrictions::newDefault(),
			'rsaKey' => '',
			'agreement' => true,
			'action' => 'propose',
		];

		$user = $this->getTestSysop()->getUser();
		$this->createCentralUserAccount( $user );

		// Register consumer in the DB.
		$consumer = $this->registerOAuthConsumer( $user, $oauth1Spec, StatusValue::newGood() );
		$this->assertInstanceOf( OAuth1Consumer::class, $consumer );
		$this->assertFalse( $consumer->getOwnerOnly() );

		$specialConReg = $this->newSpecialPage();
		$specialConReg->setContext( $this->prepareRequestContext( $user ) );
		$specialConReg->execute( 'list' );

		$pageHtml = $specialConReg->getContext()->getOutput()->getHTML();
		$consumerKey = $consumer->getConsumerKey();
		$this->assertStringContainsString( $consumerKey, $pageHtml );
		$this->assertStringContainsString( '>OAuth 1.0a</td>', $pageHtml );
		$this->assertStringContainsString( '>details</a>', $pageHtml );
		$this->assertStringContainsString( '>manage</a>', $pageHtml );
		$this->assertStringContainsString( '>Contact email<', $pageHtml );
		$this->assertStringContainsString( 'proposed an OAuth consumer (consumer key', $pageHtml );
	}

	public function testExecuteListOAuth1OwnerOnlyConsumers(): void {
		$this->assertCentralAuthExtensionIsLoaded();

		$oauth1Spec = [
			'oauthVersion' => Consumer::OAUTH_VERSION_1,
			'name' => 'test consumer',
			'version' => '1.0',
			'description' => 'test',
			'ownerOnly' => true,
			'callbackUrl' => 'https://example.com/oauth',
			'callbackIsPrefix' => false,
			'email' => 'owner@wiki.domain',
			'wiki' => '*',
			'oauth2IsConfidential' => null,
			'oauth2GrantTypes' => [],
			'granttype' => 'normal',
			'grants' => json_encode( [ 'editpage' ] ),
			'restrictions' => MWRestrictions::newDefault(),
			'rsaKey' => '',
			'agreement' => true,
			'action' => 'propose',
		];

		$user = $this->getTestSysop()->getUser();
		$this->createCentralUserAccount( $user );

		// Register consumer in the DB.
		$consumer = $this->registerOAuthConsumer( $user, $oauth1Spec, StatusValue::newGood() );
		$this->assertInstanceOf( OAuth1Consumer::class, $consumer );
		$this->assertTrue( $consumer->getOwnerOnly() );

		$specialConReg = $this->newSpecialPage();
		$specialConReg->setContext( $this->prepareRequestContext( $user ) );
		$specialConReg->execute( 'list' );

		$pageHtml = $specialConReg->getContext()->getOutput()->getHTML();
		$consumerKey = $consumer->getConsumerKey();
		$this->assertStringContainsString( $consumerKey, $pageHtml );
		$this->assertStringContainsString( '>OAuth 1.0a</td>', $pageHtml );
		$this->assertStringContainsString( '>details</a>', $pageHtml );
		$this->assertStringContainsString( '>manage</a>', $pageHtml );
		$this->assertStringContainsString( '>Contact email<', $pageHtml );
		$this->assertStringContainsString( '> created an owner-only OAuth consumer (consumer key <', $pageHtml );
	}

	public function testExecuteListOAuth2Consumers(): void {
		$this->assertCentralAuthExtensionIsLoaded();

		$oauth2Spec = [
			'oauthVersion' => Consumer::OAUTH_VERSION_2,
			'name' => 'test',
			'version' => '1.0',
			'description' => 'test',
			'ownerOnly' => false,
			'callbackUrl' => 'https://example.com/oauth',
			'callbackIsPrefix' => null,
			'email' => 'owner@wiki.domain',
			'wiki' => '*',
			'oauth2IsConfidential' => true,
			'oauth2GrantTypes' => [ 'authorization_code', 'refresh_token' ],
			'granttype' => 'normal',
			'grants' => json_encode( [ 'editpage' ] ),
			'restrictions' => MWRestrictions::newDefault(),
			'rsaKey' => '',
			'agreement' => true,
			'action' => 'propose',
		];

		$user = $this->getTestSysop()->getUser();
		$this->createCentralUserAccount( $user );

		// Register consumer in the DB.
		$consumer = $this->registerOAuthConsumer( $user, $oauth2Spec, StatusValue::newGood() );
		$this->assertInstanceOf( ClientEntity::class, $consumer );
		$this->assertFalse( $consumer->getOwnerOnly() );

		$specialConReg = $this->newSpecialPage();
		$specialConReg->setContext( $this->prepareRequestContext( $user ) );
		$specialConReg->execute( 'list' );

		$pageHtml = $specialConReg->getContext()->getOutput()->getHTML();
		$consumerKey = $consumer->getConsumerKey();
		$this->assertStringContainsString( $consumerKey, $pageHtml );
		$this->assertStringContainsString( '>OAuth 2.0</td>', $pageHtml );
		$this->assertStringContainsString( '>details</a>', $pageHtml );
		$this->assertStringContainsString( '>manage</a>', $pageHtml );
		$this->assertStringContainsString( '>Contact email<', $pageHtml );
		$this->assertStringContainsString( 'proposed an OAuth consumer (consumer key', $pageHtml );
	}

	public function testExecuteListOAuth2OwnerOnlyConsumers(): void {
		$this->assertCentralAuthExtensionIsLoaded();

		$oauth2Spec = [
			'oauthVersion' => Consumer::OAUTH_VERSION_2,
			'name' => 'test',
			'version' => '1.0',
			'description' => 'test',
			'ownerOnly' => true,
			'callbackUrl' => 'https://example.com/oauth',
			'callbackIsPrefix' => null,
			'email' => 'owner@wiki.domain',
			'wiki' => '*',
			'oauth2IsConfidential' => true,
			'oauth2GrantTypes' => [ 'client_credentials' ],
			'granttype' => 'normal',
			'grants' => json_encode( [ 'editpage' ] ),
			'restrictions' => MWRestrictions::newDefault(),
			'rsaKey' => '',
			'agreement' => true,
			'action' => 'propose',
		];

		$user = $this->getTestSysop()->getUser();
		$this->createCentralUserAccount( $user );

		// Register consumer in the DB.
		$consumer = $this->registerOAuthConsumer( $user, $oauth2Spec, StatusValue::newGood() );
		$this->assertInstanceOf( ClientEntity::class, $consumer );
		$this->assertTrue( $consumer->getOwnerOnly() );

		$specialConReg = $this->newSpecialPage();
		$specialConReg->setContext( $this->prepareRequestContext( $user ) );
		$specialConReg->execute( 'list' );

		$pageHtml = $specialConReg->getContext()->getOutput()->getHTML();
		$consumerKey = $consumer->getConsumerKey();
		$this->assertStringContainsString( $consumerKey, $pageHtml );
		$this->assertStringContainsString( '>OAuth 2.0</td>', $pageHtml );
		$this->assertStringContainsString( '>details</a>', $pageHtml );
		$this->assertStringContainsString( '>manage</a>', $pageHtml );
		$this->assertStringContainsString( '>Contact email<', $pageHtml );
		$this->assertStringContainsString( '> created an owner-only OAuth consumer (consumer key <', $pageHtml );
	}

	public function testExecuteReadOnlyMode(): void {
		$this->overrideConfigValue( 'MWOAuthReadOnly', true );

		$specialConReg = $this->newSpecialPage();
		$user = $this->getTestSysop()->getUser();
		$specialConReg->setContext( $this->prepareRequestContext( $user ) );

		$this->expectException( ErrorPageError::class );
		$this->expectExceptionMessage(
			'The OAuth database is temporarily locked. Please try again in a few minutes.'
		);

		$specialConReg->execute( 'propose/oauth2' );
	}

	public function testExecuteProposeViewOAuth1(): void {
		$specialConReg = $this->newSpecialPage();
		$user = $this->getTestSysop()->getUser();
		$specialConReg->setContext( $this->prepareRequestContext( $user ) );
		$specialConReg->execute( 'propose/oauth1a' );

		$outputPage = $specialConReg->getContext()->getOutput();
		$this->assertStringContainsString(
			'Developers should use the form below to propose a new OAuth 1.0a consumer',
			$outputPage->getHTML()
		);
	}

	public function testExecuteProposeViewOAuth2(): void {
		$specialConReg = $this->newSpecialPage();
		$user = $this->getTestSysop()->getUser();
		$specialConReg->setContext( $this->prepareRequestContext( $user ) );
		$specialConReg->execute( 'propose/oauth2' );

		$outputPage = $specialConReg->getContext()->getOutput();
		$this->assertStringContainsString(
			'Developers should use the form below to request a token for a new OAuth 2.0 client',
			$outputPage->getHTML()
		);
	}

	public function testExecuteUpdateViewWithoutUpdateRights(): void {
		$this->setGroupPermissions( [
			'user' => [
				'mwoauthupdateownconsumer' => false,
			]
		] );

		$specialConReg = $this->newSpecialPage();
		// This user should be non-admin, so it shouldn't have the appropriate
		// permissions.
		$user = $this->getTestUser( 'user' )->getUser();
		$specialConReg->setContext( $this->prepareRequestContext( $user ) );

		$this->expectException( PermissionsError::class );
		$specialConReg->execute( 'update' );
	}

	public function testExecuteUpdateViewConsumerNoKey(): void {
		$specialConReg = $this->newSpecialPage();
		$user = $this->getTestSysop()->getUser();
		$specialConReg->setContext( $this->prepareRequestContext( $user ) );
		$specialConReg->execute( 'update' );

		$outputPage = $specialConReg->getContext()->getOutput();
		$this->assertStringContainsString(
			'>No consumer exists with the given key', $outputPage->getHTML()
		);
	}

	public function testExecuteUpdateViewConsumerWithKey(): void {
		$this->assertCentralAuthExtensionIsLoaded();

		$oauth2Spec = [
			'oauthVersion' => Consumer::OAUTH_VERSION_2,
			'name' => 'test',
			'version' => '1.0',
			'description' => 'test',
			'ownerOnly' => true,
			'callbackUrl' => 'https://example.com/oauth',
			'callbackIsPrefix' => null,
			'email' => 'owner@wiki.domain',
			'wiki' => '*',
			'oauth2IsConfidential' => true,
			'oauth2GrantTypes' => [ 'client_credentials' ],
			'granttype' => 'normal',
			'grants' => json_encode( [ 'editpage' ] ),
			'restrictions' => MWRestrictions::newDefault(),
			'rsaKey' => '',
			'agreement' => true,
			'action' => 'propose',
		];

		$user = $this->getTestSysop()->getUser();
		$this->createCentralUserAccount( $user );

		// Register consumer in the DB.
		$consumer = $this->registerOAuthConsumer( $user, $oauth2Spec, StatusValue::newGood() );
		$this->assertInstanceOf( ClientEntity::class, $consumer );
		$this->assertTrue( $consumer->getOwnerOnly() );

		$specialConReg = $this->newSpecialPage();
		$specialConReg->setContext( $this->prepareRequestContext( $user ) );
		$specialConReg->execute( 'list' );

		$pageHtml = $specialConReg->getContext()->getOutput()->getHTML();
		$consumerKey = $consumer->getConsumerKey();
		$this->assertStringContainsString( $consumerKey, $pageHtml );

		$specialConReg->execute( "update/$consumerKey" );
		$pageHtml = $specialConReg->getContext()->getOutput()->getHTML();
		$this->assertStringContainsString(
			'>Use the form below to update aspects of an OAuth consumer you control',
			$pageHtml
		);
		$this->assertStringContainsString( '>Update OAuth consumer application<', $pageHtml );
		$this->assertStringContainsString( '>Update consumer<', $pageHtml );
	}
}
