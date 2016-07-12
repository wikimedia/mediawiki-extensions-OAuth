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

if ( !defined( 'MEDIAWIKI' ) ) {
	echo "OAuth extension\n";
	exit( 1 ) ;
}

$wgExtensionCredits['other'][] = array(
	'path'           => __FILE__,
	'name'           => 'OAuth',
	'descriptionmsg' => 'mwoauth-desc',
	'author'         => array( 'Aaron Schulz', 'Chris Steipp', 'Brad Jorsch' ),
	'url'            => 'https://www.mediawiki.org/wiki/Extension:OAuth',
	'license-name'   => 'GPL-2.0+',
);

# Load default config variables
/**
 * @var string Wiki ID of OAuth management wiki
 * On wiki farms, it makes sense to set this to a wiki that acts as a portal
 * site, is decidated to management, or just handles login/authentication. It
 * can, however, be set to any wiki if the farm. For single-wiki sites or farms
 * where each wiki manages consumers separately, it should be left as false.
 */
$wgMWOAuthCentralWiki = false;

/**
 * @var bool Whether shared global user IDs are stored in the oauth tables
 * On wiki farms with a central authentication system (with integer user IDs)
 * that share a single OAuth management wiki, this must be set to true. If wikis
 * have a central authentication system but have their own OAuth management, then
 * this can be either true or false. Otherwise it should always be set to false.
 *
 * Setting this to true requires CentralIdLookup or an MWOAuth aware
 * authentication extension.
 *
 * This value should not be changed after the fact to avoid ambigious IDs.
 * Proper user ID migration should be done before any such changes.
 */
$wgMWOAuthSharedUserIDs = false;

/**
 * @var string Source of shared user IDs, if enabled
 *
 * If CentralIdLookup is available, this is the $providerId for
 * CentralIdLookup::factory(). Generally null would be what you want, to use
 * the default provider.
 *
 * If that class is not available or the named provider is not found, this is
 * passed to the 'OAuthGetUserNamesFromCentralIds', 'OAuthGetLocalUserFromCentralId',
 * 'OAuthGetCentralIdFromLocalUser', and 'OAuthGetCentralIdFromUserName' hooks.
 *
 * This has no effect if $wgMWOAuthSharedUserIDs is set to false.
 */
$wgMWOAuthSharedUserSource = null;

/** @var integer Seconds after which an idle consumer request is marked as "expired" */
$wgMWOAuthRequestExpirationAge = 30 * 86400;

$wgAvailableRights[] = 'mwoauthproposeconsumer';
$wgAvailableRights[] = 'mwoauthupdateownconsumer';
$wgAvailableRights[] = 'mwoauthmanageconsumer';
$wgAvailableRights[] = 'mwoauthsuppress';
$wgAvailableRights[] = 'mwoauthviewsuppressed';
$wgAvailableRights[] = 'mwoauthviewprivate';
$wgAvailableRights[] = 'mwoauthmanagemygrants';

$wgGroupPermissions['user']['mwoauthmanagemygrants'] = true;

$wgDefaultUserOptions['echo-subscriptions-web-oauth-owner'] = true;
$wgDefaultUserOptions['echo-subscriptions-email-oauth-owner'] = true;
$wgDefaultUserOptions['echo-subscriptions-web-oauth-admin'] = true;
$wgDefaultUserOptions['echo-subscriptions-email-oauth-admin'] = true;

/** @var bool Require HTTPs for user transactions that might send out secret tokens */
$wgMWOAuthSecureTokenTransfer = true; // RfC compliance

/** @var array List of API module classes to disable when OAuth is used for the request. */
$wgMWOauthDisabledApiModules = array(
	'ApiLogin',
	'ApiLogout',
);

/**
 * @var bool prevent write activity to the database. When this is set, consumers cannot
 * be added or updated, and new authorizations are prohibited. Authorization headers for
 * existing authorizations will continue to work. Useful for migrating database tables.
 */
$wgMWOAuthReadOnly = false;

/**
 * @var string Secret to add to HMAC of token secrets
 * A cryptographically random string, used as an extra protection for secrets stored in the
 * database. This can use the wiki's $wgSecretKey, but in multi-wiki configurations, this needs
 * to be the same for all wikis.
 */
$wgOAuthSecretKey = $wgSecretKey;

/**
 * @var string[] User groups to notify about new consumers that need to be reviewed.
 */
$wgOAuthGroupsToNotify = [];

