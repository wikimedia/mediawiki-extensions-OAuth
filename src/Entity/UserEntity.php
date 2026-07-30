<?php

namespace MediaWiki\Extension\OAuth\Entity;

use League\OAuth2\Server\Entities\UserEntityInterface;
use MediaWiki\Extension\OAuth\Backend\Utils;
use MediaWiki\User\User;
use MediaWiki\User\UserIdentity;

/**
 * Represents a MediaWiki user when interfacing with OAuth libraries.
 *
 * Only use this when the local user is owned by the central user (this is checked by the static constructors).
 */
class UserEntity implements UserEntityInterface {
	private int $identifier;

	/**
	 * Create from a UserIdentity, verifying that this local user is owned by the central user.
	 * The provided user doesn't have to be registered locally yet.
	 * @param UserIdentity $user
	 * @return UserEntity|null
	 */
	public static function newFromMWUser( UserIdentity $user ) {
		$centralUserId = Utils::getCentralIdFromLocalUser( $user );
		return $centralUserId ? new static( $centralUserId ) : null;
	}

	/**
	 * Create from a central user ID, verifying that the local user is attached to this central user.
	 * @param int $centralUserId
	 * @return UserEntity|null
	 */
	public static function newFromCentralId( int $centralUserId ) {
		$localUser = Utils::getLocalUserFromCentralId( $centralUserId );
		return $localUser ? new static( $centralUserId ) : null;
	}

	/**
	 * @internal For unit tests only, use self:;newFromCentralId() to validate incoming IDs
	 */
	public function __construct( int $identifier ) {
		$this->identifier = $identifier;
	}

	/**
	 * @inheritDoc
	 * Return the user's identifier (which is the central user ID).
	 */
	public function getIdentifier(): string {
		return (string)$this->identifier;
	}

	public function getCentralId(): int {
		return $this->identifier;
	}

	/**
	 * @return User|false
	 */
	public function getMWUser(): false|User {
		return Utils::getLocalUserFromCentralId( $this->identifier );
	}
}
