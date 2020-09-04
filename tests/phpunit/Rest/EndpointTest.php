<?php

namespace MediaWiki\Extensions\OAuth\Tests\Rest;

use EmptyBagOStuff;
use FormatJson;
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

/**
 * Class EndpointTest
 * @package MediaWiki\Extensions\OAuth\Tests\Rest
 */
abstract class EndpointTest extends \MediaWikiTestCase {

	/**
	 * @throws \Exception
	 */
	protected function setUp() : void {
		parent::setUp();

		$this->setMwGlobals( [
			'wgOAuthSecretKey' => base64_encode( random_bytes( 32 ) )
		] );

		RequestContext::getMain()->setTitle( Title::newMainPage() );
	}

	/**
	 * @return mixed
	 */
	abstract public function provideTestViaRouter();

	/**
	 * @param $path
	 * @return Uri
	 */
	protected static function makeUri( $path ) {
		return new Uri( "http://www.example.com/rest$path" );
	}

	/**
	 * @param array $requestInfo
	 * @param array $responseInfo
	 * @param callable|null $call
	 * @dataProvider provideTestViaRouter
	 */
	public function testViaRouter( array $requestInfo = [], array $responseInfo = [], callable $call = null ) {
		$objectFactory = new ObjectFactory(
			$this->getMockForAbstractClass( ContainerInterface::class )
		);
		$permissionManager = $this->createMock( PermissionManager::class );
		$request = new RequestData( $requestInfo );

		$user = new User;
		if ( null !== $call ) {
			$user = $call();

			RequestContext::getMain()->setUser( $user );
		}

		$router = new Router(
			[ __DIR__ . '/testRoutes.json' ],
			[],
			'http://wiki.example.com',
			'/rest',
			new EmptyBagOStuff(),
			new ResponseFactory( [] ),
			new StaticBasicAuthorizer(),
			$objectFactory,
			new Validator( $objectFactory, $permissionManager, $request, $user ),
			$this->createHookContainer()
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
			$body = is_array( $responseInfo['body'] ) ?
				$responseInfo['body'] :
				FormatJson::decode( $responseInfo['body'], true );

			$this->assertArrayEquals( $body, FormatJson::decode( $response->getBody()->getContents(), true ) );
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
