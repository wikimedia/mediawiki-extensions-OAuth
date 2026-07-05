<?php

namespace MediaWiki\Extension\OAuth\Tests\Integration\Repository;

use MediaWiki\Extension\OAuth\Backend\Consumer;
use MediaWiki\Extension\OAuth\Backend\Utils;
use MediaWiki\Extension\OAuth\Entity\ClientEntity;
use MediaWiki\Extension\OAuth\Lib\OAuthException;
use MediaWiki\Extension\OAuth\Repository\ClientRepositoryAdapter;
use MediaWiki\Extension\OAuth\Repository\DatabaseConsumerRepository;
use MediaWiki\Utils\MWRestrictions;
use MediaWikiIntegrationTestCase;

/**
 * @covers \MediaWiki\Extension\OAuth\Repository\ClientRepositoryAdapter
 * @group Database
 * @group OAuth
 */
class ClientRepositoryAdapterTest extends MediaWikiIntegrationTestCase {

	private DatabaseConsumerRepository $repository;
	private ClientRepositoryAdapter $adapter;

	protected function setUp(): void {
		parent::setUp();
		$this->overrideConfigValue( 'MWOAuthCentralWiki', false );
		$this->overrideConfigValue( 'MWOAuthSharedUserSource', 'local' );
		$this->repository = new DatabaseConsumerRepository();
		$this->adapter = new ClientRepositoryAdapter( $this->repository );
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

	public function testGetClientEntityOAuth2(): void {
		$consumer = $this->newConsumer( [ Consumer::FIELD_OAUTH_VERSION => Consumer::OAUTH_VERSION_2 ] );
		$consumerKey = $consumer->getConsumerKey();
		$this->repository->save( $consumer );

		$clientEntity = $this->adapter->getClientEntity( $consumerKey );
		$this->assertInstanceOf( ClientEntity::class, $clientEntity );
		$this->assertSame( $consumerKey, $clientEntity->getIdentifier() );
	}

	public function testGetClientEntityOAuth1(): void {
		$consumer = $this->newConsumer( [
			Consumer::FIELD_OAUTH_VERSION => Consumer::OAUTH_VERSION_1,
			Consumer::FIELD_OAUTH2_IS_CONFIDENTIAL => false,
			Consumer::FIELD_OAUTH2_GRANT_TYPES => [],
		] );
		$consumerKey = $consumer->getConsumerKey();
		$this->repository->save( $consumer );

		$result = $this->adapter->getClientEntity( $consumerKey );
		$this->assertNull( $result );
	}

	public function testGetClientEntityNotFound(): void {
		$result = $this->adapter->getClientEntity( 'nonexistentkey00000000000000000b' );
		$this->assertNull( $result );
	}

	public function testValidateClient(): void {
		$secretKey = bin2hex( random_bytes( 16 ) );
		$consumer = $this->newConsumer( [ Consumer::FIELD_SECRET_KEY => $secretKey ] );
		$this->repository->save( $consumer );

		$validSecret = Utils::hmacDBSecret( $secretKey );
		$result = $this->adapter->validateClient(
			$consumer->getConsumerKey(),
			$validSecret,
			ClientEntity::GRANT_TYPE_AUTHORIZATION_CODE
		);
		$this->assertTrue( $result );

		$result = $this->adapter->validateClient(
			$consumer->getConsumerKey(),
			'wrong-secret',
			ClientEntity::GRANT_TYPE_AUTHORIZATION_CODE
		);
		$this->assertFalse( $result );
	}

	public function testValidateClientNotFound(): void {
		$this->expectException( OAuthException::class );
		$this->adapter->validateClient(
			'nonexistentkey00000000000000000c',
			'any-secret',
			'authorization_code'
		);
	}

}
