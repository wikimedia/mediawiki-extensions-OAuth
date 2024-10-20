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
use InvalidArgumentException;
use LogEventsList;
use LogPage;
use MediaWiki\Context\IContextSource;
use MediaWiki\Extension\OAuth\Backend\Consumer;
use MediaWiki\Extension\OAuth\Backend\Utils;
use MediaWiki\Extension\OAuth\Control\ConsumerAccessControl;
use MediaWiki\Extension\OAuth\Control\ConsumerSubmitControl;
use MediaWiki\Extension\OAuth\Frontend\Pagers\ListMyConsumersPager;
use MediaWiki\Extension\OAuth\Frontend\UIUtils;
use MediaWiki\Html\Html;
use MediaWiki\HTMLForm\Field\HTMLHiddenField;
use MediaWiki\HTMLForm\Field\HTMLRestrictionsField;
use MediaWiki\HTMLForm\HTMLForm;
use MediaWiki\Json\FormatJson;
use MediaWiki\Message\Message;
use MediaWiki\Permissions\GrantsInfo;
use MediaWiki\Permissions\GrantsLocalization;
use MediaWiki\Permissions\PermissionManager;
use MediaWiki\SpecialPage\SpecialPage;
use MediaWiki\Status\Status;
use MediaWiki\User\User;
use MediaWiki\WikiMap\WikiMap;
use MediaWiki\Xml\Xml;
use MWRestrictions;
use PermissionsError;
use stdClass;
use UserBlockedError;
use Wikimedia\Rdbms\IDatabase;

/**
 * Page that has registration request form and consumer update form
 */
class SpecialMWOAuthConsumerRegistration extends SpecialPage {
	private PermissionManager $permissionManager;
	private GrantsInfo $grantsInfo;
	private GrantsLocalization $grantsLocalization;

	public function __construct(
		PermissionManager $permissionManager,
		GrantsInfo $grantsInfo,
		GrantsLocalization $grantsLocalization
	) {
		parent::__construct( 'OAuthConsumerRegistration' );
		$this->permissionManager = $permissionManager;
		$this->grantsInfo = $grantsInfo;
		$this->grantsLocalization = $grantsLocalization;
	}

	public function doesWrites() {
		return true;
	}

	public function userCanExecute( User $user ) {
		return $user->isEmailConfirmed();
	}

	public function displayRestrictionError() {
		throw new PermissionsError( null, [ 'mwoauthconsumerregistration-need-emailconfirmed' ] );
	}

