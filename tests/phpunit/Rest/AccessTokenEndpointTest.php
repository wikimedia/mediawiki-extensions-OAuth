<?php

namespace MediaWiki\Extensions\OAuth\Tests\Rest;

/**
 * @covers \MediaWiki\Extensions\OAuth\Rest\Handler\AccessToken
 */
class AccessTokenEndpointTest extends EndpointTest {
	public function provideTestViaRouter() {
		return [
			'normal' => [
				[
					'method' => 'POST',
					'uri' => self::makeUri( '/oauth2/access_token' ),
					'headers' => [
						'Content-Type' => 'application/json'
					],
					'postParams' => [
						'grant_type' => 'authorization_code',
						'client_id' => 'dummy'
					]
				],
				[
					'statusCode' => 401,
					'reasonPhrase' => 'Unauthorized',
					'protocolVersion' => '1.1'
				]
			],
			'method not allowed' => [
				[
					'method' => 'GET',
					'uri' => self::makeUri( '/oauth2/access_token' ),
				],
				[
					'statusCode' => 405,
					'reasonPhrase' => 'Method Not Allowed',
					'protocolVersion' => '1.1',
					'body' => '{"httpCode":405,"httpReason":"Method Not Allowed"}',
				]
			],
			'invalid grant type' => [
				[
					'method' => 'POST',
					'uri' => self::makeUri( '/oauth2/access_token' ),
					'postParams' => [
						'grant_type' => 'dummy',
						'client_id' => 'dummy'
					]
				],
				[
					'statusCode' => 400,
					'reasonPhrase' => 'Bad Request',
					'protocolVersion' => '1.1'
				]
			],
			'grant type missing' => [
				[
					'method' => 'POST',
					'uri' => self::makeUri( '/oauth2/access_token' ),
					'postParams' => [
						'client_id' => 'dummy'
					]
				],
				[
					'statusCode' => 400,
					'reasonPhrase' => 'Bad Request',
					'protocolVersion' => '1.1'
				]
			],
		];
	}
}