# Main i18n file and special page alias file
$wgMessagesDirs['MWOAuth'] = __DIR__ . '/i18n';
$wgExtensionMessagesFiles['MWOAuthAliases'] = __DIR__ . '/frontend/language/MWOAuth.alias.php';

# Setup classes
$wgAutoloadClasses['MediaWiki\Extensions\OAuth\MWOAuthAPISetup'] = __DIR__ . '/api/MWOAuthAPI.setup.php';
$wgAutoloadClasses['MediaWiki\Extensions\OAuth\MWOAuthUISetup'] = __DIR__ . '/frontend/MWOAuthUI.setup.php';
$wgAutoloadClasses['MediaWiki\Extensions\OAuth\MWOAuthUIHooks'] = __DIR__ . '/frontend/MWOAuthUI.hooks.php';
$wgAutoloadClasses['MediaWiki\Extensions\OAuth\MWOAuthHooks'] = __DIR__ . '/backend/MWOAuth.hooks.php';

# Special pages and pagers
$wgAutoloadClasses['MediaWiki\Extensions\OAuth\SpecialMWOAuth'] = __DIR__ . '/frontend/specialpages/SpecialMWOAuth.php';
$wgAutoloadClasses['MediaWiki\Extensions\OAuth\SpecialMWOAuthConsumerRegistration'] =
	__DIR__ . '/frontend/specialpages/SpecialMWOAuthConsumerRegistration.php';
$wgAutoloadClasses['MediaWiki\Extensions\OAuth\MWOAuthListMyConsumersPager'] =
	__DIR__ . '/frontend/specialpages/SpecialMWOAuthConsumerRegistration.php';
$wgAutoloadClasses['MediaWiki\Extensions\OAuth\SpecialMWOAuthManageConsumers'] =
	__DIR__ . '/frontend/specialpages/SpecialMWOAuthManageConsumers.php';
$wgAutoloadClasses['MediaWiki\Extensions\OAuth\MWOAuthManageConsumersPager'] =
	__DIR__ . '/frontend/specialpages/SpecialMWOAuthManageConsumers.php';
$wgAutoloadClasses['MediaWiki\Extensions\OAuth\SpecialMWOAuthManageMyGrants'] =
	__DIR__ . '/frontend/specialpages/SpecialMWOAuthManageMyGrants.php';
$wgAutoloadClasses['MediaWiki\Extensions\OAuth\MWOAuthManageMyGrantsPager'] =
	__DIR__ . '/frontend/specialpages/SpecialMWOAuthManageMyGrants.php';
$wgAutoloadClasses['MediaWiki\Extensions\OAuth\SpecialMWOAuthListConsumers'] =
	__DIR__ . '/frontend/specialpages/SpecialMWOAuthListConsumers.php';
$wgAutoloadClasses['MediaWiki\Extensions\OAuth\MWOAuthListConsumersPager'] =
	__DIR__ . '/frontend/specialpages/SpecialMWOAuthListConsumers.php';

# Utility functions
$wgAutoloadClasses['MediaWiki\Extensions\OAuth\MWOAuthUtils'] = __DIR__ . '/backend/MWOAuthUtils.php';
$wgAutoloadClasses['MediaWiki\Extensions\OAuth\MWOAuthException'] = __DIR__ . '/backend/MWOAuthException.php';

# Data access objects
$wgAutoloadClasses['MediaWiki\Extensions\OAuth\MWOAuthDAO'] = __DIR__ . '/backend/MWOAuthDAO.php';
$wgAutoloadClasses['MediaWiki\Extensions\OAuth\MWOAuthToken'] = __DIR__ . '/backend/MWOAuthToken.php';
$wgAutoloadClasses['MediaWiki\Extensions\OAuth\MWOAuthConsumer'] = __DIR__ . '/backend/MWOAuthConsumer.php';
$wgAutoloadClasses['MediaWiki\Extensions\OAuth\MWOAuthConsumerAcceptance'] =
	__DIR__ . '/backend/MWOAuthConsumerAcceptance.php';
$wgAutoloadClasses['MediaWiki\Extensions\OAuth\MWOAuthRequest'] = __DIR__ . '/backend/MWOAuthRequest.php';

# Control logic
$wgAutoloadClasses['MediaWiki\Extensions\OAuth\MWOAuthDAOAccessControl'] =
	__DIR__ . '/control/MWOAuthDAOAccessControl.php';
$wgAutoloadClasses['MediaWiki\Extensions\OAuth\MWOAuthSubmitControl'] =
	__DIR__ . '/control/MWOAuthSubmitControl.php';
