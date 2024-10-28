<?php

namespace MediaWiki\Extension\OAuth\Repository;

use League\OAuth2\Server\Entities\AccessTokenEntityInterface;
use League\OAuth2\Server\Entities\ClientEntityInterface;
use League\OAuth2\Server\Entities\ScopeEntityInterface;
use League\OAuth2\Server\Exception\OAuthServerException;
use League\OAuth2\Server\Exception\UniqueTokenIdentifierConstraintViolationException;
use League\OAuth2\Server\Repositories\AccessTokenRepositoryInterface;
use MediaWiki\Extension\OAuth\Entity\AccessTokenEntity;
use MediaWiki\Extension\OAuth\Entity\ClientEntity;
use MediaWiki\MediaWikiServices;

class AccessTokenRepository extends DatabaseRepository implements AccessTokenRepositoryInterface {
	private const FIELD_EXPIRES = 'oaat_expires';
	private const FIELD_ACCEPTANCE_ID = 'oaat_acceptance_id';
	private const FIELD_REVOKED = 'oaat_revoked';

	/** @var string */
	private $issuer;

	/**
	 * @param string|null $issuer
	 */
	public function __construct(
		?string $issuer = null
	) {
		if ( !$issuer ) {
			// TODO: When the extension is converted to proper use of DI,
			// this needs to be always injected.
			$issuer = MediaWikiServices::getInstance()
				->getMainConfig()
				->get( 'CanonicalServer' );
		}
		$this->issuer = $issuer;
	}

	/**
	 * Create a new access token
	 *
	 * @param ClientEntityInterface|ClientEntity $clientEntity
	 * @param ScopeEntityInterface[] $scopes
	 * @param string|int|null $userIdentifier
	 * @return AccessTokenEntityInterface
	 * @throws OAuthServerException
	 */
	public function getNewToken( ClientEntityInterface $clientEntity,
		array $scopes, $userIdentifier = null ) {
		return new AccessTokenEntity( $clientEntity, $scopes,
			$this->issuer, $userIdentifier );
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

		$this->getDB( DB_PRIMARY )->newInsertQueryBuilder()
			->insertInto( $this->getTableName() )
			->row( $data )
			->caller( __METHOD__ )
			->execute();
	}

	/**
	 * Revoke an access token.
	 *
	 * @param string $tokenId
	 */
	public function revokeAccessToken( $tokenId ) {
		if ( $this->identifierExists( $tokenId ) ) {
			$this->getDB( DB_PRIMARY )->newUpdateQueryBuilder()
				->update( $this->getTableName() )
				->set( [ static::FIELD_REVOKED => 1 ] )
				->where( [ $this->getIdentifierField() => $tokenId ] )
				->caller( __METHOD__ )
				->execute();
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
		$row = $this->getDB()->newSelectQueryBuilder()
			->select( static::FIELD_REVOKED )
			->from( $this->getTableName() )
			->where( [ $this->getIdentifierField() => $tokenId ] )
			->caller( __METHOD__ )
			->fetchRow();
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
		$this->getDB( DB_PRIMARY )->newDeleteQueryBuilder()
			->deleteFrom( $this->getTableName() )
			->where( [ static::FIELD_ACCEPTANCE_ID => $approvalId ] )
			->caller( __METHOD__ )
			->execute();
	}

	/**
	 * Get ID of the approval bound to this AT
	 *
	 * @param string $tokenId
	 * @return bool|int
	 */
	public function getApprovalId( $tokenId ) {
		$row = $this->getDB()->newSelectQueryBuilder()
			->select( static::FIELD_ACCEPTANCE_ID )
			->from( $this->getTableName() )
			->where( [ $this->getIdentifierField() => $tokenId ] )
			->caller( __METHOD__ )
			->fetchRow();

		if ( $row ) {
			return (int)$row->{static::FIELD_ACCEPTANCE_ID};
		}

		return false;
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
