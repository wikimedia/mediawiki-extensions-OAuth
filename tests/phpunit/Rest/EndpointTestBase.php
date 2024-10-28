<?php

namespace MediaWiki\Extension\OAuth\Tests\Rest;

use Exception;
use GuzzleHttp\Psr7\Uri;
use MediaWiki\Context\RequestContext;
use MediaWiki\Json\FormatJson;
use MediaWiki\Rest\Handler;
use MediaWiki\Rest\HttpException;
use MediaWiki\Rest\RequestData;
use MediaWiki\Rest\RequestInterface;
use MediaWiki\Rest\ResponseInterface;
use MediaWiki\Tests\Rest\Handler\HandlerTestTrait;
use MediaWiki\Title\Title;
use MediaWiki\User\User;
use MediaWikiIntegrationTestCase;

/**
 * Class EndpointTest
 * @package MediaWiki\Extension\OAuth\Tests\Rest
 */
abstract class EndpointTestBase extends MediaWikiIntegrationTestCase {

	use HandlerTestTrait;

	/**
	 * @throws Exception
	 */
	protected function setUp(): void {
		parent::setUp();

		$this->overrideConfigValues( [
			'OAuthSecretKey' => base64_encode( random_bytes( 32 ) )
		] );

		RequestContext::getMain()->setTitle( Title::newMainPage() );
	}

	/**
	 * @return mixed
	 */
	abstract public static function provideTestHandlerExecute();

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
		?callable $userCreateCallback = null,
		?callable $extraValidationCallback = null
	) {
		if ( isset( $requestInfo['postParams'] ) ) {
			$requestInfo['method'] = 'POST';
			$requestInfo['headers']['content-type'] = 'application/x-www-form-urlencoded';
		}

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
		if ( isset( $responseInfo['bodyPattern'] ) ) {
			$expectedPattern = $responseInfo['bodyPattern'];
			$responseBody = (string)$response->getBody();

			$this->assertMatchesRegularExpression( $expectedPattern, $responseBody );
		}
		if ( isset( $responseInfo['body'] ) ) {
			$expectedBody = is_array( $responseInfo['body'] ) ?
				$responseInfo['body'] :
				FormatJson::decode( $responseInfo['body'], true );

			$responseBody = FormatJson::decode( (string)$response->getBody(), true );

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
				'bodyPattern',
				'body',
			] ),
			'$responseInfo may not contain unknown keys' );

		if ( $extraValidationCallback ) {
			$extraValidationCallback( $this, $response );
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
