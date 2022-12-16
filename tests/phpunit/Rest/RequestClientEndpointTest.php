<?php

namespace MediaWiki\Extension\OAuth\Tests\Rest;

use FormatJson;
use MediaWiki\Extension\OAuth\Rest\Handler\RequestClient;
use MediaWiki\Rest\Handler;
use MediaWiki\Rest\ResponseInterface;
use User;
use WikiMap;

/**
 * @covers \MediaWiki\Extension\OAuth\Rest\Handler\RequestClient
 * @group Database
 * @group OAuth
 */
class RequestClientEndpointTest extends EndpointTest {

	/**
	 * @var array
	 */
	private $postParams = [
		'name' => 'TestName',
		'version' => '1.0',
		'description' => 'TestDescription',
		'wiki' => '*',
		'owner_only' => false,
		'callback_url' => 'https://test.com',
		'callback_is_prefix' => false,
		'email' => 'test@test.com',
		'is_confidential' => false,
		'grant_types' => [ 'client_credentials' ],
		'scopes' => [],
	];

	/**
	 * @var array
	 */
	private $postParamsOwnerOnlyRestriction = [
		'callback_url' => false,
	];

	/**
	 * @var array
	 */
	private $postParamsEmailMismatch = [
		'email' => '_test@test.com',
	];

	/**
	 * @var array
	 */
	private $postParamsWrongGrantTypes = [
		'owner_only' => true,
		'grant_types' => [ 'authorization_code', 'refresh_token' ],
	];

	/**
	 * @var array
	 */
	private $postParamsOwnerOnly = [
		'owner_only' => true,
	];

	/**
	 * @throws \Exception
	 */
	protected function setUp(): void {
		parent::setUp();

		$this->setMwGlobals( [
			'wgMWOAuthCentralWiki' => WikiMap::getCurrentWikiId(),
			'wgGroupPermissions' => [
				'*' => [ 'mwoauthproposeconsumer' => true ]
			],
			'wgEmailAuthentication' => false
		] );
		$this->tablesUsed[] = 'oauth_registered_consumer';
	}

