<?php
/**
 * Static utility functions for OAuth
 *
 * @file
 * @ingroup OAuth
 */
class MWOAuthUtils {
	/**
	 * @return bool
	 */
	public static function isCentralWiki() {
		global $wgMWOAuthCentralWiki;

		return ( wfWikiId() === $wgMWOAuthCentralWiki );
	}

	/**
	 * @param integer $index DB_MASTER/DB_SLAVE
	 * @return DBConnRef
	 */
	public static function getCentralDB( $index ) {
		global $wgMWOAuthCentralWiki;

		return wfGetLB( $wgMWOAuthCentralWiki )->getConnectionRef(
			$index, array(), $wgMWOAuthCentralWiki );
	}

	/**
	 * @param DBConnRef $db
	 * @return array
	 */
	public static function getConsumerStateCounts( DBConnRef $db ) {
		$res = $db->select( 'oauth_registered_consumer',
			array( 'oarc_stage', 'count' => 'COUNT(*)' ),
			array(),
			__METHOD__,
			array( 'GROUP BY' => 'oarc_stage' )
		);
		$table = array(
			MWOAuthConsumer::STAGE_APPROVED => 0,
			MWOAuthConsumer::STAGE_DISABLED => 0,
			MWOAuthConsumer::STAGE_EXPIRED  => 0,
			MWOAuthConsumer::STAGE_PROPOSED => 0,
			MWOAuthConsumer::STAGE_REJECTED => 0,
		);
		foreach ( $res as $row ) {
			$table[(int)$row->oarc_stage] = (int)$row->count;
		}
		return $table;
	}

	/**
	 * Sanitize the output of apache_request_headers because
	 * we always want the keys to be Cased-Like-This and arh()
	 * returns the headers in the same case as they are in the
	 * request
	 * @return Array of apache headers and their values
	 */
	public static function getHeaders() {
		$request = RequestContext::getMain()->getRequest();
		$headers = $request->getAllHeaders();

		$out = array();
		foreach ($headers AS $key => $value) {
			$key = str_replace(
				" ",
				"-",
				ucwords( strtolower( str_replace( "-", " ", $key) ) )
			);
			$out[$key] = $value;
		}
		return $out;
	}

	/**
	 * Test this request for an OAuth Authorization header
	 * @param WebRequest $request the MediaWiki request
	 * @return Boolean (true if a header was found)
	 */
	public static function hasOAuthHeaders( WebRequest $request ) {
		$header = $request->getHeader( 'Authorization' );
		if ( $header !== false && substr( $header, 0, 6 ) == 'OAuth ' ) {
			return true;
		}
		return false;
	}

	/**
	 * Make a cache key for the given arguments, that (hopefully) won't clash with
	 * anything else in your cache
	 */
	public static function getCacheKey( /* varags */ ) {
		global $wgMWOAuthCentralWiki;

		$args = func_get_args();
		return "OAUTH:$wgMWOAuthCentralWiki:" . implode( ':', $args );
	}


	/**
	 * @param DBConnRef $dbw
	 * @return void
	 */
	public static function runAutoMaintenance( DBConnRef $dbw ) {
		global $wgMWOAuthRequestExpirationAge;

		if ( $wgMWOAuthRequestExpirationAge <= 0 ) {
			return;
		}

		$cutoff = $dbw->timestamp( time() - $wgMWOAuthRequestExpirationAge );
		$dbw->onTransactionIdle( function() use ( $dbw, $cutoff ) {
			$dbw->update( 'oauth_registered_consumer',
				array( 'oarc_stage' => MWOAuthConsumer::STAGE_EXPIRED,
					'oarc_stage_timestamp' => $dbw->timestamp() ),
				array( 'oarc_stage' => MWOAuthConsumer::STAGE_PROPOSED,
					'oarc_stage_timestamp < ' . $dbw->addQuotes( $cutoff ) ),
				__METHOD__
			);
		} );
	}

	/**
	 * @return array
	 */
	public static function getValidGrants() {
		global $wgMWOAuthGrantPermissions;

		return array_keys( $wgMWOAuthGrantPermissions );
	}

