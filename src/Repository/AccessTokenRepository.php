<?php

namespace MediaWiki\Extensions\OAuth\Repository;

use League\OAuth2\Server\Entities\AccessTokenEntityInterface;
use League\OAuth2\Server\Entities\ClientEntityInterface;
use League\OAuth2\Server\Entities\ScopeEntityInterface;
use League\OAuth2\Server\Exception\UniqueTokenIdentifierConstraintViolationException;
use League\OAuth2\Server\Repositories\AccessTokenRepositoryInterface;
use MediaWiki\Extensions\OAuth\Entity\AccessTokenEntity;
use MediaWiki\Extensions\OAuth\Entity\ClientEntity;
use MediaWiki\Extensions\OAuth\MWOAuthException;

class AccessTokenRepository extends DatabaseRepository implements AccessTokenRepositoryInterface {
	const FIELD_EXPIRES = 'oaat_expires';
	const FIELD_ACCEPTANCE_ID = 'oaat_acceptance_id';
	const FIELD_REVOKED = 'oaat_revoked';

	/**
	 * Create a new access token
	 *
	 * @param ClientEntityInterface|ClientEntity $clientEntity
	 * @param ScopeEntityInterface[] $scopes
	 * @param mixed|null $userIdentifier
	 * @throws MWOAuthException
	 * @return AccessTokenEntityInterface
	 */
	public function getNewToken( ClientEntityInterface $clientEntity,
		array $scopes, $userIdentifier = null ) {
		return new AccessTokenEntity( $clientEntity, $scopes, $userIdentifier );
	}

	/**
	 * Persists a new access token to permanent storage.
	 *
	 * @param AccessTokenEntityInterface|AccessTokenEntity $accessTokenEntity
	 *
	 * @throws UniqueTokenIdentifierConstraintViolationException
	 */
	public function persistNewAccessToken( AccessTokenEntityInterface $accessTokenEntity ) {
		if ( $this->identifierExists( $accessTokenEntity->getIdentifier() ) ) {
			throw UniqueTokenIdentifierConstraintViolationException::create();
		}

		$data = $this->getDbDataFromTokenEntity( $accessTokenEntity );

		$this->getDB( DB_MASTER )->insert(
			$this->getTableName(),
			$data,
			__METHOD__
		);
	}

	/**
	 * Revoke an access token.
	 *
	 * @param string $tokenId
	 */
	public function revokeAccessToken( $tokenId ) {
		if ( $this->identifierExists( $tokenId ) ) {
			$this->getDB( DB_MASTER )->update(
				$this->getTableName(),
				[ static::FIELD_REVOKED => 1 ],
				[ $this->getIdentifierField() => $tokenId ],
				__METHOD__
			);
		}
	}

	/**
	 * Check if the access token has been revoked.
	 *
	 * @param string $tokenId
	 *
	 * @return bool Return true if this token has been revoked
	 */
	public function isAccessTokenRevoked( $tokenId ) {
		$row = $this->getDB()->selectRow(
			$this->getTableName(),
			[ static::FIELD_REVOKED ],
			[ $this->getIdentifierField() => $tokenId ],
			__METHOD__
		);
		if ( !$row ) {
			return true;
		}
		return (bool)$row->{static::FIELD_REVOKED};
	}

	/**
	 * Delete all access tokens issued with provided approval
	 *
	 * @param int $approvalId
	 */
	public function deleteForApprovalId( $approvalId ) {
		$this->getDB( DB_MASTER )->delete(
			$this->getTableName(),
			[
				static::FIELD_ACCEPTANCE_ID => $approvalId
			],
			__METHOD__
		);
	}

	private function getDbDataFromTokenEntity( AccessTokenEntity $accessTokenEntity ) {
		$expiry = $accessTokenEntity->getExpiryDateTime()->getTimestamp();
		if ( $expiry > 9223371197536780800 ) {
			$expiry = 'infinity';
		}
		return [
			$this->getIdentifierField() => $accessTokenEntity->getIdentifier(),
			static::FIELD_EXPIRES => $this->getDB()->encodeExpiry( $expiry ),
			static::FIELD_ACCEPTANCE_ID => $accessTokenEntity->getApproval() ?
				$accessTokenEntity->getApproval()->getId() :
				0
		];
	}

	protected function getTableName(): string {
		return 'oauth2_access_tokens';
	}

	protected function getIdentifierField(): string {
		return 'oaat_identifier';
	}
}