	public function execute( $par ) {
		$this->requireNamedUser( 'mwoauth-named-account-required-reason' );
		$this->checkPermissions();

		$request = $this->getRequest();
		$user = $this->getUser();
		$lang = $this->getLanguage();
		$centralUserId = Utils::getCentralIdFromLocalUser( $user );

		// Redirect to HTTPs if attempting to access this page via HTTP.
		// Proposals and updates to consumers can involve sending new secrets.
		if ( $this->getConfig()->get( 'MWOAuthSecureTokenTransfer' )
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
		$this->getOutput()->addModules( 'mediawiki.special' );
		$this->addHelpLink( 'Help:OAuth' );

		$block = $user->getBlock();
		if ( $block ) {
			throw new UserBlockedError( $block );
		}
		$this->checkReadOnly();

		// Format is Special:OAuthConsumerRegistration[/propose/<oauth1a|oauth2>|/list|/update/<consumer key>]
		$navigation = $par !== null ? explode( '/', $par ) : [];
		$action = $navigation[0] ?? '';
		$subPage = $navigation[1] ?? '';

		if ( $this->getConfig()->get( 'MWOAuthReadOnly' ) && $action !== 'list' ) {
			throw new ErrorPageError( 'mwoauth-error', 'mwoauth-db-readonly' );
		}

		switch ( $action ) {
			case 'propose':
				if ( !$this->permissionManager->userHasRight( $user, 'mwoauthproposeconsumer' ) ) {
					throw new PermissionsError( 'mwoauthproposeconsumer' );
				}

				if ( $subPage === '' ) {
					$this->getOutput()->addWikiMsg( 'mwoauthconsumerregistration-propose-text' );
					break;
				}

				$allWikis = Utils::getAllWikiNames();
				$showGrants = $this->grantsInfo->getValidGrants();
				if ( $subPage === 'oauth2' ) {
					$this->proposeOAuth( Consumer::OAUTH_VERSION_2, $user, $allWikis, $lang, $showGrants );
					break;
				} elseif ( $subPage === 'oauth1a' ) {
					$this->proposeOAuth( Consumer::OAUTH_VERSION_1, $user, $allWikis, $lang, $showGrants );
					break;
				} else {
					$this->getOutput()->redirect( 'Special:OAuthConsumerRegistration/propose' );
				}
				break;
			case 'update':
				if ( !$this->permissionManager->userHasRight( $user, 'mwoauthupdateownconsumer' ) ) {
					throw new PermissionsError( 'mwoauthupdateownconsumer' );
				}

				$dbr = Utils::getCentralDB( DB_REPLICA );
				$cmrAc = ConsumerAccessControl::wrap(
				Consumer::newFromKey( $dbr, $subPage ), $this->getContext() );
				if ( !$cmrAc ) {
					$this->getOutput()->addWikiMsg( 'mwoauth-invalid-consumer-key' );
					break;
				} elseif ( $cmrAc->getDAO()->getDeleted()
				&& !$this->permissionManager->userHasRight( $user, 'mwoauthviewsuppressed' )
				) {
					throw new PermissionsError( 'mwoauthviewsuppressed' );
				} elseif ( $cmrAc->getDAO()->getUserId() !== $centralUserId ) {
					// Do not show private information to other users
					$this->getOutput()->addWikiMsg( 'mwoauth-invalid-consumer-key' );
					break;
				}
				$oldSecretKey = $cmrAc->getDAO()->getSecretKey();

				$dbw = Utils::getCentralDB( DB_PRIMARY );
				$control = new ConsumerSubmitControl( $this->getContext(), [], $dbw );
				$form = HTMLForm::factory( 'ooui',
					$control->registerValidators( [
						'info' => [
							'type' => 'info',
							'raw' => true,
							'default' => UIUtils::generateInfoTable( [
								'mwoauth-consumer-name' => $cmrAc->getName(),
								'mwoauth-consumer-version' => $cmrAc->getVersion(),
								'mwoauth-oauth-version' => $cmrAc->getOAuthVersion() === Consumer::OAUTH_VERSION_2 ?
									$this->msg( 'mwoauth-oauth-version-2' )->text() :
									$this->msg( 'mwoauth-oauth-version-1' )->text(),
								'mwoauth-consumer-key' => $cmrAc->getConsumerKey(),
							], $this->getContext() ),
						],
						'restrictions' => [
							'class' => HTMLRestrictionsField::class,
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
							'required' => !$cmrAc->getOwnerOnly(),
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
					static function ( array $data, IContextSource $context ) use ( $control ) {
						$control->setInputParameters( $data );
						return $control->submit();
					}
				);
				$form->setWrapperLegendMsg( 'mwoauthconsumerregistration-update-legend' );
				$form->setSubmitTextMsg( 'mwoauthconsumerregistration-update-submit' );
				$form->addPreHtml(
					$this->msg( 'mwoauthconsumerregistration-update-text' )->parseAsBlock() );

				$status = $form->show();
				if ( $status instanceof Status && $status->isOK() ) {
					/** @var Consumer $cmr */
					// @phan-suppress-next-line PhanTypeArraySuspiciousNullable
					$cmr = $status->value['result']['consumer'];
					$this->getOutput()->addWikiMsg( 'mwoauthconsumerregistration-updated' );
					$curSecretKey = $cmr->getSecretKey();
					// token reset?
					if ( $oldSecretKey !== $curSecretKey ) {
						if ( $cmr->getOwnerOnly() ) {
							// @phan-suppress-next-line PhanTypeArraySuspiciousNullable
							$accessToken = $status->value['result']['accessToken'];
							if ( $cmr->getOAuthVersion() === Consumer::OAUTH_VERSION_2 ) {
								// If we just add raw AT to the page, it would go 3000px wide
								$accessToken = Html::element( 'span', [
									'style' => 'overflow-wrap: break-word'
								], (string)$accessToken );

								$this->getOutput()->addWikiMsg(
									'mwoauthconsumerregistration-secretreset-owner-only-oauth2',
									$cmr->getConsumerKey(),
									Utils::hmacDBSecret( $cmr->getSecretKey() ),
									Message::rawParam( $accessToken )
								);
							} else {
								$this->getOutput()->addWikiMsg(
									'mwoauthconsumerregistration-secretreset-owner-only-oauth1',
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
					$logPage = new LogPage( 'mwoauthconsumer' );
					$out->addHTML( Xml::element( 'h2', null, $logPage->getName()->text() ) );
					LogEventsList::showLogExtract( $out, 'mwoauthconsumer', '', '', [
						'conds'  => [
							'ls_field' => 'OAuthConsumer',
							'ls_value' => $cmrAc->getConsumerKey(),
						],
						'flags'  => LogEventsList::NO_EXTRA_USER_LINKS,
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
				if ( mt_rand( 0, 29 ) == 0 ) {
					Utils::runAutoMaintenance( Utils::getCentralDB( DB_PRIMARY ) );
				}
				break;
			default:
				$this->getOutput()->addWikiMsg( 'mwoauthconsumerregistration-maintext' );
		}

		$this->addSubtitleLinks( $action, $subPage );

		$this->getOutput()->addModuleStyles( 'ext.MWOAuth.styles' );
	}

	/**
	 * Show navigation links
	 *
	 * @param string $action
	 * @param string $subPage
	 * @return void
	 */
	protected function addSubtitleLinks( $action, $subPage ) {
		$listLinks = [];
		if ( $action === 'propose' && $subPage ) {
			if ( $subPage === 'oauth1a' ) {
				$listLinks[] = $this->msg( 'mwoauthconsumerregistration-propose-oauth1' )->escaped();
				$listLinks[] = $this->getLinkRenderer()->makeKnownLink(
					$this->getPageTitle( 'propose/oauth2' ),
					$this->msg( 'mwoauthconsumerregistration-propose-oauth2' )->text()
				);
			} elseif ( $subPage === 'oauth2' ) {
				$listLinks[] = $this->getLinkRenderer()->makeKnownLink(
					$this->getPageTitle( 'propose/oauth1a' ),
					$this->msg( 'mwoauthconsumerregistration-propose-oauth1' )->text()
				);
				$listLinks[] = $this->msg( 'mwoauthconsumerregistration-propose-oauth2' )->escaped();
			}
		} else {
			$listLinks[] = $this->getLinkRenderer()->makeKnownLink(
				$this->getPageTitle( 'propose/oauth1a' ),
				$this->msg( 'mwoauthconsumerregistration-propose-oauth1' )->text()
			);
			$listLinks[] = $this->getLinkRenderer()->makeKnownLink(
				$this->getPageTitle( 'propose/oauth2' ),
				$this->msg( 'mwoauthconsumerregistration-propose-oauth2' )->text()
			);
		}
		if ( $subPage || $action !== 'list' ) {
			$listLinks[] = $this->getLinkRenderer()->makeKnownLink(
				$this->getPageTitle( 'list' ),
				$this->msg( 'mwoauthconsumerregistration-list' )->text()
			);
		} else {
			$listLinks[] = $this->msg( 'mwoauthconsumerregistration-list' )->escaped();
		}
		if ( $subPage && $action == 'update' ) {
			$listLinks[] = $this->getLinkRenderer()->makeKnownLink(
				SpecialPage::getTitleFor( 'OAuthListConsumers', "view/$subPage" ),
				$this->msg( 'mwoauthconsumer-consumer-view' )->text()
			);
		}

		$linkHtml = $this->getLanguage()->pipeList( $listLinks );

		$viewall = $this->msg( 'parentheses' )->rawParams(
			$this->getLinkRenderer()->makeKnownLink(
				$this->getPageTitle(),
				$this->msg( 'mwoauthconsumerregistration-main' )->text()
			)
		)->escaped();

		$this->getOutput()->setSubtitle(
			"<strong>" . $this->msg( 'mwoauthconsumerregistration-navigation' )->escaped() .
			"</strong> [{$linkHtml}] <strong>{$viewall}</strong>" );
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

		$links = [];
		$links[] = $this->getLinkRenderer()->makeKnownLink(
			SpecialPage::getTitleFor( 'OAuthListConsumers', "view/$cmrKey" ),
			$this->msg( 'mwoauthlistconsumers-view' )->text()
		);

		$links[] = $this->getLinkRenderer()->makeKnownLink(
			$this->getPageTitle( 'update/' . $cmrKey ),
			$this->msg( 'mwoauthconsumerregistration-manage' )->text()
		);

		$links = $this->getLanguage()->pipeList( $links );

		$time = htmlspecialchars( $this->getLanguage()->timeanddate(
			wfTimestamp( TS_MW, $cmrAc->getRegistration() ), true ) );

		$stageKey = Consumer::$stageNames[$cmrAc->getStage()];
		$encStageKey = htmlspecialchars( $stageKey );
		// Show last log entry (@TODO: title namespace?)
		// @TODO: inject DB
		$logHtml = '';
		LogEventsList::showLogExtract( $logHtml, 'mwoauthconsumer', '', '', [
			'conds'  => [
				'ls_field' => 'OAuthConsumer',
				'ls_value' => $cmrAc->getConsumerKey(),
			],
			'lim'    => 1,
			'flags'  => LogEventsList::NO_EXTRA_USER_LINKS,
		] );

		$lang = $this->getLanguage();
		$oauthVersionMessage = $cmrAc->getOAuthVersion() === Consumer::OAUTH_VERSION_2 ?
			$this->msg( 'mwoauth-oauth-version-2' )->text() :
			$this->msg( 'mwoauth-oauth-version-1' )->text();
		$data = [
			'mwoauthconsumerregistration-name' => $cmrAc->escapeForHtml( $cmrAc->getNameAndVersion() ),
			'mwoauth-oauth-version' => $cmrAc->escapeForHtml( $oauthVersionMessage ),
			// Messages: mwoauth-consumer-stage-proposed, mwoauth-consumer-stage-rejected,
			// mwoauth-consumer-stage-expired, mwoauth-consumer-stage-approved,
			// mwoauth-consumer-stage-disabled
			'mwoauthconsumerregistration-stage' =>
				$this->msg( "mwoauth-consumer-stage-$stageKey" )->escaped(),
			'mwoauthconsumerregistration-description' => $cmrAc->escapeForHtml(
				$cmrAc->get( 'description', static function ( $s ) use ( $lang ) {
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

	private function proposeOAuth( int $oauthVersion, User $user, $allWikis, $lang, $showGrants ) {
		if ( !in_array( $oauthVersion, [ Consumer::OAUTH_VERSION_1, Consumer::OAUTH_VERSION_2 ] ) ) {
			throw new InvalidArgumentException( 'Invalid OAuth version' );
		}
		$dbw = Utils::getCentralDB( DB_PRIMARY );
		$control = new ConsumerSubmitControl( $this->getContext(), [], $dbw );

		$grantNames = $this->grantsLocalization->getGrantDescriptionsWithClasses(
			$showGrants, $this->getLanguage() );
		$formDescriptor = [
			'oauthVersion' => [
				'class' => HTMLHiddenField::class,
				'default' => $oauthVersion,
			],
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
				'help-message' => ( $oauthVersion === Consumer::OAUTH_VERSION_2 )
					? 'mwoauth-consumer-callbackurl-help' : null,
				'required' => true,
				'hide-if' => [ '!==', 'ownerOnly', '' ],
			],
			'callbackIsPrefix' => [
				'oauthVersion' => Consumer::OAUTH_VERSION_1,
				'type' => 'check',
				'label-message' => 'mwoauth-consumer-callbackisprefix',
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
					 $this->msg( 'mwoauth-consumer-wiki-thiswiki', WikiMap::getCurrentWikiId() )
						 ->escaped() => WikiMap::getCurrentWikiId()
				 ] + array_flip( $allWikis ),
				'label-message' => 'mwoauth-consumer-wiki',
				'required' => true,
				'default' => '*'
			],
			'oauth2IsConfidential' => [
				'oauthVersion' => Consumer::OAUTH_VERSION_2,
				'type' => 'check',
				'label-message' => 'mwoauth-oauth2-is-confidential',
				'help-message' => 'mwoauth-oauth2-is-confidential-help',
				'default' => 1
			],
			'oauth2GrantTypes' => [
				'oauthVersion' => Consumer::OAUTH_VERSION_2,
				'type' => 'multiselect',
				'label-message' => 'mwoauth-oauth2-granttypes',
				'hide-if' => [ '!==', 'ownerOnly', '' ],
				'options' => array_filter( [
					$this->msg( 'mwoauth-oauth2-granttype-auth-code' )->escaped() => 'authorization_code',
					$this->msg( 'mwoauth-oauth2-granttype-refresh-token' )->escaped() => 'refresh_token',
					$this->msg( 'mwoauth-oauth2-granttype-client-credentials' )->escaped() => 'client_credentials',
				], fn ( $grantType ) => in_array( $grantType, $this->getConfig()->get( 'OAuth2EnabledGrantTypes' ) ) ),
				'required' => true,
				'default' => [ 'authorization_code', 'refresh_token' ]
			],
			'granttype' => [
				'type' => 'radio',
				'options-messages' => [
					'grant-mwoauth-authonly' => 'authonly',
					'grant-mwoauth-authonlyprivate' => 'authonlyprivate',
					'mwoauth-granttype-normal' => 'normal',
				],
				'label-message' => 'mwoauth-consumer-granttypes',
				'default' => 'normal',
			],
			// HACK separate field from grants because HTMLFormField cannot position help text on top
			'grantsHelp' => [
				'type' => 'info',
				'default' => '',
				'help-message' => 'mwoauth-consumer-grantshelp',
			],
			'grants' => [
				'type' => 'checkmatrix',
				'label-message' => 'mwoauth-consumer-grantsneeded',
				'hide-if' => [ '!==', 'granttype', 'normal' ],
				'columns' => [
					$this->msg( 'mwoauth-consumer-required-grant' )->escaped() => 'grant'
				],
				'rows' => array_combine(
					$grantNames,
					$showGrants
				),
				'tooltips-html' => array_combine(
					$grantNames,
					array_map(
						fn ( $rights ) => Html::rawElement( 'ul', [], implode( '', array_map(
							fn ( $right ) => Html::rawElement( 'li', [], $this->msg( "right-$right" )->parse() ),
							$rights
						) ) ),
						array_intersect_key( $this->grantsInfo->getRightsByGrant(),
							array_fill_keys( $showGrants, true ) )
					)
				),
				'force-options-on' => array_map(
					static function ( $g ) {
						return "grant-$g";
					},
					$this->grantsInfo->getHiddenGrants()
				),
				// different format
				'validation-callback' => null,
			],
			'restrictions' => [
				'class' => HTMLRestrictionsField::class,
				'required' => true,
				'default' => MWRestrictions::newDefault(),
			],
			'rsaKey' => [
				'oauthVersion' => Consumer::OAUTH_VERSION_1,
				'type' => 'textarea',
				'label-message' => 'mwoauth-consumer-rsakey',
				'help-message' => 'mwoauth-consumer-rsakey-help',
				'required' => false,
				'default' => '',
				'rows' => 5
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
		];
		$formDescriptor = array_filter( $formDescriptor,
			fn ( $field ) => !isset( $field['oauthVersion'] ) || $field['oauthVersion'] === $oauthVersion
		);

		$form = HTMLForm::factory( 'ooui',
			$control->registerValidators( $formDescriptor ),
			$this->getContext()
		);
		$form->setSubmitCallback(
			function ( array $data, IContextSource $context ) use ( $control ) {
				// adapt form to controller
				$data = $this->fillDefaultFields( $data );

				$data['grants'] = FormatJson::encode(
					preg_replace( '/^grant-/', '', $data['grants'] )
				);

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
		$form->addPreHtml(
			// mwoauthconsumerregistration-propose-text-oauth1
			// mwoauthconsumerregistration-propose-text-oauth2
			$this->msg( "mwoauthconsumerregistration-propose-text-oauth$oauthVersion" )->parseAsBlock() );

		$status = $form->show();
		if ( $status instanceof Status && $status->isOK() ) {
			/** @var Consumer $cmr */
			// @phan-suppress-next-line PhanTypeArraySuspiciousNullable
			$cmr = $status->value['result']['consumer'];
			if ( $cmr->getOwnerOnly() ) {
				// @phan-suppress-next-line PhanTypeArraySuspiciousNullable
				$accessToken = $status->value['result']['accessToken'];
				if ( $oauthVersion === Consumer::OAUTH_VERSION_1 ) {
					$this->getOutput()->addWikiMsg(
						"mwoauthconsumerregistration-created-owner-only-oauth1",
						$cmr->getConsumerKey(),
						Utils::hmacDBSecret( $cmr->getSecretKey() ),
						$accessToken->key,
						Utils::hmacDBSecret( $accessToken->secret )
					);
				} else {
					// OAuth 2 access tokens are very long
					$accessToken = Html::element( 'span', [
						'style' => 'overflow-wrap: break-word'
					], (string)$accessToken );
					$this->getOutput()->addWikiMsg(
						'mwoauthconsumerregistration-created-owner-only-oauth2',
						$cmr->getConsumerKey(),
						Utils::hmacDBSecret( $cmr->getSecretKey() ),
						Message::rawParam( $accessToken )
					);
				}
			} elseif ( $cmr->getStage() === Consumer::STAGE_APPROVED ) {
				// mwoauthconsumerregistration-autoapproved-oauth1
				// mwoauthconsumerregistration-autoapproved-oauth2
				$this->getOutput()->addWikiMsg( "mwoauthconsumerregistration-autoapproved-oauth$oauthVersion",
					$cmr->getConsumerKey(),
					Utils::hmacDBSecret( $cmr->getSecretKey() )
				);
			} else {
				// mwoauthconsumerregistration-proposed-oauth1
				// mwoauthconsumerregistration-proposed-oauth2
				$this->getOutput()->addWikiMsg( "mwoauthconsumerregistration-proposed-oauth$oauthVersion",
					$cmr->getConsumerKey(),
					Utils::hmacDBSecret( $cmr->getSecretKey() ) );
			}
			$this->getOutput()->returnToMain();
		}
	}

	/**
	 * Used to adapt both OAuth forms to the same structure so SubmitControl::validateFields() doesn't fail
	 *
	 * @param array $form
	 * @return array
	 */
	private function fillDefaultFields( array $form ): array {
		// These defaults are taken from the legacy form and are present regardless of OAuth version
		$defaults = [
			'callbackIsPrefix' => false,
			'oauth2IsConfidential' => true,
			'oauth2GrantTypes'  => [ 'authorization_code', 'refresh_token' ],
			'granttype' => 'normal',
			'rsaKey' => '',
		];

		$form = array_merge( $defaults, $form );

		// 'callbackUrl' must be present,
		// otherwise SubmitControl::validateFields() fails.
		if ( $form['ownerOnly'] && !isset( $form['callbackUrl'] ) ) {
			$form['callbackUrl'] = '';
		}

		return $form;
	}
}
