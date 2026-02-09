<?php

namespace MediaWiki\Extension\OAuth\Tests\Integration\Backend;

use MediaWiki\Extension\OAuth\Backend\MWOAuthException;
use MediaWikiIntegrationTestCase;

/**
 * @covers \MediaWiki\Extension\OAuth\Backend\MWOAuthException
 * @group OAuth
 */
class MWOAuthExceptionTest extends MediaWikiIntegrationTestCase {

	/**
	 * @dataProvider provideParameterHandling
	 */
	public function testParameterHandling(
		string $msg,
		array $params,
		string $expectedMessage,
		string $expectedNormalizedMessage,
		array $expectedContext
	) {
		$exception = new MWOAuthException( $msg, $params );
		$this->assertSame( $expectedMessage, $exception->getMessage() );
		$this->assertSame( $expectedNormalizedMessage, $exception->getNormalizedMessage() );
		$this->assertSame( $expectedContext, $exception->getMessageContext() );
	}

	public static function provideParameterHandling() {
		return [
			'empty' => [
				'msg' => 'mwoauth-invalid-field-generic',
				'params' => [],
				'expectedMessage' => 'Invalid value provided',
				'expectedNormalizedMessage' => 'Invalid value provided',
				'expectedContext' => [],
			],
			'numeric key' => [
				'msg' => 'mwoauth-missing-field',
				'params' => [ 'name' ],
				'expectedMessage' => 'Missing value for "name" field',
				'expectedNormalizedMessage' => 'Missing value for "name" field',
				'expectedContext' => [],
			],
			'named key' => [
				'msg' => 'mwoauth-missing-field',
				'params' => [ 'fieldname' => 'name' ],
				'expectedMessage' => 'Missing value for "name" field',
				'expectedNormalizedMessage' => 'Missing value for "{fieldname}" field',
				'expectedContext' => [ 'fieldname' => 'name' ],
			],
			'mixed keys' => [
				'msg' => 'mwoauth-missing-field',
				'params' => [ 'name', 'consumer' => 'abcd1234' ],
				'expectedMessage' => 'Missing value for "name" field',
				'expectedNormalizedMessage' => 'Missing value for "name" field',
				'expectedContext' => [ 'consumer' => 'abcd1234' ],
			],
		];
	}

}
