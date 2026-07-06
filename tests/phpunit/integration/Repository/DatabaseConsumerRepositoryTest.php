<?php

namespace MediaWiki\Extension\OAuth\Tests\Integration\Repository;

use MediaWiki\Extension\OAuth\Backend\Consumer;
use MediaWiki\Extension\OAuth\Entity\ClientEntity;
use MediaWiki\Extension\OAuth\Repository\DatabaseConsumerRepository;
use MediaWiki\Utils\MWRestrictions;
use MediaWikiIntegrationTestCase;

/**
 * @covers \MediaWiki\Extension\OAuth\Repository\DatabaseConsumerRepository
 * @group Database
 * @group OAuth
 */
class DatabaseConsumerRepositoryTest extends MediaWikiIntegrationTestCase {

	private DatabaseConsumerRepository $repository;

	protected function setUp(): void {
		parent::setUp();
		$this->overrideConfigValue( 'MWOAuthCentralWiki', false );
		$this->overrideConfigValue( 'MWOAuthSharedUserSource', 'local' );
		$this->repository = new DatabaseConsumerRepository();
	}

	private function newConsumer( array $overrides = [] ): Consumer {
		return Consumer::newFromArray( array_merge( [
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
			Consumer::FIELD_OAUTH_VERSION => Consumer::OAUTH_VERSION_2,
			Consumer::FIELD_DEVELOPER_AGREEMENT => true,
			Consumer::FIELD_OWNER_ONLY => false,
			Consumer::FIELD_WIKI => '*',
			Consumer::FIELD_GRANTS => [ 'editpage' ],
			Consumer::FIELD_REGISTRATION => '20150101000000',
			Consumer::FIELD_SECRET_KEY => bin2hex( random_bytes( 16 ) ),
			Consumer::FIELD_RSA_KEY => '',
			Consumer::FIELD_RESTRICTIONS => MWRestrictions::newDefault(),
			Consumer::FIELD_STAGE => Consumer::STAGE_APPROVED,
			Consumer::FIELD_STAGE_TIMESTAMP => '20250101000000',
			Consumer::FIELD_DELETED => false,
			Consumer::FIELD_OAUTH2_IS_CONFIDENTIAL => true,
			Consumer::FIELD_OAUTH2_GRANT_TYPES => [
				ClientEntity::GRANT_TYPE_AUTHORIZATION_CODE,
				ClientEntity::GRANT_TYPE_REFRESH_TOKEN,
			],
		], $overrides ) );
	}

	public function testSaveAndReload(): void {
		$consumer = $this->newConsumer();
		$this->repository->save( $consumer );

		$fetched = $this->repository->getById( $consumer->getId() );
		$this->assertInstanceOf( ClientEntity::class, $fetched );
		$this->assertGreaterThan( 0, $fetched->getId() );
		$this->assertSame( $consumer->getId(), $fetched->getId() );
		$this->assertSame( $consumer->getConsumerKey(), $fetched->getConsumerKey() );
		$this->assertSame( $consumer->getName(), $fetched->getName() );
		$this->assertSame( $consumer->getUserId(), $fetched->getUserId() );
		$this->assertSame( $consumer->getVersion(), $fetched->getVersion() );
		$this->assertSame( $consumer->getCallbackUrl(), $fetched->getCallbackUrl() );
		$this->assertSame( $consumer->getCallbackIsPrefix(), $fetched->getCallbackIsPrefix() );
		$this->assertSame( $consumer->getDescription(), $fetched->getDescription() );
		$this->assertSame( $consumer->getEmail(), $fetched->getEmail() );
		$this->assertSame( $consumer->getEmailAuthenticated(), $fetched->getEmailAuthenticated() );
		$this->assertSame( $consumer->getOauthVersion(), $fetched->getOauthVersion() );
		$this->assertSame( $consumer->getDeveloperAgreement(), $fetched->getDeveloperAgreement() );
		$this->assertSame( $consumer->getOwnerOnly(), $fetched->getOwnerOnly() );
		$this->assertSame( $consumer->getWiki(), $fetched->getWiki() );
		$this->assertSame( $consumer->getGrants(), $fetched->getGrants() );
		$this->assertSame( $consumer->getRegistration(), $fetched->getRegistration() );
		$this->assertSame( $consumer->getSecretKey(), $fetched->getSecretKey() );
		$this->assertSame( $consumer->getRsaKey(), $fetched->getRsaKey() );
		$this->assertSame( $consumer->getRestrictions()->toArray(), $fetched->getRestrictions()->toArray() );
		$this->assertSame( $consumer->getStage(), $fetched->getStage() );
		$this->assertSame( $consumer->getStageTimestamp(), $fetched->getStageTimestamp() );
		$this->assertSame( $consumer->getDeleted(), $fetched->getDeleted() );
		$this->assertSame( $consumer->isConfidential(), $fetched->isConfidential() );
		$this->assertSame( $consumer->getAllowedGrants(), $fetched->getAllowedGrants() );
	}

