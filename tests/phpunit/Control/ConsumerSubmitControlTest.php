<?php

namespace MediaWiki\Extension\OAuth\Tests\Control;

use MediaWiki\Extension\OAuth\Backend\Consumer;
use MediaWiki\Extension\OAuth\Backend\OAuth1Consumer;
use MediaWiki\Extension\OAuth\Control\ConsumerSubmitControl;
use MediaWiki\Extension\OAuth\Entity\ClientEntity;
use MediaWiki\Status\Status;
use MediaWiki\User\User;
use MediaWiki\WikiMap\WikiMap;
use MediaWikiIntegrationTestCase;
use MWRestrictions;
use RequestContext;
use StatusValue;

/**
 * @covers \MediaWiki\Extension\OAuth\Control\ConsumerSubmitControl
 * @group Database
 */
class ConsumerSubmitControlTest extends MediaWikiIntegrationTestCase {

	private string $oauthOwnerUsername;

	/**
	 * @dataProvider provideSubmit_propose_OAuth1
	 */
	public function testSubmit_propose_OAuth1(
		array $data,
		StatusValue $expectedStatus,
		callable $consumerCheck = null
	) {
		$this->doSubmit( $data, $expectedStatus, $consumerCheck );
	}

	/**
	 * @dataProvider provideSubmit_propose_OAuth2
	 */
	public function testSubmit_propose_OAuth2(
		array $data,
		StatusValue $expectedStatus,
		callable $consumerCheck = null
	) {
		$this->doSubmit( $data, $expectedStatus, $consumerCheck );
	}

