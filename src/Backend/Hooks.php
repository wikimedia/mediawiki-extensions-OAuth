<?php

namespace MediaWiki\Extension\OAuth\Backend;

use MediaWiki\Api\Hook\ApiRsdServiceApisHook;
use MediaWiki\ChangeTags\Hook\ChangeTagCanCreateHook;
use MediaWiki\ChangeTags\Hook\ChangeTagsListActiveHook;
use MediaWiki\ChangeTags\Hook\ListDefinedTagsHook;
use MediaWiki\Extension\OAuth\Frontend\OAuthLogFormatter;
use MediaWiki\Hook\SetupAfterCacheHook;
use MediaWiki\Status\Status;
use MediaWiki\Storage\NameTableAccessException;
use MediaWiki\Storage\NameTableStore;
use MediaWiki\User\User;
use MediaWiki\WikiMap\WikiMap;

/**
 * Class containing hooked functions for an OAuth environment
 */
class Hooks implements
	ApiRsdServiceApisHook,
	ChangeTagsListActiveHook,
	ChangeTagCanCreateHook,
	ListDefinedTagsHook,
	SetupAfterCacheHook
{

	/** @var NameTableStore */
	private $changeTagDefStore;

	/**
	 * @param NameTableStore $changeTagDefStore
	 */
	public function __construct( NameTableStore $changeTagDefStore ) {
		$this->changeTagDefStore = $changeTagDefStore;
	}

	/**
	 * Called right after configuration variables have been set.
	 */
	public static function onRegistration() {
		global $wgOAuth2PrivateKey, $wgOAuth2PublicKey;

		// Set $wgOAuth2PrivateKey and $wgOAuth2PublicKey for Wikimedia Jenkins, PHPUnit.
		if ( defined( 'MW_PHPUNIT_TEST' ) || defined( 'MW_QUIBBLE_CI' ) ) {
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
			$wgLogActionsHandlers['mwoauthconsumer/*'] = [
				'class' => OAuthLogFormatter::class,
				'services' => [
					'LinkRenderer',
					'TitleFactory',
					'UserEditTracker',
				],
			];
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
	 */
	public function onChangeTagCanCreate( $tag, $user, &$status ) {
		if ( Utils::isReservedTagName( $tag ) ) {
			$status->fatal( 'mwoauth-tag-reserved' );
		}
	}

	public static function onMergeAccountFromTo( User $oUser, User $nUser ) {
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

	public function onListDefinedTags( &$tags ) {
		return $this->getUsedConsumerTags( false, $tags );
	}

	public function onChangeTagsListActive( &$tags ) {
		return $this->getUsedConsumerTags( true, $tags );
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
	private function getUsedConsumerTags( $activeOnly, &$tags ) {
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
		$tagIds = [];
		foreach ( $allTags as $tag ) {
			try {
				$tagIds[] = $this->changeTagDefStore->getId( $tag );
			} catch ( NameTableAccessException $ex ) {
				continue;
			}
		}
		if ( $tagIds === [] ) {
			// Nothing to add, return
			return true;
		}
		$conditions = [ 'ct_tag_id' => $tagIds ];
		$field = 'ct_tag_id';

		if ( $allTags ) {
			$db = wfGetDB( DB_REPLICA );
			$res = $db->select(
				'change_tag',
				[ $field ],
				$conditions,
				__METHOD__,
				[ 'DISTINCT' ]
			);
			foreach ( $res as $row ) {
				$tags[] = $this->changeTagDefStore->getName( intval( $row->ct_tag_id ) );
			}
		}

		return true;
	}

	public function onSetupAfterCache() {
		global $wgMWOAuthCentralWiki, $wgMWOAuthSharedUserIDs;

		if ( $wgMWOAuthCentralWiki === false ) {
			// Treat each wiki as its own "central wiki" as there is no actual one
			$wgMWOAuthCentralWiki = WikiMap::getCurrentWikiId();
		} else {
			// There is actually a central wiki, requiring global user IDs via hook
			$wgMWOAuthSharedUserIDs = true;
		}
	}

	public function onApiRsdServiceApis( &$apis ) {
		$apis['MediaWiki']['settings']['OAuth'] = true;
	}
}
