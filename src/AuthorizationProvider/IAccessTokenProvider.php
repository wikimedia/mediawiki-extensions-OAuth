<?php

namespace MediaWiki\Extensions\OAuth\AuthorizationProvider;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

interface IAccessTokenProvider extends IAuthorizationProvider {
	/**
	 * Retrieve access tokens
	 *
	 * @param ServerRequestInterface $request
	 * @param ResponseInterface $response
	 * @return ResponseInterface
	 */
	public function getAccessTokens( ServerRequestInterface $request,
		ResponseInterface $response ) : ResponseInterface;
}
