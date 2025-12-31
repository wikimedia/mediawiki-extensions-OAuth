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
	 */
	public function getNewRefreshToken(): ?RefreshTokenEntityInterface {
		return new RefreshTokenEntity();
	}

	/**
	 * Create a new refresh token_name.
	 *
	 * @throws UniqueTokenIdentifierConstraintViolationException
	 */
	public function persistNewRefreshToken( RefreshTokenEntityInterface $refreshTokenEntity ): void {
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
	 */
	public function revokeRefreshToken( string $tokenId ): void {
		$this->delete( $tokenId );
	}

	/**
	 * Check if the refresh token has been revoked.
	 *
	 * @param string $tokenId
	 *
	 * @return bool Return true if this token has been revoked
	 */
	public function isRefreshTokenRevoked( string $tokenId ): bool {
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
	 */
	protected function getCacheKeyType(): string {
		return "RefreshToken";
	}
}
