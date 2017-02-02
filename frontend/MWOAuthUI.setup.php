<?php

namespace MediaWiki\Extensions\OAuth;

/**
 * Class containing hooked functions for an OAuth environment
 */
class MWOAuthUISetup {
	/**
	 * @return void
	 */
	public static function conditionalSetup() {
		global $wgSpecialPages, $wgLogTypes, $wgLogNames,
			$wgLogHeaders, $wgLogActionsHandlers;

		if ( MWOAuthUtils::isCentralWiki() ) {
			$wgSpecialPages['OAuthConsumerRegistration'] = 'MediaWiki\Extensions\OAuth\SpecialMWOAuthConsumerRegistration';
			$wgSpecialPages['OAuthManageConsumers'] = 'MediaWiki\Extensions\OAuth\SpecialMWOAuthManageConsumers';

			$wgLogTypes[] = 'mwoauthconsumer';
			$wgLogNames['mwoauthconsumer'] = 'mwoauthconsumer-consumer-logpage';
			$wgLogHeaders['mwoauthconsumer'] = 'mwoauthconsumer-consumer-logpagetext';
			$wgLogActionsHandlers['mwoauthconsumer/*'] = MWOAuthLogFormatter::class;
		}
	}
}
