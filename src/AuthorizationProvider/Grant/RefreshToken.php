<?php

namespace MediaWiki\Extension\OAuth\AuthorizationProvider\Grant;

use Exception;
use League\OAuth2\Server\Grant\GrantTypeInterface;
use League\OAuth2\Server\Grant\RefreshTokenGrant;
use MediaWiki\Extension\OAuth\AuthorizationProvider\AccessToken;

class RefreshToken extends AccessToken {

	/**
	 * @return GrantTypeInterface
	 * @throws Exception
	 */
	protected function getGrant(): GrantTypeInterface {
		return new RefreshTokenGrant(
			$this->getRefreshTokenRepo()
		);
	}
}
