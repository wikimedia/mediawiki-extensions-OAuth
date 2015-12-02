<?php

namespace MediaWiki\Extensions\OAuth;

/*
 (c) Aaron Schulz 2013, GPL

 This program is free software; you can redistribute it and/or modify
 it under the terms of the GNU General Public License as published by
 the Free Software Foundation; either version 2 of the License, or
 (at your option) any later version.

 This program is distributed in the hope that it will be useful,
 but WITHOUT ANY WARRANTY; without even the implied warranty of
 MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 GNU General Public License for more details.

 You should have received a copy of the GNU General Public License along
 with this program; if not, write to the Free Software Foundation, Inc.,
 59 Temple Place - Suite 330, Boston, MA 02111-1307, USA.
 http://www.gnu.org/copyleft/gpl.html
*/

/**
 * Special page for listing the queue of consumer requests and managing
 * their approval/rejection and also for listing approved/disabled consumers
 */
class SpecialMWOAuthManageConsumers extends \SpecialPage {
	protected $stage; // integer; MWOAuthConsumer::STAGE_* constant
	protected $stageKey; // string

	protected static $stageKeyMap = array(
		MWOAuthConsumer::STAGE_PROPOSED => 'proposed',
		MWOAuthConsumer::STAGE_REJECTED => 'rejected',
		MWOAuthConsumer::STAGE_EXPIRED  => 'expired',
		MWOAuthConsumer::STAGE_APPROVED => 'approved',
		MWOAuthConsumer::STAGE_DISABLED => 'disabled',
	);

	public function __construct() {
		parent::__construct( 'OAuthManageConsumers', 'mwoauthmanageconsumer' );
	}

	public function execute( $par ) {
		global $wgMWOAuthReadOnly;

		$user = $this->getUser();

		$this->setHeaders();
		$this->getOutput()->disallowUserJs();

		if ( !$user->isLoggedIn() ) {
			$this->getOutput()->addWikiMsg( 'mwoauthmanageconsumers-notloggedin' );
			return;
		} elseif ( !$user->isAllowed( 'mwoauthmanageconsumer' ) ) {
			throw new \PermissionsError( 'mwoauthmanageconsumer' );
		}

		if ( $wgMWOAuthReadOnly ) {
			throw new \ErrorPageError( 'mwoauth-error', 'mwoauth-db-readonly' );
		}

		// Format is Special:OAuthManageConsumers[/<stage>[/<consumer key>]]
		$navigation = explode( '/', $par );
		$stageKey = isset( $navigation[0] ) ? $navigation[0] : null;
		$consumerKey = isset( $navigation[1] ) ? $navigation[1] : null;

		switch ( $stageKey ) {
		// Queue of new consumer requests:
		case 'proposed':
			$this->stage = MWOAuthConsumer::STAGE_PROPOSED;
			break;
		case 'rejected':
			$this->stage = MWOAuthConsumer::STAGE_REJECTED;
			break;
		case 'expired':
			$this->stage = MWOAuthConsumer::STAGE_EXPIRED;
			break;
		// List of currently and once-approved consumers:
		case 'approved':
			$this->stage = MWOAuthConsumer::STAGE_APPROVED;
			break;
		case 'disabled':
			$this->stage = MWOAuthConsumer::STAGE_DISABLED;
			break;
		}

		if ( $this->stage !== null ) {
			$this->stageKey = $stageKey;
			if ( $consumerKey ) {
				$this->handleConsumerForm( $consumerKey );
			} else {
				$this->showConsumerList();
			}
		} else {
			$this->showMainHub();
		}

		$this->addQueueSubtitleLinks( $consumerKey );

		$this->getOutput()->addModuleStyles( 'ext.MWOAuth.BasicStyles' );
	}

