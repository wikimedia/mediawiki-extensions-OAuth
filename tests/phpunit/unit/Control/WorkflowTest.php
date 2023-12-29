<?php

namespace MediaWiki\Extension\OAuth\Tests\Control;

use MediaWiki\Config\HashConfig;
use MediaWiki\Config\ServiceOptions;
use MediaWiki\Extension\OAuth\Backend\Consumer;
use MediaWiki\Extension\OAuth\Control\Workflow;
use MediaWikiUnitTestCase;

/**
 * @covers \MediaWiki\Extension\OAuth\Control\Workflow
 */
class WorkflowTest extends MediaWikiUnitTestCase {

	/**
	 * @dataProvider provideConsumerCanBeAutoApproved
	 */
	public function testConsumerCanBeAutoApproved(
		array $oauthAutoApprove,
		array $consumerGrants,
		bool $expectedResult
	) {
		$config = new HashConfig( [ 'OAuthAutoApprove' => $oauthAutoApprove ] );
		$consumer = $this->createNoOpMock( Consumer::class, [ 'getGrants' ] );
		$consumer->method( 'getGrants' )->willReturn( $consumerGrants );
		$workflow = new Workflow( new ServiceOptions( Workflow::CONSTRUCTOR_OPTIONS, $config ) );
		$this->assertSame( $expectedResult, $workflow->consumerCanBeAutoApproved( $consumer ) );
	}

	public static function provideConsumerCanBeAutoApproved() {
		return [
			'empty' => [ [], [ 'basic' ], false ],
			'good grants' => [ [ [ 'grants' => [ 'basic', 'editpage' ] ] ], [ 'basic' ], true ],
			'bad grants' => [ [ [ 'grants' => [ 'basic', 'editpage' ] ] ], [ 'import' ], false ],
			'good and bad grants' => [ [ [ 'grants' => [ 'basic', 'editpage' ] ] ], [ 'basic', 'import' ], false ],
			'multiple conditions 1' => [ [
				[ 'grants' => [ 'basic', 'editpage' ] ],
				[ 'grants' => [ 'import' ] ],
			], [ 'import' ], true ],
			'multiple conditions 2' => [ [
				[ 'grants' => [ 'basic', 'editpage' ] ],
				[ 'grants' => [ 'import' ] ],
			], [ 'basic', 'editpage', 'import' ], false ],
			'unknown rule' => [ [ [
				'grants' => [ 'basic', 'editpage' ],
				'unknown' => true,
			] ], [ 'basic' ], false ],
		];
	}

}
