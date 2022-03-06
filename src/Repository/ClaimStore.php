<?php

namespace MediaWiki\Extension\OAuth\Repository;

use League\OAuth2\Server\Entities\ClientEntityInterface;
use League\OAuth2\Server\Repositories\ClaimRepositoryInterface;
use LogicException;
use MediaWiki\Extension\OAuth\Entity\MWClientEntityInterface;
use MediaWiki\Extension\OAuth\HookRunner;
use MediaWiki\MediaWikiServices;

class ClaimStore implements ClaimRepositoryInterface {

	/**
	 * @var HookRunner
	 */
	private $hookRunner;

	public function __construct() {
		$hookContainer = MediaWikiServices::getInstance()->getHookContainer();
		$this->hookRunner = new HookRunner( $hookContainer );
	}

	/**
	 * @inheritDoc
	 */
	public function getClaims(
		string $grantType, ClientEntityInterface $clientEntity, $userIdentifier = null
	) {
		if ( !( $clientEntity instanceof MWClientEntityInterface ) ) {
			throw new LogicException( '$clientEntity must be instance of ' .
				MWClientEntityInterface::class . ', got ' . get_class( $clientEntity ) . ' instead' );
		}

		$privateClaims = [];
		$this->hookRunner->onOAuthClaimStoreGetClaims( $grantType, $clientEntity, $privateClaims );

		return $privateClaims;
	}
}
