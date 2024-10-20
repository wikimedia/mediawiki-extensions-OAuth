<?php

namespace MediaWiki\Extension\OAuth\Tests\Rest;

use Exception;
use MediaWiki\Extension\OAuth\Backend\Consumer;
use MediaWiki\Extension\OAuth\Backend\Utils;
use MediaWiki\Extension\OAuth\Rest\Handler\ResetClientSecret;
use MediaWiki\Json\FormatJson;
use MediaWiki\MainConfigNames;
use MediaWiki\Rest\Handler;
use MediaWiki\Rest\ResponseInterface;
use MediaWiki\User\User;
use MediaWiki\WikiMap\WikiMap;
use MediaWikiIntegrationTestCase;
use MWRestrictions;

/**
 * @covers \MediaWiki\Extension\OAuth\Rest\Handler\ResetClientSecret
 * @group Database
 * @group OAuth
 */
class ResetClientSecretEndpointTest extends EndpointTestBase {

	/**
	 * @var array
	 */
	protected const DEFAULT_CONSUMER_DATA = [
		'id' => null,
		'consumerKey' => null,
		'name' => 'rc_test_name',
		'userId' => null,
		'version' => '1',
		'callbackUrl' => 'https://test.com',
		'callbackIsPrefix' => null,
		'description' => 'test_description',
		'email' => 'test@test.com',
		'emailAuthenticated' => 1577836800,
		'oauthVersion' => 1,
		'developerAgreement' => 1,
		'ownerOnly' => false,
		'wiki' => '*',
		'grants' => '["test"]',
		'registration' => 1577836800,
		'secretKey' => 'sk111111111111111111111111111111',
		'rsaKey' => '',
		'restrictions' => '{"IPAddresses": ["127.0.0.1"]}',
		'stage' => 1,
		'stageTimestamp' => 1577836800,
		'deleted' => 0,
		'oauth2IsConfidential' => 1,
		'oauth2GrantTypes' => null,
	];

	/**
	 * @var array
	 */
	private const OWNER_ONLY_CONSUMER_DATA = [
		'ownerOnly' => true,
		'oauth2GrantTypes' => [ 'client_credentials' ],
	];

	/**
	 * @throws Exception
	 */
	protected function setUp(): void {
		parent::setUp();

		$this->overrideConfigValues( [
			'MWOAuthCentralWiki' => WikiMap::getCurrentWikiId(),
			MainConfigNames::GroupPermissions => [
				'*' => [ 'mwoauthupdateownconsumer' => true ]
			],
		] );
	}

