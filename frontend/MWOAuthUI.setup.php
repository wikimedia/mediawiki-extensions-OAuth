<?php
/**
 * Class containing hooked functions for an OAuth environment
 */
class MWOAuthUISetup {
	/**
	 * @param array $pages $wgSpecialPages (list of special pages)
	 * @param array $groups $wgSpecialPageGroups (assoc array of special page groups)
	 * @return void
	 */
	public static function defineSpecialPages( array &$pages, array &$groups ) {
		// Pages specific to the central OAuth management wiki
		if ( MWOAuthUtils::isCentralWiki() ) {
			$pages['MWOAuthConsumerRegistration'] = 'MWOAuthConsumerRegistration';
			$groups['MWOAuthConsumerRegistration'] = 'users';
			$pages['MWOAuthManageConsumers'] = 'MWOAuthManageConsumers';
			$groups['MWOAuthManageConsumers'] = 'users';
			$pages['MWOAuthManageMyGrants'] = 'MWOAuthManageMyGrants';
		}
	}

	/**
	 * @param array $logNames $wgLogNames (assoc array of log name message keys)
	 * @param array $logHeaders $wgLogHeaders (assoc array of log header message keys)
	 * @param array $filterLogTypes $wgFilterLogTypes
	 * @return void
	 */
	public static function defineLogBasicDescription(
		&$logNames, &$logHeaders, &$filterLogTypes
	) {
		$logNames['mwoauthconsumer'] = 'mwoauthconsumer-consumer-logpage';
		$logHeaders['mwoauthconsumer'] = 'mwoauthconsumer-consumer-logpagetext';
	}

	/**
	 * @param array$logActions $wgLogActions (assoc array of log action message keys)
	 * @param array $logActionsHandlers $wgLogActionsHandlers (assoc array of log handlers)
	 * @return void
	 */
	public static function defineLogActionHandlers(
		&$logActions, &$logActionsHandlers
	) {
		$logActions['mwoauthconsumer/propose'] = 'mwoauth-logentry-consumer-propose';
		$logActions['mwoauthconsumer/update'] = 'mwoauth-logentry-consumer-update';
		$logActions['mwoauthconsumer/approve'] = 'mwoauth-logentry-consumer-approve';
		$logActions['mwoauthconsumer/reject'] = 'mwoauth-logentry-consumer-reject';
		$logActions['mwoauthconsumer/disable'] = 'mwoauth-logentry-consumer-disable';
		$logActions['mwoauthconsumer/reenable'] = 'mwoauth-logentry-consumer-reenable';
	}

	/**
	 * @param array $modules $wgResourceModules
	 * @return void
	 */
	public static function defineResourceModules( array &$modules ) {

	}
}
