<?php

namespace MediaWiki\Extension\OAuth\Tests\Repository;

use DateInterval;
use DateTimeImmutable;
use MediaWiki\Extension\OAuth\Entity\AuthCodeEntity;
use MediaWiki\Extension\OAuth\Repository\AuthCodeRepository;
use MediaWiki\Extension\OAuth\Tests\Entity\MockClientEntity;
use MediaWikiIntegrationTestCase;

/**
 * @covers \MediaWiki\Extension\OAuth\Repository\AuthCodeRepository
 * @group OAuth
 * @group Database
 */
class AuthCodeRepositoryTest extends MediaWikiIntegrationTestCase {
	/** @var AuthCodeEntity */
	protected $authCodeToken;
	/** @var AuthCodeRepository */
	protected $authCodeTokenRepo;

	protected function setUp(): void {
		parent::setUp();

		$this->authCodeTokenRepo = AuthCodeRepository::factory();
		$this->authCodeToken = $this->authCodeTokenRepo->getNewAuthCode();
		$this->authCodeToken->setIdentifier( bin2hex( random_bytes( 20 ) ) );
		$this->authCodeToken->setClient(
			MockClientEntity::newMock( $this->getTestUser()->getUser() )
		);
		$this->authCodeToken->setExpiryDateTime(
			( new DateTimeImmutable() )->add( new DateInterval( 'PT1H' ) )
		);
	}

	public function testPersistingToken() {
		$this->authCodeTokenRepo->persistNewAuthCode( $this->authCodeToken );

		$this->assertFalse(
			$this->authCodeTokenRepo->isAuthCodeRevoked( $this->authCodeToken->getIdentifier() ),
			'AuthCode token must be persisted'
		);
	}

	public function testRevokingToken() {
		$this->authCodeTokenRepo->revokeAuthCode( $this->authCodeToken->getIdentifier() );

		$this->assertTrue(
			$this->authCodeTokenRepo->isAuthCodeRevoked( $this->authCodeToken->getIdentifier() ),
			'AuthCode token should be revoked'
		);
	}
}
