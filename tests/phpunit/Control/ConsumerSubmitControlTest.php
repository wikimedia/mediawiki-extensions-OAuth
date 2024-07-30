<?php

namespace MediaWiki\Extension\OAuth\Tests\Control;

use MediaWiki\Context\RequestContext;
use MediaWiki\Extension\OAuth\Backend\Consumer;
use MediaWiki\Extension\OAuth\Backend\OAuth1Consumer;
use MediaWiki\Extension\OAuth\Control\ConsumerSubmitControl;
use MediaWiki\Extension\OAuth\Entity\ClientEntity;
use MediaWiki\Status\Status;
use MediaWiki\User\User;
use MediaWiki\WikiMap\WikiMap;
use MediaWikiIntegrationTestCase;
use MWRestrictions;
use StatusValue;

/**
 * @covers \MediaWiki\Extension\OAuth\Control\ConsumerSubmitControl
 * @group Database
 */
class ConsumerSubmitControlTest extends MediaWikiIntegrationTestCase {

	private User $owner;

	/** @return OAuth1Consumer|ClientEntity|null */
	private function doSubmit( array $data, StatusValue $expectedStatus ) {
		$this->overrideConfigValues( [
			'MWOAuthCentralWiki' => WikiMap::getCurrentWikiId(),
			'OAuthAutoApprove' => [ [
				'grants' => [ 'mwoauth-authonly', 'mwoauth-authonlyprivate', 'basic' ],
			] ],
		] );
		$this->setGroupPermissions( [
			'user' => [ 'mwoauthproposeconsumer' => true ]
		] );

		$context = RequestContext::getMain();
		$user = $this->getMutableTestUser()->getUser();
		$user->setEmail( 'owner@wiki.domain' );
		$user->confirmEmail();
		$user->saveSettings();
		$context->setUser( $user );
		$this->owner = $user;

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
		if ( !$expectedStatus->isGood() ) {
			$this->assertSame(
				Status::wrap( $expectedStatus )->getWikiText( false, false, 'en' ),
				Status::wrap( $actualStatus )->getWikiText( false, false, 'en' ) );
		}

		return $actualStatus->getValue()['result']['consumer'] ?? null;
	}

	private function getBaseForOAuth1() {
		return [
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
	}

	public function testSubmit_OAuth1_good() {
		$consumer = $this->doSubmit( $this->getBaseForOAuth1(), StatusValue::newGood() );
		$this->assertInstanceOf( OAuth1Consumer::class, $consumer );
		$this->assertSame( $this->owner->getId(), $consumer->getUserId() );
		$this->assertFalse( $consumer->getOwnerOnly() );
		$this->assertSame( [ 'basic', 'editpage' ], $consumer->getGrants() );
		$this->assertSame( Consumer::STAGE_PROPOSED, $consumer->getStage() );
	}

	public function testSubmit_OAuth1_autoApproved() {
		$consumer = $this->doSubmit(
			[
				'grants' => json_encode( [ 'basic' ] ),
			] + $this->getBaseForOAuth1(),
			StatusValue::newGood()
		);
		$this->assertSame( [ 'basic' ], $consumer->getGrants() );
		$this->assertSame( Consumer::STAGE_APPROVED, $consumer->getStage() );
	}

	public function testSubmit_OAuth1_invalidVersionString() {
		$this->doSubmit(
			[
				'version' => 'foo',
			] + $this->getBaseForOAuth1(),
			StatusValue::newFatal( 'mwoauth-invalid-field', 'version' )
		);
	}

	public function testSubmit_OAuth1_bareDomain() {
		$this->doSubmit(
			[
				'callbackUrl' => 'https://example.com/',
			] + $this->getBaseForOAuth1(),
			StatusValue::newFatal( 'mwoauth-error-callback-bare-domain-oauth1' ),
		);
	}

	public function testSubmit_OAuth1_ignoreWarwnings() {
		$this->doSubmit(
			[
				'callbackUrl' => 'https://example.com/',
				'ignorewarnings' => true,
			] + $this->getBaseForOAuth1(),
			StatusValue::newGood()
		);
	}

	private function getBaseForOAuth2() {
		return [
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
	}

	public static function provideSubmit_OAuth2_good() {
		yield 'good' => [];

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
				]
			];
		}
	}

	/**
	 * @dataProvider provideSubmit_OAuth2_good
	 */
	public function testSubmit_OAuth2_good( array $data = [] ) {
		$consumer = $this->doSubmit(
			$data + $this->getBaseForOAuth2(),
			StatusValue::newGood()
		);
		$this->assertInstanceOf( ClientEntity::class, $consumer );
		$this->assertSame( $this->owner->getId(), $consumer->getUserId() );
		$this->assertFalse( $consumer->getOwnerOnly() );
		$this->assertSame( [ 'basic', 'editpage' ], $consumer->getGrants() );
	}

	public function testSubmit_OAuth2_nonLocalhostHttpNotAlllowed() {
		$this->doSubmit(
			[
				'callbackUrl' => 'http://example.com',
			] + $this->getBaseForOAuth2(),
			StatusValue::newFatal( 'mwoauth-error-callback-url-must-be-https' )
		);
	}
}
