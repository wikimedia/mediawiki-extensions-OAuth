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
	 * @TODO: reference count/release DB
	 * @param integer $index DB_MASTER/DB_SLAVE
	 */
	public static function getCentralDB( $index ) {
		global $wgMWOAuthCentralWiki;

		return wfGetDB( $index, array(), $wgMWOAuthCentralWiki );
	}

	/**
	 * @param DatabaseBase $db
	 * @return array
	 */
	public static function getConsumerStateCounts( DatabaseBase $db ) {
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
	 * @param DatabaseBase $dbw
	 * @return void
	 */
	public static function runAutoMaintenance( DatabaseBase $dbw ) {
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
	 * @param string $grant
	 * @return Message
	 */
	public static function grantName( $grant ) {
		$msg = wfMessage( "mwoauth-grant-$grant" );
		return $msg->exists() ? $msg : wfMessage( "mwoauth-grant-generic", $grant );
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
	 * @return MWOAuthServer with default configurations
	 */
	public static function newMWOAuthServer() {
		global $wgMemc;
		$dbr = MWOAuthUtils::getCentralDB( DB_SLAVE );
		$store = new MWOAuthDataStore( $wgMemc, $dbr );
		$server = new MWOAuthServer( $store );
		$server->add_signature_method( new OAuthSignatureMethod_HMAC_SHA1() );
		$server->add_signature_method( new MWOAuthSignatureMethod_RSA_SHA1( $store ) );
		return $server;
	}
}
