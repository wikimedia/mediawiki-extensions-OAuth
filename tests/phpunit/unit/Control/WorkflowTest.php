<?php

namespace MediaWiki\Extension\OAuth\Tests\Control;

use MediaWiki\Config\HashConfig;
use MediaWiki\Config\ServiceOptions;
use MediaWiki\Extension\OAuth\Backend\Consumer;
use MediaWiki\Extension\OAuth\Control\Workflow;
use MediaWiki\Utils\UrlUtils;
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
		string $consumerCallbackUrl,
		bool $expectedResult
	) {
		$config = new HashConfig( [ 'OAuthAutoApprove' => $oauthAutoApprove ] );
		$consumer = $this->createNoOpMock( Consumer::class, [ 'getGrants', 'getCallbackUrl' ] );
		$consumer->method( 'getGrants' )->willReturn( $consumerGrants );
		$consumer->method( 'getCallbackUrl' )->willReturn( $consumerCallbackUrl );
		$workflow = new Workflow( new ServiceOptions( Workflow::CONSTRUCTOR_OPTIONS, $config ),
			new UrlUtils( [ UrlUtils::VALID_PROTOCOLS => UrlUtils::ALL_PROTOCOLS ] ) );
		$this->assertSame( $expectedResult, $workflow->consumerCanBeAutoApproved( $consumer ) );
	}

	public static function provideConsumerCanBeAutoApproved() {
		$boringCallbackUrl = 'https://example.com/oauth';
		$basicGrants = [ 'basic' ];
		return [
			'empty' => [
				[],
				$basicGrants,
				$boringCallbackUrl,
				false
			],
			'good grants' => [
				[ [ 'grants' => [ 'basic', 'editpage' ] ] ],
				$basicGrants,
				$boringCallbackUrl,
				true
			],
			'bad grants' => [
				[ [ 'grants' => [ 'basic', 'editpage' ] ] ],
				[ 'import' ],
				$boringCallbackUrl,
				false
			],
			'good and bad grants' => [
				[ [ 'grants' => [ 'basic', 'editpage' ] ] ],
				[ 'basic', 'import' ],
				$boringCallbackUrl,
				false
			],
			'multiple conditions 1' => [
				[
					[ 'grants' => [ 'basic', 'editpage' ] ],
					[ 'grants' => [ 'import' ] ],
				],
				[ 'import' ],
				$boringCallbackUrl,
				true
			],
			'multiple conditions 2' => [
				[
					[ 'grants' => [ 'basic', 'editpage' ] ],
					[ 'grants' => [ 'import' ] ],
				],
				[ 'basic', 'editpage', 'import' ],
				$boringCallbackUrl,
				false
			],
			'allowed protocol' => [
				[ [ 'protocols' => [ 'http', 'https' ] ] ],
				$basicGrants,
				$boringCallbackUrl,
				true
			],
			'disallowed protocol' => [
				[ [ 'protocols' => [ 'http', 'https' ] ] ],
				$basicGrants,
				'example.app:oauth',
				false
			],
			'unknown rule' => [
				[ [
					'grants' => [ 'basic', 'editpage' ],
					'unknown' => true,
				] ],
				$basicGrants,
				$boringCallbackUrl,
				false
			],
		];
	}

}
