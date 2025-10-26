<?php

namespace MediaWiki\Extension\OAuth\Tests\Repository;

use League\OAuth2\Server\Entities\ClientEntityInterface;
use MediaWiki\Extension\OAuth\Entity\ClaimEntity;
use MediaWiki\Extension\OAuth\Repository\ClaimStore;
use MediaWiki\Extension\OAuth\Tests\Entity\MockClientEntity;
use MediaWiki\User\UserIdentity;
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
			[], [], []
		];

		yield 'one claim' => [
			[ 'core' => 'foo' ],
			[ 'ext' => 'bar' ],
			[
				new ClaimEntity( 'core', 'foo' ),
				new ClaimEntity( 'ext', 'bar' ),
			],
		];

		yield 'list of claims' => [
			[
				'some' => 'stuff',
				'more' => 123,
			],
			[
				'class' => 'dummy class',
				'another' => [ 'num' => 8, 'str' => 'mock' ]
			],
			[
				new ClaimEntity( 'some', 'stuff' ),
				new ClaimEntity( 'more', 123 ),
				new ClaimEntity( 'class', 'dummy class' ),
				new ClaimEntity( 'another', [ 'num' => 8, 'str' => 'mock' ] ),
			],
		];

		// the more specific hook should take precedence
		yield 'override' => [
			[ 'foo' => 'core', 'bar' => 'core' ],
			[ 'boom' => 'ext', 'foo' => 'ext' ],
			[
				new ClaimEntity( 'foo', 'ext' ),
				new ClaimEntity( 'bar', 'core' ),
				new ClaimEntity( 'boom', 'ext' ),
			],
		];
	}

	/**
	 * @dataProvider provideClaims
	 * @covers \MediaWiki\Extension\OAuth\Repository\ClaimStore::getClaims
	 */
	public function testGetClaims( $coreHookClaims, $extensionHookclaims, $expectedClaims ) {
		$client = MockClientEntity::newMock( $this->getTestUser()->getUser() );

		$this->setTemporaryHook(
			'GetSessionJwtData',
			function ( ?UserIdentity $user, array &$jwtData ) use ( $client, $coreHookClaims ) {
				$this->assertSame( $client->getUserId(), $user->getId() );
				$jwtData = array_merge( $jwtData, $coreHookClaims );
			}
		);
		$this->setTemporaryHook(
			'OAuthClaimStoreGetClaims',
			function ( string $grantType, ClientEntityInterface $clientEntity, array &$privateClaims )
				use ( $client, $extensionHookclaims )
			{
				$this->assertEquals( $clientEntity->getName(), $client->getName() );
				foreach ( $extensionHookclaims as $name => $value ) {
					$privateClaims[] = new ClaimEntity( $name, $value );
				}
			}
		);

		$res = $this->oAuthClaimStore->getClaims(
			'fake_type',
			$client,
			$client->getUserId()
		);

		$makeReadableWhenPrinted = static fn ( array $claims ) => array_map(
			static fn ( ClaimEntity $claim ) => $claim->getName() . ':' . json_encode( $claim->getValue() ),
			$claims
		);
		$this->assertArrayEquals( $makeReadableWhenPrinted( $expectedClaims ), $makeReadableWhenPrinted( $res ) );
	}

	public function provideGetClaims_userIdentity() {
		return [
			[ false, false, false ],
			[ false, true, true ],
			[ true, false, true ],
			[ true, true, true ],
		];
	}

	/**
	 * @dataProvider provideGetClaims_userIdentity
	 * @covers \MediaWiki\Extension\OAuth\Repository\ClaimStore::getClaims
	 */
	public function testGetClaims_userIdentity( $clientUserId, $explicitUserId, $expectCoreHook ) {
		$user = $this->getTestUser()->getUser();
		$client = MockClientEntity::newMock( $user,
			$clientUserId ? [] : [ 'userId' => null ] );

		$this->setTemporaryHook(
			'GetSessionJwtData',
			static function ( ?UserIdentity $user, array &$jwtData ) {
				$jwtData['core'] = true;
			}
		);
		$this->setTemporaryHook(
			'OAuthClaimStoreGetClaims',
			static function ( string $grantType, ClientEntityInterface $clientEntity, array &$privateClaims ) {
				$privateClaims[] = new ClaimEntity( 'ext', true );
			}
		);

		$res = $this->oAuthClaimStore->getClaims(
			'fake_type',
			$client,
			$explicitUserId ? $user->getId() : null
		);

		$claims = array_map( static fn ( ClaimEntity $claimEntity ) => $claimEntity->getName(), $res );
		if ( $expectCoreHook ) {
			$this->assertArrayEquals( [ 'core', 'ext' ], $claims, ordered: false );
		} else {
			$this->assertSame( [ 'ext' ], $claims );
		}
	}
}
