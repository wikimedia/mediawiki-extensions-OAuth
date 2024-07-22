<?php

namespace MediaWiki\Extension\OAuth\Backend;

use MediaWiki\Extension\OAuth\Frontend\OAuthLogFormatter;
use MediaWiki\MediaWikiServices;
use MediaWiki\Storage\NameTableAccessException;
use Status;
use User;
use WikiMap;

/**
 * Class containing hooked functions for an OAuth environment
 */
class Hooks {

	/**
	 * Called right after configuration variables have been set.
	 */
	public static function onRegistration() {
		global $wgWikimediaJenkinsCI, $wgOAuth2PrivateKey, $wgOAuth2PublicKey;

		// Set $wgOAuth2PrivateKey and $wgOAuth2PublicKey for Wikimedia Jenkins, PHPUnit.
		if ( defined( 'MW_PHPUNIT_TEST' ) || ( $wgWikimediaJenkinsCI ?? false ) ) {
			$wgOAuth2PrivateKey = <<<EOK
-----BEGIN RSA PRIVATE KEY-----
MIIBOwIBAAJBAMBGXQYJ2lXzLuQkRlWoqYJvSnNGfRvPBUVsbHfFPyCr8i6jBPcO
vtMLFMRAaq4quRDFgQ7YQLvKTqjpN+bo7RECAwEAAQJBAKP3XTzZCihhyYskpBZI
TsW8wnCrm+UrFgOuApHg04S3oeUXpNApxxGy+EX0aBsVoPBuisyBjiJDIFssdgJa
IwECIQDuMipv8QOzA9qJPPpXZCQQN6znXjSE3jZhrBH879SDBQIhAM6lgY0lWB0N
lhQZWtM8jRcxtJUFrApEizE6WFxj/LedAiEAyINgaAVqiMror3iugNyi4ygLHGWY
LnVlMAmKxvMZYQUCIAYTeb6ztWaNSrdmk3QYmLFw5bVoCEn4//q/k2+MBRdFAiA2
MJWJuom6IpoP0UrM/gJbwGxwgZymb4jL+sKFoIqGmA==
-----END RSA PRIVATE KEY-----
EOK;
			$wgOAuth2PublicKey = <<<EOK
-----BEGIN PUBLIC KEY-----
MFwwDQYJKoZIhvcNAQEBBQADSwAwSAJBAMBGXQYJ2lXzLuQkRlWoqYJvSnNGfRvP
BUVsbHfFPyCr8i6jBPcOvtMLFMRAaq4quRDFgQ7YQLvKTqjpN+bo7RECAwEAAQ==
-----END PUBLIC KEY-----
EOK;
		}
	}

	public static function onExtensionFunctions() {
		global $wgLogTypes, $wgLogNames,
			$wgLogHeaders, $wgLogActionsHandlers, $wgActionFilteredLogs;

		if ( Utils::isCentralWiki() ) {
			$wgLogTypes[] = 'mwoauthconsumer';
			$wgLogNames['mwoauthconsumer'] = 'mwoauthconsumer-consumer-logpage';
			$wgLogHeaders['mwoauthconsumer'] = 'mwoauthconsumer-consumer-logpagetext';
			$wgLogActionsHandlers['mwoauthconsumer/*'] = OAuthLogFormatter::class;
			$wgActionFilteredLogs['mwoauthconsumer'] = [
				'approve' => [ 'approve' ],
				'create-owner-only' => [ 'create-owner-only' ],
				'disable' => [ 'disable' ],
				'propose' => [ 'propose' ],
				'reenable' => [ 'reenable' ],
				'reject' => [ 'reject' ],
				'update' => [ 'update' ],
			];
		}
	}

	/**
	 * Reserve change tags that look like an OAuth change tag.
	 *
	 * @param string $tag
	 * @param User|null $user
	 * @param Status &$status
	 * @return bool
	 */
	public static function onChangeTagCanCreate( $tag, ?User $user, Status &$status ) {
		if ( Utils::isReservedTagName( $tag ) ) {
			$status->fatal( 'mwoauth-tag-reserved' );
		}
		return true;
	}

