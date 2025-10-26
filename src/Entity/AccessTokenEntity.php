<?php

namespace MediaWiki\Extension\OAuth\Entity;

use DateTimeImmutable;
use InvalidArgumentException;
use Lcobucci\JWT\Configuration;
use Lcobucci\JWT\Signer\Key\InMemory;
use Lcobucci\JWT\Signer\Key\LocalFileReference;
use Lcobucci\JWT\Signer\Rsa\Sha256;
use Lcobucci\JWT\Token;
use League\OAuth2\Server\CryptKey;
use League\OAuth2\Server\Entities\AccessTokenEntityInterface;
use League\OAuth2\Server\Entities\ScopeEntityInterface;
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

	private Configuration $jwtConfiguration;

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

	public function __toString(): string {
		return $this->convertToJWT()->toString();
	}

	/** @inheritDoc */
	public function setPrivateKey( CryptKey $privateKey ) {
		$this->setJwtConfiguration( Configuration::forAsymmetricSigner(
			new Sha256(),
			LocalFileReference::file( $privateKey->getKeyPath(), $privateKey->getPassPhrase() ?? '' ),
			InMemory::plainText( '' )
		) );
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
	 * Get the approval that allows this AT to be created
	 *
	 * @return ConsumerAcceptance|false
	 */
	public function getApproval() {
		return $this->approval;
	}

	/**
	 * Get the client that the token was issued to.
	 *
	 * @return ClientEntity
	 */
	public function getClient() {
		return $this->client;
	}

	public function isOwnerOnly(): bool {
		return $this->getClient()->getOwnerOnly();
	}

	/** @internal Exposed for tests only. */
	public function setJwtConfiguration( Configuration $configuration ): void {
		$this->jwtConfiguration = $configuration;
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
		} catch ( Throwable ) {
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

	private function convertToJWT(): Token {
		// This is a copy of league/oauth-server's AccessTokenTrait, except we use a different
		// 'sub' claim (relatedTo()).

		$builder = $this->jwtConfiguration->builder()
			->permittedFor( $this->getClient()->getIdentifier() )
			->identifiedBy( $this->getIdentifier() )
			->issuedAt( new DateTimeImmutable() )
			->canOnlyBeUsedAfter( new DateTimeImmutable() )
			->expiresAt( $this->getExpiryDateTime() )
			->relatedTo( $this->getUserIdentifierForJwt() );

		if ( $this->getIssuer() ) {
			$builder->issuedBy( $this->getIssuer() );
		}

		foreach ( $this->getClaims() as $claim ) {
			$builder->withClaim( $claim->getName(), $claim->getValue() );
		}

		return $builder
			// Set scope claim late to prevent it from being overridden.
			->withClaim( 'scopes', $this->getScopes() )
			->getToken( $this->jwtConfiguration->signer(), $this->jwtConfiguration->signingKey() );
	}

	private function getUserIdentifierForJwt(): string {
		global $wgOAuth2UsePrefixedSub;

		$centralId = $this->getUserIdentifier();
		if (
			// FIXME is this possible? is it the same as T407655?
			!$centralId
			// Owner-only access tokens are valid forever, which makes them impractical for some of the
			// things other JWTs are used for, and having a different structure helps to differentiate them.
			// Also, since they are valid forever, and the JWT format might change in the future,
			// we want to minimize the number of legacy formats we'll have to support forever, so we
			// go with the already-established legacy format where 'sub' is just a user ID.
			|| $this->isOwnerOnly()
			// Temporary feature flag in case something needs to be adapted to the change in the JWT.
			|| !$wgOAuth2UsePrefixedSub
		) {
			return (string)$centralId;
		} else {
			// Short-lived non-owner-only access token. Use the new 'sub' format to match
			// SessionManager::getJwtData().
			$lookupScope = Utils::getCentralIdLookup()->getScope();
			return "mw:$lookupScope:$centralId";
		}
	}

}