	public static function provideTestHandlerExecute() {
		return [
			'Unsupported Media Type' => [
				[
					'method' => 'POST',
					'uri' => self::makeUri( '/oauth2/client/INVALID_CLIENT_KEY/reset_secret' ),
					'pathParams' => [ 'client_key' => 'INVALID_CLIENT_KEY' ]
				],
				[
					'statusCode' => 400,
					'reasonPhrase' => 'Bad Request',
					'protocolVersion' => '1.1'
				]
			],
			'Missing Content-Type header' => [
				[
					'method' => 'POST',
					'uri' => self::makeUri( '/oauth2/client/11111111111111111111111111111111/reset_secret' ),
					'pathParams' => [ 'client_key' => '11111111111111111111111111111111' ],
					'headers' => [],
				],
				[
					'statusCode' => 400,
					'reasonPhrase' => 'Bad Request',
					'protocolVersion' => '1.1'
				]
			],
			'Invalid client key' => [
				[
					'method' => 'POST',
					'uri' => self::makeUri( '/oauth2/client/22222222222222222222222222222222/reset_secret' ),
					'headers' => [
						'Content-Type' => 'application/json'
					],
					'pathParams' => [ 'client_key' => '444444444' ],
				],
				[
					'statusCode' => 400,
					'reasonPhrase' => 'Bad Request',
					'protocolVersion' => '1.1'
				],
			],
			'Deleted Consumer Request' => [
				[
					'method' => 'POST',
					'uri' => self::makeUri( '/oauth2/client/11111111111111111111111111111111/reset_secret' ),
					'pathParams' => [ 'client_key' => '11111111111111111111111111111111' ],
					'headers' => [
						'Content-Type' => 'application/json'
					],
				],
				[
					'statusCode' => 401,
					'reasonPhrase' => 'Unauthorized',
					'protocolVersion' => '1.1'
				],
				static function () {
					$user = User::createNew( 'ResetClientSecretTestUser1' );
					$centralId = Utils::getCentralIdFromUserName( $user->getName() );
					$db = Utils::getCentralDB( DB_PRIMARY );

					$consumerData = self::DEFAULT_CONSUMER_DATA;
					$consumerData['userId'] = $centralId;
					$consumerData['consumerKey'] = '11111111111111111111111111111111';
					$consumerData['deleted'] = true;
					$consumerData['restrictions'] = MWRestrictions::newFromJson( $consumerData['restrictions'] );
					Consumer::newFromArray( $consumerData )->save( $db );

					return $user;
				}
			],
			'User Mismatch Request' => [
				[
					'method' => 'POST',
					'uri' => self::makeUri( '/oauth2/client/22222222222222222222222222222222/reset_secret' ),
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
					$user = User::createNew( 'ResetClientSecretTestUser2' );
					$db = Utils::getCentralDB( DB_PRIMARY );

					$consumerData = self::DEFAULT_CONSUMER_DATA;
					$consumerData['userId'] = 999;
					$consumerData['consumerKey'] = '22222222222222222222222222222222';
					$consumerData['deleted'] = false;
					$consumerData['name'] = 'test_name_user_mismatch';
					$consumerData['restrictions'] = MWRestrictions::newFromJson( $consumerData['restrictions'] );
					Consumer::newFromArray( $consumerData )->save( $db );

					return $user;
				}
			],
			'Successful Request OAuth 1' => [
				[
					'method' => 'POST',
					'uri' => self::makeUri( '/oauth2/client/33333333333333333333333333333333/reset_secret' ),
					'pathParams' => [ 'client_key' => '33333333333333333333333333333333' ],
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
					$user = User::createNew( 'ResetClientSecretTestUser3' );
					$centralId = Utils::getCentralIdFromUserName( $user->getName() );
					$db = Utils::getCentralDB( DB_PRIMARY );

					$consumerData = self::DEFAULT_CONSUMER_DATA;
					$consumerData['userId'] = $centralId;
					$consumerData['consumerKey'] = '33333333333333333333333333333333';
					$consumerData['name'] = 'test_name_user_successful';
					$consumerData['restrictions'] = MWRestrictions::newFromJson( $consumerData['restrictions'] );
					Consumer::newFromArray( $consumerData )->save( $db );

					return $user;
				}
			],
			'Successful Request OAuth 2' => [
				[
					'method' => 'POST',
					'uri' => self::makeUri( '/oauth2/client/44444444444444444444444444444444/reset_secret' ),
					'pathParams' => [ 'client_key' => '44444444444444444444444444444444' ],
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
					$user = User::createNew( 'ResetClientSecretTestUser4' );
					$centralId = Utils::getCentralIdFromUserName( $user->getName() );
					$db = Utils::getCentralDB( DB_PRIMARY );

					$consumerData = self::DEFAULT_CONSUMER_DATA;
					$consumerData['userId'] = $centralId;
					$consumerData['consumerKey'] = '44444444444444444444444444444444';
					$consumerData['name'] = 'test_name_user_successful';
					$consumerData['oauthVersion'] = '2';
					$consumerData['restrictions'] = MWRestrictions::newFromJson( $consumerData['restrictions'] );
					Consumer::newFromArray( $consumerData )->save( $db );

					return $user;
				}
			],
			'Successful Request OAuth 2 Owner Only' => [
				[
					'method' => 'POST',
					'uri' => self::makeUri( '/oauth2/client/55555555555555555555555555555555/reset_secret' ),
					'pathParams' => [ 'client_key' => '55555555555555555555555555555555' ],
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
					$user = User::createNew( 'ResetClientSecretTestUser5' );
					$centralId = Utils::getCentralIdFromUserName( $user->getName() );
					$db = Utils::getCentralDB( DB_PRIMARY );

					$consumerData = self::DEFAULT_CONSUMER_DATA;
					$consumerData['userId'] = $centralId;
					$consumerData['consumerKey'] = '55555555555555555555555555555555';
					$consumerData['name'] = 'test_name_user_successful';
					$consumerData['oauthVersion'] = '2';
					$consumerData['restrictions'] = MWRestrictions::newFromJson( $consumerData['restrictions'] );
					Consumer::newFromArray(
						array_merge( $consumerData, self::OWNER_ONLY_CONSUMER_DATA )
					)->save( $db );

					return $user;
				},
				static function ( MediaWikiIntegrationTestCase $testCase, ResponseInterface $response ) {
					$responseBody = FormatJson::decode(
						$response->getBody()->getContents(),
						true
					);
					$testCase->assertArrayHasKey( 'access_token', $responseBody );
					$testCase->assertMatchesRegularExpression( '/((.*)\.(.*)\.(.*))/', $responseBody['access_token'] );
				}
			],
		];
	}

	protected function newHandler(): Handler {
		return new ResetClientSecret();
	}
}
