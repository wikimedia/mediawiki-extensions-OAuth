<?php

namespace MediaWiki\Extension\OAuth\Tests\Rest;

use MediaWiki\Extension\OAuth\Backend\Consumer;
use MediaWiki\Extension\OAuth\Backend\Utils;
use MediaWiki\Extension\OAuth\Rest\Handler\Authorize;
use MediaWiki\Rest\Handler;
use MediaWiki\User\User;
use MWRestrictions;

/**
 * @covers \MediaWiki\Extension\OAuth\Rest\Handler\Authorize
 * @group Database
 * @group OAuth
 */
class AuthorizationEndpointTest extends EndpointTestBase {

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

	public static function provideTestHandlerExecute() {
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
					'protocolVersion' => '1.1',
					'bodyPattern' => '/title=Special:UserLogin/'
				]
			],

			'unknown consumer' => [
				[
					'method' => 'GET',
					'uri' => self::makeUri( '/oauth2/authorize' ),
					'queryParams' => [
						'client_id' => '1234567',
						'response_type' => 'code'
					]
				],
				[
					'statusCode' => 401,
					'reasonPhrase' => 'Unauthorized',
					'protocolVersion' => '1.1',
				],
				static function () {
					$user = User::createNew( 'ResetClientSecretTestUser1' );
					return $user;
				}

			],

			'success' => [
				[
					'method' => 'GET',
					'uri' => self::makeUri( '/oauth2/authorize' ),
					'queryParams' => [
						'client_id' => '33333333333333333333333333333333',
						'response_type' => 'code'
					]
				],
				[
					'statusCode' => 307,
					'reasonPhrase' => 'Temporary Redirect',
					'protocolVersion' => '1.1',
					'bodyPattern' => '/title=Special:OAuth/'
				],
				static function () {
					$user = User::createNew( 'ResetClientSecretTestUser2' );

					$centralId = Utils::getCentralIdFromUserName( $user->getName() );
					$db = Utils::getCentralDB( DB_PRIMARY );

					$consumerData = self::DEFAULT_CONSUMER_DATA;
					$consumerData['userId'] = $centralId;
					$consumerData['consumerKey'] = '33333333333333333333333333333333';
					$consumerData['oauthVersion'] = '2';
					$consumerData['name'] = 'test_name_user_successful';
					$consumerData['restrictions'] = MWRestrictions::newFromJson( $consumerData['restrictions'] );
					Consumer::newFromArray( $consumerData )->save( $db );

					return $user;
				}

			]
		];
	}

	protected function newHandler(): Handler {
		return Authorize::factory();
	}
}
