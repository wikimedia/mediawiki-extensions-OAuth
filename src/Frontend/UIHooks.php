<?php

namespace MediaWiki\Extension\OAuth\Frontend;

use MediaWiki\Cache\Hook\MessagesPreLoadHook;
use MediaWiki\Context\DerivativeContext;
use MediaWiki\Context\RequestContext;
use MediaWiki\Extension\OAuth\Backend\Consumer;
use MediaWiki\Extension\OAuth\Backend\Utils;
use MediaWiki\Extension\OAuth\Control\ConsumerAccessControl;
use MediaWiki\Extension\OAuth\Control\ConsumerSubmitControl;
use MediaWiki\Extension\OAuth\Frontend\SpecialPages\SpecialMWOAuthConsumerRegistration;
use MediaWiki\Extension\OAuth\Frontend\SpecialPages\SpecialMWOAuthManageConsumers;
use MediaWiki\Hook\LoginFormValidErrorMessagesHook;
use MediaWiki\Html\Html;
use MediaWiki\HTMLForm\HTMLForm;
use MediaWiki\Parser\Sanitizer;
use MediaWiki\Permissions\PermissionManager;
use MediaWiki\Preferences\Hook\GetPreferencesHook;
use MediaWiki\SpecialPage\Hook\SpecialPage_initListHook;
use MediaWiki\SpecialPage\Hook\SpecialPageAfterExecuteHook;
use MediaWiki\SpecialPage\Hook\SpecialPageBeforeFormDisplayHook;
use MediaWiki\SpecialPage\SpecialPage;
use MediaWiki\User\User;
use MediaWiki\WikiMap\WikiMap;
use MWException;
use OOUI\ButtonWidget;

/**
 * Class containing GUI even handler functions for an OAuth environment
 *
 * @phpcs:disable MediaWiki.NamingConventions.LowerCamelFunctionsName.FunctionName
 */
