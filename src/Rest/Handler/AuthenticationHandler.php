<?php

namespace MediaWiki\Extension\OAuth\Rest\Handler;

use Config;
use League\OAuth2\Server\Exception\OAuthServerException;
use MediaWiki\Extension\OAuth\AuthorizationProvider\AccessToken as AccessTokenProvider;
use MediaWiki\Extension\OAuth\AuthorizationProvider\Grant\AuthorizationCodeAuthorization;
use MediaWiki\Extension\OAuth\Backend\Utils;
use MediaWiki\Extension\OAuth\Response;
use MediaWiki\MediaWikiServices;
use MediaWiki\Rest\Handler;
use MediaWiki\Rest\HttpException;
use MediaWiki\Rest\Response as RestResponse;
use MediaWiki\Rest\StringStream;
use MediaWiki\Rest\Validator\Validator;
use MediaWiki\Title\Title;
use Psr\Http\Message\ResponseInterface;
use RequestContext;
use User;

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
		$grantKey = $this->getGrantKey();
		$validated = $this->getValidatedParams();
		$grantKeyValue = $validated[$grantKey];

		$class = $this->getGrantClass( $grantKeyValue );
		if ( !$class || !is_callable( [ $class, 'factory' ] ) ) {
			throw new HttpException( 'invalid_request', 400 );
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
			// Catch and store any validation errors, so they can be thrown
			// during the execution, and get caught by appropriate error handling code
			$type = $exception->getErrorData()['error'] ?? 'parameter-validation-failed';
			if ( $type === 'parameter-validation-failed' ) {
				$missingParam = $exception->getErrorData()['name'] ?? '';
				return $this->queueError( OAuthServerException::invalidRequest( $missingParam ) );
			}
			$this->queueError( OAuthServerException::serverError( $exception->getMessage() ) );
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
		$response = $response ?? new Response();
		$response = $exception->generateHttpResponse( $response );
		if ( $exception->hasRedirect() || $this->getRequest()->getMethod() === 'POST' ) {
			return $response;
		}

		$context = RequestContext::getMain();
		// T379504: Set dummy context title
		$context->setTitle( Title::makeTitle( NS_SPECIAL, 'Api/Rest' ) );
		$out = $context->getOutput();

		// TODO: Should we include message/hint eventhough they are not localized?
		$out->showErrorPage(
			'mwoauth-error',
			$this->getLocalizedErrorMessage( $exception->getErrorType() )
		);

		ob_start();
		$out->output();
		$html = ob_get_clean();

		$response = $this->getResponseFactory()->create();
		$stream = new StringStream( $html );
		$response->setHeader( 'Content-Type', 'text/html' );
		$response->setBody( $stream );

		return $response;
	}

	/**
	 * @param string $type
	 * @return string
	 */
	private function getLocalizedErrorMessage( $type ) {
		$map = [
			'invalid_client' => 'mwoauth-oauth2-error-invalid-client',
			'server_error' => 'mwoauth-oauth2-error-server-error',
			'invalid_request' => 'mwoauth-oauth2-error-invalid-request',
			'unauthorized_client' => 'mwoauth-oauth2-error-unauthorized-client',
			'access_denied' => 'mwoauth-oauth2-error-access-denied',
			'unsupported_response_type' => 'mwoauth-oauth2-error-unsupported-response-type',
			'invalid_scope' => 'mwoauth-oauth2-error-invalid-scope',
			'temporarily_unavailable' => 'mwoauth-oauth2-error-temporarily-unavailable'
		];
		if ( isset( $map[$type] ) ) {
			return $map[$type];
		}

		return 'mwoauth-oauth2-error-server-error';
	}

	/**
	 * @return string
	 */
	abstract protected function getGrantKey();

	/**
	 * @param string $grantKey
	 * @return string|false
	 */
	abstract protected function getGrantClass( $grantKey );
}
