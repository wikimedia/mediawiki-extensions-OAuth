<?php

namespace MediaWiki\Extension\OAuth\Repository\Hook;

use MediaWiki\Extension\OAuth\Entity\MWClientEntityInterface;

interface OAuthClaimStoreGetClaimsHook {

	/**
	 * Use this hook to add a list of private claims to a client's JWT
	 *
	 * @param string $grantType Type of OAuth grant
	 * @param MWClientEntityInterface $clientEntity Client that is making the request
	 * @param array &$privateClaims List of private claims to be added to the JWT
	 * @param string|null $userIdentifier Identifier for the user that is making the request, default is null
	 * @return void
	 */
	public function onOAuthClaimStoreGetClaims(
		string $grantType, MWClientEntityInterface $clientEntity, array &$privateClaims, $userIdentifier = null
	);
}
