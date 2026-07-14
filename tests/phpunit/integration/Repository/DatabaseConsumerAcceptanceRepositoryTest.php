<?php

namespace MediaWiki\Extension\OAuth\Tests\Integration\Repository;

use MediaWiki\Extension\OAuth\Backend\Consumer;
use MediaWiki\Extension\OAuth\Backend\ConsumerAcceptance;
use MediaWiki\Extension\OAuth\Backend\Utils;
use MediaWiki\Extension\OAuth\Repository\DatabaseConsumerAcceptanceRepository;
use MediaWikiIntegrationTestCase;

/**
 * @covers \MediaWiki\Extension\OAuth\Repository\DatabaseConsumerAcceptanceRepository
 * @group Database
 * @group OAuth
 */
class DatabaseConsumerAcceptanceRepositoryTest extends MediaWikiIntegrationTestCase {

	private DatabaseConsumerAcceptanceRepository $repository;
	private Consumer $consumer;

	protected function setUp(): void {
		parent::setUp();
		$this->overrideConfigValue( 'MWOAuthCentralWiki', false );
		$this->overrideConfigValue( 'MWOAuthSharedUserSource', 'local' );
		$this->repository = new DatabaseConsumerAcceptanceRepository();
		$this->consumer = $this->createTestConsumer();
	}

	private function createTestConsumer(): Consumer {
		$consumer = Consumer::newFromArray( [
			Consumer::FIELD_ID => null,
			Consumer::FIELD_CONSUMER_KEY => bin2hex( random_bytes( 16 ) ),
			Consumer::FIELD_NAME => 'Test Consumer',
			Consumer::FIELD_USER_ID => $this->getTestUser()->getUser()->getId(),
			Consumer::FIELD_VERSION => '1.0',
			Consumer::FIELD_CALLBACK_URL => 'https://example.com/callback',
			Consumer::FIELD_CALLBACK_IS_PREFIX => false,
			Consumer::FIELD_DESCRIPTION => 'A test consumer',
			Consumer::FIELD_EMAIL => 'test@example.com',
			Consumer::FIELD_EMAIL_AUTHENTICATED => '20050101000000',
			Consumer::FIELD_OAUTH_VERSION => Consumer::OAUTH_VERSION_1,
			Consumer::FIELD_DEVELOPER_AGREEMENT => true,
			Consumer::FIELD_OWNER_ONLY => false,
			Consumer::FIELD_WIKI => '*',
			Consumer::FIELD_GRANTS => [ 'read' ],
			Consumer::FIELD_REGISTRATION => '20150101000000',
			Consumer::FIELD_SECRET_KEY => bin2hex( random_bytes( 16 ) ),
			Consumer::FIELD_RSA_KEY => '',
			Consumer::FIELD_RESTRICTIONS => \MediaWiki\Utils\MWRestrictions::newDefault(),
			Consumer::FIELD_STAGE => Consumer::STAGE_APPROVED,
			Consumer::FIELD_STAGE_TIMESTAMP => '20250101000000',
			Consumer::FIELD_DELETED => false,
			Consumer::FIELD_OAUTH2_IS_CONFIDENTIAL => false,
			Consumer::FIELD_OAUTH2_GRANT_TYPES => [],
		] );
		$consumer->save( Utils::getOAuthDB( DB_PRIMARY ) );
		return $consumer;
	}

	private function newAcceptance( array $overrides = [] ): ConsumerAcceptance {
		return ConsumerAcceptance::newFromArray( array_merge( [
			'id' => null,
			'wiki' => '*',
			'userId' => $this->getTestUser()->getUser()->getId(),
			'consumerId' => $this->consumer->getId(),
			'accessToken' => bin2hex( random_bytes( 16 ) ),
			'accessSecret' => bin2hex( random_bytes( 16 ) ),
			'grants' => [ 'read' ],
			'accepted' => wfTimestampNow(),
			'oauth_version' => Consumer::OAUTH_VERSION_1,
		], $overrides ) );
	}

