<?php

namespace MediaWiki\Extensions\OAuth;

/**
 * Class containing hooked functions for an OAuth environment
 */
class MWOAuthHooks {
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
