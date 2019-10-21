<?php

namespace MediaWiki\Extensions\OAuth\Entity;

use League\OAuth2\Server\Entities\AccessTokenEntityInterface;
use League\OAuth2\Server\Entities\ScopeEntityInterface;
use League\OAuth2\Server\Entities\Traits\AccessTokenTrait;
use League\OAuth2\Server\Entities\Traits\EntityTrait;
use League\OAuth2\Server\Entities\Traits\TokenEntityTrait;
use MediaWiki\Extensions\OAuth\MWOAuthConsumerAcceptance;

class AccessTokenEntity implements AccessTokenEntityInterface {
	use AccessTokenTrait;
	use EntityTrait;
	use TokenEntityTrait;

	/**
	 * User approval of the client
	 *
	 * @var MWOAuthConsumerAcceptance|bool
	 */
	private $approval = false;

	/**
	 * @param ClientEntity $clientEntity
	 * @param ScopeEntityInterface[] $scopes
	 * @param string|null $userIdentifier
	 */
	public function __construct(
		ClientEntity $clientEntity, array $scopes, $userIdentifier = null
	) {
		$this->approval = $this->setApprovalFromClientScopesUser(
			$clientEntity, $scopes, $userIdentifier
		);

		$this->setClient( $clientEntity );
		foreach ( $scopes as $scope ) {
			if ( !in_array( $scope->getIdentifier(), $clientEntity->getGrants() ) ) {
				continue;
			}
			$this->addScope( $scope );
		}
		$this->setUserIdentifier( $userIdentifier );
	}

	/**
	 * Get the approval that allows this AT to be created
	 *
	 * @return MWOAuthConsumerAcceptance
	 */
	public function getApproval() {
		return $this->approval;
	}

	/**
	 * @param ClientEntity $clientEntity
	 * @param array $scopes
	 * @param null $userIdentifier
	 * @return MWOAuthConsumerAcceptance|bool
	 */
	private function setApprovalFromClientScopesUser(
		ClientEntity $clientEntity, array $scopes, $userIdentifier = null
	) {
		// Not implemented yet
		return false;
	}
}
