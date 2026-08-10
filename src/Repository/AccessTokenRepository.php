<?php

namespace MediaWiki\Extension\OAuth\Repository;

use League\OAuth2\Server\Entities\AccessTokenEntityInterface;
use League\OAuth2\Server\Entities\ClientEntityInterface;
use League\OAuth2\Server\Entities\ScopeEntityInterface;
use League\OAuth2\Server\Exception\OAuthServerException;
use League\OAuth2\Server\Exception\UniqueTokenIdentifierConstraintViolationException;
use League\OAuth2\Server\Repositories\AccessTokenRepositoryInterface;
use MediaWiki\Extension\OAuth\Backend\Utils;
use MediaWiki\Extension\OAuth\Entity\AccessTokenEntity;
use MediaWiki\Extension\OAuth\Entity\ClientEntity;
use stdClass;
use Wikimedia\Rdbms\IDatabase;
use Wikimedia\Rdbms\IReadableDatabase;

class AccessTokenRepository implements AccessTokenRepositoryInterface {
	private const TABLE = 'oauth2_access_tokens';
	private const FIELD_ID = 'oaat_identifier';
	private const FIELD_ACCEPTANCE_ID = 'oaat_acceptance_id';
	private const FIELD_EXPIRES = 'oaat_expires';
	private const FIELD_REVOKED = 'oaat_revoked';
	private const FIELDS = [
		self::FIELD_ID,
		self::FIELD_ACCEPTANCE_ID,
		self::FIELD_EXPIRES,
		self::FIELD_REVOKED,
	];

	public function __construct(
		private string $issuer
	) {
	}

	/**
	 * Create a new access token
	 *
	 * @param ClientEntityInterface|ClientEntity $clientEntity
	 * @param ScopeEntityInterface[] $scopes
	 * @param ?string $userIdentifier
	 * @return AccessTokenEntityInterface
	 * @throws OAuthServerException
	 */
	public function getNewToken(
		ClientEntityInterface $clientEntity,
		array $scopes,
		?string $userIdentifier = null
	): AccessTokenEntityInterface {
		return new AccessTokenEntity(
			$clientEntity,
			$scopes,
			$this->issuer,
			$userIdentifier
		);
	}

	/**
	 * Persists a new access token to permanent storage.
	 *
	 * @throws UniqueTokenIdentifierConstraintViolationException
	 */
	public function persistNewAccessToken( AccessTokenEntityInterface $accessTokenEntity ): void {
		if ( $this->getRowById( $accessTokenEntity->getIdentifier() ) ) {
			throw UniqueTokenIdentifierConstraintViolationException::create();
		}

		/** @var AccessTokenEntity $accessTokenEntity */
		'@phan-var AccessTokenEntity $accessTokenEntity';
		$data = $this->getDbDataFromTokenEntity( $accessTokenEntity );

		$this->getDB( DB_PRIMARY )->newInsertQueryBuilder()
			->insertInto( self::TABLE )
			->row( $data )
			->caller( __METHOD__ )
			->execute();
	}

	/**
	 * Revoke an access token.
	 */
	public function revokeAccessToken( string $tokenId ): void {
		$this->getDB( DB_PRIMARY )->newUpdateQueryBuilder()
			->update( self::TABLE )
			->set( [ self::FIELD_REVOKED => 1 ] )
			->where( [ self::FIELD_ID => $tokenId ] )
			->caller( __METHOD__ )
			->execute();
	}

	/**
	 * Check if the access token has been revoked.
	 *
	 * @param string $tokenId
	 *
	 * @return bool Return true if this token has been revoked
	 */
	public function isAccessTokenRevoked( string $tokenId ): bool {
		$row = $this->getRowById( $tokenId );
		if ( !$row ) {
			return true;
		}
		return (bool)$row->{self::FIELD_REVOKED};
	}

	/**
	 * Delete all access tokens issued with provided approval
	 *
	 * @param int $approvalId
	 */
	public function deleteForApprovalId( $approvalId ): void {
		$this->getDB( DB_PRIMARY )->newDeleteQueryBuilder()
			->deleteFrom( self::TABLE )
			->where( [ self::FIELD_ACCEPTANCE_ID => $approvalId ] )
			->caller( __METHOD__ )
			->execute();
	}

	/**
	 * Get ID of the approval bound to this AT
	 *
	 * @param string $tokenId
	 * @return bool|int
	 */
	public function getApprovalId( string $tokenId ) {
		$row = $this->getRowById( $tokenId );

		if ( $row ) {
			return (int)$row->{self::FIELD_ACCEPTANCE_ID};
		}

		return false;
	}

	/**
	 * @param int $index
	 * @return IDatabase|IReadableDatabase
	 */
	public function getDB( $index = DB_REPLICA ) {
		return Utils::getOAuthDB( $index );
	}

	private function getRowById( string $tokenId ): false|stdClass {
		return $this->getDB()->newSelectQueryBuilder()
		   ->select( self::FIELDS )
		   ->from( self::TABLE )
		   ->where( [ self::FIELD_ID => $tokenId ] )
		   ->caller( __METHOD__ )
		   ->fetchRow();
	}

	private function getDbDataFromTokenEntity( AccessTokenEntity $accessTokenEntity ): array {
		$expiry = $accessTokenEntity->getExpiryDateTime()->getTimestamp();
		if ( $expiry > 9223371197536780800 ) {
			$expiry = 'infinity';
		}
		return [
			self::FIELD_ID => $accessTokenEntity->getIdentifier(),
			self::FIELD_EXPIRES => $this->getDB()->encodeExpiry( $expiry ),
			self::FIELD_ACCEPTANCE_ID => $accessTokenEntity->getApproval() ?
				$accessTokenEntity->getApproval()->getId() :
				0
		];
	}

}
