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
 * Page that has registration request form and consumer update form
 */
class SpecialMWOAuthConsumerRegistration extends \SpecialPage {
	protected static $stageKeyMap = array(
		MWOAuthConsumer::STAGE_PROPOSED => 'proposed',
		MWOAuthConsumer::STAGE_REJECTED => 'rejected',
		MWOAuthConsumer::STAGE_EXPIRED  => 'expired',
		MWOAuthConsumer::STAGE_APPROVED => 'approved',
		MWOAuthConsumer::STAGE_DISABLED => 'disabled',
	);

	public function __construct() {
		parent::__construct( 'OAuthConsumerRegistration' );
	}

	public function execute( $par ) {
		global $wgMWOAuthSecureTokenTransfer, $wgMWOAuthReadOnly;

		$request = $this->getRequest();
		$user = $this->getUser();
		$lang = $this->getLanguage();
		$centralUserId = MWOAuthUtils::getCentralIdFromLocalUser( $user );

		// Redirect to HTTPs if attempting to access this page via HTTP.
		// Proposals and updates to consumers can involve sending new secrets.
		if ( $wgMWOAuthSecureTokenTransfer
			&& $request->detectProtocol() == 'http'
			&& substr( wfExpandUrl( '/', PROTO_HTTPS ), 0, 8 ) === 'https://'
		) {
			$redirUrl = str_replace( 'http://', 'https://', $request->getFullRequestURL() );
			$this->getOutput()->redirect( $redirUrl );
			$this->getOutput()->addVaryHeader( 'X-Forwarded-Proto' );
			return;
		}

		$this->setHeaders();
		$this->getOutput()->disallowUserJs();

		$block = $user->getBlock();
		if ( $block ) {
			throw new \UserBlockedError( $block );
		} elseif ( wfReadOnly() ) {
			throw new \ReadOnlyError();
		} elseif ( !$this->getUser()->isLoggedIn() ) {
			$this->getOutput()->addWikiMsg( 'mwoauthconsumerregistration-notloggedin' );
			return;
		}

		// Format is Special:OAuthConsumerRegistration[/propose|/list|/update/<consumer key>]
		$navigation = explode( '/', $par );
		$action = isset( $navigation[0] ) ? $navigation[0] : null;
		$consumerKey = isset( $navigation[1] ) ? $navigation[1] : null;

		if ( $wgMWOAuthReadOnly && $action !== 'list' ) {
			throw new \ErrorPageError( 'mwoauth-error', 'mwoauth-db-readonly' );
		}

		switch ( $action ) {
		case 'propose':
			if ( !$user->isAllowed( 'mwoauthproposeconsumer' ) ) {
				throw new \PermissionsError( 'mwoauthproposeconsumer' );
			}

			$allWikis = MWOAuthUtils::getAllWikiNames();

			// 'authonly' and 'authonlyprivate' are specially handled, don't
			// include them in the normal list of grants.
			$showGrants = array_diff(
				MWOAuthUtils::getValidGrants(),
				array( 'authonly', 'authonlyprivate' )
			);

			$dbw = MWOAuthUtils::getCentralDB( DB_MASTER ); // @TODO: lazy handle
			$control = new MWOAuthConsumerSubmitControl( $this->getContext(), array(), $dbw );
			$form = new \HTMLForm(
				$control->registerValidators( array(
					'name' => array(
						'type' => 'text',
						'label-message' => 'mwoauth-consumer-name',
						'size' => '45',
						'required' => true
					),
					'version' => array(
						'type' => 'text',
						'label-message' => 'mwoauth-consumer-version',
						'required' => true,
						'default' => "1.0"
					),
					'description' => array(
						'type' => 'textarea',
						'label-message' => 'mwoauth-consumer-description',
						'required' => true,
						'rows' => 5
					),
					'ownerOnly' => array(
						'type' => 'check',
						'label-message' => array( 'mwoauth-consumer-owner-only', $user->getName() ),
						'help-message' => array( 'mwoauth-consumer-owner-only-help', $user->getName() ),
					),
					'callbackUrl' => array(
						'type' => 'text',
						'label-message' => 'mwoauth-consumer-callbackurl',
						'required' => true,
						'hide-if' => array( '!==', 'ownerOnly', '' ),
					),
					'callbackIsPrefix' => array(
						'type' => 'check',
						'label-message' => 'mwoauth-consumer-callbackisprefix',
						'required' => true,
						'hide-if' => array( '!==', 'ownerOnly', '' ),
					),
					'email' => array(
						'type' => 'text',
						'label-message' => 'mwoauth-consumer-email',
						'required' => true
					),
					'wiki' => array(
						'type' => $allWikis ? 'autocompleteselect' : 'select',
						'options' => array(
							wfMessage( 'mwoauth-consumer-allwikis' )->escaped() => '*',
							wfMessage( 'mwoauth-consumer-wiki-thiswiki', wfWikiID() )->escaped() => wfWikiID()
						),
						'autocomplete' => array_flip( $allWikis ),
						'other' => wfMessage( 'mwoauth-consumer-wiki-other' )->escaped(),
						'label-message' => 'mwoauth-consumer-wiki',
						'required' => true,
						'default' => '*'
					),
					'granttype'  => array(
						'type' => 'radio',
						'options-messages' => array(
							'mwoauth-grant-authonly' => 'authonly',
							'mwoauth-grant-authonlyprivate' => 'authonlyprivate',
							'mwoauth-granttype-normal' => 'normal',
						),
						'label-message' => 'mwoauth-consumer-granttypes',
						'default' => 'normal',
					),
					'grants'  => array(
						'type' => 'checkmatrix',
						'label-message' => 'mwoauth-consumer-grantsneeded',
						'help-message' => 'mwoauth-consumer-grantshelp',
						'hide-if' => array( '!==', 'granttype', 'normal' ),
						'columns' => array(
							$this->msg( 'mwoauth-consumer-required-grant' )->escaped() => 'grant'
						),
						'rows' => array_combine(
							array_map( 'MediaWiki\Extensions\OAuth\MWOAuthUtils::getGrantsLink', $showGrants ),
							$showGrants
						),
						'tooltips' => array_combine(
							array_map( 'MediaWiki\Extensions\OAuth\MWOAuthUtils::grantName', $showGrants ),
							array_map(
								function( $rights ) use ( $lang ) {
									return $lang->semicolonList( array_map(
										'\User::getRightDescription', $rights ) );
								},
								array_intersect_key( MWOAuthUtils::getRightsByGrant(), array_flip( $showGrants ) )
							)
						),
						'force-options-on' => array_map(
							function( $g ) { return "grant-$g"; },
							MWOAuthUtils::getHiddenGrants()
						),
						'validation-callback' => null // different format
					),
					'restrictions' => array(
						'type' => 'textarea',
						'label-message' => 'mwoauth-consumer-restrictions-json',
						'required' => true,
						'default' => \FormatJSON::encode( MWOAuthConsumer::newRestrictions() ),
						'rows' => 5
					),
					'rsaKey' => array(
						'type' => 'textarea',
						'label-message' => 'mwoauth-consumer-rsakey',
						'required' => false,
						'default' => '',
						'rows' => 5
					),
					'agreement' => array(
						'type' => 'check',
						'label-message' => 'mwoauth-consumer-developer-agreement',
						'required' => true,
					),
					'action' => array(
						'type'    => 'hidden',
						'default' => 'propose'
					)
				) ),
				$this->getContext()
			);
			$form->setSubmitCallback(
				function( array $data, \IContextSource $context ) use ( $control ) {
					$data['grants'] = \FormatJSON::encode( // adapt form to controller
						preg_replace( '/^grant-/', '', $data['grants'] ) );

					$control->setInputParameters( $data );
					return $control->submit();
				}
			);
			$form->setWrapperLegendMsg( 'mwoauthconsumerregistration-propose-legend' );
			$form->setSubmitTextMsg( 'mwoauthconsumerregistration-propose-submit' );
			$form->addPreText(
				$this->msg( 'mwoauthconsumerregistration-propose-text' )->parseAsBlock() );

			$status = $form->show();
			if ( $status instanceof \Status && $status->isOk() ) {
				$cmr = $status->value['result']['consumer'];
				if ( $cmr->get( 'ownerOnly' ) ) {
					$cmra = $status->value['result']['acceptance'];
					$this->getOutput()->addWikiMsg( 'mwoauthconsumerregistration-created-owner-only',
						$cmr->get( 'consumerKey' ),
						MWOAuthUtils::hmacDBSecret( $cmr->get( 'secretKey' ) ),
						$cmra->get( 'accessToken' ),
						MWOAuthUtils::hmacDBSecret( $cmra->get( 'accessSecret' ) )
					);
				} else {
					$this->getOutput()->addWikiMsg( 'mwoauthconsumerregistration-proposed',
						$cmr->get( 'consumerKey' ),
						MWOAuthUtils::hmacDBSecret( $cmr->get( 'secretKey' ) ) );
				}
				$this->getOutput()->returnToMain();
			}
			break;
		case 'update':
			if ( !$user->isAllowed( 'mwoauthupdateownconsumer' ) ) {
				throw new \PermissionsError( 'mwoauthupdateownconsumer' );
			}

			$dbr = MWOAuthUtils::getCentralDB( DB_SLAVE );
			$cmr = MWOAuthDAOAccessControl::wrap(
				MWOAuthConsumer::newFromKey( $dbr, $consumerKey ), $this->getContext() );
			if ( !$cmr ) {
				$this->getOutput()->addWikiMsg( 'mwoauth-invalid-consumer-key' );
				break;
			} elseif ( $cmr->get( 'deleted' ) && !$user->isAllowed( 'mwoauthviewsuppressed' ) ) {
				throw new \PermissionsError( 'mwoauthviewsuppressed' );
			} elseif ( $cmr->get( 'userId' ) !== $centralUserId ) {
				// Do not show private information to other users
				$this->getOutput()->addWikiMsg( 'mwoauth-invalid-consumer-key' );
				break;
			}
			$oldSecretKey = $cmr->getDAO()->get( 'secretKey' );

			$dbw = MWOAuthUtils::getCentralDB( DB_MASTER ); // @TODO: lazy handle
			$control = new MWOAuthConsumerSubmitControl( $this->getContext(), array(), $dbw );
			$form = new \HTMLForm(
				$control->registerValidators( array(
					'nameShown' => array(
						'type' => 'info',
						'label-message' => 'mwoauth-consumer-name',
						'size' => '45',
						'default' => $cmr->get( 'name' )
					),
					'version' => array(
						'type' => 'info',
						'label-message' => 'mwoauth-consumer-version',
						'default' => $cmr->get( 'version' )
					),
					'consumerKeyShown' => array(
						'type' => 'info',
						'label-message' => 'mwoauth-consumer-key',
						'size' => '40',
						'default' => $cmr->get( 'consumerKey' )
					),
					'restrictions' => array(
						'type' => 'textarea',
						'label-message' => 'mwoauth-consumer-restrictions-json',
						'required' => true,
						'default' => \FormatJSON::encode( $cmr->getDAO()->get( 'restrictions' ) ),
						'rows' => 5
					),
					'resetSecret' => array(
						'type' => 'check',
						'label-message' => 'mwoauthconsumerregistration-resetsecretkey',
						'default' => false,
					),
					'rsaKey' => array(
						'type' => 'textarea',
						'label-message' => 'mwoauth-consumer-rsakey',
						'required' => false,
						'default' => $cmr->getDAO()->get( 'rsaKey' ),
						'rows' => 5
					),
					'reason' => array(
						'type' => 'text',
						'label-message' => 'mwoauth-consumer-reason',
						'required' => true
					),
					'consumerKey' => array(
						'type' => 'hidden',
						'default' => $cmr->get( 'consumerKey' )
					),
					'changeToken' => array(
						'type'    => 'hidden',
						'default' => $cmr->getDAO()->getChangeToken( $this->getContext() )
					),
					'action' => array(
						'type'    => 'hidden',
						'default' => 'update'
					)
				) ),
				$this->getContext()
			);
			$form->setSubmitCallback(
				function( array $data, \IContextSource $context ) use ( $control ) {
					$control->setInputParameters( $data );
					return $control->submit();
				}
			);
			$form->setWrapperLegendMsg( 'mwoauthconsumerregistration-update-legend' );
			$form->setSubmitTextMsg( 'mwoauthconsumerregistration-update-submit' );
			$form->addPreText(
				$this->msg( 'mwoauthconsumerregistration-update-text' )->parseAsBlock() );

			$status = $form->show();
			if ( $status instanceof \Status && $status->isOk() ) {
				$cmr = $status->value['result']['consumer'];
				$this->getOutput()->addWikiMsg( 'mwoauthconsumerregistration-updated' );
				$curSecretKey = $cmr->get( 'secretKey' );
				if ( $oldSecretKey !== $curSecretKey ) { // token reset?
					if ( $cmr->get( 'ownerOnly' ) ) {
						$cmra = $status->value['result']['acceptance'];
						$this->getOutput()->addWikiMsg( 'mwoauthconsumerregistration-secretreset-owner-only',
							$cmr->get( 'consumerKey' ),
							MWOAuthUtils::hmacDBSecret( $curSecretKey ),
							$cmra->get( 'accessToken' ),
							MWOAuthUtils::hmacDBSecret( $cmra->get( 'accessSecret' ) )
						);
					} else {
						$this->getOutput()->addWikiMsg( 'mwoauthconsumerregistration-secretreset',
							MWOAuthUtils::hmacDBSecret( $curSecretKey ) );
					}
				}
				$this->getOutput()->returnToMain();
			} else {
				$out = $this->getOutput();
				// Show all of the status updates
				$logPage = new \LogPage( 'mwoauthconsumer' );
				$out->addHTML( \Xml::element( 'h2', null, $logPage->getName()->text() ) );
				\LogEventsList::showLogExtract( $out, 'mwoauthconsumer', '', '',
					array(
						'conds'  => array( 'ls_field' => 'OAuthConsumer',
							'ls_value' => $cmr->get( 'consumerKey' ) ),
						'flags'  => \LogEventsList::NO_EXTRA_USER_LINKS
					)
				);
			}
			break;
		case 'list':
			$pager = new MWOAuthListMyConsumersPager( $this, array(), $centralUserId );
			if ( $pager->getNumRows() ) {
				$this->getOutput()->addHTML( $pager->getNavigationBar() );
				$this->getOutput()->addHTML( $pager->getBody() );
				$this->getOutput()->addHTML( $pager->getNavigationBar() );
			} else {
				$this->getOutput()->addWikiMsg( "mwoauthconsumerregistration-none" );
			}
			# Every 30th view, prune old deleted items
			if ( 0 == mt_rand( 0, 29 ) ) {
				MWOAuthUtils::runAutoMaintenance( MWOAuthUtils::getCentralDB( DB_MASTER ) );
			}
			break;
		default:
			$this->getOutput()->addWikiMsg( 'mwoauthconsumerregistration-maintext' );
		}

		$this->addSubtitleLinks( $action, $consumerKey );

		$this->getOutput()->addModuleStyles( 'ext.MWOAuth.BasicStyles' );
	}

