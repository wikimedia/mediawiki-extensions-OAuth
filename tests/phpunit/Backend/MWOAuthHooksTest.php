<?php

namespace MediaWiki\Extensions\OAuth\Tests\Backend;

use MediaWiki\Extensions\OAuth\MWOAuthHooks;
use PHPUnit\Framework\TestCase;
use Status;
use User;

/**
 * @covers \MediaWiki\Extensions\OAuth\MWOAuthServer
 */
class MWOAuthHooksTest extends TestCase {

	/**
	 * @dataProvider provideOnChangeTagCanCreate
	 */
	public function testOnChangeTagCanCreate( $tagName, $statusOk ) {
		$status = Status::newGood();
		MWOAuthHooks::onChangeTagCanCreate( $tagName, new User, $status );
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
