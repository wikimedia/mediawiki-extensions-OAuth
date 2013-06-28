<?php
/**
 * Static utility functions for OAuth
 */
class MWOAuthUtils {
	/**
	 * @return bool
	 */
	public static function isCentralWiki() {
		global $wgMWOAuthCentralWiki;

		return ( wfWikiId() === $wgMWOAuthCentralWiki );
	}

	/**
	 * @TODO: reference count/release DB
	 * @param integer $index DB_MASTER/DB_SLAVE
	 */
	public static function getCentralDB( $index ) {
		global $wgMWOAuthCentralWiki;

		return wfGetDB( $index, array(), $wgMWOAuthCentralWiki );
	}

	/**
	 * @param DatabaseBase $db
	 * @return array
	 */
	public static function getConsumerStateCounts( DatabaseBase $db ) {
		$res = $db->select( 'oauth_registered_consumer',
			array( 'oarc_stage', 'count' => 'COUNT(*)' ),
			array(),
			__METHOD__,
			array( 'GROUP BY' => 'oarc_stage' )
		);
		$table = array(
			MWOAuthConsumer::STAGE_APPROVED => 0,
			MWOAuthConsumer::STAGE_DISABLED => 0,
			MWOAuthConsumer::STAGE_EXPIRED  => 0,
			MWOAuthConsumer::STAGE_PROPOSED => 0,
			MWOAuthConsumer::STAGE_REJECTED => 0,
		);
		foreach ( $res as $row ) {
			$table[(int)$row->oarc_stage] = $row->count;
		}
		return $table;
	}

	/**
	 * @param DatabaseBase $db
	 * @return void
	 */
	public static function runAutoMaintenance( DatabaseBase $db ) {
		// @TODO: move old rejected => expired and DELETE even older expired
	}
}
