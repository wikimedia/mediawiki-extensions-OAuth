<?php
/**
 * Class containing basic setup functions.
 */
class OAuthSetup {
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
		$backendDir       = "$dir/backend";
		$schemaDir        = "$dir/backend/schema";
		$businessDir      = "$dir/business";
		$apiDir           = "$dir/api";
		$frontendDir      = "$dir/frontend";
		$langDir          = "$dir/frontend/language/";
		$spActionDir      = "$dir/frontend/specialpages/actions";

		# Main i18n file and special page alias file
		$messagesFiles['OAuth'] = "$langDir/OAuth.i18n.php";
		$messagesFiles['OAuthAliases'] = "$langDir/OAuth.alias.php";

		$classes['OAuthAPISetup'] = "$apiDir/OAuthAPI.setup.php";
		$classes['OAuthUISetup'] = "$frontendDir/OAuthUI.setup.php";

		# API for "initiate"?
		# API for "token"?

		# Special:OAuthClientRegistration
		# Special:OAuthClientRegistrationApproval
		# Special:OAuth/authorize
		# Special:OAuth/initiate?
		# Special:OAuth/token?

		# Utility functions
		$classes['OAuth'] = "$backendDir/OAuthUtils.php";

		# Data access objects

		# Business logic

		# Schema changes
		$classes['OAuthUpdaterHooks'] = "$schemaDir/OAuthUpdater.hooks.php";
	}
}
