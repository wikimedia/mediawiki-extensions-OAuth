<?php
/**
 * Class containing hooked functions for an OAuth environment
 */
class MWOAuthUISetup {
	/**
	 * This function must NOT depend on any config vars
	 *
	 * @return void
	 */
	public static function unconditionalSetup() {
		global $wgHooks;

		$wgHooks['GetPreferences'][] = 'MWOAuthUIHooks::onGetPreferences';
	}

	/**
	 * @return void
	 */
	public static function conditionalSetup() {
		global $wgSpecialPages, $wgSpecialPageGroups;
		global $wgLogTypes, $wgLogNames, $wgLogHeaders, $wgLogActionsHandlers;
		global $wgResourceModules;

		if ( MWOAuthUtils::isCentralWiki() ) {
			$wgSpecialPages['MWOAuth'] = 'SpecialMWOAuth'; // @TODO: allow everywhere
			$wgSpecialPages['MWOAuthConsumerRegistration'] = 'SpecialMWOAuthConsumerRegistration';
			$wgSpecialPageGroups['MWOAuthConsumerRegistration'] = 'users';
			$wgSpecialPages['MWOAuthManageConsumers'] = 'SpecialMWOAuthManageConsumers';
			$wgSpecialPageGroups['MWOAuthManageConsumers'] = 'users';
			$wgSpecialPages['MWOAuthManageMyGrants'] = 'SpecialMWOAuthManageMyGrants';

			$wgLogTypes[] = 'mwoauthconsumer';
			$wgLogNames['mwoauthconsumer'] = 'mwoauthconsumer-consumer-logpage';
			$wgLogHeaders['mwoauthconsumer'] = 'mwoauthconsumer-consumer-logpagetext';
			$wgLogActionsHandlers['mwoauthconsumer/*'] = 'LogFormatter';
		}

		$wgResourceModules['ext.MWOAuth'] = array(
			'styles'        => 'ext.MWOAuth.css',
			'localBasePath' => dirname( __FILE__ ) . '/modules',
			'remoteExtPath' => 'OAuth/frontend/modules',
		);
	}
}
