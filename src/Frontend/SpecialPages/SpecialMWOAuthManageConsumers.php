<?php

namespace MediaWiki\Extension\OAuth\Frontend\SpecialPages;

/**
 * (c) Aaron Schulz 2013, GPL
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License along
 * with this program; if not, write to the Free Software Foundation, Inc.,
 * 59 Temple Place - Suite 330, Boston, MA 02111-1307, USA.
 * http://www.gnu.org/copyleft/gpl.html
 */

use ErrorPageError;
use LogEventsList;
use LogPage;
use MediaWiki\Context\IContextSource;
use MediaWiki\Extension\OAuth\Backend\Consumer;
use MediaWiki\Extension\OAuth\Backend\Utils;
use MediaWiki\Extension\OAuth\Control\ConsumerAccessControl;
use MediaWiki\Extension\OAuth\Control\ConsumerSubmitControl;
use MediaWiki\Extension\OAuth\Entity\ClientEntity;
use MediaWiki\Extension\OAuth\Frontend\Pagers\ManageConsumersPager;
use MediaWiki\Extension\OAuth\Frontend\UIUtils;
use MediaWiki\Html\Html;
use MediaWiki\HTMLForm\HTMLForm;
use MediaWiki\MediaWikiServices;
use MediaWiki\Permissions\GrantsLocalization;
use MediaWiki\SpecialPage\SpecialPage;
use MediaWiki\Status\Status;
use MediaWiki\Title\Title;
use MediaWiki\Xml\Xml;
use MWRestrictions;
use OOUI\HtmlSnippet;
use PermissionsError;
use stdClass;
use Wikimedia\Rdbms\IDatabase;

/**
 * Special page for listing the queue of consumer requests and managing
 * their approval/rejection and also for listing approved/disabled consumers
 */
class SpecialMWOAuthManageConsumers extends SpecialPage {
	/** @var bool|int An Consumer::STAGE_* constant on queue/list subpages, false otherwise */
	protected $stage = false;
	/** @var string A stage key from Consumer::$stageNames */
	protected $stageKey;

	/**
	 * Stages which are shown in a queue (they are in an actionable state and can form a backlog)
	 * @var int[]
	 */
	public static $queueStages = [ Consumer::STAGE_PROPOSED,
		Consumer::STAGE_REJECTED, Consumer::STAGE_EXPIRED ];

	/**
	 * Stages which cannot form a backlog and are shown in a list
	 * @var int[]
	 */
	public static $listStages = [ Consumer::STAGE_APPROVED,
		Consumer::STAGE_DISABLED ];

	/** @var GrantsLocalization */
	private $grantsLocalization;

	/**
	 * @param GrantsLocalization $grantsLocalization
	 */
	public function __construct( GrantsLocalization $grantsLocalization ) {
		parent::__construct( 'OAuthManageConsumers', 'mwoauthmanageconsumer' );
		$this->grantsLocalization = $grantsLocalization;
	}

	public function doesWrites() {
		return true;
	}

	public function execute( $par ) {
		$user = $this->getUser();
		$permissionManager = MediaWikiServices::getInstance()->getPermissionManager();

		$this->setHeaders();
		$this->getOutput()->disallowUserJs();
		$this->addHelpLink( 'Help:OAuth' );
		$this->requireNamedUser( 'mwoauth-available-only-to-registered' );

		if ( !$permissionManager->userHasRight( $user, 'mwoauthmanageconsumer' ) ) {
			throw new PermissionsError( 'mwoauthmanageconsumer' );
		}

		if ( $this->getConfig()->get( 'MWOAuthReadOnly' ) ) {
			throw new ErrorPageError( 'mwoauth-error', 'mwoauth-db-readonly' );
		}

		// Format is Special:OAuthManageConsumers[/<stage>|/<consumer key>]
		// B/C format is Special:OAuthManageConsumers/<stage>/<consumer key>
		$consumerKey = null;
		$navigation = $par !== null ? explode( '/', $par ) : [];
		if ( count( $navigation ) === 2 ) {
			$this->stage = false;
			$consumerKey = $navigation[1];
		} elseif ( count( $navigation ) === 1 && $navigation[0] ) {
			$this->stage = array_search( $navigation[0], Consumer::$stageNames, true );
			if ( $this->stage !== false ) {
				$this->stageKey = $navigation[0];
			} else {
				$consumerKey = $navigation[0];
			}
		}

		if ( $consumerKey ) {
			$this->handleConsumerForm( $consumerKey );
		} elseif ( $this->stage !== false ) {
			$this->showConsumerList();
		} else {
			$this->showMainHub();
		}

		$this->addQueueSubtitleLinks( $consumerKey );

		$this->getOutput()->addModuleStyles( 'ext.MWOAuth.styles' );
	}

