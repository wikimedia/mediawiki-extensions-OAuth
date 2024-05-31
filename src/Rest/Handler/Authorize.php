<?php

namespace MediaWiki\Extension\OAuth\Rest\Handler;

use Exception;
use GuzzleHttp\Psr7\ServerRequest;
use League\OAuth2\Server\Entities\ScopeEntityInterface;
use League\OAuth2\Server\Exception\OAuthServerException;
use League\OAuth2\Server\RequestTypes\AuthorizationRequest;
use MediaWiki\Extension\OAuth\AuthorizationProvider\Grant\AuthorizationCodeAuthorization;
use MediaWiki\Extension\OAuth\Entity\ClientEntity;
use MediaWiki\Extension\OAuth\Entity\UserEntity;
use MediaWiki\Extension\OAuth\Exception\ClientApprovalDenyException;
use MediaWiki\Extension\OAuth\Response;
use MediaWiki\Rest\Response as RestResponse;
use MediaWiki\SpecialPage\SpecialPage;
use MediaWiki\User\User;
use MediaWiki\WikiMap\WikiMap;
use MWExceptionHandler;
use Throwable;
use Wikimedia\ParamValidator\ParamValidator;

/**
 * Handles the oauth2/authorize endpoint, which displays an authorization dialog to the user if
 * needed (by redirecting to Special:OAuth/approve), and returns an authorization code that can be
 * traded for the access token.
 */
class Authorize extends AuthenticationHandler {
	private const RESPONSE_TYPE_CODE = 'code';

	/**
	 * @inheritDoc
	 */
	public function execute() {
		$response = new Response();

		try {
			if ( $this->queuedError ) {
				throw $this->queuedError;
			}
			$request = ServerRequest::fromGlobals()->withQueryParams(
				$this->getValidatedParams()
			);
			// Note: Owner-only clients can only use client_credentials grant
			// so would be rejected from this endpoint with invalid_client error
			// automatically, no need for additional checks
			if ( !$this->user instanceof User || !$this->user->isNamed() ) {
				return $this->getLoginRedirectResponse();
			}

			$authProvider = $this->getAuthorizationProvider();
			$authProvider->setUser( $this->user );
			/** @var AuthorizationRequest $authRequest */
			$authRequest = $authProvider->init( $request );
			$this->setValidScopes( $authRequest );
			if ( !$authProvider->needsUserApproval() ) {
				return $authProvider->authorize( $authRequest, $response );
			}

			if ( $this->getValidatedParams()['approval_cancel'] ) {
				throw new ClientApprovalDenyException( $authRequest->getRedirectUri() );
			}

			if (
				$this->getValidatedParams()['approval_pass'] &&
				$this->checkApproval( $authRequest )
			) {
				$authRequest->setAuthorizationApproved( true );
				return $authProvider->authorize( $authRequest, $response );
			}

			return $this->getApprovalRedirectResponse( $authRequest );
		} catch ( OAuthServerException $ex ) {
			return $this->errorResponse( $ex, $response );
		} catch ( Throwable $ex ) {
			MWExceptionHandler::logException( $ex );
			return $this->errorResponse(
				OAuthServerException::serverError( $ex->getMessage() ),
				$response
			);
		}
	}

	protected function setValidScopes( AuthorizationRequest &$authRequest ) {
		/** @var ClientEntity $client */
		$client = $authRequest->getClient();
		'@phan-var ClientEntity $client';

		$scopes = $this->getValidatedParams()['scope'];
		if ( !$scopes ) {
			// No scope parameter
			$authRequest->setScopes(
				$client->getScopes()
			);
			return;
		}
		// Trim off any not allowed scopes
		$allowedScopes = $client->getGrants();

		$authRequest->setScopes( array_filter(
			$authRequest->getScopes(),
			static function ( ScopeEntityInterface $scope ) use ( $allowedScopes ) {
				return in_array( $scope->getIdentifier(), $allowedScopes );
			}
		) );
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
			],
			'approval_cancel' => [
				self::PARAM_SOURCE => 'query',
				ParamValidator::PARAM_TYPE => 'string',
				ParamValidator::PARAM_REQUIRED => false,
			],
			'approval_pass' => [
				self::PARAM_SOURCE => 'query',
				ParamValidator::PARAM_TYPE => 'string',
				ParamValidator::PARAM_REQUIRED => false,
			]
		];
	}

	/**
	 * @param AuthorizationRequest $authRequest
	 * @return RestResponse
	 */
	private function getApprovalRedirectResponse( AuthorizationRequest $authRequest ) {
		return $this->getResponseFactory()->createTemporaryRedirect(
			SpecialPage::getTitleFor( 'OAuth', 'approve' )->getFullURL( [
				'returnto' => $this->getRequest()->getUri()->getPath(),
				'returntoquery' => $this->getQueryParamsCgi(),
				'client_id' => $authRequest->getClient()->getIdentifier(),
				'oauth_version' => ClientEntity::OAUTH_VERSION_2,
				'scope' => implode( ' ', array_map( static function ( ScopeEntityInterface $scope ) {
					return $scope->getIdentifier();
				}, $authRequest->getScopes() ) )
			] )
		);
	}

	private function getLoginRedirectResponse() {
		return $this->getResponseFactory()->createTemporaryRedirect(
			SpecialPage::getTitleFor( 'Userlogin' )->getFullURL( [
				'returnto' => SpecialPage::getTitleFor( 'OAuth', 'rest_redirect' ),
				'returntoquery' => wfArrayToCgi( [
					'rest_url' => $this->getRequest()->getUri()->__toString(),
				] ),
			] )
		);
	}

	/**
	 * @return string
	 */
	protected function getGrantType() {
		return $this->getValidatedParams()['response_type'];
	}

	/**
	 * @param string $grantType
	 * @return string|false
	 */
	protected function getGrantClass( $grantType ) {
		switch ( $grantType ) {
			case static::RESPONSE_TYPE_CODE:
				return AuthorizationCodeAuthorization::class;
			default:
				return false;
		}
	}

	/**
	 * Check if user has approved the client, and scopes it requested
	 *
	 * @param AuthorizationRequest $authRequest
	 * @return bool
	 */
	private function checkApproval( AuthorizationRequest $authRequest ) {
		/** @var ClientEntity $client */
		$client = $authRequest->getClient();
		'@phan-var ClientEntity $client';

		/** @var UserEntity $userEntity */
		$userEntity = $authRequest->getUser();
		'@phan-var UserEntity $userEntity';

		try {
			$approval = $client->getCurrentAuthorization(
				$userEntity->getMwUser(),
				WikiMap::getCurrentWikiId()
			);
		} catch ( Exception $ex ) {
			return false;
		}

		if ( !$approval ) {
			return false;
		}

		// Scopes in OAuth 1.0 are called grants
		$scopes = $approval->getGrants();
		$requestedScopes = $this->getFlatScopes( $authRequest->getScopes() );
		$missing = array_diff( $requestedScopes, $scopes );
		if ( $missing ) {
			return false;
		}

		return true;
	}

	/**
	 * @param ScopeEntityInterface[] $scopeEntities
	 * @return string[]
	 */
	private function getFlatScopes( $scopeEntities ) {
		return array_map( static function ( ScopeEntityInterface $scope ) {
			return $scope->getIdentifier();
		}, $scopeEntities );
	}
}
