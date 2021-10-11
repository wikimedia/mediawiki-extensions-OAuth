<?php

namespace MediaWiki\Extensions\OAuth\Tests\Entity;

use MediaWiki\Extensions\OAuth\Entity\ClaimEntity;
use MediaWikiIntegrationTestCase;

/**
 * @group OAuth
 */
class ClaimEntityTest extends MediaWikiIntegrationTestCase {
	public function provideClaims() {
		yield 'string claim' => [
			[ 'str' => 'string' ]
		];

		yield 'number claim' => [
			[ 'num' => 9 ]
		];

		yield 'list of claims' => [
			[
				'class' => 'dummy class',
				'another_item' => [
					'num' => 8,
					'str' => 'mock'
				]
			]
		];
	}

	/**
	 * @dataProvider provideClaims
	 * @covers \MediaWiki\Extensions\OAuth\Entity\ClaimEntity::getName
	 * @covers \MediaWiki\Extensions\OAuth\Entity\ClaimEntity::getValue
	 */
	public function testProperties( $claims ) {
		foreach ( $claims as $name => $value ) {
			$claimEntity = new ClaimEntity( $name, $value );
			$this->assertEquals( $name, $claimEntity->getName() );
			$this->assertEquals( $value, $claimEntity->getValue() );
		}
	}
}
