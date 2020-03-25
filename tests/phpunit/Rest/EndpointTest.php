<?php

namespace MediaWiki\Extensions\OAuth\Tests\Rest;

use EmptyBagOStuff;
use GuzzleHttp\Psr7\Uri;
use MediaWiki\Permissions\PermissionManager;
use MediaWiki\Rest\BasicAccess\StaticBasicAuthorizer;
use MediaWiki\Rest\RequestData;
use MediaWiki\Rest\ResponseFactory;
use MediaWiki\Rest\Router;
use MediaWiki\Rest\Validator\Validator;
use Psr\Container\ContainerInterface;
use RequestContext;
use Title;
use User;
use Wikimedia\ObjectFactory;

abstract class EndpointTest extends \MediaWikiTestCase {

	protected function setUp() : void {
		parent::setUp();

		$this->setMwGlobals( [
			'wgOAuthSecretKey' => base64_encode( random_bytes( 32 ) )
		] );

		RequestContext::getMain()->setTitle( Title::newMainPage() );
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
			'http://wiki.example.com',
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
