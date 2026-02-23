<?php

namespace MediaWiki\Extension\OAuth\Tests\Integration\Control;

use CentralAuthTestUser;
use MediaWiki\Context\RequestContext;
use MediaWiki\Extension\CentralAuth\User\CentralAuthUser;
use MediaWiki\Extension\OAuth\Backend\Consumer;
use MediaWiki\Extension\OAuth\Backend\OAuth1Consumer;
use MediaWiki\Extension\OAuth\Control\ConsumerSubmitControl;
use MediaWiki\Extension\OAuth\Entity\ClientEntity;
use MediaWiki\Registration\ExtensionRegistry;
use MediaWiki\Status\Status;
use MediaWiki\User\User;
use MediaWiki\Utils\MWRestrictions;
use MediaWiki\WikiMap\WikiMap;
use MediaWikiIntegrationTestCase;
use StatusValue;

/**
 * @covers \MediaWiki\Extension\OAuth\Control\ConsumerSubmitControl
 * @group Database
 */
class ConsumerSubmitControlTest extends MediaWikiIntegrationTestCase {

	private User $owner;

	public function setUp(): void {
		parent::setUp();
		$this->overrideConfigValue( 'MWOAuthSharedUserSource', 'local' );
	}

	private function createCentralUserAccount( User $user ): void {
		// Ensure we have a central user, if not, create one.
		$caUser = CentralAuthUser::getPrimaryInstanceByName( $user->getName() );
		if ( $caUser->exists() ) {
			// do nothing.
		} else {
			$centralUser = new CentralAuthTestUser(
				$user->getName(),
				'GUCP@ssword',
				[ 'gu_email' => $user->getEmail() ],
				[ [ WikiMap::getCurrentWikiId(), 'primary' ] ]
			);
			$centralUser->save( $this->getDb() );
		}
	}

