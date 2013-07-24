<?php
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
 * Special page for listing consumers this user granted access to and
 * for manage the specific grants given or revoking access for the consumer
 */
class SpecialMWOAuthManageMyGrants extends UnlistedSpecialPage {
	protected static $stageKeyMap = array(
		MWOAuthConsumer::STAGE_PROPOSED => 'proposed',
		MWOAuthConsumer::STAGE_REJECTED => 'rejected',
		MWOAuthConsumer::STAGE_EXPIRED  => 'expired',
		MWOAuthConsumer::STAGE_APPROVED => 'approved',
		MWOAuthConsumer::STAGE_DISABLED => 'disabled',
	);

	public function __construct() {
		parent::__construct( 'MWOAuthManageMyGrants', 'mwoauthmanagemygrants' );
	}

	public function execute( $par ) {
		$user = $this->getUser();
		$request = $this->getRequest();

		if ( !$user->isAllowed( 'mwoauthmanagemygrants' ) ) {
			throw new PermissionsError( 'mwoauthmanagemygrants' );
		} elseif ( !$user->getID() ) {
			throw new PermissionsError( 'user' );
		}

		$this->setHeaders();
		$this->getOutput()->disallowUserJs();

		// Format is Special:MWOAuthManageMyGrants[/list|/manage/<accesstoken>]
		$navigation = explode( '/', $par );
		$typeKey = isset( $navigation[0] ) ? $navigation[0] : null;
		$acceptanceId = isset( $navigation[1] ) ? $navigation[1] : null;

		switch ( $typeKey ) {
		case 'manage':
			$this->handleConsumerForm( $acceptanceId );
			break;
		case 'list':
			// fall through
		default:
			$this->showConsumerList();
			break;
		}

		$this->addSubtitleLinks( $acceptanceId );

		$this->getOutput()->addModules( 'ext.MWOAuth' ); // CSS
	}

	/**
	 * Show other parent page link
	 *
	 * @param type $acceptanceId
	 * @return void
	 */
	protected function addSubtitleLinks( $acceptanceId ) {
		$listLinks = array();
		if ( $acceptanceId ) {
			$listLinks[] = Linker::linkKnown(
				$this->getTitle( 'proposed' ),
				$this->msg( 'mwoauthmanagemygrants-showlist' )->escaped() );
		} else {
			$listLinks[] = $this->msg( 'mwoauthmanagemygrants-showlist' )->escaped();
		}

		$linkHtml = $this->getLanguage()->pipeList( $listLinks );

		$this->getOutput()->setSubtitle(
			"<strong>" . $this->msg( 'mwoauthmanagemygrants-navigation' )->escaped() .
			"</strong> [{$linkHtml}]" );
	}

