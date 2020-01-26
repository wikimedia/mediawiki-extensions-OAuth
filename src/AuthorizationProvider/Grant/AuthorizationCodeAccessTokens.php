<?php

namespace MediaWiki\Extensions\OAuth\AuthorizationProvider\Grant;

use DateInterval;
use Exception;
use League\OAuth2\Server\Grant\AuthCodeGrant;
use League\OAuth2\Server\Grant\GrantTypeInterface;
use MediaWiki\Extensions\OAuth\AuthorizationProvider\AccessToken;

class AuthorizationCodeAccessTokens extends AccessToken {

	/**
	 * @return GrantTypeInterface
	 * @throws Exception
	 */
	protected function getGrant(): GrantTypeInterface {
		$authCodeRepo = $this->getAuthCodeRepo();
		$refreshTokenRepo = $this->getRefreshTokenRepo();
		$grant = new AuthCodeGrant( $authCodeRepo, $refreshTokenRepo, new DateInterval( 'PT10M' ) );
		if ( !$this->config->get( 'OAuth2RequireCodeChallengeForPublicClients' ) ) {
			$grant->disableRequireCodeChallengeForPublicClients();
		}

		return $grant;
	}
}
