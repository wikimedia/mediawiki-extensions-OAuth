<?php

namespace MediaWiki\Extensions\OAuth\Frontend\SpecialPages;

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

use MediaWiki\Extensions\OAuth\Backend\Consumer;
use MediaWiki\Extensions\OAuth\Backend\Utils;
use MediaWiki\Extensions\OAuth\Control\ConsumerAccessControl;
use MediaWiki\Extensions\OAuth\Control\ConsumerSubmitControl;
use MediaWiki\Extensions\OAuth\Frontend\Pagers\ListMyConsumersPager;
use MediaWiki\Extensions\OAuth\Frontend\UIUtils;
use MediaWiki\MediaWikiServices;
use User;
use Wikimedia\Rdbms\DBConnRef;

/**
 * Page that has registration request form and consumer update form
 */
class SpecialMWOAuthConsumerRegistration extends \SpecialPage {

	public function __construct() {
		parent::__construct( 'OAuthConsumerRegistration' );
	}

	public function doesWrites() {
		return true;
	}

	public function userCanExecute( User $user ) {
		return $user->isEmailConfirmed();
	}

	public function displayRestrictionError() {
		throw new \PermissionsError( null, [ 'mwoauthconsumerregistration-need-emailconfirmed' ] );
	}

	public function execute( $par ) {
		global $wgMWOAuthSecureTokenTransfer, $wgMWOAuthReadOnly;
		$this->checkPermissions();

		$request = $this->getRequest();
		$user = $this->getUser();
		$lang = $this->getLanguage();
		$centralUserId = Utils::getCentralIdFromLocalUser( $user );

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
		$this->addHelpLink( 'Help:OAuth' );

		$block = $user->getBlock();
		if ( $block ) {
			throw new \UserBlockedError( $block );
		}
		$this->checkReadOnly();
		if ( !$this->getUser()->isLoggedIn() ) {
			throw new \UserNotLoggedIn();
		}

		// Format is Special:OAuthConsumerRegistration[/propose|/list|/update/<consumer key>]
		$navigation = explode( '/', $par );
		$action = $navigation[0] ?? null;
		$consumerKey = $navigation[1] ?? null;

		if ( $wgMWOAuthReadOnly && $action !== 'list' ) {
			throw new \ErrorPageError( 'mwoauth-error', 'mwoauth-db-readonly' );
		}

		switch ( $action ) {
		case 'propose':
			if ( !$user->isAllowed( 'mwoauthproposeconsumer' ) ) {
				throw new \PermissionsError( 'mwoauthproposeconsumer' );
			}

			$allWikis = Utils::getAllWikiNames();

			$showGrants = \MWGrants::getValidGrants();
			$config = MediaWikiServices::getInstance()->getConfigFactory()->makeConfig( 'mwoauth' );

			$dbw = Utils::getCentralDB( DB_MASTER ); // @TODO: lazy handle
			$control = new ConsumerSubmitControl( $this->getContext(), [], $dbw );
			$form = \HTMLForm::factory( 'ooui',
				$control->registerValidators( [
					'name' => [
						'type' => 'text',
						'label-message' => 'mwoauth-consumer-name',
						'size' => '45',
						'required' => true
					],
					'version' => [
						'type' => 'text',
						'label-message' => 'mwoauth-consumer-version',
						'required' => true,
						'default' => "1.0"
					],
					'oauthVersion' => [
						'type' => 'select',
						'label-message' => 'mwoauth-oauth-version',
						'options' => [
							$this->msg( 'mwoauth-oauth-version-1' )->escaped() =>
								Consumer::OAUTH_VERSION_1,
							$this->msg( 'mwoauth-oauth-version-2' )->escaped() =>
								Consumer::OAUTH_VERSION_2
						],
						'required' => true,
						'default' => Consumer::OAUTH_VERSION_1
					],
					'description' => [
						'type' => 'textarea',
						'label-message' => 'mwoauth-consumer-description',
						'required' => true,
						'rows' => 5
					],
					'ownerOnly' => [
						'type' => 'check',
						'label-message' => [ 'mwoauth-consumer-owner-only', $user->getName() ],
						'help-message' => [ 'mwoauth-consumer-owner-only-help', $user->getName() ],
					],
					'callbackUrl' => [
						'type' => 'text',
						'label-message' => 'mwoauth-consumer-callbackurl',
						'required' => true,
						'hide-if' => [ '!==', 'ownerOnly', '' ],
					],
					'callbackIsPrefix' => [
						'type' => 'check',
						'label-message' => 'mwoauth-consumer-callbackisprefix',
						'required' => true,
						'hide-if' => [ '!==', 'ownerOnly', '' ],
					],
					'email' => [
						'type' => 'text',
						'label-message' => 'mwoauth-consumer-email',
						'required' => true,
						'readonly' => true,
						'default' => $user->getEmail(),
						'help-message' => 'mwoauth-consumer-email-help',
					],
					'wiki' => [
						'type' => $allWikis ? 'combobox' : 'select',
						'options' => [
							$this->msg( 'mwoauth-consumer-allwikis' )->escaped() => '*',
							$this->msg( 'mwoauth-consumer-wiki-thiswiki', wfWikiID() )
								->escaped() => wfWikiID()
						] + array_flip( $allWikis ),
						'label-message' => 'mwoauth-consumer-wiki',
						'required' => true,
						'default' => '*'
					],
					'oauth2IsConfidential' => [
						'type' => 'check',
						'label-message' => 'mwoauth-oauth2-is-confidential',
						'help-message' => 'mwoauth-oauth2-is-confidential-help',
						'hide-if' => [ '!==', 'oauthVersion', (string)Consumer::OAUTH_VERSION_2 ],
						'default' => 1
					],
					'oauth2GrantTypes'  => [
						'type' => 'multiselect',
						'label-message' => 'mwoauth-oauth2-granttypes',
						'hide-if' => [ 'OR',
							[ '!==', 'oauthVersion', (string)Consumer::OAUTH_VERSION_2 ],
							[ '!==', 'ownerOnly', '' ]
						],
						'options' => array_filter( [
							$this->msg( 'mwoauth-oauth2-granttype-auth-code' )->escaped() =>
								'authorization_code',
							$this->msg( 'mwoauth-oauth2-granttype-refresh-token' )->escaped() =>
								'refresh_token',
							$this->msg( 'mwoauth-oauth2-granttype-client-credentials' )->escaped() =>
								'client_credentials',
						], function ( $grantType ) use ( $config ) {
							return in_array( $grantType, $config->get( 'OAuth2EnabledGrantTypes' ) );
						} ),
						'dropdown' => true,
						'required' => true,
						'default' => [ 'authorization_code', 'refresh_token' ]
					],
					'granttype'  => [
						'type' => 'radio',
						'options-messages' => [
							'grant-mwoauth-authonly' => 'authonly',
							'grant-mwoauth-authonlyprivate' => 'authonlyprivate',
							'mwoauth-granttype-normal' => 'normal',
						],
						'label-message' => 'mwoauth-consumer-granttypes',
						'default' => 'normal',
					],
					'grants'  => [
						'type' => 'checkmatrix',
						'label-message' => 'mwoauth-consumer-grantsneeded',
						'help-message' => 'mwoauth-consumer-grantshelp',
						'hide-if' => [ '!==', 'granttype', 'normal' ],
						'columns' => [
							$this->msg( 'mwoauth-consumer-required-grant' )->escaped() => 'grant'
						],
						'rows' => array_combine(
							array_map( 'MWGrants::getGrantsLink', $showGrants ),
							$showGrants
						),
						'tooltips' => array_combine(
							array_map( 'MWGrants::getGrantsLink', $showGrants ),
							array_map(
								function ( $rights ) use ( $lang ) {
									return $lang->semicolonList( array_map(
										'\User::getRightDescription', $rights ) );
								},
								array_intersect_key(
									\MWGrants::getRightsByGrant(), array_flip( $showGrants ) )
							)
						),
						'force-options-on' => array_map(
							function ( $g ) {
								return "grant-$g";
							},
							\MWGrants::getHiddenGrants()
						),
						'validation-callback' => null, // different format
					],
					'restrictions' => [
						'class' => 'HTMLRestrictionsField',
						'required' => true,
						'default' => \MWRestrictions::newDefault(),
					],
					'rsaKey' => [
						'type' => 'textarea',
						'label-message' => 'mwoauth-consumer-rsakey',
						'help-message' => 'mwoauth-consumer-rsakey-help',
						'required' => false,
						'default' => '',
						'rows' => 5,
						'hide-if' => [ '===', 'oauthVersion', (string)Consumer::OAUTH_VERSION_2 ]
					],
					'agreement' => [
						'type' => 'check',
						'label-message' => 'mwoauth-consumer-developer-agreement',
						'required' => true,
					],
					'action' => [
						'type'    => 'hidden',
						'default' => 'propose'
					]
				] ),
				$this->getContext()
			);
			$form->setSubmitCallback(
				function ( array $data, \IContextSource $context ) use ( $control ) {
					$data['grants'] = \FormatJson::encode( // adapt form to controller
						preg_replace( '/^grant-/', '', $data['grants'] ) );
					// 'callbackUrl' must be present,
					// otherwise MWOAuthSubmitControl::validateFields() fails.
					if ( $data['ownerOnly'] && !isset( $data['callbackUrl'] ) ) {
						$data['callbackUrl'] = '';
					}
					// Force all ownerOnly clients to use client_credentials
					if ( $data['ownerOnly'] ) {
						$data['oauth2GrantTypes'] = [ 'client_credentials' ];
					}

					$control->setInputParameters( $data );
					return $control->submit();
				}
			);
			$form->setWrapperLegendMsg( 'mwoauthconsumerregistration-propose-legend' );
			$form->setSubmitTextMsg( 'mwoauthconsumerregistration-propose-submit' );
			$form->addPreText(
				$this->msg( 'mwoauthconsumerregistration-propose-text' )->parseAsBlock() );

			$status = $form->show();
			if ( $status instanceof \Status && $status->isOK() ) {
				/** @var Consumer $cmr */
				// @phan-suppress-next-line PhanTypeArraySuspiciousNullable
				$cmr = $status->value['result']['consumer'];
				if ( $cmr->getOwnerOnly() ) {
					// @phan-suppress-next-line PhanTypeArraySuspiciousNullable
					$accessToken = $status->value['result']['accessToken'];
					if ( $cmr->getOAuthVersion() === Consumer::OAUTH_VERSION_2 ) {
						// If we just add raw AT to the page, it would go 3000px wide
						$accessToken = \Html::element( 'span', [
							'style' => 'overflow-wrap: break-word'
						], (string)$accessToken );

						$this->getOutput()->addWikiMsg(
							'mwoauthconsumerregistration-created-owner-only-oauth2',
							$cmr->getConsumerKey(),
							Utils::hmacDBSecret( $cmr->getSecretKey() ),
							\Message::rawParam( $accessToken )
						);
					} else {
						$this->getOutput()->addWikiMsg(
							'mwoauthconsumerregistration-created-owner-only',
							$cmr->getConsumerKey(),
							Utils::hmacDBSecret( $cmr->getSecretKey() ),
							$accessToken->key,
							Utils::hmacDBSecret( $accessToken->secret )
						);
					}
				} else {
					$this->getOutput()->addWikiMsg( 'mwoauthconsumerregistration-proposed',
						$cmr->getConsumerKey(),
						Utils::hmacDBSecret( $cmr->getSecretKey() ) );
				}
				$this->getOutput()->returnToMain();
			}
			break;
		case 'update':
			if ( !$user->isAllowed( 'mwoauthupdateownconsumer' ) ) {
				throw new \PermissionsError( 'mwoauthupdateownconsumer' );
			}

			$dbr = Utils::getCentralDB( DB_REPLICA );
			$cmrAc = ConsumerAccessControl::wrap(
				Consumer::newFromKey( $dbr, $consumerKey ), $this->getContext() );
			if ( !$cmrAc ) {
				$this->getOutput()->addWikiMsg( 'mwoauth-invalid-consumer-key' );
				break;
			} elseif ( $cmrAc->getDAO()->getDeleted() && !$user->isAllowed( 'mwoauthviewsuppressed' ) ) {
				throw new \PermissionsError( 'mwoauthviewsuppressed' );
			} elseif ( $cmrAc->getDAO()->getUserId() !== $centralUserId ) {
				// Do not show private information to other users
				$this->getOutput()->addWikiMsg( 'mwoauth-invalid-consumer-key' );
				break;
			}
			$oldSecretKey = $cmrAc->getDAO()->getSecretKey();

			$dbw = Utils::getCentralDB( DB_MASTER ); // @TODO: lazy handle
			$control = new ConsumerSubmitControl( $this->getContext(), [], $dbw );
			$form = \HTMLForm::factory( 'ooui',
				$control->registerValidators( [
					'info' => [
						'type' => 'info',
						'raw' => true,
						'default' => UIUtils::generateInfoTable( [
							'mwoauth-consumer-name' => $cmrAc->getName(),
							'mwoauth-consumer-version' => $cmrAc->getVersion(),
							'mwoauth-oauth-version' => $cmrAc->getOAuthVersion() === Consumer::OAUTH_VERSION_2 ?
								wfMessage( 'mwoauth-oauth-version-2' )->text() :
								wfMessage( 'mwoauth-oauth-version-1' )->text(),
							'mwoauth-consumer-key' => $cmrAc->getConsumerKey(),
						], $this->getContext() ),
					],
					'restrictions' => [
						'class' => 'HTMLRestrictionsField',
						'required' => true,
						'default' => $cmrAc->getDAO()->getRestrictions(),
					],
					'resetSecret' => [
						'type' => 'check',
						'label-message' => 'mwoauthconsumerregistration-resetsecretkey',
						'default' => false,
					],
					'rsaKey' => [
						'type' => 'textarea',
						'label-message' => 'mwoauth-consumer-rsakey',
						'required' => false,
						'default' => $cmrAc->getDAO()->getRsaKey(),
						'rows' => 5,
					],
					'reason' => [
						'type' => 'text',
						'label-message' => 'mwoauth-consumer-reason',
						'required' => true
					],
					'consumerKey' => [
						'type' => 'hidden',
						'default' => $cmrAc->getConsumerKey(),
					],
					'changeToken' => [
						'type'    => 'hidden',
						'default' => $cmrAc->getDAO()->getChangeToken( $this->getContext() ),
					],
					'action' => [
						'type'    => 'hidden',
						'default' => 'update'
					]
				] ),
				$this->getContext()
			);
			$form->setSubmitCallback(
				function ( array $data, \IContextSource $context ) use ( $control ) {
					$control->setInputParameters( $data );
					return $control->submit();
				}
			);
			$form->setWrapperLegendMsg( 'mwoauthconsumerregistration-update-legend' );
			$form->setSubmitTextMsg( 'mwoauthconsumerregistration-update-submit' );
			$form->addPreText(
				$this->msg( 'mwoauthconsumerregistration-update-text' )->parseAsBlock() );

			$status = $form->show();
			if ( $status instanceof \Status && $status->isOK() ) {
				/** @var Consumer $cmr */
				// @phan-suppress-next-line PhanTypeArraySuspiciousNullable
				$cmr = $status->value['result']['consumer'];
				$this->getOutput()->addWikiMsg( 'mwoauthconsumerregistration-updated' );
				$curSecretKey = $cmr->getSecretKey();
				if ( $oldSecretKey !== $curSecretKey ) { // token reset?
					if ( $cmr->getOwnerOnly() ) {
						// @phan-suppress-next-line PhanTypeArraySuspiciousNullable
						$accessToken = $status->value['result']['accessToken'];
						if ( $cmr->getOAuthVersion() === Consumer::OAUTH_VERSION_2 ) {
							// If we just add raw AT to the page, it would go 3000px wide
							$accessToken = \Html::element( 'span', [
								'style' => 'overflow-wrap: break-word'
							], (string)$accessToken );

							$this->getOutput()->addWikiMsg(
								'mwoauthconsumerregistration-secretreset-owner-only-oauth2',
								$cmr->getConsumerKey(),
								Utils::hmacDBSecret( $cmr->getSecretKey() ),
								\Message::rawParam( $accessToken )
							);
						} else {
							$this->getOutput()->addWikiMsg(
								'mwoauthconsumerregistration-secretreset-owner-only',
								$cmr->getConsumerKey(),
								Utils::hmacDBSecret( $curSecretKey ),
								$accessToken->key,
								Utils::hmacDBSecret( $accessToken->secret )
							);
						}
					} else {
						$this->getOutput()->addWikiMsg( 'mwoauthconsumerregistration-secretreset',
							Utils::hmacDBSecret( $curSecretKey ) );
					}
				}
				$this->getOutput()->returnToMain();
			} else {
				$out = $this->getOutput();
				// Show all of the status updates
				$logPage = new \LogPage( 'mwoauthconsumer' );
				$out->addHTML( \Xml::element( 'h2', null, $logPage->getName()->text() ) );
				\LogEventsList::showLogExtract( $out, 'mwoauthconsumer', '', '', [
					'conds'  => [
						'ls_field' => 'OAuthConsumer',
						'ls_value' => $cmrAc->getConsumerKey(),
					],
					'flags'  => \LogEventsList::NO_EXTRA_USER_LINKS,
				] );
			}
			break;
		case 'list':
			$pager = new ListMyConsumersPager( $this, [], $centralUserId );
			if ( $pager->getNumRows() ) {
				$this->getOutput()->addHTML( $pager->getNavigationBar() );
				$this->getOutput()->addHTML( $pager->getBody() );
				$this->getOutput()->addHTML( $pager->getNavigationBar() );
			} else {
				$this->getOutput()->addWikiMsg( "mwoauthconsumerregistration-none" );
			}
			# Every 30th view, prune old deleted items
			if ( 0 == mt_rand( 0, 29 ) ) {
				Utils::runAutoMaintenance( Utils::getCentralDB( DB_MASTER ) );
			}
			break;
		default:
			$this->getOutput()->addWikiMsg( 'mwoauthconsumerregistration-maintext' );
		}

		$this->addSubtitleLinks( $action, $consumerKey );

		$this->getOutput()->addModuleStyles( 'ext.MWOAuth.styles' );
	}