	/**
	 * @return array
	 */
	public static function getRightsByGrant() {
		global $wgMWOAuthGrantPermissions;

		$res = array();
		foreach ( $wgMWOAuthGrantPermissions as $grant => $rights ) {
			$res[$grant] = array_keys( array_filter( $rights ) );
		}
		return $res;
	}

	/**
	 * @param string $grant
	 * @return string Grant description
	 */
	public static function grantName( $grant ) {
		// Give grep a chance to find the usages:
		// mwoauth-grant-blockusers, mwoauth-grant-createeditmovepage, mwoauth-grant-delete,
		// mwoauth-grant-editinterface, mwoauth-grant-editmycssjs, mwoauth-grant-editmywatchlist,
		// mwoauth-grant-editpage, mwoauth-grant-editprotected, mwoauth-grant-highvolume,
		// mwoauth-grant-oversight, mwoauth-grant-patrol, mwoauth-grant-protect, mwoauth-grant-rollback,
		// mwoauth-grant-sendemail, mwoauth-grant-uploadeditmovefile, mwoauth-grant-uploadfile,
		// mwoauth-grant-useoauth, mwoauth-grant-viewdeleted, mwoauth-grant-viewmywatchlist
		$msg = wfMessage( "mwoauth-grant-$grant" );
		$msg = $msg->exists() ? $msg : wfMessage( "mwoauth-grant-generic", $grant );
		return $msg->text();
	}

	/**
	 * @param array $grants
	 * @return array Array of corresponding grant descriptions
	 */
	public static function grantNames( array $grants ) {
		return array_map( 'MWOAuthUtils::grantName', $grants );
	}

	/**
	 * @param array|string $grants
	 * @return array
	 */
	public static function getGrantRights( $grants ) {
		global $wgMWOAuthGrantPermissions;

		$rights = array();
		foreach ( (array)$grants as $grant ) {
			if ( isset( $wgMWOAuthGrantPermissions[$grant] ) ) {
				$rights = array_merge( $rights,
					array_keys( array_filter( $wgMWOAuthGrantPermissions[$grant] ) ) );
			}
		}
		return array_unique( $rights );
	}

	/**
	 * @param array $grants
	 * @return bool
	 */
	public static function grantsAreValid( array $grants ) {
		return array_diff( $grants, self::getValidGrants() ) === array();
	}

	/**
	 * @param array $restrictions
	 * @return bool
	 */
	public static function restrictionsAreValid( array $restrictions ) {
		static $validKeys = array( 'IPAddresses' );
		static $neededKeys = array( 'IPAddresses' );

		$keys = array_keys( $restrictions );
		if ( array_diff( $keys, $validKeys ) ) {
			return false;
		} elseif ( array_diff( $neededKeys, $keys ) ) {
			return false;
		}
		foreach ( $restrictions['IPAddresses'] as $ip ) {
			if ( !IP::isIPAddress( $ip ) ) {
				return false;
			}
		}

		return true;
	}

	/**
	 * Quickly get a new server with all the default configurations
	 *
	 * @return MWOAuthServer with default configurations
	 */
	public static function newMWOAuthServer() {
		global $wgMemc;

		$dbr = MWOAuthUtils::getCentralDB( DB_SLAVE );
		$store = new MWOAuthDataStore( $dbr, $wgMemc );
		$server = new MWOAuthServer( $store );
		$server->add_signature_method( new OAuthSignatureMethod_HMAC_SHA1() );
		$server->add_signature_method( new MWOAuthSignatureMethod_RSA_SHA1( $store ) );

		return $server;
	}

