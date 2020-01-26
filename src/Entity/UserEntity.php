<?php

namespace MediaWiki\Extensions\OAuth\Entity;

use League\OAuth2\Server\Entities\UserEntityInterface;
use MediaWiki\Extensions\OAuth\MWOAuthUtils;
use MWException;
use User;

class UserEntity implements UserEntityInterface {

	/**
	 * @var int
	 */
	private $identifier = 0;

	/**
	 * @param User $user
	 * @return UserEntity|null
	 */
	public static function newFromMWUser( User $user ) {
		try {
			$userId = MWOAuthUtils::getCentralIdFromLocalUser( $user );
			if ( !$userId ) {
				return null;
			}
			return new static( $userId );
		} catch ( MWException $ex ) {
			return null;
		}
	}

	/**
	 * @param string $identifier
	 */
	public function __construct( $identifier ) {
		$this->identifier = $identifier;
	}

	/**
	 * Return the user's identifier.
	 *
	 * @return mixed
	 */
	public function getIdentifier() {
		return $this->identifier;
	}

	/**
	 * @return bool|User
	 */
	public function getMWUser() {
		return MWOAuthUtils::getLocalUserFromCentralId( $this->identifier );
	}
}