	/** @return OAuth1Consumer|ClientEntity|null */
	private function doSubmit( array $data, StatusValue $expectedStatus, ?User $user = null ) {
		$this->overrideConfigValues( [
			'MWOAuthCentralWiki' => WikiMap::getCurrentWikiId(),
			'OAuthAutoApprove' => [ [
				'grants' => [ 'mwoauth-authonly', 'mwoauth-authonlyprivate', 'basic' ],
			] ],
		] );
		$this->setGroupPermissions( [
			'user' => [
				'mwoauthproposeconsumer' => true,
				'mwoauthupdateownconsumer' => true,
			],
		] );

		$context = RequestContext::getMain();
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

	private function getNonOwnerOnlyOAuth1ConsumerFormData() {
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

	private function getOwnerOnlyOAuth1ConsumerFormData() {
		return [
			'oauthVersion' => Consumer::OAUTH_VERSION_1,
			'name' => 'test consumer',
			'version' => '1.0',
			'description' => 'test',
			'ownerOnly' => true,
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

	public function testSubmitOAuth1Good() {
		$user = $this->getMutableTestUser()->getUser();
		$consumer = $this->doSubmit(
			$this->getNonOwnerOnlyOAuth1ConsumerFormData(), StatusValue::newGood(), $user
		);
		$this->assertInstanceOf( OAuth1Consumer::class, $consumer );
		$this->assertSame( $this->owner->getId(), $consumer->getUserId() );
		$this->assertFalse( $consumer->getOwnerOnly() );
		$this->assertSame( [ 'basic', 'editpage' ], $consumer->getGrants() );
		$this->assertSame( Consumer::STAGE_PROPOSED, $consumer->getStage() );
	}

	public function testSubmitOAuth1AutoApproved() {
		$user = $this->getMutableTestUser()->getUser();
		$consumer = $this->doSubmit(
			[
				'grants' => json_encode( [ 'basic' ] ),
			] + $this->getNonOwnerOnlyOAuth1ConsumerFormData(),
			StatusValue::newGood(),
			$user
		);
		$this->assertSame( [ 'basic' ], $consumer->getGrants() );
		$this->assertSame( Consumer::STAGE_APPROVED, $consumer->getStage() );
	}

	public function testSubmitOAuth1InvalidVersionString() {
		$user = $this->getMutableTestUser()->getUser();
		$this->doSubmit(
			[
				'version' => 'foo',
			] + $this->getNonOwnerOnlyOAuth1ConsumerFormData(),
			StatusValue::newFatal( 'mwoauth-invalid-field', 'version' ),
			$user
		);
	}

	public function testSubmitOAuth1BareDomain() {
		$user = $this->getMutableTestUser()->getUser();
		$this->doSubmit(
			[
				'callbackUrl' => 'https://example.com/',
			] + $this->getNonOwnerOnlyOAuth1ConsumerFormData(),
			StatusValue::newFatal( 'mwoauth-error-callback-bare-domain-oauth1' ),
			$user
		);
	}

	public function testSubmitOAuth1IgnoreWarwnings() {
		$user = $this->getMutableTestUser()->getUser();
		$this->doSubmit(
			[
				'callbackUrl' => 'https://example.com/',
				'ignorewarnings' => true,
			] + $this->getNonOwnerOnlyOAuth1ConsumerFormData(),
			StatusValue::newGood(),
			$user
		);
	}

	private function getNonOwnerOnlyOAuth2ConsumerFormData() {
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

	private function getOwnerOnlyOAuth2ConsumerFormData() {
		return [
			'oauthVersion' => Consumer::OAUTH_VERSION_2,
			'name' => 'test',
			'version' => '1.0',
			'description' => 'test',
			'ownerOnly' => true,
			'callbackUrl' => 'https://example.com/oauth',
			'callbackIsPrefix' => null,
			'email' => 'owner@wiki.domain',
			'wiki' => '*',
			'oauth2IsConfidential' => true,
			'oauth2GrantTypes' => [ 'client_credentials' ],
			'granttype' => 'normal',
			'grants' => json_encode( [ 'editpage' ] ),
			'restrictions' => MWRestrictions::newDefault(),
			'rsaKey' => '',
			'agreement' => true,
			'action' => 'propose',
		];
	}

	public static function provideSubmitOAuth2Good() {
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
	 * @dataProvider provideSubmitOAuth2Good
	 */
	public function testSubmitOAuth2Good( array $data = [] ) {
		$user = $this->getMutableTestUser()->getUser();
		$consumer = $this->doSubmit(
			$data + $this->getNonOwnerOnlyOAuth2ConsumerFormData(),
			StatusValue::newGood(),
			$user
		);
		$this->assertInstanceOf( ClientEntity::class, $consumer );
		$this->assertSame( $this->owner->getId(), $consumer->getUserId() );
		$this->assertFalse( $consumer->getOwnerOnly() );
		$this->assertSame( [ 'basic', 'editpage' ], $consumer->getGrants() );
	}

	public function testSubmitOAuth2NonLocalhostHttpNotAlllowed() {
		$user = $this->getMutableTestUser()->getUser();
		$this->doSubmit(
			[
				'callbackUrl' => 'http://example.com',
			] + $this->getNonOwnerOnlyOAuth2ConsumerFormData(),
			StatusValue::newFatal( 'mwoauth-error-callback-url-must-be-https' ),
			$user
		);
	}

	/** @dataProvider provideOAuthOwnerOnlyFormData */
	public function testSubmitOAuthOwnerOnly(
		$formData,
		$expectedClassInstance,
		$expectedOwnerOnly,
		$expectedGrants,
		$expectedStage
	) {
		$this->assertCentralAuthExtensionInstalled();
		$user = $this->getMutableTestUser()->getUser();
		$this->createCentralUserAccount( $user );
		$consumer = $this->doSubmit( $formData, StatusValue::newGood(), $user );
		$this->assertInstanceOf( $expectedClassInstance, $consumer );
		$this->assertSame( $this->owner->getId(), $consumer->getUserId() );
		$this->assertSame( $expectedOwnerOnly, $consumer->getOwnerOnly() );
		$this->assertSame( $expectedGrants, $consumer->getGrants() );

		// Owner-only consumers are automatically approved.
		$this->assertSame( $expectedStage, $consumer->getStage() );
	}

	public static function provideOAuthOwnerOnlyFormData() {
		/* [ form data ], class instance, owner only, [ grants ] */
		yield 'OAuth2 consumer owner only' => [
			[
				'oauthVersion' => Consumer::OAUTH_VERSION_2,
				'name' => 'test',
				'version' => '1.0',
				'description' => 'test',
				'ownerOnly' => true,
				'callbackUrl' => 'https://example.com/oauth',
				'callbackIsPrefix' => null,
				'email' => 'owner@wiki.domain',
				'wiki' => '*',
				'oauth2IsConfidential' => true,
				'oauth2GrantTypes' => [ 'client_credentials' ],
				'granttype' => 'normal',
				'grants' => json_encode( [ 'editpage' ] ),
				'restrictions' => MWRestrictions::newDefault(),
				'rsaKey' => '',
				'agreement' => true,
				'action' => 'propose',
			], ClientEntity::class, true, [ 'basic', 'editpage' ], Consumer::STAGE_APPROVED
		];
		yield 'OAuth1 consumer owner only' => [
			[
				'oauthVersion' => Consumer::OAUTH_VERSION_1,
				'name' => 'test consumer',
				'version' => '1.0',
				'description' => 'test',
				'ownerOnly' => true,
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
			], OAuth1Consumer::class, true, [ 'basic', 'editpage' ], Consumer::STAGE_APPROVED
		];
	}

	/** @dataProvider provideUpdateOAuth1OwnerOnlyFormData */
	public function testUpdateOAuth1OwnerOnly(
		$formData,
		$expectedClassInstance,
		$expectedOwnerOnly,
		$expectedGrants,
		$expectedStage
	) {
		$this->assertCentralAuthExtensionInstalled();
		$user = $this->getTestSysop()->getUser();
		$consumer = $this->doSubmit(
			$this->getOwnerOnlyOAuth1ConsumerFormData(),
			StatusValue::newGood(),
			$user
		);
		$updateFormData = [
			'consumerKey' => $consumer->getConsumerKey(),
			'reason' => 'test',
			'changeToken' => $consumer->getChangeToken( RequestContext::getMain() ),
			'resetSecret' => false,
		];
		$consumer = $this->doSubmit(
			$formData + $updateFormData,
			StatusValue::newGood(),
			$user
		);
		$this->assertInstanceOf( $expectedClassInstance, $consumer );
		$this->assertSame( $this->owner->getId(), $consumer->getUserId() );
		$this->assertSame( $expectedOwnerOnly, $consumer->getOwnerOnly() );
		$this->assertSame( $expectedGrants, $consumer->getGrants() );

		// Owner-only consumers are automatically approved.
		$this->assertSame( $expectedStage, $consumer->getStage() );
	}

	public static function provideUpdateOAuth1OwnerOnlyFormData() {
		/* [ form data ], class instance, owner only, [ grants ] */
		yield 'OAuth1 consumer owner only' => [
			[
				'oauthVersion' => Consumer::OAUTH_VERSION_1,
				'name' => 'test consumer',
				'version' => '1.0',
				'description' => 'test',
				'ownerOnly' => true,
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
				'action' => 'update',
			], OAuth1Consumer::class, true, [ 'basic', 'editpage' ], Consumer::STAGE_APPROVED
		];
	}

	/** @dataProvider provideUpdateOAuth2OwnerOnlyFormData */
	public function testUpdateOAuth2OwnerOnly(
		$formData,
		$expectedClassInstance,
		$expectedOwnerOnly,
		$expectedGrants,
		$expectedStage
	) {
		$this->assertCentralAuthExtensionInstalled();
		$user = $this->getTestSysop()->getUser();
		$this->createCentralUserAccount( $user );
		$consumer = $this->doSubmit(
			$this->getOwnerOnlyOAuth2ConsumerFormData(),
			StatusValue::newGood(),
			$user
		);
		$updateFormData = [
			'consumerKey' => $consumer->getConsumerKey(),
			'reason' => 'test',
			'changeToken' => $consumer->getChangeToken( RequestContext::getMain() ),
			'resetSecret' => false,
		];
		$consumer = $this->doSubmit(
			$formData + $updateFormData,
			StatusValue::newGood(),
			$user
		);
		$this->assertInstanceOf( $expectedClassInstance, $consumer );
		$this->assertSame( $this->owner->getId(), $consumer->getUserId() );
		$this->assertSame( $expectedOwnerOnly, $consumer->getOwnerOnly() );
		$this->assertSame( $expectedGrants, $consumer->getGrants() );

		// Owner-only consumers are automatically approved.
		$this->assertSame( $expectedStage, $consumer->getStage() );
	}

	public static function provideUpdateOAuth2OwnerOnlyFormData() {
		/* [ form data ], class instance, owner only, [ grants ] */
		yield 'OAuth2 consumer owner only' => [
			[
				'oauthVersion' => Consumer::OAUTH_VERSION_2,
				'name' => 'test consumer',
				'version' => '1.0',
				'description' => 'test',
				'ownerOnly' => true,
				'callbackUrl' => 'https://example.com/oauth',
				'callbackIsPrefix' => false,
				'email' => 'owner@wiki.domain',
				'wiki' => '*',
				'oauth2IsConfidential' => null,
				'oauth2GrantTypes' => [ 'client_credentials' ],
				'granttype' => 'normal',
				'grants' => json_encode( [ 'editpage' ] ),
				'restrictions' => MWRestrictions::newDefault(),
				'rsaKey' => '',
				'agreement' => true,
				'action' => 'update',
			], ClientEntity::class, true, [ 'basic', 'editpage' ], Consumer::STAGE_APPROVED
		];
	}

	private function assertCentralAuthExtensionInstalled() {
		if ( !ExtensionRegistry::getInstance()->isLoaded( 'CentralAuth' ) ) {
			$this->markTestSkipped( 'CentralAuth is not loaded' );
		}
	}
}