	/**
	 * Show navigation links
	 *
	 * @param string $action
	 * @param string $consumerKey
	 * @return void
	 */
	protected function addSubtitleLinks( $action, $consumerKey ) {
		$listLinks = array();
		if ( $consumerKey || $action !== 'propose' ) {
			$listLinks[] = \Linker::linkKnown(
				$this->getPageTitle( 'propose' ),
				$this->msg( 'mwoauthconsumerregistration-propose' )->escaped() );
		} else {
			$listLinks[] = $this->msg( 'mwoauthconsumerregistration-propose' )->escaped();
		}
		if ( $consumerKey || $action !== 'list' ) {
			$listLinks[] = \Linker::linkKnown(
				$this->getPageTitle( 'list' ),
				$this->msg( 'mwoauthconsumerregistration-list' )->escaped() );
		} else {
			$listLinks[] = $this->msg( 'mwoauthconsumerregistration-list' )->escaped();
		}

		$linkHtml = $this->getLanguage()->pipeList( $listLinks );

		$viewall = $this->msg( 'parentheses' )->rawParams( \Linker::linkKnown(
			$this->getPageTitle(), $this->msg( 'mwoauthconsumerregistration-main' )->escaped() ) );

		$this->getOutput()->setSubtitle(
			"<strong>" . $this->msg( 'mwoauthconsumerregistration-navigation' )->escaped() .
			"</strong> [{$linkHtml}] <strong>{$viewall}</strong>" );
	}

