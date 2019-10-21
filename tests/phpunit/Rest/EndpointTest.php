<?php

namespace MediaWiki\Extensions\OAuth\Tests\Rest;

use EmptyBagOStuff;
use GuzzleHttp\Psr7\Uri;
use MediaWiki\Extensions\OAuth\Tests\Lib\Mock_OAuthSignatureMethod_RSA_SHA1;
use MediaWiki\Permissions\PermissionManager;
use MediaWiki\Rest\BasicAccess\StaticBasicAuthorizer;
use MediaWiki\Rest\RequestData;
use MediaWiki\Rest\ResponseFactory;
use MediaWiki\Rest\Router;
use MediaWiki\Rest\Validator\Validator;
use Psr\Container\ContainerInterface;
use Wikimedia\ObjectFactory;
use User;

abstract class EndpointTest extends \MediaWikiTestCase {

	protected function setUp() : void {
		parent::setUp();

		$signatureMethod = new Mock_OAuthSignatureMethod_RSA_SHA1();
		$request = null;
		$this->setMwGlobals( [
			'wgOAuth2PublicKey' => $signatureMethod->fetch_public_cert( $request ),
			'wgOAuth2PrivateKey' => $signatureMethod->fetch_private_cert( $request ),
			'wgOAuthSecretKey' => base64_encode( random_bytes( 32 ) )
		] );
	}

	abstract public static function provideTestViaRouter();

	protected static function makeUri( $path ) {
		return new Uri( "http://www.example.com/rest$path" );
	}

	/** @dataProvider provideTestViaRouter */
	public function testViaRouter( $requestInfo, $responseInfo ) {
		$objectFactory = new ObjectFactory(
			$this->getMockForAbstractClass( ContainerInterface::class )
		);
		$permissionManager = $this->createMock( PermissionManager::class );
		$request = new RequestData( $requestInfo );
		$router = new Router(
			[ __DIR__ . '/testRoutes.json' ],
			[],
			'/rest',
			new EmptyBagOStuff(),
			new ResponseFactory( [] ),
			new StaticBasicAuthorizer(),
			$objectFactory,
			new Validator( $objectFactory, $permissionManager, $request, new User )
		);
		$response = $router->execute( $request );

		if ( isset( $responseInfo['statusCode'] ) ) {
			$this->assertSame( $responseInfo['statusCode'], $response->getStatusCode() );
		}
		if ( isset( $responseInfo['reasonPhrase'] ) ) {
			$this->assertSame( $responseInfo['reasonPhrase'], $response->getReasonPhrase() );
		}
		if ( isset( $responseInfo['protocolVersion'] ) ) {
			$this->assertSame( $responseInfo['protocolVersion'], $response->getProtocolVersion() );
		}
		if ( isset( $responseInfo['body'] ) ) {
			$this->assertSame( $responseInfo['body'], $response->getBody()->getContents() );
		}
		$this->assertSame(
			[],
			array_diff( array_keys( $responseInfo ), [
				'statusCode',
				'reasonPhrase',
				'protocolVersion',
				'body'
			] ),
			'$responseInfo may not contain unknown keys' );
	}
}
