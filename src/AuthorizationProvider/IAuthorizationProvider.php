<?php

namespace MediaWiki\Extension\OAuth\AuthorizationProvider;

use MediaWiki\Extension\OAuth\Entity\UserEntity;

interface IAuthorizationProvider {

	/**
	 * Set user that on whose behalf
	 * the client is making the request
	 */
	public function setUser( UserEntity $user );

	/**
	 * Must user explicitly allow application
	 * to use this grant type
	 *
	 * @return bool
	 */
	public function needsUserApproval();

}