	/**
	 * Given a central wiki user ID, get a central user name
	 *
	 * @param integer $userId
	 * @param bool|User $audience show hidden names based on this user, or false for public
	 * @throws MWException
	 * @return string|bool User name, false if not found, empty string if name is hidden
	 */
	public static function getCentralUserNameFromId( $userId, $audience = false ) {
		global $wgMWOAuthCentralWiki, $wgMWOAuthSharedUserIDs, $wgMWOAuthSharedUserSource;

		if ( $wgMWOAuthSharedUserIDs ) { // global ID required via hook
			if ( !Hooks::isRegistered( 'OAuthGetUserNamesFromCentralIds' ) ) {
				throw new MWException( "No handler for 'OAuthGetUserNamesFromCentralIds' hook" );
			}
			$namesById = array( $userId => null );
			wfRunHooks( 'OAuthGetUserNamesFromCentralIds',
				array( $wgMWOAuthCentralWiki,
					&$namesById,
					$audience,
					$wgMWOAuthSharedUserSource
				)
			);
			$name = $namesById[$userId];
			if ( $name === null ) {
				// The extension didn't handle the id
				throw new MWException( 'Could not lookup name from ID via hook.' );
			}
		} else {
			$name = '';
			$user = User::newFromName( $userId );
			if ( !$user->isHidden()
				|| ( $audience instanceof User && $audience->isAllowed( 'hideuser' ) )
			) {
				$name = $user->getName();
			}
		}

		return $name;
	}

	/**
	 * Given a central wiki user ID, get a local User object
	 *
	 * @param integer $userId
	 * @throws MWException
	 * @return User|bool User or false if not found
	 */
	public static function getLocalUserFromCentralId( $userId ) {
		global $wgMWOAuthCentralWiki, $wgMWOAuthSharedUserIDs, $wgMWOAuthSharedUserSource;

		if ( $wgMWOAuthSharedUserIDs ) { // global ID required via hook
			if ( !Hooks::isRegistered( 'OAuthGetLocalUserFromCentralId' ) ) {
				throw new MWException( "No handler for 'OAuthGetLocalUserFromCentralId' hook" );
			}
			$user = null;
			// Let extensions check that central wiki user ID is attached to a global account
			// and that return the user on this wiki that is attached to that global account
			wfRunHooks( 'OAuthGetLocalUserFromCentralId',
				array( $userId, $wgMWOAuthCentralWiki, &$user, $wgMWOAuthSharedUserSource ) );
			// If there is no local user, the extension should set the user to false
			if ( $user === null ) {
				throw new MWException( 'Could not lookup user from ID via hook.' );
			}
		} else {
			$user = User::newFromId( $userId );
		}

		return $user;
	}

	/**
	 * Given a local User object, get the user ID for that user on the central wiki
	 *
	 * @param User $user
	 * @throws MWException
	 * @return integer|bool ID or false if not found
	 */
	public static function getCentralIdFromLocalUser( User $user ) {
		global $wgMWOAuthCentralWiki, $wgMWOAuthSharedUserIDs, $wgMWOAuthSharedUserSource;

		if ( $wgMWOAuthSharedUserIDs ) { // global ID required via hook
			if ( isset( $user->oAuthUserData['centralId'] ) ) {
				$id = $user->oAuthUserData['centralId'];
			} else {
				if ( !Hooks::isRegistered( 'OAuthGetCentralIdFromLocalUser' ) ) {
					throw new MWException( "No handler for 'OAuthGetCentralIdFromLocalUser' hook" );
				}
				$id = null;
				// Let CentralAuth check that $user is attached to a global account and
				// that the foreign local account on the central wiki is also attached to it
				wfRunHooks( 'OAuthGetCentralIdFromLocalUser',
					array( $user, $wgMWOAuthCentralWiki, &$id, $wgMWOAuthSharedUserSource ) );
				// If there is no such user, the extension should set the ID to false
				if ( $id === null ) {
					throw new MWException( 'Could not lookup ID for user via hook.' );
				}
				// Process cache the result to avoid queries
				$user->oAuthUserData['centralId'] = $id;
			}
		} else {
			$id = $user->getId();
		}

		return $id;
	}

	/**
	 * Get the effective secret key/token to use for OAuth purposes.
	 *
	 * For example, the "secret key" and "access secret" values that are
	 * used for authenticating request should be the result of applying this
	 * function to the respective values stored in the DB. This means that
	 * a leak of DB values is not enough to impersonate consumers.
	 *
	 * @param string $secret
	 * @return string
	 */
	public static function hmacDBSecret( $secret ) {
		global $wgOAuthSecretKey;

		return $wgOAuthSecretKey ? hash_hmac( 'sha1', $secret, $wgOAuthSecretKey ) : $secret;
	}
}