	/**
	 * Show other sub-queue links. Grey out the current one.
	 * When viewing a request, show them all.
	 *
	 * @param string $consumerKey
	 * @return void
	 */
	protected function addQueueSubtitleLinks( $consumerKey ) {
		$listLinks = array();
		if ( $consumerKey || $this->stage !== MWOAuthConsumer::STAGE_PROPOSED ) {
			$listLinks[] = \Linker::linkKnown(
				$this->getPageTitle( 'proposed' ),
				$this->msg( 'mwoauthmanageconsumers-showproposed' )->escaped() );
		} else {
			$listLinks[] = $this->msg( 'mwoauthmanageconsumers-showproposed' )->escaped();
		}
		if ( $consumerKey || $this->stage !== MWOAuthConsumer::STAGE_REJECTED ) {
			$listLinks[] = \Linker::linkKnown(
				$this->getPageTitle( 'rejected' ),
				$this->msg( 'mwoauthmanageconsumers-showrejected' )->escaped() );
		} else {
			$listLinks[] = $this->msg( 'mwoauthmanageconsumers-showrejected' )->escaped();
		}
		if ( $consumerKey || $this->stage !== MWOAuthConsumer::STAGE_EXPIRED ) {
			$listLinks[] = \Linker::linkKnown(
				$this->getPageTitle( 'expired' ),
				$this->msg( 'mwoauthmanageconsumers-showexpired' )->escaped() );
		} else {
			$listLinks[] = $this->msg( 'mwoauthmanageconsumers-showexpired' )->escaped();
		}

		$linkHtml = $this->getLanguage()->pipeList( $listLinks );

		$viewall = $this->msg( 'parentheses' )->rawParams( \Linker::linkKnown(
			$this->getPageTitle(), $this->msg( 'mwoauthmanageconsumers-main' )->escaped() ) );

		$this->getOutput()->setSubtitle(
			"<strong>" . $this->msg( 'mwoauthmanageconsumers-type' )->escaped() .
			"</strong> [{$linkHtml}] <strong>{$viewall}</strong>" );
	}

	/**
	 * Show the links to all the queues and how many requests are in each.
	 * Also show the list of enabled and disabled consumers and how many there are of each.
	 *
	 * @return void
	 */
	protected function showMainHub() {
		static $keyStageMapQ = array(
			'proposed' => MWOAuthConsumer::STAGE_PROPOSED,
			'rejected' => MWOAuthConsumer::STAGE_REJECTED,
			'expired'  => MWOAuthConsumer::STAGE_EXPIRED
		);
		static $keyStageMapL = array(
			'approved' => MWOAuthConsumer::STAGE_APPROVED,
			'disabled' => MWOAuthConsumer::STAGE_DISABLED,
		);

		$out = $this->getOutput();

		$out->addWikiMsg( 'mwoauthmanageconsumers-maintext' );

		$counts = MWOAuthUtils::getConsumerStateCounts( MWOAuthUtils::getCentralDB( DB_SLAVE ) );

		$out->wrapWikiMsg( "<p><strong>$1</strong></p>", 'mwoauthmanageconsumers-queues' );
		$out->addHTML( '<ul>' );
		foreach ( $keyStageMapQ as $stageKey => $stage ) {
			$tag = ( $stage === MWOAuthConsumer::STAGE_EXPIRED ) ? 'i' : 'b';
			$out->addHTML(
				'<li>' .
				"<$tag>" .
				\Linker::linkKnown(
					$this->getPageTitle( $stageKey ),
					// Messages: mwoauthmanageconsumers-q-proposed, mwoauthmanageconsumers-q-rejected,
					// mwoauthmanageconsumers-q-expired
					$this->msg( 'mwoauthmanageconsumers-q-' . $stageKey )->escaped()
				) .
				"</$tag> [$counts[$stage]]" .
				'</li>'
			);
		}
		$out->addHTML( '</ul>' );

		$out->wrapWikiMsg( "<p><strong>$1</strong></p>", 'mwoauthmanageconsumers-lists' );
		$out->addHTML( '<ul>' );
		foreach ( $keyStageMapL as $stageKey => $stage ) {
			$out->addHTML(
				'<li>' .
				\Linker::linkKnown(
					$this->getPageTitle( $stageKey ),
					// Messages: mwoauthmanageconsumers-l-approved, mwoauthmanageconsumers-l-disabled
					$this->msg( 'mwoauthmanageconsumers-l-' . $stageKey )->escaped()
				) .
				" [$counts[$stage]]" .
				'</li>'
			);
		}
		$out->addHTML( '</ul>' );
	}

