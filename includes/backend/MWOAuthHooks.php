<?php

namespace MediaWiki\Extensions\OAuth;

use MediaWiki\MediaWikiServices;
use MediaWiki\Storage\NameTableAccessException;

/**
 * Class containing hooked functions for an OAuth environment
 */
class MWOAuthHooks {

	public static function onExtensionFunctions() {
		\MediaWiki\Extensions\OAuth\MWOAuthUISetup::conditionalSetup();
	}

	/**
	 * Reserve change tags that look like an OAuth change tag.
	 *
	 * @param string $tag
	 * @param \User|null $user
	 * @param \Status &$status
	 * @return bool
	 */
	public static function onChangeTagCanCreate( $tag, \User $user = null, \Status &$status ) {
		if ( MWOAuthUtils::isReservedTagName( $tag ) ) {
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

	public static function onCentralAuthGlobalUserMerged( $oldname, $newname, $oldid, $newid ) {
		global $wgMWOAuthSharedUserIDs;

		if ( $wgMWOAuthSharedUserIDs && $oldid && $newid ) {
			self::doUserIdMerge( $oldid, $newid );
		}

		return true;
	}

	protected static function doUserIdMerge( $oldid, $newid ) {
		$dbw = MWOAuthUtils::getCentralDB( DB_MASTER );
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
		$db = MWOAuthUtils::getCentralDB( DB_REPLICA );
		$conds = [
			$db->makeList( [
				'oarc_wiki = ' . $db->addQuotes( '*' ),
				'oarc_wiki = ' . $db->addQuotes( wfWikiId() ),
			], LIST_OR ),
			'oarc_deleted' => 0,
		];
		if ( $activeOnly ) {
			$conds[] = $db->makeList( [
				'oarc_stage = ' . MWOAuthConsumer::STAGE_APPROVED,
				// Proposed consumers are active for the owner, so count them too
				'oarc_stage = ' . MWOAuthConsumer::STAGE_PROPOSED,
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
			$allTags[] = MWOAuthUtils::getTagName( $row->oarc_id );
		}

		// Step 2: Return only those that are in use.
		$changeTagDefStore = MediaWikiServices::getInstance()->getChangeTagDefStore();
		$tagIds = [];
		foreach ( $allTags as $tag ) {
			try {
				$tagIds[] = $changeTagDefStore->getId( $tag );
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
				$tags[] = $changeTagDefStore->getName( intval( $row->ct_tag_id ) );
			}
		}

		return true;
	}

	public static function onSetupAfterCache() {
		global $wgMWOAuthCentralWiki, $wgMWOAuthSharedUserIDs;

		if ( $wgMWOAuthCentralWiki === false ) {
			// Treat each wiki as its own "central wiki" as there is no actual one
			$wgMWOAuthCentralWiki = wfWikiId(); // default
		} else {
			// There is actually a central wiki, requiring global user IDs via hook
			$wgMWOAuthSharedUserIDs = true;
		}
	}

	public static function onApiRsdServiceApis( array &$apis ) {
		$apis['MediaWiki']['settings']['OAuth'] = true;
	}
}
