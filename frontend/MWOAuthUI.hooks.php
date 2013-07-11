<?php
/**
 * Class containing GUI even handler functions for an OAuth environment
 */
class MWOAuthUIHooks {
	public static function onGetPreferences(  $user, &$preferences ) {
		$prefInsert = array( 'mwoauth-prefs-managegrants' =>
			array(
				'section' => 'personal/info',
				'label-message' => 'mwoauth-prefs-managegrants',
				'type' => 'info',
				'raw' => true,
				'default' => wfMessage( 'parentheses' )->rawParams(
					Linker::linkKnown(
						SpecialPage::getTitleFor( 'MWOAuthManageMyGrants' ),
						wfMessage( 'mwoauth-prefs-managegrantslink' )
					)
				)->escaped()
			),
		);

		$after = array_key_exists( 'editcount', $preferences )
			? 'usergroups'
			: 'userid';
		$preferences = wfArrayInsertAfter( $preferences, $prefInsert, $after );

		return true;
	}
}
