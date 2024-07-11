<?php

namespace MediaWiki\Extension\OAuth\Rest\Handler;

use League\OAuth2\Server\Exception\OAuthServerException;
use LogicException;
use MediaWiki\Config\Config;
use MediaWiki\Context\RequestContext;
use MediaWiki\Extension\OAuth\AuthorizationProvider\AccessToken as AccessTokenProvider;
use MediaWiki\Extension\OAuth\AuthorizationProvider\Grant\AuthorizationCodeAuthorization;
use MediaWiki\Extension\OAuth\Backend\Utils;
use MediaWiki\Extension\OAuth\Response;
use MediaWiki\MediaWikiServices;
use MediaWiki\Message\Message;
use MediaWiki\Rest\Handler;
use MediaWiki\Rest\HttpException;
use MediaWiki\Rest\LocalizedHttpException;
use MediaWiki\Rest\Response as RestResponse;
use MediaWiki\Rest\StringStream;
use MediaWiki\Rest\Validator\Validator;
use MediaWiki\User\User;
use Psr\Http\Message\ResponseInterface;

abstract class AuthenticationHandler extends Handler {

	/**
	 * @var User
	 */
	protected $user;

	/**
	 * @var Config
	 */
	protected $config;

	/**
	 * @var OAuthServerException|null
	 */
	protected $queuedError;

	/**
	 * @return AuthenticationHandler
	 */
	public static function factory() {
		$centralId = Utils::getCentralIdFromLocalUser( RequestContext::getMain()->getUser() );
		$user = $centralId ? Utils::getLocalUserFromCentralId( $centralId ) : User::newFromId( 0 );
		$config = MediaWikiServices::getInstance()->getConfigFactory()->makeConfig( 'mwoauth' );
		// @phan-suppress-next-line PhanTypeInstantiateAbstractStatic
		return new static( $user, $config );
	}

	/**
	 * @param User $user
	 * @param Config $config
	 */
	protected function __construct( User $user, Config $config ) {
		$this->user = $user;
		$this->config = $config;
	}

	/**
	 * We do not want any permission checks
	 *
	 * @return bool
	 */
	public function needsReadAccess() {
		return false;
	}

	/**
	 * We do not want any permission checks
	 *
	 * @return bool
	 */
	public function needsWriteAccess() {
		return false;
	}

	/**
	 * @throws HttpException
	 * @return AccessTokenProvider|AuthorizationCodeAuthorization
	 */
	protected function getAuthorizationProvider() {
		$grantType = $this->getGrantType();

		$class = $this->getGrantClass( $grantType );
		if ( !$class || !is_callable( [ $class, 'factory' ] ) ) {
			throw new LogicException( 'Could not find grant class factory' );
		}

		/** @var AccessTokenProvider|AuthorizationCodeAuthorization $authProvider */
		$authProvider = $class::factory();
		'@phan-var AccessTokenProvider|AuthorizationCodeAuthorization $authProvider';
		return $authProvider;
	}

	public function validate( Validator $restValidator ) {
		try {
			parent::validate( $restValidator );
		} catch ( HttpException $exception ) {
			if ( $exception instanceof LocalizedHttpException ) {
				$formatted = $this->getResponseFactory()->formatMessage( $exception->getMessageValue() );
				$message = $formatted['messageTranslations']['en'] ?? reset( $formatted['messageTranslations'] );
			} else {
				$message = $exception->getMessage();
			}
			// Catch and store any validation errors, so they can be thrown
			// during the execution, and get caught by appropriate error handling code
			$type = $exception->getErrorData()['error'] ?? 'parameter-validation-failed';
			if ( $type === 'parameter-validation-failed' ) {
				$missingParam = $exception->getErrorData()['name'] ?? '';
				$this->queueError( new OAuthServerException(
					// OAuthServerException::invalidRequest() but with more useful text
					'Invalid request: ' . $message,
					3,
					'invalid_request',
					400,
					$missingParam ? \sprintf( 'Check the `%s` parameter', $missingParam ) : null
				) );
				return;
			}
			$this->queueError( OAuthServerException::serverError( $message ) );
		}
	}

	/**
	 * @param OAuthServerException $ex
	 */
	protected function queueError( OAuthServerException $ex ) {
		// If already set, do not override, since we cannot throw more than one error,
		// and it will probably be more useful to throw first error that occurred
		if ( !$this->queuedError ) {
			$this->queuedError = $ex;
		}
	}

	/**
	 * @param array $query
	 * @return string
	 */
	protected function getQueryParamsCgi( $query = [] ) {
		$queryParams = $this->getRequest()->getQueryParams();
		unset( $queryParams['title'] );

		$queryParams = array_merge( $queryParams, $query );
		return wfArrayToCgi( $queryParams );
	}

	/**
	 * @param OAuthServerException $exception
	 * @param Response|null $response
	 * @return ResponseInterface|RestResponse
	 */
	protected function errorResponse( $exception, $response = null ) {
		$response ??= new Response();
		$response = $exception->generateHttpResponse( $response );
		if ( $exception->hasRedirect() || $this->getRequest()->getMethod() === 'POST' ) {
			return $response;
		}

		$out = RequestContext::getMain()->getOutput();
		$out->showErrorPage(
			'mwoauth-error',
			$this->getLocalizedErrorMessage( $exception )
		);

		ob_start();
		$out->output();
		$html = ob_get_clean();

		$response = $this->getResponseFactory()->create();
		$stream = new StringStream( $html );
		$response->setStatus( $exception->getHttpStatusCode() );
		$response->setHeader( 'Content-Type', 'text/html' );
		$response->setBody( $stream );

		return $response;
	}

	private function getLocalizedErrorMessage( OAuthServerException $exception ): Message {
		$type = $exception->getErrorType();
		$map = [
			'invalid_client' => 'mwoauth-oauth2-error-invalid-client',
			'invalid_request' => 'mwoauth-oauth2-error-invalid-request',
			'unauthorized_client' => 'mwoauth-oauth2-error-unauthorized-client',
			'access_denied' => 'mwoauth-oauth2-error-access-denied',
			'unsupported_response_type' => 'mwoauth-oauth2-error-unsupported-response-type',
			'invalid_scope' => 'mwoauth-oauth2-error-invalid-scope',
			'temporarily_unavailable' => 'mwoauth-oauth2-error-temporarily-unavailable',
			// 'server_error' is passed through to the catch-all handler below
		];
		$msg = isset( $map[$type] )
			? wfMessage( $map[$type] )
			: wfMessage( 'mwoauth-oauth2-error-server-error', $exception->getMessage() );
		if ( $exception->getHint() ) {
			return wfMessage( 'mwoauth-oauth2-error-serverexception-withhint', $msg, $exception->getHint() );
		} else {
			return $msg;
		}
	}

	/** @inheritDoc */
	protected function detectExtraneousBodyFields( Validator $restValidator ) {
		// Ignore unexpected parameters per https://datatracker.ietf.org/doc/html/rfc6749#section-3.1
		// and https://datatracker.ietf.org/doc/html/rfc6749#section-3.2 :
		// "The authorization server MUST ignore unrecognized request parameters."
	}

	/**
	 * @return string
	 */
	abstract protected function getGrantType();

	/**
	 * @param string $grantType
	 * @return string|false
	 */
	abstract protected function getGrantClass( $grantType );

}
