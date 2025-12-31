<?php

namespace MediaWiki\Extension\OAuth\AuthorizationProvider;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

interface IAccessTokenProvider extends IAuthorizationProvider {
	/**
	 * Retrieve access tokens
	 */
	public function getAccessTokens(
		ServerRequestInterface $request,
		ResponseInterface $response
	): ResponseInterface;
}
