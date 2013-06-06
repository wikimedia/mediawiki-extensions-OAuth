<?php
/**
 * Class containing updater functions for an OAuth environment
 */
class OAuthUpdaterHooks {

	/**
	 * @param DatabaseUpdater $updater
	 * @return bool
	 */
	public static function addSchemaUpdates( DatabaseUpdater $updater ) {
		$base = dirname( __FILE__ );
		if ( $updater->getDB()->getType() == 'mysql' ) {
			$base = "$base/mysql";

			// $updater->addExtensionTable( 'oauth_registration', "$base/OAuth.sql" );
		} elseif ( $updater->getDB()->getType() == 'postgres' ) {
			$base = "$base/postgres";

			// @TODO
		}
		return true;
	}
}
