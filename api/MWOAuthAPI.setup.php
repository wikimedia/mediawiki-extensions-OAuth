<?php
/**
 * Class containing hooked functions for an OAuth environment
 */
class MWOAuthAPISetup {
	/**
	 * This function must NOT depend on any config vars
	 *
	 * @return void
	 */
	public static function unconditionalSetup() {
		global $wgHooks;

		$wgHooks['UserLoadFromSession'][] = __CLASS__ . '::onUserLoadFromSession';
		$wgHooks['UserLoadAfterLoadFromSession'][] = __CLASS__ . '::onUserLoadAfterLoadFromSession';
		$wgHooks['UserGetRights'][] = __CLASS__ . '::onUserGetRights';
		$wgHooks['UserIsEveryoneAllowed'][] = __CLASS__ . '::onUserIsEveryoneAllowed';
		$wgHooks['ApiCheckCanExecute'][] = __CLASS__ . '::onApiCheckCanExecute';
		$wgHooks['RecentChange_save'][] = __CLASS__ . '::onRecentChange_save';
		$wgHooks['CentralAuthAbortCentralAuthToken'][] = __CLASS__ . '::onCentralAuthAbortCentralAuthToken';
	}

	/**
	 * Create the appropriate type of exception to throw, based on MW_API
	 *
	 * @param string $msgKey Key for the error message
	 * Varargs: normal message parameters.
	 * @return MWException
	 */
	private static function makeException( $msgKey /* ... */ ) {
		$params = func_get_args();
		array_shift( $params );
		$msg = wfMessage( $msgKey, $params );
		if ( defined( 'MW_API' ) ) {
			$msg = $msg->inLanguage( 'en' )->useDatabase( false )->plain();
			return new UsageException( $msg, $msgKey );
		} else {
			return new ErrorPageError( 'mwoauth-invalid-authorization-title', $msg );
		}
	}

	/**
	 * Validate the OAuth headers and fetch the access token
	 *
	 * @throws UsageException if the headers are invalid and MW_API is defined
	 * @throws ErrorPageError if the headers are invalid and MW_API is not defined
	 * @return OAuthToken|null
	 */
	private static function getOAuthAccessToken() {
		static $result = false;
		if ( $result === false ) {
			$context = RequestContext::getMain();
			$request = $context->getRequest();
			$title = $context->getTitle();
			if ( !MWOAuthUtils::hasOAuthHeaders( $request ) || $title->isSpecial( 'MWOAuth' ) ) {
				$result = null;
			} else {
				try {
					$server = MWOAuthUtils::newMWOAuthServer();
					$oauthRequest = MWOAuthRequest::fromRequest( $request );
					list( , $result ) = $server->verify_request( $oauthRequest );
				} catch ( OAuthException $ex ) {
					$result = $ex;
				}
			}
		}

		if ( $result instanceof OAuthException ) {
			throw self::makeException( 'mwoauth-invalid-authorization', $result->getMessage() );
		}
		return $result;
	}

	/**
	 * User is being loaded from session data
	 *
	 * We need to validate the OAuth credentials, and tag this user object.
	 *
	 * @param User $user
	 * @param boolean|null &$result Set to a boolean to skip the normal loading
	 * @throws MWException
	 * @return boolean
	 */
	public static function onUserLoadFromSession( User $user, &$result ) {
		global $wgMemc, $wgBlockDisablesLogin;

		$user->oAuthSessionData = array();
		try {
			$accesstoken = self::getOAuthAccessToken();
			if ( $accesstoken !== null ) {
				$wiki = wfWikiID();
				$dbr = MWOAuthUtils::getCentralDB( DB_SLAVE );
				$access = MWOAuthConsumerAcceptance::newFromToken( $dbr, $accesstoken->key );
				if ( $access->get( 'wiki' ) !== '*' && $access->get( 'wiki' ) !== $wiki ) {
					throw self::makeException( 'mwoauth-invalid-authorization-wrong-wiki', $wiki );
				}
				$consumer = MWOAuthConsumer::newFromId( $dbr, $access->get( 'consumerId' ) );
				if ( $consumer->get( 'stage' ) !== MWOAuthConsumer::STAGE_APPROVED
					&& !$consumer->isPendingAndOwnedBy( $user ) // let publisher test this
				) {
					throw self::makeException( 'mwoauth-invalid-authorization-not-approved' );
				} elseif ( $consumer->get( 'wiki' ) !== '*'
					&& $consumer->get( 'wiki' ) !== $wiki
				) {
					throw self::makeException( 'mwoauth-invalid-authorization-wrong-wiki', $wiki );
				}
				$localUser = MWOAuthUtils::getLocalUserFromCentralId( $access->get( 'userId' ) );
				if ( !$localUser || !$localUser->isLoggedIn() ) {
					throw self::makeException( 'mwoauth-invalid-authorization-invalid-user' );
				} elseif ( $user->isLoggedIn() && $user->getId() !== $localUser->getId() ) {
					throw self::makeException( 'mwoauth-invalid-authorization-wrong-user' );
				}
				if ( $localUser->isLocked() || ( $wgBlockDisablesLogin && $localUser->isBlocked() ) ) {
					throw self::makeException( 'mwoauth-invalid-authorization-blocked-user' );
				}
				$user->setID( $localUser->getId() );
				$user->loadFromId();
				$user->oAuthSessionData += array(
					'accesstoken' => $accesstoken,
					'rights' => MWOAuthUtils::getGrantRights( $access->get( 'grants' ) ),
				);
				// Setup a session for this OAuth user, so edit tokens work.
				// Preserve the session ID used so clients can ignore cookies.
				$key = wfMemcKey( 'oauthsessionid', $access->get( 'id' ) );
				$sessionId = $wgMemc->get( $key ) ?: MWCryptRand::generateHex( 32, true );
				$wgMemc->set( $key, $sessionId, 3600 ); // create/renew
				wfSetupSession( $sessionId ); // create/reuse this "anonymous" session
				Hooks::register( 'AfterFinalPageOutput', function( $out ) {
					// Just in case, make sure this is not a valid login session for sanity
					RequestContext::getMain()->getRequest()->setSessionData( 'wsUserName', null );
				} );

				$result = true;
			}
		} catch( ErrorPageError $ex ) {
			// We can't throw an ErrorPageError from UserLoadFromSession,
			// because OutputPage needs a User object and it wouldn't be
			// available yet. The UserLoadAfterLoadFromSession hook function
			// will throw the ErrorPageError when it is safe to do so.
			$user->oAuthSessionData['exception'] = $ex;
			$result = false;
			return false;
		}

		return true;
	}

