<?php

namespace MediaWiki\Extension\OAuth\Backend;

use DatabaseUpdater;

/**
 * Class containing updater functions for an OAuth environment
 */
class UpdaterHooks {
	/**
	 * @param DatabaseUpdater $updater
	 * @return bool
	 */
	public static function addSchemaUpdates( DatabaseUpdater $updater ) {
		if ( !Utils::isCentralWiki() ) {
			// no tables to add
			return true;
		}

		$dbType = $updater->getDB()->getType();

		if ( $dbType == 'mysql' || $dbType == 'sqlite' ) {

			$updater->addExtensionTable(
				'oauth_registered_consumer',
				self::getPath( 'OAuth.sql', $dbType )
			);

			$updater->addExtensionField(
				'oauth_registered_consumer',
				'oarc_callback_is_prefix',
				self::getPath( 'callback_is_prefix.sql', $dbType )
			);

			$updater->addExtensionField(
				'oauth_registered_consumer',
				'oarc_developer_agreement',
				self::getPath( 'developer_agreement.sql', $dbType )
			);

			$updater->addExtensionField(
				'oauth_registered_consumer',
				'oarc_owner_only',
				self::getPath( 'owner_only.sql', $dbType )
			);

			$updater->addExtensionField(
				'oauth_registered_consumer',
				'oarc_oauth_version',
				self::getPath( 'oauth_version_registered.sql', $dbType )
			);

			$updater->addExtensionField(
				'oauth_registered_consumer',
				'oarc_oauth2_is_confidential',
				self::getPath( 'oauth2_is_confidential.sql', $dbType )
			);

			$updater->addExtensionField(
				'oauth_registered_consumer',
				'oarc_oauth2_allowed_grants',
				self::getPath( 'oauth2_allowed_grants.sql', $dbType )
			);

			$updater->addExtensionField(
				'oauth_accepted_consumer',
				'oaac_oauth_version',
				self::getPath( 'oauth_version_accepted.sql', $dbType )
			);

			$updater->addExtensionTable(
				'oauth2_access_tokens',
				self::getPath( 'oauth2_access_tokens.sql', $dbType )
			);

			$updater->addExtensionIndex(
				'oauth2_access_tokens',
				'oaat_acceptance_id',
				self::getPath( 'index_on_oaat_acceptance_id.sql', $dbType )
			);

		}
		return true;
	}

	/**
	 * @param string $filename Name of the patch file (without path).
	 *    The file should be in the schema/<dbtype>/ directory
	 *    or the schema/ directory.
	 * @param string $dbType 'mysql' or 'sqlite'
	 * @return string
	 */
	protected static function getPath( $filename, $dbType ) {
		$base = dirname( dirname( __DIR__ ) ) . '/schema';
		if ( file_exists( "$base/$dbType/$filename" ) ) {
			return "$base/$dbType/$filename";
		}
		return "$base/$filename";
	}
}
