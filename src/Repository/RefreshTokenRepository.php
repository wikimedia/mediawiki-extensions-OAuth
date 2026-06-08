<?php

namespace MediaWiki\Extension\OAuth\Repository;

use InvalidArgumentException;
use League\OAuth2\Server\Entities\RefreshTokenEntityInterface;
use League\OAuth2\Server\Exception\UniqueTokenIdentifierConstraintViolationException;
use League\OAuth2\Server\Repositories\RefreshTokenRepositoryInterface;
use MediaWiki\Extension\OAuth\Backend\Utils;
use MediaWiki\Extension\OAuth\Entity\RefreshTokenEntity;
use MediaWiki\MediaWikiServices;
use Wikimedia\ObjectCache\BagOStuff;
use Wikimedia\Timestamp\ConvertibleTimestamp;

class RefreshTokenRepository extends CacheRepository implements RefreshTokenRepositoryInterface {
	public static function factory(): static {
		$cache = Utils::getSessionCache();
		$gracePeriod = MediaWikiServices::getInstance()->getMainConfig()->get( 'OAuth2RefreshTokenGracePeriod' );
		return new static( $cache, $gracePeriod );
	}

	protected function __construct(
		BagOStuff $cache,
		protected readonly int $gracePeriod
	) {
		parent::__construct( $cache );
	}

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
		$mergeFunction = function ( BagOStuff $cache, $cacheKey, $value ) {
			if ( $value !== false ) {
				/** @var array{identifier:string,accessToken:string,expires:int,graceExpires?: ?int} $value */
				'@phan-var array{identifier:string,accessToken:string,expires:int,graceExpires?:?int} $value';
				$graceExpiry = $value['graceExpires'] ?? null;
				if ( $graceExpiry !== null ) {
					// Already in grace period, nothing to do
					return false;
				} else {
					$value['graceExpires'] = ConvertibleTimestamp::time() + $this->gracePeriod;
				}
			}
			return $value;
		};
		$this->cache->merge( $this->getCacheKey( $tokenId ), $mergeFunction, $this->gracePeriod );
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
		/** @var array{identifier:string,accessToken:string,expires:int,graceExpires?: ?int} $refreshTokenData */
		'@phan-var array{identifier:string,accessToken:string,expires:int,graceExpires?:?int} $refreshTokenData';
		$expiry = $refreshTokenData['graceExpires'] ?? $refreshTokenData['expires'];
		if ( $expiry < ConvertibleTimestamp::time() ) {
			// In theory this check is not needed because the cache item would have expired
			// already, but no harm in checking just in case.
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