	/**
	 * Show the form to approve/reject/disable/re-enable consumers
	 *
	 * @param string $consumerKey
	 * @throws \PermissionsError
	 */
	protected function handleConsumerForm( $consumerKey ) {
		$user = $this->getUser();
		$lang = $this->getLanguage();
		$dbr = MWOAuthUtils::getCentralDB( DB_SLAVE );
		$cmr = MWOAuthDAOAccessControl::wrap(
			MWOAuthConsumer::newFromKey( $dbr, $consumerKey ), $this->getContext() );
		if ( !$cmr ) {
			$this->getOutput()->addWikiMsg( 'mwoauth-invalid-consumer-key' );
			return;
		} elseif ( $cmr->get( 'deleted' ) && !$user->isAllowed( 'mwoauthviewsuppressed' ) ) {
			throw new \PermissionsError( 'mwoauthviewsuppressed' );
		}
		$pending = !in_array( $cmr->get( 'stage' ), array(
			MWOAuthConsumer::STAGE_APPROVED, MWOAuthConsumer::STAGE_DISABLED ) );

		if ( $pending ) {
			$opts = array(
				$this->msg( 'mwoauthmanageconsumers-approve' )->escaped() => 'approve',
				$this->msg( 'mwoauthmanageconsumers-reject' )->escaped()  => 'reject'
			);
			if ( $this->getUser()->isAllowed( 'mwoauthsuppress' ) ) {
				$msg = $this->msg( 'mwoauthmanageconsumers-rsuppress' )->escaped();
				$opts["<strong>$msg</strong>"] = 'rsuppress';
			}
		} else {
			$opts = array(
				$this->msg( 'mwoauthmanageconsumers-disable' )->escaped() => 'disable',
				$this->msg( 'mwoauthmanageconsumers-reenable' )->escaped()  => 'reenable'
			);
			if ( $this->getUser()->isAllowed( 'mwoauthsuppress' ) ) {
				$msg = $this->msg( 'mwoauthmanageconsumers-dsuppress' )->escaped();
				$opts["<strong>$msg</strong>"] = 'dsuppress';
			}
		}

		$owner = $cmr->get( 'userId', function( $s ) {
			$name = MWOAuthUtils::getCentralUserNameFromId( $s );
			return $name;
		} );

		$link = \Linker::linkKnown(
			$title = \SpecialPage::getTitleFor( 'OAuthListConsumers' ),
			wfMessage( 'mwoauthmanageconsumers-search-publisher' )->escaped(),
			array(),
			array( 'publisher' => $owner )
		);
		$ownerLink = htmlspecialchars( $owner ) . ' ' .
			wfMessage( 'parentheses' )->rawParams( $link )->escaped();
		$ownerOnly = $cmr->get( 'ownerOnly' );

		$dbw = MWOAuthUtils::getCentralDB( DB_MASTER ); // @TODO: lazy handle
		$control = new MWOAuthConsumerSubmitControl( $this->getContext(), array(), $dbw );
		$form = new \HTMLForm(
			$control->registerValidators( array(
				'consumerKeyShown' => array(
					'type' => 'info',
					'label-message' => 'mwoauth-consumer-key',
					'default' => $cmr->get( 'consumerKey' ),
				),
				'name' => array(
					'type' => 'info',
					'label-message' => 'mwoauth-consumer-name',
					'default' => $cmr->get( 'name', function( $s ) {
						$link = \Linker::linkKnown(
							\SpecialPage::getTitleFor( 'OAuthListConsumers' ),
							wfMessage( 'mwoauthmanageconsumers-search-name' )->escaped(),
							array(),
							array( 'name' => $s )
						);
						return htmlspecialchars( $s ) . ' ' .
							wfMessage( 'parentheses' )->rawParams( $link )->escaped();
					} ),
					'raw' => true
				),
				'version' => array(
					'type' => 'info',
					'label-message' => 'mwoauth-consumer-version',
					'default' => $cmr->get( 'version' )
				),
				'user' => array(
					'type' => 'info',
					'label-message' => 'mwoauth-consumer-user',
					'default' => $ownerLink,
					'raw' => true
				),
				'description' => array(
					'type' => 'info',
					'label-message' => 'mwoauth-consumer-description',
					'default' => $cmr->get( 'description' ),
					'rows' => 5
				),
				'ownerOnly' => array(
					'type' => $ownerOnly ? 'info' : 'hidden',
					'label-message' => 'mwoauth-consumer-owner-only-label',
					'default' => wfMessage( 'mwoauth-consumer-owner-only', $owner )->escaped(),
				),
				'callbackUrl' => array(
					'type' => $ownerOnly ? 'hidden' : 'info',
					'label-message' => 'mwoauth-consumer-callbackurl',
					'default' => $cmr->get( 'callbackUrl' ),
				),
				'callbackIsPrefix' => array(
					'type' => $ownerOnly ? 'hidden' : 'info',
					'label-message' => 'mwoauth-consumer-callbackisprefix',
					'default' => $cmr->get( 'callbackIsPrefix' )
				),
				'grants'  => array(
					'type' => 'info',
					'label-message' => 'mwoauth-consumer-grantsneeded',
					'default' => $cmr->get( 'grants', function( $grants ) use ( $lang ) {
						return $lang->semicolonList( \MWGrants::grantNames( $grants, $lang ) );
					} ),
					'rows' => 5
				),
				'email' => array(
					'type' => 'info',
					'label-message' => 'mwoauth-consumer-email',
					'default' => $cmr->get( 'email' )
				),
				'wiki' => array(
					'type' => 'info',
					'label-message' => 'mwoauth-consumer-wiki',
					'default' => $cmr->get( 'wiki' )
				),
				'restrictions' => array(
					'type' => 'info',
					'label-message' => 'mwoauth-consumer-restrictions-json',
					'default' => $cmr->get( 'restrictions' )->toJson( true ),
					'rows' => 5
				),
				'rsaKey' => array(
					'type' => 'info',
					'label-message' => 'mwoauth-consumer-rsakey',
					'default' => $cmr->get( 'rsaKey' ),
					'rows' => 5
				),
				'stage' => array(
					'type' => 'info',
					'label-message' => 'mwoauth-consumer-stage',
					// Messages: mwoauth-consumer-stage-proposed, mwoauth-consumer-stage-rejected,
					// mwoauth-consumer-stage-expired, mwoauth-consumer-stage-approved,
					// mwoauth-consumer-stage-disabled
					'default' => $cmr->get( 'deleted' )
						? $this->msg( 'mwoauth-consumer-stage-suppressed' )
						: $this->msg( 'mwoauth-consumer-stage-' .
							self::$stageKeyMap[$cmr->get( 'stage' )] )
				),
				'action' => array(
					'type' => 'radio',
					'label-message' => 'mwoauthmanageconsumers-action',
					'required' => true,
					'options' => $opts,
					'default' => '' // no validate on GET
				),
				'reason' => array(
					'type' => 'text',
					'label-message' => 'mwoauthmanageconsumers-reason',
					'required' => true
				),
				'consumerKey' => array(
					'type' => 'hidden',
					'default' => $cmr->get( 'consumerKey' )
				),
				'changeToken' => array(
					'type' => 'hidden',
					'default' => $cmr->getDAO()->getChangeToken( $this->getContext() )
				),
			) ),
			$this->getContext()
		);
		$form->setSubmitCallback(
			function( array $data, \IContextSource $context ) use ( $control ) {
				$data['suppress'] = 0;
				if ( $data['action'] === 'dsuppress' ) {
					$data = array( 'action' => 'disable', 'suppress' => 1 ) + $data;
				} elseif ( $data['action'] === 'rsuppress' ) {
					$data = array( 'action' => 'reject', 'suppress' => 1 ) + $data;
				}
				$control->setInputParameters( $data );
				return $control->submit();
			}
		);

		$form->setWrapperLegendMsg( 'mwoauthmanageconsumers-confirm-legend' );
		$form->setSubmitTextMsg( 'mwoauthmanageconsumers-confirm-submit' );
		$form->addPreText(
			$this->msg( 'mwoauthmanageconsumers-confirm-text' )->parseAsBlock() );

		$status = $form->show();
		if ( $status instanceof \Status && $status->isOk() ) {
			$type = self::$stageKeyMap[$status->value['result']->get( 'stage' )];
			// Messages: mwoauthmanageconsumers-success-approved, mwoauthmanageconsumers-success-rejected,
			// mwoauthmanageconsumers-success-disabled
			$this->getOutput()->addWikiMsg( "mwoauthmanageconsumers-success-$type" );
			$this->getOutput()->returnToMain();
		} else {
			$out = $this->getOutput();
			// Show all of the status updates
			$logPage = new \LogPage( 'mwoauthconsumer' );
			$out->addHTML( \Xml::element( 'h2', null, $logPage->getName()->text() ) );
			\LogEventsList::showLogExtract( $out, 'mwoauthconsumer', '', '',
				array(
					'conds'  => array(
						'ls_field' => 'OAuthConsumer', 'ls_value' => $cmr->get( 'consumerKey' ) ),
					'flags'  => \LogEventsList::NO_EXTRA_USER_LINKS
				)
			);
		}
	}