	public function testSaveAndGetById(): void {
		$acceptance = $this->newAcceptance();
		$this->repository->save( $acceptance );

		$id = $acceptance->getId();
		$this->assertNotNull( $id );
		$this->assertGreaterThan( 0, $id );

		$fetched = $this->repository->getById( $id );
		$this->assertInstanceOf( ConsumerAcceptance::class, $fetched );
		$this->assertSame( $id, (int)$fetched->getId() );
		$this->assertSame( $acceptance->getAccessToken(), $fetched->getAccessToken() );
		$this->assertSame( $acceptance->getAccessSecret(), $fetched->getAccessSecret() );
		$this->assertSame( $acceptance->getWiki(), $fetched->getWiki() );
		$this->assertSame( $acceptance->getUserId(), $fetched->getUserId() );
		$this->assertSame( $acceptance->getConsumerId(), $fetched->getConsumerId() );
		$this->assertSame( $acceptance->getGrants(), $fetched->getGrants() );
		$this->assertSame( $acceptance->getAccepted(), $fetched->getAccepted() );
		$this->assertSame( $acceptance->getOAuthVersion(), $fetched->getOAuthVersion() );
	}

	public function testGetByIdNotFound(): void {
		$result = $this->repository->getById( PHP_INT_MAX );
		$this->assertFalse( $result );
	}

	public function testSaveAndGetByToken(): void {
		$acceptance = $this->newAcceptance();
		$this->repository->save( $acceptance );

		$token = $acceptance->getAccessToken();
		$fetched = $this->repository->getByToken( $token );
		$this->assertInstanceOf( ConsumerAcceptance::class, $fetched );
		$this->assertSame( $acceptance->getId(), (int)$fetched->getId() );
	}

	public function testGetByTokenNotFound(): void {
		$result = $this->repository->getByToken( 'nonexistent00000000000000000a' );
		$this->assertFalse( $result );
	}

	public function testSaveAndGetByUserConsumerWiki(): void {
		$user = $this->getTestUser()->getUser();
		$acceptance = $this->newAcceptance( [
			'wiki' => '*',
			'userId' => $user->getId(),
		] );
		$this->repository->save( $acceptance );

		$fetched = $this->repository->getByUserConsumerWiki(
			$user->getId(),
			$this->consumer,
			'*'
		);
		$this->assertInstanceOf( ConsumerAcceptance::class, $fetched );
		$this->assertSame( $acceptance->getId(), (int)$fetched->getId() );
	}

	public function testGetByUserConsumerWikiNotFound(): void {
		$user = $this->getTestUser()->getUser();
		$result = $this->repository->getByUserConsumerWiki(
			999999,
			$this->consumer,
			'*'
		);
		$this->assertFalse( $result );
	}

	public function testGetByUserConsumerWikiWithSpecificWiki(): void {
		$user = $this->getTestUser()->getUser();
		$acceptance = $this->newAcceptance( [
			'wiki' => 'enwiki',
			'userId' => $user->getId(),
		] );
		$this->repository->save( $acceptance );

		$fetched = $this->repository->getByUserConsumerWiki(
			$user->getId(),
			$this->consumer,
			'enwiki'
		);
		$this->assertInstanceOf( ConsumerAcceptance::class, $fetched );
		$this->assertSame( $acceptance->getId(), (int)$fetched->getId() );

		// Should not find when looking for '*'
		$notFound = $this->repository->getByUserConsumerWiki(
			$user->getId(),
			$this->consumer,
			'*'
		);
		$this->assertFalse( $notFound );
	}

	public function testSaveUpdate(): void {
		$acceptance = $this->newAcceptance();
		$this->repository->save( $acceptance );
		$id = $acceptance->getId();

		$fetched = $this->repository->getById( $id );
		$fetched->setFields( [
			'grants' => [ 'read', 'write' ],
			'wiki' => 'enwiki',
		] );
		$this->repository->save( $fetched );

		$refetched = $this->repository->getById( $id );
		$this->assertSame( [ 'read', 'write' ], $refetched->getGrants() );
		$this->assertSame( 'enwiki', $refetched->getWiki() );
	}

	public function testDelete(): void {
		$acceptance = $this->newAcceptance();
		$this->repository->save( $acceptance );
		$id = $acceptance->getId();

		$fetched = $this->repository->getById( $id );
		$deleted = $this->repository->delete( $fetched );
		$this->assertTrue( $deleted );

		$result = $this->repository->getById( $id );
		$this->assertFalse( $result );
	}

	public function testDeleteNew(): void {
		$acceptance = $this->newAcceptance();
		$result = $this->repository->delete( $acceptance );
		$this->assertFalse( $result );
	}

}
