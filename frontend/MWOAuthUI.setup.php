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
		global $wgSpecialPages, $wgHooks, $wgResourceModules;

		$wgSpecialPages['OAuth'] = 'MediaWiki\Extensions\OAuth\SpecialMWOAuth';
		$wgSpecialPages['OAuthManageMyGrants'] = 'MediaWiki\Extensions\OAuth\SpecialMWOAuthManageMyGrants';
		$wgSpecialPages['OAuthListConsumers'] = 'MediaWiki\Extensions\OAuth\SpecialMWOAuthListConsumers';

		$wgHooks['GetPreferences'][] = 'MediaWiki\Extensions\OAuth\MWOAuthUIHooks::onGetPreferences';
		$wgHooks['MessagesPreLoad'][] = 'MediaWiki\Extensions\OAuth\MWOAuthUIHooks::onMessagesPreLoad';
		$wgHooks['SpecialPageAfterExecute'][] = 'MediaWiki\Extensions\OAuth\MWOAuthUIHooks::onSpecialPageAfterExecute';

		$wgResourceModules['ext.MWOAuth.BasicStyles'] = array(
			'position'		=> 'top',
			'styles'        => array( 'ext.MWOAuth.BasicStyles.css' ),
			'localBasePath' => dirname( __FILE__ ) . '/modules',
			'remoteExtPath' => 'OAuth/frontend/modules'
		);
		$wgResourceModules['ext.MWOAuth.AuthorizeForm'] = array(
			'position'		=> 'top',
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
		global $wgSpecialPages;
		global $wgLogTypes, $wgLogNames, $wgLogHeaders, $wgLogActionsHandlers;

		if ( MWOAuthUtils::isCentralWiki() ) {
			$wgSpecialPages['OAuthConsumerRegistration'] = 'MediaWiki\Extensions\OAuth\SpecialMWOAuthConsumerRegistration';
			$wgSpecialPages['OAuthManageConsumers'] = 'MediaWiki\Extensions\OAuth\SpecialMWOAuthManageConsumers';

			$wgLogTypes[] = 'mwoauthconsumer';
			$wgLogNames['mwoauthconsumer'] = 'mwoauthconsumer-consumer-logpage';
			$wgLogHeaders['mwoauthconsumer'] = 'mwoauthconsumer-consumer-logpagetext';
			$wgLogActionsHandlers['mwoauthconsumer/*'] = 'LogFormatter';
		}
	}
}
