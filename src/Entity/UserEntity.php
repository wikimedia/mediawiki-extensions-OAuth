<?php

namespace MediaWiki\Extension\OAuth\Entity;

use League\OAuth2\Server\Entities\UserEntityInterface;
use MediaWiki\Exception\MWException;
use MediaWiki\Extension\OAuth\Backend\Utils;
use MediaWiki\User\User;

class UserEntity implements UserEntityInterface {
	private int $identifier;

	/**
	 * @param User $user
	 * @return UserEntity|null
	 */
	public static function newFromMWUser( User $user ) {
		try {
			$userId = Utils::getCentralIdFromLocalUser( $user );
			if ( !$userId ) {
				return null;
			}
			return new static( $userId );
		} catch ( MWException ) {
			return null;
		}
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