	/**
	 * Show the form to approve/reject/disable/re-enable consumers
	 *
	 * @param string $acceptanceId
	 * @return void
	 */
	protected function handleConsumerForm( $acceptanceId ) {
		$user = $this->getUser();
		$lang = $this->getLanguage();
		$db = MWOAuthUtils::getCentralDB( DB_SLAVE );

		$cmra = MWOAuthDAOAccessControl::wrap(
			MWOAuthConsumerAcceptance::newFromId( $db, $acceptanceId ), $this->getContext() );
		if ( !$cmra ) {
			$this->getOutput()->addHtml( $this->msg( 'mwoauth-invalid-access-token' )->escaped() );
			return;
		}

		$cmr = MWOAuthDAOAccessControl::wrap(
			MWOAuthConsumer::newFromId( $db, $cmra->get( 'consumerId' ) ), $this->getContext() );
		if ( $cmr->get( 'deleted' ) && !$user->isAllowed( 'mwoauthviewsuppressed' ) ) {
			throw new PermissionsError( 'mwoauthviewsuppressed' );
		}

		$form = new HTMLForm(
			array(
				'name' => array(
					'type' => 'info',
					'label-message' => 'mwoauth-consumer-name',
					'default' => $cmr->get( 'name' )
				),
				'user' => array(
					'type' => 'info',
					'label-message' => 'mwoauth-consumer-user',
					'default' => $cmr->get( 'userId', 'MWOAuthUtils::getCentralUserNameFromId' )
				),
				'version' => array(
					'type' => 'info',
					'label-message' => 'mwoauth-consumer-version',
					'default' => $cmr->get( 'version' )
				),
				'consumerKey' => array(
					'type' => 'info',
					'label-message' => 'mwoauth-consumer-key',
					'default' => $cmr->get( 'consumerKey' )
				),
				'description' => array(
					'type' => 'textarea',
					'label-message' => 'mwoauth-consumer-description',
					'default' => $cmr->get( 'description' ),
					'readonly' => true,
					'rows' => 5
				),
				'grants'  => array(
					'type' => 'checkmatrix',
					'label-message' => 'mwoauthmanagemygrants-applicablegrantsallowed',
					'columns' => array(
						$this->msg( 'mwoauthmanagemygrants-grantaccept' )->escaped() => 'grant'
					),
					'rows' => array_combine(
						array_map( 'htmlspecialchars',
							MWOAuthUtils::grantNames( $cmr->get( 'grants' ) ) ),
						$cmr->get( 'grants' )
					),
					'default' => array_map(
						function( $g ) { return "grant-$g"; },
						$cmra->get( 'grants' )
					),
					'tooltips' => array_combine(
						array_map( 'MWOAuthUtils::grantName', MWOAuthUtils::getValidGrants() ),
						array_map(
							function( $rights ) use ( $lang ) {
								return $lang->semicolonList( array_map(
									'User::getRightDescription', $rights ) );
							},
							MWOAuthUtils::getRightsByGrant()
						)
					)
				),
				'usedOnWiki' => array(
					'type' => 'info',
					'label-message' => 'mwoauth-consumer-wiki',
					'default' => $cmr->get( 'wiki' )
				),
				'wiki' => array(
					'type' => 'text',
					'label-message' => 'mwoauthmanagemygrants-wikiallowed',
					'default' => $cmra->get( 'wiki' )
				),
				'action' => array(
					'type' => 'radio',
					'label-message' => 'mwoauthmanagemygrants-action',
					'required' => true,
					'options' => array(
						$this->msg( 'mwoauthmanagemygrants-update' )->escaped() => 'update',
						$this->msg( 'mwoauthmanagemygrants-renounce' )->escaped() => 'renounce' )
				),
				'acceptanceId' => array(
					'type' => 'hidden',
					'default' => $cmra->get( 'id' )
				),
			),
			$this->getContext()
		);
		$act = null;
		$form->setSubmitCallback( function( array $data, IContextSource $context ) use ( &$act ) {
			$act = $data['action']; // this will be valid on success
			$data['grants'] = FormatJSON::encode( // adapt form to controller
				preg_replace( '/^grant-/', '', $data['grants'] ) );

			$dbw = MWOAuthUtils::getCentralDB( DB_MASTER );
			$controller = new MWOAuthConsumerAcceptanceSubmitControl( $context, $data, $dbw );
			return $controller->submit();
		} );

		$form->setWrapperLegendMsg( 'mwoauthmanagemygrants-confirm-legend' );
		$form->setSubmitTextMsg( 'mwoauthmanagemygrants-confirm-submit' );
		$form->addPreText(
			$this->msg( 'mwoauthmanagemygrants-confirm-text' )->parseAsBlock() );

		$status = $form->show();
		if ( $status instanceof Status && $status->isOk() ) {
			// Uses messages mwoauthmanagemygrants-success-update,
			// mwoauthmanagemygrants-success-renounce
			$this->getOutput()->addWikiMsg( "mwoauthmanagemygrants-success-$act" );
			$this->getOutput()->returnToMain();
		}
	}

	/**
	 * Show a paged list of consumers with links to details
	 *
	 * @return void
	 */
	protected function showConsumerList() {
		$centralUserId = MWOAuthUtils::getCentralIdFromLocalUser( $this->getUser() );
		$pager = new MWOAuthManageMyGrantsPager( $this, array(), $centralUserId );
		if ( $pager->getNumRows() ) {
			$this->getOutput()->addHTML( $pager->getNavigationBar() );
			$this->getOutput()->addHTML( $pager->getBody() );
			$this->getOutput()->addHTML( $pager->getNavigationBar() );
		} else {
			$this->getOutput()->addWikiMsg( "mwoauthmanagemygrants-none" );
		}
	}

