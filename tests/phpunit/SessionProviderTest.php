<?php
/**
 * @section LICENSE
 * © 2017 Wikimedia Foundation and contributors
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License along
 * with this program; if not, write to the Free Software Foundation, Inc.,
 * 51 Franklin Street, Fifth Floor, Boston, MA 02110-1301, USA.
 * http://www.gnu.org/copyleft/gpl.html
 *
 * @file
 */

namespace MediaWiki\Extensions\OAuth\Tests;

use MediaWiki\Extensions\OAuth\SessionProvider;
use MediaWikiTestCase;
use RecentChange;

/**
 * @covers \MediaWiki\Extensions\OAuth\SessionProvider
 * @group OAuth
 * @license GPL-2.0-or-later
 */
class SessionProviderTest extends MediaWikiTestCase {

	protected function setUp() : void {
		parent::setUp();
		// the SessionProvider constructor modifies $wgHooks, stash it
		global $wgHooks;
		$this->setMwGlobals( 'wgHooks', $wgHooks );
	}

	public function testSafeAgainstCsrf() {
		$provider = $this->getMockBuilder( SessionProvider::class )
			->setMethodsExcept( [ 'safeAgainstCsrf' ] )
			->getMock();
		$this->assertTrue( $provider->safeAgainstCsrf() );
	}

	/**
	 * @dataProvider provideOnMarkPatrolledArguments
	 */
	public function testOnMarkPatrolled( $consumerId, $auto, $expectedExtraTag ) {
		$provider = $this->getMockBuilder( SessionProvider::class )
			->onlyMethods( [ 'getPublicConsumerId' ] )
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

	public function provideOnMarkPatrolledArguments() {
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

	public function provideOnRecentChangeSave() {
		yield 'no consumer' => [
			null,
		];

		yield 'consumer 123' => [
			123,
		];
	}
}
