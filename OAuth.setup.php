<?php
/**
 * Class containing basic setup functions.
 */
class MWOAuthSetup {
	/**
	 * Register source code paths.
	 * This function must NOT depend on any config vars.
	 *
	 * @param $classes Array $classes
	 * @param $messagesFiles Array $messagesFiles
	 * @param $redactedFunctionArgs Array $redactedFunctionArgs
	 * @return void
	 */
	public static function defineSourcePaths(
		array &$classes, array &$messagesFiles, array &$redactedFunctionArgs
	) {
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
		$messagesFiles['MWOAuth'] = "$langDir/MWOAuth.i18n.php";
		$messagesFiles['MWOAuthAliases'] = "$langDir/MWOAuth.alias.php";

		# Setup classes
		$classes['MWOAuthAPISetup'] = "$apiDir/MWOAuthAPI.setup.php";
		$classes['MWOAuthUISetup'] = "$frontendDir/MWOAuthUI.setup.php";
		$classes['MWOAuthUIHooks'] = "$frontendDir/MWOAuthUI.hooks.php";

		# Special pages
		$classes['SpecialMWOAuth'] = "$specialsDir/SpecialMWOAuth.php";
		$classes['SpecialMWOAuthConsumerRegistration'] =
			"$specialsDir/SpecialMWOAuthConsumerRegistration.php";
		$classes['SpecialMWOAuthManageConsumers'] =
			"$specialsDir/SpecialMWOAuthManageConsumers.php";
		$classes['SpecialMWOAuthManageMyGrants'] =
			"$specialsDir/SpecialMWOAuthManageMyGrants.php";

		# Utility functions
		$classes['MWOAuthUtils'] = "$backendDir/MWOAuthUtils.php";
		$classes['MWOAuthException'] = "$backendDir/MWOAuthException.php";

		# Data access objects
		$classes['MWOAuthDAO'] = "$backendDir/MWOAuthDAO.php";
		$classes['MWOAuthToken'] = "$backendDir/MWOAuthToken.php";
		$classes['MWOAuthConsumer'] = "$backendDir/MWOAuthConsumer.php";
		$classes['MWOAuthConsumerAcceptance'] = "$backendDir/MWOAuthConsumerAcceptance.php";
		$classes['MWOAuthRequest'] = "$backendDir/MWOAuthRequest.php";

		# Control logic
		$classes['MWOAuthDAOAccessControl'] = "$controlDir/MWOAuthDAOAccessControl.php";
		$classes['MWOAuthSubmitControl'] = "$controlDir/MWOAuthSubmitControl.php";
		$classes['MWOAuthConsumerSubmitControl'] = "$controlDir/MWOAuthConsumerSubmitControl.php";
		$classes['MWOAuthConsumerAcceptanceSubmitControl'] =
			"$controlDir/MWOAuthConsumerAcceptanceSubmitControl.php";
		$classes['MWOAuthServer'] = "$backendDir/MWOAuthServer.php"; // "MWOAuth1Protocol"?
		$classes['MWOAuthSignatureMethod_RSA_SHA1'] = "$backendDir/MWOAuthSignatureMethod.php";

		# Library
		$classes['OAuthException'] = "$libDir/OAuth.php";
		$classes['OAuthConsumer'] = "$libDir/OAuth.php";
		$classes['OAuthToken'] = "$libDir/OAuth.php";
		$classes['OAuthSignatureMethod'] = "$libDir/OAuth.php";
		$classes['OAuthSignatureMethod_HMAC_SHA1'] = "$libDir/OAuth.php";
		$classes['OAuthSignatureMethod_RSA_SHA1'] = "$libDir/OAuth.php";
		$classes['OAuthRequest'] = "$libDir/OAuth.php";
		$classes['OAuthServer'] = "$libDir/OAuth.php";
		$classes['OAuthDataStore'] = "$libDir/OAuth.php";
		$classes['OAuthUtil'] = "$libDir/OAuth.php";

		# Storage
		$classes['MWOAuthDataStore'] = "$backendDir/MWOAuthDataStore.php";

		# Schema changes
		$classes['MWOAuthUpdaterHooks'] = "$schemaDir/MWOAuthUpdater.hooks.php";

		# Indicate functions with arguments that need redaction
		$redactedFunctionArgs += array(
			'MWOAuthDAO::setField' => 1, # $value could be a secret
			'MWOAuthUtils::hmacDBSecret' => 0,
			'OAuthConsumer::__construct' => 1,
			'OAuthToken::__construct' => 1,
			'OAuthRequest::set_parameter' => 1, # $value could be the nonce
			'OAuthServer::check_nonce' => 2,
			'OAuthDataStore::lookup_nonce' => 2,
			'OAuthUtil::urlencode_rfc3986' => 0, # Value being encoded may contain secrets
		);
	}

	/**
	 * This function must NOT depend on any config vars
	 *
	 * @return void
	 */
	public static function unconditionalSetup() {
		global $wgHooks;

		$wgHooks['LoadExtensionSchemaUpdates'][] = 'MWOAuthUpdaterHooks::addSchemaUpdates';
		$wgHooks['UnitTestsList'][] = function( array &$files ) {
			$directoryIterator = new RecursiveDirectoryIterator( __DIR__ . '/tests/' );
			foreach ( new RecursiveIteratorIterator( $directoryIterator ) as $fileInfo ) {
				if ( substr( $fileInfo->getFilename(), -8 ) === 'Test.php' ) {
					$files[] = $fileInfo->getPathname();
				}
			}
			return true;
		};
	}
}
