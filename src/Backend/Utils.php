<?php

namespace MediaWiki\Extension\OAuth\Backend;

use MediaWiki\Context\RequestContext;
use MediaWiki\Deferred\AutoCommitUpdate;
use MediaWiki\Deferred\DeferredUpdates;
use MediaWiki\Exception\MWException;
use MediaWiki\Extension\Notifications\Model\Event;
use MediaWiki\Extension\OAuth\Lib\OAuthSignatureMethodHmacSha1;
use MediaWiki\Logger\LoggerFactory;
use MediaWiki\MediaWikiServices;
use MediaWiki\Request\WebRequest;
use MediaWiki\Title\Title;
use MediaWiki\User\CentralId\CentralIdLookup;
use MediaWiki\User\User;
use MediaWiki\WikiMap\WikiMap;
use Wikimedia\ObjectCache\BagOStuff;
use Wikimedia\Rdbms\IDatabase;
use Wikimedia\Rdbms\ILBFactory;
use Wikimedia\Rdbms\IReadableDatabase;

/**
 * Static utility functions for OAuth
 *
 * @ingroup OAuth
 */
class Utils {
	/**
	 * @return bool
	 */
	public static function isCentralWiki() {
		return ( WikiMap::getCurrentWikiId() === self::getCentralWiki() );
	}

	/**
	 * @return string|bool
	 */
	public static function getCentralWiki() {
		global $wgMWOAuthCentralWiki;

		return $wgMWOAuthCentralWiki;
	}

	/**
	 * Get the URL to be used as the issuer ('iss') claim in JWTs.
	 */
	public static function getJwtIssuer(): string {
		$centralWiki = self::getCentralWiki() ?: WikiMap::getCurrentWikiId();
		$wiki = WikiMap::getWiki( $centralWiki );
		if ( !$wiki ) {
			LoggerFactory::getInstance( 'OAuth' )->error( 'WikiMap not configured' );
			return MediaWikiServices::getInstance()->getUrlUtils()->getCanonicalServer();
		}
		return $wiki->getCanonicalServer();
	}

	/**
	 * @param int $index DB_PRIMARY/DB_REPLICA
	 * @return IDatabase|IReadableDatabase
	 */
	public static function getCentralDB( $index ) {
		/** @var ILBFactory $connProvider To support hasOrMadeRecentPrimaryChanges() */
		$connProvider = MediaWikiServices::getInstance()->getConnectionProvider();
		'@phan-var ILBFactory $connProvider';

		// T244415: Use the primary database if there were changes
		if ( $index === DB_PRIMARY ||
			( $index === DB_REPLICA && $connProvider->hasOrMadeRecentPrimaryChanges() )
		) {
			return $connProvider->getPrimaryDatabase( 'virtual-oauth' );
		}

		return $connProvider->getReplicaDatabase( 'virtual-oauth' );
	}

	/**
	 * @return BagOStuff
	 */
	public static function getSessionCache() {
		global $wgMWOAuthSessionCacheType;
		global $wgSessionCacheType;

		$sessionCacheType = $wgMWOAuthSessionCacheType ?? $wgSessionCacheType;
		return MediaWikiServices::getInstance()
			->getObjectCacheFactory()->getInstance( $sessionCacheType );
	}

	/**
	 * Get the cache type for OAuth 1.0 nonces
	 * @return BagOStuff
	 */
	public static function getNonceCache() {
		global $wgMWOAuthNonceCacheType, $wgMWOAuthSessionCacheType, $wgSessionCacheType;

		$cacheType = $wgMWOAuthNonceCacheType
			?? $wgMWOAuthSessionCacheType ?? $wgSessionCacheType;
		return MediaWikiServices::getInstance()
			->getObjectCacheFactory()->getInstance( $cacheType );
	}

	/**
	 * @param IDatabase $db
	 * @return int[]
	 */
	public static function getConsumerStateCounts( IDatabase $db ) {
		$res = $db->newSelectQueryBuilder()
			->select( [ 'oarc_stage', 'count' => 'COUNT(*)' ] )
			->from( 'oauth_registered_consumer' )
			->groupBy( 'oarc_stage' )
			->caller( __METHOD__ )
			->fetchResultSet();
		$table = [
			Consumer::STAGE_APPROVED => 0,
			Consumer::STAGE_DISABLED => 0,
			Consumer::STAGE_EXPIRED  => 0,
			Consumer::STAGE_PROPOSED => 0,
			Consumer::STAGE_REJECTED => 0,
		];
		foreach ( $res as $row ) {
			$table[(int)$row->oarc_stage] = (int)$row->count;
		}
		return $table;
	}

