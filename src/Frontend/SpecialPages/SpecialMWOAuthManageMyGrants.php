<?php

namespace MediaWiki\Extension\OAuth\Frontend\SpecialPages;

use ErrorPageError;
use MediaWiki\Context\IContextSource;
use MediaWiki\Extension\OAuth\Backend\Consumer;
use MediaWiki\Extension\OAuth\Backend\ConsumerAcceptance;
use MediaWiki\Extension\OAuth\Backend\Utils;
use MediaWiki\Extension\OAuth\Control\ConsumerAcceptanceAccessControl;
use MediaWiki\Extension\OAuth\Control\ConsumerAcceptanceSubmitControl;
use MediaWiki\Extension\OAuth\Control\ConsumerAccessControl;
use MediaWiki\Extension\OAuth\Frontend\Pagers\ManageMyGrantsPager;
use MediaWiki\Extension\OAuth\Frontend\UIUtils;
use MediaWiki\HTMLForm\HTMLForm;
use MediaWiki\Json\FormatJson;
use MediaWiki\MediaWikiServices;
use MediaWiki\Permissions\GrantsInfo;
use MediaWiki\Permissions\GrantsLocalization;
use MediaWiki\SpecialPage\SpecialPage;
use MediaWiki\Status\Status;
use PermissionsError;
use stdClass;
use Wikimedia\Rdbms\IDatabase;

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

/**
 * Special page for listing consumers this user granted access to and
 * for manage the specific grants given or revoking access for the consumer
 */
class SpecialMWOAuthManageMyGrants extends SpecialPage {
	/** @var string[]|null */
	private static $irrevocableGrants = null;

	/** @var GrantsInfo */
	private $grantsInfo;

	/** @var GrantsLocalization */
	private $grantsLocalization;

	/**
	 * @param GrantsInfo $grantsInfo
	 * @param GrantsLocalization $grantsLocalization
	 */
	public function __construct(
		GrantsInfo $grantsInfo,
		GrantsLocalization $grantsLocalization
	) {
		parent::__construct( 'OAuthManageMyGrants', 'mwoauthmanagemygrants' );
		$this->grantsInfo = $grantsInfo;
		$this->grantsLocalization = $grantsLocalization;
	}

	public function doesWrites() {
		return true;
	}

	public function execute( $par ) {
		$this->setHeaders();
		$this->getOutput()->disallowUserJs();
		$this->addHelpLink( 'Help:OAuth' );
		$this->requireNamedUser( 'mwoauth-available-only-to-registered' );

		$user = $this->getUser();
		$permissionManager = MediaWikiServices::getInstance()->getPermissionManager();
		if ( !$permissionManager->userHasRight( $user, 'mwoauthmanagemygrants' ) ) {
			throw new PermissionsError( 'mwoauthmanagemygrants' );
		}

		// Format is Special:OAuthManageMyGrants[/list|/manage/<accesstoken>]
		$navigation = $par !== null ? explode( '/', $par ) : [];
		$typeKey = $navigation[0] ?? null;
		$acceptanceId = $navigation[1] ?? null;

		if ( $this->getConfig()->get( 'MWOAuthReadOnly' )
				&& in_array( $typeKey, [ 'update', 'revoke' ] )
		) {
			throw new ErrorPageError( 'mwoauth-error', 'mwoauth-db-readonly' );
		}

		switch ( $typeKey ) {
			case 'update':
			case 'revoke':
				$this->handleConsumerForm( $acceptanceId ?? 0, $typeKey );
				break;
			default:
				$this->showConsumerList();
				break;
		}

		$this->addSubtitleLinks( $acceptanceId );

		$this->getOutput()->addModuleStyles( 'ext.MWOAuth.styles' );
	}