	/**
	 * @param \DBConnRef $db
	 * @param sdtclass $row
	 * @return string
	 */
	public function formatRow( \DBConnRef $db, $row ) {
		$cmr = MWOAuthDAOAccessControl::wrap(
			MWOAuthConsumer::newFromRow( $db, $row ), $this->getContext() );

		$link = \Linker::linkKnown(
			$this->getPageTitle( 'update/' . $cmr->get( 'consumerKey' ) ),
			$this->msg( 'mwoauthconsumerregistration-manage' )->escaped()
		);

		$time = $this->getLanguage()->timeanddate(
			wfTimestamp( TS_MW, $cmr->get( 'registration' ) ), true );

		$stageKey = self::$stageKeyMap[$cmr->get( 'stage' )];
		$encStageKey = htmlspecialchars( $stageKey ); // sanity
		// Show last log entry (@TODO: title namespace?)
		// @TODO: inject DB
		$logHtml = '';
		\LogEventsList::showLogExtract( $logHtml, 'mwoauthconsumer', '', '',
			array(
				'conds'  => array(
					'ls_field' => 'OAuthConsumer', 'ls_value' => $cmr->get( 'consumerKey' ) ),
				'lim'    => 1,
				'flags'  => \LogEventsList::NO_EXTRA_USER_LINKS
			)
		);

		$lang = $this->getLanguage();
		$data = array(
			'mwoauthconsumerregistration-name' => htmlspecialchars(
				$cmr->get( 'name', function( $s ) use ( $cmr ) {
					return $s . ' [' . $cmr->get( 'version' ) . ']'; } )
			),
			// Messages: mwoauth-consumer-stage-proposed, mwoauth-consumer-stage-rejected,
			// mwoauth-consumer-stage-expired, mwoauth-consumer-stage-approved, mwoauth-consumer-stage-disabled
			'mwoauthconsumerregistration-stage' =>
				$this->msg( "mwoauth-consumer-stage-$stageKey" )->escaped(),
			'mwoauthconsumerregistration-description' => htmlspecialchars(
				$cmr->get( 'description', function( $s ) use ( $lang ) {
					return $lang->truncate( $s, 10024 ); } )
			),
			'mwoauthconsumerregistration-email' => htmlspecialchars(
				$cmr->get( 'email' ) ),
			'mwoauthconsumerregistration-consumerkey' => htmlspecialchars(
				$cmr->get( 'consumerKey' ) ),
			'mwoauthconsumerregistration-lastchange' => $logHtml
		);

		$r = "<li class='mw-mwoauthconsumerregistration-{$encStageKey}'>";
		$r .= "<span>$time (<strong>{$link}</strong>)</span>";
		$r .= "<table class='mw-mwoauthconsumerregistration-body' " .
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
class MWOAuthListMyConsumersPager extends \ReverseChronologicalPager {
	public $mForm, $mConds;

	function __construct( $form, $conds, $centralUserId ) {
		$this->mForm = $form;
		$this->mConds = $conds;
		$this->mConds['oarc_user_id'] = $centralUserId;
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
