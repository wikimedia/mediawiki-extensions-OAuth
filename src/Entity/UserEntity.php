<?php

namespace MediaWiki\Extension\OAuth\Entity;

use League\OAuth2\Server\Entities\UserEntityInterface;
use MediaWiki\Extension\OAuth\Backend\Utils;
use MediaWiki\User\User;
use MWException;

class UserEntity implements UserEntityInterface {

	/**
	 * @var int
	 */
	private $identifier;

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
		} catch ( MWException $ex ) {
			return null;
		}
	}

	/**
	 * @param int $identifier
	 */
	public function __construct( $identifier ) {
		$this->identifier = $identifier;
	}

	/**
	 * Return the user's identifier.
	 *
	 * @return int
	 */
	public function getIdentifier() {
		return $this->identifier;
	}

	/**
	 * @return User|false
	 */
	public function getMWUser() {
		return Utils::getLocalUserFromCentralId( $this->identifier );
	}
}
