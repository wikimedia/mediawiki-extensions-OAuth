<?php

namespace MediaWiki\Extension\OAuth\AuthorizationProvider\Grant;

use DateInterval;
use Exception;
use League\OAuth2\Server\Grant\GrantTypeInterface;
use MediaWiki\Extension\OAuth\AuthorizationProvider\AccessTokenProvider;

/**
 * Provides access tokens for an authorization code grant.
 */
class AuthorizationCodeAccessTokenProvider extends AccessTokenProvider {

	/**
	 * @return GrantTypeInterface
	 * @throws Exception
	 */
	protected function getGrant(): GrantTypeInterface {
		$authCodeRepo = $this->getAuthCodeRepo();
		$refreshTokenRepo = $this->getRefreshTokenRepo();
		$grant = new AuthCodeGrantWithCustomClaims(
			$authCodeRepo, $refreshTokenRepo, new DateInterval( 'PT10M' ) );
		if ( !$this->config->get( 'OAuth2RequireCodeChallengeForPublicClients' ) ) {
			$grant->disableRequireCodeChallengeForPublicClients();
		}

		return $grant;
	}
}
