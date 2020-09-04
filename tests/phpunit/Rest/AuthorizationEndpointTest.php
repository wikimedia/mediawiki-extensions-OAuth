<?php

namespace MediaWiki\Extensions\OAuth\Tests\Rest;

/**
 * @covers \MediaWiki\Extensions\OAuth\Rest\Handler\Authorize
 */
class AuthorizationEndpointTest extends EndpointTest {
	/**
	 * @return array
	 */
	public function provideTestViaRouter() {
		return [
			'redirect to login' => [
				[
					'method' => 'GET',
					'uri' => self::makeUri( '/oauth2/authorize' ),
					'queryParams' => [
						'client_id' => 'dummy',
						'response_type' => 'code'
					]
				],
				[
					'statusCode' => 307,
					'reasonPhrase' => 'Temporary Redirect',
					'protocolVersion' => '1.1'
				]
			],
			'method not allowed' => [
				[
					'method' => 'POST',
					'uri' => self::makeUri( '/oauth2/authorize' ),
				],
				[
					'statusCode' => 405,
					'reasonPhrase' => 'Method Not Allowed',
					'protocolVersion' => '1.1',
					'body' => '{"httpCode":405,"httpReason":"Method Not Allowed"}',
				]
			],
		];
	}
}
