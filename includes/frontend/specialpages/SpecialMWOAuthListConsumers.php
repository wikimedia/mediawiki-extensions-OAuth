<?php

namespace MediaWiki\Extensions\OAuth;

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

use Html;
use OOUI\HtmlSnippet;
use SpecialPage;
use Wikimedia\Rdbms\DBConnRef;

/**
 * Special page for listing the queue of consumer requests and managing
 * their approval/rejection and also for listing approved/disabled consumers
 */
class SpecialMWOAuthListConsumers extends \SpecialPage {
	public function __construct() {
		parent::__construct( 'OAuthListConsumers' );
	}

	public function execute( $par ) {
		$this->setHeaders();
		$this->addHelpLink( 'Help:OAuth' );

		// Format is Special:OAuthListConsumers[/list|/view/[<consumer key>]]
		$navigation = explode( '/', $par );
		$type = $navigation[0] ?? null;
		$consumerKey = $navigation[1] ?? null;

		$this->showConsumerListForm();

		switch ( $type ) {
		case 'view':
			$this->showConsumerInfo( $consumerKey );
			break;
		default:
			$this->showConsumerList();
			break;
		}

		$this->getOutput()->addModuleStyles( 'ext.MWOAuth.styles' );
	}

	/**
	 * Show the form to approve/reject/disable/re-enable consumers
	 *
	 * @param string $consumerKey
	 * @throws \PermissionsError
	 */
	protected function showConsumerInfo( $consumerKey ) {
		$user = $this->getUser();
		$out = $this->getOutput();

		if ( !$consumerKey ) {
			$out->addWikiMsg( 'mwoauth-missing-consumer-key' );
		}

		$dbr = MWOAuthUtils::getCentralDB( DB_REPLICA );
		$cmrAc = MWOAuthConsumerAccessControl::wrap(
			MWOAuthConsumer::newFromKey( $dbr, $consumerKey ), $this->getContext() );
		if ( !$cmrAc ) {
			$out->addWikiMsg( 'mwoauth-invalid-consumer-key' );
			return;
		} elseif ( $cmrAc->getDeleted() && !$user->isAllowed( 'mwoauthviewsuppressed' ) ) {
			throw new \PermissionsError( 'mwoauthviewsuppressed' );
		}

		$grants = $cmrAc->getGrants();
		if ( $grants === [ 'mwoauth-authonly' ] || $grants === [ 'mwoauth-authonlyprivate' ] ) {
			$s = $this->msg( 'grant-' . $grants[0] )->plain() . "\n";
		} else {
			$s = \MWGrants::getGrantsWikiText( $grants, $this->getLanguage() );
			if ( $s == '' ) {
				$s = $this->msg( 'mwoauthlistconsumers-basicgrantsonly' )->plain();
			} else {
				$s .= "\n";
			}
		}

		$stageKey = MWOAuthConsumer::$stageNames[$cmrAc->getDAO()->getStage()];
		$data = [
			'mwoauthlistconsumers-name' => $cmrAc->getName(),
			'mwoauthlistconsumers-version' => $cmrAc->getVersion(),
			'mwoauth-oauth-version' => $cmrAc->getOAuthVersion(),
			'mwoauthlistconsumers-user' => $cmrAc->getUserName(),
			'mwoauthlistconsumers-status' => $this->msg( "mwoauthlistconsumers-status-$stageKey" ),
			'mwoauthlistconsumers-description' => $cmrAc->getDescription(),
			'mwoauthlistconsumers-wiki' => $cmrAc->getWikiName(),
			'mwoauthlistconsumers-callbackurl' => $cmrAc->getCallbackUrl(),
			'mwoauthlistconsumers-callbackisprefix' => $cmrAc->getCallbackIsPrefix() ?
				$this->msg( 'htmlform-yes' ) : $this->msg( 'htmlform-no' ),
		];

		if ( $grants !== [ 'basic' ] ) {
			$data[ 'mwoauthlistconsumers-grants' ] = new HtmlSnippet( $out->parseInlineAsInterface( $s ) );
		}

		$out->addHTML( MWOAuthUIUtils::generateInfoTable( $data, $this->getContext() ) );

		if ( MWOAuthUtils::isCentralWiki() ) {
			// Show all of the status updates
			$logPage = new \LogPage( 'mwoauthconsumer' );
			$out->addHTML( \Xml::element( 'h2', null, $logPage->getName()->text() ) );
			\LogEventsList::showLogExtract( $out, 'mwoauthconsumer', '', '', [
				'conds' => [
					'ls_field' => 'OAuthConsumer',
					'ls_value' => $cmrAc->getConsumerKey(),
				],
				'flags' => \LogEventsList::NO_EXTRA_USER_LINKS,
			] );
		}
	}

