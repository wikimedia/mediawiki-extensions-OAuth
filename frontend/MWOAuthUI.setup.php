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
		global $wgSpecialPages, $wgSpecialPageGroups, $wgHooks, $wgResourceModules;

		$wgSpecialPages['OAuth'] = 'SpecialMWOAuth';
		$wgSpecialPages['OAuthManageMyGrants'] = 'SpecialMWOAuthManageMyGrants';
		$wgSpecialPageGroups['OAuthManageMyGrants'] = 'users';
		$wgSpecialPages['OAuthListConsumers'] = 'SpecialMWOAuthListConsumers';
		$wgSpecialPageGroups['OAuthListConsumers'] = 'users';

		$wgHooks['GetPreferences'][] = 'MWOAuthUIHooks::onGetPreferences';
		$wgHooks['MessagesPreLoad'][] = 'MWOAuthUIHooks::onMessagesPreLoad';

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
		$wgResourceModules['ext.MWOAuth.WikiSelect'] = array(
			'scripts'       => array( 'ext.MWOAuth.WikiSelect.js' ),
			'dependencies'  => array( 'jquery.ui.autocomplete' ),
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
			$wgSpecialPages['OAuthConsumerRegistration'] = 'SpecialMWOAuthConsumerRegistration';
			$wgSpecialPageGroups['OAuthConsumerRegistration'] = 'users';
			$wgSpecialPages['OAuthManageConsumers'] = 'SpecialMWOAuthManageConsumers';
			$wgSpecialPageGroups['OAuthManageConsumers'] = 'users';

			$wgLogTypes[] = 'mwoauthconsumer';
			$wgLogNames['mwoauthconsumer'] = 'mwoauthconsumer-consumer-logpage';
			$wgLogHeaders['mwoauthconsumer'] = 'mwoauthconsumer-consumer-logpagetext';
			$wgLogActionsHandlers['mwoauthconsumer/*'] = 'LogFormatter';
		}
	}
}
