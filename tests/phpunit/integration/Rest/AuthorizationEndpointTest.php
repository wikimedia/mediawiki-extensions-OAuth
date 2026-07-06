<?php

namespace MediaWiki\Extension\OAuth\Tests\Integration\Rest;

use MediaWiki\Extension\OAuth\Backend\Consumer;
use MediaWiki\Extension\OAuth\Backend\Utils;
use MediaWiki\Extension\OAuth\OAuthServices;
use MediaWiki\Extension\OAuth\Rest\Handler\Authorize;
use MediaWiki\Rest\Handler;
use MediaWiki\User\User;
use MediaWiki\Utils\MWRestrictions;
use MediaWikiIntegrationTestCase;

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
						'response_type' => 'code',
					],
				],
				[
					'statusCode' => 307,
					'reasonPhrase' => 'Temporary Redirect',
					'protocolVersion' => '1.1',
					'bodyPattern' => '/\btitle=Special:UserLogin\b/',
				],
			],

			'redirect to login and preserve display/lang' => [
				[
					'method' => 'GET',
					'uri' => self::makeUri( '/oauth2/authorize' ),
					'queryParams' => [
						'client_id' => 'dummy',
						'response_type' => 'code',
						'display' => 'popup',
						'ui_locales' => 'fake fr en',
					],
				],
				[
					'statusCode' => 307,
					'bodyPatterns' => [
						'/\btitle=Special:UserLogin\b/',
						'/\bdisplay=popup\b/',
						'/\buselang=fr\b/',
					],
				],
			],

			'unknown consumer' => [
				[
					'method' => 'GET',
					'uri' => self::makeUri( '/oauth2/authorize' ),
					'queryParams' => [
						'client_id' => '1234567',
						'response_type' => 'code',
					],
				],
				[
					'statusCode' => 401,
					'reasonPhrase' => 'Unauthorized',
					'protocolVersion' => '1.1',
				],
				static function () {
					return User::createNew( 'ResetClientSecretTestUser1' );
				}

			],

			'redirect to authorization dialog' => [
				[
					'method' => 'GET',
					'uri' => self::makeUri( '/oauth2/authorize' ),
					'queryParams' => [
						'client_id' => '33333333333333333333333333333333',
						'response_type' => 'code',
					],
				],
				[
					'statusCode' => 307,
					'reasonPhrase' => 'Temporary Redirect',
					'protocolVersion' => '1.1',
					'bodyPattern' => '/title=Special:OAuth/',
				],
				static function ( MediaWikiIntegrationTestCase $testCase ) {
					$consumerRepository = OAuthServices::wrap( $testCase->getServiceContainer() )
						->getConsumerRepository();
					$user = User::createNew( 'ResetClientSecretTestUser2' );

					$centralId = Utils::getCentralIdFromUserName( $user->getName() );

					$consumerData = self::DEFAULT_CONSUMER_DATA;
					$consumerData['userId'] = $centralId;
					$consumerData['consumerKey'] = '33333333333333333333333333333333';
					$consumerData['oauthVersion'] = '2';
					$consumerData['name'] = 'test_name_user_successful';
					$consumerData['restrictions'] = MWRestrictions::newFromJson( $consumerData['restrictions'] );
					$consumerRepository->save( Consumer::newFromArray( $consumerData ) );

					return $user;
				},
			],

			'redirect to authorization dialog and preserve display/lang' => [
				[
					'method' => 'GET',
					'uri' => self::makeUri( '/oauth2/authorize' ),
					'queryParams' => [
						'client_id' => '33333333333333333333333333333333',
						'response_type' => 'code',
						'display' => 'popup',
						'ui_locales' => 'fake fr en',
					],
				],
				[
					'statusCode' => 307,
					'reasonPhrase' => 'Temporary Redirect',
					'protocolVersion' => '1.1',
					'bodyPatterns' => [
						'/title=Special:OAuth/',
						'/\bdisplay=popup\b/',
						'/\buselang=fr\b/',
					],
				],
				static function ( MediaWikiIntegrationTestCase $testCase ) {
					$consumerRepository = OAuthServices::wrap( $testCase->getServiceContainer() )
						->getConsumerRepository();
					$user = User::createNew( 'ResetClientSecretTestUser2' );

					$centralId = Utils::getCentralIdFromUserName( $user->getName() );
					$db = Utils::getOAuthDB( DB_PRIMARY );

					$consumerData = self::DEFAULT_CONSUMER_DATA;
					$consumerData['userId'] = $centralId;
					$consumerData['consumerKey'] = '33333333333333333333333333333333';
					$consumerData['oauthVersion'] = '2';
					$consumerData['name'] = 'test_name_user_successful';
					$consumerData['restrictions'] = MWRestrictions::newFromJson( $consumerData['restrictions'] );
					$consumerRepository->save( Consumer::newFromArray( $consumerData ) );

					return $user;
				},
			],

			// TODO test actual authorization
		];
	}

	protected function newHandler(): Handler {
		return Authorize::factory();
	}
}
