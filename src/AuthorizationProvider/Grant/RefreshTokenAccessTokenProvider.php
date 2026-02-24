<?php

namespace MediaWiki\Extension\OAuth\AuthorizationProvider\Grant;

use Exception;
use League\OAuth2\Server\Grant\GrantTypeInterface;
use League\OAuth2\Server\Grant\RefreshTokenGrant;
use MediaWiki\Extension\OAuth\AuthorizationProvider\AccessTokenProvider;

/**
 * Provides access tokens for a refresh token grant.
 */
class RefreshTokenAccessTokenProvider extends AccessTokenProvider {

	/**
	 * @throws Exception
	 */
	protected function getGrant(): GrantTypeInterface {
		return new RefreshTokenGrant(
			$this->getRefreshTokenRepo()
		);
	}
}
