<?php

namespace MediaWiki\Extension\OAuth\Tests\Integration\Repository;

use LogicException;
use MediaWiki\Extension\OAuth\Backend\Consumer;
use MediaWiki\Extension\OAuth\Entity\ClientEntity;
use MediaWiki\Extension\OAuth\OAuthServices;
use MediaWiki\Extension\OAuth\Repository\ArrayConsumerRepository;
use MediaWiki\Extension\OAuth\Repository\DatabaseConsumerRepository;
use MediaWiki\User\CentralId\CentralIdLookup;
use MediaWiki\User\CentralId\CentralIdLookupFactory;
use MediaWiki\Utils\MWRestrictions;
use MediaWikiIntegrationTestCase;
use Wikimedia\NormalizedException\NormalizedException;

/**
 * @covers \MediaWiki\Extension\OAuth\Repository\ArrayConsumerRepository
 * @group Database
 * @group OAuth
 */
class ArrayConsumerRepositoryTest extends MediaWikiIntegrationTestCase {

	private ArrayConsumerRepository $repository;

	protected function setUp(): void {
		parent::setUp();
		$this->overrideConfigValue( 'MWOAuthCentralWiki', false );
		$this->overrideConfigValue( 'MWOAuthSharedUserSource', 'local' );
		$this->repository = new ArrayConsumerRepository(
			OAuthServices::wrap( $this->getServiceContainer() )->getConsumerValidator(),
		);
	}

	private function newConsumerData( array $overrides = [] ): array {
		return array_merge( [
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
		], $overrides );
	}

	private function newConsumer( array $overrides = [] ): Consumer {
		return Consumer::newFromArray( $this->newConsumerData( $overrides ) );
	}

	public function testAddConsumer(): void {
		$consumer = $this->newConsumer( [
			Consumer::FIELD_ID => 123456,
		] );
		$this->repository->addConsumer( $consumer );
		$fetched = $this->repository->getByKey( $consumer->getConsumerKey() );
		$this->assertSame( $consumer, $fetched );
	}