	/**
	 * Show navigation links
	 *
	 * @param string|null $acceptanceId
	 * @return void
	 */
	protected function addSubtitleLinks( $acceptanceId ) {
		$listLinks = [];

		if ( $acceptanceId ) {
			$dbr = Utils::getCentralDB( DB_REPLICA );
			$cmraAc = ConsumerAcceptance::newFromId( $dbr, (int)$acceptanceId );
			$listLinks[] = $this->getLinkRenderer()->makeKnownLink(
				$this->getPageTitle(),
				$this->msg( 'mwoauthmanagemygrants-showlist' )->text()
			);

			if ( $cmraAc ) {
				$cmrAc = Consumer::newFromId( $dbr, $cmraAc->getConsumerId() );
				$consumerKey = $cmrAc->getConsumerKey();
				$listLinks[] = $this->getLinkRenderer()->makeKnownLink(
					SpecialPage::getTitleFor( 'OAuthListConsumers', "view/$consumerKey" ),
					$this->msg( 'mwoauthconsumer-application-view' )->text()
				);
			}
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
	 * @param int $acceptanceId
	 * @param string $type One of (update,revoke)
	 * @throws PermissionsError
	 */
	protected function handleConsumerForm( $acceptanceId, $type ) {
		$user = $this->getUser();
		$dbr = Utils::getCentralDB( DB_REPLICA );
		$permissionManager = MediaWikiServices::getInstance()->getPermissionManager();

		$centralUserId = Utils::getCentralIdFromLocalUser( $user );
		if ( !$centralUserId ) {
			$this->getOutput()->addWikiMsg( 'badaccess-group0' );
			return;
		}

		$cmraAc = ConsumerAcceptanceAccessControl::wrap(
			ConsumerAcceptance::newFromId( $dbr, $acceptanceId ), $this->getContext() );
		if ( !$cmraAc || $cmraAc->getUserId() !== $centralUserId ) {
			$this->getOutput()->addHTML( $this->msg( 'mwoauth-invalid-access-token' )->escaped() );
			return;
		}

		$cmrAc = ConsumerAccessControl::wrap(
			Consumer::newFromId( $dbr, $cmraAc->getConsumerId() ), $this->getContext() );
		if ( $cmrAc->getDeleted()
			&& !$permissionManager->userHasRight( $user, 'mwoauthviewsuppressed' ) ) {
			throw new PermissionsError( 'mwoauthviewsuppressed' );
		}

		$action = '';
		if ( $this->getRequest()->getCheck( 'renounce' ) ) {
			$action = 'renounce';
		} elseif ( $this->getRequest()->getCheck( 'update' ) ) {
			$action = 'update';
		}

		$data = [ 'action' => $action ];
		$control = new ConsumerAcceptanceSubmitControl(
			$this->getContext(), $data, $dbr, $cmraAc->getDAO()->getOAuthVersion()
		);
		$form = HTMLForm::factory( 'ooui',
			$control->registerValidators( [
				'info' => [
					'type' => 'info',
					'raw' => true,
					'default' => UIUtils::generateInfoTable( [
						'mwoauth-consumer-name' => $cmrAc->getNameAndVersion(),
						'mwoauth-consumer-user' => $cmrAc->getUserName(),
						'mwoauth-consumer-description' => $cmrAc->getDescription(),
						'mwoauthmanagemygrants-wikiallowed' => $cmraAc->getWikiName(),
					], $this->getContext() ),
				],
				'grants'  => [
					'type' => 'checkmatrix',
					'label-message' => 'mwoauthmanagemygrants-applicablegrantsallowed',
					'columns' => [
						$this->msg( 'mwoauthmanagemygrants-grantaccept' )->escaped() => 'grant'
					],
					'rows' => array_combine(
						array_map( [ $this->grantsLocalization, 'getGrantsLink' ], $cmrAc->getGrants() ),
						$cmrAc->getGrants()
					),
					'default' => array_map(
						static function ( $g ) {
							return "grant-$g";
						},
						$cmraAc->getGrants()
					),
					'tooltips-html' => [
						$this->grantsLocalization->getGrantsLink( 'basic' ) =>
							$this->msg( 'mwoauthmanagemygrants-basic-tooltip' )->parse(),
						$this->grantsLocalization->getGrantsLink( 'mwoauth-authonly' ) =>
							$this->msg( 'mwoauthmanagemygrants-authonly-tooltip' )->parse(),
						$this->grantsLocalization->getGrantsLink( 'mwoauth-authonlyprivate' ) =>
							$this->msg( 'mwoauthmanagemygrants-authonly-tooltip' )->parse(),
					],
					'force-options-on' => array_map(
						static function ( $g ) {
							return "grant-$g";
						},
						( $type === 'revoke' )
							? array_merge( $this->grantsInfo->getValidGrants(), self::irrevocableGrants() )
							: self::irrevocableGrants()
					),
					'validation-callback' => null
				],
				'acceptanceId' => [
					'type' => 'hidden',
					'default' => $cmraAc->getId(),
				]
			] ),
			$this->getContext()
		);
		$form->setSubmitCallback(
			static function ( array $data, IContextSource $context ) use ( $action, $cmraAc ) {
				$data['action'] = $action;
				// adapt form to controller
				$data['grants'] = FormatJson::encode(
					preg_replace( '/^grant-/', '', $data['grants'] )
				);

				$dbw = Utils::getCentralDB( DB_PRIMARY );
				$control = new ConsumerAcceptanceSubmitControl(
					$context, $data, $dbw, $cmraAc->getDAO()->getOAuthVersion()
				);
				return $control->submit();
			}
		);

		$form->setWrapperLegendMsg( 'mwoauthmanagemygrants-confirm-legend' );
		$form->suppressDefaultSubmit();
		if ( $type === 'revoke' ) {
			$form->addButton( [
				'name' => 'renounce',
				'value' => $this->msg( 'mwoauthmanagemygrants-renounce' )->text(),
				'flags' => [ 'primary', 'destructive' ],
			] );
		} else {
			$form->addButton( [
				'name' => 'update',
				'value' => $this->msg( 'mwoauthmanagemygrants-update' )->text(),
				'flags' => [ 'primary', 'progressive' ],
			] );
		}
		$form->addPreHtml(
			$this->msg( "mwoauthmanagemygrants-$type-text" )->parseAsBlock() );

		$status = $form->show();
		if ( $status instanceof Status && $status->isOK() ) {
			// Messages: mwoauthmanagemygrants-success-update, mwoauthmanagemygrants-success-renounce
			$this->getOutput()->addWikiMsg( "mwoauthmanagemygrants-success-$action" );
		}
	}

	/**
	 * Show a paged list of consumers with links to details
	 *
	 * @return void
	 */
	protected function showConsumerList() {
		$this->getOutput()->addWikiMsg( 'mwoauthmanagemygrants-text' );

		$centralUserId = Utils::getCentralIdFromLocalUser( $this->getUser() );
		$pager = new ManageMyGrantsPager( $this, [], $centralUserId );
		if ( $pager->getNumRows() ) {
			$this->getOutput()->addHTML( $pager->getNavigationBar() );
			$this->getOutput()->addHTML( $pager->getBody() );
			$this->getOutput()->addHTML( $pager->getNavigationBar() );
		} else {
			$this->getOutput()->addWikiMsg( "mwoauthmanagemygrants-none" );
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
		$cmraAc = ConsumerAcceptanceAccessControl::wrap(
			ConsumerAcceptance::newFromRow( $db, $row ), $this->getContext() );

		$linkRenderer = $this->getLinkRenderer();

		$links = [];
		if ( array_diff( $cmrAc->getGrants(), self::irrevocableGrants() ) ) {
			$links[] = $linkRenderer->makeKnownLink(
				$this->getPageTitle( 'update/' . $cmraAc->getId() ),
				$this->msg( 'mwoauthmanagemygrants-review' )->text()
			);
		}
		$links[] = $linkRenderer->makeKnownLink(
			$this->getPageTitle( 'revoke/' . $cmraAc->getId() ),
			$this->msg( 'mwoauthmanagemygrants-revoke' )->text()
		);
		$reviewLinks = $this->getLanguage()->pipeList( $links );

		$encName = $cmrAc->escapeForHtml( $cmrAc->getNameAndVersion() );

		$r = '<li class="mw-mwoauthmanagemygrants-list-item">';
		$r .= "<strong dir='ltr'>{$encName}</strong> (<strong>$reviewLinks</strong>)";
		$data = [
			'mwoauthmanagemygrants-user' => $cmrAc->getUserName(),
			'mwoauthmanagemygrants-wikiallowed' => $cmraAc->getWikiName(),
		];

		foreach ( $data as $msg => $val ) {
			$r .= '<p>' . $this->msg( $msg )->escaped() . ' ' . $cmrAc->escapeForHtml( $val ) . '</p>';
		}

		$editsLink = $linkRenderer->makeKnownLink(
			SpecialPage::getTitleFor( 'Contributions', $this->getUser()->getName() ),
			$this->msg( 'mwoauthmanagemygrants-editslink', $this->getUser()->getName() )->text(),
			[],
			[ 'tagfilter' => Utils::getTagName( $cmrAc->getId() ) ]
		);
		$r .= '<p>' . $editsLink . '</p>';
		$actionsLink = $linkRenderer->makeKnownLink(
			SpecialPage::getTitleFor( 'Log' ),
			$this->msg( 'mwoauthmanagemygrants-actionslink', $this->getUser()->getName() )->text(),
			[],
			[
				'user' => $this->getUser()->getName(),
				'tagfilter' => Utils::getTagName( $cmrAc->getId() ),
			]
		);
		$r .= '<p>' . $actionsLink . '</p>';

		$r .= '</li>';

		return $r;
	}

	private static function irrevocableGrants() {
		if ( self::$irrevocableGrants === null ) {
			self::$irrevocableGrants = array_merge(
				MediaWikiServices::getInstance()->getGrantsInfo()->getHiddenGrants(),
				[ 'mwoauth-authonly', 'mwoauth-authonlyprivate' ]
			);
		}
		return self::$irrevocableGrants;
	}

	protected function getGroupName() {
		return 'login';
	}
}
