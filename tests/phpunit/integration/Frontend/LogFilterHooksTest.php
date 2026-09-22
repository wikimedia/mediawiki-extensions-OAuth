<?php

namespace MediaWiki\Extension\OAuth\Tests\Integration\Frontend;

use MediaWiki\Extension\OAuth\Frontend\LogFilterHooks;
use MediaWiki\Logging\LogEventsList;
use MediaWiki\Logging\ManualLogEntry;
use MediaWiki\MainConfigNames;
use MediaWiki\Request\FauxRequest;
use MediaWiki\SpecialPage\SpecialPage;
use MediaWiki\Tests\Specials\SpecialPageTestBase;

/**
 * @covers \MediaWiki\Extension\OAuth\Frontend\LogFilterHooks
 * @group Database
 * @group OAuth
 */
class LogFilterHooksTest extends SpecialPageTestBase {

	protected function newSpecialPage(): SpecialPage {
		return $this->getServiceContainer()->getSpecialPageFactory()->getPage( 'Log' );
	}

	private function newHooks(): LogFilterHooks {
		return new LogFilterHooks( $this->getServiceContainer()->getConnectionProvider() );
	}

	public function testHidesOwnerOnlyCreationsByDefault(): void {
		$qc = [];
		$this->newHooks()->onSpecialLogAddLogSearchRelations( 'mwoauthconsumer', new FauxRequest(), $qc );

		$dbr = $this->getServiceContainer()->getConnectionProvider()->getReplicaDatabase();
		$this->assertEquals( [ $dbr->expr( 'log_action', '!=', 'create-owner-only' ) ], $qc );
	}

	public function testKeepsExistingConditions(): void {
		$qc = [ 'log_namespace' => NS_USER ];
		$this->newHooks()->onSpecialLogAddLogSearchRelations( 'mwoauthconsumer', new FauxRequest(), $qc );

		$this->assertCount( 2, $qc );
		$this->assertSame( NS_USER, $qc['log_namespace'] );
	}

	public static function provideRequestsThatAreNotFiltered(): iterable {
		yield 'another log type' => [ 'newusers', [] ];
		yield 'all logs' => [ '', [] ];
		yield 'checkbox ticked' => [ 'mwoauthconsumer', [ 'showowneronly' => '1' ] ];
		yield 'owner-only creations picked in the action filter' => [
			'mwoauthconsumer', [ 'subtype' => 'create-owner-only' ]
		];
		yield 'one entry requested by ID' => [ 'mwoauthconsumer', [ 'logid' => '42' ] ];
	}

	/**
	 * @dataProvider provideRequestsThatAreNotFiltered
	 */
	public function testDoesNotFilter( string $type, array $query ): void {
		$qc = [];
		$this->newHooks()->onSpecialLogAddLogSearchRelations( $type, new FauxRequest( $query ), $qc );

		$this->assertSame( [], $qc );
	}

	public function testAddsCheckboxToOAuthConsumerLogOnly(): void {
		$logEventsList = $this->createNoOpMock( LogEventsList::class );
		$unused = '';

		$formDescriptor = [];
		$this->newHooks()->onLogEventsListGetExtraInputs( 'newusers', $logEventsList, $unused, $formDescriptor );
		$this->assertSame( [], $formDescriptor );

		$this->newHooks()->onLogEventsListGetExtraInputs(
			'mwoauthconsumer', $logEventsList, $unused, $formDescriptor
		);
		$this->assertSame( 'check', $formDescriptor['type'] );
		$this->assertSame( 'showowneronly', $formDescriptor['name'] );
		$this->assertFalse( $formDescriptor['default'] );
	}

	public static function provideSpecialLog(): iterable {
		yield 'default' => [ [], [ 'propose' ] ];
		yield 'checkbox ticked' => [ [ 'showowneronly' => '1' ], [ 'create-owner-only', 'propose' ] ];
		yield 'owner-only creations picked in the action filter' => [
			[ 'subtype' => 'create-owner-only' ], [ 'create-owner-only' ]
		];
	}

	/**
	 * @dataProvider provideSpecialLog
	 */
	public function testSpecialLog( array $query, array $expectedActions ): void {
		// The log type is only registered on the central wiki, which is not the case in CI
		$this->overrideConfigValues( [
			MainConfigNames::LogTypes => array_unique( [
				...$this->getConfVar( MainConfigNames::LogTypes ),
				'mwoauthconsumer',
			] ),
			MainConfigNames::ActionFilteredLogs => [
				'mwoauthconsumer' => [
					'create-owner-only' => [ 'create-owner-only' ],
					'propose' => [ 'propose' ],
				],
			] + $this->getConfVar( MainConfigNames::ActionFilteredLogs ),
		] );
		$performer = $this->getTestSysop()->getUser();
		foreach ( [ 'create-owner-only', 'propose' ] as $action ) {
			$logEntry = new ManualLogEntry( 'mwoauthconsumer', $action );
			$logEntry->setPerformer( $performer );
			$logEntry->setTarget( $performer->getUserPage() );
			$logEntry->setParameters( [ '4:consumer' => str_repeat( 'a', 32 ) ] );
			$logEntry->insert();
		}

		[ $html ] = $this->executeSpecialPage( 'mwoauthconsumer', new FauxRequest( $query ) );

		preg_match_all( '/data-mw-logaction="mwoauthconsumer\/([a-z-]+)"/', $html, $matches );
		$actions = $matches[1];
		sort( $actions );
		$this->assertSame( $expectedActions, $actions );
		$this->assertStringContainsString( '(mwoauthconsumer-log-show-owner-only)', $html );
	}
}
