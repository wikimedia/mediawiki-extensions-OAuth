<?php

namespace MediaWiki\Extension\OAuth\Tests\Integration\Entity;

use MediaWiki\Extension\OAuth\Entity\ClaimEntity;
use MediaWikiIntegrationTestCase;

/**
 * @covers \MediaWiki\Extension\OAuth\Entity\ClaimEntity
 * @group OAuth
 */
class ClaimEntityTest extends MediaWikiIntegrationTestCase {
	public static function provideClaims() {
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
	 */
	public function testProperties( $claims ) {
		foreach ( $claims as $name => $value ) {
			$claimEntity = new ClaimEntity( $name, $value );
			$this->assertEquals( $name, $claimEntity->getName() );
			$this->assertEquals( $value, $claimEntity->getValue() );
		}
	}
}
