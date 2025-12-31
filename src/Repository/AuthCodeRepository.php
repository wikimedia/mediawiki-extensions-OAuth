<?php

namespace MediaWiki\Extension\OAuth\Repository;

use InvalidArgumentException;
use League\OAuth2\Server\Entities\AuthCodeEntityInterface;
use League\OAuth2\Server\Exception\UniqueTokenIdentifierConstraintViolationException;
use League\OAuth2\Server\Repositories\AuthCodeRepositoryInterface;
use MediaWiki\Extension\OAuth\Entity\AuthCodeEntity;

class AuthCodeRepository extends CacheRepository implements AuthCodeRepositoryInterface {

	/**
	 * Creates a new AuthCode
	 */
	public function getNewAuthCode(): AuthCodeEntityInterface {
		return new AuthCodeEntity();
	}

	/**
	 * Persists a new auth code to permanent storage.
	 *
	 * @param AuthCodeEntityInterface $authCodeEntity
	 *
	 * @throws UniqueTokenIdentifierConstraintViolationException
	 */
	public function persistNewAuthCode( AuthCodeEntityInterface $authCodeEntity ): void {
		if ( !$authCodeEntity instanceof AuthCodeEntity ) {
			throw new InvalidArgumentException(
				'$authCodeEntity must be instance of ' .
				AuthCodeEntity::class . ', got ' . get_class( $authCodeEntity ) . ' instead'
			);
		}
		if ( $this->has( $authCodeEntity->getIdentifier() ) ) {
			throw UniqueTokenIdentifierConstraintViolationException::create();
		}

		$this->set(
			$authCodeEntity->getIdentifier(),
			$authCodeEntity->jsonSerialize(),
			$authCodeEntity->getExpiryDateTime()->getTimestamp()
		);
	}

	/**
	 * Revoke an auth code.
	 */
	public function revokeAuthCode( string $codeId ): void {
		$this->delete( $codeId );
	}

	/**
	 * Check if the auth code has been revoked.
	 *
	 * @param string $codeId
	 *
	 * @return bool Return true if this code has been revoked
	 */
	public function isAuthCodeRevoked( string $codeId ): bool {
		return $this->has( $codeId ) === false;
	}

	/**
	 * Get object type for session key
	 *
	 * @return string
	 */
	protected function getCacheKeyType(): string {
		return 'AuthCode';
	}
}