	/**
	 * Show a paged list of consumers with links to details
	 */
	protected function showConsumerList() {
		$pager = new MWOAuthManageConsumersPager( $this, array(), $this->stage );
		if ( $pager->getNumRows() ) {
			$this->getOutput()->addHTML( $pager->getNavigationBar() );
			$this->getOutput()->addHTML( $pager->getBody() );
			$this->getOutput()->addHTML( $pager->getNavigationBar() );
		} else {
			// Messages: mwoauthmanageconsumers-none-proposed, mwoauthmanageconsumers-none-rejected,
			// mwoauthmanageconsumers-none-expired, mwoauthmanageconsumers-none-approved,
			// mwoauthmanageconsumers-none-disabled
			$this->getOutput()->addWikiMsg( "mwoauthmanageconsumers-none-{$this->stageKey}" );
		}
		# Every 30th view, prune old deleted items
		if ( 0 == mt_rand( 0, 29 ) ) {
			MWOAuthUtils::runAutoMaintenance( MWOAuthUtils::getCentralDB( DB_MASTER ) );
		}
	}

	/**
	 * @param \DBConnRef $db
	 * @param sdtclass $row
	 * @return string
	 */
	public function formatRow( \DBConnRef $db, $row ) {
		static $stageActionMap = array(
			MWOAuthConsumer::STAGE_PROPOSED => 'propose',
			MWOAuthConsumer::STAGE_REJECTED => 'reject',
			MWOAuthConsumer::STAGE_EXPIRED  => 'propose',
			MWOAuthConsumer::STAGE_APPROVED => 'approve',
			MWOAuthConsumer::STAGE_DISABLED => 'disable',
		);

		$cmr = MWOAuthDAOAccessControl::wrap(
			MWOAuthConsumer::newFromRow( $db, $row ), $this->getContext() );

		$cmrKey = $cmr->get( 'consumerKey' );
		$stageKey = self::$stageKeyMap[$cmr->get( 'stage' )];

		$link = \Linker::linkKnown(
			$this->getPageTitle( "{$stageKey}/{$cmrKey}" ),
			$this->msg( 'mwoauthmanageconsumers-review' )->escaped()
		);

		$time = $this->getLanguage()->timeanddate(
			wfTimestamp( TS_MW, $cmr->get( 'registration' ) ), true );

		$encStageKey = htmlspecialchars( $stageKey ); // sanity
		$r = "<li class='mw-mwoauthmanageconsumers-{$encStageKey}'>";

		$r .= $time . " (<strong>{$link}</strong>)";

		// Show last log entry (@TODO: title namespace?)
		// @TODO: inject DB
		$logHtml = '';
		\LogEventsList::showLogExtract( $logHtml, 'mwoauthconsumer', '', '',
			array(
				'action' => $stageActionMap[$cmr->get( 'stage' )],
				'conds'  => array(
					'ls_field' => 'OAuthConsumer', 'ls_value' => $cmr->get( 'consumerKey' ) ),
				'lim'    => 1,
				'flags'  => \LogEventsList::NO_EXTRA_USER_LINKS
			)
		);

		$lang = $this->getLanguage();
		$data = array(
			'mwoauthmanageconsumers-name' => htmlspecialchars(
				$cmr->get( 'name', function( $s ) use ( $cmr ) {
					return $s . ' [' . $cmr->get( 'version' ) . ']'; } )
			),
			'mwoauthmanageconsumers-user' => htmlspecialchars(
				$cmr->get( 'userId', 'MediaWiki\Extensions\OAuth\MWOAuthUtils::getCentralUserNameFromId' )
			),
			'mwoauthmanageconsumers-description' => htmlspecialchars(
				$cmr->get( 'description', function( $s ) use ( $lang ) {
					return $lang->truncate( $s, 10024 ); } )
			),
			'mwoauthmanageconsumers-email' => htmlspecialchars( $cmr->get( 'email' ) ),
			'mwoauthmanageconsumers-consumerkey' => htmlspecialchars( $cmr->get( 'consumerKey' ) ),
			'mwoauthmanageconsumers-lastchange' => $logHtml
		);

		$r .= "<table class='mw-mwoauthmanageconsumers-body' " .
			"cellspacing='1' cellpadding='3' border='1' width='100%'>";
		foreach ( $data as $msg => $encValue ) {
			$r .= '<tr>' .
				'<td><strong>' . $this->msg( $msg )->escaped() . '</strong></td>' .
				'<td width=\'90%\'>' . $encValue . '</td>' .
				'</tr>';
		}
		$r .= '</table>';

		$r .= '</li>';

		return $r;
	}

