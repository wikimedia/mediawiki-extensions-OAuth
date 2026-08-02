<?php

namespace MediaWiki\Extension\OAuth\AuthorizationProvider;

use League\OAuth2\Server\Exception\OAuthServerException;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

abstract class AccessTokenProvider extends AuthorizationProvider implements IAccessTokenProvider {

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
		return $this->server->respondToAccessTokenRequest( $request, $response );
	}

}