	/**
	 * @param DBConnRef $db
	 * @param sdtclass $row
	 * @return string
	 */
	public function formatRow( DBConnRef $db, $row ) {
		$cmr = MWOAuthDAOAccessControl::wrap(
			MWOAuthConsumer::newFromRow( $db, $row ), $this->getContext() );
		$cmra = MWOAuthDAOAccessControl::wrap(
			MWOAuthConsumerAcceptance::newFromRow( $db, $row ), $this->getContext() );

		$cmrKey = $cmr->get( 'consumerKey' );
		$stageKey = self::$stageKeyMap[$cmr->get( 'stage' )];

		$link = Linker::linkKnown(
			$this->getTitle( 'manage/' . $cmra->get( 'id' ) ),
			$this->msg( 'mwoauthmanagemygrants-review' )->escaped()
		);

		$time = $this->getLanguage()->timeanddate(
			wfTimestamp( TS_MW, $cmr->get( 'registration' ) ), true );

		$encStageKey = htmlspecialchars( $stageKey ); // sanity
		$r = "<li class='mw-mwoauthmanagemygrants-{$encStageKey}'>";

		$r .= $time . " (<strong>{$link}</strong>)";

		$lang = $this->getLanguage();
		$data = array(
			'mwoauthmanagemygrants-name' =>
				$cmr->get( 'name', function( $s ) use ( $cmr ) {
					return $s . ' [' . $cmr->get( 'version' ) . ']'; } ),
			'mwoauthmanagemygrants-user' => $cmr->get( 'userId',
				'MWOAuthUtils::getCentralUserNameFromId' ),
			'mwoauthmanagemygrants-description' =>
				$cmr->get( 'description', function( $s ) use ( $lang ) {
					return $lang->truncate( $s, 10024 ); } ),
			'mwoauthmanagemygrants-wiki' => $cmr->get( 'wiki' ),
			'mwoauthmanagemygrants-wikiallowed' => $cmra->get( 'wiki' ),
			'mwoauthmanagemygrants-grants' => $lang->semicolonList(
				MWOAuthUtils::grantNames( $cmr->get( 'grants' ) ) ),
			'mwoauthmanagemygrants-grantsallowed' => $lang->semicolonList(
				MWOAuthUtils::grantNames( $cmra->get( 'grants' ) ) ),
			'mwoauthmanagemygrants-consumerkey' => $cmr->get( 'consumerKey' )
		);

		$r .= "<table class='mw-mwoauthmanagemygrants-body' " .
			"cellspacing='1' cellpadding='3' border='1' width='100%'>";
		foreach ( $data as $msg => $value ) {
			$r .= '<tr>' .
				'<td><strong>' . $this->msg( $msg )->escaped() . '</strong></td>' .
				'<td width=\'90%\'>' . htmlspecialchars( $value ) . '</td>' .
				'</tr>';
		}
		$r .= '</table>';
		$r .= '</li>';

		return $r;
	}
}

/**
 * Query to list out consumers that have an access token for this user
 *
 * @TODO: use UserCache
 */
class MWOAuthManageMyGrantsPager extends ReverseChronologicalPager {
	public $mForm, $mConds;

	function __construct( $form, $conds, $centralUserId ) {
		$this->mForm = $form;
		$this->mConds = $conds;
		$this->mConds[] = 'oaac_consumer_id = oarc_id';
		$this->mConds['oaac_user_id'] = $centralUserId;
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
	 * @return Title
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
			'tables' => array( 'oauth_accepted_consumer', 'oauth_registered_consumer' ),
			'fields' => array( '*' ),
			'conds'  => $this->mConds
		);
	}

	/**
	 * @return string
	 */
	function getIndexField() {
		return 'oaac_consumer_id';
	}
}
