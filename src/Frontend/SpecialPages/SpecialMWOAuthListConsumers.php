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

use LogEventsList;
use LogPage;
use MediaWiki\Extension\OAuth\Backend\Consumer;
use MediaWiki\Extension\OAuth\Backend\ConsumerAcceptance;
use MediaWiki\Extension\OAuth\Backend\Utils;
use MediaWiki\Extension\OAuth\Control\ConsumerAccessControl;
use MediaWiki\Extension\OAuth\Frontend\Pagers\ListConsumersPager;
use MediaWiki\Extension\OAuth\Frontend\UIUtils;
use MediaWiki\HTMLForm\HTMLForm;
use MediaWiki\Linker\LinkRenderer;
use MediaWiki\MediaWikiServices;
use MediaWiki\Permissions\GrantsLocalization;
use MediaWiki\SpecialPage\SpecialPage;
use MediaWiki\User\User;
use MediaWiki\WikiMap\WikiMap;
use MediaWiki\Xml\Xml;
use MWException;
use OOUI\HtmlSnippet;
use PermissionsError;
use stdClass;
use Wikimedia\Rdbms\IDatabase;

/**
 * Special page for listing the queue of consumer requests and managing
 * their approval/rejection and also for listing approved/disabled consumers
 */
class SpecialMWOAuthListConsumers extends SpecialPage {
	/** @var GrantsLocalization */
	private $grantsLocalization;

	/**
	 * @param GrantsLocalization $grantsLocalization
	 */
	public function __construct( GrantsLocalization $grantsLocalization ) {
		parent::__construct( 'OAuthListConsumers' );
		$this->grantsLocalization = $grantsLocalization;
	}

