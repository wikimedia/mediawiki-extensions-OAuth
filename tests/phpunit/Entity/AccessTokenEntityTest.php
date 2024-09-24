<?php

namespace MediaWiki\Extension\OAuth\Tests\Entity;

use League\OAuth2\Server\Entities\ScopeEntityInterface;
use MediaWiki\Extension\OAuth\Entity\AccessTokenEntity;
use MediaWiki\Extension\OAuth\Entity\ClaimEntity;
use MediaWiki\Extension\OAuth\Entity\ScopeEntity;
use MediaWikiIntegrationTestCase;

/**
 * @covers \MediaWiki\Extension\OAuth\Entity\AccessTokenEntity
 * @group OAuth
 * @group Database
 */
class AccessTokenEntityTest extends MediaWikiIntegrationTestCase {

	public function testProperties() {
		$claims = [
			new ClaimEntity( 'name', 'dummyValue' ),
			new ClaimEntity(
				'arr',
				[
					'str' => 'string',
					'num' => 9
				]
			)
		];
		$accessToken = new AccessTokenEntity(
			MockClientEntity::newMock( $this->getTestUser()->getUser(), [
				'consumerKey' => 'dummykey'
			] ),
			[
				new ScopeEntity( 'editpage' ),
				new ScopeEntity( 'highvolume' )
			],
			'dummy',
			$this->getTestUser()->getUser()->getId()
		);
		$identifier = bin2hex( random_bytes( 40 ) );
		$accessToken->setIdentifier( $identifier );
		foreach ( $claims as $claim ) {
			$accessToken->addClaim( $claim );
		}

		$this->assertSame(
			$identifier, $accessToken->getIdentifier(),
			'Access token identifier should match the one set'
		);
		$this->assertSame(
			$this->getTestUser()->getUser()->getId(),
			$accessToken->getUserIdentifier(),
			'Access token should have the same user identifier that was passed to it'
		);
		$this->assertSame(
			'dummykey', $accessToken->getClient()->getIdentifier(),
			'Access token should have the same client identifier as the one that was passed'
		);
		$atScopes = array_map( static function ( ScopeEntityInterface $scope ) {
			return $scope->getIdentifier();
		}, $accessToken->getScopes() );
		$this->assertArrayEquals(
			[ 'editpage', 'highvolume' ],
			$atScopes,
			'Access tokens should have the same scopes as the ones that were passed'
		);
		$tokenClaims = $accessToken->getClaims();
		$this->assertSameSize( $claims, $tokenClaims );
		foreach ( $claims as $index => $claim ) {
			$this->assertSame( $claim->getName(), $tokenClaims[$index]->getName() );
			$this->assertSame( $claim->getValue(), $tokenClaims[$index]->getValue() );
		}

		$this->assertSame( 'dummy', $accessToken->getIssuer() );
		$accessToken->setIssuer( 'new_dummy' );
		$this->assertSame( 'new_dummy', $accessToken->getIssuer() );
	}
}
