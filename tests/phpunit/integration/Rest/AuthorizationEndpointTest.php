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
					return self::createOAuth2AuthorizationCodeConsumer(
						$testCase,
						'33333333333333333333333333333333',
						'test_name_user_successful',
						true
					);
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
					return self::createOAuth2AuthorizationCodeConsumer(
						$testCase,
						'33333333333333333333333333333333',
						'test_name_user_successful',
						true
					);
				},
			],

			'public client without code challenge' => [
				[
					'method' => 'GET',
					'uri' => self::makeUri( '/oauth2/authorize' ),
					'queryParams' => [
						'client_id' => '44444444444444444444444444444444',
						'response_type' => 'code',
					],
				],
				[
					'statusCode' => 400,
					'reasonPhrase' => 'Bad Request',
					'protocolVersion' => '1.1',
					'bodyPattern' => '/Code challenge must be provided for public clients/',
				],
				static function ( MediaWikiIntegrationTestCase $testCase ) {
					return self::createOAuth2AuthorizationCodeConsumer(
						$testCase,
						'44444444444444444444444444444444',
						'test_name_public_without_pkce',
						false
					);
				},
			],

			'public client with code challenge' => [
				[
					'method' => 'GET',
					'uri' => self::makeUri( '/oauth2/authorize' ),
					'queryParams' => [
						'client_id' => '55555555555555555555555555555555',
						'response_type' => 'code',
						'code_challenge' => 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789',
						'code_challenge_method' => 'plain',
					],
				],
				[
					'statusCode' => 307,
					'reasonPhrase' => 'Temporary Redirect',
					'protocolVersion' => '1.1',
					'bodyPattern' => '/title=Special:OAuth/',
				],
				static function ( MediaWikiIntegrationTestCase $testCase ) {
					return self::createOAuth2AuthorizationCodeConsumer(
						$testCase,
						'55555555555555555555555555555555',
						'test_name_public_with_pkce',
						false
					);
				},
			],

			'approve authorization' => [
				[
					'method' => 'GET',
					'uri' => self::makeUri( '/oauth2/authorize' ),
					'queryParams' => [
						'client_id' => '66666666666666666666666666666666',
						'response_type' => 'code',
						'approval_pass' => '1',
						'state' => 'test-state',
					],
				],
				[
					'statusCode' => 302,
					'protocolVersion' => '1.1',
				],
				static function ( MediaWikiIntegrationTestCase $testCase ) {
					return self::createOAuth2AuthorizationCodeConsumer(
						$testCase,
						'66666666666666666666666666666666',
						'test_name_approved_authorization',
						true,
						true
					);
				},
				static function ( self $testCase, $response ) {
					$location = $response->getHeaderLine( 'Location' );
					$testCase->assertStringStartsWith( 'https://test.com?', $location );

					parse_str( parse_url( $location, PHP_URL_QUERY ), $query );
					$testCase->assertArrayHasKey( 'code', $query );
					$testCase->assertNotSame( '', $query['code'] );
					$testCase->assertSame( 'test-state', $query['state'] );
				},
			],
		];
	}

	/**
	 * @param MediaWikiIntegrationTestCase $testCase
	 * @param string $consumerKey
	 * @param string $name
	 * @param bool $isConfidential
	 * @param bool $authorize
	 * @return User
	 */
	private static function createOAuth2AuthorizationCodeConsumer(
		MediaWikiIntegrationTestCase $testCase,
		string $consumerKey,
		string $name,
		bool $isConfidential,
		bool $authorize = false
	): User {
		$consumerRepository = OAuthServices::wrap( $testCase->getServiceContainer() )
			->getConsumerRepository();
		$user = User::createNew( 'AuthorizationEndpointTestUser' . $consumerKey[0] );

		$consumerData = self::DEFAULT_CONSUMER_DATA;
		$consumerData['userId'] = Utils::getCentralIdFromUserName( $user->getName() );
		$consumerData['consumerKey'] = $consumerKey;
		$consumerData['oauthVersion'] = '2';
		$consumerData['name'] = $name;
		$consumerData['oauth2IsConfidential'] = $isConfidential;
		$consumerData['oauth2GrantTypes'] = [ 'authorization_code', 'refresh_token' ];
		$consumerData['restrictions'] = MWRestrictions::newFromJson( $consumerData['restrictions'] );
		$consumer = Consumer::newFromArray( $consumerData );
		$consumerRepository->save( $consumer );

		if ( $authorize ) {
			$consumer->authorize( $user, false, $consumer->getGrants() );
		}

		return $user;
	}

	protected function newHandler(): Handler {
		return Authorize::factory();
	}
}
