<?php

namespace MediaWiki\Extension\OAuth\Tests\Entity;

use DateInterval;
use DateTimeImmutable;
use Lcobucci\JWT\Configuration;
use League\OAuth2\Server\Entities\ScopeEntityInterface;
use MediaWiki\Extension\OAuth\Entity\AccessTokenEntity;
use MediaWiki\Extension\OAuth\Entity\ClaimEntity;
use MediaWiki\Extension\OAuth\Entity\ClientEntity;
use MediaWiki\Extension\OAuth\Entity\ScopeEntity;
use MediaWiki\WikiMap\WikiMap;
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

	public function testJwt() {
		$getSub = static function ( ClientEntity $clientEntity, ?int $userId = -1 ) {
			if ( $userId === -1 ) {
				$userId = $clientEntity->getUserId();
			}
			$accessTokenEntity = new AccessTokenEntity( $clientEntity, [], 'dummy:', $userId );
			$accessTokenEntity->setIdentifier( 'abcd' );
			$accessTokenEntity->setExpiryDateTime( ( new DateTimeImmutable() )->add(
				new DateInterval( 'P1000Y' )
			) );
			$accessTokenEntity->setJwtConfiguration( Configuration::forUnsecuredSigner() );
			$jwt = (string)$accessTokenEntity;
			$claims = json_decode( base64_decode( explode( '.', $jwt )[1] ), true );
			return $claims['sub'];
		};
		$wikiId = WikiMap::getCurrentWikiId();

		$this->overrideConfigValue( 'OAuth2UsePrefixedSub', false );
		$clientEntity = MockClientEntity::newMock( $this->getTestUser()->getUser() );
		$this->assertSame( (string)$clientEntity->getUserId(), $getSub( $clientEntity ) );

		$this->overrideConfigValue( 'OAuth2UsePrefixedSub', true );
		$this->assertSame( "mw:local:$wikiId:" . $clientEntity->getUserId(), $getSub( $clientEntity ) );

		$this->assertSame( '0', $getSub( $clientEntity, 0 ) );
		$this->assertSame( '', $getSub( $clientEntity, null ) );

		$clientEntity = MockClientEntity::newMock( $this->getTestUser()->getUser(), [ 'userId' => 0 ] );
		$this->assertSame( '0', $getSub( $clientEntity ) );

		$clientEntity = MockClientEntity::newMock( $this->getTestUser()->getUser(), [ 'ownerOnly' => true ] );
		$this->assertSame( (string)$clientEntity->getUserId(), $getSub( $clientEntity ) );
	}
}