	/**
	 * Show navigation links
	 *
	 * @param string $action
	 * @param string $consumerKey
	 * @return void
	 */
	protected function addSubtitleLinks( $action, $consumerKey ) {
		$listLinks = [];
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
		if ( $consumerKey && $action == 'update' ) {
			$listLinks[] = \Linker::linkKnown(
				\SpecialPage::getTitleFor( 'OAuthListConsumers', "view/$consumerKey" ),
				$this->msg( 'mwoauthconsumer-consumer-view' )->escaped() );
		}

		$linkHtml = $this->getLanguage()->pipeList( $listLinks );

		$viewall = $this->msg( 'parentheses' )->rawParams(
			\Linker::linkKnown(
				$this->getPageTitle(),
				$this->msg( 'mwoauthconsumerregistration-main' )->escaped()
			)
		)->escaped();

		$this->getOutput()->setSubtitle(
			"<strong>" . $this->msg( 'mwoauthconsumerregistration-navigation' )->escaped() .
			"</strong> [{$linkHtml}] <strong>{$viewall}</strong>" );
	}

	/**
	 * @param DBConnRef $db
	 * @param \stdClass $row
	 * @return string
	 */
	public function formatRow( DBConnRef $db, $row ) {
		$cmrAc = ConsumerAccessControl::wrap(
			Consumer::newFromRow( $db, $row ), $this->getContext() );
		$cmrKey = $cmrAc->getConsumerKey();

		$links = [];
		$links[] = \Linker::linkKnown(
			\SpecialPage::getTitleFor( 'OAuthListConsumers', "view/$cmrKey" ),
			$this->msg( 'mwoauthlistconsumers-view' )->escaped()
		);

		$links[] = \Linker::linkKnown(
			$this->getPageTitle( 'update/' . $cmrKey ),
			$this->msg( 'mwoauthconsumerregistration-manage' )->escaped()
		);

		$links = $this->getLanguage()->pipeList( $links );

		$time = htmlspecialchars( $this->getLanguage()->timeanddate(
			wfTimestamp( TS_MW, $cmrAc->getRegistration() ), true ) );

		$stageKey = Consumer::$stageNames[$cmrAc->getStage()];
		$encStageKey = htmlspecialchars( $stageKey ); // sanity
		// Show last log entry (@TODO: title namespace?)
		// @TODO: inject DB
		$logHtml = '';
		\LogEventsList::showLogExtract( $logHtml, 'mwoauthconsumer', '', '', [
			'conds'  => [
				'ls_field' => 'OAuthConsumer',
				'ls_value' => $cmrAc->getConsumerKey(),
			],
			'lim'    => 1,
			'flags'  => \LogEventsList::NO_EXTRA_USER_LINKS,
		] );

		$lang = $this->getLanguage();
		$oauthVersionMessage = $cmrAc->getOAuthVersion() === Consumer::OAUTH_VERSION_2 ?
			wfMessage( 'mwoauth-oauth-version-2' )->text() :
			wfMessage( 'mwoauth-oauth-version-1' )->text();
		$data = [
			'mwoauthconsumerregistration-name' => $cmrAc->escapeForHtml( $cmrAc->getNameAndVersion() ),
			'mwoauth-oauth-version' => $cmrAc->escapeForHtml( $oauthVersionMessage ),
			// Messages: mwoauth-consumer-stage-proposed, mwoauth-consumer-stage-rejected,
			// mwoauth-consumer-stage-expired, mwoauth-consumer-stage-approved,
			// mwoauth-consumer-stage-disabled
			'mwoauthconsumerregistration-stage' =>
				$this->msg( "mwoauth-consumer-stage-$stageKey" )->escaped(),
			'mwoauthconsumerregistration-description' => $cmrAc->escapeForHtml(
				$cmrAc->get( 'description', function ( $s ) use ( $lang ) {
					return $lang->truncateForVisual( $s, 10024 );
				} )
			),
			'mwoauthconsumerregistration-email' => $cmrAc->escapeForHtml( $cmrAc->getEmail() ),
			'mwoauthconsumerregistration-consumerkey' => $cmrAc->escapeForHtml( $cmrAc->getConsumerKey() ),
			'mwoauthconsumerregistration-lastchange' => $logHtml,
		];

		$r = "<li class='mw-mwoauthconsumerregistration-{$encStageKey}'>";
		$r .= "<span>$time (<strong>{$links}</strong>)</span>";
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
