<?php

namespace MediaWiki\Extensions\OAuth;

/**
 * Class containing basic setup functions.
 */
class MWOAuthSetup {
	/**
	 * Register source code paths.
	 * This function must NOT depend on any config vars.
	 *
	 * @param array $classes
	 * @param array $messagesFiles
	 * @param array $messagesDirs
	 * @return void
	 */
	public static function defineSourcePaths( array &$classes, array &$messagesFiles, array &$messagesDirs ) {
		$dir = __DIR__;

		# Basic directory layout
		$backendDir  = "$dir/backend";
		$schemaDir   = "$dir/backend/schema";
		$controlDir  = "$dir/control";
		$apiDir      = "$dir/api";
		$frontendDir = "$dir/frontend";
		$langDir     = "$dir/frontend/language";
		$specialsDir = "$dir/frontend/specialpages";
		$libDir      = "$dir/lib";

		# Main i18n file and special page alias file
		$messagesDirs['MWOAuth'] = "$dir/i18n";
		$messagesFiles['MWOAuthAliases'] = "$langDir/MWOAuth.alias.php";

		# Setup classes
		$classes['MediaWiki\Extensions\OAuth\MWOAuthAPISetup'] = "$apiDir/MWOAuthAPI.setup.php";
		$classes['MediaWiki\Extensions\OAuth\MWOAuthUISetup'] = "$frontendDir/MWOAuthUI.setup.php";
		$classes['MediaWiki\Extensions\OAuth\MWOAuthUIHooks'] = "$frontendDir/MWOAuthUI.hooks.php";
		$classes['MediaWiki\Extensions\OAuth\MWOAuthHooks'] = "$backendDir/MWOAuth.hooks.php";

		# Special pages and pagers
		$classes['MediaWiki\Extensions\OAuth\SpecialMWOAuth'] = "$specialsDir/SpecialMWOAuth.php";
		$classes['MediaWiki\Extensions\OAuth\SpecialMWOAuthConsumerRegistration'] =
			"$specialsDir/SpecialMWOAuthConsumerRegistration.php";
		$classes['MediaWiki\Extensions\OAuth\MWOAuthListMyConsumersPager'] =
			"$specialsDir/SpecialMWOAuthConsumerRegistration.php";
		$classes['MediaWiki\Extensions\OAuth\SpecialMWOAuthManageConsumers'] =
			"$specialsDir/SpecialMWOAuthManageConsumers.php";
		$classes['MediaWiki\Extensions\OAuth\MWOAuthManageConsumersPager'] =
			"$specialsDir/SpecialMWOAuthManageConsumers.php";
		$classes['MediaWiki\Extensions\OAuth\SpecialMWOAuthManageMyGrants'] =
			"$specialsDir/SpecialMWOAuthManageMyGrants.php";
		$classes['MediaWiki\Extensions\OAuth\MWOAuthManageMyGrantsPager'] =
			"$specialsDir/SpecialMWOAuthManageMyGrants.php";
		$classes['MediaWiki\Extensions\OAuth\SpecialMWOAuthListConsumers'] =
			"$specialsDir/SpecialMWOAuthListConsumers.php";
		$classes['MediaWiki\Extensions\OAuth\MWOAuthListConsumersPager'] =
			"$specialsDir/SpecialMWOAuthListConsumers.php";

		# Utility functions
		$classes['MediaWiki\Extensions\OAuth\MWOAuthUtils'] = "$backendDir/MWOAuthUtils.php";
		$classes['MediaWiki\Extensions\OAuth\MWOAuthException'] = "$backendDir/MWOAuthException.php";

		# Data access objects
		$classes['MediaWiki\Extensions\OAuth\MWOAuthDAO'] = "$backendDir/MWOAuthDAO.php";
		$classes['MediaWiki\Extensions\OAuth\MWOAuthToken'] = "$backendDir/MWOAuthToken.php";
		$classes['MediaWiki\Extensions\OAuth\MWOAuthConsumer'] = "$backendDir/MWOAuthConsumer.php";
		$classes['MediaWiki\Extensions\OAuth\MWOAuthConsumerAcceptance'] = "$backendDir/MWOAuthConsumerAcceptance.php";
		$classes['MediaWiki\Extensions\OAuth\MWOAuthRequest'] = "$backendDir/MWOAuthRequest.php";

		# Control logic
		$classes['MediaWiki\Extensions\OAuth\MWOAuthDAOAccessControl'] = "$controlDir/MWOAuthDAOAccessControl.php";
		$classes['MediaWiki\Extensions\OAuth\MWOAuthSubmitControl'] = "$controlDir/MWOAuthSubmitControl.php";
		$classes['MediaWiki\Extensions\OAuth\MWOAuthConsumerSubmitControl'] = "$controlDir/MWOAuthConsumerSubmitControl.php";
		$classes['MediaWiki\Extensions\OAuth\MWOAuthConsumerAcceptanceSubmitControl'] =
			"$controlDir/MWOAuthConsumerAcceptanceSubmitControl.php";
		$classes['MediaWiki\Extensions\OAuth\MWOAuthServer'] = "$backendDir/MWOAuthServer.php"; // "MWOAuth1Protocol"?
		$classes['MediaWiki\Extensions\OAuth\MWOAuthSignatureMethod_RSA_SHA1'] = "$backendDir/MWOAuthSignatureMethod.php";

		# Library
		$classes['MediaWiki\Extensions\OAuth\OAuthException'] = "$libDir/OAuth.php";
		$classes['MediaWiki\Extensions\OAuth\OAuthConsumer'] = "$libDir/OAuth.php";
		$classes['MediaWiki\Extensions\OAuth\OAuthToken'] = "$libDir/OAuth.php";
		$classes['MediaWiki\Extensions\OAuth\OAuthSignatureMethod'] = "$libDir/OAuth.php";
		$classes['MediaWiki\Extensions\OAuth\OAuthSignatureMethod_HMAC_SHA1'] = "$libDir/OAuth.php";
		$classes['MediaWiki\Extensions\OAuth\OAuthSignatureMethod_PLAINTEXT'] = "$libDir/OAuth.php";
		$classes['MediaWiki\Extensions\OAuth\OAuthSignatureMethod_RSA_SHA1'] = "$libDir/OAuth.php";
		$classes['MediaWiki\Extensions\OAuth\OAuthRequest'] = "$libDir/OAuth.php";
		$classes['MediaWiki\Extensions\OAuth\OAuthServer'] = "$libDir/OAuth.php";
		$classes['MediaWiki\Extensions\OAuth\OAuthDataStore'] = "$libDir/OAuth.php";
		$classes['MediaWiki\Extensions\OAuth\OAuthUtil'] = "$libDir/OAuth.php";

		# Storage
		$classes['MediaWiki\Extensions\OAuth\MWOAuthDataStore'] = "$backendDir/MWOAuthDataStore.php";

		# Schema changes
		$classes['MediaWiki\Extensions\OAuth\MWOAuthUpdaterHooks'] = "$schemaDir/MWOAuthUpdater.hooks.php";

		# Session provider
		$classes['MediaWiki\Extensions\OAuth\MWOAuthSessionProvider'] = "$apiDir/MWOAuthSessionProvider.php";
	}