	/**
	 * Show other sub-queue links. Grey out the current one.
	 * When viewing a request, show them all and a link to current consumer view.
	 *
	 * @param string|null $consumerKey
	 * @return void
	 */
	protected function addQueueSubtitleLinks( $consumerKey ) {
		$linkRenderer = $this->getLinkRenderer();
		$listLinks = [];
		foreach ( self::$queueStages as $stage ) {
			$stageKey = Consumer::$stageNames[$stage];
			if ( $consumerKey || $this->stageKey !== $stageKey ) {
				$listLinks[] = $linkRenderer->makeKnownLink(
					$this->getPageTitle( $stageKey ),
					// Messages: mwoauthmanageconsumers-showproposed,
					// mwoauthmanageconsumers-showrejected, mwoauthmanageconsumers-showexpired,
					$this->msg( 'mwoauthmanageconsumers-show' . $stageKey )->text()
				);
			} else {
				$listLinks[] = $this->msg( 'mwoauthmanageconsumers-show' . $stageKey )->escaped();
			}
		}

		if ( $consumerKey ) {
			$consumerViewLink = "[" . $linkRenderer->makeKnownLink(
				SpecialPage::getTitleFor( 'OAuthListConsumers', "view/$consumerKey" ),
				$this->msg( 'mwoauthconsumer-consumer-view' )->text() ) . "]";
		} else {
			$consumerViewLink = '';
		}

		$linkHtml = $this->getLanguage()->pipeList( $listLinks );

		$viewall = $this->msg( 'parentheses' )->rawParams( $linkRenderer->makeKnownLink(
			$this->getPageTitle(),
			$this->msg( 'mwoauthmanageconsumers-main' )->text()
		) )->escaped();

		$this->getOutput()->setSubtitle(
			"<strong>" . $this->msg( 'mwoauthmanageconsumers-type' )->escaped() .
			"</strong> [{$linkHtml}] {$consumerViewLink} <strong>{$viewall}</strong>" );
	}

