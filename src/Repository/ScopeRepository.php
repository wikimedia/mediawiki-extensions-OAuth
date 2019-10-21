<?php

namespace MediaWiki\Extensions\OAuth\Repository;

use League\OAuth2\Server\Entities\ScopeEntityInterface;
use League\OAuth2\Server\Repositories\ScopeRepositoryInterface;
use League\OAuth2\Server\Entities\ClientEntityInterface;
use MediaWiki\Extensions\OAuth\Entity\ClientEntity;
use MediaWiki\Extensions\OAuth\Entity\ScopeEntity;
use MWGrants;

class ScopeRepository implements ScopeRepositoryInterface {
	/**
	 * @var array
	 */
	protected $allowedScopes = [
		'mwoauth-authonly',
		'mwoauth-authonlyprivate'
	];

	public function __construct() {
		$this->allowedScopes = array_merge( $this->allowedScopes, MWGrants::getValidGrants() );
	}

	/**
	 * Return information about a scope.
	 *
	 * @param string $identifier The scope identifier
	 *
	 * @return ScopeEntityInterface|null
	 */
	public function getScopeEntityByIdentifier( $identifier ) {
		if ( in_array( $identifier,  $this->allowedScopes, true ) ) {
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
		return $scopes;
	}
}
