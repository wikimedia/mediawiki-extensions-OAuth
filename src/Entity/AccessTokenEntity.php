<?php

namespace MediaWiki\Extensions\OAuth\Entity;

use League\OAuth2\Server\CryptKey;
use League\OAuth2\Server\Entities\AccessTokenEntityInterface;
use League\OAuth2\Server\Entities\ScopeEntityInterface;
use League\OAuth2\Server\Entities\Traits\AccessTokenTrait;
use League\OAuth2\Server\Entities\Traits\EntityTrait;
use League\OAuth2\Server\Entities\Traits\TokenEntityTrait;
use League\OAuth2\Server\Exception\OAuthServerException;
use MediaWiki\Extensions\OAuth\MWOAuthConsumerAcceptance;
use MediaWiki\Extensions\OAuth\MWOAuthUtils;
use MediaWiki\MediaWikiServices;
use Throwable;
use InvalidArgumentException;
use User;

class AccessTokenEntity implements AccessTokenEntityInterface {
	use AccessTokenTrait;
	use EntityTrait;
	use TokenEntityTrait;

	/**
	 * @var ClientEntity
	 */
	protected $client;

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
		if ( $clientEntity->getOwnerOnly() ) {
			if ( $userIdentifier !== null && $userIdentifier !== $clientEntity->getUserId() ) {
				throw new InvalidArgumentException(
					'$userIdentifier must be null, or match the client owner user id,' .
					' for owner-only clients, ' . $userIdentifier . ' given'
				);
			}
			foreach ( $clientEntity->getScopes() as $scope ) {
				$this->addScope( $scope );
			}
			$this->setUserIdentifier( $clientEntity->getUserId() );
		} else {
			foreach ( $scopes as $scope ) {
				if ( !in_array( $scope->getIdentifier(), $clientEntity->getGrants() ) ) {
					continue;
				}
				$this->addScope( $scope );
			}
			$this->setUserIdentifier( $userIdentifier );
		}
		$this->confirmClientUsable();
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
	 * Set configured private key
	 */
	public function setPrivateKeyFromConfig() {
		$oauthConfig = MediaWikiServices::getInstance()->getConfigFactory()->makeConfig( 'mwoauth' );
		// Private key to sign the token
		$privateKey = new CryptKey( $oauthConfig->get( 'OAuth2PrivateKey' ) );
		$this->setPrivateKey( $privateKey );
	}

	/**
	 * Get the client that the token was issued to.
	 *
	 * @return ClientEntity
	 */
	public function getClient() {
		return $this->client;
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
		if ( $clientEntity->getOwnerOnly() && $userIdentifier === null ) {
			$userIdentifier = $clientEntity->getUserId();
			$scopes = $clientEntity->getScopes();
		}
		try {
			$user = MWOAuthUtils::getLocalUserFromCentralId( $userIdentifier );
			$approval = $clientEntity->getCurrentAuthorization( $user, wfWikiID() );
		} catch ( Throwable $ex ) {
			return false;
		}
		if ( !$approval ) {
			return $approval;
		}

		$approvedScopes = $approval->getGrants();
		$notApproved = array_filter(
			$scopes,
			function ( ScopeEntityInterface $scope ) use ( $approvedScopes ) {
				return !in_array( $scope->getIdentifier(), $approvedScopes, true );
			}
		);

		return empty( $notApproved ) ? $approval : false;
	}

	private function confirmClientUsable() {
		$userId = $this->getUserIdentifier() ?? 0;
		$user = MWOAuthUtils::getLocalUserFromCentralId( $userId );
		if ( !$user ) {
			$user = User::newFromId( 0 );
		}

		if ( !$this->getClient()->isUsableBy( $user ) ) {
			throw OAuthServerException::accessDenied(
				'Client ' . $this->getClient()->getIdentifier() .
				'is not usable by user with ID ' . $user->getId()
			);
		}
	}

}