$wgAutoloadClasses['MediaWiki\Extensions\OAuth\MWOAuthConsumerSubmitControl'] =
	__DIR__ . '/control/MWOAuthConsumerSubmitControl.php';
$wgAutoloadClasses['MediaWiki\Extensions\OAuth\MWOAuthConsumerAcceptanceSubmitControl'] =
	__DIR__ . '/control/MWOAuthConsumerAcceptanceSubmitControl.php';
$wgAutoloadClasses['MediaWiki\Extensions\OAuth\MWOAuthServer'] =
	__DIR__ . '/backend/MWOAuthServer.php'; // "MWOAuth1Protocol"?
$wgAutoloadClasses['MediaWiki\Extensions\OAuth\MWOAuthSignatureMethod_RSA_SHA1'] =
	__DIR__ . '/backend/MWOAuthSignatureMethod.php';

# Echo
$wgAutoloadClasses['MediaWiki\Extensions\OAuth\EchoOAuthStageChangePresentationModel'] =
	__DIR__ . '/frontend/EchoOAuthStageChangePresentationModel.php';

# Library
$wgAutoloadClasses['MediaWiki\Extensions\OAuth\OAuthException'] = __DIR__ . '/lib/OAuth.php';
$wgAutoloadClasses['MediaWiki\Extensions\OAuth\OAuthConsumer'] = __DIR__ . '/lib/OAuth.php';
$wgAutoloadClasses['MediaWiki\Extensions\OAuth\OAuthToken'] = __DIR__ . '/lib/OAuth.php';
$wgAutoloadClasses['MediaWiki\Extensions\OAuth\OAuthSignatureMethod'] = __DIR__ . '/lib/OAuth.php';
$wgAutoloadClasses['MediaWiki\Extensions\OAuth\OAuthSignatureMethod_HMAC_SHA1'] = __DIR__ . '/lib/OAuth.php';
$wgAutoloadClasses['MediaWiki\Extensions\OAuth\OAuthSignatureMethod_PLAINTEXT'] = __DIR__ . '/lib/OAuth.php';
$wgAutoloadClasses['MediaWiki\Extensions\OAuth\OAuthSignatureMethod_RSA_SHA1'] = __DIR__ . '/lib/OAuth.php';
$wgAutoloadClasses['MediaWiki\Extensions\OAuth\OAuthRequest'] = __DIR__ . '/lib/OAuth.php';
$wgAutoloadClasses['MediaWiki\Extensions\OAuth\OAuthServer'] = __DIR__ . '/lib/OAuth.php';
$wgAutoloadClasses['MediaWiki\Extensions\OAuth\OAuthDataStore'] = __DIR__ . '/lib/OAuth.php';
$wgAutoloadClasses['MediaWiki\Extensions\OAuth\OAuthUtil'] = __DIR__ . '/lib/OAuth.php';

# Storage
$wgAutoloadClasses['MediaWiki\Extensions\OAuth\MWOAuthDataStore'] = __DIR__ . '/backend/MWOAuthDataStore.php';

# Schema changes
$wgAutoloadClasses['MediaWiki\Extensions\OAuth\MWOAuthUpdaterHooks'] = __DIR__ . '/backend/schema/MWOAuthUpdater.hooks.php';

# Session provider
$wgAutoloadClasses['MediaWiki\Extensions\OAuth\MWOAuthSessionProvider'] = __DIR__ . '/api/MWOAuthSessionProvider.php';

$wgHooks['ChangeTagCanCreate'][] = 'MediaWiki\Extensions\OAuth\MWOAuthHooks::onChangeTagCanCreate';
$wgHooks['ListDefinedTags'][] = array( 'MediaWiki\Extensions\OAuth\MWOAuthHooks::getUsedConsumerTags', false );
$wgHooks['ChangeTagsListActive'][] = array( 'MediaWiki\Extensions\OAuth\MWOAuthHooks::getUsedConsumerTags', true );
$wgHooks['MergeAccountFromTo'][] = 'MediaWiki\Extensions\OAuth\MWOAuthHooks::onMergeAccountFromTo';
$wgHooks['CentralAuthGlobalUserMerged'][] = 'MediaWiki\Extensions\OAuth\MWOAuthHooks::onCentralAuthGlobalUserMerged';
$wgHooks['LoadExtensionSchemaUpdates'][] = 'MediaWiki\Extensions\OAuth\MWOAuthUpdaterHooks::addSchemaUpdates';
$wgHooks['UnitTestsList'][] = 'MediaWiki\Extensions\OAuth\MWOAuthHooks::onUnitTestsList';

