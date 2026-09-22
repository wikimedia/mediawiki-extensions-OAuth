<?php

namespace MediaWiki\Extension\OAuth\Tests\Integration\Frontend;

use MediaWiki\Extension\OAuth\Backend\Consumer;
use MediaWiki\Extension\OAuth\Tests\ConsumerFixtureTrait;
use MediaWiki\Request\FauxRequest;
use MediaWiki\SpecialPage\SpecialPage;
use MediaWiki\Tests\Specials\SpecialPageTestBase;
use MediaWiki\WikiMap\WikiMap;

/**
 * @covers \MediaWiki\Extension\OAuth\Frontend\SpecialPages\SpecialMWOAuthManageConsumers
 * @covers \MediaWiki\Extension\OAuth\Backend\Utils::getConsumerStateCounts
 * @group Database
 * @group OAuth
 */
class SpecialMWOAuthManageConsumersTest extends SpecialPageTestBase {
	use ConsumerFixtureTrait;

	private Consumer $consumer;
	private Consumer $ownerOnlyConsumer;

	protected function setUp(): void {
		parent::setUp();
		$this->overrideConfigValues( [
			// The page only works on the central wiki
			'MWOAuthCentralWiki' => WikiMap::getCurrentWikiId(),
			'MWOAuthSharedUserSource' => 'local',
			// Keep the random auto-maintenance of list pages away from the fixtures
			'MWOAuthRequestExpirationAge' => 0,
		] );
		$this->setGroupPermissions( 'sysop', 'mwoauthmanageconsumer', true );

		$owner = $this->getTestUser()->getUserIdentity();
		$this->consumer = $this->createConsumer( $owner, 'Multi-user test consumer', false );
		$this->ownerOnlyConsumer = $this->createConsumer( $owner, 'Owner-only test consumer', true );
	}

	protected function newSpecialPage(): SpecialPage {
		return $this->getServiceContainer()->getSpecialPageFactory()->getPage( 'OAuthManageConsumers' );
	}

	private function executeAsAdmin( string $subPage, array $query = [] ): string {
		[ $html ] = $this->executeSpecialPage(
			$subPage,
			new FauxRequest( $query ),
			null,
			$this->getTestSysop()->getAuthority()
		);
		return $html;
	}

	public function testApprovedListHidesOwnerOnlyConsumersByDefault(): void {
		$html = $this->executeAsAdmin( 'approved' );

		$this->assertStringContainsString( $this->consumer->getConsumerKey(), $html );
		$this->assertStringNotContainsString( $this->ownerOnlyConsumer->getConsumerKey(), $html );
		$this->assertMatchesRegularExpression(
			'/href="[^"]*showowneronly=1[^"]*"[^>]*>\(mwoauthmanageconsumers-show-owner-only\)</',
			$html
		);
	}

	public function testApprovedListShowsOwnerOnlyConsumersOnRequest(): void {
		$html = $this->executeAsAdmin( 'approved', [ 'showowneronly' => '1' ] );

		$this->assertStringContainsString( $this->consumer->getConsumerKey(), $html );
		$this->assertStringContainsString( $this->ownerOnlyConsumer->getConsumerKey(), $html );
		// Only the owner-only consumer's row is marked as such
		$this->assertSame( 1, substr_count( $html, '(mwoauthlistconsumers-owner-only)' ) );
		$this->assertStringContainsString( '(mwoauthmanageconsumers-hide-owner-only)', $html );
		$this->assertStringNotContainsString( '(mwoauthmanageconsumers-show-owner-only)', $html );
	}

	public static function provideStages(): iterable {
		yield 'approved' => [ 'approved', true ];
		yield 'disabled' => [ 'disabled', true ];
		yield 'configuration-based' => [ 'configuration-based', true ];
		yield 'proposed' => [ 'proposed', false ];
		yield 'rejected' => [ 'rejected', false ];
		yield 'expired' => [ 'expired', false ];
	}

	/**
	 * @dataProvider provideStages
	 */
	public function testToggleIsOnlyShownOnListStages( string $stageKey, bool $expectToggle ): void {
		$html = $this->executeAsAdmin( $stageKey );

		$this->assertSame(
			$expectToggle,
			str_contains( $html, '(mwoauthmanageconsumers-show-owner-only)' )
		);
	}

	public function testMainHubCountsExcludeOwnerOnlyConsumers(): void {
		$owner = $this->getTestUser()->getUserIdentity();
		$this->createConsumer( $owner, 'Second owner-only test consumer', true );
		$this->createConsumer( $owner, 'Proposed test consumer', false, Consumer::STAGE_PROPOSED );

		// Rendering the hub also used to warn about the missing count of configuration-based consumers
		$html = $this->executeAsAdmin( '' );

		$this->assertMatchesRegularExpression( '/\(mwoauthmanageconsumers-l-approved\)<\/a> \[1\]/', $html );
		$this->assertMatchesRegularExpression( '/\(mwoauthmanageconsumers-q-proposed\)<\/a><\/b> \[1\]/', $html );
		$this->assertMatchesRegularExpression(
			'/\(mwoauthmanageconsumers-l-configuration-based\)<\/a> \[0\]/',
			$html
		);
	}

	public function testReviewFormSearchLinksIncludeOwnerOnlyConsumers(): void {
		$html = $this->executeAsAdmin( $this->ownerOnlyConsumer->getConsumerKey() );

		foreach ( [ 'mwoauthmanageconsumers-search-name', 'mwoauthmanageconsumers-search-publisher' ] as $msg ) {
			$this->assertMatchesRegularExpression(
				'/href="[^"]*showowneronly=1[^"]*"[^>]*>\(' . $msg . '\)</',
				$html
			);
		}
	}
}
