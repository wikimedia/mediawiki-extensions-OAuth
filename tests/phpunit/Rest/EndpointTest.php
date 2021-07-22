<?php

namespace MediaWiki\Extensions\OAuth\Tests\Rest;

use FormatJson;
use GuzzleHttp\Psr7\Uri;
use MediaWiki\Rest\Handler;
use MediaWiki\Rest\HttpException;
use MediaWiki\Rest\RequestData;
use MediaWiki\Rest\RequestInterface;
use MediaWiki\Tests\Rest\Handler\HandlerTestTrait;
use RequestContext;
use Title;
use User;

/**
 * Class EndpointTest
 * @package MediaWiki\Extensions\OAuth\Tests\Rest
 */
abstract class EndpointTest extends \MediaWikiTestCase {

	use HandlerTestTrait;

	/**
	 * @throws \Exception
	 */
	protected function setUp(): void {
		parent::setUp();

		$this->setMwGlobals( [
			'wgOAuthSecretKey' => base64_encode( random_bytes( 32 ) )
		] );

		RequestContext::getMain()->setTitle( Title::newMainPage() );
	}

	/**
	 * @return mixed
	 */
	abstract public function provideTestHandlerExecute();

	/**
	 * @param string $path
	 * @return Uri
	 */
	protected static function makeUri( $path ) {
		return new Uri( "http://www.example.com/rest$path" );
	}

	abstract protected function newHandler(): Handler;

	/**
	 * @param array $requestInfo
	 * @param array $responseInfo
	 * @param callable|null $userCreateCallback
	 * @param callable|null $extraValidationCallback
	 * @dataProvider provideTestHandlerExecute
	 */
	public function testHandlerExecute(
		array $requestInfo = [],
		array $responseInfo = [],
		callable $userCreateCallback = null,
		callable $extraValidationCallback = null
	) {
		$request = new RequestData( $requestInfo );

		if ( $userCreateCallback ) {
			$user = $userCreateCallback();
		} else {
			$user = new User();
		}

		// TODO: to remove this once REST is switched to Authority
		RequestContext::getMain()->setUser( $user );

		$response = $this->executeHandlerAndGetReponse( $this->newHandler(), $request, [], [], [], [], $user );

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
			$expectedBody = is_array( $responseInfo['body'] ) ?
				$responseInfo['body'] :
				FormatJson::decode( $responseInfo['body'], true );

			$responseBody = FormatJson::decode( $response->getBody()->getContents(), true );

			unset( $expectedBody['messageTranslations'] );
			unset( $responseBody['messageTranslations'] );
			$this->assertArrayEquals( $expectedBody, $responseBody );
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

		if ( $extraValidationCallback ) {
			$extraValidationCallback( $response );
		}
	}

	/**
	 * Executes the given Handler on the given request.
	 *
	 * @param Handler $handler
	 * @param RequestInterface $request
	 * @param array $config
	 * @param array $hooks Hook overrides
	 * @param array $validatedParams Path/query params to return as already valid
	 * @param array $validatedBody Body params to return as already valid
	 * @param User|null $user User provided by request
	 * @return ResponseInterface
	 */
	private function executeHandlerAndGetReponse(
		Handler $handler,
		RequestInterface $request,
		$config = [],
		$hooks = [],
		$validatedParams = [],
		$validatedBody = [],
		?User $user = null
	) {
		try {
			return $this->executeHandler(
				$handler,
				$request,
				$config,
				$hooks,
				$validatedParams,
				$validatedBody,
				$user
			);
		} catch ( HttpException $e ) {
			return $handler->getResponseFactory()->createFromException( $e );
		}
	}
}
