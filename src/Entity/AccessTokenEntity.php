<?php

namespace MediaWiki\Extension\OAuth\Entity;

use InvalidArgumentException;
use League\OAuth2\Server\CryptKey;
use League\OAuth2\Server\Entities\AccessTokenEntityInterface;
use League\OAuth2\Server\Entities\ScopeEntityInterface;
use League\OAuth2\Server\Entities\Traits\AccessTokenTrait;
use League\OAuth2\Server\Entities\Traits\EntityTrait;
use League\OAuth2\Server\Entities\Traits\TokenEntityTrait;
use League\OAuth2\Server\Exception\OAuthServerException;
use MediaWiki\Extension\OAuth\Backend\ConsumerAcceptance;
use MediaWiki\Extension\OAuth\Backend\Utils;
use MediaWiki\MediaWikiServices;
use MediaWiki\User\User;
use MediaWiki\WikiMap\WikiMap;
use Throwable;

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
	 * @var ConsumerAcceptance|false
	 */
	private $approval;

	/**
	 * @param ClientEntity $clientEntity
	 * @param ScopeEntityInterface[] $scopes
	 * @param string $issuer
	 * @param string|int|null $userIdentifier
	 * @throws OAuthServerException
	 */
	public function __construct(
		ClientEntity $clientEntity,
		array $scopes,
		string $issuer,
		$userIdentifier = null
	) {
		$this->approval = $this->setApprovalFromClientScopesUser(
			$clientEntity, $scopes, $userIdentifier
		);

		$this->setClient( $clientEntity );
		$this->setIssuer( $issuer );
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
	 * @return ConsumerAcceptance|false
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
	 * @param string|int|null $userIdentifier
	 * @return ConsumerAcceptance|false
	 */
	private function setApprovalFromClientScopesUser(
		ClientEntity $clientEntity, array $scopes, $userIdentifier = null
	) {
		if ( $clientEntity->getOwnerOnly() && $userIdentifier === null ) {
			$userIdentifier = $clientEntity->getUserId();
			$scopes = $clientEntity->getScopes();
		}
		if ( !$userIdentifier ) {
			return false;
		}
		try {
			$user = Utils::getLocalUserFromCentralId( $userIdentifier );
			$approval = $clientEntity->getCurrentAuthorization( $user, WikiMap::getCurrentWikiId() );
		} catch ( Throwable $ex ) {
			return false;
		}
		if ( !$approval ) {
			return $approval;
		}

		$approvedScopes = $approval->getGrants();
		$notApproved = array_filter(
			$scopes,
			static function ( ScopeEntityInterface $scope ) use ( $approvedScopes ) {
				return !in_array( $scope->getIdentifier(), $approvedScopes, true );
			}
		);

		return !$notApproved ? $approval : false;
	}

	private function confirmClientUsable() {
		$userId = $this->getUserIdentifier() ?? 0;
		$user = Utils::getLocalUserFromCentralId( $userId );
		if ( !$user ) {
			$user = User::newFromId( 0 );
		}

		if ( !$this->getClient()->isUsableBy( $user ) ) {
			throw OAuthServerException::accessDenied(
				'Client ' . $this->getClient()->getIdentifier() .
				' is not usable by user with ID ' . $user->getId()
			);
		}
	}

}
