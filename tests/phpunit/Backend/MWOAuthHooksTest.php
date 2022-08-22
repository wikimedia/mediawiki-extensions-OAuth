<?php

namespace MediaWiki\Extension\OAuth\Tests\Backend;

use MediaWiki\Extension\OAuth\Backend\Hooks;
use PHPUnit\Framework\TestCase;
use Status;
use User;

/**
 * @covers \MediaWiki\Extension\OAuth\Backend\MWOAuthServer
 * @group OAuth
 */
class MWOAuthHooksTest extends TestCase {

	/**
	 * @dataProvider provideOnChangeTagCanCreate
	 */
	public function testOnChangeTagCanCreate( $tagName, $statusOk ) {
		$status = Status::newGood();
		( new Hooks )->onChangeTagCanCreate( $tagName, new User, $status );
		$this->assertSame( $statusOk, $status->isOK() );
	}

	public function provideOnChangeTagCanCreate() {
		return [
			[ 'foo', true ],
			[ 'OAuth CID', true ],
			[ 'OAuth CID:', false ],
			[ 'oauth cid:', false ],
			[ 'OAuth CID: 123', false ],
		];
	}

}
