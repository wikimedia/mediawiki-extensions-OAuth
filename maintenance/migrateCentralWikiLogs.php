<?php
/**
 * This script should be run as part of migrating to a new central OAuth wiki in your
 * cluster. See the notes in migrateCentralWikiLogs.php for the complete process.
 * This script is intended to be run on the new central wiki after the tables have already
 * been migrated. This will fill in the logs from newest to oldest, and tries to do sane
 * things if you need to stop it and restart it later.
 *
 * @ingroup Maintenance
 */
if ( getenv( 'MW_INSTALL_PATH' ) ) {
	$IP = getenv( 'MW_INSTALL_PATH' );
} else {
	$IP = __DIR__ . '/../../..';
}

require_once "$IP/maintenance/Maintenance.php";

use MediaWiki\MediaWikiServices;

class MigrateCentralWikiLogs extends Maintenance {
	public function __construct() {
		parent::__construct();
		$this->addDescription( "Import central wiki logs to this wiki" );
		$this->addOption( 'old', 'Previous central wiki', true, true );
		$this->setBatchSize( 200 );
		$this->requireExtension( "OAuth" );
	}

	public function execute() {
		$oldWiki = $this->getOption( 'old' );
		$targetWiki = WikiMap::getCurrentWikiId();

		$this->output( "Moving OAuth logs from '$oldWiki' to '$targetWiki'\n" );

		// We only read from $oldDb, but we do want to make sure we get the most recent logs.
		$lbFactory = MediaWikiServices::getInstance()->getDBLoadBalancerFactory();
		$oldDb = $lbFactory->getMainLB( $oldWiki )->getConnectionRef( DB_PRIMARY, [], $oldWiki );
		$targetDb = $lbFactory->getMainLB( $targetWiki )
			->getConnectionRef( DB_PRIMARY, [], $targetWiki );

		$targetMinTS = $targetDb->selectField(
			'logging',
			"MIN(log_timestamp)",
			[
				'log_type' => 'mwoauthconsumer',
			],
			__METHOD__
		);

		$lastMinTimestamp = null;
		if ( $targetMinTS !== false ) {
			$lastMinTimestamp = $targetMinTS;
		}

		$commentStore = MediaWikiServices::getInstance()->getCommentStore();
		$commentQuery = $commentStore->getJoin( 'log_comment' );

		do {
			$conds = [ 'log_type' => 'mwoauthconsumer' ];

			// This assumes that we don't have more than mBatchSize oauth log entries
			// with the same timestamp. Otherwise this will go into an infinite loop.
			if ( $lastMinTimestamp !== null ) {
				$conds[] = 'log_timestamp < ' .
					$oldDb->addQuotes( $oldDb->timestamp( $lastMinTimestamp ) );
			}

			$oldLoggs = $oldDb->select(
				[ 'logging', 'actor' ] + $commentQuery['tables'],
				[
					'log_id', 'log_action', 'log_timestamp', 'log_params', 'log_deleted',
					'actor_id', 'actor_name', 'actor_user'
				] + $commentQuery['fields'],
				$conds,
				__METHOD__,
				[
					'ORDER BY' => 'log_timestamp DESC',
					'LIMIT' => $this->mBatchSize + 1,
				],
				[
					'actor' => [ 'JOIN', 'actor_id=log_actor' ]
				] + $commentQuery['joins']
			);

			$rowCount = $oldLoggs->numRows();

			if ( $rowCount == $this->mBatchSize + 1 ) {
				$first = $oldLoggs->fetchObject();
				$oldLoggs->seek( $rowCount - 2 );
				$last = $oldLoggs->fetchObject();
				if ( $first->log_timestamp === $last->log_timestamp ) {
					$this->fatalError( "Batch size too low to avoid infinite loop.\n" );
				}
				$extra = $oldLoggs->fetchObject();
				if ( $last->log_timestamp === $extra->log_timestamp ) {
					$this->fatalError( "We hit an edge case. Please increase the batch " .
						" size and restart the transfer.\n" );
				}
				$oldLoggs->rewind();
			}

			$targetDb->begin( __METHOD__ );
			foreach ( $oldLoggs as $key => $row ) {
				// Skip if this is the extra row we selected
				if ( $key > $this->mBatchSize ) {
					continue;
				}

				$lastMinTimestamp = $row->log_timestamp;

				$this->output( "Migrating log {$row->log_id}...\n" );
				if ( !$row->actor_user ) {
					$this->output(
						"Cannot transfer log_id: {$row->log_id}, the log user doesn't exist"
					);
					continue;
				}
				$logUser = MediaWikiServices::getInstance()->getActorNormalization()
					->newActorFromRow( $row );
				$params = LogEntryBase::extractParams( $row->log_params );
				if ( !isset( $params['4:consumer'] ) ) {
					$this->output( "Cannot transfer log_id: {$row->log_id}, param isn't correct" );
					continue;
				}
				$logEntry = new ManualLogEntry( 'mwoauthconsumer', $row->log_action );
				$logEntry->setPerformer( $logUser );
				$logEntry->setTarget( Title::makeTitleSafe( NS_USER, $row->actor_name ) );
				$logEntry->setComment( $commentStore->getComment( 'log_comment', $row )->text );
				$logEntry->setParameters( $params );
				$logEntry->setRelations( [
					'OAuthConsumer' => [ $params['4:consumer'] ]
				] );
				// ManualLogEntry::insert() calls $dbw->timestamp on the value
				$logEntry->setTimestamp( $row->log_timestamp );
				// @TODO: Maybe this will do something some day. Sigh.
				$logEntry->setDeleted( $row->log_deleted );
				$logEntry->insert( $targetDb );
			}
			$targetDb->commit( __METHOD__ );

			$lbFactory->waitForReplication();

		} while ( $rowCount );
	}

}

$maintClass = MigrateCentralWikiLogs::class;
require_once RUN_MAINTENANCE_IF_MAIN;