	private function doSubmit( array $data, StatusValue $expectedStatus, callable $consumerCheck = null ) {
		global $wgGroupPermissions;
		$this->setMwGlobals( [
			'wgMWOAuthCentralWiki' => WikiMap::getCurrentWikiId(),
			'wgOAuthAutoApprove' => [ [
				'grants' => [ 'mwoauth-authonly', 'mwoauth-authonlyprivate', 'basic' ],
			] ],
		] );
		$this->mergeMwGlobalArrayValue( 'wgGroupPermissions', [
			'user' => [ 'mwoauthproposeconsumer' => true ] + $wgGroupPermissions['user'],
		] );

		$context = RequestContext::getMain();
		$user = $this->getMutableTestUser()->getUser();
		$user->setEmail( 'owner@wiki.domain' );
		$user->confirmEmail();
		$user->saveSettings();
		$context->setUser( $user );
		$this->oauthOwnerUsername = $user->getName();

		$dbw = $this->getDb();
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
			$this->assertArrayHasKey( 'result', $actualStatus->getValue() );
			$this->assertArrayHasKey( 'consumer', $actualStatus->getValue()['result'] );
			$consumerCheck( $this, $user, $actualStatus->getValue()['result']['consumer'] );
		}
		if ( !$expectedStatus->isGood() ) {
			$this->assertSame(
				Status::wrap( $expectedStatus )->getWikiText( false, false, 'en' ),
				Status::wrap( $actualStatus )->getWikiText( false, false, 'en' ) );
		}
	}

	public static function provideSubmit_propose_OAuth1() {
		$baseConsumerData = [
			'oauthVersion' => Consumer::OAUTH_VERSION_1,
			'name' => 'test consumer',
			'version' => '1.0',
			'description' => 'test',
			'ownerOnly' => false,
			'callbackUrl' => 'https://example.com/oauth',
			'callbackIsPrefix' => false,
			'email' => 'owner@wiki.domain',
			'wiki' => '*',
			'oauth2IsConfidential' => null,
			'oauth2GrantTypes' => [],
			'granttype' => 'normal',
			'grants' => json_encode( [ 'editpage' ] ),
			'restrictions' => MWRestrictions::newDefault(),
			'rsaKey' => '',
			'agreement' => true,
			'action' => 'propose',
		];

		return [
			'good' => [
				$baseConsumerData,
				StatusValue::newGood(),
				static function ( MediaWikiIntegrationTestCase $testCase, User $owner, $consumer ) {
					$testCase->assertInstanceOf( OAuth1Consumer::class, $consumer );
					/** @var OAuth1Consumer $consumer */
					$testCase->assertSame( $owner->getId(), $consumer->getUserId() );
					$testCase->assertFalse( $consumer->getOwnerOnly() );
					$testCase->assertSame( [ 'basic', 'editpage' ], $consumer->getGrants() );
					$testCase->assertSame( Consumer::STAGE_PROPOSED, $consumer->getStage() );
				},
			],
			'auto-approved' => [
				[
					'grants' => json_encode( [ 'basic' ] ),
				] + $baseConsumerData,
				StatusValue::newGood(),
				static function ( MediaWikiIntegrationTestCase $testCase, User $owner, $consumer ) {
					$testCase->assertSame( [ 'basic' ], $consumer->getGrants() );
					$testCase->assertSame( Consumer::STAGE_APPROVED, $consumer->getStage() );
				},
			],
			'invalid version string' => [
				[
					'version' => 'foo',
				] + $baseConsumerData,
				StatusValue::newFatal( 'mwoauth-invalid-field', 'version' ),
			],
			'bare domain' => [
				[
					'callbackUrl' => 'https://example.com/',
				] + $baseConsumerData,
				StatusValue::newFatal( 'mwoauth-error-callback-bare-domain-oauth1', ),
			],
			'ignore warnings' => [
				[
					'callbackUrl' => 'https://example.com/',
					'ignorewarnings' => true,
				] + $baseConsumerData,
				StatusValue::newGood(),
			],
		];
	}

	public static function provideSubmit_propose_OAuth2() {
		$baseConsumerData = [
			'oauthVersion' => Consumer::OAUTH_VERSION_2,
			'name' => 'test',
			'version' => '1.0',
			'description' => 'test',
			'ownerOnly' => false,
			'callbackUrl' => 'https://example.com/oauth',
			'callbackIsPrefix' => null,
			'email' => 'owner@wiki.domain',
			'wiki' => '*',
			'oauth2IsConfidential' => true,
			'oauth2GrantTypes' => [ 'authorization_code', 'refresh_token' ],
			'granttype' => 'normal',
			'grants' => json_encode( [ 'editpage' ] ),
			'restrictions' => MWRestrictions::newDefault(),
			'rsaKey' => '',
			'agreement' => true,
			'action' => 'propose',
		];
		$assertGoodConsumer = static function ( MediaWikiIntegrationTestCase $testCase, User $owner, $consumer ) {
			$testCase->assertInstanceOf( ClientEntity::class, $consumer );
			/** @var ClientEntity $consumer */
			$testCase->assertSame( $owner->getId(), $consumer->getUserId() );
			$testCase->assertFalse( $consumer->getOwnerOnly() );
			$testCase->assertSame( [ 'basic', 'editpage' ], $consumer->getGrants() );
		};

		yield 'good' => [
			$baseConsumerData,
			StatusValue::newGood(),
			$assertGoodConsumer,
		];

		foreach ( [
			'localhost',
			'127.0.0.1',
			'[::1]',
			'dev.whatever.localhost',
			'foo.wiki.local.wmftest.com',
			'striker.local.wmftest.net',
			'dev-portal.local.wmftest.org',
		] as $host ) {
			yield "http protocol ($host), allowed" => [
				[
					'callbackUrl' => "http://$host:8080/oauth",
				] + $baseConsumerData,
				StatusValue::newGood(),
				$assertGoodConsumer,
			];
		}

		yield 'http protocol (non-localhost), not allowed' => [
			[
				'callbackUrl' => 'http://example.com',
			] + $baseConsumerData,
			StatusValue::newFatal( 'mwoauth-error-callback-url-must-be-https' ),
		];
	}

}
