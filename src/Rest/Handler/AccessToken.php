<?php

namespace MediaWiki\Extensions\OAuth\Rest\Handler;

use GuzzleHttp\Psr7\ServerRequest;
use League\OAuth2\Server\Exception\OAuthServerException;
use MediaWiki\Extensions\OAuth\AuthorizationProvider\Grant\AuthorizationCodeAccessTokens;
use MediaWiki\Extensions\OAuth\AuthorizationProvider\Grant\ClientCredentials;
use MediaWiki\Extensions\OAuth\AuthorizationProvider\Grant\RefreshToken;
use MediaWiki\Extensions\OAuth\Response;
use Throwable;
use Wikimedia\ParamValidator\ParamValidator;

/**
 * Handles the oauth2/access_token endpoint, which can be used after the user has returned from
 * the authorization dialog to trade the off the received authorization code for an access token.
 */
class AccessToken extends AuthenticationHandler {

	const GRANT_TYPE_CLIENT_CREDENTIALS = 'client_credentials';
	const GRANT_TYPE_AUTHORIZATION_CODE = 'authorization_code';
	const GRANT_TYPE_REFRESH_TOKEN = 'refresh_token';

	/**
	 * @inheritDoc
	 */
	public function execute() {
		$response = new Response();

		try {
			if ( $this->queuedError ) {
				throw $this->queuedError;
			}
			$request = ServerRequest::fromGlobals()->withParsedBody(
				$this->getValidatedParams()
			);

			$authProvider = $this->getAuthorizationProvider();
			return $authProvider->getAccessTokens( $request, $response );
		} catch ( OAuthServerException $exception ) {
			return $this->errorResponse( $exception, $response );
		} catch ( Throwable $exception ) {
			return $this->errorResponse(
				OAuthServerException::serverError( $exception->getMessage(), $exception ),
				$response
			);
		}
	}

	/**
	 * @inheritDoc
	 */
	public function getParamSettings() {
		return [
			'grant_type' => [
				self::PARAM_SOURCE => 'post',
				ParamValidator::PARAM_TYPE => [
					self::GRANT_TYPE_CLIENT_CREDENTIALS,
					self::GRANT_TYPE_AUTHORIZATION_CODE,
					self::GRANT_TYPE_REFRESH_TOKEN,
				],
				ParamValidator::PARAM_REQUIRED => true,
			],
			'client_id' => [
				self::PARAM_SOURCE => 'post',
				ParamValidator::PARAM_TYPE => 'string',
				ParamValidator::PARAM_REQUIRED => false,
			],
			'client_secret' => [
				self::PARAM_SOURCE => 'post',
				ParamValidator::PARAM_TYPE => 'string',
				ParamValidator::PARAM_REQUIRED => false,
			],
			'redirect_uri' => [
				self::PARAM_SOURCE => 'post',
				ParamValidator::PARAM_TYPE => 'string',
				ParamValidator::PARAM_REQUIRED => false,
			],
			'scope' => [
				self::PARAM_SOURCE => 'post',
				ParamValidator::PARAM_TYPE => 'string',
				ParamValidator::PARAM_REQUIRED => false,
			],
			'code' => [
				self::PARAM_SOURCE => 'post',
				ParamValidator::PARAM_TYPE => 'string',
				ParamValidator::PARAM_REQUIRED => false,
			],
			'refresh_token' => [
				self::PARAM_SOURCE => 'post',
				ParamValidator::PARAM_TYPE => 'string',
				ParamValidator::PARAM_REQUIRED => false,
			],
			'code_verifier' => [
				self::PARAM_SOURCE => 'post',
				ParamValidator::PARAM_TYPE => 'string',
				ParamValidator::PARAM_REQUIRED => false,
			]
		];
	}

	/**
	 * @return string
	 */
	protected function getGrantKey() {
		return 'grant_type';
	}

	/**
	 * @param string $grantKey
	 * @return string|false
	 */
	protected function getGrantClass( $grantKey ) {
		switch ( $grantKey ) {
			case static::GRANT_TYPE_AUTHORIZATION_CODE:
				return AuthorizationCodeAccessTokens::class;
			case static::GRANT_TYPE_CLIENT_CREDENTIALS:
				return ClientCredentials::class;
			case static::GRANT_TYPE_REFRESH_TOKEN:
				return RefreshToken::class;
			default:
				return false;
		}
	}
}
