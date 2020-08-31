<?php

namespace MediaWiki\Extensions\OAuth\Tests\Repository;

use MediaWiki\Extensions\OAuth\Entity\AccessTokenEntity;
use MediaWiki\Extensions\OAuth\Repository\AccessTokenRepository;
use MediaWiki\Extensions\OAuth\Tests\Entity\Mock_ClientEntity;
use MediaWikiTestCase;

/**
 * @covers \MediaWiki\Extensions\OAuth\Repository\AccessTokenRepository
 * @group Database
 */
class AccessTokenRepositoryTest extends MediaWikiTestCase {
	protected $accessToken;
	protected $accessTokenRepo;

	protected $tablesUsed = [ 'oauth2_access_tokens' ];

	protected function setUp() : void {
		parent::setUp();

		$this->accessToken = new AccessTokenEntity(
			Mock_ClientEntity::newMock( $this->getTestUser()->getUser() ), [], 'dummy'
		);
		$identifier = bin2hex( random_bytes( 40 ) );
		$this->accessToken->setIdentifier( $identifier );
		$this->accessToken->setExpiryDateTime(
			( new \DateTimeImmutable() )->add( new \DateInterval( 'PT1H' ) )
		);

		$this->accessTokenRepo = new AccessTokenRepository( 'dummy' );
	}

	public function testPersistingToken() {
		$this->accessTokenRepo->persistNewAccessToken( $this->accessToken );

		$this->assertFalse(
			$this->accessTokenRepo->isAccessTokenRevoked( $this->accessToken->getIdentifier() ),
			'Access token should not be revoked'
		);
	}

	public function testRevokingToken() {
		$this->accessTokenRepo->revokeAccessToken( $this->accessToken->getIdentifier() );

		$this->assertTrue(
			$this->accessTokenRepo->isAccessTokenRevoked( $this->accessToken->getIdentifier() ),
			'Access token should be revoked'
		);
	}

	public function testGetNewToken() {
		$client = Mock_ClientEntity::newMock( $this->getTestUser()->getUser() );
		$token = $this->accessTokenRepo->getNewToken( $client, [] );
		$this->assertSame( 'dummy', $token->getIssuer() );
		$this->assertSame( $client, $token->getClient() );
	}
}
