<?php

namespace MediaWiki\Extensions\OAuth\AuthorizationProvider\Grant;

use League\OAuth2\Server\Grant\ClientCredentialsGrant;
use League\OAuth2\Server\Grant\GrantTypeInterface;
use MediaWiki\Extensions\OAuth\AuthorizationProvider\AccessToken;

class ClientCredentials extends AccessToken {

	/**
	 * @return GrantTypeInterface
	 */
	protected function getGrant(): GrantTypeInterface {
		return new ClientCredentialsGrant();
	}
}