	public function execute( $par ) {
		$this->setHeaders();
		$this->addHelpLink( 'Help:OAuth' );

		// Format is Special:OAuthListConsumers[/list|/view/[<consumer key>]]
		$navigation = $par !== null ? explode( '/', $par ) : [];
		$type = $navigation[0] ?? null;
		$consumerKey = $navigation[1] ?? '';

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
	 * @throws PermissionsError
	 */
	protected function showConsumerInfo( $consumerKey ) {
		$user = $this->getUser();
		$out = $this->getOutput();

		if ( !$consumerKey ) {
			$out->addWikiMsg( 'mwoauth-missing-consumer-key' );
		}

		$dbr = Utils::getCentralDB( DB_REPLICA );
		$cmrAc = ConsumerAccessControl::wrap(
			Consumer::newFromKey( $dbr, $consumerKey ), $this->getContext() );
		$permissionManager = MediaWikiServices::getInstance()->getPermissionManager();

		if ( !$cmrAc ) {
			$out->addWikiMsg( 'mwoauth-invalid-consumer-key' );
			return;
		} elseif ( $cmrAc->getDeleted()
			&& !$permissionManager->userHasRight( $user, 'mwoauthviewsuppressed' ) ) {
			throw new PermissionsError( 'mwoauthviewsuppressed' );
		}

		$grants = $cmrAc->getGrants();
		if ( $grants === [ 'mwoauth-authonly' ] || $grants === [ 'mwoauth-authonlyprivate' ] ) {
			$s = $this->msg( 'grant-' . $grants[0] )->plain();
		} elseif ( $grants === [ 'basic' ] ) {
			$s = $this->msg( 'mwoauthlistconsumers-basicgrantsonly' )->plain();
		} else {
			$s = $this->grantsLocalization->getGrantsWikiText( $grants, $this->getLanguage() );
		}

		$stageKey = Consumer::$stageNames[$cmrAc->getDAO()->getStage()];
		$data = [
			'mwoauthlistconsumers-name' => $cmrAc->getName(),
			'mwoauthlistconsumers-version' => $cmrAc->getVersion(),
			'mwoauth-oauth-version' => $cmrAc->getOAuthVersion() === Consumer::OAUTH_VERSION_2
				? $this->msg( 'mwoauth-oauth-version-2' )
				: $this->msg( 'mwoauth-oauth-version-1' ),
			'mwoauthlistconsumers-user' => $cmrAc->getUserName(),
			'mwoauthlistconsumers-status' => $this->msg( "mwoauthlistconsumers-status-$stageKey" ),
			'mwoauthlistconsumers-description' => $cmrAc->getDescription(),
			'mwoauthlistconsumers-owner-only' => $cmrAc->getDAO()->getOwnerOnly()
				? $this->msg( 'htmlform-yes' ) : $this->msg( 'htmlform-no' ),
			'mwoauthlistconsumers-wiki' => $cmrAc->getWikiName(),
			'mwoauthlistconsumers-callbackurl' => $cmrAc->getCallbackUrl(),
			'mwoauthlistconsumers-callbackisprefix' => $cmrAc->getCallbackIsPrefix() ?
				$this->msg( 'htmlform-yes' ) : $this->msg( 'htmlform-no' ),
			'mwoauthlistconsumers-grants' => new HtmlSnippet( $out->parseInlineAsInterface( $s ) ),
		];
		if ( $cmrAc->getOAuthVersion() === Consumer::OAUTH_VERSION_2 ) {
			$data += [
				'mwoauthlistconsumers-oauth2-is-confidential' => $cmrAc->isConfidential() ?
					$this->msg( 'htmlform-yes' ) : $this->msg( 'htmlform-no' ),
			];
		}

		$out->addHTML( UIUtils::generateInfoTable( $data, $this->getContext() ) );

		$rcLink = $this->getLinkRenderer()->makeKnownLink(
			SpecialPage::getTitleFor( 'Recentchanges' ),
			$this->msg( 'mwoauthlistconsumers-rclink' )->plain(),
			[],
			[ 'tagfilter' => Utils::getTagName( $cmrAc->getId() ) ]
		);
		$out->addHTML( "<p>$rcLink</p>" );

		$this->addNavigationSubtitle( $cmrAc );

		if ( Utils::isCentralWiki() ) {
			// Show all of the status updates
			$logPage = new LogPage( 'mwoauthconsumer' );
			$out->addHTML( Xml::element( 'h2', null, $logPage->getName()->text() ) );
			LogEventsList::showLogExtract( $out, 'mwoauthconsumer', '', '', [
				'conds' => [
					'ls_field' => 'OAuthConsumer',
					'ls_value' => $cmrAc->getConsumerKey(),
				],
				'flags' => LogEventsList::NO_EXTRA_USER_LINKS,
			] );
		}
	}

	/**
	 * Show a form for the paged list of consumers
	 */
	protected function showConsumerListForm() {
		$form = HTMLForm::factory( 'ooui',
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
							=> Consumer::STAGE_PROPOSED,
						$this->msg( 'mwoauth-consumer-stage-approved' )->escaped()
							=> Consumer::STAGE_APPROVED,
						$this->msg( 'mwoauth-consumer-stage-rejected' )->escaped()
							=> Consumer::STAGE_REJECTED,
						$this->msg( 'mwoauth-consumer-stage-disabled' )->escaped()
							=> Consumer::STAGE_DISABLED,
						$this->msg( 'mwoauth-consumer-stage-expired' )->escaped()
							=> Consumer::STAGE_EXPIRED
					],
					'default'  => Consumer::STAGE_APPROVED,
					'required' => false
				]
			],
			$this->getContext()
		);
		// always go back to listings
		$form->setAction( $this->getPageTitle()->getFullURL() );
		$form->setSubmitCallback( static function () {
			return false;
		} );
		$form->setMethod( 'get' );
		$form->setSubmitTextMsg( 'go' );
		$form->setWrapperLegendMsg( 'mwoauthlistconsumers-legend' );
		$form->show();
	}

	/**
	 * Show a paged list of consumers with links to details
	 */
	protected function showConsumerList() {
		$request = $this->getRequest();

		$name = $request->getVal( 'name', '' );
		$stage = $request->getInt( 'stage', Consumer::STAGE_APPROVED );
		if ( $request->getVal( 'publisher', '' ) !== '' ) {
			$centralId = Utils::getCentralIdFromUserName( $request->getVal( 'publisher' ) );
		} else {
			$centralId = null;
		}

		$pager = new ListConsumersPager( $this, [], $name, $centralId, $stage );
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
			Consumer::newFromRow( $db, $row ), $this->getContext() );
		$cmrKey = $cmrAc->getConsumerKey();
		$stageKey = Consumer::$stageNames[$cmrAc->getStage()];
		$permMgr = MediaWikiServices::getInstance()->getPermissionManager();

		$links = [];
		$links[] = $this->getLinkRenderer()->makeKnownLink(
			$this->getPageTitle( "view/{$cmrKey}" ),
			$this->msg( 'mwoauthlistconsumers-view' )->text(),
			[],
			$this->getRequest()->getValues( 'name', 'publisher', 'stage' )
		);
		if ( $permMgr->userHasRight( $this->getUser(), 'mwoauthmanageconsumer' ) ) {
			$links[] = $this->getLinkRenderer()->makeKnownLink(
				SpecialPage::getTitleFor( 'OAuthManageConsumers', $cmrKey ),
				$this->msg( 'mwoauthmanageconsumers-review' )->text()
			);
		}
		$links = $this->getLanguage()->pipeList( $links );

		$encStageKey = htmlspecialchars( $stageKey );
		$r = "<li class=\"mw-mwoauthlistconsumers-{$encStageKey}\">";

		$name = $cmrAc->getNameAndVersion();
		$r .= '<strong>' . $cmrAc->escapeForHtml( $name ) . '</strong> ' . $this->msg( 'parentheses' )
				->rawParams( "<strong>{$links}</strong>" )->escaped();

		$lang = $this->getLanguage();
		$data = [
			'mwoauth-oauth-version' => $cmrAc->escapeForHtml(
				$cmrAc->getOAuthVersion() === Consumer::OAUTH_VERSION_2
					? $this->msg( 'mwoauth-oauth-version-2' )
					: $this->msg( 'mwoauth-oauth-version-1' )
			),
			'mwoauthlistconsumers-user' => $cmrAc->escapeForHtml( $cmrAc->getUserName() ),
			'mwoauthlistconsumers-description' => $cmrAc->escapeForHtml(
				$cmrAc->get( 'description', static function ( $s ) use ( $lang ) {
					return $lang->truncateForVisual( $s, 10024 );
				} )
			),
			'mwoauthlistconsumers-wiki' => $cmrAc->escapeForHtml( $cmrAc->getWikiName() ),
			'mwoauthlistconsumers-status' =>
				$this->msg( "mwoauthlistconsumers-status-$stageKey" )->escaped(),
		];
		if ( $cmrAc->getDAO()->getOwnerOnly() ) {
			$data = wfArrayInsertAfter( $data, [
				'mwoauthlistconsumers-owner-only' => $this->msg( 'htmlform-yes' ),
			], 'mwoauthlistconsumers-description' );
		}

		foreach ( $data as $msg => $encValue ) {
			$r .= '<p>' . $this->msg( $msg )->escaped() . ': ' . $encValue . '</p>';
		}

		$rcLink = $this->getLinkRenderer()->makeKnownLink(
			SpecialPage::getTitleFor( 'Recentchanges' ),
			$this->msg( 'mwoauthlistconsumers-rclink' )->plain(),
			[],
			[ 'tagfilter' => Utils::getTagName( $cmrAc->getId() ) ]
		);
		$r .= '<p>' . $rcLink . '</p>';

		$r .= '</li>';

		return $r;
	}

	protected function getGroupName() {
		return 'users';
	}

	/**
	 * @param ConsumerAccessControl $cmrAc
	 * @throws MWException
	 */
	private function addNavigationSubtitle( ConsumerAccessControl $cmrAc ): void {
		$user = $this->getUser();
		$centralUserId = Utils::getCentralIdFromLocalUser( $user );
		$linkRenderer = $this->getLinkRenderer();
		$consumer = $cmrAc->getDAO();

		$siteLinks = array_merge(
			$this->updateLink( $cmrAc, $centralUserId, $linkRenderer ),
			$this->manageConsumerLink( $consumer, $user, $linkRenderer ),
			$this->manageMyGrantsLink( $consumer, $centralUserId, $linkRenderer )
		);

		if ( $siteLinks ) {
			$links = $this->getLanguage()->pipeList( $siteLinks );
			$this->getOutput()->setSubtitle(
				"<strong>" . $this->msg( 'mwoauthlistconsumers-navigation' )->escaped() .
				"</strong> [{$links}]" );
		}
	}

	/**
	 * @param ConsumerAccessControl $cmrAc
	 * @param int $centralUserId Add update link for this user id, if they can update the consumer
	 * @param LinkRenderer $linkRenderer
	 * @return string[]
	 * @throws MWException
	 */
	private function updateLink(
		ConsumerAccessControl $cmrAc, $centralUserId,
		LinkRenderer $linkRenderer
	): array {
		if ( Utils::isCentralWiki() && $cmrAc->getDAO()->getUserId() === $centralUserId ) {
			return [
				$linkRenderer->makeKnownLink( SpecialPage::getTitleFor( 'OAuthConsumerRegistration',
					'update/' . $cmrAc->getDAO()->getConsumerKey() ),
					$this->msg( 'mwoauthlistconsumers-update-link' )->text() )
			];
		}

		return [];
	}

	/**
	 * @param Consumer $consumer
	 * @param User $user
	 * @param LinkRenderer $linkRenderer
	 * @return string[]
	 * @throws MWException
	 */
	private function manageConsumerLink(
		Consumer $consumer, User $user, LinkRenderer $linkRenderer
	): array {
		$permMgr = MediaWikiServices::getInstance()->getPermissionManager();

		if ( Utils::isCentralWiki() && $permMgr->userHasRight( $user, 'mwoauthmanageconsumer' ) ) {
			return [
				$linkRenderer->makeKnownLink( SpecialPage::getTitleFor( 'OAuthManageConsumers',
					$consumer->getConsumerKey() ),
					$this->msg( 'mwoauthlistconsumers-manage-link' )->text() )
			];
		}

		return [];
	}

	/**
	 * @param Consumer $consumer
	 * @param int $centralUserId Add link to manage grants for this user, if they've granted this
	 * consumer
	 * @param LinkRenderer $linkRenderer
	 * @return string[]
	 * @throws MWException
	 */
	private function manageMyGrantsLink(
		Consumer $consumer, $centralUserId, LinkRenderer $linkRenderer
	): array {
		$acceptance = $this->userGrantedAcceptance( $consumer, $centralUserId );
		if ( $acceptance !== false ) {
			return [
				$linkRenderer->makeKnownLink( SpecialPage::getTitleFor( 'OAuthManageMyGrants',
					'update/' . $acceptance->getId() ),
					$this->msg( 'mwoauthlistconsumers-grants-link' )->text() )
			];
		}

		return [];
	}

	/**
	 * @param Consumer $consumer
	 * @param int $centralUserId UserId to retrieve the grants for
	 * @return bool|ConsumerAcceptance
	 */
	private function userGrantedAcceptance( Consumer $consumer, $centralUserId ) {
		$dbr = Utils::getCentralDB( DB_REPLICA );
		$wikiSpecificGrant =
			ConsumerAcceptance::newFromUserConsumerWiki(
				$dbr, $centralUserId, $consumer, WikiMap::getCurrentWikiId() );

		$allWikiGrant = ConsumerAcceptance::newFromUserConsumerWiki(
			$dbr, $centralUserId, $consumer, '*' );

		if ( $wikiSpecificGrant !== false ) {
			return $wikiSpecificGrant;
		}
		if ( $allWikiGrant !== false ) {
			return $allWikiGrant;
		}
		return false;
	}
}