class UIHooks implements
	GetPreferencesHook,
	LoginFormValidErrorMessagesHook,
	MessagesPreLoadHook,
	SpecialPageAfterExecuteHook,
	SpecialPageBeforeFormDisplayHook,
	SpecialPage_initListHook
{

	/** @var PermissionManager */
	private $permissionManager;

	/**
	 * @param PermissionManager $permissionManager
	 */
	public function __construct( PermissionManager $permissionManager ) {
		$this->permissionManager = $permissionManager;
	}

	/**
	 * @param User $user
	 * @param array &$preferences
	 * @return bool
	 * @throws MWException
	 */
	public function onGetPreferences( $user, &$preferences ) {
		$dbr = Utils::getCentralDB( DB_REPLICA );
		$conds = [
			'oaac_user_id' => Utils::getCentralIdFromLocalUser( $user ),
		];

		if ( !$this->permissionManager->userHasRight( $user, 'mwoauthviewsuppressed' ) ) {
			$conds['oarc_deleted'] = 0;
		}
		$count = $dbr->newSelectQueryBuilder()
			->select( 'COUNT(*)' )
			->from( 'oauth_accepted_consumer' )
			->join( 'oauth_registered_consumer', null, 'oaac_consumer_id = oarc_id' )
			->where( $conds )
			->caller( __METHOD__ )
			->fetchField();

		$control = new ButtonWidget( [
			'href' => SpecialPage::getTitleFor( 'OAuthManageMyGrants' )->getLinkURL(),
			'label' => wfMessage( 'mwoauth-prefs-managegrantslink' )->numParams( $count )->text()
		] );

		$prefInsert = [ 'mwoauth-prefs-managegrants' =>
			[
				'section' => 'personal/info',
				'label-message' => 'mwoauth-prefs-managegrants',
				'type' => 'info',
				'raw' => true,
				'default' => (string)$control
			],
		];

		if ( array_key_exists( 'usergroups', $preferences ) ) {
			$preferences = wfArrayInsertAfter( $preferences, $prefInsert, 'usergroups' );
		} else {
			$preferences += $prefInsert;
		}

		return true;
	}

	/**
	 * Override MediaWiki namespace for a message
	 * @param string $title Message name (no prefix)
	 * @param string &$message Message wikitext
	 * @param string $code Language code
	 * @return bool false if we replaced $message
	 */
	public function onMessagesPreLoad( $title, &$message, $code ) {
		// Quick fail check
		if ( substr( $title, 0, 15 ) !== 'Tag-OAuth_CID:_' ) {
			return true;
		}

		// More expensive check
		if ( !preg_match( '!^Tag-OAuth_CID:_(\d+)((?:-description)?)(?:/|$)!', $title, $m ) ) {
			return true;
		}

		// Put the correct language in the context, so that later uses of $context->msg() will use it
		$context = new DerivativeContext( RequestContext::getMain() );
		$context->setLanguage( $code );

		$dbr = Utils::getCentralDB( DB_REPLICA );
		$cmrAc = ConsumerAccessControl::wrap(
			Consumer::newFromId( $dbr, (int)$m[1] ),
			$context
		);
		if ( !$cmrAc ) {
			// Invalid consumer, skip it
			return true;
		}

		if ( $m[2] ) {
			$message = $cmrAc->escapeForWikitext( $cmrAc->getDescription() );
		} else {
			$target = SpecialPage::getTitleFor( 'OAuthListConsumers',
				'view/' . $cmrAc->getConsumerKey()
			);
			$encName = $cmrAc->escapeForWikitext( $cmrAc->getNameAndVersion() );
			$message = "[[$target|$encName]]";
		}
		return false;
	}

	/**
	 * Append OAuth-specific grants to Special:ListGrants
	 * @param SpecialPage $special
	 * @param string $par
	 * @return bool
	 */
	public function onSpecialPageAfterExecute( $special, $par ) {
		if ( $special->getName() !== 'Listgrants' ) {
			return true;
		}

		$out = $special->getOutput();

		$out->addWikiMsg( 'mwoauth-listgrants-extra-summary' );

		$out->addHTML(
			Html::openElement( 'table',
			[ 'class' => 'wikitable mw-listgrouprights-table' ] ) .
			'<tr>' .
			Html::element( 'th', [], $special->msg( 'listgrants-grant' )->text() ) .
			Html::element( 'th', [], $special->msg( 'listgrants-rights' )->text() ) .
			'</tr>'
		);

		$grants = [
			'mwoauth-authonly' => [],
			'mwoauth-authonlyprivate' => [],
		];

		foreach ( $grants as $grant => $rights ) {
			$descs = [];
			$rights = array_filter( $rights );
			foreach ( $rights as $permission => $granted ) {
				$descs[] = $special->msg(
					'listgrouprights-right-display',
					User::getRightDescription( $permission ),
					'<span class="mw-listgrants-right-name">' . $permission . '</span>'
				)->parse();
			}
			if ( !count( $descs ) ) {
				$grantCellHtml = '';
			} else {
				sort( $descs );
				$grantCellHtml = '<ul><li>' . implode( "</li>\n<li>", $descs ) . '</li></ul>';
			}

			$id = Sanitizer::escapeIdForAttribute( $grant );
			$out->addHTML( Html::rawElement( 'tr', [ 'id' => $id ],
				"<td>" . $special->msg( "grant-$grant" )->escaped() . "</td>" .
				"<td>" . $grantCellHtml . '</td>'
			) );
		}

		$out->addHTML( Html::closeElement( 'table' ) );

		return true;
	}

	/**
	 * Add additional text to Special:BotPasswords
	 * @param string $name Special page name
	 * @param HTMLForm $form
	 * @return bool
	 */
	public function onSpecialPageBeforeFormDisplay( $name, $form ) {
		global $wgMWOAuthCentralWiki;

		if ( $name === 'BotPasswords' ) {
			if ( Utils::isCentralWiki() ) {
				$url = SpecialPage::getTitleFor( 'OAuthConsumerRegistration' )->getFullURL();
			} else {
				$url = WikiMap::getForeignURL(
					$wgMWOAuthCentralWiki,
					// Cross-wiki, so don't localize
					'Special:OAuthConsumerRegistration'
				);
			}
			$form->addPreHtml( $form->msg( 'mwoauth-botpasswords-note', $url )->parseAsBlock() );
		}
		return true;
	}

	/**
	 * @param array &$notifications
	 * @param array &$notificationCategories
	 * @param array &$icons
	 */
	public static function onBeforeCreateEchoEvent(
		&$notifications, &$notificationCategories, &$icons
	) {
		global $wgOAuthGroupsToNotify;

		if ( !Utils::isCentralWiki() ) {
			return;
		}

		$notificationCategories['oauth-owner'] = [
			'tooltip' => 'echo-pref-tooltip-oauth-owner',
		];
		$notificationCategories['oauth-admin'] = [
			'tooltip' => 'echo-pref-tooltip-oauth-admin',
			'usergroups' => $wgOAuthGroupsToNotify,
		];

		foreach ( ConsumerSubmitControl::$actions as $eventName ) {
			// oauth-app-propose and oauth-app-update notifies admins of the app.
			// oauth-app-approve, oauth-app-reject, oauth-app-disable and oauth-app-reenable
			// notify owner of the change.
			$notifications["oauth-app-$eventName"] =
				EchoOAuthStageChangePresentationModel::getDefinition( $eventName );
		}

		$icons['oauth'] = [ 'path' => 'OAuth/resources/assets/echo-icon.png' ];
	}

	/**
	 * @param array &$specialPages
	 */
	public function onSpecialPage_initList( &$specialPages ) {
		if ( Utils::isCentralWiki() ) {
			$specialPages['OAuthConsumerRegistration'] = [
				'class' => SpecialMWOAuthConsumerRegistration::class,
				'services' => [
					'PermissionManager',
					'GrantsInfo',
					'GrantsLocalization',
				],
			];
			$specialPages['OAuthManageConsumers'] = [
				'class' => SpecialMWOAuthManageConsumers::class,
				'services' => [
					'GrantsLocalization',
				],
			];
		}
	}

	/**
	 * Show help text when a user is redirected to provider login page
	 * @param array &$messages
	 * @return bool
	 */
	public function onLoginFormValidErrorMessages( &$messages ) {
		$messages = array_merge( $messages, [
			'mwoauth-named-account-required-reason',
			'mwoauth-named-account-required-reason-for-temp-user',
			'mwoauth-available-only-to-registered',
		] );
		return true;
	}
}