	/**
	 * Get request headers.
	 * Sanitizes the output of apache_request_headers because
	 * we always want the keys to be Cased-Like-This and arh()
	 * returns the headers in the same case as they are in the
	 * request
	 * @return array Header name => value
	 */
	public static function getHeaders() {
		$request = RequestContext::getMain()->getRequest();
		$headers = $request->getAllHeaders();

		$out = [];
		foreach ( $headers as $key => $value ) {
			$key = str_replace(
				" ",
				"-",
				ucwords( strtolower( str_replace( "-", " ", $key ) ) )
			);
			$out[$key] = $value;
		}
		return $out;
	}

	/**
	 * Test this request for an OAuth Authorization header
	 * @param WebRequest $request the MediaWiki request
	 * @return bool true if a header was found
	 */
	public static function hasOAuthHeaders( WebRequest $request ) {
		$header = $request->getHeader( 'Authorization' );

		return $header !== false && strpos( $header, 'OAuth ' ) === 0;
	}

	/**
	 * Make a cache key for the given arguments, that (hopefully) won't clash with
	 * anything else in your cache
	 * @param string ...$args
	 * @return string
	 */
	public static function getCacheKey( ...$args ) {
		global $wgMWOAuthCentralWiki;

		return "OAUTH:$wgMWOAuthCentralWiki:" . implode( ':', $args );
	}

	/**
	 * @param IDatabase $dbw
	 * @return void
	 */
	public static function runAutoMaintenance( IDatabase $dbw ) {
		global $wgMWOAuthRequestExpirationAge;

		if ( $wgMWOAuthRequestExpirationAge <= 0 ) {
			return;
		}

		$cutoff = time() - $wgMWOAuthRequestExpirationAge;
		$fname = __METHOD__;
		DeferredUpdates::addUpdate(
			new AutoCommitUpdate(
				$dbw,
				__METHOD__,
				static function ( IDatabase $dbw ) use ( $cutoff, $fname ) {
					$dbw->newUpdateQueryBuilder()
						->update( 'oauth_registered_consumer' )
						->set( [
							'oarc_stage' => Consumer::STAGE_EXPIRED,
							'oarc_stage_timestamp' => $dbw->timestamp()
						] )
						->where( [
							'oarc_stage' => Consumer::STAGE_PROPOSED,
							$dbw->expr( 'oarc_stage_timestamp', '<', $dbw->timestamp( $cutoff ) )
						] )
						->caller( $fname )
						->execute();
				}
			)
		);
	}

	/**
	 * Get the pretty name of an OAuth wiki ID restriction value
	 *
	 * @param string $wikiId A wiki ID or '*'
	 * @return string
	 */
	public static function getWikiIdName( $wikiId ) {
		if ( $wikiId === '*' ) {
			return wfMessage( 'mwoauth-consumer-allwikis' )->text();
		}

		$host = WikiMap::getWikiName( $wikiId );
		if ( strpos( $host, '.' ) ) {
			// e.g. "en.wikipedia.org"
			return $host;
		}

		return $wikiId;
	}

	/**
	 * Get the pretty names of all local wikis
	 *
	 * @return string[] associative array of local wiki names indexed by wiki ID
	 */
	public static function getAllWikiNames() {
		global $wgConf;
		$wikiNames = [];
		foreach ( $wgConf->getLocalDatabases() as $dbname ) {
			$name = self::getWikiIdName( $dbname );
			if ( $name != $dbname ) {
				$wikiNames[$dbname] = $name;
			}
		}
		return $wikiNames;
	}

	/**
	 * Quickly get a new server with all the default configurations
	 *
	 * @return MWOAuthServer with default configurations
	 */
	public static function newMWOAuthServer() {
		$store = static::newMWOAuthDataStore();
		$server = new MWOAuthServer( $store );
		$server->add_signature_method( new OAuthSignatureMethodHmacSha1() );
		$server->add_signature_method( new MWOAuthSignatureMethodRsaSha1( $store ) );

		return $server;
	}