	/**
	 * This function must NOT depend on any config vars
	 *
	 * @return void
	 */
	public static function unconditionalSetup() {
		global $wgHooks;

		$wgHooks['ChangeTagCanCreate'][] = 'MediaWiki\Extensions\OAuth\MWOAuthHooks::onChangeTagCanCreate';
		$wgHooks['ListDefinedTags'][] = array( 'MediaWiki\Extensions\OAuth\MWOAuthHooks::getUsedConsumerTags', false );
		$wgHooks['ChangeTagsListActive'][] = array( 'MediaWiki\Extensions\OAuth\MWOAuthHooks::getUsedConsumerTags', true );
		$wgHooks['MergeAccountFromTo'][] = 'MediaWiki\Extensions\OAuth\MWOAuthHooks::onMergeAccountFromTo';
		$wgHooks['CentralAuthGlobalUserMerged'][] = 'MediaWiki\Extensions\OAuth\MWOAuthHooks::onCentralAuthGlobalUserMerged';
		$wgHooks['LoadExtensionSchemaUpdates'][] = 'MediaWiki\Extensions\OAuth\MWOAuthUpdaterHooks::addSchemaUpdates';
		$wgHooks['UnitTestsList'][] = function( array &$files ) {
			$directoryIterator = new \RecursiveDirectoryIterator( __DIR__ . '/tests/' );
			foreach ( new \RecursiveIteratorIterator( $directoryIterator ) as $fileInfo ) {
				if ( substr( $fileInfo->getFilename(), -8 ) === 'Test.php' ) {
					$files[] = $fileInfo->getPathname();
				}
			}
			return true;
		};
	}
}
