<?php

namespace MediaWiki\Extensions\OAuth;

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

		$wgSpecialPages['OAuth'] = 'MediaWiki\Extensions\OAuth\SpecialMWOAuth';
		$wgSpecialPages['OAuthManageMyGrants'] = 'MediaWiki\Extensions\OAuth\SpecialMWOAuthManageMyGrants';
		$wgSpecialPageGroups['OAuthManageMyGrants'] = 'users';
		$wgSpecialPages['OAuthListConsumers'] = 'MediaWiki\Extensions\OAuth\SpecialMWOAuthListConsumers';
		$wgSpecialPageGroups['OAuthListConsumers'] = 'users';

		$wgHooks['GetPreferences'][] = 'MediaWiki\Extensions\OAuth\MWOAuthUIHooks::onGetPreferences';
		$wgHooks['MessagesPreLoad'][] = 'MediaWiki\Extensions\OAuth\MWOAuthUIHooks::onMessagesPreLoad';

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
			$wgSpecialPages['OAuthConsumerRegistration'] = 'MediaWiki\Extensions\OAuth\SpecialMWOAuthConsumerRegistration';
			$wgSpecialPageGroups['OAuthConsumerRegistration'] = 'users';
			$wgSpecialPages['OAuthManageConsumers'] = 'MediaWiki\Extensions\OAuth\SpecialMWOAuthManageConsumers';
			$wgSpecialPageGroups['OAuthManageConsumers'] = 'users';

			$wgLogTypes[] = 'mwoauthconsumer';
			$wgLogNames['mwoauthconsumer'] = 'mwoauthconsumer-consumer-logpage';
			$wgLogHeaders['mwoauthconsumer'] = 'mwoauthconsumer-consumer-logpagetext';
			$wgLogActionsHandlers['mwoauthconsumer/*'] = 'LogFormatter';
		}
	}
}
