<?php
/**
 * Class containing updater functions for an OAuth environment
 */
class MWOAuthUpdaterHooks {
	/**
	 * @param DatabaseUpdater $updater
	 * @return bool
	 */
	public static function addSchemaUpdates( DatabaseUpdater $updater ) {
		if ( !MWOAuthUtils::isCentralWiki() ) {
			return true; // no tables to add
		}
		$base = dirname( __FILE__ );

		$dbType = $updater->getDB()->getType();

		if ( $dbType == 'mysql' or $dbType == 'sqlite' ) {
			$base = "$base/mysql";

			$updater->addExtensionTable( 'oauth_registered_consumer', "$base/OAuth.sql" );
		} elseif ( $updater->getDB()->getType() == 'postgres' ) {
			//$base = "$base/postgres";

			// @TODO
		}
		return true;
	}
}
