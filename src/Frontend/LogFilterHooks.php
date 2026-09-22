<?php

namespace MediaWiki\Extension\OAuth\Frontend;

use MediaWiki\Logging\Hook\LogEventsListGetExtraInputsHook;
use MediaWiki\Specials\Hook\SpecialLogAddLogSearchRelationsHook;
use Wikimedia\Rdbms\IConnectionProvider;

/**
 * Hide owner-only consumer creations on Special:Log/mwoauthconsumer unless asked for.
 *
 * @license GPL-2.0-or-later
 */
class LogFilterHooks implements
	LogEventsListGetExtraInputsHook,
	SpecialLogAddLogSearchRelationsHook
{
	private const LOG_TYPE = 'mwoauthconsumer';
	private const OWNER_ONLY_ACTION = 'create-owner-only';

	public function __construct(
		private readonly IConnectionProvider $connectionProvider,
	) {
	}

	/** @inheritDoc */
	public function onLogEventsListGetExtraInputs( $type, $logEventsList, &$unused, &$formDescriptor ) {
		if ( $type !== self::LOG_TYPE ) {
			return;
		}
		$formDescriptor = [
			'type' => 'check',
			'name' => UIUtils::SHOW_OWNER_ONLY_PARAM,
			'label-message' => 'mwoauthconsumer-log-show-owner-only',
			'default' => false,
		];
	}

	/** @inheritDoc */
	public function onSpecialLogAddLogSearchRelations( $type, $request, &$qc ) {
		if ( $type !== self::LOG_TYPE
			|| $request->getBool( UIUtils::SHOW_OWNER_ONLY_PARAM )
			// An explicit request for these entries, or for one specific entry, must never come back empty
			|| $request->getVal( 'subtype' ) === self::OWNER_ONLY_ACTION
			|| $request->getInt( 'logid' )
		) {
			return;
		}
		$qc[] = $this->connectionProvider->getReplicaDatabase()
			->expr( 'log_action', '!=', self::OWNER_ONLY_ACTION );
	}
}
