<?php

namespace MediaWiki\Extensions\OAuth\Rest\Handler;

use GuzzleHttp\Psr7\ServerRequest;
use League\OAuth2\Server\Exception\OAuthServerException;
use League\OAuth2\Server\RequestTypes\AuthorizationRequest;
use MediaWiki\Extensions\OAuth\AuthorizationProvider\Grant\AuthorizationCodeAuthorization;
use MediaWiki\Extensions\OAuth\Response;
use Throwable;
use SpecialPage;
use User;
use Wikimedia\ParamValidator\ParamValidator;

class Authorize extends AuthenticationHandler {
	const RESPONSE_TYPE_CODE = 'code';

	/**
	 * @inheritDoc
	 */
	public function execute() {
		$response = new Response();
		$request = ServerRequest::fromGlobals()->withQueryParams(
			$this->getRequest()->getQueryParams()
		);

		try {
			if ( !$this->user instanceof User || $this->user->isAnon() ) {
				return $this->getLoginRedirectResponse();
			}

			$authProvider = $this->getAuthorizationProvider();
			$authProvider->setUser( $this->user );
			/** @var AuthorizationRequest $authRequest */
			$authRequest = $authProvider->init( $request );
			if ( !$authProvider->needsUserApproval() ) {
				return $authProvider->authorize( $authRequest, $response );
			}
			$authRequest->setAuthorizationApproved( true );
			return $authProvider->authorize( $authRequest, $response );
		} catch ( OAuthServerException $ex ) {
			return $ex->generateHttpResponse( $response );
		} catch ( Throwable $ex ) {
			return OAuthServerException::serverError( $ex->getMessage() )
				->generateHttpResponse( $response );
		}
	}

	/**
	 * @inheritDoc
	 */
	public function getParamSettings() {
		return [
			'response_type' => [
				self::PARAM_SOURCE => 'query',
				ParamValidator::PARAM_TYPE => [
					self::RESPONSE_TYPE_CODE
				],
				ParamValidator::PARAM_REQUIRED => true,
			],
			'client_id' => [
				self::PARAM_SOURCE => 'query',
				ParamValidator::PARAM_TYPE => 'string',
				ParamValidator::PARAM_REQUIRED => true,
			],
			'redirect_uri' => [
				self::PARAM_SOURCE => 'query',
				ParamValidator::PARAM_TYPE => 'string',
				ParamValidator::PARAM_REQUIRED => false,
			],
			'scope' => [
				self::PARAM_SOURCE => 'query',
				ParamValidator::PARAM_TYPE => 'string',
				ParamValidator::PARAM_REQUIRED => false,
			],
			'state' => [
				self::PARAM_SOURCE => 'query',
				ParamValidator::PARAM_TYPE => 'string',
				ParamValidator::PARAM_REQUIRED => false,
			],
			'code_challenge' => [
				self::PARAM_SOURCE => 'query',
				ParamValidator::PARAM_TYPE => 'string',
				ParamValidator::PARAM_REQUIRED => false,
			],
			'code_challenge_method' => [
				self::PARAM_SOURCE => 'query',
				ParamValidator::PARAM_TYPE => [
					'plain',
					'S256'
				],
				ParamValidator::PARAM_REQUIRED => false,
			]
		];
	}

	private function getLoginRedirectResponse() {
		return $this->getResponseFactory()->createTemporaryRedirect(
			SpecialPage::getTitleFor( 'Userlogin' )->getFullURL( [
				'returnto' => SpecialPage::getTitleFor( 'OAuth', 'rest_redirect' ),
				'returntoquery' => $this->getQueryParamsCgi( [
					'rest_url' => $this->getRequest()->getUri()->getPath()
				] ),
			] )
		);
	}

	/**
	 * @return string
	 */
	protected function getGrantKey() {
		return 'response_type';
	}

	/**
	 * @param string $grantKey
	 * @return string|false
	 */
	protected function getGrantClass( $grantKey ) {
		switch ( $grantKey ) {
			case static::RESPONSE_TYPE_CODE:
				return AuthorizationCodeAuthorization::class;
			default:
				return false;
		}
	}
}
