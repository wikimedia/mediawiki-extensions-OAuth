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
	 * @return void
	 */
	public static function defineSourcePaths( array &$classes, array &$messagesFiles ) {
		$dir = __DIR__;

		# Basic directory layout
		$backendDir  = "$dir/backend";
		$schemaDir   = "$dir/backend/schema";
		$controlDir  = "$dir/control";
		$apiDir      = "$dir/api";
		$frontendDir = "$dir/frontend";
		$langDir     = "$dir/frontend/language/";
		$spActionDir = "$dir/frontend/specialpages/actions";
		$libDir      = "$dir/lib";

		# Main i18n file and special page alias file
		$messagesFiles['MWOAuth'] = "$langDir/MWOAuth.i18n.php";
		$messagesFiles['MWOAuthAliases'] = "$langDir/MWOAuth.alias.php";

		$classes['MWOAuthAPISetup'] = "$apiDir/MWOAuthAPI.setup.php";
		$classes['MWOAuthUISetup'] = "$frontendDir/MWOAuthUI.setup.php";

		# API for "initiate"?
		# API for "token"?

		$classes['SpecialOAuth'] = "$frontendDir/specials/SpecialOAuth.php";
		$classes['MWOAuthConsumerRegistration'] = "$spActionDir/MWOAuthConsumerRegistration.php";
		$classes['MWOAuthManageConsumers'] = "$spActionDir/MWOAuthManageConsumers.php";
		$classes['MWOAuthManageMyGrants'] = "$spActionDir/MWOAuthManageMyGrants.php";
		# Special:MWOAuth/authorize
		# Special:MWOAuth/initiate?
		# Special:MWOAuth/token?

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
		$classes['MWOAuthServer'] = "$backendDir/MWOAuthServer.php"; // "MWOAuth1Protocol" might be a better name
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
	}
}