	public static function newMWOAuthDataStore(): MWOAuthDataStore {
		$lb = MediaWikiServices::getInstance()->getDBLoadBalancer();
		$dbr = self::getCentralDB( DB_REPLICA );
		$dbw = $lb->getServerCount() > 1 ? self::getCentralDB( DB_PRIMARY ) : null;
		return new MWOAuthDataStore( $dbr, $dbw, self::getSessionCache(), self::getNonceCache() );
	}

	/**
	 * Given a central wiki user ID, get a central username
	 *
	 * @param int $userId
	 * @param bool|User|string $audience show hidden names based on this user, or false for public
	 * @throws MWException
	 * @return string|bool Username, false if not found, empty string if name is hidden
	 */
	public static function getCentralUserNameFromId( $userId, $audience = false ) {
		global $wgMWOAuthSharedUserIDs, $wgMWOAuthSharedUserSource;

		// global ID required via hook
		if ( $wgMWOAuthSharedUserIDs ) {
			$lookup = MediaWikiServices::getInstance()
				->getCentralIdLookupFactory()
				->getLookup( $wgMWOAuthSharedUserSource );
			$name = $lookup->nameFromCentralId(
				$userId,
				$audience === 'raw'
					? CentralIdLookup::AUDIENCE_RAW
					: ( $audience ?: CentralIdLookup::AUDIENCE_PUBLIC )
			);
			if ( $name === null ) {
				$name = false;
			}
		} else {
			$name = '';
			$user = User::newFromId( $userId );
			$permissionManager = MediaWikiServices::getInstance()->getPermissionManager();

			if ( $audience === 'raw'
				|| !$user->isHidden()
				|| ( $audience instanceof User && $permissionManager->userHasRight( $audience, 'hideuser' ) )
			) {
				$name = $user->getName();
			}
		}

		return $name;
	}

	/**
	 * Given a central wiki user ID, get a local User object
	 *
	 * @param int $userId
	 * @return User|false False if not found
	 */
	public static function getLocalUserFromCentralId( $userId ) {
		global $wgMWOAuthSharedUserIDs, $wgMWOAuthSharedUserSource;

		// global ID required via hook
		if ( $wgMWOAuthSharedUserIDs ) {
			$lookup = MediaWikiServices::getInstance()
				->getCentralIdLookupFactory()
				->getLookup( $wgMWOAuthSharedUserSource );
			$user = $lookup->localUserFromCentralId( $userId );
			if ( $user === null || !$lookup->isAttached( $user ) ) {
				return false;
			}
			return User::newFromIdentity( $user );
		}

		return User::newFromId( $userId );
	}