	/**
	 * Called after user is loaded from session data
	 *
	 * If the user somehow missed our onUserLoadFromSession, then there are
	 * multiple fancy auth mechanisms going on. Don't allow that.
	 *
	 * If UserLoadFromSession couldn't throw an ErrorPageError, throw it now.
	 *
	 * @throws ErrorPageError
	 * @throws MWException
	 * @param User $user
	 * @return boolean
	 */
	public static function onUserLoadAfterLoadFromSession( User $user ) {
		// If there was an exception that couldn't be thrown from
		// UserLoadFromSession, throw it now.
		if ( isset( $user->oAuthSessionData['exception'] ) ) {
			throw $user->oAuthSessionData['exception'];
		}

		// If we have OAuth headers, the oAuthSessionData had better be valid
		if ( self::getOAuthAccessToken() !== null &&
			!isset( $user->oAuthSessionData['accesstoken'] )
		) {
			throw new MWException( __METHOD__ . ': OAuth headers are present, but the ' .
				__CLASS__ . '::onUserLoadFromSession hook function was not called' );
		}

		return true;
	}

	/**
	 * Called when the user's rights are being fetched
	 *
	 * @param User $user
	 * @param array &$rights current rights list
	 * @return boolean
	 */
	public static function onUserGetRights( User $user, array &$rights ) {
		if ( isset( $user->oAuthSessionData['rights'] ) ) {
			$rights = array_intersect( $rights, $user->oAuthSessionData['rights'] );
		}
		return true;
	}

	/**
	 * Called to check if everyone has a particular user right
	 *
	 * @param string $right
	 * @return boolean
	 */
	public static function onUserIsEveryoneAllowed( $right ) {
		/** @todo: If we implement a "default" grant, return true for rights granted there. */
		return false;
	}

	/**
	 * Disable certain API modules when used with OAuth
	 *
	 * Modules such as ApiLogin and ApiLogout make no sense with OAuth.
	 *
	 * @param ApiBase $module
	 * @param User $user
	 * @param string|array &$message
	 * @return boolean
	 */
	public static function onApiCheckCanExecute( ApiBase $module, User $user, &$message ) {
		global $wgMWOauthDisabledApiModules;

		if ( !isset( $user->oAuthSessionData['accesstoken'] ) ) {
			return true;
		}

		foreach ( $wgMWOauthDisabledApiModules as $badModule ) {
			if ( $module instanceof $badModule ) {
				// Awful interface, API.
				ApiBase::$messageMap['mwoauth-api-module-disabled'] = array(
					'code' => 'mwoauth-api-module-disabled',
					'info' => 'The "$1" module is not available with OAuth.',
				);
				$message = array( 'mwoauth-api-module-disabled', $module->getModuleName() );
				return false;
			}
		}

		return true;
	}

	/**
	 * Record the fact that OAuth was used for anything added to RecentChanges.
	 *
	 * @param RecentChange $rc
	 * @return boolean true
	 */
	public static function onRecentChange_save( $rc ) {
		$accesstoken = self::getOAuthAccessToken();
		if ( $accesstoken !== null ) {
			$dbr = MWOAuthUtils::getCentralDB( DB_SLAVE );
			$access = MWOAuthConsumerAcceptance::newFromToken( $dbr, $accesstoken->key );
			$consumerId = $access->get( 'consumerId' );
			ChangeTags::addTags(
				"OAuth CID: $consumerId",
				$rc->mAttribs['rc_id'],
				$rc->mAttribs['rc_this_oldid'],
				$rc->mAttribs['rc_logid']
			);
		}
		return true;
	}

	/**
	 * Prevent CentralAuth from issuing centralauthtokens if we have
	 * OAuth headers in this request.
	 * @return boolean
	 */
	public static function onCentralAuthAbortCentralAuthToken() {
		$request = RequestContext::getMain()->getRequest();
		if ( MWOAuthUtils::hasOAuthHeaders( $request ) ) {
			return false;
		}
		return true;
	}
}
