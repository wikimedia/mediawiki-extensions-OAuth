<?php

namespace MediaWiki\Extension\OAuth\AuthorizationProvider\Grant;

use DateInterval;
use Exception;
use League\OAuth2\Server\Exception\OAuthServerException;
use League\OAuth2\Server\Grant\GrantTypeInterface;
use League\OAuth2\Server\RequestTypes\AuthorizationRequestInterface;
use MediaWiki\Extension\OAuth\AuthorizationProvider\AuthorizationProvider;
use MediaWiki\Extension\OAuth\Entity\ClientEntity;
use MediaWiki\Extension\OAuth\Entity\UserEntity;
use MediaWiki\Extension\OAuth\OAuthServices;
use MediaWiki\MediaWikiServices;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

/**
 * Provides authorization codes.
 */
class AuthorizationCodeProvider extends AuthorizationProvider {

	/** @inheritDoc */
	public function needsUserApproval() {
		return true;
	}

	/**
	 * @throws Exception
	 */
	protected function getGrant(): GrantTypeInterface {
		$authCodeRepo = $this->getAuthCodeRepo();
		$refreshTokenRepo = $this->getRefreshTokenRepo();
		// May not need custom claims here?
		$grant = new AuthCodeGrantWithCustomClaims(
			$authCodeRepo, $refreshTokenRepo, new DateInterval( 'PT10M' ) );
		return $grant;
	}

	/**
	 * @throws OAuthServerException
	 */
	public function init( ServerRequestInterface $request ): AuthorizationRequestInterface {
		$authRequest = $this->server->validateAuthorizationRequest( $request );
		/** @var ClientEntity $client */
		$client = $authRequest->getClient();
		'@phan-var ClientEntity $client';

		if ( !$client->isUsableBy( $this->user ) ) {
			throw OAuthServerException::accessDenied(
				'Client ' . $client->getIdentifier() .
				' is not usable by user with ID ' . $this->user->getId()
			);
		}
		$userEntity = UserEntity::newFromMWUser( $this->user );
		$authRequest->setUser( $userEntity );
		$this->logAuthorizationRequest( __METHOD__, $authRequest );

		$consumerRepository = OAuthServices::wrap( MediaWikiServices::getInstance() )->getConsumerRepository();
		$consumer = $consumerRepository->getByKey( $authRequest->getClient()->getIdentifier() );

		$this->logger->info(
			"OAuth2: Starting authorization request for client {consumer_key} and user (id) {user} ", [
				'user' => $authRequest->getUser()->getIdentifier()
			] + ( $consumer ? $consumer->getLogContext() : [] )
		);

		return $authRequest;
	}

	public function authorize(
		AuthorizationRequestInterface $authRequest,
		ResponseInterface $response
	): ResponseInterface {
		$this->logAuthorizationRequest( __METHOD__, $authRequest );
		return $this->server->completeAuthorizationRequest( $authRequest, $response );
	}

	protected function logAuthorizationRequest(
		string $method,
		AuthorizationRequestInterface $authRequest
	) {
		$consumerRepository = OAuthServices::wrap( MediaWikiServices::getInstance() )->getConsumerRepository();
		$consumer = $consumerRepository->getByKey( $authRequest->getClient()->getIdentifier() );

		$this->logger->info(
			"OAuth2: Authorization request, func {func}, for client {consumer_key} " .
			"and user (id) {user} using grant \"{grant}\"", [
				'func' => $method,
				'user' => $authRequest->getUser()->getIdentifier(),
				'grant' => $authRequest->getGrantTypeId()
			] + ( $consumer ? $consumer->getLogContext() : [] )
		);
	}
}
