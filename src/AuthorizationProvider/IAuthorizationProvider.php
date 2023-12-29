<?php

namespace MediaWiki\Extension\OAuth\AuthorizationProvider;

use MediaWiki\User\User;

interface IAuthorizationProvider {

	/**
	 * Set user that on whose behalf
	 * the client is making the request
	 *
	 * @param User $user
	 */
	public function setUser( User $user );

	/**
	 * Must user explicitly allow application
	 * to use this grant type
	 *
	 * @return bool
	 */
	public function needsUserApproval();

}