	protected function getGroupName() {
		return 'users';
	}
}

/**
 * Query to list out consumers
 *
 * @TODO: use UserCache
 */
class MWOAuthManageConsumersPager extends \ReverseChronologicalPager {
	public $mForm, $mConds;

	function __construct( $form, $conds, $stage ) {
		$this->mForm = $form;
		$this->mConds = $conds;
		$this->mConds['oarc_stage'] = $stage;
		if ( !$this->getUser()->isAllowed( 'mwoauthviewsuppressed' ) ) {
			$this->mConds['oarc_deleted'] = 0;
		}

		$this->mDb = MWOAuthUtils::getCentralDB( DB_SLAVE );
		parent::__construct();

		# Treat 20 as the default limit, since each entry takes up 5 rows.
		$urlLimit = $this->mRequest->getInt( 'limit' );
		$this->mLimit = $urlLimit ? $urlLimit : 20;
	}

	/**
	 * @return \Title
	 */
	function getTitle() {
		return $this->mForm->getFullTitle();
	}

	/**
	 * @param $row
	 * @return string
	 */
	function formatRow( $row ) {
		return $this->mForm->formatRow( $this->mDb, $row );
	}

	/**
	 * @return string
	 */
	function getStartBody() {
		if ( $this->getNumRows() ) {
			return '<ul>';
		} else {
			return '';
		}
	}

	/**
	 * @return string
	 */
	function getEndBody() {
		if ( $this->getNumRows() ) {
			return '</ul>';
		} else {
			return '';
		}
	}

	/**
	 * @return array
	 */
	function getQueryInfo() {
		return array(
			'tables' => array( 'oauth_registered_consumer' ),
			'fields' => array( '*' ),
			'conds'  => $this->mConds
		);
	}

	/**
	 * @return string
	 */
	function getIndexField() {
		return 'oarc_stage_timestamp';
	}
}
