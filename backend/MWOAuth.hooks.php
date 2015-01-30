<?php

namespace MediaWiki\Extensions\OAuth;

/**
 * Class containing hooked functions for an OAuth environment
 */
class MWOAuthHooks {
	/**
	 * Reserve all change tags beginning with 'OAuth CID:' (case-insensitive) so
	 * that the user may not create them
	 *
	 * @todo Also consume ListDefinedTags and ChangeTagsListActive hooks in a
	 * sensible way, so OAuth tags don't appear undefined and inactive on Special:Tags
	 *
	 * @param string $tag
	 * @param \User $user
	 * @param \Status $status
	 * @return bool
	 */
	public static function onChangeTagCanCreate( $tag, \User $user, \Status &$status ) {
		if ( strpos( strtolower( $tag ), 'oauth cid:' ) === 0 ) {
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

	protected function doUserIdMerge( $oldid, $newid ) {
		$dbw = MWOAuthUtils::getCentralDB( DB_MASTER );
		// Merge any consumers register to this user
		$dbw->update( 'oauth_registered_consumer',
			array( 'oarc_user_id' => $newid ),
			array( 'oarc_user_id' => $oldid ),
			__METHOD__
		);
		// Delete any acceptance tokens by the old user ID
		$dbw->delete( 'oauth_accepted_consumer',
			array( 'oaac_user_id' => $oldid ),
			__METHOD__
		);
	}
}
