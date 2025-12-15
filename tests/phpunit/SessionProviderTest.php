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

use MediaWiki\Extension\OAuth\SessionProvider;
use MediaWiki\RecentChanges\RecentChange;
use MediaWikiIntegrationTestCase;

/**
 * @covers \MediaWiki\Extension\OAuth\SessionProvider
 * @group OAuth
 * @group Database
 * @license GPL-2.0-or-later
 */
class SessionProviderTest extends MediaWikiIntegrationTestCase {

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
}
