<?php

namespace MediaWiki\Extension\OAuth\Repository;

use InvalidArgumentException;
use League\OAuth2\Server\Entities\RefreshTokenEntityInterface;
use League\OAuth2\Server\Exception\UniqueTokenIdentifierConstraintViolationException;
use League\OAuth2\Server\Repositories\RefreshTokenRepositoryInterface;
use MediaWiki\Extension\OAuth\Entity\RefreshTokenEntity;

class RefreshTokenRepository extends CacheRepository implements RefreshTokenRepositoryInterface {

	/**
	 * Creates a new refresh token
	 *
	 * @return RefreshTokenEntityInterface|null
	 */
	public function getNewRefreshToken() {
		return new RefreshTokenEntity();
	}

	/**
	 * Create a new refresh token_name.
	 *
	 * @param RefreshTokenEntityInterface $refreshTokenEntity
	 *
	 * @throws UniqueTokenIdentifierConstraintViolationException
	 */
	public function persistNewRefreshToken( RefreshTokenEntityInterface $refreshTokenEntity ) {
		if ( !$refreshTokenEntity instanceof RefreshTokenEntity ) {
			throw new InvalidArgumentException(
				'$refreshTokenEntity must be instance of ' .
				RefreshTokenEntity::class . ', got ' . get_class( $refreshTokenEntity ) . ' instead'
			);
		}
		if ( $this->has( $refreshTokenEntity->getIdentifier() ) ) {
			throw UniqueTokenIdentifierConstraintViolationException::create();
		}

		$this->set(
			$refreshTokenEntity->getIdentifier(),
			$refreshTokenEntity->jsonSerialize(),
			$refreshTokenEntity->getExpiryDateTime()->getTimestamp()
		);
	}

	/**
	 * Revoke the refresh token.
	 *
	 * @param string $tokenId
	 */
	public function revokeRefreshToken( $tokenId ) {
		$this->delete( $tokenId );
	}

	/**
	 * Check if the refresh token has been revoked.
	 *
	 * @param string $tokenId
	 *
	 * @return bool Return true if this token has been revoked
	 */
	public function isRefreshTokenRevoked( $tokenId ) {
		// Refresh tokens cannot be mass-revoked when the user revokes the approval for a client
		// via SpecialMWOAuthManageMyGrants, because they are stored in a key-value store.
		// instead we rely on the fact that the refresh token contains the access token ID
		// (oaat_identifier), and access tokens are marked invalid but never deleted, except when
		// the user revokes the approval for the client.
		// TODO would be nicer to directly store the approval ID (oaac_id) in the refresh token repo.
		$refreshTokenData = $this->get( $tokenId );
		if ( $refreshTokenData === false ) {
			return true;
		}
		$accessTokenRepository = new AccessTokenRepository();
		return $accessTokenRepository->getApprovalId( $refreshTokenData['accessToken'] ) === false;
	}

	/**
	 * Get object type for session key
	 *
	 * @return string
	 */
	protected function getCacheKeyType(): string {
		return "RefreshToken";
	}
}