	public static function onMergeAccountFromTo( \User $oUser, \User $nUser ) {
		global $wgMWOAuthSharedUserIDs;

		if ( !$wgMWOAuthSharedUserIDs ) {
			$oldid = $oUser->getId();
			$newid = $nUser->getId();
			if ( $oldid && $newid ) {
				self::doUserIdMerge( $oldid, $newid );
			}
		}

		return true;
	}

	protected static function doUserIdMerge( $oldid, $newid ) {
		$dbw = Utils::getCentralDB( DB_PRIMARY );
		// Merge any consumers register to this user
		$dbw->update( 'oauth_registered_consumer',
			[ 'oarc_user_id' => $newid ],
			[ 'oarc_user_id' => $oldid ],
			__METHOD__
		);
		// Delete any acceptance tokens by the old user ID
		$dbw->delete( 'oauth_accepted_consumer',
			[ 'oaac_user_id' => $oldid ],
			__METHOD__
		);
	}

	public static function onListDefinedTags( &$tags ) {
		return self::getUsedConsumerTags( false, $tags );
	}

	public static function onChangeTagsListActive( &$tags ) {
		return self::getUsedConsumerTags( true, $tags );
	}

	/**
	 * List tags that should show as defined/active on Special:Tags
	 *
	 * Handles both the ChangeTagsListActive and ListDefinedTags hooks. Only
	 * lists those tags that are actually in use on the local wiki, to avoid
	 * flooding Special:Tags with tags for consumers that will never be making
	 * logged actions.
	 *
	 * @param bool $activeOnly true for ChangeTagsListActive, false for ListDefinedTags
	 * @param array &$tags
	 * @return bool
	 */
	private static function getUsedConsumerTags( $activeOnly, &$tags ) {
		// Step 1: Get the list of (active) consumers' tags for this wiki
		$db = Utils::getCentralDB( DB_REPLICA );
		$conds = [
			$db->makeList( [
				'oarc_wiki = ' . $db->addQuotes( '*' ),
				'oarc_wiki = ' . $db->addQuotes( WikiMap::getCurrentWikiId() ),
			], LIST_OR ),
			'oarc_deleted' => 0,
		];
		if ( $activeOnly ) {
			$conds[] = $db->makeList( [
				'oarc_stage = ' . Consumer::STAGE_APPROVED,
				// Proposed consumers are active for the owner, so count them too
				'oarc_stage = ' . Consumer::STAGE_PROPOSED,
			], LIST_OR );
		}
		$res = $db->select(
			'oauth_registered_consumer',
			[ 'oarc_id' ],
			$conds,
			__METHOD__
		);
		$allTags = [];
		foreach ( $res as $row ) {
			$allTags[] = Utils::getTagName( $row->oarc_id );
		}

		// Step 2: Return only those that are in use.
		$changeTagDefStore = MediaWikiServices::getInstance()->getChangeTagDefStore();
		foreach ( $allTags as $tag ) {
			try {
				$changeTagDefStore->getId( $tag );
			} catch ( NameTableAccessException $ex ) {
				continue;
			}
			// if it has an ID, it's in use
			$tags[] = $tag;
		}

		return true;
	}

	public static function onSetupAfterCache() {
		global $wgMWOAuthCentralWiki, $wgMWOAuthSharedUserIDs;

		if ( $wgMWOAuthCentralWiki === false ) {
			// Treat each wiki as its own "central wiki" as there is no actual one
			$wgMWOAuthCentralWiki = WikiMap::getCurrentWikiId();
		} else {
			// There is actually a central wiki, requiring global user IDs via hook
			$wgMWOAuthSharedUserIDs = true;
		}
	}

	public static function onApiRsdServiceApis( array &$apis ) {
		$apis['MediaWiki']['settings']['OAuth'] = true;
	}
}
