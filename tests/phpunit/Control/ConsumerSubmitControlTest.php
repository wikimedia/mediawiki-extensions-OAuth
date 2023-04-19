<?php

namespace MediaWiki\Extension\OAuth\Tests\Control;

use MediaWiki\Extension\OAuth\Backend\Consumer;
use MediaWiki\Extension\OAuth\Backend\OAuth1Consumer;
use MediaWiki\Extension\OAuth\Control\ConsumerSubmitControl;
use MediaWiki\Extension\OAuth\Entity\ClientEntity;
use MediaWiki\WikiMap\WikiMap;
use MediaWikiIntegrationTestCase;
use MWRestrictions;
use RequestContext;
use Status;
use StatusValue;

/**
 * @covers \MediaWiki\Extension\OAuth\Control\ConsumerSubmitControl
 */
class ConsumerSubmitControlTest extends MediaWikiIntegrationTestCase {

	/**
	 * @dataProvider provideSubmit_propose_OAuth1
	 */
	public function testSubmit_propose_OAuth1( array $data, StatusValue $expectedStatus ) {
		$this->doSubmit( $data, $expectedStatus );
	}

	/**
	 * @dataProvider provideSubmit_propose_OAuth2
	 */
	public function testSubmit_propose_OAuth2( array $data, StatusValue $expectedStatus ) {
		$this->doSubmit( $data, $expectedStatus );
	}

	private function doSubmit( array $data, StatusValue $expectedStatus, callable $consumerCheck = null ) {
		global $wgGroupPermissions;
		$this->setMwGlobals( [
			'wgMWOAuthCentralWiki' => WikiMap::getCurrentWikiId(),
		] );
		$this->mergeMwGlobalArrayValue( 'wgGroupPermissions', [
			'user' => [ 'mwoauthproposeconsumer' => true ] + $wgGroupPermissions['user'],
		] );

		$context = RequestContext::getMain();
		$user = $this->getMutableTestUser( 'OAuthOwner' )->getUser();
		$user->setEmail( 'owner@wiki.domain' );
		$user->confirmEmail();
		$user->saveSettings();
		$context->setUser( $user );

		$dbw = $this->getDb( DB_PRIMARY );
		$control = new ConsumerSubmitControl( $context, [], $dbw );
		$control->registerValidators( [] );
		$control->setInputParameters( $data );
		$actualStatus = $control->submit();
		$expectedStatus->isOK()
			? $this->assertStatusOK( $actualStatus )
			: $this->assertStatusNotOK( $actualStatus );
		$expectedStatus->isGood()
			? $this->assertStatusGood( $actualStatus )
			: $this->assertStatusNotOK( $actualStatus );
		$this->assertSame( $expectedStatus->isGood(), $actualStatus->isGood() );
		if ( $expectedStatus->isOK() && $consumerCheck ) {
			$this->assertArrayHasKey( 'consumer', $actualStatus->getValue() );
			$consumerCheck( $actualStatus->getValue()['consumer'] );
		}
		if ( !$expectedStatus->isGood() ) {
			$this->assertSame(
				Status::wrap( $expectedStatus )->getWikiText( false, false, 'en' ),
				Status::wrap( $actualStatus )->getWikiText( false, false, 'en' ) );
		}
	}

	public function provideSubmit_propose_OAuth1() {
		$baseConsumerData = [
			'oauthVersion' => Consumer::OAUTH_VERSION_1,
			'name' => 'test consumer',
			'version' => '1.0',
			'description' => 'test',
			'ownerOnly' => false,
			'callbackUrl' => 'https://example.com',
			'callbackIsPrefix' => false,
			'email' => 'owner@wiki.domain',
			'wiki' => '*',
			'oauth2IsConfidential' => null,
			'oauth2GrantTypes' => [],
			'granttype' => 'normal',
			'grants' => json_encode( [ 'basic' ] ),
			'restrictions' => MWRestrictions::newDefault(),
			'rsaKey' => '',
			'agreement' => true,
			'action' => 'propose',
		];

		return [
			'good' => [
				$baseConsumerData,
				StatusValue::newGood(),
				function ( $consumer ) {
					$owner = $this->getServiceContainer()->getUserFactory()->newFromName( 'OAuthOwner' );

					$this->assertInstanceOf( OAuth1Consumer::class, $consumer );
					/** @var OAuth1Consumer $consumer */
					$this->assertSame( $owner->getId(), $consumer->getUserId() );
					$this->assertFalse( $consumer->getOwnerOnly() );
					$this->assertSame( [ 'basic' ], $consumer->getGrants() );
				},
			],
			'invalid version string' => [
				[
					'version' => 'foo',
				] + $baseConsumerData,
				StatusValue::newFatal( 'mwoauth-invalid-field', 'version' ),
			],
		];
	}

	public function provideSubmit_propose_OAuth2() {
		$baseConsumerData = [
			'oauthVersion' => Consumer::OAUTH_VERSION_2,
			'name' => 'test',
			'version' => '1.0',
			'description' => 'test',
			'ownerOnly' => false,
			'callbackUrl' => 'https://example.com',
			'callbackIsPrefix' => null,
			'email' => 'owner@wiki.domain',
			'wiki' => '*',
			'oauth2IsConfidential' => true,
			'oauth2GrantTypes' => [ 'authorization_code', 'refresh_token' ],
			'granttype' => 'normal',
			'grants' => json_encode( [ 'basic' ] ),
			'restrictions' => MWRestrictions::newDefault(),
			'rsaKey' => '',
			'agreement' => true,
			'action' => 'propose',
		];

		return [
			'good' => [
				$baseConsumerData,
				StatusValue::newGood(),
				function ( $consumer ) {
					$owner = $this->getServiceContainer()->getUserFactory()->newFromName( 'OAuthOwner' );

					$this->assertInstanceOf( ClientEntity::class, $consumer );
					/** @var ClientEntity $consumer */
					$this->assertSame( $owner->getId(), $consumer->getUserId() );
					$this->assertFalse( $consumer->getOwnerOnly() );
					$this->assertSame( [ 'basic' ], $consumer->getGrants() );
				},
			],
		];
	}

}
