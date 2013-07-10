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

		# Main i18n file and special page alias file
		$messagesFiles['MWOAuth'] = "$langDir/MWOAuth.i18n.php";
		$messagesFiles['MWOAuthAliases'] = "$langDir/MWOAuth.alias.php";

		$classes['MWOAuthAPISetup'] = "$apiDir/MWOAuthAPI.setup.php";
		$classes['MWOAuthUISetup'] = "$frontendDir/MWOAuthUI.setup.php";

		# API for "initiate"?
		# API for "token"?

		$classes['MWOAuthConsumerRegistration'] = "$spActionDir/MWOAuthConsumerRegistration.php";
		$classes['MWOAuthManageConsumers'] = "$spActionDir/MWOAuthManageConsumers.php";
		$classes['MWOAuthManageMyGrants'] = "$spActionDir/MWOAuthManageMyGrants.php";
		# Special:MWOAuth/authorize
		# Special:MWOAuth/initiate?
		# Special:MWOAuth/token?

		# Utility functions
		$classes['MWOAuthUtils'] = "$backendDir/MWOAuthUtils.php";

		# Data access objects
		$classes['MWOAuthDAO'] = "$backendDir/MWOAuthDAO.php";
		$classes['MWOAuthConsumer'] = "$backendDir/MWOAuthConsumer.php";
		$classes['MWOAuthConsumerAcceptance'] = "$backendDir/MWOAuthConsumerAcceptance.php";

		# Control logic
		$classes['MWOAuthDAOAccessControl'] = "$controlDir/MWOAuthDAOAccessControl.php";
		$classes['MWOAuthSubmitControl'] = "$controlDir/MWOAuthSubmitControl.php";
		$classes['MWOAuthConsumerSubmitControl'] = "$controlDir/MWOAuthConsumerSubmitControl.php";
		$classes['MWOAuthConsumerAcceptanceSubmitControl'] =
			"$controlDir/MWOAuthConsumerAcceptanceSubmitControl.php";

		# Schema changes
		$classes['MWOAuthUpdaterHooks'] = "$schemaDir/MWOAuthUpdater.hooks.php";
	}
}