	public function testSaveAndReload(): void {
		$consumer = $this->newConsumer();
		$this->repository->save( $consumer );

		$fetched = $this->repository->getById( $consumer->getId() );
		$this->assertInstanceOf( ClientEntity::class, $fetched );
		$this->assertLessThan( 0, $fetched->getId() );
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

	public function testAddConsumerArray(): void {
		$consumerData = $this->newConsumerData( [
			Consumer::FIELD_ID => 123456,
		] );
		$consumer = Consumer::newFromArray( $consumerData );
		$this->repository->addConsumerArray( $consumerData );

		$fetched = $this->repository->getById( $consumer->getId() );
		$this->assertInstanceOf( ClientEntity::class, $fetched );
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

		// test internal cache
		$fetched2 = $this->repository->getById( $consumer->getId() );
		$this->assertSame( $fetched, $fetched2 );
	}

	public function testConfigurationArray() {
		$mockCentralIdLookup = $this->createNoOpMock( CentralIdLookup::class, [ 'nameFromCentralId' ] );
		$mockCentralIdLookup->method( 'nameFromCentralId' )->with( 12345 )->willReturn( 'TestUser' );
		$mockLookupFactory = $this->createNoOpMock( CentralIdLookupFactory::class, [ 'getLookup' ] );
		$mockLookupFactory->method( 'getLookup' )->willReturn( $mockCentralIdLookup );
		$this->setService( 'CentralIdLookupFactory', $mockLookupFactory );

		$this->repository->addConfigurationArray( [
			Consumer::FIELD_ID => 1234,
			Consumer::FIELD_CONSUMER_KEY => '1234567890abcdef1234567890abcdef',
			Consumer::FIELD_NAME => 'Test Consumer',
			Consumer::FIELD_DESCRIPTION => 'A test consumer',
			Consumer::FIELD_USER_ID => 12345,
			Consumer::FIELD_VERSION => '1.0.1',
			Consumer::FIELD_OAUTH_VERSION => Consumer::OAUTH_VERSION_2,
			Consumer::FIELD_OAUTH2_IS_CONFIDENTIAL => false,
			Consumer::FIELD_GRANTS => [ 'editpage' ],
			Consumer::FIELD_OAUTH2_GRANT_TYPES => [
				ClientEntity::GRANT_TYPE_AUTHORIZATION_CODE,
			],
			Consumer::FIELD_CALLBACK_URL => 'https://example.com/callback',
		] );

		$consumer = $this->repository->getById( 1234 );
		$this->assertInstanceOf( ClientEntity::class, $consumer );
		$this->assertSame( '1.0.1', $consumer->getVersion() );
		$this->assertSame( [ ClientEntity::GRANT_TYPE_AUTHORIZATION_CODE ], $consumer->getAllowedGrants() );
		$this->assertFalse( $consumer->isConfidential() );

		$consumer2 = $this->repository->getByKey( '1234567890abcdef1234567890abcdef' );
		$this->assertSame( $consumer, $consumer2 );

		$this->repository->addConfigurationArray( [
			Consumer::FIELD_ID => 1235,
			Consumer::FIELD_CONSUMER_KEY => '1234567890abcdef1234567890abcdeg',
			Consumer::FIELD_NAME => 'Test Consumer',
			Consumer::FIELD_DESCRIPTION => 'A test consumer',
			Consumer::FIELD_USER_ID => 12345,
			Consumer::FIELD_VERSION => '1.0.2',
			Consumer::FIELD_OAUTH_VERSION => Consumer::OAUTH_VERSION_2,
			Consumer::FIELD_OAUTH2_IS_CONFIDENTIAL => false,
			Consumer::FIELD_GRANTS => [ 'editpage' ],
			Consumer::FIELD_OAUTH2_GRANT_TYPES => [
				ClientEntity::GRANT_TYPE_AUTHORIZATION_CODE,
			],
			// missing callback URL
		] );
		$this->expectException( NormalizedException::class );
		$this->repository->getById( 1235 );
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

	public function testDisallowWritesOnSave() {
		$this->repository = new ArrayConsumerRepository(
			OAuthServices::wrap( $this->getServiceContainer() )->getConsumerValidator(),
			allowWrites: false,
		);
		$consumer = $this->newConsumer();
		$this->expectException( LogicException::class );
		$this->repository->save( $consumer );
	}

	public function testDisallowWritesOnDelete() {
		$this->repository = new ArrayConsumerRepository(
			OAuthServices::wrap( $this->getServiceContainer() )->getConsumerValidator(),
			allowWrites: false,
		);
		$consumer = $this->newConsumer();
		$this->expectException( LogicException::class );
		$this->repository->delete( $consumer );
	}

	public function testDisallowStoringConfigurationBasedConsumerInDb() {
		$consumerData = $this->newConsumerData( [
			Consumer::FIELD_ID => 123456,
		] );
		$this->repository->addConsumerArray( $consumerData );
		$consumer = $this->repository->getByKey( $consumerData[Consumer::FIELD_CONSUMER_KEY] );
		$this->assertInstanceOf( Consumer::class, $consumer );
		$dbRepository = new DatabaseConsumerRepository();
		$this->expectException( LogicException::class );
		$dbRepository->save( $consumer );
	}

	public function testDisallowDeletingConfigurationBasedConsumerFromDb() {
		$consumerData = $this->newConsumerData( [
			Consumer::FIELD_ID => 123456,
		] );
		$this->repository->addConsumerArray( $consumerData );
		$consumer = $this->repository->getByKey( $consumerData[Consumer::FIELD_CONSUMER_KEY] );
		$this->assertInstanceOf( Consumer::class, $consumer );
		$dbRepository = new DatabaseConsumerRepository();
		$this->expectException( LogicException::class );
		$dbRepository->delete( $consumer );
	}

}
