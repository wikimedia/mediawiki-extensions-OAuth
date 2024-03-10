<?php

namespace MediaWiki\Extension\OAuth\AuthorizationProvider;

use League\OAuth2\Server\Exception\OAuthServerException;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

abstract class AccessToken extends AuthorizationProvider implements IAccessTokenProvider {

	/**
	 * @param ServerRequestInterface $request
	 * @param ResponseInterface $response
	 *
	 * @return ResponseInterface
	 * @throws OAuthServerException
	 */
	public function getAccessTokens(
		ServerRequestInterface $request, ResponseInterface $response
	): ResponseInterface {
		$this->logAccessTokenRequest( $request );
		return $this->server->respondToAccessTokenRequest( $request, $response );
	}

	/**
	 * @param ServerRequestInterface $request
	 */
	protected function logAccessTokenRequest( ServerRequestInterface $request ) {
		$this->logger->info(
			"OAuth2: Access token request - Grant type {grant}, client id: {client}", [
				'grant' => $this->getGrantSingleton()->getIdentifier(),
				'client' => $this->getClientIdFromRequest( $request )
			] );
	}
}
