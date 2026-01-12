<?php

namespace MediaWiki\Extension\OAuth\Frontend;

use MediaWiki\Extension\Notifications\Hooks\BeforeCreateEchoEventHook;
use MediaWiki\Extension\OAuth\Backend\Utils;
use MediaWiki\Extension\OAuth\Control\ConsumerSubmitControl;

class EchoHooks implements BeforeCreateEchoEventHook {

	/** @inheritDoc */
	public function onBeforeCreateEchoEvent(
		&$notifications, &$notificationCategories, &$icons
	) {
		global $wgOAuthGroupsToNotify;

		if ( !Utils::isCentralWiki() ) {
			return;
		}

		$notificationCategories['oauth-owner'] = [
			'tooltip' => 'echo-pref-tooltip-oauth-owner',
		];
		$notificationCategories['oauth-admin'] = [
			'tooltip' => 'echo-pref-tooltip-oauth-admin',
			'usergroups' => $wgOAuthGroupsToNotify,
		];

		foreach ( ConsumerSubmitControl::$actions as $eventName ) {
			// oauth-app-propose and oauth-app-update notifies admins of the app.
			// oauth-app-approve, oauth-app-reject, oauth-app-disable and oauth-app-reenable
			// notify owner of the change.
			$notifications["oauth-app-$eventName"] =
				EchoOAuthStageChangePresentationModel::getDefinition( $eventName );
		}

		$icons['oauth'] = [ 'path' => 'OAuth/resources/assets/echo-icon.png' ];
	}
}
