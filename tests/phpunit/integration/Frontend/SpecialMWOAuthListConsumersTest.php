<?php

namespace MediaWiki\Extension\OAuth\Tests\Integration\Frontend;

use MediaWiki\Extension\OAuth\Backend\Consumer;
use MediaWiki\Extension\OAuth\Tests\ConsumerFixtureTrait;
use MediaWiki\Request\FauxRequest;
use MediaWiki\SpecialPage\SpecialPage;
use MediaWiki\Tests\Specials\SpecialPageTestBase;
use MediaWiki\WikiMap\WikiMap;

/**
 * @covers \MediaWiki\Extension\OAuth\Frontend\SpecialPages\SpecialMWOAuthListConsumers
 * @group Database
 * @group OAuth
 */
class SpecialMWOAuthListConsumersTest extends SpecialPageTestBase {
	use ConsumerFixtureTrait;

	private Consumer $consumer;
	private Consumer $ownerOnlyConsumer;

	protected function setUp(): void {
		parent::setUp();
		$this->overrideConfigValues( [
			'MWOAuthCentralWiki' => WikiMap::getCurrentWikiId(),
			'MWOAuthSharedUserSource' => 'local',
			// Keep the random auto-maintenance of list pages away from the fixtures
			'MWOAuthRequestExpirationAge' => 0,
		] );

		$owner = $this->getTestUser()->getUserIdentity();
		$this->consumer = $this->createConsumer( $owner, 'Multi-user test consumer', false );
		$this->ownerOnlyConsumer = $this->createConsumer( $owner, 'Owner-only test consumer', true );
	}

	protected function newSpecialPage(): SpecialPage {
		return $this->getServiceContainer()->getSpecialPageFactory()->getPage( 'OAuthListConsumers' );
	}

	private function getShowOwnerOnlyCheckbox( string $html ): string {
		$this->assertMatchesRegularExpression( '/<input[^>]*name=[\'"]showowneronly[\'"][^>]*>/', $html );
		preg_match( '/<input[^>]*name=[\'"]showowneronly[\'"][^>]*>/', $html, $matches );
		return $matches[0];
	}

	public function testOwnerOnlyConsumersAreHiddenByDefault(): void {
		[ $html ] = $this->executeSpecialPage();

		$this->assertStringContainsString( $this->consumer->getConsumerKey(), $html );
		$this->assertStringNotContainsString( $this->ownerOnlyConsumer->getConsumerKey(), $html );
		$this->assertStringContainsString( '(mwoauthlistconsumers-show-owner-only)', $html );
		$this->assertStringNotContainsString( 'checked', $this->getShowOwnerOnlyCheckbox( $html ) );
	}

	public function testOwnerOnlyConsumersAreListedOnRequest(): void {
		[ $html ] = $this->executeSpecialPage( '', new FauxRequest( [ 'showowneronly' => '1' ] ) );

		$this->assertStringContainsString( $this->consumer->getConsumerKey(), $html );
		$this->assertStringContainsString( $this->ownerOnlyConsumer->getConsumerKey(), $html );
		$this->assertStringContainsString( 'checked', $this->getShowOwnerOnlyCheckbox( $html ) );
	}

	public function testViewLinksKeepShowingOwnerOnlyConsumers(): void {
		[ $html ] = $this->executeSpecialPage( '', new FauxRequest( [ 'showowneronly' => '1' ] ) );

		$this->assertMatchesRegularExpression(
			'/view\/' . $this->ownerOnlyConsumer->getConsumerKey() . '[^"]*showowneronly=1/',
			$html
		);
	}

	public function testOwnerOnlyConsumerCanStillBeViewedDirectly(): void {
		[ $html ] = $this->executeSpecialPage( 'view/' . $this->ownerOnlyConsumer->getConsumerKey() );

		$this->assertStringContainsString( 'Owner-only test consumer', $html );
		$this->assertStringContainsString( '(mwoauthlistconsumers-owner-only)', $html );
	}
}