	/**
	 * Given a local User object, get the user ID for that user on the central wiki
	 *
	 * @param User $user
	 * @return int|bool ID or false if not found
	 */
	public static function getCentralIdFromLocalUser( User $user ) {
		global $wgMWOAuthSharedUserIDs, $wgMWOAuthSharedUserSource;

		// global ID required via hook
		if ( $wgMWOAuthSharedUserIDs ) {
			// T227688 do not rely on array auto-creation for non-stdClass
			if ( !isset( $user->oAuthUserData ) ) {
				$user->oAuthUserData = [];
			}

			if ( isset( $user->oAuthUserData['centralId'] ) ) {
				$id = $user->oAuthUserData['centralId'];
			} else {
				$lookup = MediaWikiServices::getInstance()
					->getCentralIdLookupFactory()
					->getLookup( $wgMWOAuthSharedUserSource );
				if ( !$lookup->isAttached( $user ) ) {
					$id = false;
				} else {
					$id = $lookup->centralIdFromLocalUser( $user );
					if ( $id === 0 ) {
						$id = false;
					}
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
	 * Given a username, get the user ID for that user on the central wiki.
	 * @param string $username
	 * @return int|bool ID or false if not found
	 */
	public static function getCentralIdFromUserName( $username ) {
		global $wgMWOAuthSharedUserIDs, $wgMWOAuthSharedUserSource;

		// global ID required via hook
		if ( $wgMWOAuthSharedUserIDs ) {
			$lookup = MediaWikiServices::getInstance()
				->getCentralIdLookupFactory()
				->getLookup( $wgMWOAuthSharedUserSource );
			$id = $lookup->centralIdFromName( $username );
			if ( $id === 0 ) {
				$id = false;
			}
		} else {
			$id = false;
			$user = User::newFromName( $username );
			if ( $user instanceof User && $user->getId() > 0 ) {
				$id = $user->getId();
			}
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
		global $wgOAuthSecretKey, $wgSecretKey;

		$secretKey = $wgOAuthSecretKey ?? $wgSecretKey;

		return $secretKey ? hash_hmac( 'sha1', $secret, $secretKey ) : $secret;
	}

	/**
	 * Get a link to the central wiki's user talk page of a user.
	 *
	 * @param string $username the username of the User Talk link
	 * @return string the (proto-relative, urlencoded) url of the central wiki's user talk page
	 */
	public static function getCentralUserTalk( $username ) {
		global $wgMWOAuthCentralWiki, $wgMWOAuthSharedUserIDs;

		if ( $wgMWOAuthSharedUserIDs ) {
			$url = WikiMap::getForeignURL(
				$wgMWOAuthCentralWiki,
				"User_talk:$username"
			);
		} else {
			$url = Title::makeTitleSafe( NS_USER_TALK, $username )->getFullURL();
		}
		return $url;
	}

	/**
	 * @param array $grants
	 * @return bool
	 */
	public static function grantsAreValid( array $grants ) {
		// Remove our special grants before calling the core method
		$grants = array_diff( $grants, [ 'mwoauth-authonly', 'mwoauth-authonlyprivate' ] );
		return MediaWikiServices::getInstance()
			->getGrantsInfo()
			->grantsAreValid( $grants );
	}

	/**
	 * Given an OAuth consumer stage change event, find out who needs to be notified.
	 * Will be used as an EchoAttributeManager::ATTR_LOCATORS callback.
	 * @param Event $event
	 * @return User[]
	 */
	public static function locateUsersToNotify( Event $event ) {
		$agent = $event->getAgent();
		$owner = self::getLocalUserFromCentralId( $event->getExtraParam( 'owner-id' ) );

		$users = [];
		switch ( $event->getType() ) {
			case 'oauth-app-propose':
				// notify OAuth admins about new proposed apps
				$oauthAdmins = self::getOAuthAdmins();
				foreach ( $oauthAdmins as $admin ) {
					if ( $admin->equals( $owner ) ) {
						continue;
					}
					$users[$admin->getId()] = $admin;
				}
				break;
			case 'oauth-app-update':
			case 'oauth-app-approve':
			case 'oauth-app-reject':
			case 'oauth-app-disable':
			case 'oauth-app-reenable':
				// notify owner if someone else changed the status of the app
				if ( !$owner->equals( $agent ) ) {
					$users[$owner->getId()] = $owner;
				}
				break;
		}
		return $users;
	}

	/**
	 * Get the change tag name for a given consumer.
	 * @param int $consumerId
	 * @return string
	 */
	public static function getTagName( $consumerId ) {
		return 'OAuth CID: ' . (int)$consumerId;
	}

	/**
	 * Get the consumer ID from the given change tag name.
	 * @param string $tagName
	 * @return ?int Consumer ID, or null if the change tag doesn't look like one of ours
	 */
	public static function parseTagName( string $tagName ): ?int {
		if ( preg_match( '/^OAuth CID: (\d+)$/', $tagName, $matches ) ) {
			return (int)$matches[1];
		}
		return null;
	}

	/**
	 * Check if a given change tag name should be reserved for this extension.
	 * @param string $tagName
	 * @return bool
	 */
	public static function isReservedTagName( $tagName ) {
		return stripos( $tagName, 'oauth cid:' ) === 0;
	}

	/**
	 * Return a list of all OAuth admins (or the first 5000 in the unlikely case that there is more
	 * than that).
	 * Should be called on the central OAuth wiki.
	 * @return User[]
	 */
	protected static function getOAuthAdmins() {
		global $wgOAuthGroupsToNotify;

		if ( !$wgOAuthGroupsToNotify ) {
			return [];
		}

		return iterator_to_array( User::findUsersByGroup( $wgOAuthGroupsToNotify ) );
	}
}
