<?php

namespace MediaWiki\Extensions\OAuth\Tests\Entity;

use League\OAuth2\Server\Entities\ScopeEntityInterface;
use MediaWiki\Extensions\OAuth\Entity\AccessTokenEntity;
use MediaWiki\Extensions\OAuth\Entity\ScopeEntity;
use MediaWikiTestCase;

/**
 * @covers \MediaWiki\Extensions\OAuth\Entity\AccessTokenEntity
 */
class AccessTokenEntityTest extends MediaWikiTestCase {

	public function testProperties() {
		$accessToken = new AccessTokenEntity(
			Mock_ClientEntity::newMock( $this->getTestUser()->getUser(), [
				'consumerKey' => 'dummykey'
			] ),
			[
				new ScopeEntity( 'editpage' ),
				new ScopeEntity( 'highvolume' )
			],
			$this->getTestUser()->getUser()->getId()
		);
		$identifier = bin2hex( random_bytes( 40 ) );
		$accessToken->setIdentifier( $identifier );

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
		$atScopes = array_map( function ( ScopeEntityInterface $scope ) {
			return $scope->getIdentifier();
		}, $accessToken->getScopes() );
		$this->assertArrayEquals(
			[ 'editpage', 'highvolume' ],
			$atScopes,
			'Access tokens should have the same scopes as the ones that were passed'
		);
	}
}
