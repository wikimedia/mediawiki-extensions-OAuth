<?php

namespace MediaWiki\Extensions\OAuth\Tests\Rest;

use MediaWiki\Extensions\OAuth\Rest\Handler\Authorize;
use MediaWiki\Rest\Handler;

/**
 * @covers \MediaWiki\Extensions\OAuth\Rest\Handler\Authorize
 * @group OAuth
 */
class AuthorizationEndpointTest extends EndpointTest {
	/**
	 * @return array
	 */
	public function provideTestHandlerExecute() {
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
			]
		];
	}

	protected function newHandler(): Handler {
		return Authorize::factory();
	}
}
