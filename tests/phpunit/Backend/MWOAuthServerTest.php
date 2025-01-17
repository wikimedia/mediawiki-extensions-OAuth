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

namespace MediaWiki\Extension\OAuth\Tests\Backend;

use MediaWiki\Extension\OAuth\Backend\Consumer;
use MediaWiki\Extension\OAuth\Backend\MWOAuthException;
use MediaWiki\Extension\OAuth\Backend\MWOAuthServer;
use PHPUnit\Framework\TestCase;
use ReflectionMethod;

/**
 * @covers \MediaWiki\Extension\OAuth\Backend\MWOAuthServer
 * @group OAuth
 */
class MWOAuthServerTest extends TestCase {

	/**
	 * @param bool $expect Expectation
	 * @param string $registeredUrl Registered callback URL
	 * @param string $got Request callback URL
	 * @param bool $isPrefix Is Callback prefix?
	 * @dataProvider provideCheckCallback
	 */
	public function testCheckCallback( $expect, $registeredUrl, $got, $isPrefix = true ) {
		$fixture = new MWOAuthServer( null );
		$consumer = $this->createMock( Consumer::class );
		$consumer->method( 'getConsumerKey' )->willReturn( '1234567890abcdef' );
		$consumer->method( 'getName' )->willReturn( 'test' );
		$consumer->method( 'getCallbackIsPrefix' )->willReturn( $isPrefix );
		$consumer->method( 'getCallbackUrl' )->willReturn( $registeredUrl );

		$method = new ReflectionMethod( $fixture, 'checkCallback' );
		$method->setAccessible( true );
		$wasValid = null;
		try {
			$method->invoke( $fixture, $consumer, $got );
			$wasValid = true;
		} catch ( MWOAuthException $e ) {
			$wasValid = false;
		}
		$this->assertSame( $expect, $wasValid );
	}

	public static function provideCheckCallback() {
		return [
			// [ $expect, $registeredUrl, $got, $isPrefix=true ]
			[ true, '', 'oob', false ],
			[ false, 'https://host', 'https://host', false ],
			[ true, 'https://host', 'oob' ],

			[ true, 'https://host', 'https://host' ],
			[ true, 'http://host', 'https://host' ],
			[ true, 'https://host:1234', 'https://host:1234' ],
			[ true, 'http://host:1234', 'https://host:1234' ],
			[ true, 'https://host:1', 'https://host:1234' ],
			[ true, 'https://host:1', 'https://host' ],
			[ true, 'https://host', 'https://host/path?query' ],
			[ true, 'http://host', 'https://host/path?query' ],
			[ true, 'https://host/path', 'https://host/path?query' ],
			[ true, 'https://host/path?query', 'https://host/path?query' ],
			[ true, 'https://host/path', 'https://host/path/dir2' ],
			[ true, 'https://host/path?query', 'https://host/path?query&more' ],

			[ false, 'https://host/', 'https://host' ],
			[ false, 'https://host', 'https://host:1234' ],
			[ false, 'https://host:4321', 'https://host:1234' ],
			[ false, 'https://host:80', 'https://host:8099' ],
			[ false, 'https://host', 'https://host:1' ],
			[ false, 'https://host/path', 'https://host:1234/path' ],
			[ false, 'https://host/path?query', 'https://host/path' ],
			[ false, 'https://host:8000', 'https://host:8000@evil.com' ],
			[ false, 'https://host', 'https://hosting' ],
		];
	}
}
