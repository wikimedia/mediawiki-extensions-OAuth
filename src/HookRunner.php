<?php

namespace MediaWiki\Extension\OAuth;

use MediaWiki\Extension\OAuth\Entity\MWClientEntityInterface;
use MediaWiki\Extension\OAuth\Repository\Hook\OAuthClaimStoreGetClaimsHook;
use MediaWiki\HookContainer\HookContainer;

class HookRunner implements OAuthClaimStoreGetClaimsHook {

	public function __construct( private readonly HookContainer $hookContainer ) {
	}

	/**
	 * @param string $grantType
	 * @param MWClientEntityInterface $clientEntity
	 * @param array &$privateClaims
	 * @param string|null $userIdentifier
	 */
	public function onOAuthClaimStoreGetClaims(
		string $grantType, MWClientEntityInterface $clientEntity, array &$privateClaims, $userIdentifier = null
	) {
		$this->hookContainer->run(
			'OAuthClaimStoreGetClaims',
			[ $grantType, $clientEntity, &$privateClaims, $userIdentifier ]
		);
	}
}
