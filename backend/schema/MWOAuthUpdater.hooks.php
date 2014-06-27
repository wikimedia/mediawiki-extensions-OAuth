<?php

namespace MediaWiki\Extensions\OAuth;

/**
 * Class containing updater functions for an OAuth environment
 */
class MWOAuthUpdaterHooks {
	/**
	 * @param \DatabaseUpdater $updater
	 * @return bool
	 */
	public static function addSchemaUpdates( \DatabaseUpdater $updater ) {
		if ( !MWOAuthUtils::isCentralWiki() ) {
			return true; // no tables to add
		}
		$base = dirname( __FILE__ );

		$dbType = $updater->getDB()->getType();

		if ( $dbType == 'mysql' or $dbType == 'sqlite' ) {
			$base = "$base/$dbType";

			$updater->addExtensionTable( 'oauth_registered_consumer', "$base/OAuth.sql" );

			$updater->addExtensionUpdate( array(
				'addField',
				'oauth_registered_consumer',
				'oarc_callback_is_prefix',
				"$base/callback_is_prefix.sql",
				true
			) );

			$updater->addExtensionUpdate( array(
				'addField',
				'oauth_registered_consumer',
				'oarc_developer_agreement',
				"$base/developer_agreement.sql",
				true
			) );

		} elseif ( $dbType == 'postgres' ) {
			//$base = "$base/postgres";

			// @TODO
		}
		return true;
	}
}
