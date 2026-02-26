<?php

namespace MediaWiki\Extension\OAuth\Entity;

use League\OAuth2\Server\Entities\UserEntityInterface;
use MediaWiki\Extension\OAuth\Backend\Utils;
use MediaWiki\User\User;

class UserEntity implements UserEntityInterface {
	private int $identifier;

	/**
	 * @param User $user
	 * @return UserEntity|null
	 */
	public static function newFromMWUser( User $user ) {
		$userId = Utils::getCentralIdFromLocalUser( $user );
		return $userId ? new static( $userId ) : null;
	}

	public function __construct( int $identifier ) {
		$this->identifier = $identifier;
	}

	/**
	 * Return the user's identifier.
	 */
	public function getIdentifier(): string {
		return (string)$this->identifier;
	}

	/**
	 * @return User|false
	 */
	public function getMWUser() {
		return Utils::getLocalUserFromCentralId( $this->identifier );
	}
}