	public function testSaveAndGetById(): void {
		$consumer = $this->newConsumer();
		$this->repository->save( $consumer );

		$consumer2 = $this->newConsumer( [
			Consumer::FIELD_NAME => 'Test Consumer 2',
			Consumer::FIELD_CONSUMER_KEY => bin2hex( random_bytes( 16 ) ),
		] );
		$this->repository->save( $consumer2 );

		$id = $consumer->getId();
		$this->assertNotNull( $id );
		$fetched = $this->repository->getById( $id );
		$this->assertInstanceOf( Consumer::class, $fetched );
		$this->assertSame( $consumer->getConsumerKey(), $fetched->getConsumerKey() );

		$id2 = $consumer2->getId();
		$this->assertNotNull( $id2 );
		$fetched2 = $this->repository->getById( $id2 );
		$this->assertInstanceOf( Consumer::class, $fetched2 );
		$this->assertSame( $consumer2->getConsumerKey(), $fetched2->getConsumerKey() );

		$result = $this->repository->getById( PHP_INT_MAX );
		$this->assertFalse( $result );
	}

	public function testSaveAndGetByKey(): void {
		$consumer = $this->newConsumer();
		$this->repository->save( $consumer );

		$consumer2 = $this->newConsumer( [
			Consumer::FIELD_NAME => 'Test Consumer 2',
			Consumer::FIELD_CONSUMER_KEY => bin2hex( random_bytes( 16 ) ),
		] );
		$this->repository->save( $consumer2 );

		$fetched = $this->repository->getByKey( $consumer->getConsumerKey() );
		$this->assertInstanceOf( Consumer::class, $fetched );
		$this->assertSame( $consumer->getId(), $fetched->getId() );

		$id2 = $consumer2->getId();
		$this->assertNotNull( $id2 );
		$fetched2 = $this->repository->getById( $id2 );
		$this->assertInstanceOf( Consumer::class, $fetched2 );
		$this->assertSame( $consumer2->getConsumerKey(), $fetched2->getConsumerKey() );

		$result = $this->repository->getByKey( 'nonexistentkey00000000000000000a' );
		$this->assertFalse( $result );
	}

	public function testSaveAndGetByNameVersionUser(): void {
		$userId = $this->getTestUser()->getUser()->getId();
		$consumer = $this->newConsumer( [
			Consumer::FIELD_NAME => 'MyUniqueApp',
			Consumer::FIELD_VERSION => '2.0',
			Consumer::FIELD_USER_ID => $userId,
		] );
		$this->repository->save( $consumer );

		$fetched = $this->repository->getByNameVersionUser( 'MyUniqueApp', '2.0', $userId );
		$this->assertInstanceOf( Consumer::class, $fetched );
		$this->assertSame( $consumer->getConsumerKey(), $fetched->getConsumerKey() );

		$result = $this->repository->getByNameVersionUser( 'Nonexistent App', '9.9', 999999 );
		$this->assertFalse( $result );
	}

	public function testSaveUpdate(): void {
		$consumer = $this->newConsumer();
		$this->repository->save( $consumer );
		$id = $consumer->getId();

		$fetched = $this->repository->getById( $id );
		$fetched->setField( 'name', 'Updated Name' );
		$this->repository->save( $fetched );

		$refetched = $this->repository->getById( $id );
		$this->assertSame( 'Updated Name', $refetched->getName() );
	}

	public function testDelete(): void {
		$consumer = $this->newConsumer();
		$this->repository->save( $consumer );
		$id = $consumer->getId();

		$fetched = $this->repository->getById( $id );
		$deleted = $this->repository->delete( $fetched );
		$this->assertTrue( $deleted );

		$result = $this->repository->getById( $id );
		$this->assertFalse( $result );
	}

	public function testDeleteNew(): void {
		$consumer = $this->newConsumer();
		$result = $this->repository->delete( $consumer );
		$this->assertFalse( $result );
	}

}