	/**
	 * Show the links to all the queues and how many requests are in each.
	 * Also show the list of enabled and disabled consumers and how many there are of each.
	 *
	 * @return void
	 */
	protected function showMainHub() {
		$keyStageMapQ = array_intersect( array_flip( Consumer::$stageNames ),
			self::$queueStages );
		$keyStageMapL = array_intersect( array_flip( Consumer::$stageNames ),
			self::$listStages );

		$linkRenderer = $this->getLinkRenderer();
		$out = $this->getOutput();

		$out->addWikiMsg( 'mwoauthmanageconsumers-maintext' );

		$counts = Utils::getConsumerStateCounts( Utils::getCentralDB( DB_REPLICA ) );

		$out->wrapWikiMsg( "<p><strong>$1</strong></p>", 'mwoauthmanageconsumers-queues' );
		$out->addHTML( '<ul>' );
		foreach ( $keyStageMapQ as $stageKey => $stage ) {
			$tag = ( $stage === Consumer::STAGE_EXPIRED ) ? 'i' : 'b';
			$out->addHTML(
				'<li>' .
				"<$tag>" .
				$linkRenderer->makeKnownLink(
					$this->getPageTitle( $stageKey ),
					// Messages: mwoauthmanageconsumers-q-proposed, mwoauthmanageconsumers-q-rejected,
					// mwoauthmanageconsumers-q-expired
					$this->msg( 'mwoauthmanageconsumers-q-' . $stageKey )->text()
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
				$linkRenderer->makeKnownLink(
					$this->getPageTitle( $stageKey ),
					// Messages: mwoauthmanageconsumers-l-approved, mwoauthmanageconsumers-l-disabled
					$this->msg( 'mwoauthmanageconsumers-l-' . $stageKey )->text()
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
	 * @throws PermissionsError
	 */
	protected function handleConsumerForm( $consumerKey ) {
		$user = $this->getUser();
		$dbr = Utils::getCentralDB( DB_REPLICA );
		$cmrAc = ConsumerAccessControl::wrap(
			Consumer::newFromKey( $dbr, $consumerKey ), $this->getContext() );
		$permissionManager = MediaWikiServices::getInstance()->getPermissionManager();

		if ( !$cmrAc ) {
			$this->getOutput()->addWikiMsg( 'mwoauth-invalid-consumer-key' );
			return;
		} elseif ( $cmrAc->getDeleted()
			&& !$permissionManager->userHasRight( $user, 'mwoauthviewsuppressed' ) ) {
			throw new PermissionsError( 'mwoauthviewsuppressed' );
		}
		$startingStage = $cmrAc->getStage();
		$pending = !in_array( $startingStage, [
			Consumer::STAGE_APPROVED, Consumer::STAGE_DISABLED ] );

		if ( $pending ) {
			$opts = [
				$this->msg( 'mwoauthmanageconsumers-approve' )->escaped() => 'approve',
				$this->msg( 'mwoauthmanageconsumers-reject' )->escaped()  => 'reject'
			];
			if ( $permissionManager->userHasRight( $this->getUser(), 'mwoauthsuppress' ) ) {
				$msg = $this->msg( 'mwoauthmanageconsumers-rsuppress' )->escaped();
				$opts["<strong>$msg</strong>"] = 'rsuppress';
			}
		} else {
			$opts = [
				$this->msg( 'mwoauthmanageconsumers-disable' )->escaped() => 'disable',
				$this->msg( 'mwoauthmanageconsumers-reenable' )->escaped()  => 'reenable'
			];
			if ( $permissionManager->userHasRight( $this->getUser(), 'mwoauthsuppress' ) ) {
				$msg = $this->msg( 'mwoauthmanageconsumers-dsuppress' )->escaped();
				$opts["<strong>$msg</strong>"] = 'dsuppress';
			}
		}

		$dbw = Utils::getCentralDB( DB_PRIMARY );
		$control = new ConsumerSubmitControl( $this->getContext(), [], $dbw );
		$form = HTMLForm::factory( 'ooui',
			$control->registerValidators( [
				'info' => [
					'type' => 'info',
					'raw' => true,
					'default' => UIUtils::generateInfoTable(
						$this->getInfoTableOptions( $cmrAc ),
						$this->getContext()
					),
				],
				'action' => [
					'type' => 'radio',
					'label-message' => 'mwoauthmanageconsumers-action',
					'required' => true,
					'options' => $opts,
					// no validate on GET
					'default' => '',
				],
				'reason' => [
					'type' => 'text',
					'label-message' => 'mwoauthmanageconsumers-reason',
					'required' => true,
				],
				'consumerKey' => [
					'type' => 'hidden',
					'default' => $cmrAc->getConsumerKey(),
				],
				'changeToken' => [
					'type' => 'hidden',
					'default' => $cmrAc->getDAO()->getChangeToken( $this->getContext() ),
				],
			] ),
			$this->getContext()
		);
		$form->setSubmitCallback(
			static function ( array $data, IContextSource $context ) use ( $control ) {
				$data['suppress'] = 0;
				if ( $data['action'] === 'dsuppress' ) {
					$data = [ 'action' => 'disable', 'suppress' => 1 ] + $data;
				} elseif ( $data['action'] === 'rsuppress' ) {
					$data = [ 'action' => 'reject', 'suppress' => 1 ] + $data;
				}
				$control->setInputParameters( $data );
				return $control->submit();
			}
		);

		$form->setWrapperLegendMsg( 'mwoauthmanageconsumers-confirm-legend' );
		$form->setSubmitTextMsg( 'mwoauthmanageconsumers-confirm-submit' );
		$form->addPreHtml(
			$this->msg( 'mwoauthmanageconsumers-confirm-text' )->parseAsBlock() );

		$status = $form->show();
		if ( $status instanceof Status && $status->isOK() ) {
			/** @var Consumer $cmr */
			// @phan-suppress-next-line PhanTypeArraySuspiciousNullable
			$cmr = $status->value['result'];
			'@phan-var Consumer $cmr';
			$oldStageKey = Consumer::$stageNames[$startingStage];
			$newStageKey = Consumer::$stageNames[$cmr->getStage()];
			// Messages: mwoauthmanageconsumers-success-approved, mwoauthmanageconsumers-success-rejected,
			// mwoauthmanageconsumers-success-disabled
			$this->getOutput()->addWikiMsg( "mwoauthmanageconsumers-success-$newStageKey" );
			$returnTo = Title::newFromText( 'Special:OAuthManageConsumers/' . $oldStageKey );
			$this->getOutput()->addReturnTo( $returnTo, [],
				// Messages: mwoauthmanageconsumers-linkproposed,
				// mwoauthmanageconsumers-linkrejected, mwoauthmanageconsumers-linkexpired,
				// mwoauthmanageconsumers-linkapproved, mwoauthmanageconsumers-linkdisabled
				$this->msg( 'mwoauthmanageconsumers-link' . $oldStageKey )->text() );
		} else {
			$out = $this->getOutput();
			// Show all of the status updates
			$logPage = new LogPage( 'mwoauthconsumer' );
			$out->addHTML( Xml::element( 'h2', null, $logPage->getName()->text() ) );
			LogEventsList::showLogExtract( $out, 'mwoauthconsumer', '', '', [
				'conds' => [
					'ls_field' => 'OAuthConsumer',
					'ls_value' => $cmrAc->getConsumerKey(),
				],
			] );
		}
	}

	/**
	 * @param ConsumerAccessControl $cmrAc
	 * @return array
	 */
	protected function getInfoTableOptions( $cmrAc ) {
		$owner = $cmrAc->getUserName();
		$lang = $this->getLanguage();

		$link = $this->getLinkRenderer()->makeKnownLink(
			$title = SpecialPage::getTitleFor( 'OAuthListConsumers' ),
			$this->msg( 'mwoauthmanageconsumers-search-publisher' )->text(),
			[],
			[ 'publisher' => $owner ]
		);
		$ownerLink = $cmrAc->escapeForHtml( $owner ) . ' ' .
			$this->msg( 'parentheses' )->rawParams( $link )->escaped();
		$ownerOnly = $cmrAc->getDAO()->getOwnerOnly();
		$restrictions = $cmrAc->getRestrictions();

		$options = [
			// Messages: mwoauth-consumer-stage-proposed, mwoauth-consumer-stage-rejected,
			// mwoauth-consumer-stage-expired, mwoauth-consumer-stage-approved,
			// mwoauth-consumer-stage-disabled
			'mwoauth-consumer-stage' => $cmrAc->getDeleted()
				? $this->msg( 'mwoauth-consumer-stage-suppressed' )
				: $this->msg( 'mwoauth-consumer-stage-' .
					Consumer::$stageNames[$cmrAc->getStage()] ),
			'mwoauth-consumer-key' => $cmrAc->getConsumerKey(),
			'mwoauth-consumer-name' => new HtmlSnippet( $cmrAc->get( 'name', function ( $s ) {
				$link = $this->getLinkRenderer()->makeKnownLink(
					SpecialPage::getTitleFor( 'OAuthListConsumers' ),
					$this->msg( 'mwoauthmanageconsumers-search-name' )->text(),
					[],
					[ 'name' => $s ]
				);
				return htmlspecialchars( $s ) . ' ' .
					$this->msg( 'parentheses' )->rawParams( $link )->escaped();
			} ) ),
			'mwoauth-consumer-version' => $cmrAc->getVersion(),
			'mwoauth-oauth-version' => $cmrAc->getOAuthVersion() === Consumer::OAUTH_VERSION_2
				? $this->msg( 'mwoauth-oauth-version-2' )
				: $this->msg( 'mwoauth-oauth-version-1' ),
			'mwoauth-consumer-user' => new HtmlSnippet( $ownerLink ),
			'mwoauth-consumer-description' => $cmrAc->getDescription(),
			'mwoauth-consumer-owner-only-label' => $ownerOnly ?
				$this->msg( 'mwoauth-consumer-owner-only', $owner ) : null,
			'mwoauth-consumer-callbackurl' => $ownerOnly ?
				null : $this->formatCallbackUrl( $cmrAc ),
			'mwoauth-consumer-callbackisprefix' => $ownerOnly ?
				null : ( $cmrAc->getCallbackIsPrefix() ?
					$this->msg( 'htmlform-yes' ) : $this->msg( 'htmlform-no' ) ),
			'mwoauth-consumer-grantsneeded' => $cmrAc->get( 'grants',
				function ( $grants ) use ( $lang ) {
					return $lang->semicolonList( $this->grantsLocalization->getGrantDescriptions( $grants, $lang ) );
				} ),
			'mwoauth-consumer-email' => $cmrAc->getEmail(),
			'mwoauth-consumer-wiki' => $cmrAc->getWiki()
		];

		// Add OAuth2 specific parameters
		if ( $cmrAc->getOAuthVersion() === Consumer::OAUTH_VERSION_2 ) {
			/** @var ClientEntity $consumer */
			$consumer = $cmrAc->getDAO();
			$options += [
				'mwoauth-oauth2-is-confidential' => $consumer->isConfidential() ?
					$this->msg( 'htmlform-yes' ) : $this->msg( 'htmlform-no' ),
				'mwoauth-oauth2-granttypes' => implode( ', ', array_map( function ( $grant ) {
					$map = [
						'authorization_code' => 'mwoauth-oauth2-granttype-auth-code',
						'refresh_token' => 'mwoauth-oauth2-granttype-refresh-token',
						'client_credentials' => 'mwoauth-oauth2-granttype-client-credentials'
					];
					return isset( $map[$grant] ) ? $this->msg( $map[$grant] ) : '';
				}, $consumer->getAllowedGrants() ) )
			];
		}

		// Add optional parameters
		$options += [
			'mwoauth-consumer-restrictions-json' => $restrictions instanceof MWRestrictions ?
				$restrictions->toJson( true ) : $restrictions,
			'mwoauth-consumer-rsakey' => $cmrAc->getRsaKey(),
		];

		return $options;
	}

	/**
	 * Format a callback URL. Usually this doesn't do anything nontrivial, but it adds a warning
	 * to callback URLs with a special meaning.
	 * @param ConsumerAccessControl $cmrAc
	 * @return HtmlSnippet|string Formatted callback URL, as a plaintext or HTML string
	 */
	protected function formatCallbackUrl( ConsumerAccessControl $cmrAc ) {
		$url = $cmrAc->getCallbackUrl();
		if ( $cmrAc->getDAO()->getCallbackIsPrefix() ) {
			$urlParts = wfParseUrl( $cmrAc->getDAO()->getCallbackUrl() );
			if ( ( $urlParts['port'] ?? null ) === 1 ) {
				$warning = Html::element( 'span', [ 'class' => 'warning' ],
					$this->msg( 'mwoauth-consumer-callbackurl-warning' )->text() );
				$url = new HtmlSnippet( $url . ' ' . $warning );
			}
		}
		return $url;
	}

	/**
	 * Show a paged list of consumers with links to details
	 */
	protected function showConsumerList() {
		$pager = new ManageConsumersPager( $this, [], $this->stage );
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
		if ( mt_rand( 0, 29 ) == 0 ) {
			Utils::runAutoMaintenance( Utils::getCentralDB( DB_PRIMARY ) );
		}
	}

	/**
	 * @param IDatabase $db
	 * @param stdClass $row
	 * @return string
	 */
	public function formatRow( IDatabase $db, $row ) {
		$cmrAc = ConsumerAccessControl::wrap(
			Consumer::newFromRow( $db, $row ), $this->getContext()
		);

		$cmrKey = $cmrAc->getConsumerKey();
		$stageKey = Consumer::$stageNames[$cmrAc->getStage()];

		$link = $this->getLinkRenderer()->makeKnownLink(
			$this->getPageTitle( $cmrKey ),
			$this->msg( 'mwoauthmanageconsumers-review' )->text()
		);

		$time = $this->getLanguage()->timeanddate(
			wfTimestamp( TS_MW, $cmrAc->getRegistration() ), true );

		$encStageKey = htmlspecialchars( $stageKey );
		$r = "<li class='mw-mwoauthmanageconsumers-{$encStageKey}'>";

		$r .= $time . " (<strong>{$link}</strong>)";

		// Show last log entry (@TODO: title namespace?)
		// @TODO: inject DB
		$logHtml = '';
		LogEventsList::showLogExtract( $logHtml, 'mwoauthconsumer', '', '', [
			'action' => Consumer::$stageActionNames[$cmrAc->getStage()],
			'conds'  => [
				'ls_field' => 'OAuthConsumer',
				'ls_value' => $cmrAc->getConsumerKey(),
			],
			'lim'    => 1,
			'flags'  => LogEventsList::NO_EXTRA_USER_LINKS,
		] );

		$lang = $this->getLanguage();
		$data = [
			'mwoauthmanageconsumers-name' => $cmrAc->escapeForHtml( $cmrAc->getNameAndVersion() ),
			'mwoauthmanageconsumers-user' => $cmrAc->escapeForHtml( $cmrAc->getUserName() ),
			'mwoauth-oauth-version' => $cmrAc->escapeForHtml(
				$cmrAc->getOAuthVersion() === Consumer::OAUTH_VERSION_2 ?
				$this->msg( 'mwoauth-oauth-version-2' ) :
				$this->msg( 'mwoauth-oauth-version-1' )
			),
			'mwoauthmanageconsumers-description' => $cmrAc->escapeForHtml(
				$cmrAc->get( 'description', static function ( $s ) use ( $lang ) {
					return $lang->truncateForVisual( $s, 10024 );
				} )
			),
			'mwoauthmanageconsumers-email' => $cmrAc->escapeForHtml( $cmrAc->getEmail() ),
			'mwoauthmanageconsumers-consumerkey' => $cmrAc->escapeForHtml( $cmrAc->getConsumerKey() ),
			'mwoauthmanageconsumers-lastchange' => $logHtml,
		];

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
