<?php

namespace MediaWiki\Extension\OAuth\Repository;

use League\OAuth2\Server\Entities\ClientEntityInterface;
use League\OAuth2\Server\Repositories\ClaimRepositoryInterface;
use LogicException;
use MediaWiki\Extension\OAuth\Backend\Utils;
use MediaWiki\Extension\OAuth\Entity\ClaimEntity;
use MediaWiki\Extension\OAuth\Entity\ClientEntity;
use MediaWiki\Extension\OAuth\Entity\MWClientEntityInterface;
use MediaWiki\Extension\OAuth\HookRunner;
use MediaWiki\HookContainer\HookRunner as CoreHookRunner;
use MediaWiki\MediaWikiServices;

class ClaimStore implements ClaimRepositoryInterface {

	private HookRunner $hookRunner;
	private CoreHookRunner $coreHookRunner;

	public function __construct() {
		$hookContainer = MediaWikiServices::getInstance()->getHookContainer();
		$this->hookRunner = new HookRunner( $hookContainer );
		$this->coreHookRunner = new CoreHookRunner( $hookContainer );
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

		$claims = [];
		$user = null;
		if ( $clientEntity instanceof ClientEntity ) {
			$user = $clientEntity->getUser();
		}
		if ( !$user && $userIdentifier ) {
			$user = Utils::getLocalUserFromCentralId( (int)$userIdentifier );
		}
		if ( $user ) {
			$this->coreHookRunner->onGetSessionJwtData( $user, $claims );
		}

		$claimEntities = $this->claimMapToEntityList( $claims );
		$this->hookRunner->onOAuthClaimStoreGetClaims( $grantType, $clientEntity, $claimEntities );

		// Deduplicate claims; when there are multiple claims with the same name, let the last one win,
		// as that one probably comes from the extension hook, which has a more specific purpose.
		return $this->claimMapToEntityList( $this->claimEntityListToMap( $claimEntities ) );
	}

	/**
	 * @param ClaimEntity[] $claimEntityList
	 * @return array<string,array|string|int|float|bool|null>
	 */
	private function claimEntityListToMap( array $claimEntityList ): array {
		$claimMap = [];
		foreach ( $claimEntityList as $claimEntity ) {
			$claimMap[ $claimEntity->getName() ] = $claimEntity->getValue();
		}
		return $claimMap;
	}

	/**
	 * @param array<string,array|string|int|float|bool|null> $claimMap
	 * @return ClaimEntity[]
	 */
	private function claimMapToEntityList( array $claimMap ): array {
		$claimEntityList = [];
		foreach ( $claimMap as $name => $value ) {
			$claimEntityList[] = new ClaimEntity( $name, $value );
		}
		return $claimEntityList;
	}
}
