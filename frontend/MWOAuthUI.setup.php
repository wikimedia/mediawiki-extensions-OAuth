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
		global $wgSpecialPages, $wgHooks, $wgResourceModules;

		$wgSpecialPages['MWOAuth'] = 'SpecialMWOAuth';

		$wgHooks['GetPreferences'][] = 'MWOAuthUIHooks::onGetPreferences';

		$wgResourceModules['ext.MWOAuth.BasicStyles'] = array(
			'styles'        => array( 'ext.MWOAuth.BasicStyles.css' ),
			'localBasePath' => dirname( __FILE__ ) . '/modules',
			'remoteExtPath' => 'OAuth/frontend/modules'
		);
		$wgResourceModules['ext.MWOAuth.AuthorizeForm'] = array(
			'styles'        => array('ext.MWOAuth.AuthorizeForm.css' ),
			'localBasePath' => dirname( __FILE__ ) . '/modules',
			'remoteExtPath' => 'OAuth/frontend/modules'
		);
		$wgResourceModules['ext.MWOAuth.AuthorizeDialog'] = array(
			'scripts'       => array( 'ext.MWOAuth.AuthorizeDialog.js' ),
			'dependencies'  => array( 'jquery.ui.dialog' ),
			'localBasePath' => dirname( __FILE__ ) . '/modules',
			'remoteExtPath' => 'OAuth/frontend/modules',
			'messages'      => array( 'mwoauth-desc' )
		);
	}

	/**
	 * @return void
	 */
	public static function conditionalSetup() {
		global $wgSpecialPages, $wgSpecialPageGroups;
		global $wgLogTypes, $wgLogNames, $wgLogHeaders, $wgLogActionsHandlers;

		if ( MWOAuthUtils::isCentralWiki() ) {
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
	}
}
