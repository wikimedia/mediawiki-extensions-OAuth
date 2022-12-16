<?php

namespace MediaWiki\Extension\OAuth\Tests\Rest;

use MediaWiki\Extension\OAuth\Rest\Handler\Authorize;
use MediaWiki\Rest\Handler;

/**
 * @covers \MediaWiki\Extension\OAuth\Rest\Handler\Authorize
 * @group OAuth
 */
class AuthorizationEndpointTest extends EndpointTest {

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
