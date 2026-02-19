<?php

namespace MediaWiki\Extension\OAuth\AuthorizationProvider;

use League\OAuth2\Server\Grant\AbstractGrant;
use MediaWiki\Extension\OAuth\Entity\AccessTokenEntity;
use MediaWiki\Extension\OAuth\Entity\ClientEntity;
use MediaWiki\Extension\OAuth\Repository\ClaimStore;

/**
 * @extends AbstractGrant
 */
trait GrantWithCustomClaims {

	/**
	 * Add custom claims to the access token.
	 *
	 * See https://github.com/thephpleague/oauth2-server/pull/1122
	 */
	private function addCustomClaims(
		ClientEntity $client,
		?string $userIdentifier,
		AccessTokenEntity $accessToken
	): void {
		// TODO: This class should be injected elsewhere.
		$claimStore = new ClaimStore();
		$claims = $claimStore->getClaims(
			$this->getIdentifier(),
			$client,
			$userIdentifier,
			$client->getOwnerOnly()
		);
		foreach ( $claims as $claim ) {
			$accessToken->addClaim( $claim );
		}
	}

}
