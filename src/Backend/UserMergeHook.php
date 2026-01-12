<?php

namespace MediaWiki\Extension\OAuth\Backend;

use MediaWiki\Extension\UserMerge\Hooks\MergeAccountFromToHook;
use MediaWiki\User\User;

class UserMergeHook implements MergeAccountFromToHook {

	/**
	 * @param int $oldid
	 * @param int $newid
	 */
	private static function doUserIdMerge( $oldid, $newid ) {
		$dbw = Utils::getOAuthDB( DB_PRIMARY );
		// Merge any consumers register to this user
		$dbw->newUpdateQueryBuilder()
			->update( 'oauth_registered_consumer' )
			->set( [ 'oarc_user_id' => $newid ] )
			->where( [ 'oarc_user_id' => $oldid ] )
			->caller( __METHOD__ )
			->execute();
		// Delete any acceptance tokens by the old user ID
		$dbw->newDeleteQueryBuilder()
			->deleteFrom( 'oauth_accepted_consumer' )
			->where( [ 'oaac_user_id' => $oldid ] )
			->caller( __METHOD__ )
			->execute();
	}

	/** @inheritDoc */
	public function onMergeAccountFromTo( User &$oUser, User &$nUser ) {
		$oldid = Utils::getCentralIdFromLocalUser( $oUser );
		$newid = Utils::getCentralIdFromLocalUser( $nUser );
		if ( $oldid && $newid ) {
			self::doUserIdMerge( $oldid, $newid );
		}

		return true;
	}
}
