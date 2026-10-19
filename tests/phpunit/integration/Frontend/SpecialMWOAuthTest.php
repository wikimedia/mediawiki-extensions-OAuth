<?php

namespace MediaWiki\Extension\OAuth\Tests\Integration\Frontend;

use MediaWiki\Extension\CheckUser\Services\CheckUserInsert;
use MediaWiki\Extension\OAuth\Control\ConsumerAccessControl;
use MediaWiki\Extension\OAuth\Frontend\SpecialPages\SpecialMWOAuth;
use MediaWiki\Logging\LogEntryBase;
use MediaWiki\MainConfigNames;
use MediaWiki\RecentChanges\RecentChange;
use MediaWiki\Request\FauxRequest;
use MediaWiki\Title\Title;
use MediaWiki\User\User;
use MediaWikiIntegrationTestCase;
use Wikimedia\TestingAccessWrapper;

/**
 * @covers \MediaWiki\Extension\OAuth\Frontend\SpecialPages\SpecialMWOAuth
 * @group Database
 * @group OAuth
 */
class SpecialMWOAuthTest extends MediaWikiIntegrationTestCase {

	private const CONSUMER_NAME = 'Test OAuth app';
	private const CONSUMER_KEY = 'abcdef0123456789abcdef0123456789';

	protected function setUp(): void {
		parent::setUp();

		$this->markTestSkippedIfExtensionNotLoaded( 'CheckUser' );

		$this->overrideConfigValues( [
			'CheckUserLogLogins' => true,
			'CheckUserLogSuccessfulBotLogins' => false,
		] );
	}

	private function newSpecialPage(
		?object $checkUserInsert,
		bool $wasPosted = true,
		array $params = []
	): SpecialMWOAuth {
		$services = $this->getServiceContainer();
		$specialPage = new SpecialMWOAuth(
			$services->getGrantsLocalization(),
			$services->getSkinFactory(),
			$services->getUrlUtils(),
			$checkUserInsert
		);
		$context = $specialPage->getContext();
		$context->setRequest( new FauxRequest( $params, $wasPosted ) );
		$context->setTitle( Title::newMainPage() );

		return $specialPage;
	}

	private function newConsumerAccessControl(): ConsumerAccessControl {
		$cmrAc = $this->createNoOpMock(
			ConsumerAccessControl::class,
			[ 'getName', 'getConsumerKey' ]
		);
		$cmrAc->method( 'getName' )->willReturn( self::CONSUMER_NAME );
		$cmrAc->method( 'getConsumerKey' )->willReturn( self::CONSUMER_KEY );

		return $cmrAc;
	}

	private function invokeLogOAuthAuthorizationSuccess(
		SpecialMWOAuth $specialPage,
		User $user,
		ConsumerAccessControl $cmrAc
	): void {
		TestingAccessWrapper::newFromObject( $specialPage )
			->logOAuthAuthorizationSuccess( $user, $cmrAc );
	}

	public function testLogOAuthAuthorizationSuccessSendsRecentChangeToCheckUser(): void {
		$user = $this->getTestUser()->getUser();

		$recentChanges = [];
		$checkUserInsert = $this->createMock( CheckUserInsert::class );
		$checkUserInsert->expects( $this->once() )
			->method( 'updateCheckUserData' )
			->willReturnCallback( static function ( RecentChange $recentChange ) use ( &$recentChanges ) {
				$recentChanges[] = $recentChange;
			} );

		$this->invokeLogOAuthAuthorizationSuccess(
			$this->newSpecialPage( $checkUserInsert ),
			$user,
			$this->newConsumerAccessControl()
		);

		$this->assertCount( 1, $recentChanges );
		$recentChange = $recentChanges[0];
		$this->assertSame( RecentChange::SRC_LOG, $recentChange->getAttribute( 'rc_source' ) );
		$this->assertSame( 'mwoauthconsumer', $recentChange->getAttribute( 'rc_log_type' ) );
		$this->assertSame( 'authorize', $recentChange->getAttribute( 'rc_log_action' ) );
		$this->assertSame( 0, $recentChange->getAttribute( 'rc_logid' ) );
		$this->assertSame( $user->getName(), $recentChange->getAttribute( 'rc_user_text' ) );
		$this->assertSame(
			[
				'4::consumer' => self::CONSUMER_NAME,
				'5::consumer-key' => self::CONSUMER_KEY,
			],
			LogEntryBase::extractParams( $recentChange->getAttribute( 'rc_params' ) )
		);
	}

