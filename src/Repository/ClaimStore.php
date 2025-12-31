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
	 * @param bool $ownerOnly True for owner-only apps.
	 */
	public function getClaims(
		string $grantType,
		ClientEntityInterface $clientEntity,
		?string $userIdentifier = null,
		bool $ownerOnly = false
	): array {
		if ( !( $clientEntity instanceof MWClientEntityInterface ) ) {
			throw new LogicException( '$clientEntity must be instance of ' .
				MWClientEntityInterface::class . ', got ' . get_class( $clientEntity ) . ' instead' );
		}

		$claims = [];
		if ( $ownerOnly ) {
			// HACK: add a fake 'ownerOnly' claim to allow hooks to differentiate - owner-only tokens
			// never expire so often it makes sense to use different claims
			$claims['ownerOnly'] = true;
		}
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

		// Remove fake 'ownerOnly' claim.
		// This also deduplicates claims; when there are multiple claims with the same name, the last one wins,
		// as that one probably comes from the extension hook, which has a more specific purpose.
		$claims = $this->claimEntityListToMap( $claimEntities );
		unset( $claims['ownerOnly'] );
		return $this->claimMapToEntityList( $claims );
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
