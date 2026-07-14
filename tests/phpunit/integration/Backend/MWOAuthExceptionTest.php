<?php

namespace MediaWiki\Extension\OAuth\Tests\Integration\Backend;

use MediaWiki\Extension\OAuth\Backend\MWOAuthException;
use MediaWikiIntegrationTestCase;
use Wikimedia\Message\MessageValue;

/**
 * @covers \MediaWiki\Extension\OAuth\Backend\MWOAuthException
 * @group OAuth
 */
class MWOAuthExceptionTest extends MediaWikiIntegrationTestCase {

	/**
	 * @dataProvider provideParameterHandling
	 */
	public function testParameterHandling(
		MessageValue $msg,
		array $context,
		string $expectedMessage,
		string $expectedNormalizedMessage,
		array $expectedContext
	) {
		$exception = new MWOAuthException( $msg, $context );
		$this->assertSame( $expectedMessage, $exception->getMessage() );
		$this->assertSame( $expectedNormalizedMessage, $exception->getNormalizedMessage() );
		$this->assertSame( $expectedContext, $exception->getMessageContext() );
	}

	public static function provideParameterHandling() {
		return [
			'empty' => [
				'msg' => MessageValue::new( 'mwoauth-invalid-field-generic' ),
				'context' => [],
				'expectedMessage' => 'Invalid value provided',
				'expectedNormalizedMessage' => 'Invalid value provided',
				'expectedContext' => [],
			],
			'parameter only' => [
				'msg' => MessageValue::new( 'mwoauth-missing-field' )->params( 'name' ),
				'context' => [],
				'expectedMessage' => 'Missing value for "name" field',
				'expectedNormalizedMessage' => 'Missing value for "{parameter1}" field',
				'expectedContext' => [ 'parameter1' => 'name' ],
			],
			'parameter and context' => [
				'msg' => MessageValue::new( 'mwoauth-missing-field' )->params( 'name' ),
				'context' => [ 'fieldname' => 'name' ],
				'expectedMessage' => 'Missing value for "name" field',
				'expectedNormalizedMessage' => 'Missing value for "{parameter1}" field',
				'expectedContext' => [ 'parameter1' => 'name', 'fieldname' => 'name' ],
			],
			'multiple parameters and context' => [
				'msg' => MessageValue::new( 'mwoauth-missing-field' )->params( 'name', 'abcd1234' ),
				'context' => [ 'consumer' => 'abcd1234' ],
				'expectedMessage' => 'Missing value for "name" field',
				'expectedNormalizedMessage' => 'Missing value for "{parameter1}" field',
				'expectedContext' => [ 'parameter1' => 'name', 'parameter2' => 'abcd1234', 'consumer' => 'abcd1234' ],
			],
		];
	}

}