	public function testLogOAuthAuthorizationSuccessDoesNothingWithoutCheckUserInsert(): void {
		$this->invokeLogOAuthAuthorizationSuccess(
			$this->newSpecialPage( null ),
			$this->getTestUser()->getUser(),
			$this->newConsumerAccessControl()
		);

		$this->addToAssertionCount( 1 );
	}

	public function testLogOAuthAuthorizationSuccessSendsRecentChangeOnGet(): void {
		$checkUserInsert = $this->createMock( CheckUserInsert::class );
		$recentChangeMatcher = $this->isInstanceOf( RecentChange::class );
		$checkUserInsert->expects( $this->once() )->method( 'updateCheckUserData' )
			->with( $recentChangeMatcher, true );
		$this->invokeLogOAuthAuthorizationSuccess(
			$this->newSpecialPage( $checkUserInsert, false ),
			$this->getTestUser()->getUser(),
			$this->newConsumerAccessControl()
		);
	}

	/**
	 * @dataProvider provideCheckUserLoginLoggingConfig
	 */
	public function testLogOAuthAuthorizationSuccessRespectsCheckUserLoginLoggingConfig(
		bool $logLogins,
		bool $botUser,
		bool $logBotLogins,
		int $expectedCallCount
	): void {
		$this->overrideConfigValues( [
			'CheckUserLogLogins' => $logLogins,
			'CheckUserLogSuccessfulBotLogins' => $logBotLogins,
		] );
		$checkUserInsert = $this->createMock( CheckUserInsert::class );
		$checkUserInsert->expects( $this->exactly( $expectedCallCount ) )
			->method( 'updateCheckUserData' )
			->with( $this->isInstanceOf( RecentChange::class ) );

		if ( $botUser ) {
			$this->setGroupPermissions( 'bot', 'bot', true );
			$user = $this->getTestUser( 'bot' )->getUser();
		} else {
			$user = $this->getTestUser()->getUser();
		}

		$this->invokeLogOAuthAuthorizationSuccess(
			$this->newSpecialPage( $checkUserInsert ),
			$user,
			$this->newConsumerAccessControl()
		);
	}

	public static function provideCheckUserLoginLoggingConfig(): array {
		return [
			'login logging disabled' => [ false, false, false, 0 ],
			'bot login logging disabled' => [ true, true, false, 0 ],
			'bot login logging enabled' => [ true, true, true, 1 ],
		];
	}

	/**
	 * @dataProvider provideReturnto
	 */
	public function testRedirectToRESTKeepsReturntoOnWiki( string $returnto, bool $expectLocalRedirect ): void {
		$this->overrideConfigValues( [
			MainConfigNames::Server => 'https://wiki.example.org',
			MainConfigNames::CanonicalServer => 'https://wiki.example.org',
		] );
		$specialPage = $this->newSpecialPage( null, true, [ 'returnto' => $returnto ] );

		TestingAccessWrapper::newFromObject( $specialPage )->redirectToREST();

		$location = (string)$specialPage->getContext()->getRequest()->response()->getHeader( 'Location' );
		if ( $expectLocalRedirect ) {
			$this->assertStringStartsWith( 'https://wiki.example.org/', $location );
		} else {
			$this->assertTrue(
				$location === '' || str_starts_with( $location, 'https://wiki.example.org/' ),
				"Off-wiki returnto must not become the redirect Location; got: $location"
			);
		}
	}

	public static function provideReturnto(): array {
		return [
			'off-site absolute' => [ 'https://evil.example/', false ],
			'protocol-relative' => [ '//evil.example/', false ],
			'backslash authority' => [ '\\\\evil.example', false ],
			'userinfo at host' => [ 'https://wiki.example.org@evil.example/', false ],
			'backslash userinfo bypass' => [ 'https://evil.example\\@wiki.example.org/', false ],
			'host-prefix lookalike' => [ 'https://wiki.example.org.evil.example/', false ],
			'leading whitespace' => [ "\t//evil.example", false ],
			'local path' => [ '/w/rest.php/oauth2/authorize', true ],
			'local absolute' => [ 'https://wiki.example.org/wiki/Main_Page', true ],
		];
	}
}
