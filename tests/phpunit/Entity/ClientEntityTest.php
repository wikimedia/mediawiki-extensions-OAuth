<?php

namespace MediaWiki\Extensions\OAuth\Tests\Entity;

use MediaWiki\Extensions\OAuth\Backend\ConsumerAcceptance;
use MediaWiki\Extensions\OAuth\Entity\AccessTokenEntity;
use MediaWikiTestCase;

/**
 * @covers \MediaWiki\Extensions\OAuth\Entity\ClientEntity
 */
class ClientEntityTest extends MediaWikiTestCase {

	public function testProperties() {
		$domain = 'http://domain.com/oauth2';
		$client = Mock_ClientEntity::newMock( $this->getTestUser()->getUser(), [
			'consumerKey' => '123456789',
			'callbackUrl' => $domain,
			'name' => 'Test client',
			'oauth2IsConfidential' => false,
			'oauth2GrantTypes' => [ 'client_credentials' ]
		] );

		$this->assertSame(
			$domain, $client->getRedirectUri(),
			'Redirect URI should match the one given on registration'
		);
		$this->assertFalse(
			$client->isConfidential(),
			'Client should not be confidential'
		);
		$this->assertSame(
			'123456789', $client->getConsumerKey(),
			'ConsumerKey should be the same as the one given on registration'
		);

		$client->setIdentifier( '987654321' );
		$this->assertSame(
			'987654321', $client->getConsumerKey(),
			'ConsumerKey should change when explicitly set'
		);
		$this->assertSame(
			'Test client', $client->getName(),
			'Client name should be same as the one given on registration'
		);
		$this->assertArrayEquals(
			[ 'client_credentials' ], $client->getAllowedGrants(),
			'Allowed grants should be the same as ones given on registration'
		);

		$approval = ConsumerAcceptance::newFromArray(
			[
				'id' 		   => 2,
				'accessToken'  => '98764erf',
				'accepted'     => wfTimestampNow(),
				'wiki'         => 'dummy',
				'userId'       => 12345,
				'consumerId'   => '67890987654',
				'accessSecret' => 'secret key',
				'grants'       => [ 'editpage' ]
			]
		);

		$accessToken = $client->getOwnerOnlyAccessToken( $approval );
		$this->assertInstanceOf( AccessTokenEntity::class, $accessToken );
		$this->assertEquals( 12345, $accessToken->getUserIdentifier() );

		$scopes = $client->getScopes();
		$accessTokenScopes = $accessToken->getScopes();
		foreach ( $scopes  as $index => $scope ) {
			$this->assertEquals( $scope->jsonSerialize(), $accessTokenScopes[$index]->jsonSerialize() );
		}
	}
}
