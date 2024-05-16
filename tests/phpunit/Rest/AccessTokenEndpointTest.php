<?php

namespace MediaWiki\Extension\OAuth\Tests\Rest;

use MediaWiki\Extension\OAuth\Rest\Handler\AccessToken;
use MediaWiki\Rest\Handler;

/**
 * @covers \MediaWiki\Extension\OAuth\Rest\Handler\AccessToken
 * @group OAuth
 * @group Database
 */
class AccessTokenEndpointTest extends EndpointTestBase {
	public static function provideTestHandlerExecute() {
		return [
			'normal' => [
				[
					'method' => 'POST',
					'uri' => self::makeUri( '/oauth2/access_token' ),
					'headers' => [
						'Content-Type' => 'application/x-www-form-urlencoded'
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

	protected function newHandler(): Handler {
		return AccessToken::factory();
	}
}
