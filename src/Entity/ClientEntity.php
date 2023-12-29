<?php

namespace MediaWiki\Extension\OAuth\Entity;

use DateInterval;
use DateTimeImmutable;
use Exception;
use League\OAuth2\Server\Entities\AccessTokenEntityInterface;
use League\OAuth2\Server\Entities\ScopeEntityInterface;
use League\OAuth2\Server\Exception\OAuthServerException;
use MediaWiki\Extension\OAuth\Backend\Consumer;
use MediaWiki\Extension\OAuth\Backend\ConsumerAcceptance;
use MediaWiki\Extension\OAuth\Backend\MWOAuthException;
use MediaWiki\Extension\OAuth\Backend\Utils;
use MediaWiki\Extension\OAuth\Repository\AccessTokenRepository;
use MediaWiki\Extension\OAuth\Repository\ClaimStore;
use MediaWiki\User\User;

class ClientEntity extends Consumer implements MWClientEntityInterface {

	/**
	 * Returns the registered redirect URI (as a string).
	 *
	 * Alternatively return an indexed array of redirect URIs.
	 *
	 * @return string|string[]
	 */
	public function getRedirectUri() {
		return $this->getCallbackUrl();
	}

	/**
	 * Returns true if the client is confidential.
	 *
	 * @return bool
	 */
	public function isConfidential() {
		return $this->oauth2IsConfidential;
	}

	/**
	 * @return mixed
	 */
	public function getIdentifier() {
		return $this->getConsumerKey();
	}

	/**
	 * @param mixed $identifier
	 */
	public function setIdentifier( $identifier ) {
		$this->consumerKey = $identifier;
	}

	/**
	 * Get the grant types this client is allowed to use
	 *
	 * @return string[]
	 */
	public function getAllowedGrants() {
		return $this->oauth2GrantTypes;
	}

	/**
	 * Convenience function, same as getGrants()
	 * it just returns array of ScopeEntity-es instead of strings
	 *
	 * @return ScopeEntityInterface[]
	 */
	public function getScopes() {
		$scopeEntities = [];
		foreach ( $this->getGrants() as $grant ) {
			$scopeEntities[] = new ScopeEntity( $grant );
		}

		return $scopeEntities;
	}

	/**
	 * @return bool|User
	 */
	public function getUser() {
		return Utils::getLocalUserFromCentralId( $this->getUserId() );
	}

	/**
	 * @param null|string $secret
	 * @param null|string $grantType
	 * @return bool
	 */
	public function validate( $secret, $grantType ) {
		if ( !$this->isSecretValid( $secret ) ) {
			return false;
		}

		if ( !$this->isGrantAllowed( $grantType ) ) {
			return false;
		}

		return true;
	}

	/**
	 * @return int
	 */
	public function getOAuthVersion() {
		return static::OAUTH_VERSION_2;
	}

	/**
	 * @param null|string $secret
	 * @return bool
	 */
	private function isSecretValid( $secret ) {
		return is_string( $secret )
			&& hash_equals( $secret, Utils::hmacDBSecret( $this->secretKey ) );
	}

	/**
	 * @param string $grantType
	 * @return bool
	 */
	public function isGrantAllowed( $grantType ) {
		return in_array( $grantType, $this->getAllowedGrants() );
	}

	/**
	 * @param User $mwUser
	 * @param bool $update
	 * @param string[] $grants
	 * @param null $requestTokenKey
	 * @return bool
	 * @throws MWOAuthException
	 */
	public function authorize( User $mwUser, $update, $grants, $requestTokenKey = null ) {
		$this->conductAuthorizationChecks( $mwUser );

		$grants = $this->getVerifiedScopes( $grants );
		$this->saveAuthorization( $mwUser, $update, $grants );

		return true;
	}

	/**
	 * Get the access token to be used with a single user
	 * Should never be called outside of client registration/manage code
	 *
	 * @param ConsumerAcceptance $approval
	 * @param bool $revokeExisting Delete all existing tokens
	 *
	 * @return AccessTokenEntityInterface
	 * @throws MWOAuthException
	 * @throws OAuthServerException
	 * @throws Exception
	 */
	public function getOwnerOnlyAccessToken(
		ConsumerAcceptance $approval, $revokeExisting = false
	) {
		$grantType = 'client_credentials';
		if (
			count( $this->getAllowedGrants() ) !== 1 ||
			$this->getAllowedGrants()[0] !== $grantType
		) {
			// make sure client is allowed *only* client_credentials grant,
			// so that this AT cannot be used in other grant type requests
			throw new MWOAuthException( 'mwoauth-oauth2-error-owner-only-invalid-grant', [
				'consumer' => $this->getConsumerKey(),
				'consumer_name' => $this->getName(),
			] );
		}
		$accessToken = null;
		$accessTokenRepo = new AccessTokenRepository();
		if ( $revokeExisting ) {
			$accessTokenRepo->deleteForApprovalId( $approval->getId() );
		}
		/** @var AccessTokenEntity $accessToken */
		$accessToken = $accessTokenRepo->getNewToken( $this, $this->getScopes(), $approval->getUserId() );
		'@phan-var AccessTokenEntity $accessToken';
		$claimStore = new ClaimStore();
		$claims = $claimStore->getClaims( $grantType, $this );
		foreach ( $claims as $claim ) {
			$accessToken->addClaim( $claim );
		}
		$accessToken->setExpiryDateTime( ( new DateTimeImmutable() )->add(
			new DateInterval( 'P1000Y' )
		) );
		$accessToken->setPrivateKeyFromConfig();
		$accessToken->setIdentifier( bin2hex( random_bytes( 40 ) ) );

		$accessTokenRepo->persistNewAccessToken( $accessToken );

		return $accessToken;
	}

	/**
	 * Filter out scopes that application cannot use
	 *
	 * @param string[] $requested
	 * @return string[]
	 */
	private function getVerifiedScopes( $requested ) {
		return array_intersect( $requested, $this->getGrants() );
	}
}