	/**
	 * Show a form for the paged list of consumers
	 */
	protected function showConsumerListForm() {
		$form = \HTMLForm::factory( 'ooui',
			[
				'name' => [
					'name'     => 'name',
					'type'     => 'text',
					'label-message' => 'mwoauth-consumer-name',
					'required' => false,
				],
				'publisher' => [
					'name'     => 'publisher',
					'type'     => 'text',
					'label-message' => 'mwoauth-consumer-user',
					'required' => false
				],
				'stage' => [
					'name'     => 'stage',
					'type'     => 'select',
					'label-message' => 'mwoauth-consumer-stage',
					'options'  => [
						$this->msg( 'mwoauth-consumer-stage-any' )->escaped() => -1,
						$this->msg( 'mwoauth-consumer-stage-proposed' )->escaped()
							=> MWOAuthConsumer::STAGE_PROPOSED,
						$this->msg( 'mwoauth-consumer-stage-approved' )->escaped()
							=> MWOAuthConsumer::STAGE_APPROVED,
						$this->msg( 'mwoauth-consumer-stage-rejected' )->escaped()
							=> MWOAuthConsumer::STAGE_REJECTED,
						$this->msg( 'mwoauth-consumer-stage-disabled' )->escaped()
							=> MWOAuthConsumer::STAGE_DISABLED,
						$this->msg( 'mwoauth-consumer-stage-expired' )->escaped()
							=> MWOAuthConsumer::STAGE_EXPIRED
					],
					'default'  => MWOAuthConsumer::STAGE_APPROVED,
					'required' => false
				]
			],
			$this->getContext()
		);
		$form->setAction( $this->getPageTitle()->getFullURL() ); // always go back to listings
		$form->setSubmitCallback( function () {
			return false;
		} );
		$form->setMethod( 'get' );
		$form->setSubmitTextMsg( 'go' );
		$form->setWrapperLegendMsg( 'mwoauthlistconsumers-legend' );
		$form->show();
	}

	/**
	 * Show a paged list of consumers with links to details
	 * @suppress SecurityCheck-XSS For getNavigationBar, see T201811 for more information
	 */
	protected function showConsumerList() {
		$request = $this->getRequest();

		$name = $request->getVal( 'name', '' );
		$stage = $request->getInt( 'stage', MWOAuthConsumer::STAGE_APPROVED );
		if ( $request->getVal( 'publisher', '' ) !== '' ) {
			$centralId = MWOAuthUtils::getCentralIdFromUserName( $request->getVal( 'publisher' ) );
		} else {
			$centralId = null;
		}

		$pager = new MWOAuthListConsumersPager( $this, [], $name, $centralId, $stage );
		if ( $pager->getNumRows() ) {
			$this->getOutput()->addHTML( $pager->getNavigationBar() );
			$this->getOutput()->addHTML( $pager->getBody() );
			$this->getOutput()->addHTML( $pager->getNavigationBar() );
		} else {
			// Messages: mwoauthlistconsumers-none-proposed, mwoauthlistconsumers-none-rejected,
			// mwoauthlistconsumers-none-expired, mwoauthlistconsumers-none-approved,
			// mwoauthlistconsumers-none-disabled
			$this->getOutput()->addWikiMsg( "mwoauthlistconsumers-none" );
		}
		# Every 30th view, prune old deleted items
		if ( 0 == mt_rand( 0, 29 ) ) {
			MWOAuthUtils::runAutoMaintenance( MWOAuthUtils::getCentralDB( DB_MASTER ) );
		}
	}

	/**
	 * @param DBConnRef $db
	 * @param \stdClass $row
	 * @return string
	 */
	public function formatRow( DBConnRef $db, $row ) {
		$cmrAc = MWOAuthConsumerAccessControl::wrap(
			MWOAuthConsumer::newFromRow( $db, $row ), $this->getContext() );

		$cmrKey = $cmrAc->getConsumerKey();
		$stageKey = MWOAuthConsumer::$stageNames[$cmrAc->getStage()];

		$links = [];
		$links[] = \Linker::linkKnown(
			$this->getPageTitle( "view/{$cmrKey}" ),
			$this->msg( 'mwoauthlistconsumers-view' )->escaped(),
			[],
			$this->getRequest()->getValues( 'name', 'publisher', 'stage' ) // stick
		);
		if ( $this->getUser()->isAllowed( 'mwoauthmanageconsumer' ) ) {
			$links[] = \Linker::linkKnown(
				\SpecialPage::getTitleFor( 'OAuthManageConsumers', $cmrKey ),
				$this->msg( 'mwoauthmanageconsumers-review' )->escaped()
			);
		}
		$links = $this->getLanguage()->pipeList( $links );

		$encStageKey = htmlspecialchars( $stageKey ); // sanity
		$r = "<li class=\"mw-mwoauthlistconsumers-{$encStageKey}\">";

		$name = $cmrAc->getNameAndVersion();
		$r .= '<strong>' . $cmrAc->escapeForHtml( $name ) . '</strong> ' . $this->msg( 'parentheses' )
				->rawParams( "<strong>{$links}</strong>" )->escaped();

		$lang = $this->getLanguage();
		$data = [
			'mwoauth-oauth-version' => $cmrAc->escapeForHtml( $cmrAc->getOAuthVersion() ),
			'mwoauthlistconsumers-user' => $cmrAc->escapeForHtml( $cmrAc->getUserName() ),
			'mwoauthlistconsumers-description' => $cmrAc->escapeForHtml(
				$cmrAc->get( 'description', function ( $s ) use ( $lang ) {
					return $lang->truncateForVisual( $s, 10024 );
				} )
			),
			'mwoauthlistconsumers-wiki' => $cmrAc->escapeForHtml( $cmrAc->getWikiName() ),
			'mwoauthlistconsumers-status' =>
				$this->msg( "mwoauthlistconsumers-status-$stageKey" )->escaped(),
		];

		foreach ( $data as $msg => $encValue ) {
			$r .= '<p>' . $this->msg( $msg )->escaped() . ': ' . $encValue . '</p>';
		}

		$rcUrl = SpecialPage::getTitleFor( 'Recentchanges' )
			->getFullURL( [ 'tagfilter' => MWOAuthUtils::getTagName( $cmrAc->getId() ) ] );
		$rcLink = Html::element( 'a', [ 'href' => $rcUrl ],
			$this->msg( 'mwoauthlistconsumers-rclink' )->plain() );
		$r .= '<p>' . $rcLink . '</p>';

		$r .= '</li>';

		return $r;
	}

	protected function getGroupName() {
		return 'users';
	}
}
