<?php

namespace MediaWiki\Extension\OAuth\AuthorizationProvider\Grant;

use League\OAuth2\Server\Grant\ClientCredentialsGrant;
use League\OAuth2\Server\Grant\GrantTypeInterface;
use MediaWiki\Extension\OAuth\AuthorizationProvider\AccessTokenProvider;

/**
 * Provides access tokens for a client credentials grant.
 */
class ClientCredentialsAccessTokenProvider extends AccessTokenProvider {

	/**
	 * @return GrantTypeInterface
	 */
	protected function getGrant(): GrantTypeInterface {
		return new ClientCredentialsGrant();
	}
}
