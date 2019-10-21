<?php

namespace MediaWiki\Extensions\OAuth\Repository;

use League\OAuth2\Server\Entities\RefreshTokenEntityInterface;
use League\OAuth2\Server\Exception\UniqueTokenIdentifierConstraintViolationException;
use League\OAuth2\Server\Repositories\RefreshTokenRepositoryInterface;
use MediaWiki\Extensions\OAuth\Entity\RefreshTokenEntity;
use InvalidArgumentException;

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
		return $this->has( $tokenId ) === false;
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
