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
		$base = dirname( dirname( __DIR__ ) ) . '/schema';

		$dbType = $updater->getDB()->getType();

		if ( $dbType == 'mysql' || $dbType == 'sqlite' ) {
			$base = "$base/$dbType";

			$updater->addExtensionTable( 'oauth_registered_consumer', "$base/OAuth.sql" );

			$updater->addExtensionUpdate( [
				'addField',
				'oauth_registered_consumer',
				'oarc_callback_is_prefix',
				"$base/callback_is_prefix.sql",
				true
			] );

			$updater->addExtensionUpdate( [
				'addField',
				'oauth_registered_consumer',
				'oarc_developer_agreement',
				"$base/developer_agreement.sql",
				true
			] );

			$updater->addExtensionUpdate( [
				'addField',
				'oauth_registered_consumer',
				'oarc_owner_only',
				"$base/owner_only.sql",
				true
			] );

			$updater->addExtensionUpdate( [
				'addField',
				'oauth_registered_consumer',
				'oarc_oauth_version',
				"$base/oauth_version_registered.sql",
				true
			] );

			$updater->addExtensionUpdate( [
				'addField',
				'oauth_registered_consumer',
				'oarc_oauth2_is_confidential',
				"$base/oauth2_is_confidential.sql",
				true
			] );

			$updater->addExtensionUpdate( [
				'addField',
				'oauth_registered_consumer',
				'oarc_oauth2_allowed_grants',
				"$base/oauth2_allowed_grants.sql",
				true
			] );

			$updater->addExtensionUpdate( [
				'addField',
				'oauth_accepted_consumer',
				'oaac_oauth_version',
				"$base/oauth_version_accepted.sql",
				true
			] );

			$updater->addExtensionTable(
				'oauth2_access_tokens',
				"$base/oauth2_access_tokens.sql"
			);

			$updater->addExtensionIndex(
				'oauth2_access_tokens',
				'oaat_acceptance_id',
				"$base/index_on_oaat_acceptance_id.sql"
			);

		}
		return true;
	}
}
