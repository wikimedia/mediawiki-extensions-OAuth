<?php

namespace MediaWiki\Extension\OAuth\Repository;

use League\OAuth2\Server\Entities\ClientEntityInterface;
use League\OAuth2\Server\Entities\ScopeEntityInterface;
use League\OAuth2\Server\Repositories\ScopeRepositoryInterface;
use MediaWiki\Extension\OAuth\Backend\MWOAuthException;
use MediaWiki\Extension\OAuth\Backend\Utils;
use MediaWiki\Extension\OAuth\Entity\ClientEntity;
use MediaWiki\Extension\OAuth\Entity\ScopeEntity;
use MediaWiki\Extension\OAuth\Entity\UserEntity;
use MediaWiki\MediaWikiServices;
use MediaWiki\WikiMap\WikiMap;

class ScopeRepository implements ScopeRepositoryInterface {
	/**
	 * @var string[]
	 */
	protected $allowedScopes = [
		'#default',
		'mwoauth-authonly',
		'mwoauth-authonlyprivate'
	];

	public function __construct() {
		$grantsInfo = MediaWikiServices::getInstance()->getGrantsInfo();
		$this->allowedScopes = array_merge( $this->allowedScopes, $grantsInfo->getValidGrants() );
	}

	/**
	 * @return string[]
	 */
	public function getAllowedScopes() {
		return $this->allowedScopes;
	}

	/**
	 * Return information about a scope.
	 *
	 * @param string $identifier The scope identifier
	 *
	 * @return ScopeEntityInterface|null
	 */
	public function getScopeEntityByIdentifier( $identifier ) {
		if ( in_array( $identifier, $this->allowedScopes, true ) ) {
			return new ScopeEntity( $identifier );
		}

		return null;
	}

	/**
	 * Given a client, grant type and optional user identifier
	 * validate the set of scopes requested are valid and optionally
	 * append additional scopes or remove requested scopes.
	 *
	 * @param ScopeEntityInterface[] $scopes
	 * @param string $grantType
	 * @param ClientEntityInterface|ClientEntity $clientEntity
	 * @param null|string $userIdentifier
	 *
	 * @return ScopeEntityInterface[]
	 */
	public function finalizeScopes( array $scopes, $grantType,
		ClientEntityInterface $clientEntity, $userIdentifier = null ) {
		$scopes = $this->replaceDefaultScope( $scopes, $clientEntity );

		if ( $grantType !== 'authorization_code' ) {
			// For grants that do not require approval,
			// just filter out the scopes that are not allowed for the client
			return array_filter(
				$scopes,
				static function ( ScopeEntityInterface $scope ) use ( $clientEntity ) {
					return in_array( $scope->getIdentifier(), $clientEntity->getGrants(), true );
				}
			);
		}
		if ( !is_numeric( $userIdentifier ) ) {
			return [];
		}

		$mwUser = Utils::getLocalUserFromCentralId( (int)$userIdentifier );
		if ( !$mwUser ) {
			return [];
		}
		$userEntity = UserEntity::newFromMWUser( $mwUser );
		if ( $userEntity === null ) {
			return [];
		}

		// Filter out not approved scopes
		try {
			$approval = $clientEntity->getCurrentAuthorization( $mwUser, WikiMap::getCurrentWikiId() );
			$approvedScopeIds = $approval->getGrants();
		} catch ( MWOAuthException $ex ) {
			$approvedScopeIds = [];
		}

		return array_filter(
			$scopes,
			static function ( ScopeEntityInterface $scope ) use ( $approvedScopeIds ) {
				return in_array( $scope->getIdentifier(), $approvedScopeIds, true );
			}
		);
	}

	/**
	 * Detect "#default" scope and replace it with all client's allowed scopes
	 *
	 * @param ScopeEntityInterface[] $scopes
	 * @param ClientEntityInterface|ClientEntity $client
	 * @return ScopeEntityInterface[]
	 */
	private function replaceDefaultScope( array $scopes, ClientEntityInterface $client ) {
		// Normally, #default scope would be an only scope set, but go through whole array in case
		// someone explicitly made a request with that scope set
		$index = array_search( '#default', array_map( static function ( ScopeEntityInterface $scope ) {
			return $scope->getIdentifier();
		}, $scopes ) );

		if ( $index === false ) {
			return $scopes;
		}

		return $client->getScopes();
	}
}
