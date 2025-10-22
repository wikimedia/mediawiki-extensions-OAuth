<?php

namespace MediaWiki\Extension\OAuth\Backend;

use MediaWiki\Installer\DatabaseUpdater;
use MediaWiki\Installer\Hook\LoadExtensionSchemaUpdatesHook;

/**
 * Class containing updater functions for an OAuth environment
 */
class UpdaterHooks implements LoadExtensionSchemaUpdatesHook {
	/**
	 * @param DatabaseUpdater $updater
	 * @return bool
	 */
	public function onLoadExtensionSchemaUpdates( $updater ) {
		$dbType = $updater->getDB()->getType();

		$updater->addExtensionUpdateOnVirtualDomain( [
			'virtual-oauth',
			'addTable',
			'oauth_registered_consumer',
			$this->getPath( 'tables-generated.sql', $dbType ),
			true
		] );

		// PostgreSQL support was added in 1.39 with migration to abstract schema (T268565),
		// so these schema patches are not needed for it
		if ( $dbType == 'mysql' || $dbType == 'sqlite' ) {
			// 1.35
			$updater->addExtensionUpdateOnVirtualDomain( [
				'virtual-oauth',
				'addField',
				'oauth_registered_consumer',
				'oarc_oauth_version',
				$this->getPath( 'oauth_version_registered.sql', $dbType ),
				true
			] );

			$updater->addExtensionUpdateOnVirtualDomain( [
				'virtual-oauth',
				'addField',
				'oauth_registered_consumer',
				'oarc_oauth2_is_confidential',
				$this->getPath( 'oauth2_is_confidential.sql', $dbType ),
				true
			] );

			$updater->addExtensionUpdateOnVirtualDomain( [
				'virtual-oauth',
				'addField',
				'oauth_registered_consumer',
				'oarc_oauth2_allowed_grants',
				$this->getPath( 'oauth2_allowed_grants.sql', $dbType ),
				true
			] );

			$updater->addExtensionUpdateOnVirtualDomain( [
				'virtual-oauth',
				'addField',
				'oauth_accepted_consumer',
				'oaac_oauth_version',
				$this->getPath( 'oauth_version_accepted.sql', $dbType ),
				true
			] );

			$updater->addExtensionUpdateOnVirtualDomain( [
				'virtual-oauth',
				'addTable',
				'oauth2_access_tokens',
				$this->getPath( 'oauth2_access_tokens.sql', $dbType ),
				true
			] );

			$updater->addExtensionUpdateOnVirtualDomain( [
				'virtual-oauth',
				'addIndex',
				'oauth2_access_tokens',
				'oaat_acceptance_id',
				$this->getPath( 'index_on_oaat_acceptance_id.sql', $dbType ),
				true
			] );

			// 1.39
			$updater->addExtensionUpdateOnVirtualDomain( [
				'virtual-oauth',
				'modifyField',
				'oauth_accepted_consumer',
				'oaac_accepted',
				$this->getPath( 'patch-oauth_accepted_consumer-timestamp.sql', $dbType ),
				true
			] );

			$updater->addExtensionUpdateOnVirtualDomain( [
				'virtual-oauth',
				'modifyField',
				'oauth_registered_consumer',
				'oarc_email_authenticated',
				$this->getPath( 'patch-oauth_registered_consumer-timestamp.sql', $dbType ),
				true
			] );
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
	private function getPath( $filename, $dbType ) {
		$base = dirname( dirname( __DIR__ ) ) . '/schema';
		if ( file_exists( "$base/$dbType/$filename" ) ) {
			return "$base/$dbType/$filename";
		}
		return "$base/$filename";
	}
}
