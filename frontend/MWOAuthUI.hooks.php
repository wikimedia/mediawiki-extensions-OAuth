<?php
/**
 * Class containing GUI even handler functions for an OAuth environment
 */
class MWOAuthUIHooks {
	public static function onGetPreferences( $user, &$preferences ) {
		$dbr = MWOAuthUtils::getCentralDB( DB_SLAVE );
		$conds = array(
			'oaac_consumer_id = oarc_id',
			'oaac_user_id' => MWOAuthUtils::getCentralIdFromLocalUser( $user ),
		);
		if ( !$user->isAllowed( 'mwoauthviewsuppressed' ) ) {
			$conds['oarc_deleted'] = 0;
		}
		$count = $dbr->selectField(
			array( 'oauth_accepted_consumer', 'oauth_registered_consumer' ),
			'COUNT(*)',
			$conds,
			__METHOD__
		);

		$prefInsert = array( 'mwoauth-prefs-managegrants' =>
			array(
				'section' => 'personal/info',
				'label-message' => 'mwoauth-prefs-managegrants',
				'type' => 'info',
				'raw' => true,
				'default' => Linker::linkKnown(
					SpecialPage::getTitleFor( 'MWOAuthManageMyGrants' ),
					wfMessage( 'mwoauth-prefs-managegrantslink', $count )->escaped()
				)
			),
		);

		$after = array_key_exists( 'editcount', $preferences )
			? 'usergroups'
			: 'userid';
		$preferences = wfArrayInsertAfter( $preferences, $prefInsert, $after );

		return true;
	}
}
