<?php

namespace MediaWiki\Extension\OAuth\Tests\Integration\Backend;

use MediaWiki\Extension\AbuseFilter\Variables\LazyLoadedVariable;
use MediaWiki\Extension\AbuseFilter\Variables\VariableHolder;
use MediaWiki\Extension\OAuth\Backend\AbuseFilterHookHandler;
use MediaWiki\Extension\OAuth\Backend\Utils;
use MediaWiki\Extension\OAuth\SessionProvider as OAuthSessionProvider;
use MediaWiki\RecentChanges\RecentChange;
use MediaWiki\Request\WebRequest;
use MediaWiki\Session\Session;
use MediaWiki\Session\SessionProvider;
use MediaWiki\User\User;
use MediaWikiIntegrationTestCase;

/**
 * @covers \MediaWiki\Extension\OAuth\Backend\AbuseFilterHookHandler
 * @group OAuth
 * @group Database
 */
class AbuseFilterHookHandlerTest extends MediaWikiIntegrationTestCase {

	protected function setUp(): void {
		parent::setUp();
		$this->markTestSkippedIfExtensionNotLoaded( 'Abuse Filter' );
	}

	private function getHookHandler(): AbuseFilterHookHandler {
		$services = $this->getServiceContainer();
		return new AbuseFilterHookHandler(
			$services->getChangeTagsStore(),
			$services->getConnectionProvider()
		);
	}

	/**
	 * @param SessionProvider|null $sessionProvider
	 * @param array $providerMetadata
	 * @return User
	 */
	private function getUserWithSession( ?SessionProvider $sessionProvider, array $providerMetadata = [] ): User {
		$session = $this->createMock( Session::class );
		$session->method( 'getProvider' )->willReturn( $sessionProvider );
		$session->method( 'getProviderMetadata' )->willReturn( $providerMetadata );

		$request = $this->createMock( WebRequest::class );
		$request->method( 'getSession' )->willReturn( $session );

		$user = $this->createMock( User::class );
		$user->method( 'getRequest' )->willReturn( $request );
		return $user;
	}

	public function testOnAbuseFilterBuilder() {
		$realValues = [];
		$this->getHookHandler()->onAbuseFilter_builder( $realValues );
		$this->assertSame( 'oauth-consumer', $realValues['vars']['oauth_consumer'] );
	}

	public function testOnComputeVariableIgnoresOtherMethods() {
		$result = null;
		$returnValue = $this->getHookHandler()->onAbuseFilter_computeVariable(
			'some-other-method',
			new VariableHolder(),
			[],
			$result
		);
		$this->assertTrue( $returnValue );
		$this->assertNull( $result );
	}

	public function testOnComputeVariableForOAuthSession() {
		$oauthSessionProvider = $this->getMockBuilder( OAuthSessionProvider::class )
			->disableOriginalConstructor()
			->getMock();
		$user = $this->getUserWithSession( $oauthSessionProvider, [ 'consumerId' => 1234 ] );

		$result = null;
		$returnValue = $this->getHookHandler()->onAbuseFilter_computeVariable(
			'oauth-consumer',
			new VariableHolder(),
			[ 'user' => $user, 'rc_id' => null ],
			$result
		);
		$this->assertFalse( $returnValue );
		$this->assertSame( 1234, $result );
	}

	public function testOnComputeVariableForOwnerOnlyOAuthSession() {
		$oauthSessionProvider = $this->getMockBuilder( OAuthSessionProvider::class )
			->disableOriginalConstructor()
			->getMock();
		$user = $this->getUserWithSession( $oauthSessionProvider, [ 'consumerId' => null ] );

		$result = null;
		$this->getHookHandler()->onAbuseFilter_computeVariable(
			'oauth-consumer',
			new VariableHolder(),
			[ 'user' => $user, 'rc_id' => null ],
			$result
		);
		$this->assertNull( $result );
	}

	public function testOnComputeVariableForNonOAuthSession() {
		$user = $this->getUserWithSession( $this->createMock( SessionProvider::class ) );

		$result = null;
		$this->getHookHandler()->onAbuseFilter_computeVariable(
			'oauth-consumer',
			new VariableHolder(),
			[ 'user' => $user, 'rc_id' => null ],
			$result
		);
		$this->assertNull( $result );
	}

	public static function provideOnComputeVariableForRecentChange() {
		return [
			'tagged OAuth edit' => [ [ Utils::getTagName( 1234 ), 'mw-undo' ], 1234 ],
			'untagged edit' => [ [ 'mw-undo' ], null ],
		];
	}

	/**
	 * @dataProvider provideOnComputeVariableForRecentChange
	 */
	public function testOnComputeVariableForRecentChange( array $tags, ?int $expected ) {
		$rcId = 42;
		$this->getServiceContainer()->getChangeTagsStore()->addTags( $tags, $rcId );

		// The user's own session must not be consulted for a past change
		$user = $this->createNoOpMock( User::class );

		$result = null;
		$returnValue = $this->getHookHandler()->onAbuseFilter_computeVariable(
			'oauth-consumer',
			new VariableHolder(),
			[ 'user' => $user, 'rc_id' => $rcId ],
			$result
		);
		$this->assertFalse( $returnValue );
		$this->assertSame( $expected, $result );
	}

	public static function provideOnGenerateUserVars() {
		return [
			'current action' => [ null, null ],
			'past change' => [ 42, 42 ],
		];
	}

	/**
	 * @dataProvider provideOnGenerateUserVars
	 */
	public function testOnGenerateUserVars( ?int $rcId, ?int $expectedRcIdParam ) {
		$rc = null;
		if ( $rcId !== null ) {
			$rc = $this->createMock( RecentChange::class );
			$rc->method( 'getAttribute' )->with( 'rc_id' )->willReturn( $rcId );
		}
		$user = $this->createNoOpMock( User::class );

		$vars = new VariableHolder();
		$this->getHookHandler()->onAbuseFilter_generateUserVars( $vars, $user, $rc );

		$variable = $vars->getVarThrow( 'oauth_consumer' );
		$this->assertInstanceOf( LazyLoadedVariable::class, $variable );
		$this->assertSame( 'oauth-consumer', $variable->getMethod() );
		$this->assertSame( $user, $variable->getParameters()['user'] );
		$this->assertSame( $expectedRcIdParam, $variable->getParameters()['rc_id'] );
	}
}
