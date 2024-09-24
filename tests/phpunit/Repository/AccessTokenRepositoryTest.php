<?php

namespace MediaWiki\Extension\OAuth\Tests\Repository;

use DateInterval;
use DateTimeImmutable;
use MediaWiki\Extension\OAuth\Entity\AccessTokenEntity;
use MediaWiki\Extension\OAuth\Repository\AccessTokenRepository;
use MediaWiki\Extension\OAuth\Tests\Entity\MockClientEntity;
use MediaWikiIntegrationTestCase;

/**
 * @covers \MediaWiki\Extension\OAuth\Repository\AccessTokenRepository
 * @group Database
 * @group OAuth
 */
class AccessTokenRepositoryTest extends MediaWikiIntegrationTestCase {
	/** @var AccessTokenEntity */
	protected $accessToken;
	/** @var AccessTokenRepository */
	protected $accessTokenRepo;

	protected function setUp(): void {
		parent::setUp();

		$this->accessToken = new AccessTokenEntity(
			MockClientEntity::newMock( $this->getTestUser()->getUser() ), [], 'dummy'
		);
		$identifier = bin2hex( random_bytes( 40 ) );
		$this->accessToken->setIdentifier( $identifier );
		$this->accessToken->setExpiryDateTime(
			( new DateTimeImmutable() )->add( new DateInterval( 'PT1H' ) )
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
		$client = MockClientEntity::newMock( $this->getTestUser()->getUser() );
		$token = $this->accessTokenRepo->getNewToken( $client, [] );
		$this->assertSame( 'dummy', $token->getIssuer() );
		$this->assertSame( $client, $token->getClient() );
	}
}