$wgSpecialPages['OAuth'] = 'MediaWiki\Extensions\OAuth\SpecialMWOAuth';
$wgSpecialPages['OAuthManageMyGrants'] = 'MediaWiki\Extensions\OAuth\SpecialMWOAuthManageMyGrants';
$wgSpecialPages['OAuthListConsumers'] = 'MediaWiki\Extensions\OAuth\SpecialMWOAuthListConsumers';

$wgHooks['GetPreferences'][] = 'MediaWiki\Extensions\OAuth\MWOAuthUIHooks::onGetPreferences';
$wgHooks['MessagesPreLoad'][] = 'MediaWiki\Extensions\OAuth\MWOAuthUIHooks::onMessagesPreLoad';
$wgHooks['SpecialPageAfterExecute'][] = 'MediaWiki\Extensions\OAuth\MWOAuthUIHooks::onSpecialPageAfterExecute';
$wgHooks['SpecialPageBeforeFormDisplay'][] = 'MediaWiki\Extensions\OAuth\MWOAuthUIHooks::onSpecialPageBeforeFormDisplay';
$wgHooks['BeforeCreateEchoEvent'][] = 'MediaWiki\Extensions\OAuth\MWOAuthUIHooks::onBeforeCreateEchoEvent';

$wgResourceModules['ext.MWOAuth.BasicStyles'] = array(
	'position'		=> 'top',
	'styles'        => array( 'ext.MWOAuth.BasicStyles.css' ),
	'localBasePath' => __DIR__ . '/frontend/modules',
	'remoteExtPath' => 'OAuth/frontend/modules'
);
$wgResourceModules['ext.MWOAuth.AuthorizeForm'] = array(
	'position'		=> 'top',
	'styles'        => array('ext.MWOAuth.AuthorizeForm.css' ),
	'localBasePath' => __DIR__ . '/frontend/modules',
	'remoteExtPath' => 'OAuth/frontend/modules'
);
$wgResourceModules['ext.MWOAuth.AuthorizeDialog'] = array(
	'scripts'       => array( 'ext.MWOAuth.AuthorizeDialog.js' ),
	'dependencies'  => array( 'jquery.ui.dialog' ),
	'localBasePath' => __DIR__ . '/frontend/modules',
	'remoteExtPath' => 'OAuth/frontend/modules',
	'messages'      => array( 'mwoauth-desc' )
);

if ( class_exists( 'MediaWiki\\Session\\SessionManager' ) ) {
	$wgSessionProviders['MediaWiki\\Extensions\\OAuth\\MWOAuthSessionProvider'] = array(
		'class' => 'MediaWiki\\Extensions\\OAuth\\MWOAuthSessionProvider',
		'args' => array()
	);
} else {
	// @todo: Remove this when we drop support for MW core without SessionManager
	$wgHooks['UserLoadFromSession'][] = 'MWOAuthAPISetup::onUserLoadFromSession';
	$wgHooks['UserLoadAfterLoadFromSession'][] = 'MWOAuthAPISetup::onUserLoadAfterLoadFromSession';
	$wgHooks['UserGetRights'][] = 'MWOAuthAPISetup::onUserGetRights';
	$wgHooks['UserIsEveryoneAllowed'][] = 'MWOAuthAPISetup::onUserIsEveryoneAllowed';
	$wgHooks['ApiCheckCanExecute'][] = 'MWOAuthAPISetup::onApiCheckCanExecute';
	$wgHooks['RecentChange_save'][] = 'MWOAuthAPISetup::onRecentChange_save';
}

$wgHooks['CentralAuthAbortCentralAuthToken'][] = 'MWOAuthAPISetup::onCentralAuthAbortCentralAuthToken';
$wgHooks['TestCanonicalRedirect'][] = 'MWOAuthAPISetup::onTestCanonicalRedirect';

# Set default $wgMWOAuthCentralWiki, before SessionManager starts
$wgHooks['SetupAfterCache'][] = 'MediaWiki\Extensions\OAuth\MWOAuthHooks::onSetupAfterCache';

# Actually register special pages
$wgExtensionFunctions[] = function() {
	\MediaWiki\Extensions\OAuth\MWOAuthUISetup::conditionalSetup();
};

if ( file_exists( __DIR__ . '/vendor/autoload.php' ) ) {
	require_once __DIR__ . '/vendor/autoload.php';
}
