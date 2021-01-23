<?php

namespace MediaWiki\Extensions\OAuth\Tests\Repository;

use League\OAuth2\Server\Entities\ClientEntityInterface;
use MediaWiki\Extensions\OAuth\Entity\ClaimEntity;
use MediaWiki\Extensions\OAuth\Repository\ClaimStore;
use MediaWiki\Extensions\OAuth\Tests\Entity\Mock_ClientEntity;
use MediaWikiTestCase;

/**
 * @covers \MediaWiki\Extensions\OAuth\Repository\ClaimStore
 * @group Database
 * @group OAuth
 */
class ClaimStoreTest extends MediaWikiTestCase {

	/**
	 * @var ClaimStore
	 */
	private $oAuthClaimStore;

	protected function setUp(): void {
		$this->oAuthClaimStore = new ClaimStore();
	}

	public function provideClaims() {
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
	 * @covers \MediaWiki\Extensions\OAuth\Repository\ClaimStore::getClaims
	 */
	public function testGetClaimsWithHook( $claims, $expectedClaims ) {
		$client = Mock_ClientEntity::newMock( $this->getTestUser()->getUser() );
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
			Mock_ClientEntity::newMock( $this->getTestUser()->getUser() )
		);

		$this->assertEquals( [], $res );
	}
}
