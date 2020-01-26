<?php

namespace MediaWiki\Extensions\OAuth\Tests\Repository;

use MediaWiki\Extensions\OAuth\Repository\AuthCodeRepository;
use MediaWiki\Extensions\OAuth\Tests\Entity\Mock_ClientEntity;
use MediaWikiTestCase;

/**
 * @covers \MediaWiki\Extensions\OAuth\Repository\AuthCodeRepository
 */
class AuthCodeRepositoryTest extends MediaWikiTestCase {
	protected $authCodeToken;
	protected $authCodeTokenRepo;

	protected function setUp() : void {
		parent::setUp();

		$this->authCodeTokenRepo = AuthCodeRepository::factory();
		$this->authCodeToken = $this->authCodeTokenRepo->getNewAuthCode();
		$this->authCodeToken->setIdentifier( bin2hex( random_bytes( 20 ) ) );
		$this->authCodeToken->setClient(
			Mock_ClientEntity::newMock( $this->getTestUser()->getUser() )
		);
		$this->authCodeToken->setExpiryDateTime(
			( new \DateTimeImmutable() )->add( new \DateInterval( 'PT1H' ) )
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
