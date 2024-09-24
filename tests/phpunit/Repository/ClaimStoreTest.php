<?php

namespace MediaWiki\Extension\OAuth\Tests\Repository;

use League\OAuth2\Server\Entities\ClientEntityInterface;
use MediaWiki\Extension\OAuth\Entity\ClaimEntity;
use MediaWiki\Extension\OAuth\Repository\ClaimStore;
use MediaWiki\Extension\OAuth\Tests\Entity\MockClientEntity;
use MediaWikiIntegrationTestCase;

/**
 * @covers \MediaWiki\Extension\OAuth\Repository\ClaimStore
 * @group Database
 * @group OAuth
 */
class ClaimStoreTest extends MediaWikiIntegrationTestCase {

	/**
	 * @var ClaimStore
	 */
	private $oAuthClaimStore;

	protected function setUp(): void {
		$this->oAuthClaimStore = new ClaimStore();
	}

	public static function provideClaims() {
		yield 'empty claims' => [
			[], []
		];

		yield 'string claims' => [
			[ 'str' => 'string' ], [ new ClaimEntity( 'str', 'string' ) ]
		];

		yield 'number claims' => [
			[ 'num' => 9 ], [ new ClaimEntity( 'num', 9 ) ]
		];

		yield 'list of claims' => [
			[
				'class' => 'dummy class',
				'another_str' => [
					'num' => 8,
					'str' => 'mock'
				]
			],
			[
				new ClaimEntity( 'class', 'dummy class' ),
				new ClaimEntity( 'another_str',
					[
						'num' => 8,
						'str' => 'mock'
					]
				)
			]
		];
	}

	/**
	 * @dataProvider provideClaims
	 * @covers \MediaWiki\Extension\OAuth\Repository\ClaimStore::getClaims
	 */
	public function testGetClaimsWithHook( $claims, $expectedClaims ) {
		$client = MockClientEntity::newMock( $this->getTestUser()->getUser() );
		$hookCalled = false;

		$this->setTemporaryHook(
			'OAuthClaimStoreGetClaims',
			function ( string $grantType, ClientEntityInterface $clientEntity, array &$privateClaims )
			use ( $claims, $client, &$hookCalled ) {
				$this->assertEquals( $clientEntity->getName(), $client->getName() );
				foreach ( $claims as $name => $value ) {
					$privateClaims[] = new ClaimEntity( $name, $value );
				}
				$hookCalled = true;
			}
		);

		$res = $this->oAuthClaimStore->getClaims(
			'fake_type',
			$client
		);

		$this->assertTrue( $hookCalled );
		foreach ( $expectedClaims as $index => $claimEntity ) {
			$this->assertSame( $claimEntity->getName(), $res[$index]->getName() );
			$this->assertSame( $claimEntity->getValue(), $res[$index]->getValue() );
		}
	}

	public function testGetClaimsWithoutHook() {
		$res = $this->oAuthClaimStore->getClaims(
			'fake_type',
			MockClientEntity::newMock( $this->getTestUser()->getUser() )
		);

		$this->assertEquals( [], $res );
	}
}