	public function provideTestHandlerExecute() {
		return [
			'No POST params' => [
				[
					'method' => 'POST',
					'uri' => self::makeUri( '/oauth2/client' ),
					'postParams' => []
				],
				[
					'statusCode' => 400,
					'reasonPhrase' => 'Bad Request',
					'protocolVersion' => '1.1'
				]
			],
			'Not logged in' => [
				[
					'method' => 'POST',
					'uri' => self::makeUri( '/oauth2/client' ),
					'postParams' => $this->postParams,
					'headers' => [
						'Content-Type' => 'application/json'
					],
				],
				[
					'statusCode' => 400,
					'reasonPhrase' => 'Bad Request',
					'protocolVersion' => '1.1'
				],
			],
			'Email not confirmed' => [
				[
					'method' => 'POST',
					'uri' => self::makeUri( '/oauth2/client' ),
					'postParams' => $this->postParams,
					'headers' => [
						'Content-Type' => 'application/json'
					],
				],
				[
					'statusCode' => 400,
					'reasonPhrase' => 'Bad Request',
					'protocolVersion' => '1.1'
				],
				static function () {
					return User::createNew( 'RequestClientTestUser1' );
				}
			],
			'Missing Content-Type header' => [
				[
					'method' => 'POST',
					'uri' => self::makeUri( '/oauth2/client' ),
					'postParams' => $this->postParams,
					'headers' => [],
				],
				[
					'statusCode' => 415,
					'reasonPhrase' => 'Unsupported Media Type',
					'protocolVersion' => '1.1'
				],
				static function () {
					$user = User::createNew( 'RequestClientTestUser3' );
					$user->setEmail( 'test@test.com' );

					return $user;
				}
			],
			'Missing Callback URL for non-OwnerOnly client' => [
				[
					'method' => 'POST',
					'uri' => self::makeUri( '/oauth2/client' ),
					'postParams' => array_merge( $this->postParams, $this->postParamsOwnerOnlyRestriction ),
					'headers' => [
						'Content-Type' => 'application/json'
					],
				],
				[
					'statusCode' => 400,
					'reasonPhrase' => 'Bad Request',
					'protocolVersion' => '1.1'
				],
				static function () {
					$user = User::createNew( 'RequestClientTestUser4' );
					$user->setEmail( 'test@test.com' );

					return $user;
				}
			],
			'Email Mismatch' => [
				[
					'method' => 'POST',
					'uri' => self::makeUri( '/oauth2/client' ),
					'postParams' => array_merge( $this->postParams, $this->postParamsEmailMismatch ),
					'headers' => [
						'Content-Type' => 'application/json'
					],
				],
				[
					'statusCode' => 400,
					'reasonPhrase' => 'Bad Request',
					'protocolVersion' => '1.1'
				],
				static function () {
					$user = User::createNew( 'RequestClientTestUser5' );
					$user->setEmail( 'test@test.com' );

					return $user;
				}
			],
			'Invalid Grant Types for OwnerOnly client' => [
				[
					'method' => 'POST',
					'uri' => self::makeUri( '/oauth2/client' ),
					'postParams' => array_merge( $this->postParams, $this->postParamsWrongGrantTypes ),
					'headers' => [
						'Content-Type' => 'application/json'
					],
				],
				[
					'statusCode' => 400,
					'reasonPhrase' => 'Bad Request',
					'protocolVersion' => '1.1'
				],
				static function () {
					$user = User::createNew( 'RequestClientTestUser6' );
					$user->setEmail( 'test@test.com' );

					return $user;
				}
			],
			'Successful request' => [
				[
					'method' => 'POST',
					'uri' => self::makeUri( '/oauth2/client' ),
					'postParams' => $this->postParams,
					'headers' => [
						'Content-Type' => 'application/json'
					],
				],
				[
					'statusCode' => 200,
					'reasonPhrase' => 'OK',
					'protocolVersion' => '1.1'
				],
				static function () {
					$user = User::createNew( 'RequestClientTestUser2' );
					$user->setEmail( 'test@test.com' );

					return $user;
				}
			],
			'Successful request owner only' => [
				[
					'method' => 'POST',
					'uri' => self::makeUri( '/oauth2/client' ),
					'postParams' => array_merge( $this->postParams, $this->postParamsOwnerOnly ),
					'headers' => [
						'Content-Type' => 'application/json'
					],
				],
				[
					'statusCode' => 200,
					'reasonPhrase' => 'OK',
					'protocolVersion' => '1.1',
				],
				static function () {
					$user = User::createNew( 'RequestClientTestUser10' );
					$user->setEmail( 'test@test.com' );

					return $user;
				},
				function ( ResponseInterface $response ) {
					$responseBody = FormatJson::decode(
						$response->getBody()->getContents(),
						true
					);
					$this->assertArrayHasKey( 'access_token', $responseBody );
					$this->assertMatchesRegularExpression( '/((.*)\.(.*)\.(.*))/', $responseBody['access_token'] );
				},
			],
			'Successful scopes values' => [
				[
					'method' => 'POST',
					'uri' => self::makeUri( '/oauth2/client' ),
					'postParams' => [ 'scopes' => 'basic' ] + $this->postParams,
					'headers' => [
						'Content-Type' => 'application/json'
					],
				],
				[
					'statusCode' => 200,
					'reasonPhrase' => 'OK',
					'protocolVersion' => '1.1'
				],
				static function () {
					$user = User::createNew( 'RequestClientTestUser7' );
					$user->setEmail( 'test@test.com' );

					return $user;
				}
			],
			'Scope with mwoauth-authonly' => [
				[
					'method' => 'POST',
					'uri' => self::makeUri( '/oauth2/client' ),
					'postParams' => [ 'scopes' => 'mwoauth-authonly' ] + $this->postParams,
					'headers' => [
						'Content-Type' => 'application/json'
					],
				],
				[
					'statusCode' => 200,
					'reasonPhrase' => 'OK',
					'protocolVersion' => '1.1'
				],
				static function () {
					$user = User::createNew( 'RequestClientTestUser8' );
					$user->setEmail( 'test@test.com' );

					return $user;
				}
			],
			'Scope with mwoauth-authonlyprivate' => [
				[
					'method' => 'POST',
					'uri' => self::makeUri( '/oauth2/client' ),
					'postParams' => [ 'scopes' => 'mwoauth-authonlyprivate' ] + $this->postParams,
					'headers' => [
						'Content-Type' => 'application/json'
					],
				],
				[
					'statusCode' => 200,
					'reasonPhrase' => 'OK',
					'protocolVersion' => '1.1'
				],
				static function () {
					$user = User::createNew( 'RequestClientTestUser9' );
					$user->setEmail( 'test@test.com' );

					return $user;
				}
			],
			'Failed scopes values' => [
				[
					'method' => 'POST',
					'uri' => self::makeUri( '/oauth2/client' ),
					'postParams' => [ 'scopes' => 'wrong' ] + $this->postParams,
					'headers' => [
						'Content-Type' => 'application/json'
					],
				],
				[
					'statusCode' => 400,
					'reasonPhrase' => 'Bad Request',
					'protocolVersion' => '1.1'
				],
			],
		];
	}

	protected function newHandler(): Handler {
		return new RequestClient();
	}
}
