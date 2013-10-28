<?php
/**
 * Internationalisation file for OAuth extension.
 *
 * @file
 * @ingroup Extensions
 */

$messages = array();

$messages['en'] = array(
	'mwoauth' => 'OAuth',
	'mwoauth-desc' => 'OAuth 1.0a API Authorization',

	'mwoauth-verified' => 'The application is now allowed to access MediaWiki on your behalf.

To complete the process, provide this verification value to the application: \'\'\'$1\'\'\'',

	'mwoauth-missing-field' => 'Missing value for "$1" field',
	'mwoauth-invalid-field' => 'Invalid value provided for "$1" field',
	'mwoauth-invalid-field-generic' => 'Invalid value provided',

	'mwoauth-field-hidden' => '(this information is hidden)',
	'mwoauth-field-private' => '(this information is private)',

	'mwoauth-grant-generic' => '"$1" rights bundle',
	'mwoauth-prefs-managegrants' => 'Connected apps:',
	'mwoauth-prefs-managegrantslink' => 'Manage $1 connected {{PLURAL:$1|application|applications}}',

	'mwoauth-consumer-allwikis' => 'All projects on this site',
	'mwoauth-consumer-key' => 'Consumer key:',
	'mwoauth-consumer-name' => 'Application name:',
	'mwoauth-consumer-version' => 'Consumer version:',
	'mwoauth-consumer-user' => 'Publisher:',
	'mwoauth-consumer-stage' => 'Current status:',
	'mwoauth-consumer-email' => 'Contact email address:',
	'mwoauth-consumer-description' => 'Application description:',
	'mwoauth-consumer-callbackurl' => 'OAuth "callback" URL:',
	'mwoauth-consumer-grantsneeded' => 'Applicable grants:',
	'mwoauth-consumer-required-grant' => 'Applicable to consumer',
	'mwoauth-consumer-wiki' => 'Applicable wiki:',
	'mwoauth-consumer-wiki-thiswiki' => 'Current wiki ($1)',
	'mwoauth-consumer-wiki-other' => 'Specific wiki',
	'mwoauth-consumer-restrictions' => 'Usage restrictions:',
	'mwoauth-consumer-restrictions-json' => 'Usage restrictions (JSON):',
	'mwoauth-consumer-rsakey' => 'Public RSA key:',
	'mwoauth-consumer-secretkey' => 'Consumer secret token:',
	'mwoauth-consumer-accesstoken' => 'Access token:',
	'mwoauth-consumer-reason' => 'Reason:',
	'mwoauth-consumer-email-unconfirmed' => 'Your account email address has not yet been confirmed.',
	'mwoauth-consumer-email-mismatched' => 'Provided email address must match that of your account.',
	'mwoauth-consumer-alreadyexists' => 'A consumer with this name/version/publisher combination already exists',
	'mwoauth-consumer-alreadyexistsversion' => 'A consumer with this name/publisher combination already exists with an equal or higher version ("$1")',
	'mwoauth-consumer-not-accepted' => 'Cannot update information for a pending consumer request',
	'mwoauth-consumer-not-proposed' => 'The consumer is not currently proposed',
	'mwoauth-consumer-not-disabled' => 'The consumer is not currently disabled',
	'mwoauth-consumer-not-approved' => 'The consumer is not approved (it may have been disabled)',
	'mwoauth-missing-consumer-key' => 'No consumer key was provided.',
	'mwoauth-invalid-consumer-key' => 'No consumer exists with the given key.',
	'mwoauth-invalid-access-token' => 'No access token exists with the given key.',
	'mwoauth-invalid-access-wrongwiki' => 'The consumer can only be used on wiki "$1".',
	'mwoauth-consumer-conflict' => 'Someone changed the attributes of this consumer as you viewed it. Please try again. You may want to check the change log.',

	'mwoauth-consumer-stage-proposed' => 'proposed',
	'mwoauth-consumer-stage-rejected' => 'rejected',
	'mwoauth-consumer-stage-expired' => 'expired',
	'mwoauth-consumer-stage-approved' => 'approved',
	'mwoauth-consumer-stage-disabled' => 'disabled',
	'mwoauth-consumer-stage-suppressed' => 'suppressed',

	'mwoauthconsumerregistration' => 'OAuth consumer registration',
	'mwoauthconsumerregistration-notloggedin' => 'You have to be logged in to access this page.',
	'mwoauthconsumerregistration-navigation' => 'Navigation:',
	'mwoauthconsumerregistration-propose' => 'Propose new consumer',
	'mwoauthconsumerregistration-list' => 'My consumer list',
	'mwoauthconsumerregistration-main' => 'Main',
	'mwoauthconsumerregistration-propose-text' => 'Developers should use the form below to propose a new OAuth consumer (see the [//www.mediawiki.org/wiki/Extension:OAuth extension documentation] for more details). After submitting this form, you will receive a token that your application will use to identify itself to MediaWiki. An OAuth administrator will need to approve your application before it can be authorized by other users.

A few recommendations and remarks:
* Try to use as few grants as possible. Avoid grants that are not actually needed now.
* Versions are of the form "major.minor.release" (the last two being optional) and increase as grant changes are needed.
* Please provide a public RSA key (in PEM format) if possible; otherwise a (less secure) secret token will have to be used.
* Use the JSON restrictions field to limit access of this consumer to IP addresses in those CIDR ranges.
* You can use a wiki ID to restrict the consumer to a single wiki on this site (use "*" for all wikis).
* The email address provided must match that of your account (which must have been confirmed).',
	'mwoauthconsumerregistration-update-text' => 'Use the form below to update aspects of an OAuth consumer you control.

All values here will overwrite any previous ones. Do not leave blank fields unless you intend to clear those values.',
	'mwoauthconsumerregistration-maintext' => 'This page is for letting developers propose and update OAuth consumer applications in this site\'s registry.

From here, you can:
* [[Special:MWOAuthConsumerRegistration/propose|Request a token for a new consumer]].
* [[Special:MWOAuthConsumerRegistration/list|Manage your existing consumers]].

For more information about OAuth, please see the [//www.mediawiki.org/wiki/Extension:OAuth extension documentation].',
	'mwoauthconsumerregistration-propose-legend' => 'New OAuth consumer application',
	'mwoauthconsumerregistration-update-legend' => 'Update OAuth consumer application',
	'mwoauthconsumerregistration-propose-submit' => 'Propose consumer',
	'mwoauthconsumerregistration-update-submit' => 'Update consumer',
	'mwoauthconsumerregistration-none' => 'You do not control any OAuth consumers.',
	'mwoauthconsumerregistration-name' => 'Consumer',
	'mwoauthconsumerregistration-user' => 'Publisher',
	'mwoauthconsumerregistration-description' => 'Description',
	'mwoauthconsumerregistration-email' => 'Contact email',
	'mwoauthconsumerregistration-consumerkey' => 'Consumer key',
	'mwoauthconsumerregistration-stage' => 'Status',
	'mwoauthconsumerregistration-lastchange' => 'Last change',
	'mwoauthconsumerregistration-manage' => 'manage',
	'mwoauthconsumerregistration-resetsecretkey' => 'Reset the secret key to a new value',
	'mwoauthconsumerregistration-proposed' => 'Your OAuth consumer request has been received.

You have been assigned a consumer token of \'\'\'$1\'\'\' and a secret token of \'\'\'$2\'\'\'. \'\'Please record these for future reference.\'\'',
	'mwoauthconsumerregistration-updated' => 'Your OAuth consumer registry was successfully updated.',
	'mwoauthconsumerregistration-secretreset' => 'You have been assigned a consumer secret token of \'\'\'$1\'\'\'. \'\'Please record this for future reference.\'\'',

	'mwoauthmanageconsumers' => 'Manage OAuth consumers',
	'mwoauthmanageconsumers-notloggedin' => 'You have to be logged in to access this page.',
	'mwoauthmanageconsumers-type' => 'Queues:',
	'mwoauthmanageconsumers-showproposed' => 'Proposed requests',
	'mwoauthmanageconsumers-showrejected' => 'Rejected requests',
	'mwoauthmanageconsumers-showexpired' => 'Expired requests',
	'mwoauthmanageconsumers-main' => 'Main',
	'mwoauthmanageconsumers-maintext' => 'This page is meant for handling OAuth (see http://oauth.net) consumer application requests and managing established OAuth consumers.',
	'mwoauthmanageconsumers-queues' => 'Select a consumer confirmation queue from below:',
	'mwoauthmanageconsumers-q-proposed' => 'Queue of proposed consumer requests',
	'mwoauthmanageconsumers-q-rejected' => 'Queue of rejected consumer requests',
	'mwoauthmanageconsumers-q-expired' => 'Queue of expired consumer requests',
	'mwoauthmanageconsumers-lists' => 'Select a consumer status list from below:',
	'mwoauthmanageconsumers-l-approved' => 'List of currently approved consumers',
	'mwoauthmanageconsumers-l-disabled' => 'List of currently disabled consumers',
	'mwoauthmanageconsumers-none-proposed' => 'No proposed consumers in this list.',
	'mwoauthmanageconsumers-none-rejected' => 'No proposed consumers in this list.',
	'mwoauthmanageconsumers-none-expired' => 'No proposed consumers in this list.',
	'mwoauthmanageconsumers-none-approved' => 'No consumers meet this criteria.',
	'mwoauthmanageconsumers-none-disabled' => 'No consumers meet this criteria.',
	'mwoauthmanageconsumers-name' => 'Consumer',
	'mwoauthmanageconsumers-user' => 'Publisher',
	'mwoauthmanageconsumers-description' => 'Description',
	'mwoauthmanageconsumers-email' => 'Contact email',
	'mwoauthmanageconsumers-consumerkey' => 'Consumer key',
	'mwoauthmanageconsumers-lastchange' => 'Last change',
	'mwoauthmanageconsumers-review' => 'review/manage',
	'mwoauthmanageconsumers-confirm-text' => 'Use this form to approve, reject, disable, or re-enable this consumer.',
	'mwoauthmanageconsumers-confirm-legend' => 'Manage OAuth consumer',
	'mwoauthmanageconsumers-action' => 'Change status:',
	'mwoauthmanageconsumers-approve' => 'Approved',
	'mwoauthmanageconsumers-reject' => 'Rejected',
	'mwoauthmanageconsumers-rsuppress' => 'Rejected and suppressed',
	'mwoauthmanageconsumers-disable' => 'Disabled',
	'mwoauthmanageconsumers-dsuppress' => 'Disabled and suppressed',
	'mwoauthmanageconsumers-reenable' => 'Approved',
	'mwoauthmanageconsumers-reason' => 'Reason:',
	'mwoauthmanageconsumers-confirm-submit' => 'Update consumer status',
	'mwoauthmanageconsumers-viewing' => 'User "$1" is currently viewing this consumer',
	'mwoauthmanageconsumers-success-approved' => 'Request has been approved.',
	'mwoauthmanageconsumers-success-rejected' => 'Request has been rejected.',
	'mwoauthmanageconsumers-success-disabled' => 'Consumer has been disabled.',
	'mwoauthmanageconsumers-success-reanable' => 'Consumer has been re-enabled.',
	'mwoauthmanageconsumers-search-name' => 'consumers with this name',
	'mwoauthmanageconsumers-search-publisher' => 'consumers by this user',

	'mwoauthlistconsumers' => 'List OAuth consumers',
	'mwoauthlistconsumers-legend' => 'Browse OAuth consumers',
	'mwoauthlistconsumers-view' => 'details',
	'mwoauthlistconsumers-none' => 'No consumers found meeting this criteria.',
	'mwoauthlistconsumers-name' => 'Application name',
	'mwoauthlistconsumers-version' => 'Consumer version',
	'mwoauthlistconsumers-user' => 'Publisher',
	'mwoauthlistconsumers-description' => 'Description',
	'mwoauthlistconsumers-wiki' => 'Applicable wiki',
	'mwoauthlistconsumers-callbackurl' => 'OAuth "callback URL"',
	'mwoauthlistconsumers-grants' => 'Applicable grants',
	'mwoauthlistconsumers-basicgrantsonly' => '(basic access only)',
	'mwoauthlistconsumers-status' => 'Status',
	'mwoauth-consumer-stage-any' => 'any',
	'mwoauthlistconsumers-status-proposed' => 'proposed',
	'mwoauthlistconsumers-status-approved' => 'approved',
	'mwoauthlistconsumers-status-disabled' => 'disabled',
	'mwoauthlistconsumers-status-rejected' => 'rejected',
	'mwoauthlistconsumers-status-expired' => 'expired',

	'mwoauthmanagemygrants' => 'Manage account OAuth grants',
	'mwoauthmanagemygrants-text' => 'This page lists any applications that can use your account. For any such application, the scope of its access is limited by the permissions that you granted to the application when you authorized it to act on your behalf. If you separately authorized a consumer to access different "sister" projects on your behalf, then you will see separate configuration for each such project below.',
	'mwoauthmanagemygrants-notloggedin' => 'You have to be logged in to access this page.',
	'mwoauthmanagemygrants-navigation' => 'Navigation:',
	'mwoauthmanagemygrants-showlist' => 'Accepted consumer list',
	'mwoauthmanagemygrants-none' => 'No applications are currently connected to your account.',
	'mwoauthmanagemygrants-name' => 'Consumer name',
	'mwoauthmanagemygrants-user' => 'Publisher',
	'mwoauthmanagemygrants-description' => 'Description',
	'mwoauthmanagemygrants-wiki' => 'Applicable wiki',
	'mwoauthmanagemygrants-wikiallowed' => 'Allowed on wiki',
	'mwoauthmanagemygrants-grants' => 'Applicable grants',
	'mwoauthmanagemygrants-grantsallowed' => 'Grants allowed',
	'mwoauthmanagemygrants-applicablegrantsallowed' => 'Applicable grants allowed:',
	'mwoauthmanagemygrants-consumerkey' => 'Consumer key',
	'mwoauthmanagemygrants-review' => 'manage access',
	'mwoauthmanagemygrants-revoke' => 'revoke access',
	'mwoauthmanagemygrants-grantaccept' => 'Granted',
	'mwoauthmanagemygrants-update-text' => 'Use the form below to modify the permissions granted to an application (OAuth consumer) to act on your behalf.
* If you separately authorized an application to access different "sister site" projects on your behalf, then you will have separate configuration for each such project for that application.',
	'mwoauthmanagemygrants-revoke-text' => 'Use the form below to revoke access for an application (OAuth consumer) to act on your behalf.
* If you separately authorized an application to access different "sister site" projects on your behalf, then you will have separate configuration for each such project for that application.
* If you want to totally revoke access to an application, be sure to revoke it from all projects that you accepted it on.',
	'mwoauthmanagemygrants-confirm-legend' => 'Manage consumer access token',
	'mwoauthmanagemygrants-update' => 'Update grants',
	'mwoauthmanagemygrants-renounce' => 'Deauthorize',
	'mwoauthmanagemygrants-action' => 'Change status:',
	'mwoauthmanagemygrants-confirm-submit' => 'Update access token status',
	'mwoauthmanagemygrants-success-update' => 'The access token for this consumer has been updated.',
	'mwoauthmanagemygrants-success-renounce' => 'The access token for this consumer has been deleted.',

	'logentry-mwoauthconsumer-propose' => '$1 {{GENDER:$2|proposed}} an OAuth consumer (consumer key $4)',
	'logentry-mwoauthconsumer-update' => '$1 {{GENDER:$2|updated}} an OAuth consumer (consumer key $4)',
	'logentry-mwoauthconsumer-approve' => '$1 {{GENDER:$2|approved}} an OAuth consumer by $3 (consumer key $4)',
	'logentry-mwoauthconsumer-reject' => '$1 {{GENDER:$2|rejected}} an OAuth consumer by $3 (consumer key $4)',
	'logentry-mwoauthconsumer-disable' => '$1 {{GENDER:$2|disabled}} an OAuth consumer by $3 (consumer key $4)',
	'logentry-mwoauthconsumer-reenable' => '$1 {{GENDER:$2|re-enabled}} an OAuth consumer by $3 (consumer key $4)',

	'mwoauthconsumer-consumer-logpage' => 'OAuth consumer log',
	'mwoauthconsumer-consumer-logpagetext' => 'Log of approvals, rejections, and disabling of registered OAuth consumers.',

	'mwoauth-bad-request' => 'There was an error in your OAuth request.',
	'mwoauthdatastore-access-token-not-found' => 'No approved grant was found for that authorization token.',
	'mwoauthdatastore-request-token-not-found' => 'No request was found for that token.',
	'mwoauthdatastore-bad-token' => 'No token was found matching your request.',
	'mwoauthdatastore-bad-verifier' => 'The verification code provided was not valid.',
	'mwoauthdatastore-invalid-token-type' => 'The requested token type is invalid.',
	'mwoauthgrants-general-error' => 'There was an error in your OAuth request.',
	'mwoauthserver-bad-consumer' => 'No approved consumer was found for the provided key.',
	'mwoauthserver-insufficient-rights' => 'You do not have sufficient rights to perform this action.',
	'mwoauthserver-invalid-request-token' => 'Invalid token in your request.',
	'mwoauthserver-invalid-user-hookabort' => 'This user cannot use OAuth.',

	'mwoauth-invalid-authorization-title' => 'OAuth authorization error',
	'mwoauth-invalid-authorization' => 'The authorization headers in your request are not valid: $1',
	'mwoauth-invalid-authorization-wrong-wiki' => 'The authorization headers in your request are not valid for $1',
	'mwoauth-invalid-authorization-invalid-user' => 'The authorization headers in your request are for a user that doesn\'t exist here',
	'mwoauth-invalid-authorization-wrong-user' => 'The authorization headers in your request are for a different user',
	'mwoauth-invalid-authorization-not-approved' => 'The authorization headers in your request are for an OAuth consumer that is not currently approved',
	'mwoauth-invalid-authorization-blocked-user' => 'The authorization headers in your request are for a user who is blocked',

	'mwoauth-form-description-allwikis' => "Hi $1,

'''$2''' would like to do the following actions on your behalf on all projects of this site:


$4",
	'mwoauth-form-description-onewiki' => "Hi $1,

'''$2''' would like to do the following actions on your behalf on ''$4'':


$5",
	'mwoauth-form-description-allwikis-nogrants' => "Hi $1,

'''$2''' would like to have basic access on your behalf on all projects of this site.",
	'mwoauth-form-description-onewiki-nogrants' => "Hi $1,

'''$2''' would like to have basic access on your behalf on ''$4''.",

	'mwoauth-form-legal' => '',
	'mwoauth-form-privacypolicy-link' => '[[{{ns:Project}}:Privacy policy|Privacy Policy]]',
	'mwoauth-form-button-approve' => 'Allow',
	'mwoauth-form-button-cancel' => 'Cancel',
	'mwoauth-authorize-form-invalid-user' => 'This user account cannot use OAuth, because the account on this wiki, and the account on the central OAuth wiki are not linked.',
	'mwoauth-error' => 'OAuth Error',
	'mwoauth-grants-heading' => 'Requested permissions:',
	'mwoauth-grants-nogrants' => 'The application has not requested any permissions.',
	'mwoauth-acceptance-cancelled' => 'You have cancelled this request to authorize an OAuth consumer to act on your behalf.',

	'mwoauth-grant-group-page-interaction' => 'Interact with pages',
	'mwoauth-grant-group-file-interaction' => 'Interact with media',
	'mwoauth-grant-group-watchlist-interaction' => 'Interact with your watchlist',
	'mwoauth-grant-group-email' => 'Send email',
	'mwoauth-grant-group-high-volume' => 'Perform high volume activity',
	'mwoauth-grant-group-customization' => 'Customization and preferences',
	'mwoauth-grant-group-administration' => 'Perform adminstrative actions',
	'mwoauth-grant-group-other' => 'Miscellaneous activity',

	'mwoauth-grant-blockusers' => 'Block and unblock users',
	'mwoauth-grant-createaccount' => 'Create accounts',
	'mwoauth-grant-createeditmovepage' => 'Create, edit, and move pages',
	'mwoauth-grant-delete' => 'Delete pages, revisions, and log entries',
	'mwoauth-grant-editinterface' => 'Edit the MediaWiki namespace and user CSS/JS',
	'mwoauth-grant-editmycssjs' => 'Edit your own user CSS/JS',
	'mwoauth-grant-editmywatchlist' => 'Edit your watchlist',
	'mwoauth-grant-editpage' => 'Edit existing pages',
	'mwoauth-grant-editprotected' => 'Edit protected pages',
	'mwoauth-grant-highvolume' => 'High-volume editing',
	'mwoauth-grant-oversight' => 'Hide users and suppress revisions',
	'mwoauth-grant-patrol' => 'Patrol changes to pages',
	'mwoauth-grant-protect' => 'Protect and unprotect pages',
	'mwoauth-grant-rollback' => 'Rollback changes to pages',
	'mwoauth-grant-sendemail' => 'Send email to other users',
	'mwoauth-grant-uploadeditmovefile' => 'Upload, replace, and move files',
	'mwoauth-grant-uploadfile' => 'Upload new files',
	'mwoauth-grant-useoauth' => 'Basic rights',
	'mwoauth-grant-viewdeleted' => 'View deleted information',
	'mwoauth-grant-viewmywatchlist' => 'View your watchlist',

	'mwoauth-oauth-exception' => 'An error occurred in the OAuth protocol: $1',
	'mwoauth-callback-not-oob' => 'oauth_callback must be set, and must be set to "oob" (case-sensitive)',

	'right-mwoauthproposeconsumer' => 'Propose new OAuth consumers',
	'right-mwoauthupdateownconsumer' => 'Update OAuth consumers you control',
	'right-mwoauthmanageconsumer' => 'Manage OAuth consumers',
	'right-mwoauthsuppress' => 'Suppress OAuth consumers',
	'right-mwoauthviewsuppressed' => 'View suppressed OAuth consumers',
	'right-mwoauthviewprivate' => 'View private OAuth data',
	'right-mwoauthmanagemygrants' => 'Manage OAuth grants',

	'action-mwoauthmanageconsumer' => 'manage OAuth consumers',
	'action-mwoauthmanagemygrants' => 'manage your OAuth grants',
	'action-mwoauthproposeconsumer' => 'propose new OAuth consumers',
	'action-mwoauthupdateownconsumer' => 'update OAuth consumers you control',
	'action-mwoauthviewsuppressed' => 'view suppressed OAuth consumers',
);

/** Message documentation (Message documentation)
 * @author Raymond
 * @author Shirayuki
 * @author Siebrand
 */
$messages['qqq'] = array(
	'mwoauth' => 'Title of MWOAuth page',
	'mwoauth-desc' => 'Used as subtitle.',
	'mwoauth-verified' => 'Displayed to the user when the consumer does not have a callback URL, to provide the verification token that the consumer needs to complete the authorization process.

Parameters:
* $1 - verification token
* $2 - (Unused) request token (the app should already have this)',
	'mwoauth-missing-field' => 'Parameters:
* $1 - field name
See also:
* {{msg-mw|Mwoauth-invalid-field}}',
	'mwoauth-invalid-field' => 'Parameters:
* $1 - field name
See also:
* {{msg-mw|Mwoauth-missing-field}}',
	'mwoauth-invalid-field-generic' => 'Used as generic error message for form field validation.',
	'mwoauth-field-hidden' => 'Used if the information has been deleted and the user is not allowed to view suppressed information.

See also:
* {{msg-mw|Mwoauth-field-private}}',
	'mwoauth-field-private' => 'Used if the user is not allowed to view private information.

See also:
* {{msg-mw|Mwoauth-field-hidden}}',
	'mwoauth-grant-generic' => 'Used if the grant name is not defined. Parameters:
* $1 - grant name

Defined grants (grant name refers: blockusers, createeditmovepage, ...):
* {{msg-mw|Mwoauth-grant-blockusers}}
* {{msg-mw|Mwoauth-grant-createaccount}}
* {{msg-mw|Mwoauth-grant-createeditmovepage}}
* {{msg-mw|Mwoauth-grant-delete}}
* {{msg-mw|Mwoauth-grant-editinterface}}
* {{msg-mw|Mwoauth-grant-editmycssjs}}
* {{msg-mw|Mwoauth-grant-editmywatchlist}}
* {{msg-mw|Mwoauth-grant-editpage}}
* {{msg-mw|Mwoauth-grant-editprotected}}
* {{msg-mw|Mwoauth-grant-highvolume}}
* {{msg-mw|Mwoauth-grant-oversight}}
* {{msg-mw|Mwoauth-grant-patrol}}
* {{msg-mw|Mwoauth-grant-protect}}
* {{msg-mw|Mwoauth-grant-rollback}}
* {{msg-mw|Mwoauth-grant-sendemail}}
* {{msg-mw|Mwoauth-grant-uploadeditmovefile}}
* {{msg-mw|Mwoauth-grant-uploadfile}}
* {{msg-mw|Mwoauth-grant-useoauth}}
* {{msg-mw|Mwoauth-grant-viewdeleted}}
* {{msg-mw|Mwoauth-grant-viewmywatchlist}}',
	'mwoauth-prefs-managegrants' => 'Used as label in [[Special:Preferences]].

See also:
* {{msg-mw|Mwoauth-prefs-managegrantslink}}.',
	'mwoauth-prefs-managegrantslink' => 'Used in [[Special:Preferences]]. See example: [[mw:Special:Preferences]].

Used as text for the link which points to [[Special:MWOAuthManageMyGrants]].

Preceded by the label {{msg-mw|Mwoauth-prefs-managegrants}}.

Parameters:
* $1 - Number of connected applications',
	'mwoauth-consumer-allwikis' => 'Description of scope of consumer access when the scope is all wiki projects on the site',
	'mwoauth-consumer-key' => 'Used as label for the "Consumer key" input box.
{{Identical|Consumer key}}',
	'mwoauth-consumer-name' => 'Used as label for the "Application name" input box.
{{Identical|Application name}}',
	'mwoauth-consumer-version' => 'Used as label for the "Version" input box.
{{Identical|Consumer version}}',
	'mwoauth-consumer-user' => 'Used as label for the "Central username" box.
{{Identical|Publisher}}',
	'mwoauth-consumer-stage' => 'Used as label for the "Stage" value

Followed by any one of the following messages:
* {{msg-mw|Mwoauth-consumer-stage-proposed}}
* {{msg-mw|Mwoauth-consumer-stage-rejected}}
* {{msg-mw|Mwoauth-consumer-stage-expired}}
* {{msg-mw|Mwoauth-consumer-stage-approved}}
* {{msg-mw|Mwoauth-consumer-stage-disabled}}
* {{msg-mw|Mwoauth-consumer-stage-suppressed}}
{{Identical|Current status}}',
	'mwoauth-consumer-email' => 'Used as label for the "Email address" input box.',
	'mwoauth-consumer-description' => 'Used as label for the "description" textarea.
{{Identical|Application description}}',
	'mwoauth-consumer-callbackurl' => 'Used as label for the "Callback URL" input box.

See [[w:Callback (computer programming)]].',
	'mwoauth-consumer-grantsneeded' => 'Used as label.

Followed by the list of grants.
{{Identical|Applicable grant}}',
	'mwoauth-consumer-required-grant' => 'Used as table column header.',
	'mwoauth-consumer-wiki' => 'Used as label for the input box. The default value for the input box is "*".
{{Identical|Applicable wiki}}',
	'mwoauth-consumer-wiki-thiswiki' => 'Label for selection-list option, indicating the wiki this user is currently visiting.

Parameters:
* $1 - wiki ID',
	'mwoauth-consumer-wiki-other' => "Label for selection-list option, indicating the user wants to type in another wiki's name.
{{Identical|Specific wiki}}",
	'mwoauth-consumer-restrictions' => 'Used as label for the textarea. (The value is written in JSON format.)

Followed by the textarea or the message {{msg-mw|Mwoauthmanageconsumers-field-hidden}}.
{{Identical|Usage restriction}}',
	'mwoauth-consumer-restrictions-json' => 'Used as label for the "Restrictions" textarea.
{{Identical|Usage restriction}}',
	'mwoauth-consumer-rsakey' => 'Used as label for the textarea.

Followed by the textarea or the message {{msg-mw|Mwoauthmanageconsumers-field-hidden}}.',
	'mwoauth-consumer-secretkey' => 'Used as label for the textarea.',
	'mwoauth-consumer-accesstoken' => 'Unused at this time.',
	'mwoauth-consumer-reason' => 'Used as label for the "Reason" value.
{{Identical|Reason}}',
	'mwoauth-consumer-email-unconfirmed' => 'Used as failure message when taking some action which requires email-confirmation.',
	'mwoauth-consumer-email-mismatched' => 'Used as failure message when taking some action.',
	'mwoauth-consumer-alreadyexists' => 'Used as failure message.',
	'mwoauth-consumer-alreadyexistsversion' => 'Used as failure message. Parameters:
* $1 - current consumer version number',
	'mwoauth-consumer-not-accepted' => 'Unused at this time.',
	'mwoauth-consumer-not-proposed' => 'Used as failure message.

See also:
* {{msg-mw|Mwoauth-consumer-not-disabled}}',
	'mwoauth-consumer-not-disabled' => 'Used as failure message.

See also:
* {{msg-mw|Mwoauth-consumer-not-proposed}}',
	'mwoauth-consumer-not-approved' => 'Used as failure message.',
	'mwoauth-missing-consumer-key' => 'Used as error message when showing consumer information.',
	'mwoauth-invalid-consumer-key' => 'Used as failure message.',
	'mwoauth-invalid-access-token' => 'Used as failure message.',
	'mwoauth-invalid-access-wrongwiki' => 'Used as error message. Parameters:
* $1 - the wiki ID the consumer is applicable to',
	'mwoauth-consumer-conflict' => 'Used as failure message.',
	'mwoauth-consumer-stage-proposed' => '{{Related|Mwoauth-consumer-stage}}
{{Identical|Proposed}}',
	'mwoauth-consumer-stage-rejected' => '{{Related|Mwoauth-consumer-stage}}
{{Identical|Rejected}}',
	'mwoauth-consumer-stage-expired' => '{{Related|Mwoauth-consumer-stage}}
{{Identical|Expired}}',
	'mwoauth-consumer-stage-approved' => '{{Related|Mwoauth-consumer-stage}}
{{Identical|Approved}}',
	'mwoauth-consumer-stage-disabled' => '{{Related|Mwoauth-consumer-stage}}
{{Identical|Disabled}}',
	'mwoauth-consumer-stage-suppressed' => '{{Related|Mwoauth-consumer-stage}}
{{Identical|Suppressed}}',
	'mwoauthconsumerregistration' => '{{doc-special|MWOAuthConsumerRegistration}}',
	'mwoauthconsumerregistration-notloggedin' => 'Used if not blocked, not read-only and not logged in.',
	'mwoauthconsumerregistration-navigation' => 'Used in page subtitle.
{{Identical|Navigation}}',
	'mwoauthconsumerregistration-propose' => 'Used in page subtitle link text',
	'mwoauthconsumerregistration-list' => 'Used in page subtitle link text',
	'mwoauthconsumerregistration-main' => 'Used as label for "View all" link.

Preceded by list of the links ("|" separated) which have any one of the following link texts:
* {{msg-mw|Mwoauthconsumerregistration-propose}}
* {{msg-mw|Mwoauthconsumerregistration-list}}
{{Identical|Main}}',
	'mwoauthconsumerregistration-propose-text' => 'Used as introduction text for the form.',
	'mwoauthconsumerregistration-update-text' => 'Used as introduction text for the form.',
	'mwoauthconsumerregistration-maintext' => 'Used as introduction text in [[Special:MWOAuthConsumerRegistration]].',
	'mwoauthconsumerregistration-propose-legend' => 'Used as fieldset label.',
	'mwoauthconsumerregistration-update-legend' => 'Used as fieldset label.',
	'mwoauthconsumerregistration-propose-submit' => 'Used as label for the Submit button.',
	'mwoauthconsumerregistration-update-submit' => 'Used as label for the Submit button.',
	'mwoauthconsumerregistration-none' => 'Used if there are no OAuth consumers to list.',
	'mwoauthconsumerregistration-name' => 'Used as table row header.
{{Identical|Consumer}}',
	'mwoauthconsumerregistration-user' => '{{Identical|Publisher}}',
	'mwoauthconsumerregistration-description' => '{{Identical|Description}}',
	'mwoauthconsumerregistration-email' => 'field on registration form for email',
	'mwoauthconsumerregistration-consumerkey' => 'Used as table row header.
{{Identical|Consumer key}}',
	'mwoauthconsumerregistration-stage' => 'Used as table row header.

Followed by any one of the following messages:
* {{msg-mw|Mwoauth-consumer-stage-proposed}}
* {{msg-mw|Mwoauth-consumer-stage-rejected}}
* {{msg-mw|Mwoauth-consumer-stage-expired}}
* {{msg-mw|Mwoauth-consumer-stage-approved}}
* {{msg-mw|Mwoauth-consumer-stage-disabled}}
* {{msg-mw|Mwoauth-consumer-stage-suppressed}}
{{Identical|Status}}',
	'mwoauthconsumerregistration-lastchange' => 'Used as table row header.
{{Identical|Last change}}',
	'mwoauthconsumerregistration-manage' => 'Used as link text.
{{Identical|Manage}}',
	'mwoauthconsumerregistration-resetsecretkey' => 'Used a label for a checkbox',
	'mwoauthconsumerregistration-proposed' => 'Used as success message.

Parameters:
* $1 - consumer key
* $2 - secret key',
	'mwoauthconsumerregistration-updated' => 'Shown as success message',
	'mwoauthconsumerregistration-secretreset' => 'Shown on success message. Parameters:
* $1 - new secret token',
	'mwoauthmanageconsumers' => '{{doc-special|MWOAuthManageConsumers}}
{{Identical|Manage OAuth consumer}}',
	'mwoauthmanageconsumers-notloggedin' => 'Used if the user is not logged in.',
	'mwoauthmanageconsumers-type' => 'Used as subtitle.

Followed by any one (or zero) of the following messages:
* {{msg-mw|Mwoauthmanageconsumers-showproposed}}
* {{msg-mw|Mwoauthmanageconsumers-showrejected}}
* {{msg-mw|Mwoauthmanageconsumers-showexpired}}
{{Identical|Queue}}',
	'mwoauthmanageconsumers-showproposed' => 'Used as link text or plain text.

See also:
* {{msg-mw|Mwoauthmanageconsumers-type}}',
	'mwoauthmanageconsumers-showrejected' => 'Used as link text or plain text.

See also:
* {{msg-mw|Mwoauthmanageconsumers-type}}',
	'mwoauthmanageconsumers-showexpired' => 'Used as link text or plain text.

See also:
* {{msg-mw|Mwoauthmanageconsumers-type}}',
	'mwoauthmanageconsumers-main' => 'Used as link text.

Preceded by a list of links which have any one of the following labels:
* {{msg-mw|Mwoauthmanageconsumers-showproposed}}
* {{msg-mw|Mwoauthmanageconsumers-showrejected}}
* {{msg-mw|Mwoauthmanageconsumers-showexpired}}
{{Identical|Main}}',
	'mwoauthmanageconsumers-maintext' => 'Used in [[Special:MWOAuthManageConsumers]].

Followed by the message {{msg-mw|Mwoauthmanageconsumers-queues}}.',
	'mwoauthmanageconsumers-queues' => 'Used as label.

Followed by a list of links which point to [[Special:MWOAuthManageConsumers]].

Text for the link is any one of the following messages:
* {{msg-mw|Mwoauthmanageconsumers-q-proposed}}
* {{msg-mw|Mwoauthmanageconsumers-q-rejected}}
* {{msg-mw|Mwoauthmanageconsumers-q-expired}}',
	'mwoauthmanageconsumers-q-proposed' => 'Used as text for the link which points to [[Special:MWOAuthManageConsumers]].

The list is preceded by the label {{msg-mw|Mwoauthmanageconsumers-queues}}.',
	'mwoauthmanageconsumers-q-rejected' => 'Used as text for the link which points to [[Special:MWOAuthManageConsumers]].

The list is preceded by the label {{msg-mw|Mwoauthmanageconsumers-queues}}.',
	'mwoauthmanageconsumers-q-expired' => 'Used as text for the link which points to [[Special:MWOAuthManageConsumers]].

The list is preceded by the label {{msg-mw|Mwoauthmanageconsumers-queues}}.',
	'mwoauthmanageconsumers-lists' => 'Used as subtitle which is followed by a list of links.

The links are points to [[Special:MWOAuthManageConsumers]].

The text fo the link is any one of the following messages:
* {{msg-mw|Mwoauthmanageconsumers-l-approved}}
* {{msg-mw|Mwoauthmanageconsumers-l-disabled}}',
	'mwoauthmanageconsumers-l-approved' => 'Used as text for the link which points to [[Special:MWOAuthManageConsumers]].

The list is preceded by the label {{msg-mw|Mwoauthmanageconsumers-lists}}.',
	'mwoauthmanageconsumers-l-disabled' => 'Used as text for the link which points to [[Special:MWOAuthManageConsumers]].

The list is preceded by the label {{msg-mw|Mwoauthmanageconsumers-lists}}.',
	'mwoauthmanageconsumers-none-proposed' => 'Used if there are not consumers to list.
{{Related|Mwoauthmanageconsumers-none}}',
	'mwoauthmanageconsumers-none-rejected' => 'Used if there are not consumers to list.
{{Related|Mwoauthmanageconsumers-none}}',
	'mwoauthmanageconsumers-none-expired' => 'Used if there are not consumers to list.
{{Related|Mwoauthmanageconsumers-none}}',
	'mwoauthmanageconsumers-none-approved' => 'Used if there are not consumers to list.
{{Related|Mwoauthmanageconsumers-none}}',
	'mwoauthmanageconsumers-none-disabled' => 'Used if there are not consumers to list.
{{Related|Mwoauthmanageconsumers-none}}',
	'mwoauthmanageconsumers-name' => 'Used as table row header.
{{Identical|Consumer}}',
	'mwoauthmanageconsumers-user' => 'Used as table row header for the "Central username".
{{Identical|Publisher}}',
	'mwoauthmanageconsumers-description' => 'Used as table row header.
{{Identical|Description}}',
	'mwoauthmanageconsumers-email' => 'Followed by an email address or the message {{msg-mw|Mwoauth-consumer-stage-suppressed}}.',
	'mwoauthmanageconsumers-consumerkey' => 'Used as table row header.
{{Identical|Consumer key}}',
	'mwoauthmanageconsumers-lastchange' => 'Used as table row header.
{{Identical|Last change}}',
	'mwoauthmanageconsumers-review' => 'Used as label for the link which points to [[Special:MWOAuthManageConsumers]].',
	'mwoauthmanageconsumers-confirm-text' => 'Used as introduction text for the form.',
	'mwoauthmanageconsumers-confirm-legend' => 'Used as fieldset label.
{{Identical|Manage OAuth consumer}}',
	'mwoauthmanageconsumers-action' => 'Used as label for the radio box group.

Followed by the following radio boxes:
* {{msg-mw|mwoauthmanageconsumers-approve}}
* {{msg-mw|mwoauthmanageconsumers-reject}}
* {{msg-mw|mwoauthmanageconsumers-rsuppress}}
* {{msg-mw|mwoauthmanageconsumers-disable}}
* {{msg-mw|mwoauthmanageconsumers-dsuppress}}
* {{msg-mw|mwoauthmanageconsumers-reenable}}
{{Identical|Change status}}',
	'mwoauthmanageconsumers-approve' => 'Used as label for the radio box.
{{Related|Mwoauthmanageconsumers}}
{{Identical|Approved}}',
	'mwoauthmanageconsumers-reject' => 'Used as label for the radio box.
{{Related|Mwoauthmanageconsumers}}
{{Identical|Rejected}}',
	'mwoauthmanageconsumers-rsuppress' => 'Used as label for the radio box.
{{Related|Mwoauthmanageconsumers}}',
	'mwoauthmanageconsumers-disable' => 'Used as label for the radio box.
{{Related|Mwoauthmanageconsumers}}
{{Identical|Disabled}}',
	'mwoauthmanageconsumers-dsuppress' => 'Used as label for the radio box.
{{Related|Mwoauthmanageconsumers}}',
	'mwoauthmanageconsumers-reenable' => 'Used as label for the radio box.
{{Related|Mwoauthmanageconsumers}}
{{Identical|Approved}}',
	'mwoauthmanageconsumers-reason' => 'Used as label for the "Reason" input box.
{{Identical|Reason}}',
	'mwoauthmanageconsumers-confirm-submit' => 'Used as label for the Submit button.',
	'mwoauthmanageconsumers-viewing' => 'Parameters:
* $1 - username',
	'mwoauthmanageconsumers-success-approved' => 'Used as success message.
{{Related|Mwoauthmanageconsumers-success}}',
	'mwoauthmanageconsumers-success-rejected' => 'Used as success message.
{{Related|Mwoauthmanageconsumers-success}}',
	'mwoauthmanageconsumers-success-disabled' => 'Used as success message.
{{Related|Mwoauthmanageconsumers-success}}',
	'mwoauthmanageconsumers-success-reanable' => 'Used as success message.
{{Related|Mwoauthmanageconsumers-success}}',
	'mwoauthmanageconsumers-search-name' => 'Link to search for consumers with the same name.

See also:
* {{msg-mw|Mwoauthmanageconsumers-search-publisher}}',
	'mwoauthmanageconsumers-search-publisher' => 'Link to search for consumers by the same user.

See also:
* {{msg-mw|Mwoauthmanageconsumers-search-name}}',
	'mwoauthlistconsumers' => '{{doc-special|MWOAuthListConsumers}}',
	'mwoauthlistconsumers-legend' => 'Legend used for filter form fieldset.

Followed by the following labels:
* {{msg-mw|mwoauth-consumer-name}}
* {{msg-mw|mwoauth-consumer-user}}
* {{msg-mw|mwoauth-consumer-stage}}',
	'mwoauthlistconsumers-view' => 'Link to view consumer details.
{{Identical|Detail}}',
	'mwoauthlistconsumers-none' => 'Shown when a list of consumers is empty',
	'mwoauthlistconsumers-name' => 'Used as a field name in consumer lists.
{{Identical|Application name}}',
	'mwoauthlistconsumers-version' => 'Used as a field name in consumer lists.
{{Identical|Consumer version}}',
	'mwoauthlistconsumers-user' => 'Used as a field name in consumer lists.
{{Identical|Publisher}}',
	'mwoauthlistconsumers-description' => 'Used as label for the "Description" field in consumer lists.
{{Identical|Description}}',
	'mwoauthlistconsumers-wiki' => 'Used as a field name in consumer lists.
{{Identical|Applicable wiki}}',
	'mwoauthlistconsumers-callbackurl' => 'Used as a field name in consumer lists',
	'mwoauthlistconsumers-grants' => 'Used as a field name in consumer lists.
{{Identical|Applicable grant}}',
	'mwoauthlistconsumers-basicgrantsonly' => 'Message used when only hidden grants are used by a consumer (or none at all)',
	'mwoauthlistconsumers-status' => 'Used as a field name in consumer lists.
{{Identical|Status}}',
	'mwoauth-consumer-stage-any' => 'Used as special selector field for "all consumer states".
{{Identical|Any}}',
	'mwoauthlistconsumers-status-proposed' => '{{Related|Mwoauthlistconsumers-status}}
{{Identical|Proposed}}',
	'mwoauthlistconsumers-status-approved' => '{{Related|Mwoauthlistconsumers-status}}
{{Identical|Approved}}',
	'mwoauthlistconsumers-status-disabled' => '{{Related|Mwoauthlistconsumers-status}}
{{Identical|Disabled}}',
	'mwoauthlistconsumers-status-rejected' => '{{Related|Mwoauthlistconsumers-status}}
{{Identical|Rejected}}',
	'mwoauthlistconsumers-status-expired' => '{{Related|Mwoauthlistconsumers-status}}',
	'mwoauthmanagemygrants' => '{{doc-special|MWOAuthManageMyGrants}}',
	'mwoauthmanagemygrants-text' => 'Explanatory text for Special:OAuthManageMyGrants page',
	'mwoauthmanagemygrants-notloggedin' => 'Used in [[Special:MWOAuthManageMyGrants]] if the user is not logged in.',
	'mwoauthmanagemygrants-navigation' => 'Used as subtitle.

Followed by a link with the link text {{msg-mw|Mwoauthmanagemygrants-showlist}}. It can be without link.
{{Identical|Navigation}}',
	'mwoauthmanagemygrants-showlist' => 'Used as link text or as plain text',
	'mwoauthmanagemygrants-none' => 'Message when a user has not authorized any OAuth consumers',
	'mwoauthmanagemygrants-name' => 'Used as table row header.',
	'mwoauthmanagemygrants-user' => 'Used as table row header for "Central username".
{{Identical|Publisher}}',
	'mwoauthmanagemygrants-description' => 'Used as table row header.
{{Identical|Description}}',
	'mwoauthmanagemygrants-wiki' => 'Used as table row header.
{{Identical|Applicable wiki}}',
	'mwoauthmanagemygrants-wikiallowed' => 'Used as field label',
	'mwoauthmanagemygrants-grants' => 'Used as field label.
{{Identical|Applicable grant}}',
	'mwoauthmanagemygrants-grantsallowed' => 'Used as field label',
	'mwoauthmanagemygrants-applicablegrantsallowed' => 'Used as field label',
	'mwoauthmanagemygrants-consumerkey' => 'Used as table row header.
{{Identical|Consumer key}}',
	'mwoauthmanagemygrants-review' => 'Used as link text.',
	'mwoauthmanagemygrants-revoke' => 'Used as link text.',
	'mwoauthmanagemygrants-grantaccept' => 'Used as checkbox column label',
	'mwoauthmanagemygrants-update-text' => 'Explanatory text for Special:OAuthManageMyGrants form',
	'mwoauthmanagemygrants-revoke-text' => 'Explanatory text for Special:OAuthManageMyGrants form',
	'mwoauthmanagemygrants-confirm-legend' => 'Used as fieldset label',
	'mwoauthmanagemygrants-update' => 'Used as label for the radio box.

See also:
* {{msg-mw|Mwoauthmanagemygrants-action}}',
	'mwoauthmanagemygrants-renounce' => 'Used as label for the radio box.

See also:
* {{msg-mw|Mwoauthmanagemygrants-action}}',
	'mwoauthmanagemygrants-action' => 'Used as label for the radio box group.

Followed by the following radio boxes:
* {{msg-mw|Mwoauthmanagemygrants-update}}
* {{msg-mw|Mwoauthmanagemygrants-renounce}}
{{Identical|Change status}}',
	'mwoauthmanagemygrants-confirm-submit' => 'Used as label for the Submit button',
	'mwoauthmanagemygrants-success-update' => 'Message shown when grants for an OAuth consumer are updated by a user',
	'mwoauthmanagemygrants-success-renounce' => 'Message shown when grants for an OAuth consumer are totally revoked',
	'logentry-mwoauthconsumer-propose' => '{{logentry}}',
	'logentry-mwoauthconsumer-update' => '{{logentry}}
* $4 - consumer key',
	'logentry-mwoauthconsumer-approve' => '{{logentry}}
* $4 - consumer key',
	'logentry-mwoauthconsumer-reject' => '{{logentry}}
* $4 - consumer key',
	'logentry-mwoauthconsumer-disable' => '{{logentry}}
* $4 - consumer key',
	'logentry-mwoauthconsumer-reenable' => '{{logentry}}
* $4 - consumer key',
	'mwoauthconsumer-consumer-logpage' => '{{doc-logpage}}',
	'mwoauthconsumer-consumer-logpagetext' => 'Description of the OAuth consumer log.',
	'mwoauth-bad-request' => 'General error when there was a problem processing the request',
	'mwoauthdatastore-access-token-not-found' => 'Error message when an invalid access token was submitted',
	'mwoauthdatastore-request-token-not-found' => 'Error message when an invalid request token was submitted',
	'mwoauthdatastore-bad-token' => 'Error message when an invalid token was submitted',
	'mwoauthdatastore-bad-verifier' => 'Error message when an invalid verification code was submitted',
	'mwoauthdatastore-invalid-token-type' => 'Error message when an invalid page was requested',
	'mwoauthgrants-general-error' => 'Generic error, when something unexpected happened while processing the OAuth request',
	'mwoauthserver-bad-consumer' => 'Error message when an invalid consumer identifier was submitted',
	'mwoauthserver-insufficient-rights' => 'Error message that the user does not have the required rights to perform this request',
	'mwoauthserver-invalid-request-token' => 'Error message when an invalid request token was submitted',
	'mwoauthserver-invalid-user-hookabort' => 'Used as error message.',
	'mwoauth-invalid-authorization-title' => 'Title of the error page when the Authorization header is invalid',
	'mwoauth-invalid-authorization' => 'Text of the error page when the Authorization header is invalid. Parameters are:
* $1 - Specific error message from the OAuth layer, probably not localized',
	'mwoauth-invalid-authorization-wrong-wiki' => 'Text of the error page when the Authorization header is for the wrong wiki. Parameters are:
* $1 - wiki id',
	'mwoauth-invalid-authorization-invalid-user' => "Text of the error page when the Authorization header is for a user that doesn't exist",
	'mwoauth-invalid-authorization-wrong-user' => 'Text of the error page when the Authorization header is for the wrong user',
	'mwoauth-invalid-authorization-not-approved' => "Text of the error page when the Authorization header is for a consumer that isn't approved",
	'mwoauth-invalid-authorization-blocked-user' => 'Text of the error page when Authorization header is for a user who is blocked',
	'mwoauth-form-description-allwikis' => 'Description of a form requesting the user authorize an OAuth consumer to use MediaWiki on their behalf.

Parameters:
* $1 - the username
* $2 - application name
* $3 - application publisher
* $4 - formatted list of grants
See also:
* {{msg-mw|Mwoauth-form-description-onewiki}}
* {{msg-mw|Mwoauth-form-description-allwikis-nogrants}}
* {{msg-mw|Mwoauth-form-description-onewiki-nogrants}}',
	'mwoauth-form-description-onewiki' => 'Description of a form requesting the user authorize an OAuth consumer to use MediaWiki on their behalf, without any non-hidden grants.

Parameters:
* $1 - the username
* $2 - application name
* $3 - application publisher
* $4 - wiki project name
See also:
* {{msg-mw|Mwoauth-form-description-allwikis}}
* {{msg-mw|Mwoauth-form-description-onewiki}}
* {{msg-mw|Mwoauth-form-description-allwikis-nogrants}}',
	'mwoauth-form-description-allwikis-nogrants' => 'Description of a form requesting the user authorize an OAuth consumer to use MediaWiki on their behalf, without any non-hidden grants.

Parameters:
* $1 - the username
* $2 - application name
* $3 - application publisher
See also:
* {{msg-mw|Mwoauth-form-description-allwikis}}
* {{msg-mw|Mwoauth-form-description-onewiki}}
* {{msg-mw|Mwoauth-form-description-onewiki-nogrants}}',
	'mwoauth-form-description-onewiki-nogrants' => 'Description of a form requesting the user authorize an OAuth consumer to use MediaWiki on their behalf, without any non-hidden grants.

Parameters:
* $1 - the username
* $2 - application name
* $3 - application publisher
* $4 - wiki project name
See also:
* {{msg-mw|Mwoauth-form-description-allwikis}}
* {{msg-mw|Mwoauth-form-description-onewiki}}
* {{msg-mw|Mwoauth-form-description-onewiki-nogrants}}',
	'mwoauth-form-legal' => 'Message used for wiki-specific legal notes. Keep this blank.',
	'mwoauth-form-privacypolicy-link' => '{{doc-important|Do not translate <code><nowiki>{{ns:Project}}:Privacy policy</nowiki></code> part.}}',
	'mwoauth-form-button-approve' => 'Button label, indicating the user wants to allow access.

See also:
* {{msg-mw|Mwoauth-form-button-cancel}}
{{Identical|Approve}}',
	'mwoauth-form-button-cancel' => 'Button label, indicating the user wants to cancel granting access.

See also:
* {{msg-mw|Mwoauth-form-button-approve}}
{{Identical|Cancel}}',
	'mwoauth-authorize-form-invalid-user' => 'Text of the error page when the user cannot use OAuth.',
	'mwoauth-error' => 'Heading on the page, whenever an OAuth error is presented to a user.',
	'mwoauth-grants-heading' => 'Used as label for the grants list.

See also:
* {{msg-mw|Mwoauth-grant-blockusers}}
* {{msg-mw|Mwoauth-grant-createaccount}}
* {{msg-mw|Mwoauth-grant-createeditmovepage}}
* {{msg-mw|Mwoauth-grant-delete}}
* {{msg-mw|Mwoauth-grant-editinterface}}
* {{msg-mw|Mwoauth-grant-editmycssjs}}
* {{msg-mw|Mwoauth-grant-editmywatchlist}}
* {{msg-mw|Mwoauth-grant-editpage}}
* {{msg-mw|Mwoauth-grant-editprotected}}
* {{msg-mw|Mwoauth-grant-highvolume}}
* {{msg-mw|Mwoauth-grant-oversight}}
* {{msg-mw|Mwoauth-grant-patrol}}
* {{msg-mw|Mwoauth-grant-protect}}
* {{msg-mw|Mwoauth-grant-rollback}}
* {{msg-mw|Mwoauth-grant-sendemail}}
* {{msg-mw|Mwoauth-grant-uploadeditmovefile}}
* {{msg-mw|Mwoauth-grant-uploadfile}}
* {{msg-mw|Mwoauth-grant-useoauth}}
* {{msg-mw|Mwoauth-grant-viewdeleted}}
* {{msg-mw|Mwoauth-grant-viewmywatchlist}}',
	'mwoauth-grants-nogrants' => 'Warning message that the OAuth consumer has not requested any permissions',
	'mwoauth-acceptance-cancelled' => 'Message shown when an OAuth authorization request is declined',
	'mwoauth-grant-group-page-interaction' => '{{Related|Mwoauth-grant-group}}',
	'mwoauth-grant-group-file-interaction' => '{{Related|Mwoauth-grant-group}}',
	'mwoauth-grant-group-watchlist-interaction' => '{{Related|Mwoauth-grant-group}}',
	'mwoauth-grant-group-email' => '{{Related|Mwoauth-grant-group}}
{{Identical|E-mail}}',
	'mwoauth-grant-group-high-volume' => '{{Related|Mwoauth-grant-group}}',
	'mwoauth-grant-group-customization' => '{{Related|Mwoauth-grant-group}}',
	'mwoauth-grant-group-administration' => '{{Related|Mwoauth-grant-group}}',
	'mwoauth-grant-group-other' => '{{Related|Mwoauth-grant-group}}',
	'mwoauth-grant-blockusers' => 'Name for OAuth grant "blockusers".
{{Related|Mwoauth-grant}}',
	'mwoauth-grant-createaccount' => 'Name for OAuth grant "createaccount".
{{Related|Mwoauth-grant}}',
	'mwoauth-grant-createeditmovepage' => 'Name for OAuth grant "createeditmovepage".
{{Related|Mwoauth-grant}}',
	'mwoauth-grant-delete' => 'Name for OAuth grant "delete".
{{Related|Mwoauth-grant}}',
	'mwoauth-grant-editinterface' => 'Name for OAuth grant "editinterface".

"JS" stands for "JavaScript".
{{Related|Mwoauth-grant}}',
	'mwoauth-grant-editmycssjs' => 'Name for OAuth grant "editmycssjs".

"JS" stands for "JavaScript".
{{Related|Mwoauth-grant}}',
	'mwoauth-grant-editmywatchlist' => 'Name for OAuth grant "editmywatchlist".
{{Related|Mwoauth-grant}}
{{Identical|Edit your watchlist}}',
	'mwoauth-grant-editpage' => 'Name for OAuth grant "editpage".
{{Related|Mwoauth-grant}}',
	'mwoauth-grant-editprotected' => 'Name for OAuth grant "editprotected".
{{Related|Mwoauth-grant}}',
	'mwoauth-grant-highvolume' => 'Name for OAuth grant "highvolume".
{{Related|Mwoauth-grant}}',
	'mwoauth-grant-oversight' => 'Name for OAuth grant "oversight".
{{Related|Mwoauth-grant}}',
	'mwoauth-grant-patrol' => 'Name for OAuth grant "patrol".
{{Related|Mwoauth-grant}}',
	'mwoauth-grant-protect' => 'Name for OAuth grant "protect".
{{Related|Mwoauth-grant}}',
	'mwoauth-grant-rollback' => 'Name for OAuth grant "rollback".
{{Related|Mwoauth-grant}}',
	'mwoauth-grant-sendemail' => 'Name for OAuth grant "sendemail".
{{Related|Mwoauth-grant}}',
	'mwoauth-grant-uploadeditmovefile' => 'Name for OAuth grant "uploadeditmovefile".
{{Related|Mwoauth-grant}}',
	'mwoauth-grant-uploadfile' => 'Name for OAuth grant "uploadfile".
{{Related|Mwoauth-grant}}
{{Identical|Upload new file}}',
	'mwoauth-grant-useoauth' => 'Name for OAuth grant "useoauth".
{{Related|Mwoauth-grant}}',
	'mwoauth-grant-viewdeleted' => 'Name for OAuth grant "viewdeleted".
{{Related|Mwoauth-grant}}',
	'mwoauth-grant-viewmywatchlist' => 'Name for OAuth grant "viewmywatchlist".
{{Related|Mwoauth-grant}}
{{Identical|View your watchlist}}',
	'mwoauth-oauth-exception' => 'Used as failure message. Parameters:
* $1 - Exception message text',
	'mwoauth-callback-not-oob' => 'Warning that the OAuth developer failed to include the required "oauth_callback" parameter, which must be set to the case-sensitive string "oob"',
	'right-mwoauthproposeconsumer' => '{{doc-right|mwoauthproposeconsumer}}',
	'right-mwoauthupdateownconsumer' => '{{doc-right|mwoauthupdateownconsumer}}',
	'right-mwoauthmanageconsumer' => '{{doc-right|mwoauthmanageconsumer}}
{{Identical|Manage OAuth consumer}}',
	'right-mwoauthsuppress' => '{{doc-right|mwoauthsuppress}}',
	'right-mwoauthviewsuppressed' => '{{doc-right|mwoauthviewsuppressed}}',
	'right-mwoauthviewprivate' => '{{doc-right|mwoauthviewprivate}}',
	'right-mwoauthmanagemygrants' => '{{doc-right|mwoauthmanagemygrants}}',
	'action-mwoauthmanageconsumer' => '{{Doc-action|mwoauthmanageconsumer}}
{{Identical|Manage OAuth consumer}}',
	'action-mwoauthmanagemygrants' => '{{Doc-action|mwoauthmanagemygrants}}',
	'action-mwoauthproposeconsumer' => '{{Doc-action|mwoauthproposeconsumer}}',
	'action-mwoauthupdateownconsumer' => '{{Doc-action|mwoauthupdateownconsumer}}',
	'action-mwoauthviewsuppressed' => '{{Doc-action|mwoauthviewsuppressed}}',
);

/** Arabic (العربية)
 * @author مشعل الحربي
 */
$messages['ar'] = array(
	'mwoauth-prefs-managegrantslink' => 'إدارة التطبيقات التي يمكنها استخدام حسابك', # Fuzzy
);

/** Asturian (asturianu)
 * @author Xuacu
 */
$messages['ast'] = array(
	'mwoauth-desc' => 'Autenticación API OAuth 1.0a',
	'mwoauth-missing-field' => 'Falta el valor del campu "$1"',
	'mwoauth-invalid-field' => 'Diose un valor inválidu pal campu "$1"',
	'mwoauth-field-hidden' => '(esta información ta tapecida)',
	'mwoauth-field-private' => '(esta información ye privada)',
	'mwoauth-grant-generic' => 'Conxuntu de drechos "$1"',
	'mwoauth-prefs-managegrants' => 'Accesu de consumidor OAuth:',
	'mwoauth-prefs-managegrantslink' => "Xestionar permisos nel nome d'esta cuenta",
	'mwoauth-consumer-key' => 'Clave del consumidor:',
	'mwoauth-consumer-name' => "Nome d'aplicación:",
	'mwoauth-consumer-version' => 'Versión de consumidor:',
	'mwoauth-consumer-user' => 'Editorial:',
	'mwoauth-consumer-stage' => 'Estáu actual:',
	'mwoauth-consumer-email' => 'Direición de corréu-e de contautu:',
	'mwoauth-consumer-description' => "Descripción de l'aplicación:",
	'mwoauth-consumer-callbackurl' => 'URL de "callback" OAuth:',
	'mwoauth-consumer-grantsneeded' => 'Concesiones aplicables:',
	'mwoauth-consumer-required-grant' => 'Aplicable al consumidor',
	'mwoauth-consumer-wiki' => 'Wiki aplicable:',
	'mwoauth-consumer-restrictions' => "Torgues d'usu:",
	'mwoauth-consumer-restrictions-json' => "Torgues d'usu (JSON):",
	'mwoauth-consumer-rsakey' => 'Clave pública RSA:',
	'mwoauth-consumer-secretkey' => 'Pase secretu del consumidor:',
	'mwoauth-consumer-accesstoken' => "Pase d'accesu:",
	'mwoauth-consumer-reason' => 'Motivu:',
	'mwoauth-consumer-email-unconfirmed' => 'La direición de corréu de la to cuenta inda nun se confirmó.',
	'mwoauth-consumer-email-mismatched' => 'La direición de corréu proporcionada tien de casar cola de la to cuenta.',
	'mwoauth-consumer-alreadyexists' => 'Yá esiste un consumidor con esta combinación de nome/versión/editor',
	'mwoauth-consumer-alreadyexistsversion' => 'Yá esiste un consumidor con esta combinación de nome/editor con una versión igual o mayor ("$1")',
	'mwoauth-consumer-not-accepted' => "Nun se pue anovar la información d'una solicitú de consumidor pendiente",
	'mwoauth-consumer-not-proposed' => 'El consumidor nun ta propuestu actualmente',
	'mwoauth-consumer-not-disabled' => 'El consumidor nun ta desactiváu actualmente',
	'mwoauth-consumer-not-approved' => 'El consumidor nun ta aprobáu (seique, desactivóse)',
	'mwoauth-invalid-consumer-key' => 'Nun esiste dengún consumidor cola clave dada.',
	'mwoauth-invalid-access-token' => "Nun esiste dengún pase d'accesu cola clave dada.",
	'mwoauth-consumer-conflict' => "Dalguién camudó los atributos d'esti consumidor mentanto lu vía. Por favor, vuelva a intentalo. Pue comprobar el rexistru de cambios.",
	'mwoauth-consumer-stage-proposed' => 'propuestu',
	'mwoauth-consumer-stage-rejected' => 'refugáu',
	'mwoauth-consumer-stage-expired' => 'caducáu',
	'mwoauth-consumer-stage-approved' => 'aprobáu',
	'mwoauth-consumer-stage-disabled' => 'desactiváu',
	'mwoauth-consumer-stage-suppressed' => 'suprimíu',
	'mwoauthconsumerregistration' => "Rexistru de consumidor d'OAuth",
	'mwoauthconsumerregistration-notloggedin' => "Tien d'aniciar sesión pa entrar nesta páxina.",
	'mwoauthconsumerregistration-navigation' => 'Navegación:',
	'mwoauthconsumerregistration-propose' => 'Proponer un consumidor nuevu',
	'mwoauthconsumerregistration-list' => 'La mio llista de consumidores',
	'mwoauthconsumerregistration-main' => 'Principal',
);

/** Breton (brezhoneg)
 * @author Y-M D
 */
$messages['br'] = array(
	'mwoauthlistconsumers-view' => 'munudoù',
	'mwoauthlistconsumers-name' => 'Anv ar poellad',
	'mwoauthlistconsumers-user' => 'Embanner',
	'mwoauthlistconsumers-description' => 'Deskrivadur',
	'mwoauthlistconsumers-status' => 'Statud',
	'mwoauthlistconsumers-status-proposed' => 'kinniget',
	'mwoauthlistconsumers-status-approved' => 'aprouet',
	'mwoauthlistconsumers-status-disabled' => 'diweredekaet',
	'mwoauthlistconsumers-status-rejected' => 'distaolet',
);

/** Catalan (català)
 * @author Pginer
 */
$messages['ca'] = array(
	'mwoauthlistconsumers-view' => 'detalls',
);

/** Czech (česky)
 * @author Mormegil
 */
$messages['cs'] = array(
	'mwoauth-desc' => 'Autorizace pomocí rozhraní OAuth 1.0a',
	'mwoauth-missing-field' => 'Chybějící hodnota pole „$1“',
	'mwoauth-invalid-field' => 'Uvedena neplatná hodnota pole „$1“',
	'mwoauth-field-hidden' => '(tato informace je skryta)',
	'mwoauth-field-private' => '(tato informace je soukromá)',
	'mwoauth-grant-generic' => 'Balíček oprávnění „$1“',
	'mwoauth-prefs-managegrants' => 'Přístup konzumentů OAuth:',
	'mwoauth-prefs-managegrantslink' => 'Spravovat oprávnění k jednání jménem tohoto účtu',
	'mwoauth-consumer-key' => 'Klíč konzumenta:',
	'mwoauth-consumer-name' => 'Název aplikace:',
	'mwoauth-consumer-version' => 'Verze konzumenta:',
	'mwoauth-consumer-user' => 'Vydavatel:',
	'mwoauth-consumer-stage' => 'Aktuální stav:',
	'mwoauth-consumer-email' => 'Kontaktní e-mailová adresa:',
	'mwoauth-consumer-description' => 'Popis aplikace:',
	'mwoauth-consumer-callbackurl' => 'URL pro OAuth „callback“:',
	'mwoauth-consumer-grantsneeded' => 'Použitelná oprávnění:',
	'mwoauth-consumer-required-grant' => 'Použitelné konzumentem',
	'mwoauth-consumer-wiki' => 'Použitelná wiki:',
	'mwoauth-consumer-restrictions' => 'Omezení užití:',
	'mwoauth-consumer-restrictions-json' => 'Omezení užití (JSON):',
	'mwoauth-consumer-rsakey' => 'Veřejný RSA klíč:',
	'mwoauth-consumer-secretkey' => 'Tajný token konzumenta:',
	'mwoauth-consumer-accesstoken' => 'Přístupový token:',
	'mwoauth-consumer-reason' => 'Důvod:',
	'mwoauth-consumer-email-unconfirmed' => 'E-mailová adresa vašeho uživatelského účtu dosud nebyla potvrzena.',
	'mwoauth-consumer-email-mismatched' => 'Uvedená e-mailová adresa musí odpovídat té ve vašem uživatelském účtu.',
	'mwoauth-consumer-alreadyexists' => 'Konzument s touto kombinací název/verze/vydavatel již existuje',
	'mwoauth-consumer-alreadyexistsversion' => 'Konzument s touto kombinací název/vydavatel již existuje ve stejné či vyšší verzi („$1“)',
	'mwoauth-consumer-not-accepted' => 'Nelze změnit údaje u probíhajícího požadavku na konzumenta',
	'mwoauth-consumer-not-proposed' => 'Tento konzument není momentálně navržen',
	'mwoauth-consumer-not-disabled' => 'Tento konzument není momentálně zakázán',
	'mwoauth-consumer-not-approved' => 'Tento konzument není schválen (mohl být zakázán)',
	'mwoauth-invalid-consumer-key' => 'Žádný konzument s daným klíčem neexistuje.',
	'mwoauth-invalid-access-token' => 'Žádný přístupový token s daným klíčem neexistuje.',
	'mwoauth-consumer-conflict' => 'Zatímco jste si tohoto konzumenta {{GENDER:|prohlížel|prohlížela|prohlíželi}}, někdo změnil jeho atributy. Možná si budete chtít prohlédnout protokol změn.',
	'mwoauth-consumer-stage-proposed' => 'navržený',
	'mwoauth-consumer-stage-rejected' => 'odmítnutý',
	'mwoauth-consumer-stage-expired' => 'propadlý',
	'mwoauth-consumer-stage-approved' => 'schválený',
	'mwoauth-consumer-stage-disabled' => 'zakázaný',
	'mwoauth-consumer-stage-suppressed' => 'utajený',
	'mwoauthconsumerregistration' => 'Registrace konzumenta OAuth',
	'mwoauthconsumerregistration-notloggedin' => 'Pro přístup k této stránce musíte být přihlášen(a).',
	'mwoauthconsumerregistration-navigation' => 'Navigace:',
	'mwoauthconsumerregistration-propose' => 'Navrhnout nového konzumenta',
	'mwoauthconsumerregistration-list' => 'Seznam mých konzumentů',
	'mwoauthconsumerregistration-main' => 'Hlavní',
	'mwoauthconsumerregistration-propose-text' => 'Pomocí níže zobrazeného formuláře můžete navrhnout nového konzumenta OAuth (vizte http://oauth.net).

Několik doporučení a poznámek:
* Snažte se použít co nejméně oprávnění. Vyhněte se těm, která ve skutečnosti zatím nepotřebujete.
* Verze má tvar „major.minor.release“ (poslední dvě části jsou nepovinné) a zvyšuje se, když jsou potřeba změny oprávnění.
* Pokud je to možné, poskytněte veřejný klíč RSA (ve formátu PEM); v opačném případě se musí používat (méně bezpečný) tajný token.
* Pomocí omezení v JSON můžete omezit tomuto konzumentu přístup jen na IP adresy v daných rozsazích CIDR.
* Pomocí ID wiki můžete omezit tohoto konzumenta na jedinou wiki na tomto serveru (pro všechny wiki uveďte „*“).
* Zadaná e-mailová adresa musí odpovídat té na vašem uživatelském účtu (která musí být ověřena).', # Fuzzy
	'mwoauthconsumerregistration-update-text' => 'Pomocí níže uvedeného formuláře můžete změnit vlastnosti konzumenta OAuth, kterého spravujete.

Všechny uvedené hodnoty přepíšou ty původní. Neponechávejte žádná pole prázdná, pokud nechcete jejich hodnoty smazat.',
	'mwoauthconsumerregistration-maintext' => 'Tato stránka slouží k navrhování a změnám konzumentských aplikací OAuth (vizte http://oauth.net) v registru tohoto serveru.

Můžete zde [[Special:MWOAuthConsumerRegistration/propose|navrhnout nového konzumenta]] nebo [[Special:MWOAuthConsumerRegistration/list|spravovat své existující konzumenty]].', # Fuzzy
	'mwoauthconsumerregistration-propose-legend' => 'Nová konzumentská aplikace OAuth',
	'mwoauthconsumerregistration-update-legend' => 'Změna konzumentské aplikace OAuth',
	'mwoauthconsumerregistration-propose-submit' => 'Navrhnout konzumenta',
	'mwoauthconsumerregistration-update-submit' => 'Upravit konzumenta',
	'mwoauthconsumerregistration-none' => 'Nespravujete žádné konzumenty OAuth.',
	'mwoauthconsumerregistration-name' => 'Konzument',
	'mwoauthconsumerregistration-user' => 'Vydavatel',
	'mwoauthconsumerregistration-description' => 'Popis',
	'mwoauthconsumerregistration-email' => 'Kontaktní e-mail',
	'mwoauthconsumerregistration-consumerkey' => 'Klíč konzumenta',
	'mwoauthconsumerregistration-stage' => 'Stav',
	'mwoauthconsumerregistration-lastchange' => 'Poslední změna',
	'mwoauthconsumerregistration-manage' => 'spravovat',
	'mwoauthconsumerregistration-resetsecretkey' => 'Resetovat tajný klíč na novou hodnotu',
	'mwoauthconsumerregistration-proposed' => "Vaše žádost o konzumenta OAuth byla přijata.

Byl vám přidělen token konzumenta '''$1''' a tajný token '''$2'''. ''Zaznamenejte si je pro budoucí použití.''",
	'mwoauthconsumerregistration-updated' => 'Vaše registrace konzumenta OAuth byla úspěšně upravena.',
	'mwoauthconsumerregistration-secretreset' => "Byl vám přidělen tajný token konzumenta '''$1'''. ''Zaznamenejte si ho pro budoucí použití.''",
	'mwoauthmanageconsumers' => 'Správa konzumentů OAuth',
	'mwoauthmanageconsumers-notloggedin' => 'Pro přístup k této stránce musíte být přihlášen(a).',
	'mwoauthmanageconsumers-type' => 'Fronty:',
	'mwoauthmanageconsumers-showproposed' => 'Navržené žádosti',
	'mwoauthmanageconsumers-showrejected' => 'Odmítnuté žádosti',
	'mwoauthmanageconsumers-showexpired' => 'Propadlé žádosti',
	'mwoauthmanageconsumers-main' => 'Hlavní',
	'mwoauthmanageconsumers-maintext' => 'Tato stránka slouží k řešení požadavků na konzumentské aplikace OAuth (vizte http://oauth.net) a správě existujících konzumentů OAuth.',
	'mwoauthmanageconsumers-queues' => 'Níže si vyberte frontu potvrzení konzumentů:',
	'mwoauthmanageconsumers-q-proposed' => 'Fronta navržených žádostí o konzumenta',
	'mwoauthmanageconsumers-q-rejected' => 'Fronta odmítnutých žádostí o konzumenta',
	'mwoauthmanageconsumers-q-expired' => 'Fronta propadlých žádostí o konzumenta',
	'mwoauthmanageconsumers-lists' => 'Níže si vyberte seznam konzumentů podle stavu:',
	'mwoauthmanageconsumers-l-approved' => 'Seznam schválených konzumentů',
	'mwoauthmanageconsumers-l-disabled' => 'Seznam zakázaných konzumentů',
	'mwoauthmanageconsumers-none-proposed' => 'V tomto seznamu nejsou žádní navržení konzumenti.',
	'mwoauthmanageconsumers-none-rejected' => 'V tomto seznamu nejsou žádní navržení konzumenti.',
	'mwoauthmanageconsumers-none-expired' => 'V tomto seznamu nejsou žádní navržení konzumenti.',
	'mwoauthmanageconsumers-none-approved' => 'Těmto kritériím nevyhovuje žádný konzument.',
	'mwoauthmanageconsumers-none-disabled' => 'Těmto kritériím nevyhovuje žádný konzument.',
	'mwoauthmanageconsumers-name' => 'Konzument',
	'mwoauthmanageconsumers-user' => 'Vydavatel',
	'mwoauthmanageconsumers-description' => 'Popis',
	'mwoauthmanageconsumers-email' => 'Kontaktní e-mail',
	'mwoauthmanageconsumers-consumerkey' => 'Klíč konzumenta',
	'mwoauthmanageconsumers-lastchange' => 'Poslední změna',
	'mwoauthmanageconsumers-review' => 'zkontrolovat/spravovat',
	'mwoauthmanageconsumers-confirm-text' => 'Pomocí tohoto formuláře můžete tohoto konzumenta schválit, odmítnout, zakázat nebo znovu povolit.',
	'mwoauthmanageconsumers-confirm-legend' => 'Správa konzumenta OAuth',
	'mwoauthmanageconsumers-action' => 'Změnit stav:',
	'mwoauthmanageconsumers-approve' => 'Schválený',
	'mwoauthmanageconsumers-reject' => 'Odmítnutý',
	'mwoauthmanageconsumers-rsuppress' => 'Odmítnutý a utajený',
	'mwoauthmanageconsumers-disable' => 'Zakázaný',
	'mwoauthmanageconsumers-dsuppress' => 'Zakázaný a utajený',
	'mwoauthmanageconsumers-reenable' => 'Schválený',
	'mwoauthmanageconsumers-reason' => 'Důvod:',
	'mwoauthmanageconsumers-confirm-submit' => 'Aktualizovat stav konzumenta',
	'mwoauthmanageconsumers-viewing' => 'Tohoto konzumenta si v současné chvíli prohlíží {{GENDER:$1|uživatel|uživatelka}} „$1“.',
	'mwoauthmanageconsumers-success-approved' => 'Žádost byla schválena.',
	'mwoauthmanageconsumers-success-rejected' => 'Žádost byla zamítnuta.',
	'mwoauthmanageconsumers-success-disabled' => 'Konzument byl zakázán.',
	'mwoauthmanageconsumers-success-reanable' => 'Konzument byl znovu povolen.',
	'mwoauthmanagemygrants' => 'Správa přístupových oprávnění OAuth',
	'mwoauthmanagemygrants-notloggedin' => 'Pro přístup k této stránce musíte být přihlášen(a).',
	'mwoauthmanagemygrants-navigation' => 'Navigace:',
	'mwoauthmanagemygrants-showlist' => 'Seznam schválených konzumentů',
	'mwoauthmanagemygrants-none' => 'Žádný konzument nemá jménem vašeho účtu přístup.',
	'mwoauthmanagemygrants-name' => 'Název konzumenta',
	'mwoauthmanagemygrants-user' => 'Vydavatel',
	'mwoauthmanagemygrants-description' => 'Popis',
	'mwoauthmanagemygrants-wiki' => 'Použitelná wiki',
	'mwoauthmanagemygrants-wikiallowed' => 'Povoleno na wiki',
	'mwoauthmanagemygrants-grants' => 'Použitelná oprávnění',
	'mwoauthmanagemygrants-grantsallowed' => 'Přidělená oprávnění',
	'mwoauthmanagemygrants-applicablegrantsallowed' => 'Přidělená použitelná oprávnění:',
	'mwoauthmanagemygrants-consumerkey' => 'Klíč konzumenta',
	'mwoauthmanagemygrants-review' => 'zkontrolovat/spravovat přístup', # Fuzzy
	'mwoauthmanagemygrants-grantaccept' => 'Přiděleno konzumentovi', # Fuzzy
	'mwoauthmanagemygrants-confirm-text' => 'Pomocí níže zobrazeného formuláře můžete odvolat přístup nebo změnit oprávnění konzumenta OAuth k jednání vaším jménem.

Uvědomte si, že pokud jste konzumentovi dovolili přístup jen k podmnožině wiki (projektů), bude k tomuto konzumentovi existovat více přístupových tokenů.', # Fuzzy
	'mwoauthmanagemygrants-confirm-legend' => 'Správa přístupového tokenu konzumenta',
	'mwoauthmanagemygrants-update' => 'Aktualizovat oprávnění přístupového tokenu', # Fuzzy
	'mwoauthmanagemygrants-renounce' => 'Zrušit oprávnění a smazat přístupový token', # Fuzzy
	'mwoauthmanagemygrants-action' => 'Změnit stav:',
	'mwoauthmanagemygrants-confirm-submit' => 'Aktualizovat stav přístupového tokenu',
	'mwoauthmanagemygrants-success-update' => 'Přístupový token tohoto konzumenta byl aktualizován.',
	'mwoauthmanagemygrants-success-renounce' => 'Přístupový token tohoto konzumenta byl smazán.',
	'logentry-mwoauthconsumer-propose' => '$1 {{GENDER:$2|navrhl|navrhla}} konzumenta OAuth (klíč konzumenta $4)',
	'logentry-mwoauthconsumer-update' => '$1 {{GENDER:$2|aktualizoval|aktualizovala}} konzumenta OAuth (klíč konzumenta $4)',
	'logentry-mwoauthconsumer-approve' => '$1 {{GENDER:$2|schválil|schválila}} konzumenta OAuth uživatele $3 (klíč konzumenta $4)',
	'logentry-mwoauthconsumer-reject' => '$1 {{GENDER:$2|zamítnul|zamítla}} konzumenta OAuth uživatele $3 (klíč konzumenta $4)',
	'logentry-mwoauthconsumer-disable' => '$1 {{GENDER:$2|zakázal|zakázala}} konzumenta OAuth uživatele $3 (klíč konzumenta $4)',
	'logentry-mwoauthconsumer-reenable' => '$1 znovu {{GENDER:$2|povolil|povolila}} konzumenta OAuth uživatele $3 (klíč konzumenta $4)',
	'mwoauthconsumer-consumer-logpage' => 'Kniha konzumentů OAuth',
	'mwoauthconsumer-consumer-logpagetext' => 'Protokol schválení, zamítnutí a zákazů registrovaných konzumentů OAuth.',
	'mwoauth-bad-csrf-token' => 'Při odeslání formuláře došlo k chybě relace. Zkuste ho odeslat znovu.',
	'mwoauth-bad-request' => 'Ve vašem OAuth požadavku byla chyba.',
	'mwoauthdatastore-access-token-not-found' => 'K tomuto schválenému autorizačnímu tokenu nebylo nalezeno žádné schválené oprávnění.',
	'mwoauthdatastore-request-token-not-found' => 'Pro tento token nebyl nalezen žádný požadavek.',
	'mwoauthdatastore-bad-token' => 'Žádný token odpovídající vašemu požadavku nebyl nalezen.',
	'mwoauthdatastore-bad-verifier' => 'Poskytnutý ověřovací kód nebyl platný.',
	'mwoauthdatastore-invalid-token-type' => 'Požadovaný typ tokenu není platný.',
	'mwoauthgrants-general-error' => 'Ve vašem OAuth požadavku byla chyba.',
	'mwoauthserver-bad-consumer' => 'K poskytnutému klíči nebyl nalezen žádný schválený konzument.',
	'mwoauthserver-insufficient-rights' => 'K provedení této akce nemáte dostatečná oprávnění.',
	'mwoauthserver-invalid-request-token' => 'Váš požadavek obsahuje neplatný token.',
	'mwoauthserver-invalid-user-hookabort' => 'Tento uživatel nemůže používat OAuth.',
	'mwoauth-invalid-authorization-title' => 'Chyba autorizace OAuth',
	'mwoauth-invalid-authorization' => 'Autorizační hlavičky ve vašem požadavku nejsou platné: $1',
	'mwoauth-invalid-authorization-wrong-wiki' => 'Autorizační hlavičky ve vašem požadavku nejsou pro $1 platné',
	'mwoauth-invalid-authorization-invalid-user' => 'Autorizační hlavičky ve vašem požadavku jsou pro uživatele, který zde neexistuje',
	'mwoauth-invalid-authorization-wrong-user' => 'Autorizační hlavičky ve vašem požadavku jsou pro jiného uživatele',
	'mwoauth-invalid-authorization-not-approved' => 'Autorizační hlavičky ve vašem požadavku jsou pro konzumenta OAuth, který momentálně není schválen',
	'mwoauth-invalid-authorization-blocked-user' => 'Autorizační hlavičky ve vašem požadavku jsou pro uživatele, který je zablokován',
	'mwoauth-form-button-approve' => 'Ano, dovolit', # Fuzzy
	'mwoauth-authorize-form-invalid-user' => 'Tento uživatelský účet nemůže používat OAuth, protože účet na této wiki není propojen s účtem na ústřední wiki OAuth.',
	'mwoauth-error' => 'Chyba OAuth',
	'mwoauth-grants-heading' => 'Vyžadovaná oprávnění:',
	'mwoauth-grants-nogrants' => 'Tato aplikace nevyžaduje žádná oprávnění.',
	'mwoauth-grant-blockusers' => 'Blokovat uživatele', # Fuzzy
	'mwoauth-grant-createeditmovepage' => 'Zakládat, editovat a přesouvat stránky',
	'mwoauth-grant-delete' => 'Mazat stránky, revize a protokolovací záznamy',
	'mwoauth-grant-editinterface' => 'Editovat jmenný prostor MediaWiki a uživatelské CSS/JS',
	'mwoauth-grant-editmycssjs' => 'Editovat vaše vlastní uživatelské CSS/JS',
	'mwoauth-grant-editmywatchlist' => 'Upravovat váš seznam sledovaných stránek',
	'mwoauth-grant-editpage' => 'Editovat existující stránky',
	'mwoauth-grant-editprotected' => 'Editovat zamčené stránky',
	'mwoauth-grant-highvolume' => 'Hromadné editace',
	'mwoauth-grant-oversight' => 'Skrývat uživatele a utajovat revize',
	'mwoauth-grant-patrol' => 'Patrolovat', # Fuzzy
	'mwoauth-grant-protect' => 'Zamykat a odemykat stránky',
	'mwoauth-grant-rollback' => 'Vracet editace zpět', # Fuzzy
	'mwoauth-grant-sendemail' => 'Posílat e-maily', # Fuzzy
	'mwoauth-grant-uploadeditmovefile' => 'Načítat, nahrazovat a přesouvat soubory',
	'mwoauth-grant-uploadfile' => 'Načítat nové soubory',
	'mwoauth-grant-useoauth' => 'Základní oprávnění',
	'mwoauth-grant-viewdeleted' => 'Prohlížet si smazané údaje',
	'mwoauth-grant-viewmywatchlist' => 'Prohlížet si váš seznam sledovaných stránek',
	'mwoauth-callback-not-oob' => 'oauth_callback musí být nastaven, a to na hodnotu „oob“ (malými písmeny)',
	'right-mwoauthproposeconsumer' => 'Navrhování nových konzumentů OAuth',
	'right-mwoauthupdateownconsumer' => 'Upravování konzumentů OAuth, které spravujete',
	'right-mwoauthmanageconsumer' => 'Správa konzumentů OAuth',
	'right-mwoauthsuppress' => 'Utajování konzumentů OAuth',
	'right-mwoauthviewsuppressed' => 'Zobrazování utajených konzumentů OAuth',
	'right-mwoauthviewprivate' => 'Zobrazování soukromých dat OAuth',
	'right-mwoauthmanagemygrants' => 'Správa přístupových oprávnění OAuth',
	'action-mwoauthmanageconsumer' => 'spravovat konzumenty OAuth',
	'action-mwoauthmanagemygrants' => 'spravovat vámi přidělená oprávnění OAuth',
	'action-mwoauthproposeconsumer' => 'navrhovat nové konzumenty OAuth',
	'action-mwoauthupdateownconsumer' => 'upravovat konzumenty OAuth, které spravujete',
	'action-mwoauthviewsuppressed' => 'prohlížet si utajené konzumenty OAuth',
);

/** German (Deutsch)
 * @author Metalhead64
 * @author Se4598
 */
$messages['de'] = array(
	'mwoauth-desc' => 'OAuth-1.0a-API-Authentifikation',
	'mwoauth-verified' => "Die Anwendung ist jetzt berechtigt, in deinem Namen auf MediaWiki zuzugreifen.

Um den Prozess abzuschließen, gib diesen Verifizierungswert an die Anwendung weiter: '''$1'''",
	'mwoauth-missing-field' => 'Fehlender Wert für das Feld „$1“',
	'mwoauth-invalid-field' => 'Für das Feld „$1“ wurde ein ungültiger Wert angegeben',
	'mwoauth-invalid-field-generic' => 'Ungültigen Wert angegeben',
	'mwoauth-field-hidden' => '(diese Information ist versteckt)',
	'mwoauth-field-private' => '(diese Information ist privat)',
	'mwoauth-grant-generic' => 'Rechtegruppe „$1“',
	'mwoauth-prefs-managegrants' => 'Verbundene Anwendungen:',
	'mwoauth-prefs-managegrantslink' => '{{PLURAL:$1|Eine verbundene Anwendung|$1 verbundene Anwendungen}} verwalten',
	'mwoauth-consumer-allwikis' => 'Alle Projekte auf dieser Website',
	'mwoauth-consumer-key' => 'Verbraucherschlüssel:',
	'mwoauth-consumer-name' => 'Anwendungsname:',
	'mwoauth-consumer-version' => 'Verbraucherversion:',
	'mwoauth-consumer-user' => 'Herausgeber:',
	'mwoauth-consumer-stage' => 'Aktueller Status:',
	'mwoauth-consumer-email' => 'Kontakt-E-Mail-Adresse:',
	'mwoauth-consumer-description' => 'Anwendungsbeschreibung:',
	'mwoauth-consumer-callbackurl' => 'OAuth-Callback-URL:',
	'mwoauth-consumer-grantsneeded' => 'Anwendbare Berechtigungen:',
	'mwoauth-consumer-required-grant' => 'An Verbraucher anwendbar',
	'mwoauth-consumer-wiki' => 'Anwendbares Wiki:',
	'mwoauth-consumer-wiki-thiswiki' => 'Aktuelles Wiki ($1)',
	'mwoauth-consumer-wiki-other' => 'Spezielles Wiki',
	'mwoauth-consumer-restrictions' => 'Benutzungsbeschränkungen:',
	'mwoauth-consumer-restrictions-json' => 'Benutzungsbeschränkungen (JSON):',
	'mwoauth-consumer-rsakey' => 'Öffentlicher RSA-Schlüssel:',
	'mwoauth-consumer-secretkey' => 'Geheimer Verbrauchertoken:',
	'mwoauth-consumer-accesstoken' => 'Zugriffstoken:',
	'mwoauth-consumer-reason' => 'Grund:',
	'mwoauth-consumer-email-unconfirmed' => 'Die E-Mail-Adresse deines Benutzerkontos wurde noch nicht bestätigt.',
	'mwoauth-consumer-email-mismatched' => 'Die angegebene E-Mail-Adresse muss mit der deines Benutzerkontos übereinstimmen.',
	'mwoauth-consumer-alreadyexists' => 'Ein Verbraucher mit dieser Namen-/Versions-/Herausgeberkombination ist bereits vorhanden',
	'mwoauth-consumer-alreadyexistsversion' => 'Ein Verbraucher mit dieser Namen-/Herausgeber-Kombination ist bereits mit einer gleichen oder höheren Version vorhanden („$1“)',
	'mwoauth-consumer-not-accepted' => 'Die Informationen für einen ausstehenden Verbraucherantrag konnten nicht aktualisiert werden',
	'mwoauth-consumer-not-proposed' => 'Der Verbraucher ist derzeit nicht geplant',
	'mwoauth-consumer-not-disabled' => 'Der Verbraucher ist derzeit nicht deaktiviert',
	'mwoauth-consumer-not-approved' => 'Der Verbraucher ist nicht bestätigt (vielleicht wurde er deaktiviert)',
	'mwoauth-missing-consumer-key' => 'Es wurde kein Verbraucherschlüssel angegeben.',
	'mwoauth-invalid-consumer-key' => 'Es ist kein Verbraucher mit dem angegebenen Schlüssel vorhanden.',
	'mwoauth-invalid-access-token' => 'Es ist kein Zugriffstoken mit dem angegebenen Schlüssel vorhanden.',
	'mwoauth-invalid-access-wrongwiki' => 'Der Verbraucher kann nur auf dem Wiki „$1“ verwendet werden.',
	'mwoauth-consumer-conflict' => 'Ein anderer hat bereits die Attribute dieses Verbrauchers geändert. Bitte erneut versuchen. Du kannst auch das Änderungs-Logbuch überprüfen.',
	'mwoauth-consumer-stage-proposed' => 'geplant',
	'mwoauth-consumer-stage-rejected' => 'abgelehnt',
	'mwoauth-consumer-stage-expired' => 'abgelaufen',
	'mwoauth-consumer-stage-approved' => 'bestätigt',
	'mwoauth-consumer-stage-disabled' => 'deaktiviert',
	'mwoauth-consumer-stage-suppressed' => 'unterdrückt',
	'mwoauthconsumerregistration' => 'OAuth-Verbraucherregistrierung',
	'mwoauthconsumerregistration-notloggedin' => 'Du musst angemeldet sein, um auf diese Seite zugreifen zu können.',
	'mwoauthconsumerregistration-navigation' => 'Navigation:',
	'mwoauthconsumerregistration-propose' => 'Neuen Verbraucher planen',
	'mwoauthconsumerregistration-list' => 'Meine Verbraucherliste',
	'mwoauthconsumerregistration-main' => 'Start',
	'mwoauthconsumerregistration-propose-text' => 'Entwickler sollten das unten stehende Formular benutzen, um einen neuen OAuth-Verbraucher zu planen (siehe die [//www.mediawiki.org/wiki/Extension:OAuth Erweiterungsdokumentation] für Einzelheiten). Nach dem Abschicken dieses Formulars erhältst du einen Token, der von deiner Anwendung zur Identifizierung für MediaWiki verwendet wird. Ein OAuth-Administrator muss deine Anwendung bestätigen, bevor sie von anderen Benutzern autorisiert werden kann.

Hier einige Empfehlungen und Bemerkungen:
* Versuche, so wenig Berechtigungen wie möglich zu verwenden. Vermeide Berechtigungen, die in Wirklichkeit nicht benötigt werden.
* Versionen haben die Form „Hauptversion.Nebenversion.Release“ (die letzten zwei sind optional) und steigen mit der Notwendigkeit von Berechtigungsänderungen an.
* Bitte gib einen öffentlichen RSA-Schlüssel an (im PEM-Format), falls möglich. Anderenfalls muss ein wenig sicherer Geheimtoken benutzt werden.
* Verwende das JSON-Beschränkungsfeld, um den Zugriff dieses Verbrauchers auf IP-Adressen in diesen CIDR-Bereichen zu beschränken.
* Du kannst eine Wikikennung verwenden, um den Verbraucher auf ein einzelnes Wiki auf dieser Website zu beschränken (verwende „*“ für alle Wikis).
* Die angegebene E-Mail-Adresse muss mit der deines Benutzerkontos übereinstimmen und bestätigt sein.',
	'mwoauthconsumerregistration-update-text' => 'Verwende das unten stehende Formular, um Aspekte eines von dir kontrollierten OAuth-Verbrauchers zu aktualisieren.

Alle Werte hier überschreiben alle vorherigen. Hinterlasse keine leeren Felder, außer du beabsichtigst, diese Werte zu löschen.',
	'mwoauthconsumerregistration-maintext' => 'Diese Seite dient der Planung und Aktualisierung von OAuth-Verbraucheranwendungen in der Websiteregistrierung durch Entwickler.

Du kannst von hier
* [[Special:MWOAuthConsumerRegistration/propose|einen Token für einen neuen Verbraucher anfordern]] oder
* [[Special:MWOAuthConsumerRegistration/list|deine vorhandenen Verbraucher verwalten]].

Für mehr Informationen über OAuth, siehe die [//www.mediawiki.org/wiki/Extension:OAuth Erweiterungsdokumentation].',
	'mwoauthconsumerregistration-propose-legend' => 'Neue OAuth-Verbraucheranwendung',
	'mwoauthconsumerregistration-update-legend' => 'OAuth-Verbraucheranwendung aktualisieren',
	'mwoauthconsumerregistration-propose-submit' => 'Verbraucher planen',
	'mwoauthconsumerregistration-update-submit' => 'Verbraucher aktualisieren',
	'mwoauthconsumerregistration-none' => 'Du kontrollierst keine OAuth-Verbraucher.',
	'mwoauthconsumerregistration-name' => 'Verbraucher',
	'mwoauthconsumerregistration-user' => 'Herausgeber',
	'mwoauthconsumerregistration-description' => 'Beschreibung',
	'mwoauthconsumerregistration-email' => 'Kontakt-E-Mail',
	'mwoauthconsumerregistration-consumerkey' => 'Verbraucherschlüssel',
	'mwoauthconsumerregistration-stage' => 'Status',
	'mwoauthconsumerregistration-lastchange' => 'Letzte Änderung',
	'mwoauthconsumerregistration-manage' => 'verwalten',
	'mwoauthconsumerregistration-resetsecretkey' => 'Den geheimen Schlüssel auf einen neuen Wert zurücksetzen',
	'mwoauthconsumerregistration-proposed' => "Wir haben deinen OAuth-Verbraucherantrag erhalten.

Dir wurde der Verbrauchertoken '''$1''' und der Geheimtoken '''$2''' zugewiesen. ''Bitte diese für die Zukunft aufbewahren.''",
	'mwoauthconsumerregistration-updated' => 'Deine OAuth-Verbraucherregistrierung wurde erfolgreich aktualisiert.',
	'mwoauthconsumerregistration-secretreset' => "Dir wurde der geheime Verbrauchertoken '''$1''' zugeordnet. ''Bitte diesen für die Zukunft aufbewahren.''",
	'mwoauthmanageconsumers' => 'OAuth-Verbraucher verwalten',
	'mwoauthmanageconsumers-notloggedin' => 'Du musst angemeldet sein, um auf diese Seite zugreifen zu können.',
	'mwoauthmanageconsumers-type' => 'Warteschlangen:',
	'mwoauthmanageconsumers-showproposed' => 'Geplante Anträge',
	'mwoauthmanageconsumers-showrejected' => 'Abgelehnte Anträge',
	'mwoauthmanageconsumers-showexpired' => 'Abgelaufene Anträge',
	'mwoauthmanageconsumers-main' => 'Start',
	'mwoauthmanageconsumers-maintext' => 'Diese Seite ist gedacht zur Abwicklung von OAuth-Verbraucheranwendungsanträgen (siehe http://oauth.net) und zum Verwalten von bestehenden OAuth-Verbrauchern.',
	'mwoauthmanageconsumers-queues' => 'Wähle von unten eine Verbraucherbestätigungswarteschlange aus:',
	'mwoauthmanageconsumers-q-proposed' => 'Warteschlange geplanter Verbraucheranträge',
	'mwoauthmanageconsumers-q-rejected' => 'Warteschlange abgelehnter Verbraucheranträge',
	'mwoauthmanageconsumers-q-expired' => 'Warteschlange abgelaufener Verbraucheranträge',
	'mwoauthmanageconsumers-lists' => 'Wähle von unten eine Verbraucherstatusliste aus:',
	'mwoauthmanageconsumers-l-approved' => 'Liste derzeit bestätigter Verbraucher',
	'mwoauthmanageconsumers-l-disabled' => 'Liste derzeit deaktivierter Verbraucher',
	'mwoauthmanageconsumers-none-proposed' => 'In dieser Liste gibt es keine geplanten Verbraucher.',
	'mwoauthmanageconsumers-none-rejected' => 'In dieser Liste gibt es keine geplanten Verbraucher.',
	'mwoauthmanageconsumers-none-expired' => 'In dieser Liste gibt es keine geplanten Verbraucher.',
	'mwoauthmanageconsumers-none-approved' => 'Keine Verbraucher entsprechen diesen Kriterien.',
	'mwoauthmanageconsumers-none-disabled' => 'Keine Verbraucher entsprechen diesen Kriterien.',
	'mwoauthmanageconsumers-name' => 'Verbraucher',
	'mwoauthmanageconsumers-user' => 'Herausgeber',
	'mwoauthmanageconsumers-description' => 'Beschreibung',
	'mwoauthmanageconsumers-email' => 'Kontakt-E-Mail',
	'mwoauthmanageconsumers-consumerkey' => 'Verbraucherschlüssel',
	'mwoauthmanageconsumers-lastchange' => 'Letzte Änderung',
	'mwoauthmanageconsumers-review' => 'überprüfen/verwalten',
	'mwoauthmanageconsumers-confirm-text' => 'Benutze dieses Formular, um diesen Verbraucher zu bestätigen, abzulehnen, zu deaktivieren oder zu reaktivieren.',
	'mwoauthmanageconsumers-confirm-legend' => 'OAuth-Verbraucher verwalten',
	'mwoauthmanageconsumers-action' => 'Status ändern:',
	'mwoauthmanageconsumers-approve' => 'Bestätigt',
	'mwoauthmanageconsumers-reject' => 'Abgelehnt',
	'mwoauthmanageconsumers-rsuppress' => 'Abgelehnt und unterdrückt',
	'mwoauthmanageconsumers-disable' => 'Deaktiviert',
	'mwoauthmanageconsumers-dsuppress' => 'Deaktiviert und unterdrückt',
	'mwoauthmanageconsumers-reenable' => 'Bestätigt',
	'mwoauthmanageconsumers-reason' => 'Grund:',
	'mwoauthmanageconsumers-confirm-submit' => 'Verbraucherstatus aktualisieren',
	'mwoauthmanageconsumers-viewing' => '„$1“ betrachtet derzeit diesen Verbraucher',
	'mwoauthmanageconsumers-success-approved' => 'Der Antrag wurde bestätigt.',
	'mwoauthmanageconsumers-success-rejected' => 'Der Antrag wurde abgelehnt.',
	'mwoauthmanageconsumers-success-disabled' => 'Der Verbraucher wurde deaktiviert.',
	'mwoauthmanageconsumers-success-reanable' => 'Der Verbraucher wurde reaktiviert.',
	'mwoauthmanageconsumers-search-name' => 'Verbraucher mit diesem Namen',
	'mwoauthmanageconsumers-search-publisher' => 'Verbraucher von diesem Benutzer',
	'mwoauthlistconsumers' => 'OAuth-Verbraucher auflisten',
	'mwoauthlistconsumers-legend' => 'OAuth-Verbraucher durchsuchen',
	'mwoauthlistconsumers-view' => 'Einzelheiten',
	'mwoauthlistconsumers-none' => 'Es wurden keine Verbraucher gefunden, die diesen Kriterien entsprechen.',
	'mwoauthlistconsumers-name' => 'Anwendungsname',
	'mwoauthlistconsumers-version' => 'Verbraucherversion',
	'mwoauthlistconsumers-user' => 'Herausgeber',
	'mwoauthlistconsumers-description' => 'Beschreibung',
	'mwoauthlistconsumers-wiki' => 'Anwendbares Wiki',
	'mwoauthlistconsumers-callbackurl' => 'OAuth-Callback-URL',
	'mwoauthlistconsumers-grants' => 'Anwendbare Berechtigungen',
	'mwoauthlistconsumers-basicgrantsonly' => '(nur Basiszugriff)',
	'mwoauthlistconsumers-status' => 'Status',
	'mwoauth-consumer-stage-any' => 'alle',
	'mwoauthlistconsumers-status-proposed' => 'geplant',
	'mwoauthlistconsumers-status-approved' => 'bestätigt',
	'mwoauthlistconsumers-status-disabled' => 'deaktiviert',
	'mwoauthlistconsumers-status-rejected' => 'abgelehnt',
	'mwoauthlistconsumers-status-expired' => 'abgelaufen',
	'mwoauthmanagemygrants' => 'Benutzerkonten-OAuth-Berechtigungen verwalten',
	'mwoauthmanagemygrants-text' => 'Diese Seite listet alle Anwendungen auf, die dein Benutzerkonto verwenden können. Für jede Anwendung ist der Zugriffsbereich durch die von dir gewährten Berechtigungen beschränkt, wenn du die Anwendung zum Handeln auf deinen Namen autorisiert hast. Falls du einen Verbraucher separat autorisiert hast, um auf unterschiedliche Schwesterprojekte zuzugreifen, dann wirst du unten separate Konfigurationen für jedes Projekt sehen.',
	'mwoauthmanagemygrants-notloggedin' => 'Du musst angemeldet sein, um auf diese Seite zugreifen zu können.',
	'mwoauthmanagemygrants-navigation' => 'Navigation:',
	'mwoauthmanagemygrants-showlist' => 'Liste akzeptierter Verbraucher',
	'mwoauthmanagemygrants-none' => 'Derzeit sind keine Anwendungen mit deinem Benutzerkonto verbunden.',
	'mwoauthmanagemygrants-name' => 'Verbrauchername',
	'mwoauthmanagemygrants-user' => 'Herausgeber',
	'mwoauthmanagemygrants-description' => 'Beschreibung',
	'mwoauthmanagemygrants-wiki' => 'Anwendbares Wiki',
	'mwoauthmanagemygrants-wikiallowed' => 'Erlaubt auf Wiki',
	'mwoauthmanagemygrants-grants' => 'Anwendbare Berechtigungen',
	'mwoauthmanagemygrants-grantsallowed' => 'Erlaubte Berechtigungen:',
	'mwoauthmanagemygrants-applicablegrantsallowed' => 'Erlaubte anwendbare Berechtigungen:',
	'mwoauthmanagemygrants-consumerkey' => 'Verbraucherschlüssel',
	'mwoauthmanagemygrants-review' => 'Zugriff verwalten',
	'mwoauthmanagemygrants-revoke' => 'Zugriff entziehen',
	'mwoauthmanagemygrants-grantaccept' => 'Gewährt',
	'mwoauthmanagemygrants-update-text' => 'Benutze das unten stehende Formular, um die gewährten Berechtigungen für eine Anwendung (OAuth-Verbraucher) zu ändern, die auf deinen Namen handelt.
* Falls du eine Anwendung separat autorisiert hast, um auf unterschiedliche Schwesterprojekte zuzugreifen, dann wirst du separate Konfigurationen für jedes dieser Projekte haben.
* Das Verwenden von „*“ im Wikifeld gewährt Zugriff auf alle Projekte dieser Website; eine Wikiprojektkennung beschränkt den Zugriff auf ein einzelnes Projekt. Je-Projekt-Einstellungen haben Vorrang.', # Fuzzy
	'mwoauthmanagemygrants-revoke-text' => 'Benutze das unten stehende Formular, um den Zugriff für eine Anwendung (OAuth-Verbraucher) zu entziehen, die auf deinen Namen handelt.
* Falls du eine Anwendung separat autorisiert hast, um auf unterschiedliche Schwesterprojekte zuzugreifen, dann wirst du separate Konfigurationen für jedes dieser Projekte haben.
* Wenn du den Zugriff für eine Anwendung vollständig entziehen willst, stelle sicher, dass du ihn von allen Projekten entfernst, auf denen du die Berechtigungen erteilt hast.',
	'mwoauthmanagemygrants-confirm-legend' => 'Verbraucherzugriffstoken verwalten',
	'mwoauthmanagemygrants-update' => 'Berechtigungen aktualisieren',
	'mwoauthmanagemygrants-renounce' => 'Deautorisieren',
	'mwoauthmanagemygrants-action' => 'Status ändern:',
	'mwoauthmanagemygrants-confirm-submit' => 'Zugriffstokenstatus aktualisieren',
	'mwoauthmanagemygrants-success-update' => 'Der Zugriffstoken für diesen Verbraucher wurde aktualisiert.',
	'mwoauthmanagemygrants-success-renounce' => 'Der Zugriffstoken für diesen Verbraucher wurde gelöscht.',
	'logentry-mwoauthconsumer-propose' => '$1 {{GENDER:$2|plante}} einen OAuth-Verbraucher (Verbraucherschlüssel $4)',
	'logentry-mwoauthconsumer-update' => '$1 {{GENDER:$2|aktualisierte}} einen OAuth-Verbraucher (Verbraucherschlüssel $4)',
	'logentry-mwoauthconsumer-approve' => '$1 {{GENDER:$2|bestätigte}} einen OAuth-Verbraucher von $3 (Verbraucherschlüssel $4)',
	'logentry-mwoauthconsumer-reject' => '$1 {{GENDER:$2|lehnte}} einen OAuth-Verbraucher von $3 ab (Verbraucherschlüssel $4)',
	'logentry-mwoauthconsumer-disable' => '$1 deaktivierte einen OAuth-Verbraucher von $3 (Verbraucherschlüssel $4)',
	'logentry-mwoauthconsumer-reenable' => '$1 {{GENDER:$2|reaktivierte}} einen OAuth-Verbraucher von $3 (Verbraucherschlüssel $4)',
	'mwoauthconsumer-consumer-logpage' => 'OAuth-Verbraucher-Logbuch',
	'mwoauthconsumer-consumer-logpagetext' => 'Logbuch von Bestätigungen, Ablehnungen und Deaktivierungen registrierter OAuth-Verbraucher.',
	'mwoauth-bad-request' => 'In deiner OAuth-Anfrage gab es einen Fehler.',
	'mwoauthdatastore-access-token-not-found' => 'Für diesen Autorisierungstoken wurde keine bestätigte Berechtigung gefunden',
	'mwoauthdatastore-request-token-not-found' => 'Für diesen Token wurde keine Anfrage gefunden',
	'mwoauthdatastore-bad-token' => 'Es wurde kein Token gefunden, der deiner Anfrage entspricht.',
	'mwoauthdatastore-bad-verifier' => 'Der angegebene Verifikationscode war nicht gültig',
	'mwoauthdatastore-invalid-token-type' => 'Der angeforderte Tokentyp ist ungültig',
	'mwoauthgrants-general-error' => 'In deiner OAuth-Anfrage gab es einen Fehler',
	'mwoauthserver-bad-consumer' => 'Für den angegebenen Schlüssel wurde kein bestätigter Verbraucher gefunden',
	'mwoauthserver-insufficient-rights' => 'Du hast keine ausreichenden Rechte, um diese Aktion auszuführen.',
	'mwoauthserver-invalid-request-token' => 'Deine Anfrage enthält einen ungültigen Token',
	'mwoauthserver-invalid-user-hookabort' => 'Dieser Benutzer kann nicht OAuth benutzen',
	'mwoauth-invalid-authorization-title' => 'OAuth-Autorisierungsfehler',
	'mwoauth-invalid-authorization' => 'Die Autorisierungsheader in deiner Anfrage sind nicht gültig: $1',
	'mwoauth-invalid-authorization-wrong-wiki' => 'Die Autorisierungsheader in deiner Anfrage sind nicht gültig für $1',
	'mwoauth-invalid-authorization-invalid-user' => 'Die Autorisierungsheader in deiner Anfrage sind für einen Benutzer, der hier nicht vorhanden ist.',
	'mwoauth-invalid-authorization-wrong-user' => 'Die Autorisierungsheader in deiner Anfrage sind für einen anderen Benutzer',
	'mwoauth-invalid-authorization-not-approved' => 'Die Autorisierungsheader in deiner Anfrage sind für einen OAuth-Verbraucher, der derzeit nicht bestätigt ist.',
	'mwoauth-invalid-authorization-blocked-user' => 'Die Autorisierungsheader in deiner Anfrage sind für einen Benutzer, der gesperrt ist.',
	'mwoauth-form-description-allwikis' => "Hallo $1,

'''$2''' will die folgenden Aktionen auf allen Projekten auf dieser Website in deinem Namen ausführen:


$4",
	'mwoauth-form-description-onewiki' => "Hallo $1,

'''$2''' will die folgenden Aktionen auf ''$4'' in deinem Namen ausführen:


$5",
	'mwoauth-form-description-allwikis-nogrants' => "Hallo $1,

'''$2''' will Basiszugriff in deinem Namen auf allen Projekten dieser Website haben.",
	'mwoauth-form-description-onewiki-nogrants' => "Hallo $1,

'''$2''' will Basiszugriff in deinem Namen auf ''$4'' haben.",
	'mwoauth-form-privacypolicy-link' => '[[{{ns:Project}}:Privacy policy|Datenschutzrichtlinie]]',
	'mwoauth-form-button-approve' => 'Erlauben',
	'mwoauth-form-button-cancel' => 'Abbrechen',
	'mwoauth-authorize-form-invalid-user' => 'Dieses Benutzerkonto kann nicht OAuth verwenden, da das Konto auf diesem Wiki und das Konto auf dem zentralen OAuth-Wiki nicht verknüpft sind.',
	'mwoauth-error' => 'OAuth-Fehler',
	'mwoauth-grants-heading' => 'Angeforderte Berechtigungen:',
	'mwoauth-grants-nogrants' => 'Die Anwendung hat keine Berechtigungen beantragt.',
	'mwoauth-acceptance-cancelled' => 'Du hast diese Anfrage zur Autorisierung eines OAuth-Verbrauchers abgebrochen.',
	'mwoauth-grant-group-page-interaction' => 'Mit Seiten interagieren',
	'mwoauth-grant-group-file-interaction' => 'Mit Medien interagieren',
	'mwoauth-grant-group-watchlist-interaction' => 'Mit deiner Beobachtungsliste interagieren',
	'mwoauth-grant-group-email' => 'E-Mail versenden',
	'mwoauth-grant-group-high-volume' => 'Massenaktivitäten ausführen',
	'mwoauth-grant-group-customization' => 'Anpassung und Einstellungen',
	'mwoauth-grant-group-administration' => 'Administrative Aktionen ausführen',
	'mwoauth-grant-group-other' => 'Verschiedene Aktivitäten',
	'mwoauth-grant-blockusers' => 'Benutzer sperren und freigeben',
	'mwoauth-grant-createaccount' => 'Benutzerkonten erstellen',
	'mwoauth-grant-createeditmovepage' => 'Seiten erstellen, bearbeiten und verschieben',
	'mwoauth-grant-delete' => 'Seiten, Versionen und Logbucheinträge löschen',
	'mwoauth-grant-editinterface' => 'MediaWiki-Namensraum und Benutzer-CSS/JS bearbeiten',
	'mwoauth-grant-editmycssjs' => 'Deine eigene Benutzer-CSS/JS bearbeiten',
	'mwoauth-grant-editmywatchlist' => 'Deine Beobachtungsliste bearbeiten',
	'mwoauth-grant-editpage' => 'Vorhandene Seiten bearbeiten',
	'mwoauth-grant-editprotected' => 'Geschützte Seiten bearbeiten',
	'mwoauth-grant-highvolume' => 'Massenbearbeitungen',
	'mwoauth-grant-oversight' => 'Benutzer verstecken und Versionen unterdrücken',
	'mwoauth-grant-patrol' => 'Änderungen an Seiten kontrollieren',
	'mwoauth-grant-protect' => 'Seiten schützen und freigeben',
	'mwoauth-grant-rollback' => 'Änderungen an Seiten zurücksetzen',
	'mwoauth-grant-sendemail' => 'E-Mails an andere Benutzer versenden',
	'mwoauth-grant-uploadeditmovefile' => 'Dateien hochladen, ersetzen und verschieben',
	'mwoauth-grant-uploadfile' => 'Neue Dateien hochladen',
	'mwoauth-grant-useoauth' => 'Basisrechte',
	'mwoauth-grant-viewdeleted' => 'Gelöschte Informationen ansehen',
	'mwoauth-grant-viewmywatchlist' => 'Deine Beobachtungsliste ansehen',
	'mwoauth-oauth-exception' => 'Im OAuth-Protokoll ist ein Fehler aufgetreten: $1',
	'mwoauth-callback-not-oob' => 'oauth_callback muss auf „oob“ festgelegt sein (Groß-/Kleinschreibung beachten)',
	'right-mwoauthproposeconsumer' => 'Neue OAuth-Verbraucher planen',
	'right-mwoauthupdateownconsumer' => 'OAuth-Verbraucher aktualisieren',
	'right-mwoauthmanageconsumer' => 'OAuth-Verbraucher verwalten',
	'right-mwoauthsuppress' => 'OAuth-Verbraucher unterdrücken',
	'right-mwoauthviewsuppressed' => 'Unterdrückte OAuth-Verbraucher ansehen',
	'right-mwoauthviewprivate' => 'Private OAuth-Daten ansehen',
	'right-mwoauthmanagemygrants' => 'OAuth-Berechtigungen verwalten',
	'action-mwoauthmanageconsumer' => 'OAuth-Verbraucher zu verwalten',
	'action-mwoauthmanagemygrants' => 'deine OAuth-Berechtigungen zu verwalten',
	'action-mwoauthproposeconsumer' => 'neue OAuth-Verbraucher zu planen',
	'action-mwoauthupdateownconsumer' => 'OAuth-Verbraucher zu aktualisieren',
	'action-mwoauthviewsuppressed' => 'unterdrückte OAuth-Verbraucher anzusehen',
);

/** British English (British English)
 * @author Shirayuki
 */
$messages['en-gb'] = array(
	'mwoauth-desc' => 'OAuth 1.0a API Authorisation',
	'mwoauthconsumerregistration-propose-text' => 'Developers should use the form below to propose a new OAuth consumer (see the [//www.mediawiki.org/wiki/Extension:OAuth extension documentation] for more details). After submitting this form, you will receive a token that your application will use to identify itself to MediaWiki. An OAuth administrator will need to approve your application before it can be authorised by other users.

A few recommendations and remarks:
* Try to use as few grants as possible. Avoid grants that are not actually needed now.
* Versions are of the form "major.minor.release" (the last two being optional) and increase as grant changes are needed.
* Please provide a public RSA key (in PEM format) if possible; otherwise a (less secure) secret token will have to be used.
* Use the JSON restrictions field to limit access of this consumer to IP addresses in those CIDR ranges.
* You can use a wiki ID to restrict the consumer to a single wiki on this site (use "*" for all wikis).
* The email address provided must match that of your account (which must have been confirmed).',
	'mwoauthmanagemygrants-confirm-text' => 'Use the form below to revoke access or change grants for an OAuth consumer to act on your behalf.

Note that if you authorised a consumer to only have access to a subset of wikis (site projects), then there will be multiple access tokens for that consumer.', # Fuzzy
	'mwoauthmanagemygrants-renounce' => 'Deauthorise',
	'mwoauthdatastore-access-token-not-found' => 'No approved grant was found for that authorisation token',
	'mwoauth-invalid-authorization-title' => 'OAuth authorisation error',
	'mwoauth-invalid-authorization' => 'The authorisation headers in your request are not valid: $1',
	'mwoauth-invalid-authorization-wrong-wiki' => 'The authorisation headers in your request are not valid for $1',
	'mwoauth-invalid-authorization-invalid-user' => "The authorisation headers in your request are for a user that doesn't exist here",
	'mwoauth-invalid-authorization-wrong-user' => 'The authorisation headers in your request are for a different user',
	'mwoauth-invalid-authorization-not-approved' => 'The authorisation headers in your request are for an OAuth consumer that is not currently approved',
	'mwoauth-invalid-authorization-blocked-user' => 'The authorisation headers in your request are for a user who is blocked',
	'mwoauth-acceptance-cancelled' => 'You have cancelled this request to authorise an OAuth consumer to act on your behalf.',
	'mwoauth-grant-group-customization' => 'Customisation and preferences',
);

/** Spanish (español)
 * @author Fitoschido
 * @author Ovruni
 */
$messages['es'] = array(
	'mwoauth-verified' => "La aplicación ahora puede acceder a MediaWiki en tu nombre.

Para completar el proceso, proporciona este valor de comprobación a la aplcación: '''$1'''",
	'mwoauth-invalid-field-generic' => 'Se ha proporcionado un valor no válido',
	'mwoauth-consumer-allwikis' => 'Todos los proyectos en este sitio',
	'mwoauthmanagemygrants-review' => 'administrar el acceso',
	'mwoauthmanagemygrants-grantaccept' => 'Concedido',
	'mwoauthmanagemygrants-update' => 'Actualizar permisos',
	'mwoauthmanagemygrants-renounce' => 'No autorizado',
	'mwoauth-form-button-approve' => 'Permitir',
	'mwoauth-form-button-cancel' => 'Cancelar',
	'mwoauth-grant-group-page-interaction' => 'Interactuar con páginas',
	'mwoauth-grant-group-email' => 'Enviar correo electrónico',
	'mwoauth-grant-createaccount' => 'Crear cuentas',
	'mwoauth-grant-sendemail' => 'Enviar un correo electrónico a otros usuarios',
	'mwoauth-oauth-exception' => 'Ha ocurrido un error en el protocolo OAuth: $1',
);

/** Persian (فارسی)
 * @author Ebraminio
 */
$messages['fa'] = array(
	'mwoauth-field-hidden' => '(این اطلاعات پنهان است)',
);

/** Finnish (suomi)
 * @author Nike
 */
$messages['fi'] = array(
	'mwoauth-prefs-managegrants' => 'Liitetyt sovellukset:',
	'mwoauth-consumer-wiki-thiswiki' => 'Nykyinen wiki ($1)',
	'mwoauthmanagemygrants-none' => 'Yhtään sovellusta ei ole tällä hetkellä liitetty tunnukseesi.',
	'mwoauth-grant-group-customization' => 'Mukautus ja asetukset',
);

/** French (français)
 * @author Crochet.david
 * @author Gomoko
 * @author Jean-Frédéric
 * @author Linedwell
 * @author Louperivois
 * @author Wyz
 */
$messages['fr'] = array(
	'mwoauth-desc' => 'API d’authentification OAuth 1.0a',
	'mwoauth-verified' => "L’application peut maintenant accéder à MediaWiki en votre nom.

Pour terminer le processus, veuillez fournir cette valeur de vérification à l’application : ''' $1 '''",
	'mwoauth-missing-field' => 'Valeur manquante pour le champ « $1 »',
	'mwoauth-invalid-field' => 'Valeur invalide fournie pour le champ « $1 »',
	'mwoauth-invalid-field-generic' => 'Valeur non valide fournie',
	'mwoauth-field-hidden' => '(cette information est masquée)',
	'mwoauth-field-private' => '(cette information est privée)',
	'mwoauth-grant-generic' => 'ensemble de droits « $1 »',
	'mwoauth-prefs-managegrants' => 'Accès du consommateur OAuth :',
	'mwoauth-prefs-managegrantslink' => 'Gérer les droits au nom de ce compte',
	'mwoauth-consumer-allwikis' => 'Tous les projets sur ce site',
	'mwoauth-consumer-key' => 'Clé du consommateur :',
	'mwoauth-consumer-name' => "Nom de l'application :",
	'mwoauth-consumer-version' => 'Version du consommateur :',
	'mwoauth-consumer-user' => 'Éditeur :',
	'mwoauth-consumer-stage' => 'Statut actuel :',
	'mwoauth-consumer-email' => 'Adresse de courriel de contact :',
	'mwoauth-consumer-description' => "Description de l'application :",
	'mwoauth-consumer-callbackurl' => 'URl « de rappel » pour OAuth :',
	'mwoauth-consumer-grantsneeded' => 'Droits applicables :',
	'mwoauth-consumer-required-grant' => 'Applicable au consommateur',
	'mwoauth-consumer-wiki' => 'Wiki applicable :',
	'mwoauth-consumer-restrictions' => 'Limitations d’utilisation :',
	'mwoauth-consumer-restrictions-json' => 'Limitations d’utilisation (JSON) :',
	'mwoauth-consumer-rsakey' => 'Clé RSA publique :',
	'mwoauth-consumer-secretkey' => 'Jeton secret du consommateur :',
	'mwoauth-consumer-accesstoken' => 'Jeton d’accès :',
	'mwoauth-consumer-reason' => 'Motif :',
	'mwoauth-consumer-email-unconfirmed' => 'Votre adresse de courriel du compte n’a pas encore été confirmée.',
	'mwoauth-consumer-email-mismatched' => 'L’adresse de courriel fournie doit correspondre à celle de votre compte.',
	'mwoauth-consumer-alreadyexists' => 'Un consommateur avec cette combinaison de nom/version/éditeur existe déjà',
	'mwoauth-consumer-alreadyexistsversion' => 'Un consommateur avec cette combinaison de nom/éditeur existe déjà avec une version égale ou supérieure ("$1")',
	'mwoauth-consumer-not-accepted' => 'Impossible de mettre à jour les informations pour une demande de consommateur en cours',
	'mwoauth-consumer-not-proposed' => 'Le consommateur n’est actuellement pas proposé',
	'mwoauth-consumer-not-disabled' => 'Le consommateur n’est pas désactivé pour le moment',
	'mwoauth-consumer-not-approved' => 'Le consommateur n’est pas approuvé (il peut avoir été désactivé)',
	'mwoauth-invalid-consumer-key' => 'Aucun consommateur n’existe avec la clé fournie.',
	'mwoauth-invalid-access-token' => 'Aucun jeton d’accès n’existe pour la clé fournie',
	'mwoauth-consumer-conflict' => 'Quelqu’un a modifié les attributs de ce consommateur pendant que vous le consultiez. Veuillez réessayer. Vous pouvez aussi vérifier le journal des modifications.',
	'mwoauth-consumer-stage-proposed' => 'proposé',
	'mwoauth-consumer-stage-rejected' => 'rejeté',
	'mwoauth-consumer-stage-expired' => 'expiré',
	'mwoauth-consumer-stage-approved' => 'approuvé',
	'mwoauth-consumer-stage-disabled' => 'désactivé',
	'mwoauth-consumer-stage-suppressed' => 'supprimé',
	'mwoauthconsumerregistration' => 'Inscription du consommateur OAuth',
	'mwoauthconsumerregistration-notloggedin' => 'Vous devez être connecté pour accéder à cette page.',
	'mwoauthconsumerregistration-navigation' => 'Navigation :',
	'mwoauthconsumerregistration-propose' => 'Proposer un nouveau consommateur',
	'mwoauthconsumerregistration-list' => 'Ma liste de consommateurs',
	'mwoauthconsumerregistration-main' => 'Principal',
	'mwoauthconsumerregistration-propose-text' => 'Les développeurs devraient utiliser le formulaire ci-dessous pour proposer un nouveau consommateur OAuth (voir la [//www.mediawiki.org/wiki/Extension:OAuth documentation de l’extension] pour plus de détails). Après avoir publié ce formulaire, vous recevrez un jeton que votre application utilisera pour s’identifier auprès de MediaWiki. Un administrateur OAuth devra approuver votre application avant qu’elle puis être autorisée  par les autres utilisateurs.

Quelques recommandations et remarques :
* Essayez d’utiliser le moins de droits possibles. Évitez les droits qui ne sont pas vraiment nécessaires pour le moment.
* Les versions sont de la forme « majeure.mineure.révision » (les deux derniers étant facultatifs) et augmentent quand des modifications de droit sont nécessaires.
* Veuillez fournir une clé publique RSA (au format PEM) si possible ; sinon, un jeton secret (moins sécurisé) vous sera assigné.
* Utilisez le champ limitations JSON pour limiter l’accès de ce consommateur aux adresses IP dans ces plages de CIDR.
* Vous pouvez utiliser un ID de wiki pour limiter ce consommateur à un unique wiki de ce site (utilisez "*" pour tous les wikis).
* L’adresse de courriel fournie doit correspondre à celle de votre compte (qui doit avoir été confirmée).',
	'mwoauthconsumerregistration-update-text' => 'Utilisez le formulaire ci-dessous pour mettre à jour les aspects d’un consommateur OAuth que vous contrôlez.

Toutes les valeurs ici écraseront les précédentes. Ne laissez aucun champ blanc sauf si vous désirez vraiment effacer ces valeurs.',
	'mwoauthconsumerregistration-maintext' => 'Cette page sert à laisser les développeurs proposer et mettre à jour des applications consommatrices OAuth dans le registre de ce site.

Depuis ici, vous pouvez :
* [[Special:MWOAuthConsumerRegistration/propose|Demander un jeton pour un nouveau consommateur]].
* [[Special:MWOAuthConsumerRegistration/list|Gérer os consommateurs existants]].

Pour plus d’information sur OAuth, voyez la [//www.mediawiki.org/wiki/Extension:OAuth documentation de l’extension].',
	'mwoauthconsumerregistration-propose-legend' => 'Nouvelle application consommatrice OAuth',
	'mwoauthconsumerregistration-update-legend' => 'Mettre à jour l’application consommatrice OAuth',
	'mwoauthconsumerregistration-propose-submit' => 'Proposer un consommateur',
	'mwoauthconsumerregistration-update-submit' => 'Mettre à jour un consommateur',
	'mwoauthconsumerregistration-none' => 'Vous ne contrôlez aucun consommateur OAuth.',
	'mwoauthconsumerregistration-name' => 'Consommateur',
	'mwoauthconsumerregistration-user' => 'Éditeur',
	'mwoauthconsumerregistration-description' => 'Description',
	'mwoauthconsumerregistration-email' => 'Courriel de contact',
	'mwoauthconsumerregistration-consumerkey' => 'Clé du consommateur',
	'mwoauthconsumerregistration-stage' => 'État',
	'mwoauthconsumerregistration-lastchange' => 'Dernière modification',
	'mwoauthconsumerregistration-manage' => 'gérer',
	'mwoauthconsumerregistration-resetsecretkey' => 'Réinitialiser la clé secrète avec une nouvelle valeur',
	'mwoauthconsumerregistration-proposed' => "Votre demande de consommateur OAuth a été reçue.

Il vous a été assigné un jeton de consommateur '''$1''' et un jeton secret '''$2'''. ''Veuillez les conserver pour des besoins ultérieurs.''",
	'mwoauthconsumerregistration-updated' => 'Votre registre de consommateur OAuth a bien été mis à jour.',
	'mwoauthconsumerregistration-secretreset' => "Un jeton secret de consommateur de '''$1''' vous a été assigné. ''Veuillez le conserver pour tout besoin ultérieur.''",
	'mwoauthmanageconsumers' => 'Gérer les consommateurs OAuth',
	'mwoauthmanageconsumers-notloggedin' => 'Vous devez être connecté pour accéder à cette page.',
	'mwoauthmanageconsumers-type' => 'Files :',
	'mwoauthmanageconsumers-showproposed' => 'Requêtes proposées',
	'mwoauthmanageconsumers-showrejected' => 'Requêtes rejetées',
	'mwoauthmanageconsumers-showexpired' => 'Requêtes expirées',
	'mwoauthmanageconsumers-main' => 'Principal',
	'mwoauthmanageconsumers-maintext' => 'Cette page a pour but fr gérer les demandes d’applications consommatrices OAuth (voir http://oauth.net) et de gérer les consommateurs OAuth existants.',
	'mwoauthmanageconsumers-queues' => 'Sélectionner une file de confirmation de consommateur ci-dessous :',
	'mwoauthmanageconsumers-q-proposed' => 'File des requêtes de consommateur proposés',
	'mwoauthmanageconsumers-q-rejected' => 'File des requêtes de consommateur rejetées',
	'mwoauthmanageconsumers-q-expired' => 'File des requêtes de consommateur expirées',
	'mwoauthmanageconsumers-lists' => 'Sélectionner une liste d’état de consommateur ci-dessous :',
	'mwoauthmanageconsumers-l-approved' => 'Liste des consommateurs actuellement approuvés',
	'mwoauthmanageconsumers-l-disabled' => 'Liste des consommateurs actuellement désactivés',
	'mwoauthmanageconsumers-none-proposed' => 'Aucun consommateur proposé dans cette liste.',
	'mwoauthmanageconsumers-none-rejected' => 'Aucun consommateur proposé dans cette liste.',
	'mwoauthmanageconsumers-none-expired' => 'Aucun consommateur proposé dans cette liste.',
	'mwoauthmanageconsumers-none-approved' => 'Aucun consommateur ne répond à ce critère.',
	'mwoauthmanageconsumers-none-disabled' => 'Aucun consommateur ne correspond à ce critère.',
	'mwoauthmanageconsumers-name' => 'Consommateur',
	'mwoauthmanageconsumers-user' => 'Éditeur',
	'mwoauthmanageconsumers-description' => 'Description',
	'mwoauthmanageconsumers-email' => 'Courriel de contact',
	'mwoauthmanageconsumers-consumerkey' => 'Clé de consommateur',
	'mwoauthmanageconsumers-lastchange' => 'Dernière modification',
	'mwoauthmanageconsumers-review' => 'revoir/gérer',
	'mwoauthmanageconsumers-confirm-text' => 'Utilisez ce formulaire pour approuver, rejeter, désactiver ou réactiver ce consommateur.',
	'mwoauthmanageconsumers-confirm-legend' => 'Gérer le consommateur OAuth',
	'mwoauthmanageconsumers-action' => 'Modifier l’état :',
	'mwoauthmanageconsumers-approve' => 'Approuvé',
	'mwoauthmanageconsumers-reject' => 'Rejeté',
	'mwoauthmanageconsumers-rsuppress' => 'Rejeté et supprimé',
	'mwoauthmanageconsumers-disable' => 'Désactivé',
	'mwoauthmanageconsumers-dsuppress' => 'Désactivé et supprimé',
	'mwoauthmanageconsumers-reenable' => 'Approuvé',
	'mwoauthmanageconsumers-reason' => 'Motif :',
	'mwoauthmanageconsumers-confirm-submit' => 'Mettre à jour l’état du consommateur',
	'mwoauthmanageconsumers-viewing' => 'L’utilisateur « $1 » est actuellement en train de visualiser ce consommateur',
	'mwoauthmanageconsumers-success-approved' => 'La requête a été approuvée.',
	'mwoauthmanageconsumers-success-rejected' => 'La requête a été rejetée.',
	'mwoauthmanageconsumers-success-disabled' => 'Le consommateur a été désactivé.',
	'mwoauthmanageconsumers-success-reanable' => 'Le consommateur a été réactivé.',
	'mwoauthmanagemygrants' => 'Gérer les droits de compte OAuth',
	'mwoauthmanagemygrants-notloggedin' => 'Vous devez être connecté pour accéder à cette page.',
	'mwoauthmanagemygrants-navigation' => 'Navigation :',
	'mwoauthmanagemygrants-showlist' => 'Liste de consommateurs acceptés',
	'mwoauthmanagemygrants-none' => 'Aucun consommateur n’a d’accès de la part de votre compte.',
	'mwoauthmanagemygrants-name' => 'Nom du consommateur',
	'mwoauthmanagemygrants-user' => 'Éditeur',
	'mwoauthmanagemygrants-description' => 'Description',
	'mwoauthmanagemygrants-wiki' => 'Wiki applicable',
	'mwoauthmanagemygrants-wikiallowed' => 'Autorisé sur le wiki',
	'mwoauthmanagemygrants-grants' => 'Droits applicables',
	'mwoauthmanagemygrants-grantsallowed' => 'Droits accordés :',
	'mwoauthmanagemygrants-applicablegrantsallowed' => 'Droits applicables accordés :',
	'mwoauthmanagemygrants-consumerkey' => 'Clé du consommateur',
	'mwoauthmanagemygrants-review' => 'gérer l’accès',
	'mwoauthmanagemygrants-grantaccept' => 'Accordé',
	'mwoauthmanagemygrants-confirm-text' => 'Utilisez le formulaire ci-dessous pour révoquer l’accès ou modifier les droits d’un consommateur OAuth à agir en votre nom. Quelques remarques :
* Ce formulaire contrôle un « jeton », ou clé, d’accès particulier, qui permet à un consommateur d’accéder à votre compte.
* Si vous autorisez de façon isolée un consommateur à avoir accès à différents projets en votre nom, alors vous aurez des jetons d’accès multiples pour ce consommateur.
* Si vous voulez révoquer l’accès du consommateur, assurez-vous d’annuler tous les jetons d’accès pour toutes les versions de ce consommateur que vous avez acceptées.
* Utiliser "*" dans le champ wiki accorde l’accès à tous les projets de ce site ; utiliser un ID de projet du wiki limite l’accès à un unique projet.',
	'mwoauthmanagemygrants-confirm-legend' => 'Gérer le jeton d’accès du consommateur',
	'mwoauthmanagemygrants-update' => 'Mettre à jour les droits',
	'mwoauthmanagemygrants-renounce' => 'Ne plus autoriser',
	'mwoauthmanagemygrants-action' => 'Modifier l’état :',
	'mwoauthmanagemygrants-confirm-submit' => 'Mettre à jour l’état du jeton d’accès',
	'mwoauthmanagemygrants-success-update' => 'Le jeton d’accès pour ce consommateur a été mis à jour.',
	'mwoauthmanagemygrants-success-renounce' => 'Le jeton d’accès pour ce consommateur a été supprimé.',
	'logentry-mwoauthconsumer-propose' => '$1 {{GENDER:$2|a proposé}} un consommateur OAuth (clé du consommateur $4)',
	'logentry-mwoauthconsumer-update' => '$1 {{GENDER:$2|a mis à jour}} un consommateur OAuth (clé du consommateur $4)',
	'logentry-mwoauthconsumer-approve' => '$1 {{GENDER:$2|a approuvé}} un consommateur OAuth proposé par $3 (clé du consommateur $4)',
	'logentry-mwoauthconsumer-reject' => '$1 {{GENDER:$2|a rejeté}} un consommateur OAuth proposé par $3 (clé du consommateur $4)',
	'logentry-mwoauthconsumer-disable' => '$1 a désactivé un consommateur OAuth proposé par $3 (clé du consommateur $4)',
	'logentry-mwoauthconsumer-reenable' => '$1 {{GENDER:$2|a réactivé}} un consommateur OAuth proposé par $3 (clé du consommateur $4)',
	'mwoauthconsumer-consumer-logpage' => 'journal du consommateur OAuth',
	'mwoauthconsumer-consumer-logpagetext' => 'Journal des approbations, rejets et désactivations de consommateurs OAuth enregistrés.',
	'mwoauth-bad-csrf-token' => 'Erreur de session lors de l’envoi du formulaire. Veuillez réessayer vos envois.',
	'mwoauth-bad-request' => 'Il y a eu une erreur dans votre demande OAuth.',
	'mwoauthdatastore-access-token-not-found' => 'Aucun droit approuvé n’a été trouvé pour ce jeton d’autorisation.',
	'mwoauthdatastore-request-token-not-found' => 'Aucune demande n’a été trouvée pour ce jeton.',
	'mwoauthdatastore-bad-token' => 'Aucun jeton correspondant à votre demande n’a été trouvé',
	'mwoauthdatastore-bad-verifier' => 'Le code de vérification fourni n’était pas valide',
	'mwoauthdatastore-invalid-token-type' => 'Le type de jeton demandé n’est pas valide',
	'mwoauthgrants-general-error' => 'Il y a eu une erreur dans votre demande OAuth',
	'mwoauthserver-bad-consumer' => 'Aucun consommateur approuvé n’a été trouvé pour la clé fournie',
	'mwoauthserver-insufficient-rights' => 'Vous n’avez pas les droits suffisants pour effectuer cette action',
	'mwoauthserver-invalid-request-token' => 'Jeton non valide dans votre demande',
	'mwoauthserver-invalid-user-hookabort' => 'Cet utilisateur ne peut pas utiliser OAuth',
	'mwoauth-invalid-authorization-title' => 'Erreur d’autorisation OAuth',
	'mwoauth-invalid-authorization' => 'Les entêtes d’autorisation dans votre requête ne sont pas valides : $1',
	'mwoauth-invalid-authorization-wrong-wiki' => 'Les entêtes d’autorisation dans votre requête ne sont pas valides pour $1',
	'mwoauth-invalid-authorization-invalid-user' => 'Les entêtes d’autorisation dans votre requête concernent un utilisateur qui n’existe pas ici',
	'mwoauth-invalid-authorization-wrong-user' => 'Les entêtes d’autorisation dans votre requête concernent un autre utilisateur',
	'mwoauth-invalid-authorization-not-approved' => 'Les entêtes d’autorisation dans votre requête concernent un consommateur OAuth qui n’est pas approuvé pour le moment',
	'mwoauth-invalid-authorization-blocked-user' => 'Les entêtes d’autorisation dans votre requête concernent un utilisateur qui est bloqué',
	'mwoauth-form-description-allwikis' => "Bonjour $1,

'''$2''' souhaiterait faire les actions suivantes en votre nom sur tous les projets de ce site :


$4",
	'mwoauth-form-description-onewiki' => "Bonjour $1,

'''$2''' souhaiterait faire les actions suivantes en votre nom sur ''$4'':


$5",
	'mwoauth-form-description-allwikis-nogrants' => "Bonjour $1,

'''$2''' aimerait avoir un accès simple en votre nom à tous les projets de ce site.",
	'mwoauth-form-description-onewiki-nogrants' => "Bonjour $1,

'''$2''' aimerait avoir un accès simple en votre nom à ''$4''.",
	'mwoauth-form-privacypolicy-link' => '[[{{ns:Project}}:Privacy policy|Politique de confidentialité]]',
	'mwoauth-form-button-approve' => 'Autoriser',
	'mwoauth-form-button-cancel' => 'Annuler',
	'mwoauth-authorize-form-invalid-user' => 'Ce compte utilisateur ne peut pas utiliser OAuth, parce que le compte de ce wiki et le compte du wiki central OAuth ne sont pas liés.',
	'mwoauth-error' => 'Erreur OAuth',
	'mwoauth-grants-heading' => 'Droits requis :',
	'mwoauth-grants-nogrants' => 'L’application n’a demandé aucun droit.',
	'mwoauth-acceptance-cancelled' => 'Vous avez annulé cette demande d’autoriser un consommateur OAuth à agir en votre nom.',
	'mwoauth-grant-group-page-interaction' => 'Interagir avec des pages',
	'mwoauth-grant-group-file-interaction' => 'Interagir avec des médias',
	'mwoauth-grant-group-watchlist-interaction' => 'Interagir avec votre liste de suivi',
	'mwoauth-grant-group-email' => 'Envoyer un courriel',
	'mwoauth-grant-group-high-volume' => 'Effectuer une activité de fort volume',
	'mwoauth-grant-group-customization' => 'Consumérisation et préférences',
	'mwoauth-grant-group-administration' => 'Effectuer des actions administratives',
	'mwoauth-grant-group-other' => 'Activités diverses',
	'mwoauth-grant-blockusers' => 'Bloquer et débloquer les utilisateurs',
	'mwoauth-grant-createaccount' => 'Créer des comptes',
	'mwoauth-grant-createeditmovepage' => 'Créer, modifier et renommer des pages',
	'mwoauth-grant-delete' => 'Supprimer les pages, les révisions et les entrées du journal',
	'mwoauth-grant-editinterface' => 'Modifier le CSS et le JS de l’espace de nommage MédiaWiki et de l’utilisateur',
	'mwoauth-grant-editmycssjs' => 'Modifier votre propre CSS/JS utilisateur',
	'mwoauth-grant-editmywatchlist' => 'Modifier votre liste de suivi',
	'mwoauth-grant-editpage' => 'Modifier des pages existantes',
	'mwoauth-grant-editprotected' => 'Modifier les pages protégées',
	'mwoauth-grant-highvolume' => 'Modification de gros volumes',
	'mwoauth-grant-oversight' => 'Masquer les utilisateurs et supprimer les révisions',
	'mwoauth-grant-patrol' => 'Marquer des pages comme patrouillées',
	'mwoauth-grant-protect' => 'Protéger et déprotéger les pages',
	'mwoauth-grant-rollback' => 'Révoquer des modifications sur des pages',
	'mwoauth-grant-sendemail' => 'Envoyer des courriels aux autres utilisateurs',
	'mwoauth-grant-uploadeditmovefile' => 'Télécharger, remplacer et renommer des fichiers',
	'mwoauth-grant-uploadfile' => 'Importer de nouveaux fichiers',
	'mwoauth-grant-useoauth' => 'Droits de base',
	'mwoauth-grant-viewdeleted' => 'Afficher les informations supprimées',
	'mwoauth-grant-viewmywatchlist' => 'Afficher votre liste de suivi',
	'mwoauth-oauth-exception' => 'Une erreur s’est produite dans le protocole OAuth : $1',
	'mwoauth-callback-not-oob' => 'oauth_callback doit être défini, et doit valoir "oob" (en minuscules)',
	'right-mwoauthproposeconsumer' => 'Proposer des nouveaux consommateurs OAuth',
	'right-mwoauthupdateownconsumer' => 'Mettre à jour les consommateurs OAuth',
	'right-mwoauthmanageconsumer' => 'Gérer les consommateurs OAuth',
	'right-mwoauthsuppress' => 'Supprimer les consommateurs OAuth',
	'right-mwoauthviewsuppressed' => 'Afficher les consommateurs OAuth supprimés',
	'right-mwoauthviewprivate' => 'Afficher les données privées OAuth',
	'right-mwoauthmanagemygrants' => 'Gérer les droits OAuth',
	'action-mwoauthmanageconsumer' => 'gérer les consommateurs OAuth',
	'action-mwoauthmanagemygrants' => 'gérer vos droits OAuth',
	'action-mwoauthproposeconsumer' => 'proposer de nouveaux consommateurs OAuth',
	'action-mwoauthupdateownconsumer' => 'mettre à jour les consommateurs OAuth',
	'action-mwoauthviewsuppressed' => 'afficher les consommateurs OAuth supprimés',
);

/** Galician (galego)
 * @author Elisardojm
 * @author Toliño
 */
$messages['gl'] = array(
	'mwoauth-desc' => 'Autenticación API OAuth 1.0a',
	'mwoauth-verified' => "Agora, esta aplicación ten permitido acceder a MediaWiki no seu nome.

Para completar o proceso, achegue este valor de verificación á aplicación: '''$1'''",
	'mwoauth-missing-field' => 'Falta o valor para o campo "$1"',
	'mwoauth-invalid-field' => 'Achegouse un valor non válido para o campo "$1"',
	'mwoauth-invalid-field-generic' => 'O valor proporcionado non é válido',
	'mwoauth-field-hidden' => '(esta información está agochada)',
	'mwoauth-field-private' => '(esta información é privada)',
	'mwoauth-grant-generic' => 'conxunto de dereitos "$1"',
	'mwoauth-prefs-managegrants' => 'Acceso de consumidor OAuth:',
	'mwoauth-prefs-managegrantslink' => 'Administrar as concesións en nome desta conta',
	'mwoauth-consumer-allwikis' => 'Todos os proxectos deste sitio',
	'mwoauth-consumer-key' => 'Clave do consumidor:',
	'mwoauth-consumer-name' => 'Nome da aplicación:',
	'mwoauth-consumer-version' => 'Versión do consumidor:',
	'mwoauth-consumer-user' => 'Editor:',
	'mwoauth-consumer-stage' => 'Estado actual:',
	'mwoauth-consumer-email' => 'Enderezo de correo electrónico de contacto:',
	'mwoauth-consumer-description' => 'Descrición da aplicación:',
	'mwoauth-consumer-callbackurl' => 'URL de "chamada de retorno" do OAuth',
	'mwoauth-consumer-grantsneeded' => 'Concesións aplicables:',
	'mwoauth-consumer-required-grant' => 'Aplicable ao consumidor',
	'mwoauth-consumer-wiki' => 'Wiki aplicable:',
	'mwoauth-consumer-restrictions' => 'Restricións de uso:',
	'mwoauth-consumer-restrictions-json' => 'Restricións de uso (JSON):',
	'mwoauth-consumer-rsakey' => 'Clave pública RSA:',
	'mwoauth-consumer-secretkey' => 'Pase secreto do consumidor:',
	'mwoauth-consumer-accesstoken' => 'Pase de acceso:',
	'mwoauth-consumer-reason' => 'Motivo:',
	'mwoauth-consumer-email-unconfirmed' => 'Aínda non se confirmou o enderezo de correo electrónico da súa conta.',
	'mwoauth-consumer-email-mismatched' => 'O enderezo de correo electrónico achegado debe coincidir co da súa conta.',
	'mwoauth-consumer-alreadyexists' => 'Xa existe un consumidor con esa combinación de nome/versión/editor',
	'mwoauth-consumer-alreadyexistsversion' => 'Xa existe un consumidor con esa combinación de nome/editor cunha versión igual ou maior ("$1")',
	'mwoauth-consumer-not-accepted' => 'Non se pode actualizar a información dunha solicitude de consumidor pendente',
	'mwoauth-consumer-not-proposed' => 'O consumidor non está proposto actualmente',
	'mwoauth-consumer-not-disabled' => 'O consumidor non está desactivado actualmente',
	'mwoauth-consumer-not-approved' => 'O consumidor non está aprobado (se cadra, foi desactivado)',
	'mwoauth-invalid-consumer-key' => 'Non existe consumidor ningún coa clave achegada.',
	'mwoauth-invalid-access-token' => 'Non existe pase de acceso ningún coa clave achegada.',
	'mwoauth-consumer-conflict' => 'Alguén cambiou os atributos deste consumidor mentres o vía. Inténteo de novo. Se cadra, quere comprobar o rexistro de modificacións.',
	'mwoauth-consumer-stage-proposed' => 'proposto',
	'mwoauth-consumer-stage-rejected' => 'rexeitado',
	'mwoauth-consumer-stage-expired' => 'caducado',
	'mwoauth-consumer-stage-approved' => 'aprobado',
	'mwoauth-consumer-stage-disabled' => 'desactivado',
	'mwoauth-consumer-stage-suppressed' => 'suprimido',
	'mwoauthconsumerregistration' => 'Rexistro de consumidores OAuth',
	'mwoauthconsumerregistration-notloggedin' => 'Debe acceder ao sistema para acceder a esta páxina.',
	'mwoauthconsumerregistration-navigation' => 'Navegación:',
	'mwoauthconsumerregistration-propose' => 'Propoñer un novo consumidor',
	'mwoauthconsumerregistration-list' => 'A miña lista de consumidores',
	'mwoauthconsumerregistration-main' => 'Principal',
	'mwoauthconsumerregistration-propose-text' => 'Os desenvolvedores deben utilizar o formulario inferior para propoñer un novo consumidor OAuth (véxase a [//www.mediawiki.org/wiki/Extension:OAuth documentación da extensión] para atopar máis detalles). Despois de enviar este formulario, recibirá un pase que a súa aplicación usará para identificarse en MediaWiki. Un administrador de OAuth terá que aprobar a súa aplicación antes de poder ser autorizada por outros usuarios.

Algunhas recomendacións e observacións:
* Intente utilizar as menos concesións posibles. Evite as concesións que non sexan realmente necesarias agora.
* As versións son da forma "maior.menor.lanzamento" (os dous últimos son opcionais) e aumentan cando son necesarios cambios nas concesións.
* Achegue unha clave RSA pública (en formato PEM) se fose posible; en caso contrario, haberá que utilizar un pase secreto (menos seguro).
* Utilice o campo de restricións JSON para limitar o acceso deste consumidor aos enderezos IP neses rangos CIDR.
* Pode empregar un ID de wiki para restrinxir o consumidor a un único wiki neste sitio (utilice "*" para todos os wikis).
* O enderezo de correo electrónico achegado debe coincidir co da súa conta (que debeu ser confirmado).',
	'mwoauthconsumerregistration-update-text' => 'Utilice o formulario inferior para actualizar aspectos dun consumidor OAuth que controle.

Todos os valores que haxa aquí sobrescribirán os anteriores. Non deixe campos en branco a menos que queira limpar eses valores.',
	'mwoauthconsumerregistration-maintext' => 'Esta páxina está destinada a que os desenvolvedores propoñan e actualicen aplicacións de consumidores OAuth no rexistro do sitio.

Desde aquí, pode:
* [[Special:MWOAuthConsumerRegistration/propose|Solicitar un pase para un novo consumidor]].
* [[Special:MWOAuthConsumerRegistration/list|Administrar os consumidores existentes]].

Para obter máis información sobre OAuth, consulte a [//www.mediawiki.org/wiki/Extension:OAuth documentación da extensión].',
	'mwoauthconsumerregistration-propose-legend' => 'Nova aplicación de consumidores OAuth',
	'mwoauthconsumerregistration-update-legend' => 'Actualizar a aplicación de consumidores OAuth',
	'mwoauthconsumerregistration-propose-submit' => 'Propoñer o consumidor',
	'mwoauthconsumerregistration-update-submit' => 'Actualizar o consumidor',
	'mwoauthconsumerregistration-none' => 'Non controla ninún consumidor OAuth.',
	'mwoauthconsumerregistration-name' => 'Consumidor',
	'mwoauthconsumerregistration-user' => 'Editor',
	'mwoauthconsumerregistration-description' => 'Descrición',
	'mwoauthconsumerregistration-email' => 'Correo electrónico de contacto',
	'mwoauthconsumerregistration-consumerkey' => 'Clave do consumidor',
	'mwoauthconsumerregistration-stage' => 'Estado',
	'mwoauthconsumerregistration-lastchange' => 'Última modificación',
	'mwoauthconsumerregistration-manage' => 'administrar',
	'mwoauthconsumerregistration-resetsecretkey' => 'Restablecer a clave secreta cun novo valor',
	'mwoauthconsumerregistration-proposed' => "Recibiuse a súa solicitude de consumidor OAuth.

Asignóuselle o pase de consumidor '''$1''' e o pase secreto '''$2'''. ''Garde estes datos para unha futura referencia.''",
	'mwoauthconsumerregistration-updated' => 'Actualizouse correctamente o seu rexistro de consumidor OAuth.',
	'mwoauthconsumerregistration-secretreset' => "Asignóuselle o pase de consumidor '''$1'''. ''Garde estes datos para unha futura referencia.''",
	'mwoauthmanageconsumers' => 'Administrar os consumidores OAuth',
	'mwoauthmanageconsumers-notloggedin' => 'Debe acceder ao sistema para acceder a esta páxina.',
	'mwoauthmanageconsumers-type' => 'Colas:',
	'mwoauthmanageconsumers-showproposed' => 'Solicitudes propostas',
	'mwoauthmanageconsumers-showrejected' => 'Solicitudes rexeitadas',
	'mwoauthmanageconsumers-showexpired' => 'Solicitudes caducadas',
	'mwoauthmanageconsumers-main' => 'Principal',
	'mwoauthmanageconsumers-maintext' => 'Esta páxina está destinada a manexar solicitudes de aplicación de consumidor OAuth (véxase http://oauth.net) e a administrar os consumidores OAuth establecidos.',
	'mwoauthmanageconsumers-queues' => 'Seleccione unha cola de confirmación de consumidor a continuación:',
	'mwoauthmanageconsumers-q-proposed' => 'Cola das solicitudes de consumidor propostas',
	'mwoauthmanageconsumers-q-rejected' => 'Cola das solicitudes de consumidor rexeitadas',
	'mwoauthmanageconsumers-q-expired' => 'Cola das solicitudes de consumidor caducadas',
	'mwoauthmanageconsumers-lists' => 'Seleccione unha lista de estado de consumidor a continuación:',
	'mwoauthmanageconsumers-l-approved' => 'Lista dos consumidores aprobados actualmente',
	'mwoauthmanageconsumers-l-disabled' => 'Lista dos consumidores desactivados actualmente',
	'mwoauthmanageconsumers-none-proposed' => 'Non hai consumidores propostos nesta lista.',
	'mwoauthmanageconsumers-none-rejected' => 'Non hai consumidores propostos nesta lista.',
	'mwoauthmanageconsumers-none-expired' => 'Non hai consumidores propostos nesta lista.',
	'mwoauthmanageconsumers-none-approved' => 'Non hai ningún consumidor que coincida cos criterios.',
	'mwoauthmanageconsumers-none-disabled' => 'Non hai ningún consumidor que coincida cos criterios.',
	'mwoauthmanageconsumers-name' => 'Consumidor',
	'mwoauthmanageconsumers-user' => 'Editor',
	'mwoauthmanageconsumers-description' => 'Descrición',
	'mwoauthmanageconsumers-email' => 'Correo electrónico de contacto',
	'mwoauthmanageconsumers-consumerkey' => 'Clave do consumidor',
	'mwoauthmanageconsumers-lastchange' => 'Última modificación',
	'mwoauthmanageconsumers-review' => 'revisar/administrar',
	'mwoauthmanageconsumers-confirm-text' => 'Utilice este formulario para aprobar, rexeitar, desactivar ou reactivar este consumidor.',
	'mwoauthmanageconsumers-confirm-legend' => 'Administrar o consumidor OAuth',
	'mwoauthmanageconsumers-action' => 'Cambiar o estado:',
	'mwoauthmanageconsumers-approve' => 'Aprobado',
	'mwoauthmanageconsumers-reject' => 'Rexeitado',
	'mwoauthmanageconsumers-rsuppress' => 'Rexeitado e suprimido',
	'mwoauthmanageconsumers-disable' => 'Desactivado',
	'mwoauthmanageconsumers-dsuppress' => 'Desactivado e suprimido',
	'mwoauthmanageconsumers-reenable' => 'Aprobado',
	'mwoauthmanageconsumers-reason' => 'Motivo:',
	'mwoauthmanageconsumers-confirm-submit' => 'Actualizar o estado do consumidor',
	'mwoauthmanageconsumers-viewing' => 'O usuario "$1" está vendo este consumidor nestes intres',
	'mwoauthmanageconsumers-success-approved' => 'Aprobouse a solicitude.',
	'mwoauthmanageconsumers-success-rejected' => 'Rexeitouse a solicitude.',
	'mwoauthmanageconsumers-success-disabled' => 'Desactivouse o consumidor.',
	'mwoauthmanageconsumers-success-reanable' => 'Reactivouse o consumidor.',
	'mwoauthmanagemygrants' => 'Administrar as concesión de conta OAuth',
	'mwoauthmanagemygrants-notloggedin' => 'Debe acceder ao sistema para acceder a esta páxina.',
	'mwoauthmanagemygrants-navigation' => 'Navegación:',
	'mwoauthmanagemygrants-showlist' => 'Lista de consumidores aceptados',
	'mwoauthmanagemygrants-none' => 'Ningún consumidor ten acceso á súa conta.',
	'mwoauthmanagemygrants-name' => 'Nome do consumidor',
	'mwoauthmanagemygrants-user' => 'Editor',
	'mwoauthmanagemygrants-description' => 'Descrición',
	'mwoauthmanagemygrants-wiki' => 'Wiki aplicable',
	'mwoauthmanagemygrants-wikiallowed' => 'Permitido no wiki',
	'mwoauthmanagemygrants-grants' => 'Concesións aplicables',
	'mwoauthmanagemygrants-grantsallowed' => 'Concesións permitidas:',
	'mwoauthmanagemygrants-applicablegrantsallowed' => 'Concesións aplicables permitidas:',
	'mwoauthmanagemygrants-consumerkey' => 'Clave do consumidor',
	'mwoauthmanagemygrants-review' => 'administrar o acceso',
	'mwoauthmanagemygrants-grantaccept' => 'Concedido',
	'mwoauthmanagemygrants-confirm-text' => 'Utilice o formulario inferior para revogar o acceso ou cambiar as concesións dun consumidor OAuth para que actúe no seu nome. Algúns apuntamentos:
* Este formulario controla un "pase", ou clave, de acceso particular que permite a un consumidor acceder á súa conta.
* Se autorizou por separado que un consumidor teña acceso a diferentes proxectos no seu nome, entón terá múltiples pases de acceso para ese consumidor.
* Se quere revogar o acceso do consumidor, asegúrese de revogar todos os pases de acceso para todas as versións dese consumidor que aceptou.
* Ao poñer "*" no campo do wiki concédese acceso a todos os proxectos deste sitio; ao usar un ID de proxecto limítase o acceso a un único proxecto.',
	'mwoauthmanagemygrants-confirm-legend' => 'Administrar o pase de acceso do consumidor',
	'mwoauthmanagemygrants-update' => 'Actualizar as concesións',
	'mwoauthmanagemygrants-renounce' => 'Desautorizar',
	'mwoauthmanagemygrants-action' => 'Cambiar o estado:',
	'mwoauthmanagemygrants-confirm-submit' => 'Actualizar o estado do pase de acceso',
	'mwoauthmanagemygrants-success-update' => 'Actualizouse o pase de acceso deste consumidor.',
	'mwoauthmanagemygrants-success-renounce' => 'Borrouse o pase de acceso deste consumidor.',
	'logentry-mwoauthconsumer-propose' => '$1 {{GENDER:$2|propuxo}} un consumidor OAuth (clave de consumidor $4)',
	'logentry-mwoauthconsumer-update' => '$1 {{GENDER:$2|actualizou}} un consumidor OAuth (clave de consumidor $4)',
	'logentry-mwoauthconsumer-approve' => '$1 {{GENDER:$2|aprobou}} un consumidor OAuth de $3 (clave de consumidor $4)',
	'logentry-mwoauthconsumer-reject' => '$1 {{GENDER:$2|rexeitou}} un consumidor OAuth de $3 (clave de consumidor $4)',
	'logentry-mwoauthconsumer-disable' => '$1 {{GENDER:$2|desactivou}} un consumidor OAuth de $3 (clave de consumidor $4)',
	'logentry-mwoauthconsumer-reenable' => '$1 {{GENDER:$2|reactivou}} un consumidor OAuth de $3 (clave de consumidor $4)',
	'mwoauthconsumer-consumer-logpage' => 'Rexistro de consumidores OAuth',
	'mwoauthconsumer-consumer-logpagetext' => 'Rexistro de aprobacións, rexeitamentos e desactivacións dos consumidores OAuth rexistrados.',
	'mwoauth-bad-csrf-token' => 'Produciuse un erro de sesión ao enviar o formulario. Intente realizar o envío outra vez.',
	'mwoauth-bad-request' => 'Houbo un erro na súa solicitude OAuth.',
	'mwoauthdatastore-access-token-not-found' => 'Non se atopou ningunha concesión aprobada para ese pase de autorización',
	'mwoauthdatastore-request-token-not-found' => 'Non se atopou ningunha solicitude para ese pase',
	'mwoauthdatastore-bad-token' => 'Non se atopou ningún pase que coincidise coa solicitude',
	'mwoauthdatastore-bad-verifier' => 'O código de verificación achegado non é válido',
	'mwoauthdatastore-invalid-token-type' => 'O tipo de pase solicitado non é válido',
	'mwoauthgrants-general-error' => 'Houbo un erro na súa solicitude OAuth',
	'mwoauthserver-bad-consumer' => 'Non se atopou ningún consumidor aprobado para a clave achegada',
	'mwoauthserver-insufficient-rights' => 'Non ten os dereitos necesarios para levar a cabo esta acción',
	'mwoauthserver-invalid-request-token' => 'Pase non válido na súa solicitude',
	'mwoauthserver-invalid-user-hookabort' => 'Este usuario non pode utilizar OAuth',
	'mwoauth-invalid-authorization-title' => 'Erro de autorización OAuth',
	'mwoauth-invalid-authorization' => 'As cabeceiras de autorización da súa solicitude non son válidas: $1',
	'mwoauth-invalid-authorization-wrong-wiki' => 'As cabeceiras de autorización da súa solicitude non son válidas para $1',
	'mwoauth-invalid-authorization-invalid-user' => 'As cabeceiras de autorización da súa solicitude son para un usuario que non existe aquí',
	'mwoauth-invalid-authorization-wrong-user' => 'As cabeceiras de autorización da súa solicitude son para un usuario diferente',
	'mwoauth-invalid-authorization-not-approved' => 'As cabeceiras de autorización da súa solicitude son para un consumidor OAuth que non está aprobado actualmente',
	'mwoauth-invalid-authorization-blocked-user' => 'As cabeceiras de autorización da súa solicitude son para un usuario que está bloqueado',
	'mwoauth-form-description-allwikis' => "Boas $1:

'''$2''' quere realizar as seguintes accións no seu nome en todos os proxectos deste sitio:


$4",
	'mwoauth-form-description-onewiki' => "Boas $1:

'''$2''' quere realizar as seguintes accións no seu nome en ''$4'':


$5",
	'mwoauth-form-description-allwikis-nogrants' => "Boas $1:

'''$2''' quere ter un acceso básico no seu nome en todos os proxectos deste sitio.",
	'mwoauth-form-description-onewiki-nogrants' => "Boas $1:

'''$2''' quere ter un acceso básico no seu nome en ''$4''.",
	'mwoauth-form-privacypolicy-link' => '[[{{ns:Project}}:Privacy policy|Política de protección de datos]]',
	'mwoauth-form-button-approve' => 'Permitir',
	'mwoauth-form-button-cancel' => 'Cancelar',
	'mwoauth-authorize-form-invalid-user' => 'Esta conta de usuario non pode utilizar OAuth porque non están ligadas a conta neste wiki e a conta no wiki central de OAuth.',
	'mwoauth-error' => 'Erro OAuth',
	'mwoauth-grants-heading' => 'Permisos solicitados:',
	'mwoauth-grants-nogrants' => 'A aplicación non solicitou ningún permiso.',
	'mwoauth-acceptance-cancelled' => 'Cancelou esta solicitude de autorización para que o consumidor OAuth actúe no seu nome.',
	'mwoauth-grant-group-page-interaction' => 'Interactuar coas páxinas',
	'mwoauth-grant-group-file-interaction' => 'Interactuar cos ficheiros multimedia',
	'mwoauth-grant-group-watchlist-interaction' => 'Interactuar coa súa lista de vixilancia',
	'mwoauth-grant-group-email' => 'Enviar correos electrónicos',
	'mwoauth-grant-group-high-volume' => 'Realizar actividades de alto volume',
	'mwoauth-grant-group-customization' => 'Personalización e preferencias',
	'mwoauth-grant-group-administration' => 'Realizar accións administrativas',
	'mwoauth-grant-group-other' => 'Outras actividades',
	'mwoauth-grant-blockusers' => 'Bloquear e desbloquear usuarios',
	'mwoauth-grant-createaccount' => 'Crear contas',
	'mwoauth-grant-createeditmovepage' => 'Crear, editar e mover páxinas',
	'mwoauth-grant-delete' => 'Borrar páxinas, revisións e entradas de rexistro',
	'mwoauth-grant-editinterface' => 'Editar o espazo de nomes MediaWiki e o CSS/JS de usuario',
	'mwoauth-grant-editmycssjs' => 'Editar o propio CSS/JS de usuario',
	'mwoauth-grant-editmywatchlist' => 'Editar a súa lista de vixilancia',
	'mwoauth-grant-editpage' => 'Editar as páxinas existentes',
	'mwoauth-grant-editprotected' => 'Editar as páxinas protexidas',
	'mwoauth-grant-highvolume' => 'Edicións de gran volume',
	'mwoauth-grant-oversight' => 'Agochar usuarios e eliminar revisións',
	'mwoauth-grant-patrol' => 'Patrullar os cambios feitos ás páxinas',
	'mwoauth-grant-protect' => 'Protexer e desprotexer páxinas',
	'mwoauth-grant-rollback' => 'Reverter os cambios feitos ás páxinas',
	'mwoauth-grant-sendemail' => 'Enviar correos electrónicos a outros usuarios',
	'mwoauth-grant-uploadeditmovefile' => 'Cargar, substituír e mover ficheiros',
	'mwoauth-grant-uploadfile' => 'Cargar ficheiros novos',
	'mwoauth-grant-useoauth' => 'Dereitos básicos',
	'mwoauth-grant-viewdeleted' => 'Ver a información borrada',
	'mwoauth-grant-viewmywatchlist' => 'Ver a súa lista de vixilancia',
	'mwoauth-oauth-exception' => 'Ocorreu un erro no protocolo OAuth: $1',
	'mwoauth-callback-not-oob' => 'oauth_callback debe estar definido e ter o valor "oob" (distingue entre maiúsculas e minúsculas)',
	'right-mwoauthproposeconsumer' => 'Propoñer novos consumidores OAuth',
	'right-mwoauthupdateownconsumer' => 'Actualizar os consumidores OAuth que vostede controle',
	'right-mwoauthmanageconsumer' => 'Administrar os consumidores OAuth',
	'right-mwoauthsuppress' => 'Eliminar consumidores OAuth',
	'right-mwoauthviewsuppressed' => 'Ver os consumidores OAuth eliminados',
	'right-mwoauthviewprivate' => 'Ver os datos OAuth privados',
	'right-mwoauthmanagemygrants' => 'Administrar as concesións OAuth',
	'action-mwoauthmanageconsumer' => 'administrar os consumidores OAuth',
	'action-mwoauthmanagemygrants' => 'administrar as súas concesións OAuth',
	'action-mwoauthproposeconsumer' => 'propoñer novos consumidores OAuth',
	'action-mwoauthupdateownconsumer' => 'actualizar os consumidores OAuth que vostede controle',
	'action-mwoauthviewsuppressed' => 'ver os consumidores OAuth eliminados',
);

/** Hebrew (עברית)
 * @author אור שפירא
 */
$messages['he'] = array(
	'mwoauth-missing-field' => 'חסר ערך עבור שדה "$1"',
	'mwoauth-invalid-field' => 'ערך לא חוקי עבור שדה "$1"',
	'mwoauth-invalid-field-generic' => 'סופק ערך לא חוקי',
	'mwoauth-field-hidden' => '(מידע זה מוסתר)',
	'mwoauth-field-private' => '(מידע זה הוא פרטי)',
	'mwoauth-prefs-managegrants' => 'גישת צרכן OAuth:', # Fuzzy
	'mwoauth-consumer-allwikis' => 'כל הפרויקטים באתר זה',
	'mwoauth-consumer-key' => 'מפתח צרכן:',
	'mwoauth-consumer-name' => 'שם היישום:',
	'mwoauth-consumer-version' => 'גרסת צרכן:',
	'mwoauth-consumer-user' => 'מפרסם:',
	'mwoauth-consumer-stage' => 'מצב נוכחי:',
	'mwoauth-consumer-email' => 'כתובת דוא"ל נוכחית:',
	'mwoauth-consumer-restrictions' => 'מגבלות שימוש:',
	'mwoauth-consumer-restrictions-json' => 'מגבלות השימוש (JSON):',
	'mwoauth-consumer-rsakey' => 'המפתח הציבורי RSA:',
	'mwoauth-consumer-secretkey' => 'אסימון סודי של הצרכן:',
	'mwoauth-consumer-accesstoken' => 'אסימון גישה:',
	'mwoauth-consumer-reason' => 'סיבה:',
	'mwoauth-consumer-email-unconfirmed' => 'כתובת הדוא"ל עדיין לא אושרה.',
	'mwoauth-consumer-email-mismatched' => 'כתובת הדוא"ל המסופקת צריכה להתאים לכתובת בחשבון.',
	'mwoauth-consumer-alreadyexists' => 'צרכן עם שילוב שם/גרסה/מפרסם זה כבר קיים',
	'mwoauth-consumer-alreadyexistsversion' => 'צרכן עם שילוב שם/מפרסם זה כבר קיים בגרסה שווה או גבוהה ("$1")',
	'mwoauth-consumer-not-accepted' => 'אין אפשרות לעדכן מידע עבור בקשת צרכן ממתינה',
	'mwoauth-consumer-not-disabled' => 'הצרכן אינו מבוטל כרגע',
	'mwoauth-consumer-not-approved' => 'הצרכן לא מאושר (ייתכן שהוא מבוטל)',
	'mwoauth-invalid-consumer-key' => 'אין צרכן עם מפתח זה.',
	'mwoauth-invalid-access-token' => 'אין אסימון גישה עם המפתח.',
	'mwoauth-consumer-stage-proposed' => 'מוצע',
	'mwoauth-consumer-stage-rejected' => 'נדחה',
	'mwoauth-consumer-stage-expired' => 'פג תוקף',
	'mwoauth-consumer-stage-approved' => 'אושר',
	'mwoauth-consumer-stage-disabled' => 'בוטל',
	'mwoauthconsumerregistration' => 'רישום צרכן OAuth',
	'mwoauthconsumerregistration-notloggedin' => 'עליך להיות מחובר כדי לגשת לדף זה.',
	'mwoauthconsumerregistration-navigation' => 'ניווט:',
	'mwoauthconsumerregistration-propose' => 'הצעת צרכן חדש.',
	'mwoauthconsumerregistration-list' => 'רשימת הצרכנים שלי',
	'mwoauthconsumerregistration-main' => 'ראשי',
	'mwoauthconsumerregistration-update-text' => 'השתמש בטופס שלהלן כדי לעדכן את פרטי צרכן OAuth.

כל הערכים כאן יחליפו את כל הקודמים. אין להשאיר שדות ריקים אלא אם כן אתם מתכוונים לנקות את הערכים האלה.',
	'mwoauthconsumerregistration-update-submit' => 'עדכון צרכן',
	'mwoauthconsumerregistration-none' => 'אין שליטה על שום צרכן OAuth.',
	'mwoauthconsumerregistration-name' => 'צרכן',
	'mwoauthconsumerregistration-user' => 'מפרסם',
	'mwoauthconsumerregistration-description' => 'תיאור',
	'mwoauthconsumerregistration-email' => 'שלח דוא"ל',
	'mwoauthconsumerregistration-consumerkey' => 'מפתח צרכן',
	'mwoauthconsumerregistration-stage' => 'מצב',
	'mwoauthconsumerregistration-lastchange' => 'שינוי אחרון',
	'mwoauthconsumerregistration-manage' => 'ניהול',
	'mwoauthconsumerregistration-resetsecretkey' => 'אפס מפתח סודי לערך חדש',
	'mwoauthconsumerregistration-updated' => 'רישום צרכן OAuth שלך עודכן בהצלחה.',
	'mwoauthmanageconsumers' => 'ניהול צרכני OAuth',
	'mwoauthmanageconsumers-notloggedin' => 'עליך להיות מחובר כדי לגשת לדף זה.',
	'mwoauthmanageconsumers-type' => 'תורים:',
	'mwoauthmanageconsumers-showrejected' => 'בקשות שנדחו',
	'mwoauthmanageconsumers-showexpired' => 'בקשות שפג תוקפן',
	'mwoauthmanageconsumers-main' => 'ראשי',
	'mwoauthmanageconsumers-none-proposed' => 'אין צרכנים ברשימה זו.',
	'mwoauthmanageconsumers-none-rejected' => 'אין צרכנים ברשימה זו.',
	'mwoauthmanageconsumers-none-expired' => 'אין צרכנים ברשימה זו.',
	'mwoauthmanageconsumers-none-approved' => 'אין צרכנים בקטגוריה זו.',
	'mwoauthmanageconsumers-none-disabled' => 'אין צרכנים בקטגוריה זו.',
	'mwoauthmanageconsumers-name' => 'צרכן',
	'mwoauthmanageconsumers-user' => 'מפרסם',
	'mwoauthmanageconsumers-description' => 'תיאור',
	'mwoauthmanageconsumers-email' => 'שליחת דוא"ל',
	'mwoauthmanageconsumers-consumerkey' => 'מפתח צרכן',
	'mwoauthmanageconsumers-lastchange' => 'שינוי אחרון',
	'mwoauthmanageconsumers-confirm-text' => 'טופס זה מיועד לאשר, לבטל, או לאפשר צרכן זה.',
	'mwoauthmanageconsumers-confirm-legend' => 'ניהול צרכן OAuth',
	'mwoauthmanageconsumers-action' => 'שינוי מצב:',
	'mwoauthmanageconsumers-approve' => 'אושר',
	'mwoauthmanageconsumers-reject' => 'נדחה',
	'mwoauthmanageconsumers-disable' => 'בוטל',
	'mwoauthmanageconsumers-reenable' => 'אושר',
	'mwoauthmanageconsumers-reason' => 'סיבה:',
	'mwoauthmanageconsumers-confirm-submit' => 'עדכון מצב צרכן',
	'mwoauthmanageconsumers-viewing' => 'משתמש "$1" רואה כעת את הצרכן',
	'mwoauthmanageconsumers-success-approved' => 'הבקשה אושרה',
	'mwoauthmanageconsumers-success-rejected' => 'הבקשה נדחתה',
	'mwoauthmanageconsumers-success-disabled' => 'צרכן בוטל',
	'mwoauthmanageconsumers-success-reanable' => 'צרכן אופשר מחדש',
	'mwoauthmanagemygrants-notloggedin' => 'יש להיות מחובר כדי לגשת לדף זה.',
	'mwoauthmanagemygrants-navigation' => 'ניווט:',
	'mwoauthmanagemygrants-showlist' => 'רשימת צרכנים שהתקבלו',
	'mwoauthmanagemygrants-name' => 'שם צרכן',
	'mwoauthmanagemygrants-user' => 'מפרסם',
	'mwoauthmanagemygrants-description' => 'תיאור',
	'mwoauthmanagemygrants-consumerkey' => 'מפתח צרכן',
	'mwoauthmanagemygrants-review' => 'ניהול גישה',
	'mwoauthmanagemygrants-action' => 'שינוי מצב:',
	'mwoauthmanagemygrants-confirm-submit' => 'מצב עדכון אסימון גישה',
	'mwoauthmanagemygrants-success-update' => 'אסימון הגישה עבור צרכן זה עודכן.',
	'mwoauthmanagemygrants-success-renounce' => 'אסימון גישה עבור הצרכן זה נמחק.',
	'logentry-mwoauthconsumer-propose' => '$1 {{GENDER:$2|proposed}} צרכן OAuth (מפתח צרכן $4)',
	'logentry-mwoauthconsumer-update' => '$1 {{GENDER:$2|updated}} צרכן OAuth (מפתח צרכן $4)',
	'logentry-mwoauthconsumer-approve' => '$1 {{GENDER:$2|approved}} צרכןOAuth על ידי $3 (מפתח צרכן $4)',
	'logentry-mwoauthconsumer-reject' => '$1 {{GENDER:$2|rejected}} צרכן OAuth על ידי $3 (מפתח צרכן $4)',
	'logentry-mwoauthconsumer-disable' => '$1 {{GENDER:$2|disabled}} צרכן OAuth על ידי $3 (מפתח צרכן $4)',
	'logentry-mwoauthconsumer-reenable' => '$1 {{GENDER:$2|re-enabled}} צרכן OAuth על ידי $3 (מפתח צרכן $4)',
	'mwoauth-bad-request' => 'אירעה שגיאה בבקשת OAuth.',
	'mwoauthdatastore-request-token-not-found' => 'לא נמצאה בקשה לאסימון זה.',
	'mwoauthdatastore-bad-token' => 'לא נמצא אסימון מתאים לבקשתך.',
	'mwoauthdatastore-bad-verifier' => 'קוד האימות שסופק לא חוקי.',
	'mwoauthdatastore-invalid-token-type' => 'האסימון המבוקש אינו תקין.',
	'mwoauthgrants-general-error' => 'אירעה שגיאה בבקשת OAuth.',
	'mwoauthserver-bad-consumer' => 'לא נמצא צרכן מאושר עבור המפתח שסופק.',
	'mwoauthserver-insufficient-rights' => 'אין לך הרשאות לביצוע את פעולה זו.',
	'mwoauthserver-invalid-request-token' => 'אסימון לא חוקי בבקשתך.',
	'mwoauthserver-invalid-user-hookabort' => 'משתמש זה לא יכול להשתמש בOAuth.',
	'mwoauth-invalid-authorization-title' => 'שגיאת אימות OAuth',
	'mwoauth-form-description-allwikis' => 'שלום $1

"$2" מעוניין לבצע בשמך את הפעולות הבאות בכל הפרויקטים:

$4',
	'mwoauth-form-description-onewiki' => 'שלום $1

"$2" מעוניין לעשות בשמך את הפעולות הבאות ב"$4":

$5',
	'mwoauth-form-description-allwikis-nogrants' => 'שלום $1

"$2" מעוניין לקבל גישה בסיסית בשמך לכל המיזמים באתר זה.',
	'mwoauth-form-description-onewiki-nogrants' => 'שלום $1
"$2" מעוניין לקבל בשמך גישה בסיסית ב"$4".',
	'mwoauth-form-privacypolicy-link' => '[[{{ns:Project}}:Privacy policy|Privacy Policy]]',
	'mwoauth-form-button-approve' => 'לאפשר',
	'mwoauth-form-button-cancel' => 'ביטול',
	'mwoauth-authorize-form-invalid-user' => 'משתמש זה לא יכול להשתמש בOAuth בגלל שחשבונו בויקי זה וחשבונו בויקי OAuth המרכזי אינם מקושרים.',
	'mwoauth-error' => 'שגיאת OAuth',
	'mwoauth-grants-heading' => 'הרשאות מבוקשות:',
	'mwoauth-acceptance-cancelled' => 'ביטלת את הבקשה לאשר את הצרכן OAuth לפעול מטעמך.',
	'mwoauth-grant-group-page-interaction' => 'פעילות בדפים',
	'mwoauth-grant-group-file-interaction' => 'פעילות במדיה',
	'mwoauth-grant-group-watchlist-interaction' => 'פעילות ברשימת מעקב',
	'mwoauth-grant-group-email' => 'שליחת דוא"ל',
	'mwoauth-grant-group-customization' => 'התאמה אישית והעדפות',
	'mwoauth-grant-blockusers' => 'חסימה ושחרור משתמשים',
	'mwoauth-grant-createaccount' => 'יצירת חשבונות',
	'mwoauth-grant-createeditmovepage' => 'יצירה, עריכה והעברת דפים.',
	'mwoauth-grant-delete' => 'מחיקת דפים, גרסאות ויומנים',
	'mwoauth-grant-editinterface' => 'עריכת שם מדיה-ויקי ומשתמש CSS/JS',
	'mwoauth-grant-editmycssjs' => 'עריכת CSS/JS של המשתמש שלך',
	'mwoauth-grant-editmywatchlist' => 'עריכת רשימת המעקב שלך',
	'mwoauth-grant-editpage' => 'עריכת דפים קיימים',
	'mwoauth-grant-editprotected' => 'עריכת דפים מוגנים',
	'mwoauth-grant-protect' => 'הפעלת הגנה והסרת הגנה מדפים',
	'mwoauth-grant-sendemail' => 'שליחת דואר אלקטרוני למשתמשים אחרים',
	'mwoauth-grant-uploadeditmovefile' => 'העלאת קבצים, החלפת קבצים, והעברתם.',
	'mwoauth-grant-uploadfile' => 'העלאת קבצים חדשים',
	'mwoauth-grant-useoauth' => 'הרשאות בסיסיות',
	'mwoauth-grant-viewdeleted' => 'צפייה במידע שנמחק',
	'mwoauth-grant-viewmywatchlist' => 'צפייה ברשימת מעקב',
	'mwoauth-oauth-exception' => 'אירעה שגיאה בפרוטוקול OAuth:$1',
);

/** Interlingua (interlingua)
 * @author McDutchie
 */
$messages['ia'] = array(
	'mwoauth-desc' => 'Authentication via le API de OAuth 1.0a',
	'mwoauth-missing-field' => 'Manca un valor pro le campo "$1"',
	'mwoauth-invalid-field' => 'Valor non valide fornite pro le campo "$1"',
	'mwoauth-field-hidden' => '(iste information es celate)',
	'mwoauth-field-private' => '(iste information es private)',
	'mwoauth-grant-generic' => 'gruppo de derectos "$1"',
	'mwoauth-prefs-managegrants' => 'Accesso de consumitor OAuth:',
	'mwoauth-prefs-managegrantslink' => 'gerer concessiones in nomine de iste conto', # Fuzzy
	'mwoauth-consumer-key' => 'Clave de consumitor:',
	'mwoauth-consumer-name' => 'Nomine del application:',
	'mwoauth-consumer-version' => 'Version major:', # Fuzzy
	'mwoauth-consumer-user' => 'Editor:',
	'mwoauth-consumer-stage' => 'Stato actual:',
	'mwoauth-consumer-email' => 'Adresse de e-mail de contacto:',
	'mwoauth-consumer-description' => 'Description del application:',
	'mwoauth-consumer-callbackurl' => 'URL de retorno pro OAuth:',
	'mwoauth-consumer-grantsneeded' => 'Concessiones applicabile:',
	'mwoauth-consumer-required-grant' => 'Applicabile al consumitor',
	'mwoauth-consumer-wiki' => 'Wiki applicabile:',
	'mwoauth-consumer-restrictions' => 'Limitationes de uso:',
	'mwoauth-consumer-restrictions-json' => 'Limitationes de uso (JSON):',
	'mwoauth-consumer-rsakey' => 'Clave RSA public:',
	'mwoauth-consumer-secretkey' => 'Indicio secrete de consumitor:',
	'mwoauth-consumer-accesstoken' => 'Indicio de accesso:',
	'mwoauth-consumer-reason' => 'Motivo:',
	'mwoauth-consumer-alreadyexists' => 'Un consumitor con iste combination de nomine/version/editor jam existe.',
	'mwoauth-consumer-not-accepted' => 'Impossibile actualisar information pro un requesta de consumitor pendente.',
	'mwoauth-consumer-not-proposed' => 'Le consumitor non es proponite actualmente.',
	'mwoauth-consumer-not-disabled' => 'Le consumitor non es actualmente disactivate.',
	'mwoauth-consumer-not-approved' => 'Le consumitor non es approbate (illo pote haber essite disactivate)',
	'mwoauth-invalid-consumer-key' => 'Nulle consumitor existe con le calve fornite.',
	'mwoauth-invalid-access-token' => 'Nulle indicio de accesso existe con le clave fornite.',
	'mwoauth-consumer-conflict' => 'Alcuno ha cambiate le attributos de iste consumitor durante que tu lo visualisava. Per favor reproba. Pote esser un bon idea verificar le registro de cambiamentos.',
	'mwoauth-consumer-stage-proposed' => 'proponite',
	'mwoauth-consumer-stage-rejected' => 'rejectate',
	'mwoauth-consumer-stage-expired' => 'expirate',
	'mwoauth-consumer-stage-approved' => 'approbate',
	'mwoauth-consumer-stage-disabled' => 'disactivate',
	'mwoauth-consumer-stage-suppressed' => 'supprimite',
	'mwoauthconsumerregistration' => 'Registration de consumitores OAuth',
	'mwoauthconsumerregistration-navigation' => 'Navigation:',
	'mwoauthconsumerregistration-propose' => 'Proponer nove consumitor',
	'mwoauthconsumerregistration-list' => 'Mi lista de consumitores',
	'mwoauthconsumerregistration-main' => 'Principal',
	'mwoauthconsumerregistration-propose-text' => 'Usa le formulario hic infra pro proponer un nove consumitor OAuth (vide http://oauth.net).

Ecce alcun recommendationes e remarcas:
* Concede le minus derectos possibile. Evita concessiones que non es necessari in iste momento.
* Forni un clave RSA si possibile; alteremente un indicio secrete (e minus secur) te essera assignate.
* Usa le campo de restrictiones JSON pro limitar le accesso de iste consumitor al adresses IP in iste rangos CIDR.
* Tu pote usar un ID de wiki pro limitar le consumitor a un singule wiki in iste sito (usa "*" pro tote le wikis).
* Le adresse de e-mail fornite debe esser identic a illo de tu conto (le qual debe esser confirmate).', # Fuzzy
	'mwoauthconsumerregistration-update-text' => 'Le formulario sequente permitte actualisar aspectos de un consumitor OAuth que tu controla.

Tote le valores hic superscribera omne previe valores. Non lassa campos vacue si tu non ha le intention de rader iste valores.',
	'mwoauthconsumerregistration-maintext' => 'Iste pagina es pro proponer e actualisar applicationes de consumitor OAuth (vide http://oauth.net) in le base de registration de iste sito.

Ab hic, tu pote [[Special:MWOAuthConsumerRegistration/propose|proponer un nove consumitor]] o [[Special:MWOAuthConsumerRegistration/list|gerer tu consumitores existente]].', # Fuzzy
	'mwoauthconsumerregistration-propose-legend' => 'Nove application de consumitor OAuth',
	'mwoauthconsumerregistration-update-legend' => 'Actualisar application de consumitor OAuth',
	'mwoauthconsumerregistration-propose-submit' => 'Proponer consumitor',
	'mwoauthconsumerregistration-update-submit' => 'Actualisar consumitor',
	'mwoauthconsumerregistration-none' => 'Tu non controla alcun consumitor OAuth.',
	'mwoauthconsumerregistration-name' => 'Consumitor',
	'mwoauthconsumerregistration-user' => 'Editor',
	'mwoauthconsumerregistration-description' => 'Description',
	'mwoauthconsumerregistration-email' => 'E-mail de contacto',
	'mwoauthconsumerregistration-consumerkey' => 'Clave de consumitor',
	'mwoauthconsumerregistration-stage' => 'Stato',
	'mwoauthconsumerregistration-lastchange' => 'Ultime cambiamento',
	'mwoauthconsumerregistration-manage' => 'gerer',
	'mwoauthconsumerregistration-resetsecretkey' => 'Reinitialisar le clave secrete a un nove valor',
	'mwoauthconsumerregistration-proposed' => "Tu requesta de consumitor OAuth ha essite recipite.

Le systema te ha assignate un indicio de consumitor '''$1''' e un indicio secrete '''$2'''. ''Per favor conserva istes pro referentia futur.''",
	'mwoauthconsumerregistration-updated' => 'Le registration de consumitor OAuth ha essite actualisate con successo.',
	'mwoauthconsumerregistration-secretreset' => "Le systema te ha assignate un indicio secrete de consumitor '''$1'''. ''Per favor conserva lo pro referentia futur.''",
	'mwoauthmanageconsumers' => 'Gerer consumitores OAuth',
	'mwoauthmanageconsumers-type' => 'Caudas:',
	'mwoauthmanageconsumers-showproposed' => 'Requestas proponite',
	'mwoauthmanageconsumers-showrejected' => 'Requestas rejectate',
	'mwoauthmanageconsumers-showexpired' => 'Requestas expirate',
	'mwoauthmanageconsumers-main' => 'Principal',
	'mwoauthmanageconsumers-maintext' => 'Iste pagina es pro gerer requestas de application de consumitor OAuth (vide http://oauth.net) e pro gerer le consumitores OAuth establite.',
	'mwoauthmanageconsumers-queues' => 'Selige un cauda de confirmation de consumitor del lista sequente:',
	'mwoauthmanageconsumers-q-proposed' => 'Cauda de requestas de consumitor proponite',
	'mwoauthmanageconsumers-q-rejected' => 'Cauda de requestas de consumitor rejectate',
	'mwoauthmanageconsumers-q-expired' => 'Cauda de requestas de consumitor expirate',
	'mwoauthmanageconsumers-lists' => 'Selige un lista de stato de consumitor:',
	'mwoauthmanageconsumers-l-approved' => 'Lista de consumitores actualmente approbate',
	'mwoauthmanageconsumers-l-disabled' => 'Lista de consumitores actualmente disactivate',
	'mwoauthmanageconsumers-none-proposed' => 'Nulle consumitores proponite in iste lista.',
	'mwoauthmanageconsumers-none-rejected' => 'Nulle consumitores proponite in iste lista.',
	'mwoauthmanageconsumers-none-expired' => 'Nulle consumitores proponite in iste lista.',
	'mwoauthmanageconsumers-none-approved' => 'Nulle consumitor corresponde a iste criterios.',
	'mwoauthmanageconsumers-none-disabled' => 'Nulle consumitor corresponde a iste criterios.',
	'mwoauthmanageconsumers-name' => 'Consumitor',
	'mwoauthmanageconsumers-user' => 'Editor',
	'mwoauthmanageconsumers-description' => 'Description',
	'mwoauthmanageconsumers-email' => 'E-mail de contacto',
	'mwoauthmanageconsumers-consumerkey' => 'Clave de consumitor',
	'mwoauthmanageconsumers-lastchange' => 'Ultime cambiamento',
	'mwoauthmanageconsumers-review' => 'revider/gerer',
	'mwoauthmanageconsumers-confirm-text' => 'Usa iste formulario pro approbar, rejectar, disactivar o reactivar iste consumitor.',
	'mwoauthmanageconsumers-confirm-legend' => 'Gerer consumitor OAuth',
	'mwoauthmanageconsumers-action' => 'Cambiar stato:',
	'mwoauthmanageconsumers-approve' => 'Approbate',
	'mwoauthmanageconsumers-reject' => 'Rejectate',
	'mwoauthmanageconsumers-rsuppress' => 'Rejectate e supprimite',
	'mwoauthmanageconsumers-disable' => 'Disactivate',
	'mwoauthmanageconsumers-dsuppress' => 'Disactivate e supprimite',
	'mwoauthmanageconsumers-reenable' => 'Approbate',
	'mwoauthmanageconsumers-reason' => 'Motivo:',
	'mwoauthmanageconsumers-confirm-submit' => 'Actualisar stato de consumitor',
	'mwoauthmanageconsumers-viewing' => 'Le usator "$1" actualmente visualisa iste consumitor',
	'mwoauthmanageconsumers-success-approved' => 'Le requesta ha essite approbate.',
	'mwoauthmanageconsumers-success-rejected' => 'Le requesta ha essite rejectate.',
	'mwoauthmanageconsumers-success-disabled' => 'Le consumitor ha essite disactivate.',
	'mwoauthmanageconsumers-success-reanable' => 'Le consumitor ha essite reactivate.',
	'mwoauthmanagemygrants' => 'Gerer concessiones de conto OAuth',
	'mwoauthmanagemygrants-navigation' => 'Navigation:',
	'mwoauthmanagemygrants-showlist' => 'Lista de consumitores acceptate',
	'mwoauthmanagemygrants-none' => 'Nulle consumitor ha accesso in nomine de tu conto.',
	'mwoauthmanagemygrants-name' => 'Nomine del consumitor',
	'mwoauthmanagemygrants-user' => 'Editor',
	'mwoauthmanagemygrants-description' => 'Description',
	'mwoauthmanagemygrants-wiki' => 'Wiki applicabile',
	'mwoauthmanagemygrants-wikiallowed' => 'Permittite in wiki',
	'mwoauthmanagemygrants-grants' => 'Concessiones applicabile',
	'mwoauthmanagemygrants-grantsallowed' => 'Concessiones permittite',
	'mwoauthmanagemygrants-applicablegrantsallowed' => 'Concessiones applicabile permittite:',
	'mwoauthmanagemygrants-consumerkey' => 'Clave de consumitor',
	'mwoauthmanagemygrants-review' => 'revider/gerer accesso', # Fuzzy
	'mwoauthmanagemygrants-grantaccept' => 'Concedite al consumitor', # Fuzzy
	'mwoauthmanagemygrants-confirm-text' => 'Usa le formulario hic infra pro revocar le accesso o cambiar le concessiones de un consumitor OAuth pro ager in tu nomine.

Nota que si tu ha autorisate un consumitor a haber accesso solmente a un parte del wikis (projectos de sito), alora il habera plure indicios de accesso pro iste consumitor.', # Fuzzy
	'mwoauthmanagemygrants-confirm-legend' => 'Gerer indicio de accesso de consumitor',
	'mwoauthmanagemygrants-update' => 'Actualisar concessiones de indicio de accesso', # Fuzzy
	'mwoauthmanagemygrants-renounce' => 'Disautorisar e deler indicio de accesso', # Fuzzy
	'mwoauthmanagemygrants-action' => 'Cambiar stato:',
	'mwoauthmanagemygrants-confirm-submit' => 'Actualisar le stato de indicio de accesso',
	'mwoauthmanagemygrants-success-update' => 'Le indicio de accesso pro iste consumitor ha essite actualisate.',
	'mwoauthmanagemygrants-success-renounce' => 'Le indicio de accesso pro iste consumitor ha essite delite.',
	'logentry-mwoauthconsumer-propose' => '$1 proponeva un consumitor OAuth (clave de consumitor $4)', # Fuzzy
	'logentry-mwoauthconsumer-update' => '$1 actualisava un consumitor OAuth (clave de consumitor $4)', # Fuzzy
	'logentry-mwoauthconsumer-approve' => '$1 approbava un consumitor OAuth per $3 (clave de consumitor $4)', # Fuzzy
	'logentry-mwoauthconsumer-reject' => '$1 rejectava un consumitor OAuth per $3 (clave de consumitor $4)', # Fuzzy
	'logentry-mwoauthconsumer-disable' => '$1 disactivava un consumitor OAuth per $3 (clave de consumitor $4)',
	'logentry-mwoauthconsumer-reenable' => '$1 reactivava un consumitor OAuth per $3 (clave de consumitor $4)', # Fuzzy
	'mwoauthconsumer-consumer-logpage' => 'Registro de consumitores OAuth',
	'mwoauthconsumer-consumer-logpagetext' => 'Registro de approbation, rejection e disactivation de consumitores OAuth registrate.',
	'mwoauth-bad-csrf-token' => 'Fallimento de session durante le submission del formulario. Per favor retenta tu submissiones.',
	'mwoauth-bad-request' => 'Il habeva un error in le requesta OAuth.',
	'mwoauthdatastore-access-token-not-found' => 'Nulle concession approbate ha essite trovate pro iste indicio de autorisation.',
	'mwoauthdatastore-request-token-not-found' => 'Nulle requesta ha essite trovate pro iste indicio.',
	'mwoauthdatastore-bad-token' => 'Nulle indicio ha essite trovate que corresponde a tu requesta.',
	'mwoauthdatastore-bad-verifier' => 'Le codice de verification fornite non es valide.',
	'mwoauthdatastore-invalid-token-type' => 'Le typo de indicio requestate non es valide.',
	'mwoauthgrants-general-error' => 'Il habeva un error in tu requesta OAuth.',
	'mwoauthserver-bad-consumer' => 'Nulle consumitor approbate ha essite trovate pro le clave fornite.',
	'mwoauthserver-insufficient-rights' => 'Tu non ha derectos sufficiente pro exequer iste action.',
	'mwoauthserver-invalid-request-token' => 'Il ha un indicio non valide in tu requesta.',
	'mwoauthserver-invalid-user-hookabort' => 'Iste usator non pote usar OAuth.',
	'mwoauth-invalid-authorization-title' => 'Error de autorisation OAuth',
	'mwoauth-invalid-authorization' => 'Le capites de autorisation in tu requesta non es valide: $1',
	'mwoauth-invalid-authorization-wrong-wiki' => 'Le capites de autorisation in tu requesta non es valide pro $1',
	'mwoauth-invalid-authorization-invalid-user' => 'Le capites de autorisation in tu requesta es pro un usator que non existe hic',
	'mwoauth-invalid-authorization-wrong-user' => 'Le capites de autorisation in tu requesta es pro un altere usator',
	'mwoauth-invalid-authorization-not-approved' => 'Le capites de autorisation in tu requesta es pro un consumitor OAuth que non es actualmente approbate',
	'mwoauth-form-button-approve' => 'Si, autorisar', # Fuzzy
	'mwoauth-grants-heading' => 'Permissiones requestate:',
	'mwoauth-grants-nogrants' => 'Le application non ha requestate alcun permission.',
	'mwoauth-grant-blockusers' => 'Blocar usatores', # Fuzzy
	'mwoauth-grant-createeditmovepage' => 'Crear, modificar e renominar paginas',
	'mwoauth-grant-delete' => 'Deler paginas, versiones e entratas de registro',
	'mwoauth-grant-editinterface' => 'Modificar le spatio de nomines MediaWiki e le CSS/JS de usatores',
	'mwoauth-grant-editmycssjs' => 'Modificar le CSS/JS del proprie usator',
	'mwoauth-grant-editmywatchlist' => 'Modificar le proprie observatorio',
	'mwoauth-grant-editpage' => 'Modificar paginas existente',
	'mwoauth-grant-editprotected' => 'Modificar paginas protegite',
	'mwoauth-grant-highvolume' => 'Modification in massa',
	'mwoauth-grant-oversight' => 'Celar usatores e supprimer versiones',
	'mwoauth-grant-patrol' => 'Patruliar', # Fuzzy
	'mwoauth-grant-protect' => 'Proteger e disproteger paginas',
	'mwoauth-grant-rollback' => 'Revocar', # Fuzzy
	'mwoauth-grant-sendemail' => 'Inviar e-mail', # Fuzzy
	'mwoauth-grant-uploadeditmovefile' => 'Actualisar, reimplaciar e renominar files',
	'mwoauth-grant-uploadfile' => 'Incargar nove files',
	'mwoauth-grant-useoauth' => 'Derectos de base',
	'mwoauth-grant-viewdeleted' => 'Vider information delite',
	'mwoauth-grant-viewmywatchlist' => 'Vider le proprie observatorio',
	'mwoauth-callback-not-oob' => 'oauth_callback debe esser definite, e debe esser mittite a "oob" (distingue inter majusculas e minusculas)',
	'right-mwoauthproposeconsumer' => 'Proponer nove consumitores OAuth',
	'right-mwoauthupdateownconsumer' => 'Actualisar consumitores OAuth',
	'right-mwoauthmanageconsumer' => 'Gerer consumitores OAuth',
	'right-mwoauthsuppress' => 'Supprimer consumitores OAuth',
	'right-mwoauthviewsuppressed' => 'Vider consumitores OAuth supprimite',
	'right-mwoauthviewprivate' => 'Vider datos OAuth private',
	'right-mwoauthmanagemygrants' => 'Gerer concessiones OAuth',
	'action-mwoauthmanageconsumer' => 'gerer consumitores OAuth',
	'action-mwoauthmanagemygrants' => 'gerer le proprie concessiones OAuth',
	'action-mwoauthproposeconsumer' => 'proponer nove consumitores OAUth',
	'action-mwoauthupdateownconsumer' => 'actualisar consumitores OAuth',
	'action-mwoauthviewsuppressed' => 'vider consumitores OAUth supprimite',
);

/** Italian (italiano)
 * @author Beta16
 */
$messages['it'] = array(
	'mwoauth-desc' => 'API autorizzazioni OAuth 1.0a',
	'mwoauth-verified' => "Ora è consentito all'applicazione di accedere a MediaWiki per tuo conto.

Per completare il processo, inserisci questo valore per la verifica nel'applicazione: '''$1'''",
	'mwoauth-missing-field' => 'Valore mancante per il campo "$1".',
	'mwoauth-invalid-field' => 'Valore non valido per il campo "$1".',
	'mwoauth-invalid-field-generic' => 'Valore indicato non valido',
	'mwoauth-field-hidden' => '(questa informazione è nascosta)',
	'mwoauth-field-private' => '(questa informazione è privata)',
	'mwoauth-grant-generic' => 'Pacchetto diritti "$1"',
	'mwoauth-prefs-managegrants' => 'Applicazioni collegate:',
	'mwoauth-prefs-managegrantslink' => 'Gestisci $1 {{PLURAL:$1|applicazione collegata|applicazioni collegate}}',
	'mwoauth-consumer-allwikis' => 'Tutti i progetti su questo sito',
	'mwoauth-consumer-key' => 'Chiave cliente:',
	'mwoauth-consumer-name' => 'Nome applicazione:',
	'mwoauth-consumer-version' => 'Versione cliente:',
	'mwoauth-consumer-user' => 'Editore:',
	'mwoauth-consumer-stage' => 'Stato attuale:',
	'mwoauth-consumer-email' => 'Indirizzo email di contatto:',
	'mwoauth-consumer-description' => 'Descrizione applicazione:',
	'mwoauth-consumer-callbackurl' => 'URL di "callback" OAuth:',
	'mwoauth-consumer-grantsneeded' => 'Assegnazioni applicabili:',
	'mwoauth-consumer-required-grant' => 'Applicabile al cliente',
	'mwoauth-consumer-wiki' => 'Wiki applicabili:',
	'mwoauth-consumer-wiki-thiswiki' => 'Wiki attuale ($1)',
	'mwoauth-consumer-wiki-other' => 'Wiki specifico',
	'mwoauth-consumer-restrictions' => "Restrizioni d'uso:",
	'mwoauth-consumer-restrictions-json' => "Restrizioni d'uso (JSON):",
	'mwoauth-consumer-rsakey' => 'Chiave RSA pubblica:',
	'mwoauth-consumer-secretkey' => 'Token segreto cliente:',
	'mwoauth-consumer-accesstoken' => 'Token di accesso:',
	'mwoauth-consumer-reason' => 'Motivo:',
	'mwoauth-consumer-email-unconfirmed' => 'Il tuo indirizzo email non è stato ancora confermato.',
	'mwoauth-consumer-email-mismatched' => "L'indirizzo email fornito deve corrispondere a quello della tua utenza.",
	'mwoauth-consumer-alreadyexists' => 'Un cliente con questa combinazione di nome/versione/editore esiste già',
	'mwoauth-consumer-alreadyexistsversion' => 'Un cliente con questa combinazione di nome/editore esiste già con una versione uguale o superiore ("$1")',
	'mwoauth-consumer-not-accepted' => 'Non è possibile aggiornare le informazioni per una richiesta in sospeso',
	'mwoauth-consumer-not-proposed' => 'Il cliente non è attualmente proposto',
	'mwoauth-consumer-not-disabled' => 'Il cliente non è attualmente disabilitato',
	'mwoauth-consumer-not-approved' => 'Il cliente non è approvato (potrebbe essere stato disabilitato)',
	'mwoauth-missing-consumer-key' => 'Non è stata fornita alcuna chiave cliente.',
	'mwoauth-invalid-consumer-key' => 'Non esiste alcun cliente con la chiave specificata.',
	'mwoauth-invalid-access-token' => 'Non esiste alcun token di accesso con la chiave specificata.',
	'mwoauth-invalid-access-wrongwiki' => 'Il cliente può essere utilizzato solo nel wiki "$1".',
	'mwoauth-consumer-conflict' => 'Qualcuno ha cambiato gli attributi di questo cliente, come si visto. Per favore riprova. Si consiglia di controllare il registro delle modifiche.',
	'mwoauth-consumer-stage-proposed' => 'proposto',
	'mwoauth-consumer-stage-rejected' => 'respinto',
	'mwoauth-consumer-stage-expired' => 'scaduto',
	'mwoauth-consumer-stage-approved' => 'approvato',
	'mwoauth-consumer-stage-disabled' => 'disabilitato',
	'mwoauth-consumer-stage-suppressed' => 'soppresso',
	'mwoauthconsumerregistration' => 'Registrazione cliente OAuth',
	'mwoauthconsumerregistration-notloggedin' => "Devi effettuare l'accesso per accedere a questa pagina.",
	'mwoauthconsumerregistration-navigation' => 'Navigazione:',
	'mwoauthconsumerregistration-propose' => 'Proponi nuovo cliente',
	'mwoauthconsumerregistration-list' => 'Miei clienti',
	'mwoauthconsumerregistration-main' => 'Principale',
	'mwoauthconsumerregistration-propose-text' => "Gli sviluppatori dovrebbero usare il seguente modulo per proporre un nuovo cliente OAuth (vedi la [//www.mediawiki.org/wiki/Extension:OAuth documentazione dell'estensione] per ulteriori dettagli). Dopo l'invio di questo modulo, riceverai un token che l'applicazione utilizzerà per identificarsi in MediaWiki. Un amministratore di OAuth dovrà approvare l'applicazione prima che questa potrà essere autorizzata da altri utenti.

Alcune raccomandazioni e osservazioni:
* cerca di utilizzare meno assegnazioni possibili. Cerca di evitare di assegnare diritti che non sono realmente necessari ora
* le versioni sono nella forma \"major.minor.release\" (gli ultimi due sono opzionali) ed aumentala nel caso siano necessarie ulteriori assegnazioni di diritti
* fornisce una chiave RSA pubblica (in formato PEM) se possibile; altrimenti dovrà essere utilizzato un token segreto (meno sicuro)
* utilizza il campo di restrizioni JSON per limitare l'accesso di questo cliente da indirizzi IP in tali intervalli CIDR
* è possibile utilizzare un ID wiki per limitare il cliente ad un singolo wiki su questo sito (usa \"*\" per tutti gli wiki)
* l'indirizzo email fornito deve corrispondere a quello della tua utenza (che deve essere confermato).",
	'mwoauthconsumerregistration-update-text' => 'Utilizza il modulo qui sotto per aggiornare gli aspetti di un cliente OAuth che controlli.

I valori qui sovrascriveranno tutti quelli precedenti. Non lasciarli in bianco se non hai intenzione di cancellare quei valori.',
	'mwoauthconsumerregistration-maintext' => "Questa pagina è per consentire agli sviluppatori di proporre e l'aggiornare le applicazioni OAuth registrate in questo sito.

Da qui, è possibile:
* [[Special:MWOAuthConsumerRegistration/propose|richiedere un token per un nuovo cliente]]
* [[Special:MWOAuthConsumerRegistration/list|gestire i tuoi clienti esistenti]].

Per ulteriori informazioni su OAuth, vedi la [//www.mediawiki.org/wiki/Extension:OAuth documentazione dell'estensione].",
	'mwoauthconsumerregistration-propose-legend' => 'Nuova applicazione cliente OAuth',
	'mwoauthconsumerregistration-update-legend' => 'Aggiorna applicazione cliente OAuth',
	'mwoauthconsumerregistration-propose-submit' => 'Proponi cliente',
	'mwoauthconsumerregistration-update-submit' => 'Aggiorna cliente',
	'mwoauthconsumerregistration-none' => 'Non controlli alcun cliente OAuth.',
	'mwoauthconsumerregistration-name' => 'Cliente',
	'mwoauthconsumerregistration-user' => 'Editore',
	'mwoauthconsumerregistration-description' => 'Descrizione',
	'mwoauthconsumerregistration-email' => 'Email di contatto',
	'mwoauthconsumerregistration-consumerkey' => 'Chiave cliente',
	'mwoauthconsumerregistration-stage' => 'Stato',
	'mwoauthconsumerregistration-lastchange' => 'Ultima modifica',
	'mwoauthconsumerregistration-manage' => 'gestisci',
	'mwoauthconsumerregistration-resetsecretkey' => 'Reimposta la chiave segreta ad un nuovo valore',
	'mwoauthconsumerregistration-proposed' => "La tua richiesta per il cliente OAuth è stata ricevuta.

Ti è stato assegnato il token cliente '''$1''' e il token segreto '''$2'''. ''Registra questi dati per riferimenti futuri.''",
	'mwoauthconsumerregistration-updated' => 'La registrazione del tuo cliente OAuth è stata aggiornata correttamente.',
	'mwoauthconsumerregistration-secretreset' => "Ti è stato assegnato il token segreto '''$1'''. ''Registra questi dati per riferimenti futuri.''",
	'mwoauthmanageconsumers' => 'Gestione clienti OAuth',
	'mwoauthmanageconsumers-notloggedin' => "Devi effettuare l'accesso per accedere a questa pagina.",
	'mwoauthmanageconsumers-type' => 'Code:',
	'mwoauthmanageconsumers-showproposed' => 'Richieste proposte',
	'mwoauthmanageconsumers-showrejected' => 'Richieste respinte',
	'mwoauthmanageconsumers-showexpired' => 'Richieste scadute',
	'mwoauthmanageconsumers-main' => 'Principale',
	'mwoauthmanageconsumers-maintext' => 'Questa pagina è usata per la gestione delle applicazioni OAuth (vedi http://oauth.net), delle richieste e dei clienti istituiti.',
	'mwoauthmanageconsumers-queues' => 'Seleziona una coda di conferma dei clienti da sotto:',
	'mwoauthmanageconsumers-q-proposed' => 'Coda delle richieste proposte',
	'mwoauthmanageconsumers-q-rejected' => 'Coda delle richieste respinte',
	'mwoauthmanageconsumers-q-expired' => 'Coda delle richieste scadute',
	'mwoauthmanageconsumers-lists' => 'Seleziona uno stato del cliente da sotto:',
	'mwoauthmanageconsumers-l-approved' => 'Elenco dei clienti attualmente approvati',
	'mwoauthmanageconsumers-l-disabled' => 'Elenco dei clienti attualmente disabilitati',
	'mwoauthmanageconsumers-none-proposed' => 'Nessun cliente in questa lista.',
	'mwoauthmanageconsumers-none-rejected' => 'Nessun cliente in questa lista.',
	'mwoauthmanageconsumers-none-expired' => 'Nessun cliente in questa lista.',
	'mwoauthmanageconsumers-none-approved' => 'Nessun cliente soddisfa questo criterio.',
	'mwoauthmanageconsumers-none-disabled' => 'Nessun cliente soddisfa questo criterio.',
	'mwoauthmanageconsumers-name' => 'Cliente',
	'mwoauthmanageconsumers-user' => 'Editore',
	'mwoauthmanageconsumers-description' => 'Descrizione',
	'mwoauthmanageconsumers-email' => 'Email di contatto',
	'mwoauthmanageconsumers-consumerkey' => 'Chiave cliente',
	'mwoauthmanageconsumers-lastchange' => 'Ultima modifica',
	'mwoauthmanageconsumers-review' => 'rivedi/gestisci',
	'mwoauthmanageconsumers-confirm-text' => 'Usa questo modulo per approvare, respingere, disabilitare o riabilitare questo cliente.',
	'mwoauthmanageconsumers-confirm-legend' => 'Gestione cliente OAuth',
	'mwoauthmanageconsumers-action' => 'Modifica stato:',
	'mwoauthmanageconsumers-approve' => 'Approvato',
	'mwoauthmanageconsumers-reject' => 'Respinto',
	'mwoauthmanageconsumers-rsuppress' => 'Respinto e soppresso',
	'mwoauthmanageconsumers-disable' => 'Disabilitato',
	'mwoauthmanageconsumers-dsuppress' => 'Disabilitato e soppresso',
	'mwoauthmanageconsumers-reenable' => 'Approvato',
	'mwoauthmanageconsumers-reason' => 'Motivo:',
	'mwoauthmanageconsumers-confirm-submit' => 'Aggiorna stato cliente',
	'mwoauthmanageconsumers-viewing' => 'L\'utente "$1" sta attualmente vedendo questo cliente',
	'mwoauthmanageconsumers-success-approved' => 'La richiesta è stata approvata.',
	'mwoauthmanageconsumers-success-rejected' => 'La richiesta è stata respinta.',
	'mwoauthmanageconsumers-success-disabled' => 'Il cliente è stato disabilitato.',
	'mwoauthmanageconsumers-success-reanable' => 'Il cliente è stato riabilitato.',
	'mwoauthmanageconsumers-search-name' => 'clienti con questo nome',
	'mwoauthmanageconsumers-search-publisher' => 'clienti di questo utente',
	'mwoauthlistconsumers' => 'Elenco clienti OAuth',
	'mwoauthlistconsumers-legend' => 'Naviga clienti OAuth',
	'mwoauthlistconsumers-view' => 'dettagli',
	'mwoauthlistconsumers-none' => 'Nessun cliente trovato che soddisfa questo criterio.',
	'mwoauthlistconsumers-name' => 'Nome applicazione',
	'mwoauthlistconsumers-version' => 'Versione cliente',
	'mwoauthlistconsumers-user' => 'Editore',
	'mwoauthlistconsumers-description' => 'Descrizione',
	'mwoauthlistconsumers-wiki' => 'Wiki applicabili',
	'mwoauthlistconsumers-callbackurl' => 'URL di "callback" OAuth',
	'mwoauthlistconsumers-grants' => 'Assegnazioni applicabili',
	'mwoauthlistconsumers-basicgrantsonly' => '(solo accesso di base)',
	'mwoauthlistconsumers-status' => 'Stato',
	'mwoauth-consumer-stage-any' => 'qualsiasi',
	'mwoauthlistconsumers-status-proposed' => 'proposto',
	'mwoauthlistconsumers-status-approved' => 'approvato',
	'mwoauthlistconsumers-status-disabled' => 'disabilitato',
	'mwoauthlistconsumers-status-rejected' => 'respinto',
	'mwoauthlistconsumers-status-expired' => 'scaduto',
	'mwoauthmanagemygrants' => 'Gestione assegnazioni utenze OAuth',
	'mwoauthmanagemygrants-text' => "Questa pagina elenca tutte le applicazioni che possono utilizzare la tua utenza. Per tali applicazioni, l'ambito del loro accesso è limitata dalle autorizzazioni concesse all'applicazione quando è stata autorizzata ad agire per vostro conto. Se autorizzi separatamente un cliente all'accesso per vostro conto su diversi progetti \"fratelli\", poi vedrai configurazioni separate per ognuno dei progetti sotto.",
	'mwoauthmanagemygrants-notloggedin' => "Devi effettuare l'accesso per accedere a questa pagina.",
	'mwoauthmanagemygrants-navigation' => 'Navigazione:',
	'mwoauthmanagemygrants-showlist' => 'Elenco clienti accettati',
	'mwoauthmanagemygrants-none' => 'Nessuna applicazione è attualmente collegata alla tua utenza.',
	'mwoauthmanagemygrants-name' => 'Nome cliente',
	'mwoauthmanagemygrants-user' => 'Editore',
	'mwoauthmanagemygrants-description' => 'Descrizione',
	'mwoauthmanagemygrants-wiki' => 'Wiki applicabili',
	'mwoauthmanagemygrants-wikiallowed' => 'Consentito su wiki',
	'mwoauthmanagemygrants-grants' => 'Assegnazioni applicabili',
	'mwoauthmanagemygrants-grantsallowed' => 'Diritti consentiti',
	'mwoauthmanagemygrants-applicablegrantsallowed' => 'Assegnazioni applicabili consentite:',
	'mwoauthmanagemygrants-consumerkey' => 'Chiave cliente',
	'mwoauthmanagemygrants-review' => 'gestisci accesso',
	'mwoauthmanagemygrants-revoke' => "revoca l'accesso",
	'mwoauthmanagemygrants-grantaccept' => 'Assegnazioni',
	'mwoauthmanagemygrants-confirm-legend' => 'Gestione token di accesso del cliente',
	'mwoauthmanagemygrants-update' => 'Aggiorna le assegnazioni',
	'mwoauthmanagemygrants-renounce' => "Rimuovi l'autorizzazione",
	'mwoauthmanagemygrants-action' => 'Modifica stato:',
	'mwoauthmanagemygrants-confirm-submit' => 'Aggiorna lo stato del token di accesso',
	'mwoauthmanagemygrants-success-update' => 'Il token di accesso per questo cliente è stato aggiornato.',
	'mwoauthmanagemygrants-success-renounce' => 'Il token di accesso per questo cliente è stato cancellato.',
	'logentry-mwoauthconsumer-propose' => '$1 {{GENDER:$2|ha proposto}} un cliente OAuth (chiave cliente $4)',
	'logentry-mwoauthconsumer-update' => '$1 {{GENDER:$2|ha aggiornato}} un cliente OAuth (chiave cliente $4)',
	'logentry-mwoauthconsumer-approve' => '$1 {{GENDER:$2|ha approvato}} un cliente OAuth di $3 (chiave cliente $4)',
	'logentry-mwoauthconsumer-reject' => '$1 {{GENDER:$2|ha respinto}} un cliente OAuth di $3 (chiave cliente $4)',
	'logentry-mwoauthconsumer-disable' => '$1 {{GENDER:$2|ha disabilitato}} un cliente OAuth di $3 (chiave cliente $4)',
	'logentry-mwoauthconsumer-reenable' => '$1 {{GENDER:$2|ha riabilitato}} un cliente OAuth di $3 (chiave cliente $4)',
	'mwoauthconsumer-consumer-logpage' => 'Clienti OAuth',
	'mwoauthconsumer-consumer-logpagetext' => 'Registro dei clienti OAuth approvati, respinti o disabilitati.',
	'mwoauth-bad-request' => "C'è un errore nella tua richiesta OAuth.",
	'mwoauthdatastore-access-token-not-found' => 'Non è stata trovata alcuna assegnazione approvata per il token di autorizzazione.',
	'mwoauthdatastore-request-token-not-found' => 'Non è stata trovata alcuna richiesta per il token.',
	'mwoauthdatastore-bad-token' => 'Non è stato trovato alcun token che corrisponde alla tua richiesta.',
	'mwoauthdatastore-bad-verifier' => 'Il codice di verifica fornito non è valido.',
	'mwoauthdatastore-invalid-token-type' => 'Il tipo di token richiesto non è valido.',
	'mwoauthgrants-general-error' => "C'è un errore nella tua richiesta OAuth.",
	'mwoauthserver-bad-consumer' => 'Non è stato trovato alcun cliente autorizzato per la chiave fornita.',
	'mwoauthserver-insufficient-rights' => 'Non hai i diritti sufficienti per eseguire questa azione.',
	'mwoauthserver-invalid-request-token' => 'Token non valido nella tua richiesta.',
	'mwoauthserver-invalid-user-hookabort' => 'Questo utente non può utilizzare OAuth.',
	'mwoauth-invalid-authorization-title' => 'Errore autorizzazione OAuth',
	'mwoauth-invalid-authorization' => "L'intestazione dell'autorizzazione nella tua richiesta non è valida: $1",
	'mwoauth-invalid-authorization-wrong-wiki' => "L'intestazione dell'autorizzazione nella tua richiesta non è valida per $1",
	'mwoauth-invalid-authorization-invalid-user' => "L'intestazione dell'autorizzazione nella tua richiesta si riferisce ad un utente che non esiste qui",
	'mwoauth-invalid-authorization-wrong-user' => "L'intestazione dell'autorizzazione nella tua richiesta si riferisce ad un altro utente",
	'mwoauth-invalid-authorization-not-approved' => "L'intestazione dell'autorizzazione nella tua richiesta è per un cliente OAuth che non è attualmente approvato",
	'mwoauth-invalid-authorization-blocked-user' => "L'intestazione dell'autorizzazione nella tua richiesta si riferisce ad un utente che è bloccato",
	'mwoauth-form-description-allwikis' => "Ciao $1,

'''$2''' vorrebbe eseguire le seguenti azioni per tuo conto su tutti i progetti di questo sito:

$4",
	'mwoauth-form-description-onewiki' => "Ciao $1,

'''$2''' vorrebbe eseguire le seguenti azioni per tuo conto su ''$4'':


$5",
	'mwoauth-form-description-allwikis-nogrants' => "Ciao $1,

'''$2''' vorrebbe avere l'accesso di base per tuo conto su tutti i progetti di questo sito.",
	'mwoauth-form-description-onewiki-nogrants' => "Ciao $1,

'''$2''' vorrebbe avere l'accesso di base per tuo conto su ''$4''.",
	'mwoauth-form-privacypolicy-link' => '[[{{ns:Project}}:Privacy policy|Politica relativa alla privacy]]',
	'mwoauth-form-button-approve' => 'Consenti',
	'mwoauth-form-button-cancel' => 'Annulla',
	'mwoauth-authorize-form-invalid-user' => "Questo account non può usare OAuth, perché l'utenza su questo wiki e quella sul wiki OAuth centrale non sono collegate.",
	'mwoauth-error' => 'Errore OAuth',
	'mwoauth-grants-heading' => 'Autorizzazioni richieste:',
	'mwoauth-grants-nogrants' => "L'applicazione non ha richiesto alcuna autorizzazione.",
	'mwoauth-acceptance-cancelled' => 'Hai annullato la richiesta di autorizzazione per il cliente OAuth di agire per tuo conto.',
	'mwoauth-grant-group-page-interaction' => 'Interagisce con le pagine',
	'mwoauth-grant-group-file-interaction' => 'Interagisce con i file multimediali',
	'mwoauth-grant-group-watchlist-interaction' => 'Interagisce con i tuoi osservati speciali',
	'mwoauth-grant-group-email' => 'Invia email',
	'mwoauth-grant-group-high-volume' => 'Esegue azioni massive',
	'mwoauth-grant-group-customization' => 'Personalizzazione e preferenze',
	'mwoauth-grant-group-administration' => 'Esegue azioni adminstrative',
	'mwoauth-grant-group-other' => 'Attività varie',
	'mwoauth-grant-blockusers' => 'Blocca e sblocca utenti',
	'mwoauth-grant-createaccount' => "Crea un'utenza",
	'mwoauth-grant-createeditmovepage' => 'Crea, modifica e sposta le pagine',
	'mwoauth-grant-delete' => 'Cancella pagine, versioni, e voci di registro',
	'mwoauth-grant-editinterface' => 'Modifica il namespace MediaWiki e i file CSS/JS di altri utenti',
	'mwoauth-grant-editmycssjs' => 'Modifica i file CSS/JS del proprio utente',
	'mwoauth-grant-editmywatchlist' => 'Modifica i tuoi osservati speciali',
	'mwoauth-grant-editpage' => 'Modifica pagine esistenti',
	'mwoauth-grant-editprotected' => 'Modifica pagine protette',
	'mwoauth-grant-highvolume' => 'Modifiche massive',
	'mwoauth-grant-oversight' => 'Nasconde utenti e sopprime le versioni',
	'mwoauth-grant-patrol' => 'Segna le modifiche alle pagine come verificate',
	'mwoauth-grant-protect' => 'Protegge e sprotegge pagine',
	'mwoauth-grant-rollback' => 'Rollback delle modifiche alle pagine',
	'mwoauth-grant-sendemail' => 'Invia email ad altri utenti',
	'mwoauth-grant-uploadeditmovefile' => 'Carica, sostituisce e sposta i file',
	'mwoauth-grant-uploadfile' => 'Carica nuovi file',
	'mwoauth-grant-useoauth' => 'Diritti di base',
	'mwoauth-grant-viewdeleted' => 'Vede le informazioni cancellate',
	'mwoauth-grant-viewmywatchlist' => 'Vedi i tuoi osservati speciali',
	'mwoauth-oauth-exception' => 'Si è verificato un errore nel protocollo OAuth: $1',
	'mwoauth-callback-not-oob' => 'oauth_callback deve essere impostato a "oob" (in minuscolo)',
	'right-mwoauthproposeconsumer' => 'Propone nuovi clienti OAuth',
	'right-mwoauthupdateownconsumer' => 'Aggiorna clienti OAuth che controlla',
	'right-mwoauthmanageconsumer' => 'Gestisce clienti OAuth',
	'right-mwoauthsuppress' => 'Sopprime clienti OAuth',
	'right-mwoauthviewsuppressed' => 'Visualizza clienti OAuth soppressi',
	'right-mwoauthviewprivate' => 'Visualizza dati privati OAuth',
	'right-mwoauthmanagemygrants' => 'Gestisce assegnazioni OAuth',
	'action-mwoauthmanageconsumer' => 'gestire clienti OAuth',
	'action-mwoauthmanagemygrants' => 'gestire le tue assegnazioni OAuth',
	'action-mwoauthproposeconsumer' => 'proporre nuovi clienti OAuth',
	'action-mwoauthupdateownconsumer' => 'aggiornare clienti OAuth che controlli',
	'action-mwoauthviewsuppressed' => 'visualizzare clienti OAuth soppressi',
);

/** Japanese (日本語)
 * @author Shirayuki
 */
$messages['ja'] = array(
	'mwoauth' => 'OAuth',
	'mwoauth-desc' => 'OAuth 1.0a API 認証',
	'mwoauth-missing-field' => '「$1」フィールドの値がありません',
	'mwoauth-invalid-field' => '「$1」フィールドに指定した値は無効です',
	'mwoauth-invalid-field-generic' => '指定した値は無効です',
	'mwoauth-field-hidden' => '(この情報は非表示です)',
	'mwoauth-field-private' => '(この情報は非公開です)',
	'mwoauth-prefs-managegrants' => '接続済みのアプリ:',
	'mwoauth-prefs-managegrantslink' => '接続済みの $1 {{PLURAL:$1|アプリケーション}}を管理',
	'mwoauth-consumer-allwikis' => 'このサイト上のすべてのプロジェクト',
	'mwoauth-consumer-key' => 'コンシューマー キー:',
	'mwoauth-consumer-name' => 'アプリケーション名:',
	'mwoauth-consumer-version' => 'コンシューマーのバージョン:',
	'mwoauth-consumer-user' => '発行者:',
	'mwoauth-consumer-stage' => '現在の状態:',
	'mwoauth-consumer-email' => '連絡先メールアドレス:',
	'mwoauth-consumer-description' => 'アプリケーションの説明:',
	'mwoauth-consumer-callbackurl' => 'OAuth コールバック URL:',
	'mwoauth-consumer-wiki-thiswiki' => '現在のウィキ ($1)',
	'mwoauth-consumer-wiki-other' => '特定のウィキ',
	'mwoauth-consumer-restrictions' => '使用制限:',
	'mwoauth-consumer-restrictions-json' => '使用制限 (JSON):',
	'mwoauth-consumer-rsakey' => '公開 RSA キー:',
	'mwoauth-consumer-secretkey' => 'コンシューマー秘密トークン:',
	'mwoauth-consumer-accesstoken' => 'アクセス トークン:',
	'mwoauth-consumer-reason' => '理由:',
	'mwoauth-consumer-email-unconfirmed' => 'アカウントのメールアドレスがまだ確認されていません。',
	'mwoauth-consumer-email-mismatched' => '指定したメールアドレスは、アカウントのものと一致しません。',
	'mwoauth-consumer-alreadyexists' => 'この名前/バージョン/発行者の組み合わせを持つコンシューマーは既に存在します',
	'mwoauth-missing-consumer-key' => 'コンシューマー キーを指定していません。',
	'mwoauth-invalid-consumer-key' => '指定したキーのコンシューマーは存在しません。',
	'mwoauth-invalid-access-token' => '指定したキーのアクセス トークンは存在しません。',
	'mwoauth-invalid-access-wrongwiki' => 'ウィキ「$1」のみで使用できるコンシューマーです。',
	'mwoauth-consumer-stage-disabled' => '無効',
	'mwoauthconsumerregistration' => 'OAuth コンシューマー登録',
	'mwoauthconsumerregistration-notloggedin' => 'このページにアクセスするにはログインしてください。',
	'mwoauthconsumerregistration-navigation' => 'ナビゲーション:',
	'mwoauthconsumerregistration-list' => '自分のコンシューマー一覧',
	'mwoauthconsumerregistration-main' => 'メイン',
	'mwoauthconsumerregistration-update-legend' => 'OAuth コンシューマー アプリケーションの更新',
	'mwoauthconsumerregistration-update-submit' => 'コンシューマーを更新',
	'mwoauthconsumerregistration-name' => 'コンシューマー',
	'mwoauthconsumerregistration-user' => '発行者',
	'mwoauthconsumerregistration-description' => '説明',
	'mwoauthconsumerregistration-consumerkey' => 'コンシューマー キー',
	'mwoauthconsumerregistration-stage' => '状態',
	'mwoauthconsumerregistration-lastchange' => '最新の変更',
	'mwoauthconsumerregistration-updated' => 'あなたの OAuth コンシューマー レジストリを更新しました。',
	'mwoauthconsumerregistration-secretreset' => "「'''$1'''」のコンシューマー秘密トークンを割り当てました。''今後のためこれを記録しておいてください。''",
	'mwoauthmanageconsumers' => 'OAuthコンシューマー管理',
	'mwoauthmanageconsumers-notloggedin' => 'このページにアクセスするにはログインしてください。',
	'mwoauthmanageconsumers-type' => 'キュー:',
	'mwoauthmanageconsumers-main' => 'メイン',
	'mwoauthmanageconsumers-queues' => '以下からコンシューマー確認のキューを選択:',
	'mwoauthmanageconsumers-lists' => '以下からコンシューマーの状態の一覧を選択:',
	'mwoauthmanageconsumers-none-approved' => 'この条件に該当するコンシューマーはありません。',
	'mwoauthmanageconsumers-none-disabled' => 'この条件に該当するコンシューマーはありません。',
	'mwoauthmanageconsumers-name' => 'コンシューマー',
	'mwoauthmanageconsumers-user' => '発行者',
	'mwoauthmanageconsumers-description' => '説明',
	'mwoauthmanageconsumers-email' => '連絡先メール',
	'mwoauthmanageconsumers-consumerkey' => 'コンシューマー キー',
	'mwoauthmanageconsumers-lastchange' => '最新の変更',
	'mwoauthmanageconsumers-confirm-text' => 'このフォームでは、このコンシューマーを承認、却下、無効化、再有効化できます。',
	'mwoauthmanageconsumers-confirm-legend' => 'OAuth コンシューマーの管理',
	'mwoauthmanageconsumers-action' => '状態の変更:',
	'mwoauthmanageconsumers-disable' => '無効',
	'mwoauthmanageconsumers-reason' => '理由:',
	'mwoauthmanageconsumers-confirm-submit' => 'コンシューマーの状態を更新',
	'mwoauthmanageconsumers-viewing' => '利用者「$1」が現在このコンシューマーを閲覧中です',
	'mwoauthmanageconsumers-success-approved' => 'リクエストを承認しました。',
	'mwoauthmanageconsumers-success-rejected' => 'リクエストを却下しました。',
	'mwoauthmanageconsumers-success-disabled' => 'コンシューマーを無効にしました。',
	'mwoauthmanageconsumers-success-reanable' => 'コンシューマーを再度有効にしました。',
	'mwoauthmanageconsumers-search-name' => 'この名前のコンシューマー',
	'mwoauthmanageconsumers-search-publisher' => 'この利用者のコンシューマー',
	'mwoauthlistconsumers' => 'OAuthコンシューマー一覧',
	'mwoauthlistconsumers-legend' => 'OAuth コンシューマーの参照',
	'mwoauthlistconsumers-view' => '詳細',
	'mwoauthlistconsumers-none' => 'この条件に該当するコンシューマーが見つかりません。',
	'mwoauthlistconsumers-name' => 'アプリケーション名',
	'mwoauthlistconsumers-version' => 'コンシューマーのバージョン',
	'mwoauthlistconsumers-user' => '発行者',
	'mwoauthlistconsumers-description' => '説明',
	'mwoauthlistconsumers-callbackurl' => 'OAuth「コールバック URL」',
	'mwoauthlistconsumers-basicgrantsonly' => '(基本的なアクセスのみ)',
	'mwoauthlistconsumers-status' => '状態',
	'mwoauth-consumer-stage-any' => 'すべて',
	'mwoauthlistconsumers-status-approved' => '承認済',
	'mwoauthlistconsumers-status-disabled' => '無効',
	'mwoauthlistconsumers-status-rejected' => '却下',
	'mwoauthlistconsumers-status-expired' => '期限切れ',
	'mwoauthmanagemygrants-text' => 'このページでは、あなたのアカウントを使用できるアプリケーションをすべて列挙します。各アプリケーションについて、あなたの代わりに実行することを承認した際に許可した範囲に、そのアプリケーションの権限が制限されています。If you separately authorized a consumer to access different "sister" projects on your behalf, then you will see separate configuration for each such project below.', # Fuzzy
	'mwoauthmanagemygrants-notloggedin' => 'このページにアクセスするにはログインしてください。',
	'mwoauthmanagemygrants-navigation' => 'ナビゲーション:',
	'mwoauthmanagemygrants-showlist' => 'コンシューマー一覧', # Fuzzy
	'mwoauthmanagemygrants-none' => '現在、あなたのアカウントに接続されているアプリケーションはありません',
	'mwoauthmanagemygrants-name' => 'コンシューマー名',
	'mwoauthmanagemygrants-user' => '発行者',
	'mwoauthmanagemygrants-description' => '説明',
	'mwoauthmanagemygrants-consumerkey' => 'コンシューマー キー',
	'mwoauthmanagemygrants-review' => 'アクセスを管理',
	'mwoauthmanagemygrants-revoke' => 'アクセスを取り消す',
	'mwoauthmanagemygrants-confirm-legend' => 'コンシューマー アクセス トークンの管理',
	'mwoauthmanagemygrants-action' => '状態の変更:',
	'mwoauthmanagemygrants-confirm-submit' => 'アクセス トークンの状態を更新',
	'mwoauthmanagemygrants-success-update' => 'このコンシューマーのアクセス トークンを更新しました。',
	'mwoauthmanagemygrants-success-renounce' => 'このコンシューマーのアクセス トークンを削除しました。',
	'logentry-mwoauthconsumer-propose' => '$1 が OAuth コンシューマーを{{GENDER:$2|提案}} (コンシューマー キー $4)',
	'logentry-mwoauthconsumer-update' => '$1 が OAuth コンシューマーを{{GENDER:$2|更新}} (コンシューマー キー $4)',
	'logentry-mwoauthconsumer-approve' => '$1 が $3 による OAuth コンシューマーを{{GENDER:$2|承認}} (コンシューマー キー $4)',
	'logentry-mwoauthconsumer-reject' => '$1 が $3 による OAuth コンシューマーを{{GENDER:$2|却下}} (コンシューマー キー $4)',
	'logentry-mwoauthconsumer-disable' => '$1 が $3 による OAuth コンシューマーを{{GENDER:$2|無効化}} (コンシューマー キー $4)',
	'logentry-mwoauthconsumer-reenable' => '$1 が $3 による OAuth コンシューマーを{{GENDER:$2|再有効化}} (コンシューマー キー $4)',
	'mwoauthconsumer-consumer-logpage' => 'OAuth コンシューマー記録',
	'mwoauthdatastore-bad-token' => '該当するトークンは見つかりませんでした。',
	'mwoauthdatastore-bad-verifier' => '指定した認証コードは無効でした。',
	'mwoauthgrants-general-error' => 'OAuth リクエストでエラーが発生しました。',
	'mwoauthserver-insufficient-rights' => 'あなたにはこの操作を実行する権限がありません。',
	'mwoauthserver-invalid-request-token' => 'リクエストに無効なトークンがあります。',
	'mwoauthserver-invalid-user-hookabort' => 'この利用者は OAuth を使用できません。',
	'mwoauth-invalid-authorization-title' => 'OAuth 認証エラー',
	'mwoauth-form-privacypolicy-link' => '[[{{ns:Project}}:Privacy policy|プライバシー・ポリシー]]',
	'mwoauth-form-button-approve' => '許可',
	'mwoauth-form-button-cancel' => 'キャンセル',
	'mwoauth-authorize-form-invalid-user' => 'このウィキと中央管理 OAuth ウィキの利用者アカウントがリンクされていないため、このアカウントでは OAuth を使用できません。',
	'mwoauth-error' => 'OAuth エラー',
	'mwoauth-grant-group-email' => 'メールの送信',
	'mwoauth-grant-group-customization' => 'カスタマイズと個人設定',
	'mwoauth-grant-group-other' => 'その他の活動',
	'mwoauth-grant-blockusers' => '利用者をブロック/ブロック解除',
	'mwoauth-grant-createaccount' => 'アカウントを作成',
	'mwoauth-grant-createeditmovepage' => 'ページを作成/編集/移動',
	'mwoauth-grant-delete' => 'ページ、版、記録項目を削除',
	'mwoauth-grant-editinterface' => 'MediaWiki 名前空間および利用者 CSS/JavaScript を編集',
	'mwoauth-grant-editmycssjs' => '自身の利用者 CSS/JavaScript を編集',
	'mwoauth-grant-editmywatchlist' => '自身のウォッチリストを編集',
	'mwoauth-grant-editpage' => '既存のページを編集',
	'mwoauth-grant-editprotected' => '保護されたページを編集',
	'mwoauth-grant-oversight' => '利用者名および版を秘匿',
	'mwoauth-grant-patrol' => 'ページへの変更の巡回',
	'mwoauth-grant-protect' => 'ページを保護および保護解除',
	'mwoauth-grant-rollback' => 'ページヘの変更の巻き戻し',
	'mwoauth-grant-sendemail' => '他の利用者へのメールの送信',
	'mwoauth-grant-uploadeditmovefile' => 'ファイルをアップロード/置き換え/移動',
	'mwoauth-grant-uploadfile' => '新しいファイルをアップロード',
	'mwoauth-grant-useoauth' => '基本的な権限',
	'mwoauth-grant-viewdeleted' => '削除された情報を閲覧',
	'mwoauth-grant-viewmywatchlist' => '自身のウォッチリストを閲覧',
	'mwoauth-oauth-exception' => 'OAuth プロトコルでエラーが発生しました: $1',
	'mwoauth-callback-not-oob' => 'oauth_callback に「oob」を設定してください (大文字小文字を区別)',
	'right-mwoauthproposeconsumer' => '新しいコンシューマーを提案',
	'right-mwoauthupdateownconsumer' => '自身が制御できるOAuthコンシューマーを更新',
	'right-mwoauthmanageconsumer' => 'OAuthコンシューマーを管理',
	'right-mwoauthsuppress' => 'OAuthコンシューマーを秘匿',
	'right-mwoauthviewsuppressed' => '秘匿されたOAuthコンシューマーを閲覧',
	'right-mwoauthviewprivate' => '非公開OAuthデータを閲覧',
	'right-mwoauthmanagemygrants' => 'OAuth付与を管理',
	'action-mwoauthmanageconsumer' => 'OAuthコンシューマーの管理',
	'action-mwoauthmanagemygrants' => 'OAuth付与の管理',
	'action-mwoauthproposeconsumer' => '新しいコンシューマーの提案',
	'action-mwoauthupdateownconsumer' => '自分が制御できるOAuthコンシューマーの更新',
	'action-mwoauthviewsuppressed' => '秘匿されたOAuthコンシューマーの閲覧',
);

/** Korean (한국어)
 * @author Hym411
 * @author 아라
 */
$messages['ko'] = array(
	'mwoauth-desc' => 'OAuth 1.0a API 인증',
	'mwoauth-missing-field' => '"$1" 필드에 대한 값이 없습니다',
	'mwoauth-invalid-field' => '"$1" 필드에 제공한 값이 잘못되었습니다',
	'mwoauth-invalid-field-generic' => '제공한 값이 잘못되었습니다',
	'mwoauth-field-hidden' => '(이 정보는 숨겨져 있습니다)',
	'mwoauth-field-private' => '(이 정보는 비공개입니다)',
	'mwoauth-grant-generic' => '"$1" 권한 번들',
	'mwoauth-prefs-managegrants' => '연결된 애플리케이션:',
	'mwoauth-prefs-managegrantslink' => '$1개의 연결된 애플리케이션 관리',
	'mwoauth-consumer-key' => '컨슈머 키:',
	'mwoauth-consumer-name' => '애플리케이션 이름:',
	'mwoauth-consumer-version' => '컨슈머 버전:',
	'mwoauth-consumer-user' => '게시자:',
	'mwoauth-consumer-stage' => '현재 상태:',
	'mwoauth-consumer-email' => '연락처 이메일 주소:',
	'mwoauth-consumer-description' => '애플리케이션 설명:',
	'mwoauth-consumer-callbackurl' => 'OAuth "콜백" URL:',
	'mwoauth-consumer-grantsneeded' => '적용할 수 있는 부여:',
	'mwoauth-consumer-required-grant' => '컨슈머에게 적용할 수 있음',
	'mwoauth-consumer-wiki' => '적용할 수 있는 위키:',
	'mwoauth-consumer-restrictions' => '사용 제한:',
	'mwoauth-consumer-restrictions-json' => '사용 제한 (JSON):',
	'mwoauth-consumer-rsakey' => '공개 RSA 키:',
	'mwoauth-consumer-secretkey' => '컨슈머 비밀 토큰:',
	'mwoauth-consumer-accesstoken' => '접근 토큰:',
	'mwoauth-consumer-reason' => '이유:',
	'mwoauth-consumer-email-unconfirmed' => '계정 이메일 주소가 아직 확인되지 않았습니다.',
	'mwoauth-consumer-email-mismatched' => '제공한 이메일 주소는 계정과 일치하지 않습니다.',
	'mwoauth-consumer-alreadyexists' => '이 이름/버전/게시자 조합으로 된 컨슈머가 이미 존재합니다',
	'mwoauth-consumer-alreadyexistsversion' => '이 이름/게시자 조합으로 된 컨슈머가 같거나 높은 버전("$1")으로 이미 존재합니다',
	'mwoauth-consumer-not-accepted' => '보류 중인 컨슈머 요청에 대한 정보를 업데이트할 수 없습니다',
	'mwoauth-consumer-not-proposed' => '컨슈머가 현재 제안되어 있지 않습니다',
	'mwoauth-consumer-not-disabled' => '컨슈머가 현재 비활성화되어 있지 않습니다',
	'mwoauth-consumer-not-approved' => '컨슈머가 현재 승인되어 있지 않습니다 (비활성화되었을 수 있습니다)',
	'mwoauth-invalid-consumer-key' => '주어진 키로 된 컨슈머가 존재하지 않습니다.',
	'mwoauth-invalid-access-token' => '주어진 키로 된 접근 토큰이 존재하지 않습니다.',
	'mwoauth-consumer-stage-proposed' => '제안됨',
	'mwoauth-consumer-stage-rejected' => '거부됨',
	'mwoauth-consumer-stage-expired' => '만료됨',
	'mwoauth-consumer-stage-approved' => '승인됨',
	'mwoauth-consumer-stage-disabled' => '비활성화됨',
	'mwoauth-consumer-stage-suppressed' => '억제됨',
	'mwoauthconsumerregistration' => 'OAuth 컨슈머 등록',
	'mwoauthconsumerregistration-notloggedin' => '이 페이지에 접근하려면 로그인해야 합니다.',
	'mwoauthconsumerregistration-navigation' => '둘러보기:',
	'mwoauthconsumerregistration-propose' => '새 컨슈머 제안',
	'mwoauthconsumerregistration-list' => '내 컨슈머 목록',
	'mwoauthconsumerregistration-main' => '주요',
	'mwoauthconsumerregistration-propose-legend' => '새 OAuth 컨슈머 애플리케이션',
	'mwoauthconsumerregistration-update-legend' => 'OAuth 컨슈머 애플리케이션 업데이트',
	'mwoauthconsumerregistration-propose-submit' => '컨슈머 제안',
	'mwoauthconsumerregistration-update-submit' => '컨슈머 업데이트',
	'mwoauthconsumerregistration-none' => '어떠한 OAuth 컨슈머도 제어하지 않습니다.',
	'mwoauthconsumerregistration-name' => '컨슈머',
	'mwoauthconsumerregistration-user' => '게시자',
	'mwoauthconsumerregistration-description' => '설명',
	'mwoauthconsumerregistration-email' => '연락처 이메일',
	'mwoauthconsumerregistration-consumerkey' => '컨슈머 키',
	'mwoauthconsumerregistration-stage' => '상태',
	'mwoauthconsumerregistration-lastchange' => '마지막으로 바뀜',
	'mwoauthconsumerregistration-manage' => '관리',
	'mwoauthconsumerregistration-resetsecretkey' => '비밀 키를 새 값으로 재설정',
	'mwoauthconsumerregistration-updated' => 'OAuth 컨슈머 등록을 성공적으로 업데이트했습니다.',
	'mwoauthmanageconsumers' => 'OAuth 컨슈머 관리',
	'mwoauthmanageconsumers-notloggedin' => '이 페이지에 접근하려면 로그인해야 합니다.',
	'mwoauthmanageconsumers-type' => '대기열:',
	'mwoauthmanageconsumers-showproposed' => '제안된 요청',
	'mwoauthmanageconsumers-showrejected' => '거부된 요청',
	'mwoauthmanageconsumers-showexpired' => '만료된 요청',
	'mwoauthmanageconsumers-main' => '주요',
	'mwoauthmanageconsumers-queues' => '아래에서 컨슈머 확인 대기열을 선택하세요:',
	'mwoauthmanageconsumers-q-proposed' => '제안된 컨슈머 요청의 대기열',
	'mwoauthmanageconsumers-q-rejected' => '거부된 컨슈머 요청의 대기열',
	'mwoauthmanageconsumers-q-expired' => '만료된 컨슈머 요청의 대기열',
	'mwoauthmanageconsumers-lists' => '아래에서 컨슈머 상태 목록을 선택:',
	'mwoauthmanageconsumers-l-approved' => '현재 승인된 컨슈머 목록',
	'mwoauthmanageconsumers-l-disabled' => '현재 비활성화된 컨슈머 목록',
	'mwoauthmanageconsumers-none-proposed' => '이 목록에 제안된 컨슈머가 없습니다.',
	'mwoauthmanageconsumers-none-rejected' => '이 목록에 제안된 컨슈머가 없습니다.',
	'mwoauthmanageconsumers-none-expired' => '이 목록에 제안된 컨슈머가 없습니다.',
	'mwoauthmanageconsumers-none-approved' => '이 조건에 맞는 컨슈머가 없습니다.',
	'mwoauthmanageconsumers-none-disabled' => '이 조건에 맞는 컨슈머가 없습니다.',
	'mwoauthmanageconsumers-name' => '컨슈머',
	'mwoauthmanageconsumers-user' => '게시자',
	'mwoauthmanageconsumers-description' => '설명',
	'mwoauthmanageconsumers-email' => '연락처 이메일',
	'mwoauthmanageconsumers-consumerkey' => '컨슈머 키',
	'mwoauthmanageconsumers-lastchange' => '마지막으로 바뀜',
	'mwoauthmanageconsumers-review' => '검토/관리',
	'mwoauthmanageconsumers-confirm-text' => '이 컨슈머를 승인, 거부, 비활성화, 또는 다시 활성화하려면 이 양식을 사용하세요.',
	'mwoauthmanageconsumers-confirm-legend' => 'OAuth 컨슈머 관리',
	'mwoauthmanageconsumers-action' => '상태 바꾸기:',
	'mwoauthmanageconsumers-approve' => '승인됨',
	'mwoauthmanageconsumers-reject' => '거부됨',
	'mwoauthmanageconsumers-rsuppress' => '거부 및 억제됨',
	'mwoauthmanageconsumers-disable' => '비활성화됨',
	'mwoauthmanageconsumers-dsuppress' => '비활성화 및 억제됨',
	'mwoauthmanageconsumers-reenable' => '승인됨',
	'mwoauthmanageconsumers-reason' => '이유:',
	'mwoauthmanageconsumers-confirm-submit' => '컨슈머 상태 업데이트',
	'mwoauthmanageconsumers-viewing' => '"$1" 사용자는 현재 이 컨슈머를 보는 중입니다',
	'mwoauthmanageconsumers-success-approved' => '요청이 승인되었습니다.',
	'mwoauthmanageconsumers-success-rejected' => '요청이 거부되었습니다.',
	'mwoauthmanageconsumers-success-disabled' => '컨슈머가 비활성화되었습니다.',
	'mwoauthmanageconsumers-success-reanable' => '컨슈머가 다시 활성화되었습니다.',
	'mwoauthmanagemygrants' => '계정 OAuth 부여 관리',
	'mwoauthmanagemygrants-notloggedin' => '이 페이지에 접근하려면 로그인해야 합니다.',
	'mwoauthmanagemygrants-navigation' => '둘러보기:',
	'mwoauthmanagemygrants-showlist' => '허락된 컨슈머 목록',
	'mwoauthmanagemygrants-none' => '컨슈머가 당신의 계정을 대신하여 접근할 수 없습니다.', # Fuzzy
	'mwoauthmanagemygrants-name' => '컨슈머 이름',
	'mwoauthmanagemygrants-user' => '게시자',
	'mwoauthmanagemygrants-description' => '설명',
	'mwoauthmanagemygrants-wiki' => '적용할 수 있는 위키',
	'mwoauthmanagemygrants-wikiallowed' => '위키에 허용됨',
	'mwoauthmanagemygrants-grants' => '적용할 수 있는 부여',
	'mwoauthmanagemygrants-grantsallowed' => '허용된 부여',
	'mwoauthmanagemygrants-applicablegrantsallowed' => '적용할 수 있는 허용된 부여:',
	'mwoauthmanagemygrants-consumerkey' => '컨슈머 키',
	'mwoauthmanagemygrants-review' => '접근 관리',
	'mwoauthmanagemygrants-grantaccept' => '컨슈머 부여됨',
	'mwoauthmanagemygrants-confirm-legend' => '컨슈머 접근 토큰 관리',
	'mwoauthmanagemygrants-update' => '접근 토큰 업데이트',
	'mwoauthmanagemygrants-renounce' => '접근 토큰 인증 해제',
	'mwoauthmanagemygrants-action' => '상태 바꾸기:',
	'mwoauthmanagemygrants-confirm-submit' => '접근 토큰 상태 업데이트',
	'mwoauthmanagemygrants-success-update' => '이 컨슈머에 대한 접근 토큰이 업데이트되었습니다.',
	'mwoauthmanagemygrants-success-renounce' => '이 컨슈머에 대한 접근 토큰이 삭제되었습니다.',
	'logentry-mwoauthconsumer-propose' => '$1 사용자가 OAuth 컨슈머를 {{GENDER:$2|제안했습니다}} (컨슈머 키 $4)',
	'logentry-mwoauthconsumer-update' => '$1 사용자가 OAuth 컨슈머를 {{GENDER:$2|업데이트했습니다}} (컨슈머 키 $4)',
	'logentry-mwoauthconsumer-approve' => '$1 사용자가 $3에 의해 OAuth 컨슈머를 {{GENDER:$2|승인했습니다}} (컨슈머 키 $4)',
	'logentry-mwoauthconsumer-reject' => '$1 사용자가 $3에 의해 OAuth 컨슈머를 {{GENDER:$2|거부했습니다}} (컨슈머 키 $4)',
	'logentry-mwoauthconsumer-disable' => '$1 사용자가 $3에 의해 OAuth 컨슈머를 {{GENDER:$2|비활성화했습니다}} (컨슈머 키 $4)',
	'logentry-mwoauthconsumer-reenable' => '$1 사용자가 $3에 의해 OAuth 컨슈머를 {{GENDER:$2|다시 활성화했습니다}} (컨슈머 키 $4)',
	'mwoauthconsumer-consumer-logpage' => 'OAuth 컨슈머 기록',
	'mwoauthconsumer-consumer-logpagetext' => 'OAuth 컨슈머에 등록된 승인, 거부와 비활성화의 기록입니다.',
	'mwoauth-bad-request' => 'OAuth 요청에 오류가 있습니다.',
	'mwoauthdatastore-access-token-not-found' => '인증 토큰에 대한 승인된 부여를 찾을 수 없습니다.',
	'mwoauthdatastore-request-token-not-found' => '해당 요청에 대한 요청을 찾을 수 없습니다.',
	'mwoauthdatastore-bad-token' => '요청과 일치하는 토큰을 찾을 수 없습니다.',
	'mwoauthdatastore-bad-verifier' => '제공한 인증 코드는 올바르지 않습니다.',
	'mwoauthdatastore-invalid-token-type' => '요청한 토큰 유형이 잘못되었습니다.',
	'mwoauthgrants-general-error' => 'OAuth 요청에 오류가 있습니다.',
	'mwoauthserver-bad-consumer' => '제공한 키에 대한 승인된 컨슈머를 찾을 수 없습니다.',
	'mwoauthserver-insufficient-rights' => '이 작업을 수행할 수 있는 충분한 권한이 없습니다.',
	'mwoauthserver-invalid-request-token' => '요청에 잘못된 토큰이 있습니다.',
	'mwoauthserver-invalid-user-hookabort' => '이 사용자는 OAuth를 사용할 수 없습니다.',
	'mwoauth-invalid-authorization-title' => 'OAuth 인증 오류',
	'mwoauth-invalid-authorization' => '요청에 있는 인증 헤더가 올바르지 않습니다: $1',
	'mwoauth-invalid-authorization-wrong-wiki' => '요청에 있는 인증 헤더가 $1에 대해 올바르지 않습니다',
	'mwoauth-invalid-authorization-invalid-user' => '요청에 있는 인증 헤더가 여기에서 존재하지 않는 사용자를 위한 것입니다',
	'mwoauth-invalid-authorization-wrong-user' => '요청에 있는 인증 헤더가 다른 사용자를 위한 것입니다',
	'mwoauth-invalid-authorization-not-approved' => '요청에 있는 인증 헤더가 현재 승인되지 않은 OAuth 컨슈머를 위한 것입니다',
	'mwoauth-invalid-authorization-blocked-user' => '요청에 있는 인증 헤더가 차단된 사용자를 위한 것입니다',
	'mwoauth-form-button-approve' => '허용합니다',
	'mwoauth-form-button-cancel' => '취소합니다.',
	'mwoauth-authorize-form-invalid-user' => '이 사용자 계정은 계정이 이 위키에 있고,중앙 OAuth가 링크되지 않았기 때문에 OAuth를 사용할 수 없습니다.',
	'mwoauth-error' => 'OAuth 오류',
	'mwoauth-grants-heading' => '요청된 권한:',
	'mwoauth-grants-nogrants' => '애플리케이션은 권한을 요청하지 않았습니다.',
	'mwoauth-grant-group-email' => '이메일 보내기',
	'mwoauth-grant-group-high-volume' => '대량의 작업을 수행',
	'mwoauth-grant-group-customization' => '사용자 최적화 및 환경 설정',
	'mwoauth-grant-group-administration' => '관리 기능 수행',
	'mwoauth-grant-group-other' => '기타 활동',
	'mwoauth-grant-blockusers' => '사용자 차단 또는 차단 해제',
	'mwoauth-grant-createaccount' => '계정 생성',
	'mwoauth-grant-createeditmovepage' => '문서 만들기, 편집 및 옮기기',
	'mwoauth-grant-delete' => '문서, 판 및 기록 항목 삭제',
	'mwoauth-grant-editinterface' => '미디어위키 이름공간과 사용자 CSS/JS 편집',
	'mwoauth-grant-editmycssjs' => '자신의 사용자 CSS/JS 편집하기',
	'mwoauth-grant-editmywatchlist' => '내 주시문서 목록 편집하기',
	'mwoauth-grant-editpage' => '기존 문서 편집하기',
	'mwoauth-grant-editprotected' => '보호된 문서 편집하기',
	'mwoauth-grant-highvolume' => '대용량 편집',
	'mwoauth-grant-oversight' => '사용자 숨기기와 판 억제',
	'mwoauth-grant-patrol' => '페이지 검토',
	'mwoauth-grant-protect' => '문서 보호 및 보호 해제',
	'mwoauth-grant-rollback' => '문서의 바뀜을 되돌리기',
	'mwoauth-grant-sendemail' => '다른 사용자에게 이메일 보내기',
	'mwoauth-grant-uploadeditmovefile' => '파일 올리기, 바꾸기, 옮기기',
	'mwoauth-grant-uploadfile' => '새 파일 올리기',
	'mwoauth-grant-useoauth' => '기본 권한',
	'mwoauth-grant-viewdeleted' => '삭제된 정보 보기',
	'mwoauth-grant-viewmywatchlist' => '내 주시문서 목록 보기',
	'mwoauth-oauth-exception' => 'OAuth 프로토콜에 오류가 발생했습니다: $1',
	'mwoauth-callback-not-oob' => 'oauth_callback은 "oob"로 설정해야 합니다 (대소문자 구분)',
	'right-mwoauthproposeconsumer' => '새 OAuth 컨슈머 제안',
	'right-mwoauthupdateownconsumer' => '내가 제어할 수 있는 OAuth 컨슈머 업데이트',
	'right-mwoauthmanageconsumer' => 'OAuth 컨슈머 관리',
	'right-mwoauthsuppress' => 'OAuth 컨슈머 억제',
	'right-mwoauthviewsuppressed' => '억제된 OAuth 컨슈머 보기',
	'right-mwoauthviewprivate' => '비공개 OAuth 데이터 보기',
	'right-mwoauthmanagemygrants' => 'OAuth 부여 관리',
	'action-mwoauthmanageconsumer' => 'OAuth 컨슈머 관리',
	'action-mwoauthmanagemygrants' => '내 OAuth 부여 관리',
	'action-mwoauthproposeconsumer' => '새 OAuth 컨슈머 제안',
	'action-mwoauthupdateownconsumer' => '내가 제어할 수 있는 OAuth 컨슈머 업데이트',
	'action-mwoauthviewsuppressed' => '억제된 OAuth 컨슈머 보기',
);

/** Luxembourgish (Lëtzebuergesch)
 * @author Robby
 */
$messages['lb'] = array(
	'mwoauth-desc' => 'OAuth 1.0a API Autorisatioun',
	'mwoauth-missing-field' => 'De Wäert fir d\'Feld "$1" feelt',
	'mwoauth-invalid-field-generic' => 'Net valabele Wäert uginn',
	'mwoauth-field-hidden' => '(dës Informatioun ass verstoppt)',
	'mwoauth-field-private' => '(dës Informatioun ass privat)',
	'mwoauth-consumer-allwikis' => 'All Projeten op dësem Site',
	'mwoauth-consumer-name' => 'Numm vun der Applicatioun:',
	'mwoauth-consumer-stage' => 'Aktuelle Status:',
	'mwoauth-consumer-email' => 'Kontakt-E-Mail-Adress:',
	'mwoauth-consumer-description' => 'Beschreiwung vum Programm:',
	'mwoauth-consumer-wiki-thiswiki' => 'Aktuell Wiki ($1)',
	'mwoauth-consumer-wiki-other' => 'Spezifesch Wiki',
	'mwoauth-consumer-reason' => 'Grond:',
	'mwoauth-consumer-email-unconfirmed' => "D'E-Mail-Adress vun Ärem Benotzerkont gouf nach net confirméiert.",
	'mwoauth-consumer-not-disabled' => 'De Konsument ass elo net desaktivéiert',
	'mwoauth-invalid-consumer-key' => 'Et gëtt kee Konsument mat dem Schlëssel deen ugi gouf.',
	'mwoauth-consumer-stage-proposed' => 'geplangt',
	'mwoauth-consumer-stage-rejected' => 'refuséiert',
	'mwoauth-consumer-stage-expired' => 'ofgelaf',
	'mwoauth-consumer-stage-disabled' => 'desaktivéiert',
	'mwoauthconsumerregistration-notloggedin' => 'Dir musst ageloggt si fir op dës Säit ze kommen.',
	'mwoauthconsumerregistration-navigation' => 'Navigatioun:',
	'mwoauthconsumerregistration-update-submit' => 'Konsument aktualiséieren',
	'mwoauthconsumerregistration-name' => 'Konsument',
	'mwoauthconsumerregistration-description' => 'Beschreiwung',
	'mwoauthconsumerregistration-lastchange' => 'Lescht Ännerung',
	'mwoauthmanageconsumers-notloggedin' => 'Dir musst ageloggt si fir op dës Säit ze kommen.',
	'mwoauthmanageconsumers-showproposed' => 'Proposéiert Ufroen',
	'mwoauthmanageconsumers-showrejected' => 'Refuséiert Ufroen',
	'mwoauthmanageconsumers-showexpired' => 'Ofgelafen Ufroen',
	'mwoauthmanageconsumers-main' => 'Haapt',
	'mwoauthmanageconsumers-name' => 'Konsument',
	'mwoauthmanageconsumers-description' => 'Beschreiwung',
	'mwoauthmanageconsumers-lastchange' => 'Lescht Ännerung',
	'mwoauthmanageconsumers-review' => 'nokucken/geréieren',
	'mwoauthmanageconsumers-disable' => 'Desaktivéiert',
	'mwoauthmanageconsumers-reason' => 'Grond:',
	'mwoauthmanageconsumers-success-approved' => 'Ufro gouf ugeholl.',
	'mwoauthmanageconsumers-success-rejected' => 'Ufro gouf refuséiert.',
	'mwoauthlistconsumers-view' => 'Detailer',
	'mwoauthlistconsumers-description' => 'Beschreiwung',
	'mwoauthlistconsumers-wiki' => 'Applicabel Wiki',
	'mwoauthlistconsumers-status-proposed' => 'proposéiert',
	'mwoauthlistconsumers-status-disabled' => 'desaktivéiert',
	'mwoauthmanagemygrants-notloggedin' => 'Dir musst ageloggt si fir op dës Säit ze kommen.',
	'mwoauthmanagemygrants-navigation' => 'Navigatioun:',
	'mwoauthmanagemygrants-description' => 'Beschreiwung',
	'mwoauthmanagemygrants-wiki' => 'Applicabel Wiki',
	'mwoauthmanagemygrants-wikiallowed' => 'Op der Wiki erlaabt',
	'mwoauthmanagemygrants-grantaccept' => 'Accordéiert',
	'mwoauthmanagemygrants-update' => 'Rechter aktualiséieren',
	'mwoauthmanagemygrants-renounce' => 'Autorisatioun ewechhuelen',
	'mwoauthmanagemygrants-action' => 'Status änneren:',
	'mwoauthserver-invalid-user-hookabort' => 'Dëse Benotzer däerf OAuth net benotzen.',
	'mwoauth-invalid-authorization-title' => "OAuth Autorisatioun's-Feeler",
	'mwoauth-invalid-authorization-blocked-user' => "D'Autorisatiounen an Ärer Ufro si fir ee Benotzer dee gespaart ass",
	'mwoauth-form-privacypolicy-link' => '[[{{ns:Project}}:Privacy policy|Dateschutzrichtlinnen]]',
	'mwoauth-form-button-approve' => 'Erlaben',
	'mwoauth-form-button-cancel' => 'Ofbriechen',
	'mwoauth-authorize-form-invalid-user' => 'Dëse Benotzerkont kann OAuth net benotzen, well de Benotzerkont op dëser Wiki an de Benotzerkont op der zentraler OAuth Wiki net matenee verbonn sinn.',
	'mwoauth-error' => 'OAuth Feeler',
	'mwoauth-grants-heading' => 'Ugefroten Autorisatiounen:',
	'mwoauth-grant-group-page-interaction' => 'Mat Säiten interagéieren',
	'mwoauth-grant-group-watchlist-interaction' => 'Mat Ärer Iwwerwaachungslëscht interagéieren',
	'mwoauth-grant-group-email' => 'E-Mail schécken',
	'mwoauth-grant-group-customization' => 'Upassungen an Astellungen',
	'mwoauth-grant-group-other' => 'Verschidden Aktivitéiten',
	'mwoauth-grant-blockusers' => 'Benotzer spären an hir Spär ophiewen',
	'mwoauth-grant-createaccount' => 'Benotzerkonten opmaachen',
	'mwoauth-grant-editinterface' => 'MediaWiki-Nummraum a Benotzer CSS/JS änneren',
	'mwoauth-grant-editmycssjs' => 'Ären eegene Benotzer CSS/JS änneren',
	'mwoauth-grant-editmywatchlist' => 'Ännert Är Iwwerwaachungslëscht',
	'mwoauth-grant-editpage' => 'Säiten déi et gëtt änneren',
	'mwoauth-grant-editprotected' => 'Gespaarte Säiten änneren',
	'mwoauth-grant-oversight' => 'Benotzer verstoppen a Versioune läschen',
	'mwoauth-grant-patrol' => 'Ännerungen op Säiten iwwerwaachen',
	'mwoauth-grant-rollback' => 'Ännerungen op Säiten zrécksetzen',
	'mwoauth-grant-sendemail' => 'Anere Benotzer E-Maile schécken',
	'mwoauth-grant-uploadfile' => 'Nei Fichieren eroplueden',
	'mwoauth-grant-useoauth' => 'Basisrechter',
	'mwoauth-grant-viewdeleted' => 'Geläschten Informatioune kucken',
	'mwoauth-grant-viewmywatchlist' => 'Kuckt Är Iwwerwaachungslëscht',
	'mwoauth-oauth-exception' => 'Am OAuth-Protokoll ass e Feeler geschitt: $1',
);

/** Macedonian (македонски)
 * @author Bjankuloski06
 */
$messages['mk'] = array(
	'mwoauth-desc' => 'Заверка со прилогот OAuth 1.0a',
	'mwoauth-verified' => "На прилогот не му е дозволен пристап до МедијаВики во ваше име.

За да ја довршите постапката, на прилогот укажете му ја следнава контролна вредност: '''$1'''",
	'mwoauth-missing-field' => 'Недостасува вредност во полето „$1“',
	'mwoauth-invalid-field' => 'Во полето „$1“ е зададена неважечка вредност',
	'mwoauth-invalid-field-generic' => 'Укажана е неважечка вредност',
	'mwoauth-field-hidden' => '(оваа информација е скриена)',
	'mwoauth-field-private' => '(оваа информација е приватна)',
	'mwoauth-grant-generic' => 'Група права „$1“',
	'mwoauth-prefs-managegrants' => 'Поврзани прилози:',
	'mwoauth-prefs-managegrantslink' => 'Раководење со $1 {{PLURAL:$1|поврзан прилог|поврзани прилози}}',
	'mwoauth-consumer-allwikis' => 'Сите проекти на ова мрежно место',
	'mwoauth-consumer-key' => 'Потрошувачки клуч:',
	'mwoauth-consumer-name' => 'Назив на прилогот:',
	'mwoauth-consumer-version' => 'Потрошувачка верзија:',
	'mwoauth-consumer-user' => 'Издавач:',
	'mwoauth-consumer-stage' => 'Тековен статус:',
	'mwoauth-consumer-email' => 'Е-пошта за контакт:',
	'mwoauth-consumer-description' => 'Опис на прилогот:',
	'mwoauth-consumer-callbackurl' => 'URL-адреса за повикување на OAuth:',
	'mwoauth-consumer-grantsneeded' => 'Применливи доделувања:',
	'mwoauth-consumer-required-grant' => 'Применлив потрошувач',
	'mwoauth-consumer-wiki' => 'Применливо вики:',
	'mwoauth-consumer-wiki-thiswiki' => 'Тековно вики ($1)',
	'mwoauth-consumer-wiki-other' => 'Конкретно вики',
	'mwoauth-consumer-restrictions' => 'Ограничувања на употребата:',
	'mwoauth-consumer-restrictions-json' => 'Ограничувања на употребата (JSON):',
	'mwoauth-consumer-rsakey' => 'Јавен RSA-клуч:',
	'mwoauth-consumer-secretkey' => 'Тајна потрошувачка шифра:',
	'mwoauth-consumer-accesstoken' => 'Пристапна шифра:',
	'mwoauth-consumer-reason' => 'Причина:',
	'mwoauth-consumer-email-unconfirmed' => 'Вашата е-поштенска адреса сè уште не е потврдена.',
	'mwoauth-consumer-email-mismatched' => 'Укажаната е-пошта мора да одговара на онаа во сметката.',
	'mwoauth-consumer-alreadyexists' => 'Веќе постои потрошувач со ваква комбинација од име/верзија/издавач',
	'mwoauth-consumer-alreadyexistsversion' => 'Веќе постои потрошувач со оваа комбинација на име/издавач со еднаква или повисока верзија („$1“)',
	'mwoauth-consumer-not-accepted' => 'Не можам да ги изменам информациите за потрошувачко барање во исчекување',
	'mwoauth-consumer-not-proposed' => 'Потрошувачот во моментов не е предложен',
	'mwoauth-consumer-not-disabled' => 'Потрошувачот во моментов не е оневозможен',
	'mwoauth-consumer-not-approved' => 'Потрошувачот не е одобрен (може да е оневозможен)',
	'mwoauth-missing-consumer-key' => 'Нема укажано потрошувачки клуч.',
	'mwoauth-invalid-consumer-key' => 'Не постои потрошувач со таков клуч.',
	'mwoauth-invalid-access-token' => 'Не постои пристапна шифра со таков клуч.',
	'mwoauth-invalid-access-wrongwiki' => 'Потрошувачот може да се користи само на викито „$1“.',
	'mwoauth-consumer-conflict' => 'Некои ги изменил атрибутети на овој потрошувач додека го разгледувавте. Обидете се повторно. Може да го погледате и дневникот на измени.',
	'mwoauth-consumer-stage-proposed' => 'предложен',
	'mwoauth-consumer-stage-rejected' => 'одбиен',
	'mwoauth-consumer-stage-expired' => 'истечен',
	'mwoauth-consumer-stage-approved' => 'одобрен',
	'mwoauth-consumer-stage-disabled' => 'оневозможен',
	'mwoauth-consumer-stage-suppressed' => 'притаен',
	'mwoauthconsumerregistration' => 'Регистрација на потрошувач на OAuth',
	'mwoauthconsumerregistration-notloggedin' => 'Треба да сте најавени за да ја отворите страницата.',
	'mwoauthconsumerregistration-navigation' => 'Навигација:',
	'mwoauthconsumerregistration-propose' => 'Предложи нов потрошувач',
	'mwoauthconsumerregistration-list' => 'Список на мои потрошувачи',
	'mwoauthconsumerregistration-main' => 'Главна',
	'mwoauthconsumerregistration-propose-text' => 'Програмерите треба да го користат образецов за предлагање на нов потрошувач на OAuth (повеќе ќе најдете во [//www.mediawiki.org/wiki/Extension:OAuth документацијата на додатокот]). Откако ќе го поднесете образецот, ќе добиете шифра со која ќе се претставувате на МедијаВики. Администратор на OAuth ќе треба да ви ја одобри пријавата пред да можат да ја овластуваат корисниците.

Неколку препораки и напомении:
* Доделувајте што помалку одобренија. Одбегнувајте ги оние што не се потребни во моментов.
* Верзиите се од обликот „главно.споредно.издание“ (последните две се незадолжителни) и зголемете и се зголемуваат со потребата за измени во доделувањата.
* По можност, внесете јавен RSA-клуч (во форматот PEM); во спротивно (помалку безбедно) ќе ви зададеме таен клуч.
* Користете ги полињата за ограничувања од JSON за да го ограничите пристапот на потрошувачот на IP-адресите во тие CIDR-опсези.
* Можете да го ограничите потрошувачот на само едно вики на ова мрежно место, внесувајќи ја назнаката на викито („*“ за сите викија).
* Укажаната е-пошта мора да одговара на онаа на вашата сметка (која пак, мора да биде потврдена).',
	'mwoauthconsumerregistration-update-text' => 'Образецот подолу служи за менување на аспекти на потрошувачот на OAuth што се во ваша контрола.

Сите вредности тука ќе се презапишат врз претходните. Не оставајте празни полиња, освен ако не сакате да ги исчистите истите.',
	'mwoauthconsumerregistration-maintext' => 'Оваа страница им овозможува на програмерите да предлагаат и подновуваат (менуваат) потрошувачки прилози за OAuth (погл. http://oauth.net) во регистарот на ова мрежно место.

Од овде можете да: [[Special:MWOAuthConsumerRegistration/propose|предложите нов потрошувач]] или пак [[Special:MWOAuthConsumerRegistration/list|раководите со вашите постоечки потрошувачи]].',
	'mwoauthconsumerregistration-propose-legend' => 'Нов кориснички прилог за OAuth',
	'mwoauthconsumerregistration-update-legend' => 'Измена на кориснички прилог за OAuth',
	'mwoauthconsumerregistration-propose-submit' => 'Предложи потрошувач',
	'mwoauthconsumerregistration-update-submit' => 'Измени потрошувач',
	'mwoauthconsumerregistration-none' => 'Не контролирате ниеден потрошувач на OAuth.',
	'mwoauthconsumerregistration-name' => 'Потрошувач',
	'mwoauthconsumerregistration-user' => 'Издавач',
	'mwoauthconsumerregistration-description' => 'Опис',
	'mwoauthconsumerregistration-email' => 'Е-пошта за контакт',
	'mwoauthconsumerregistration-consumerkey' => 'Потрошувачки клуч',
	'mwoauthconsumerregistration-stage' => 'Статус',
	'mwoauthconsumerregistration-lastchange' => 'Последна измена',
	'mwoauthconsumerregistration-manage' => 'раководи',
	'mwoauthconsumerregistration-resetsecretkey' => 'Дај нова вредност на тајниот клуч',
	'mwoauthconsumerregistration-proposed' => "Вашето потрошувачко барање за OAuth е примено.

Вашата потрошувачка шифра гласи '''$1''', а тајната шифра гласи '''$2'''. ''Зачувајте ги бидејќи може да ви затребаат во иднина.''",
	'mwoauthconsumerregistration-updated' => 'Вашиот потрошувачки регистар na OAuth е успешно изменет.',
	'mwoauthconsumerregistration-secretreset' => "Вашата тајна потрошувачка шифра гласи '''$1'''. ''Зачувајте ја бидејќи може да ви затреба во иднина.''",
	'mwoauthmanageconsumers' => 'Раководење со потрошувачи на OAuth',
	'mwoauthmanageconsumers-notloggedin' => 'Треба да сте најавени за да ја отворите страницата.',
	'mwoauthmanageconsumers-type' => 'Редици:',
	'mwoauthmanageconsumers-showproposed' => 'Предложени барања',
	'mwoauthmanageconsumers-showrejected' => 'Одбиени барања',
	'mwoauthmanageconsumers-showexpired' => 'Истечени барања',
	'mwoauthmanageconsumers-main' => 'Главна',
	'mwoauthmanageconsumers-maintext' => 'Страницава е предвидена за работење со барања за кориснички прилози за OAuth (погл. http://oauth.net) и раководење со постоечките потрошувачи.',
	'mwoauthmanageconsumers-queues' => 'Подолу изберете редица на потрочувачи за одобрување:',
	'mwoauthmanageconsumers-q-proposed' => 'Редица на барања за предлагање на потрошувачи',
	'mwoauthmanageconsumers-q-rejected' => 'Редица на одбиени потрошувачки барања',
	'mwoauthmanageconsumers-q-expired' => 'Редица на истечени потрошувачки барања',
	'mwoauthmanageconsumers-lists' => 'Подолу изберете потрошувачки статусен список:',
	'mwoauthmanageconsumers-l-approved' => 'Список на моментално одобрени корисници',
	'mwoauthmanageconsumers-l-disabled' => 'Список на моментално оневозможени потрошувачи',
	'mwoauthmanageconsumers-none-proposed' => 'На списокот нема предложени потрошувачи.',
	'mwoauthmanageconsumers-none-rejected' => 'На списокот нема предложени потрошувачи.',
	'mwoauthmanageconsumers-none-expired' => 'На списокот нема предложени потрошувачи.',
	'mwoauthmanageconsumers-none-approved' => 'Нема потрошувачи што одговараат на дадените услови.',
	'mwoauthmanageconsumers-none-disabled' => 'Нема потрошувачи што одговараат на дадените услови.',
	'mwoauthmanageconsumers-name' => 'Потрошувач',
	'mwoauthmanageconsumers-user' => 'Издавач',
	'mwoauthmanageconsumers-description' => 'Опис',
	'mwoauthmanageconsumers-email' => 'Е-пошта за контакт',
	'mwoauthmanageconsumers-consumerkey' => 'Потрошувачки клуч',
	'mwoauthmanageconsumers-lastchange' => 'Последна измена',
	'mwoauthmanageconsumers-review' => 'проверка/раководство',
	'mwoauthmanageconsumers-confirm-text' => 'Образецов служи за одобрување, одбивање или преовозможување на корисникот.',
	'mwoauthmanageconsumers-confirm-legend' => 'Раководење со потрошувач на OAuth',
	'mwoauthmanageconsumers-action' => 'Статус на измената:',
	'mwoauthmanageconsumers-approve' => 'Одобрен',
	'mwoauthmanageconsumers-reject' => 'Одбиен',
	'mwoauthmanageconsumers-rsuppress' => 'Одбиен и притаен',
	'mwoauthmanageconsumers-disable' => 'Оневозможен',
	'mwoauthmanageconsumers-dsuppress' => 'Оневозможен и притаен',
	'mwoauthmanageconsumers-reenable' => 'Одобрено',
	'mwoauthmanageconsumers-reason' => 'Причина:',
	'mwoauthmanageconsumers-confirm-submit' => 'Измени потр. статус',
	'mwoauthmanageconsumers-viewing' => 'Корисникот „$1“ во моментов го гледа потрошувачов',
	'mwoauthmanageconsumers-success-approved' => 'Барањето е одобрено.',
	'mwoauthmanageconsumers-success-rejected' => 'Барањето е одбиено.',
	'mwoauthmanageconsumers-success-disabled' => 'Потрошувачот е оневозможен.',
	'mwoauthmanageconsumers-success-reanable' => 'Потрошувачот е преовозможен.',
	'mwoauthmanageconsumers-search-name' => 'потрошувачи со ова име',
	'mwoauthmanageconsumers-search-publisher' => 'потрошувачи од овој корисник',
	'mwoauthlistconsumers' => 'Список на потрошувачи на OAuth',
	'mwoauthlistconsumers-legend' => 'Прелистај потрошувачи на OAuth',
	'mwoauthlistconsumers-view' => 'подробности',
	'mwoauthlistconsumers-none' => 'Нема потрошувачи што одговараат на дадените услови.',
	'mwoauthlistconsumers-name' => 'Назив на прилогот',
	'mwoauthlistconsumers-version' => 'Потрошувачка верзија',
	'mwoauthlistconsumers-user' => 'Издавач',
	'mwoauthlistconsumers-description' => 'Опис',
	'mwoauthlistconsumers-wiki' => 'Применливо вики',
	'mwoauthlistconsumers-callbackurl' => 'URL-адреса за повикување на OAuth',
	'mwoauthlistconsumers-grants' => 'Применливи доделувања',
	'mwoauthlistconsumers-basicgrantsonly' => '(само основен пристап)',
	'mwoauthlistconsumers-status' => 'Статус',
	'mwoauth-consumer-stage-any' => 'било кој',
	'mwoauthlistconsumers-status-proposed' => 'предложен',
	'mwoauthlistconsumers-status-approved' => 'одобрен',
	'mwoauthlistconsumers-status-disabled' => 'оневозможен',
	'mwoauthlistconsumers-status-rejected' => 'одбиен',
	'mwoauthlistconsumers-status-expired' => 'истечен',
	'mwoauthmanagemygrants' => 'Раководење со доделувања на OAuth на сметки',
	'mwoauthmanagemygrants-text' => 'На страницава се наведени прилозите што можат да ја користат вашата сметка. Нивниот степен на пристап е ограничен од тоа што сте им дозволиле да прават кога сте им го одобриле пристапот. Ако на потрошувачот сте му дале посебно овластување за пристап на друг збратимен проект, тогаш подолу ќе ви се појават посебни поставки за секој одделен проект.',
	'mwoauthmanagemygrants-notloggedin' => 'Треба да сте најавени за да ја отворите страницата.',
	'mwoauthmanagemygrants-navigation' => 'Навигација:',
	'mwoauthmanagemygrants-showlist' => 'Список на прифатени потрошувачи',
	'mwoauthmanagemygrants-none' => 'Нема потрошувачи поврзани со вашата сметка.',
	'mwoauthmanagemygrants-name' => 'Име на потрошувач',
	'mwoauthmanagemygrants-user' => 'Издавач',
	'mwoauthmanagemygrants-description' => 'Опис',
	'mwoauthmanagemygrants-wiki' => 'Применливо вики',
	'mwoauthmanagemygrants-wikiallowed' => 'Дозволено на викито',
	'mwoauthmanagemygrants-grants' => 'Применливи доделувања',
	'mwoauthmanagemygrants-grantsallowed' => 'Дозволени доделувања:',
	'mwoauthmanagemygrants-applicablegrantsallowed' => 'Дозволени применливи доделувања:',
	'mwoauthmanagemygrants-consumerkey' => 'Потрошувачки клуч',
	'mwoauthmanagemygrants-review' => 'раковод. со пристап',
	'mwoauthmanagemygrants-revoke' => 'одземи пристап',
	'mwoauthmanagemygrants-grantaccept' => 'Доделено',
	'mwoauthmanagemygrants-update-text' => 'Со овој образец можете да ги измените дозволите доделени на некој прилог (потрошувач на OAuth) за да делува во ваше име.
* Ако сте му дале посебно овластување за друг збратимен проект, тогаш ќе имате посебни поставки за секој таков проект.
* Со „*“ во википолето можете да му дадете овластување за сите проекти на мрежното место. Ако сакате да го овластите за само еден проект, внесете ја неговата назнака. Конкретните поставки за даден проект имаат предност.', # Fuzzy
	'mwoauthmanagemygrants-revoke-text' => 'Со овој образец можете да му го одземете пристапотна некој прилог (потрошувач на OAuth) за да делува во ваше име.
* Ако сте му дале посебно овластување за друг збратимен проект, тогаш ќе имате посебни поставки за секој таков проект.
* Ако сакате целосно да му го одземете пристапот на прилогот, тоа направете го за секој од проектите за кои сте го овластиле.',
	'mwoauthmanagemygrants-confirm-legend' => 'Раководење со шифра за кориснички пристап',
	'mwoauthmanagemygrants-update' => 'Измени доделувања',
	'mwoauthmanagemygrants-renounce' => 'Одземи дозвола',
	'mwoauthmanagemygrants-action' => 'Смени статус:',
	'mwoauthmanagemygrants-confirm-submit' => 'Измени статус на пристап. шифра',
	'mwoauthmanagemygrants-success-update' => 'Пристапната шифра на овој потрошувач е изменета.',
	'mwoauthmanagemygrants-success-renounce' => 'Пристапната шифра на овој потрошувач е избришана.',
	'logentry-mwoauthconsumer-propose' => '$1 {{GENDER:$2|предложи}} потрошувач на OAuth (потрошувачки клуч $4)',
	'logentry-mwoauthconsumer-update' => '$1 {{GENDER:$2|измени}} потрошувач на OAuth (потрошувачки клуч $4)',
	'logentry-mwoauthconsumer-approve' => '$1 {{GENDER:$2|одобри}} потрошувач на OAuth со $3 (потрошувачки клуч $4)',
	'logentry-mwoauthconsumer-reject' => '$1 {{GENDER:$2|одби}} потрошувач на OAuth со $3 (потрошувачки клуч $4)',
	'logentry-mwoauthconsumer-disable' => '$1 оневозможи потрошувач на OAuth со $3 (потрошувачки клуч $4)',
	'logentry-mwoauthconsumer-reenable' => '$1 {{GENDER:$2|преовозможи}} потрошувач на OAuth со $3 (потрошувачки клуч $4)',
	'mwoauthconsumer-consumer-logpage' => 'Потрошувачки дневник за OAuth',
	'mwoauthconsumer-consumer-logpagetext' => 'Дневник на одобрувања, одбивања и оневозможувања на регистрирани потрошувачи на OAuth.',
	'mwoauth-bad-request' => 'Се јави грешка во барањето за OAuth.',
	'mwoauthdatastore-access-token-not-found' => 'Не пронајдов одобрено доделување со таа повластена шифра',
	'mwoauthdatastore-request-token-not-found' => 'Не пронајдов барање со таа шифра',
	'mwoauthdatastore-bad-token' => 'Не пронајдов барање што одговара на бараното',
	'mwoauthdatastore-bad-verifier' => 'Укажаниот потврден код е неважечки',
	'mwoauthdatastore-invalid-token-type' => 'Побараниот тип на шифра е неважечки.',
	'mwoauthgrants-general-error' => 'Се појави грешка во барањето за OAuth',
	'mwoauthserver-bad-consumer' => 'Нема одобрен потрошувач со таков клуч',
	'mwoauthserver-insufficient-rights' => 'Ги немате потребните права за да го извршите ова дејство.',
	'mwoauthserver-invalid-request-token' => 'Неважечкa шифра во барањето.',
	'mwoauthserver-invalid-user-hookabort' => 'Корисникот не може да користи OAuth',
	'mwoauth-invalid-authorization-title' => 'Грешка со овластувањето во OAuth',
	'mwoauth-invalid-authorization' => 'Овластителните заглавија во вашето барање се неисправни: $1',
	'mwoauth-invalid-authorization-wrong-wiki' => 'Овластителните заглавија во вашето барање се неисправни за $1',
	'mwoauth-invalid-authorization-invalid-user' => 'Овластителните заглавија во вашето барање се однесуваат на корисникот што тука не постои',
	'mwoauth-invalid-authorization-wrong-user' => 'Овластителните заглавија во вашето барање се однесуваат на друг корисник',
	'mwoauth-invalid-authorization-not-approved' => 'Овластителните заглавија во вашето барање се однесуваат на потрошувач на OAuth што во моментов не е одобрен',
	'mwoauth-invalid-authorization-blocked-user' => 'Овластителните заглавија во вашето барање се однесуваат на корисник што е блокиран',
	'mwoauth-form-description-allwikis' => "Здраво $1,

Програмот '''$2''' би сакал да ги изврши следниве дејства во ваше име на ова мрежно место:


$4",
	'mwoauth-form-description-onewiki' => "Здраво $1,

Програмот '''$2''' би сакал да ги изврши следниве дејства во ваше име на ''$4'':


$5",
	'mwoauth-form-description-allwikis-nogrants' => "Здраво $1,

Програмот '''$2''' би сакал да добие основен пристап на сите проекти на ова мрежно место во ваше име.",
	'mwoauth-form-description-onewiki-nogrants' => "Здраво $1,

Програмот '''$2''' би сакал да добие основен пристап до ''$4'' во ваше име.",
	'mwoauth-form-privacypolicy-link' => '[[{{ns:Project}}:Privacy policy|Заштита на личните податоци]]',
	'mwoauth-form-button-approve' => 'Дозволи',
	'mwoauth-form-button-cancel' => 'Откажи',
	'mwoauth-authorize-form-invalid-user' => 'Оваа корисничка сметка не може да користи OAuth бидејќи не е поврзана со сметката на OAuth.',
	'mwoauth-error' => 'Грешка во OAuth',
	'mwoauth-grants-heading' => 'Побарани дозволи:',
	'mwoauth-grants-nogrants' => 'Прилогот нема побарано ниедна дозвола.',
	'mwoauth-acceptance-cancelled' => 'Го имате откажано ова барање за овластување на потрошувач на OAuth да делува ваше име.',
	'mwoauth-grant-group-page-interaction' => 'Опходување со страници',
	'mwoauth-grant-group-file-interaction' => 'Опходување со слики и снимки',
	'mwoauth-grant-group-watchlist-interaction' => 'Опходување со списокот на набљудувања',
	'mwoauth-grant-group-email' => 'Испраќање на е-пошта',
	'mwoauth-grant-group-high-volume' => 'Вршење на активности од голем обем',
	'mwoauth-grant-group-customization' => 'Прилагодувања и поставки',
	'mwoauth-grant-group-administration' => 'Вршење на административни дејства',
	'mwoauth-grant-group-other' => 'Разни активности',
	'mwoauth-grant-blockusers' => 'Блокирање и одблокирање корисници',
	'mwoauth-grant-createaccount' => 'Направи сметки',
	'mwoauth-grant-createeditmovepage' => 'Создавање, измена и преместување на страници',
	'mwoauth-grant-delete' => 'Бришење на страници, ревизии и дневнички записи',
	'mwoauth-grant-editinterface' => 'Измена на именскиот простор „МедијаВики“ и кориснички CSS/JS',
	'mwoauth-grant-editmycssjs' => 'Уредување на ваш кориснички CSS/JS',
	'mwoauth-grant-editmywatchlist' => 'Уредување на ваш список на набљудувања',
	'mwoauth-grant-editpage' => 'Измени постоечки страници',
	'mwoauth-grant-editprotected' => 'Уредување на заштитени страници',
	'mwoauth-grant-highvolume' => 'Уредување на ангро',
	'mwoauth-grant-oversight' => 'Скривање на корисници и ревизии',
	'mwoauth-grant-patrol' => 'Патрола на измени во страници',
	'mwoauth-grant-protect' => 'Заштита на незаштитени страници',
	'mwoauth-grant-rollback' => 'Отповикување на измени во страници',
	'mwoauth-grant-sendemail' => 'Испраќање на е-пошта до други корисници',
	'mwoauth-grant-uploadeditmovefile' => 'Подигање, замена и преместување на податотеки',
	'mwoauth-grant-uploadfile' => 'Подигни нови податотеки',
	'mwoauth-grant-useoauth' => 'Основни права',
	'mwoauth-grant-viewdeleted' => 'Преглед на избришани информации',
	'mwoauth-grant-viewmywatchlist' => 'Преглед на вашите набљудувања',
	'mwoauth-oauth-exception' => 'Се појави грешка во протоколот на OAuth: $1',
	'mwoauth-callback-not-oob' => 'oauth_callback мора да е поставено на „oob“ (строго со мали букви)',
	'right-mwoauthproposeconsumer' => 'Предлагање на нови потрошувачи на OAuth',
	'right-mwoauthupdateownconsumer' => 'Измена на потрошувачи на OAuth',
	'right-mwoauthmanageconsumer' => 'Раководење со потрошувачи на OAuth',
	'right-mwoauthsuppress' => 'Скривање на потрошувачи на OAuth',
	'right-mwoauthviewsuppressed' => 'Преглед на скриените потрошувачи на OAuth',
	'right-mwoauthviewprivate' => 'Преглед на приватни податоци за OAuth',
	'right-mwoauthmanagemygrants' => 'Раководење со доделувања за OAuth',
	'action-mwoauthmanageconsumer' => 'раководење со потрошувачи на OAuth',
	'action-mwoauthmanagemygrants' => 'раководење со вашите доделувања за OAuth',
	'action-mwoauthproposeconsumer' => 'предлагање на потрошувачи на OAuth',
	'action-mwoauthupdateownconsumer' => 'измена на потрошувачи на OAuth',
	'action-mwoauthviewsuppressed' => 'преглед на скриени потрошувачи на OAuth',
);

/** Malayalam (മലയാളം)
 * @author Kavya Manohar
 * @author Raghith
 * @author Santhosh.thottingal
 */
$messages['ml'] = array(
	'mwoauthmanagemygrants-user' => 'പ്രസാധക(ൻ)',
	'mwoauth-form-button-cancel' => 'റദ്ദാക്കുക',
	'mwoauth-grant-sendemail' => 'ഇമെയിൽ അയയ്ക്കുക', # Fuzzy
);

/** Marathi (मराठी)
 * @author V.narsikar
 */
$messages['mr'] = array(
	'mwoauth-prefs-managegrantslink' => 'या खात्याचे वतीने अनुदानांचे व्यवस्थापन करा',
	'mwoauth-consumer-email-unconfirmed' => 'तुमचा विपत्र (ई-मेल) पत्ता अद्याप प्रमाणित झाला नाही.',
	'mwoauth-consumer-email-mismatched' => 'आपल्या खात्याचा विपत्रपत्ता दिलेल्या विपत्रपत्त्याशी जुळावयास हवा.',
	'mwoauth-grant-blockusers' => 'सदस्य प्रतिबंधन', # Fuzzy
	'mwoauth-grant-delete' => 'पाने, आवृत्त्या व नोंदी वगळा',
	'mwoauth-grant-editinterface' => 'मिडियाविकि नामविश्व व सदस्यांची CSS/JS संपादा',
	'mwoauth-grant-editmycssjs' => 'आपले स्वतःचे CSS/JS पान संपादित करा',
	'mwoauth-grant-editmywatchlist' => 'आपली निरीक्षणयादी संपादित करा',
	'mwoauth-grant-editprotected' => 'संपादनांपासून सुरक्षित असलेली पाने',
	'mwoauth-grant-highvolume' => 'अत्त्युच्च-जागा घेणारे संपादन',
	'mwoauth-grant-oversight' => 'सदस्य लपवा व आवृत्त्या दाबा',
	'mwoauth-grant-patrol' => 'गस्त', # Fuzzy
	'mwoauth-grant-protect' => 'पाने सुरक्षित किंवा असुरक्षित करा',
	'mwoauth-grant-rollback' => 'परतवा', # Fuzzy
	'mwoauth-grant-sendemail' => 'विपत्र पाठवा', # Fuzzy
	'mwoauth-grant-useoauth' => 'मुळ अधिकार',
	'mwoauth-grant-viewdeleted' => 'वगळलेली माहिती बघा',
	'mwoauth-grant-viewmywatchlist' => 'आपली निरीक्षणसूची बघा',
);

/** Malay (Bahasa Melayu)
 * @author Anakmalaysia
 */
$messages['ms'] = array(
	'mwoauth-desc' => 'Kelulusan OAuth 1.0a API',
	'mwoauth-verified' => "Aplikasi ini kini dibenarkan untuk mengakses MediaWiki bagi pihak anda.

Untuk melengkapkan proses ini, berikan nilai penentusahan ini kepada aplikasi: '''$1'''",
	'mwoauth-missing-field' => 'Nilai tertinggal untuk ruangan "$1"',
	'mwoauth-invalid-field' => 'Nilai yang diberikan tidak sah untuk ruangan "$1"',
	'mwoauth-invalid-field-generic' => 'Nilai yang diberikan tidak sah',
	'mwoauth-field-hidden' => '(maklumat ini tersembunyi)',
	'mwoauth-field-private' => '(maklumat ini adalah peribadi)',
);

/** Low Saxon (Netherlands) (Nedersaksies)
 * @author Servien
 */
$messages['nds-nl'] = array(
	'mwoauthconsumerregistration-navigation' => 'Navigasie:',
	'mwoauthconsumerregistration-main' => 'Veurblad',
	'mwoauthconsumerregistration-stage' => 'Staotus',
);

/** Dutch (Nederlands)
 * @author Hansmuller
 * @author Siebrand
 * @author Sjoerddebruin
 */
$messages['nl'] = array(
	'mwoauth-desc' => 'Authenticatie via de OAuth 1.0a API',
	'mwoauth-missing-field' => 'Waarde voor het veld "$1" ontbreekt',
	'mwoauth-invalid-field' => 'Er is een ongeldige waarde opgegeven voor het veld "$1"',
	'mwoauth-field-hidden' => '(deze gegevens zijn verborgen)',
	'mwoauth-field-private' => '(deze gegevens zijn privé)',
	'mwoauth-consumer-key' => 'Consumersleutel:',
	'mwoauth-consumer-name' => 'Naam toepassing:',
	'mwoauth-consumer-version' => 'Consumerversie:',
	'mwoauth-consumer-user' => 'Uitgever:',
	'mwoauth-consumer-stage' => 'Huidige status:',
	'mwoauth-consumer-email' => 'E-mailadres voor contact:',
	'mwoauth-consumer-description' => 'Toepassingsbeschrijving:',
	'mwoauth-consumer-callbackurl' => 'URL voor OAuth-"callback":',
	'mwoauth-consumer-grantsneeded' => 'Van toepassing zijnde rechten:',
	'mwoauth-consumer-required-grant' => 'Van toepassing op consumer',
	'mwoauth-consumer-wiki' => 'Van toepassing op wiki:',
	'mwoauth-consumer-wiki-other' => 'Specifieke wiki',
	'mwoauth-consumer-restrictions' => 'Gebruiksbeperkingen:',
	'mwoauth-consumer-restrictions-json' => 'Gebruiksbeperkingen (JSON):',
	'mwoauth-consumer-rsakey' => 'Openbare SSH-sleutel:',
	'mwoauth-consumer-secretkey' => 'Geheim token consumer:',
	'mwoauth-consumer-accesstoken' => 'Toegangstoken:',
	'mwoauth-consumer-reason' => 'Reden:',
	'mwoauth-invalid-access-token' => 'Er bestaat geen toegangstoken met de opgegeven sleutel.',
	'mwoauth-consumer-stage-proposed' => 'voorgesteld',
	'mwoauth-consumer-stage-rejected' => 'afgewezen',
	'mwoauth-consumer-stage-expired' => 'vervallen',
	'mwoauth-consumer-stage-approved' => 'goedgekeurd',
	'mwoauth-consumer-stage-disabled' => 'uitgeschakeld',
	'mwoauth-consumer-stage-suppressed' => 'onderdrukt',
	'mwoauthconsumerregistration-navigation' => 'Navigatie:',
	'mwoauthconsumerregistration-propose' => 'Nieuwe consumer voorstellen',
	'mwoauthconsumerregistration-list' => 'Uw consumerlijst',
	'mwoauthconsumerregistration-propose-text' => 'Ontwikkelaars moeten het onderstaande formulier gebruiken om een nieuwe OAuthconsumer voor te stellen (zie de [//www.mediawiki.org/wiki/Extension:OAuth documentatie van de uitbreiding] voor meer details). Na het indienden van dit formulier ontvangt u een token dat uw programma gaat gebruiken om zichzelf te identificeren bij MediaWiki. Een OAuthbeheerder moet uw aanvraag goedkeuren voor het door andere gebruikers kan worden toegestaan.

Een paar aanbevelingen en opmerkingen:
* Probeer zo min mogelijk bevoegdheden te gebruiken  Vermijd bevoegdheden die niet echt nodig zijn;
* Versies hebben de opmaak "groot.klein.release" (de laatste twee elementen zijn optioneel) en moeten oplopen als er wijzigingen voor de toestemmingen nodig zijn;
* Gebruik als mogelijk een RSA-sleutel (in PEM-opmaak); als dat niet mogelijk is, wordt u een (minder veilig) geheim token toegewezen;
* Gebruik het veld JSON-beperkingen om de toegang voor deze consumer te beperken tot IP-adressen in de opgegeven CIDR-bereiken;
* U kunt een wiki-ID gebruiken om de consumer te beperken tot één enkele wiki op deze site (gebruik "*" voor alle wiki\'s);
* Het e-mailadres moet overeenkomen met dat van uw gebruiker (en het e-mailadres moet zijn bevestigd).',
	'mwoauthconsumerregistration-propose-legend' => 'Nieuwe OAuthconsumertoepassing',
	'mwoauthconsumerregistration-update-legend' => 'OAuthconsumertoepassing bijwerken',
	'mwoauthconsumerregistration-propose-submit' => 'Consumer voorstellen',
	'mwoauthconsumerregistration-update-submit' => 'Consumer bijwerken',
	'mwoauthconsumerregistration-name' => 'Consumer',
	'mwoauthconsumerregistration-user' => 'Uitgever',
	'mwoauthconsumerregistration-description' => 'Beschrijving',
	'mwoauthconsumerregistration-email' => 'E-mailadres voor contact',
	'mwoauthconsumerregistration-consumerkey' => 'Consumersleutel',
	'mwoauthconsumerregistration-stage' => 'Status',
	'mwoauthconsumerregistration-lastchange' => 'Laatste wijziging',
	'mwoauthconsumerregistration-manage' => 'beheren',
	'mwoauthmanageconsumers' => 'OAuthconsumers beheren',
	'mwoauthmanageconsumers-type' => 'Wachtrijen:',
	'mwoauthmanageconsumers-showproposed' => 'Voorgestelde verzoeken',
	'mwoauthmanageconsumers-showrejected' => 'Afgewezen verzoeken',
	'mwoauthmanageconsumers-showexpired' => 'Verlopen aanvragen',
	'mwoauthmanageconsumers-lists' => 'Selecteer een consumerstatus uit de onderstaande lijst:',
	'mwoauthmanageconsumers-none-expired' => 'Geen voorgestelde consumers in deze lijst.',
	'mwoauthmanageconsumers-none-approved' => 'Er zijn geen consumers die aan deze voorwaarden voldoen.',
	'mwoauthmanageconsumers-name' => 'Consumer',
	'mwoauthmanageconsumers-user' => 'Uitgever',
	'mwoauthmanageconsumers-description' => 'Beschrijving',
	'mwoauthmanageconsumers-email' => 'E-mailadres voor contact',
	'mwoauthmanageconsumers-consumerkey' => 'Consumersleutel',
	'mwoauthmanageconsumers-lastchange' => 'Laatste wijziging',
	'mwoauthmanageconsumers-review' => 'controleren en beheren',
	'mwoauthmanageconsumers-confirm-text' => 'Gebruik dit formulier om deze consumer goed te keuren, af te keuren of opnieuw in te schakelen.',
	'mwoauthmanageconsumers-confirm-legend' => 'OAuthconsumer beheren',
	'mwoauthmanageconsumers-action' => 'Status wijzigen:',
	'mwoauthmanageconsumers-approve' => 'Goedgekeurd',
	'mwoauthmanageconsumers-reject' => 'Afgewezen',
	'mwoauthmanageconsumers-rsuppress' => 'Afgewezen en onderdrukt',
	'mwoauthmanageconsumers-disable' => 'Uitgeschakeld',
	'mwoauthmanageconsumers-dsuppress' => 'Uitgeschakeld en onderdrukt',
	'mwoauthmanageconsumers-reenable' => 'Goedgekeurd',
	'mwoauthmanageconsumers-reason' => 'Reden:',
	'mwoauthmanageconsumers-confirm-submit' => 'Consumerstatus bijwerken',
	'mwoauthmanageconsumers-viewing' => 'Gebruiker "$1" bekijkt op dit moment deze consumer',
	'mwoauthmanageconsumers-success-approved' => 'Het verzoek is goedgekeurd.',
	'mwoauthmanageconsumers-success-rejected' => 'Het verzoek is afgewezen.',
	'mwoauthmanageconsumers-success-disabled' => 'De consumer is uitgeschakeld.',
	'mwoauthmanageconsumers-success-reanable' => 'De consumer is opnieuw ingeschakeld.',
	'mwoauthmanagemygrants' => 'OAuthrechten van gebruiker beheren',
	'mwoauthmanagemygrants-navigation' => 'Navigatie:',
	'mwoauthmanagemygrants-showlist' => 'Geaccepteerde consumerlijst',
	'mwoauthmanagemygrants-none' => 'Er zijn geen consumers die toegang hebben namens uw gebruiker.', # Fuzzy
	'mwoauthmanagemygrants-name' => 'Consumernaam',
	'mwoauthmanagemygrants-user' => 'Uitgever',
	'mwoauthmanagemygrants-description' => 'Beschrijving',
	'mwoauthmanagemygrants-wiki' => 'Van toepassing op wiki',
	'mwoauthmanagemygrants-wikiallowed' => 'Toegestaan op wiki',
	'mwoauthmanagemygrants-grants' => 'Van toepassing zijnde rechten',
	'mwoauthmanagemygrants-grantsallowed' => 'Toegestane rechten:',
	'mwoauthmanagemygrants-applicablegrantsallowed' => 'Van toepassing zijnde rechten toegestaan:',
	'mwoauthmanagemygrants-consumerkey' => 'Consumersleutel',
	'mwoauthmanagemygrants-review' => 'toegang beheren',
	'mwoauthmanagemygrants-grantaccept' => 'Toegestaan',
	'mwoauthmanagemygrants-confirm-legend' => 'consumertoegangstoken beheren',
	'mwoauthmanagemygrants-update' => 'Toegang bijwerken',
	'mwoauthmanagemygrants-renounce' => 'Machtiging intrekken',
	'mwoauthmanagemygrants-action' => 'Statuswijziging:',
	'mwoauthmanagemygrants-confirm-submit' => 'Toegangstokenstatus bijwerken',
	'mwoauthmanagemygrants-success-update' => 'Het toegangstoken voor deze consumer is bijgewerkt.',
	'mwoauthmanagemygrants-success-renounce' => 'Het toegangstoken voor deze consumer is verwijderd.',
	'mwoauthconsumer-consumer-logpage' => 'OAuthconsumerlogboek',
	'mwoauthconsumer-consumer-logpagetext' => 'Logboek met goedkeuringen, afwijzingen en uitschakelingen van geregistreerde OAuthconsumers.',
	'mwoauth-bad-request' => 'Er is een fout opgetreden in uw OAthverzoek.',
	'mwoauthdatastore-request-token-not-found' => 'Er is geen verzoek aangetroffen voor dat token',
	'mwoauthdatastore-bad-token' => 'Er is geen token gevonden dat hoort bij uw verzoek',
	'mwoauthdatastore-bad-verifier' => 'De verificatiecode die is opgegeven is niet geldig',
	'mwoauthdatastore-invalid-token-type' => 'Het verzoektokentype is ongeldig',
	'mwoauthgrants-general-error' => 'Er is een fout opgetreden in uw OAthverzoek',
	'mwoauthserver-bad-consumer' => 'Er is geen goedgekeurde consumer voor de opgegeven sleutel',
	'mwoauthserver-insufficient-rights' => 'U hebt onvoldoende rechten om deze handeling uit te voeren',
	'mwoauthserver-invalid-request-token' => 'Ongeldig token in uw verzoek',
	'mwoauthserver-invalid-user-hookabort' => 'Deze gebruiker kan OAuth niet gebruiken',
	'mwoauth-form-button-approve' => 'Toestaan',
	'mwoauth-grants-heading' => 'Aangevraagde rechten:',
	'mwoauth-grants-nogrants' => 'De toepassing heeft geen rechten aangevraagd.',
	'mwoauth-grant-blockusers' => 'Gebruikers (de)blokkeren',
	'mwoauth-grant-createeditmovepage' => "Pagina's aanmaken, bewerken en hernoemen",
	'mwoauth-grant-delete' => "Pagina's, wijzigingen en vermeldingen in het logboek verwijderen",
	'mwoauth-grant-editmycssjs' => 'Uw eigen CSS/JavaScript bewerken',
	'mwoauth-grant-editmywatchlist' => 'Uw eigen volglijst bewerken',
	'mwoauth-grant-editpage' => "Bestaande pagina's bewerken",
	'mwoauth-grant-editprotected' => "Beveiligde pagina's bewerken",
	'mwoauth-grant-highvolume' => 'Veel bewerkingen in korte tijd maken',
	'mwoauth-grant-oversight' => 'Gebruikers en versies verbergen',
	'mwoauth-grant-patrol' => "Wijzigingen aan pagina's controleren",
	'mwoauth-grant-protect' => "Pagina's beveiligen en beveiliging opheffen",
	'mwoauth-grant-rollback' => "Wijzigingen aan pagina's terugdraaien",
	'mwoauth-grant-sendemail' => 'E-mail verzenden aan andere gebruikers',
	'mwoauth-grant-uploadeditmovefile' => 'Bestanden uploaden, vervangen en hernoemen',
	'mwoauth-grant-uploadfile' => 'Nieuwe bestanden uploaden',
	'mwoauth-grant-useoauth' => 'Grondrechten',
	'mwoauth-grant-viewdeleted' => 'Verwijderde gegevens bekijken',
	'mwoauth-grant-viewmywatchlist' => 'Uw volglijst bekijken',
	'right-mwoauthproposeconsumer' => 'Nieuwe OAuthconsumers voorstellen',
	'right-mwoauthupdateownconsumer' => 'OAuthconsumers bijwerken',
	'right-mwoauthmanageconsumer' => 'OAuthconsumers beheren',
	'right-mwoauthsuppress' => 'OAuthconsumers onderdrukken',
	'right-mwoauthviewsuppressed' => 'Onderdrukte OAuthconsumers bekijken',
	'right-mwoauthmanagemygrants' => 'OAuthbevoegdheden beheren',
	'action-mwoauthmanageconsumer' => 'OAuthconsumers te beheren',
	'action-mwoauthmanagemygrants' => 'uw OAuthbevoegdheden te beheren',
	'action-mwoauthproposeconsumer' => 'nieuwe OAuthconsumers voor te stellen',
	'action-mwoauthupdateownconsumer' => 'OAuthconsumers bij te werken',
	'action-mwoauthviewsuppressed' => 'onderdrukte OAuthconsumers te bekijken',
);

/** Occitan (occitan)
 * @author Cedric31
 */
$messages['oc'] = array(
	'mwoauth-desc' => 'API d’autentificacion OAuth 1.0a',
	'mwoauth-missing-field' => 'Valor mancanta pel camp « $1 »',
	'mwoauth-invalid-field' => 'Valor invalida provesida pel camp « $1 »',
	'mwoauth-field-hidden' => '(aquesta informacion es amagada)',
	'mwoauth-field-private' => '(aquesta informacion es privada)',
	'mwoauth-grant-generic' => 'ensemble de dreches « $1 »',
	'mwoauth-prefs-managegrants' => 'Accès del consomator OAuth :', # Fuzzy
	'mwoauth-prefs-managegrantslink' => "Gerir los dreches al nom d'aqueste compte", # Fuzzy
	'mwoauth-consumer-key' => 'Clau del consomator :',
	'mwoauth-consumer-name' => "Nom de l'aplicacion :",
	'mwoauth-consumer-version' => 'Version del consomator :',
	'mwoauth-consumer-user' => 'Editor :',
	'mwoauth-consumer-stage' => 'Estatut actual :',
	'mwoauth-consumer-email' => 'Adreça de corrièr electronic de contacte :',
	'mwoauth-consumer-description' => "Descripcion de l'aplicacion :",
	'mwoauth-consumer-callbackurl' => 'URl « de rapèl » per OAuth :',
	'mwoauth-consumer-grantsneeded' => 'Dreches aplicables :',
	'mwoauth-consumer-required-grant' => 'Aplicable al consomator',
	'mwoauth-consumer-wiki' => 'Wiki aplicable :',
	'mwoauth-consumer-restrictions' => 'Limitacions d’utilizacion :',
	'mwoauth-consumer-restrictions-json' => 'Limitacions d’utilizacion (JSON) :',
	'mwoauth-consumer-rsakey' => 'Clau RSA publica :',
	'mwoauth-consumer-secretkey' => 'Geton secret del consomator :',
	'mwoauth-consumer-accesstoken' => 'Geton d’accès :',
	'mwoauth-consumer-reason' => 'Motiu :',
	'mwoauth-consumer-email-unconfirmed' => 'Vòstra adreça de corrièr electronic del compte es pas encara estada confirmada.',
	'mwoauth-consumer-email-mismatched' => 'L’adreça de corrièr electronic provesida deu correspondre a la de vòstre compte.',
	'mwoauth-consumer-alreadyexists' => 'Un consomator amb aquesta combinason de nom/version/editor existís ja',
	'mwoauth-consumer-alreadyexistsversion' => 'Un consomator amb aquesta combinason de nom/editor existís ja amb una version egala o superiora ("$1")',
	'mwoauth-consumer-not-accepted' => 'Impossible de metre a jorn las informacions per una demanda de consomator en cors',
	'mwoauth-consumer-not-proposed' => 'Lo consomator es pas prepausat actualament',
	'mwoauth-consumer-not-disabled' => 'Lo consomator es pas desactivat pel moment',
	'mwoauth-consumer-not-approved' => "Lo consomator es pas aprovat (benlèu qu'es estat desactivat)",
	'mwoauth-invalid-consumer-key' => 'Cap de consomator existís pas amb la clau provesida.',
	'mwoauth-invalid-access-token' => 'Cap de geton d’accès existís pas per la clau provesida',
	'mwoauth-consumer-conflict' => "Qualqu’un a modificat los atributs d'aqueste consomator pendent que lo consultavatz. Tornatz ensajar. Podètz tanben verificar lo jornal de las modificacions.",
	'mwoauth-consumer-stage-proposed' => 'prepausat',
	'mwoauth-consumer-stage-rejected' => 'regetat',
	'mwoauth-consumer-stage-expired' => 'expirat',
	'mwoauth-consumer-stage-approved' => 'aprovat',
	'mwoauth-consumer-stage-disabled' => 'desactivat',
	'mwoauth-consumer-stage-suppressed' => 'suprimit',
	'mwoauthconsumerregistration' => 'Inscripcion del consomator OAuth',
	'mwoauthconsumerregistration-notloggedin' => 'Vos cal èsser connectat per accedir a aquesta pagina.',
	'mwoauthconsumerregistration-navigation' => 'Navigacion :',
	'mwoauthconsumerregistration-propose' => 'Prepausar un novèl consomator',
	'mwoauthconsumerregistration-list' => 'Ma lista de consomators',
	'mwoauthconsumerregistration-main' => 'Principal',
	'mwoauthconsumerregistration-propose-submit' => 'Prepausar un consomator',
	'mwoauthconsumerregistration-update-submit' => 'Metre a jorn un consomator',
	'mwoauthconsumerregistration-none' => 'Contrarotlatz pas cap de consomator OAuth.',
	'mwoauthconsumerregistration-name' => 'Consomator',
	'mwoauthconsumerregistration-user' => 'Editor',
	'mwoauthconsumerregistration-description' => 'Descripcion',
	'mwoauthconsumerregistration-email' => 'Corrièr electronic de contacte',
	'mwoauthconsumerregistration-consumerkey' => 'Clau del consomator',
	'mwoauthconsumerregistration-stage' => 'Estat',
	'mwoauthconsumerregistration-lastchange' => 'Darrièr cambiament',
	'mwoauthconsumerregistration-manage' => 'gerir',
	'mwoauthmanageconsumers' => 'Gerir los consomators OAuth',
	'mwoauthmanageconsumers-notloggedin' => 'Vos cal èsser connectat per accedir a aquesta pagina.',
	'mwoauthmanageconsumers-type' => "Filas d'espèra :",
	'mwoauthmanageconsumers-showproposed' => 'Requèstas prepausadas',
	'mwoauthmanageconsumers-showrejected' => 'Requèstas regetadas',
	'mwoauthmanageconsumers-showexpired' => 'Requèstas expiradas',
	'mwoauthmanageconsumers-main' => 'Principal',
	'mwoauthmanageconsumers-name' => 'Consomator',
	'mwoauthmanageconsumers-user' => 'Editor',
	'mwoauthmanageconsumers-description' => 'Descripcion',
	'mwoauthmanageconsumers-email' => 'Corrièr electronic de contacte',
	'mwoauthmanageconsumers-consumerkey' => 'Clau del consomator',
	'mwoauthmanageconsumers-lastchange' => 'Darrièr cambiament',
	'mwoauthmanageconsumers-review' => 'reveire/gerir',
	'mwoauthmanageconsumers-approve' => 'Aprovat',
	'mwoauthmanageconsumers-reject' => 'Regetat',
	'mwoauthmanageconsumers-disable' => 'Desactivat',
	'mwoauthmanageconsumers-reenable' => 'Aprovat',
	'mwoauthmanageconsumers-reason' => 'Motiu :',
	'mwoauthmanagemygrants-navigation' => 'Navigacion :',
	'mwoauthmanagemygrants-user' => 'Editor',
	'mwoauthmanagemygrants-description' => 'Descripcion',
	'mwoauthmanagemygrants-wiki' => 'Wiki aplicable',
	'mwoauthmanagemygrants-wikiallowed' => 'Autorizat sul wiki',
	'mwoauthmanagemygrants-consumerkey' => 'Clau del consomator',
	'mwoauth-error' => 'Error OAuth',
	'mwoauth-grant-blockusers' => 'Blocar e desblocar los utilizaires',
	'mwoauth-grant-patrol' => 'Marcar de paginas coma patrolhadas',
);

/** Polish (polski)
 * @author Chrumps
 * @author Ty221
 */
$messages['pl'] = array(
	'mwoauth-consumer-reason' => 'Powód:',
	'mwoauthmanageconsumers-reason' => 'Powód:',
	'mwoauth-grant-group-customization' => 'Dostosowywanie i preferencje',
);

/** Pashto (پښتو)
 * @author Ahmed-Najib-Biabani-Ibrahimkhel
 */
$messages['ps'] = array(
	'mwoauth-grant-blockusers' => 'په کارنانو بنديز لگول او بنديز ليرې کول',
);

/** Portuguese (português)
 * @author Dannyps
 */
$messages['pt'] = array(
	'mwoauth-form-button-approve' => 'Sim, permitir', # Fuzzy
	'mwoauth-grants-heading' => 'Permissões solicitadas:',
);

/** Brazilian Portuguese (português do Brasil)
 * @author Luckas
 */
$messages['pt-br'] = array(
	'mwoauth-form-button-approve' => 'Permitir',
	'mwoauth-form-button-cancel' => 'Cancelar',
	'mwoauth-grant-createaccount' => 'Criar contas',
);

/** tarandíne (tarandíne)
 * @author Joetaras
 */
$messages['roa-tara'] = array(
	'mwoauth-desc' => 'OAuth 1.0a API de autendicazione',
	'mwoauth-missing-field' => 'Valore zumbate pu cambe "$1"',
	'mwoauth-invalid-field' => 'Valore date invalide pu cambe "$1"',
	'mwoauth-field-hidden' => "(sta 'mbormazione jè scunnute)",
	'mwoauth-field-private' => "(sta 'mbormazione jè private)",
	'mwoauth-consumer-key' => "Chiave d'u consumatore:",
	'mwoauth-consumer-name' => "Nome de l'applicazione:",
	'mwoauth-consumer-version' => "Versione d'u consumatore:",
	'mwoauth-consumer-user' => 'Pubblecatore:',
	'mwoauth-consumer-stage' => 'State de mò:',
	'mwoauth-consumer-email' => 'Indirizze email de condatte:',
	'mwoauth-consumer-description' => "Descrizione de l'applicazione:",
	'mwoauth-consumer-wiki' => 'Uicchi applicabbile:',
	'mwoauth-consumer-restrictions' => 'Ause le restriziune:',
	'mwoauth-consumer-rsakey' => 'Chiave pubblche RSA:',
	'mwoauth-consumer-reason' => 'Mutive:',
	'mwoauth-consumer-stage-proposed' => 'proposte',
	'mwoauth-consumer-stage-rejected' => 'scettate',
	'mwoauth-consumer-stage-expired' => 'scadute',
	'mwoauth-consumer-stage-approved' => 'approvate',
	'mwoauth-consumer-stage-disabled' => 'disabbilitate',
	'mwoauth-consumer-stage-suppressed' => 'scangellate',
	'mwoauthconsumerregistration-navigation' => 'Navigazzione:',
	'mwoauthconsumerregistration-main' => 'Prengepàle',
	'mwoauthconsumerregistration-user' => 'Pubblecatore',
	'mwoauthconsumerregistration-description' => 'Descrizione',
	'mwoauthconsumerregistration-email' => 'Email de condatte',
	'mwoauthconsumerregistration-consumerkey' => "Chiave d'u consumatore",
	'mwoauthconsumerregistration-lastchange' => 'Urteme cangiamende',
	'mwoauthconsumerregistration-manage' => 'gestisce',
	'mwoauthmanageconsumers-type' => 'Code:',
	'mwoauthmanageconsumers-showproposed' => 'Richieste proposte',
	'mwoauthmanageconsumers-showrejected' => 'Richieste scettate',
	'mwoauthmanageconsumers-showexpired' => 'Richieste scadute',
	'mwoauthmanageconsumers-main' => 'Prengepàle',
	'mwoauthmanageconsumers-reason' => 'Mutive:',
);

/** Slovak (slovenčina)
 * @author Kusavica
 */
$messages['sk'] = array(
	'mwoauth-form-button-approve' => 'Povoliť',
	'mwoauth-form-button-cancel' => 'Zrušiť',
	'mwoauth-grant-group-email' => 'Poslať email',
);

/** Slovenian (slovenščina)
 * @author Dbc334
 * @author Eleassar
 */
$messages['sl'] = array(
	'mwoauth-consumer-allwikis' => 'Vsi projekti na tem spletišču',
	'mwoauthmanagemygrants-review' => 'upravljaj z dostopom',
	'mwoauthmanagemygrants-grantaccept' => 'Donirano',
	'mwoauthmanagemygrants-update' => 'Posodobitev donacij',
	'mwoauthmanagemygrants-renounce' => 'Deavtoriziraj',
	'mwoauth-form-description-allwikis' => "Pozdravljeni, $1

'''$2''' bi v vašem imenu v vseh projektih tega spletišča rad izvedel naslednje dejanje:


$4",
	'mwoauth-form-description-onewiki' => "Pozdravljeni, $1,

'''$2''' bi rad na projektu »$4« v vašem imenu izvedel naslednje dejanje:


$5",
	'mwoauth-form-description-allwikis-nogrants' => "Pozdravljeni, $1,

'''$2''' bi v vseh projektih tega spletišča rad imel osnovni dostop v vašem imenu.$2",
	'mwoauth-form-description-onewiki-nogrants' => "Pozdravljeni, $1,

'''$2''' bi v projektu ''$4'' rad imel osnovni dostop v vašem imenu.",
	'mwoauth-form-privacypolicy-link' => '[[{{ns:Project}}:Privacy policy|Politika zasebnosti]]',
	'mwoauth-form-button-approve' => 'Dovoli',
	'mwoauth-form-button-cancel' => 'Prekliči',
	'mwoauth-acceptance-cancelled' => 'To prošnjo za avtorizacijo uporabnika OAuth za delovanje v vašem imenu ste preklicali.',
	'mwoauth-grant-group-page-interaction' => 'Interakcija s stranmi',
	'mwoauth-grant-group-file-interaction' => 'Interakcija z mediji',
	'mwoauth-grant-group-watchlist-interaction' => 'Interakcija z vašim spiskom nadzorov',
	'mwoauth-grant-group-email' => 'Pošljite e-pošto',
	'mwoauth-grant-group-high-volume' => 'Izvedi visokoštevilsko dejanje',
	'mwoauth-grant-group-customization' => 'Prilagoditve in nastavitve',
	'mwoauth-grant-group-administration' => 'Izvajanje administrativnih dejanj',
	'mwoauth-grant-group-other' => 'Druga dejavnost',
	'mwoauth-grant-createaccount' => 'Ustvarite račune',
	'mwoauth-grant-patrol' => 'Nadzor sprememb strani',
	'mwoauth-grant-rollback' => 'Razveljavitev sprememb strani',
	'mwoauth-grant-sendemail' => 'Pošiljanje e-pošte drugim uporabnikom',
	'mwoauth-oauth-exception' => 'Napaka v protokolu OAuth: $1',
);

/** Serbian (Cyrillic script) (српски (ћирилица)‎)
 * @author Milicevic01
 */
$messages['sr-ec'] = array(
	'mwoauth-field-hidden' => '(ова информација је скривена)',
	'mwoauth-field-private' => '(ова информација је приватна)',
);

/** Swedish (svenska)
 * @author Eihpossophie
 * @author Jopparn
 * @author Skalman
 * @author WikiPhoenix
 */
$messages['sv'] = array(
	'mwoauth-desc' => 'OAuth 1.0a API-tillstånd',
	'mwoauth-missing-field' => 'Saknar värde för "$1"-fältet',
	'mwoauth-invalid-field' => 'Ogiltigt värde angett för "$1"-fältet',
	'mwoauth-invalid-field-generic' => 'Ogiltigt värde angivet',
	'mwoauth-field-hidden' => '(denna information är dold)',
	'mwoauth-field-private' => '(denna information är privata)',
	'mwoauth-prefs-managegrants' => 'Anslutna appar:',
	'mwoauth-prefs-managegrantslink' => 'Hantera $1 {{PLURAL:$1|ansluten applikation|anslutna applikationer}}',
	'mwoauth-consumer-allwikis' => 'Alla projekt på denna webbplats',
	'mwoauth-consumer-key' => 'Konsumentnyckel:',
	'mwoauth-consumer-name' => 'Applikationsnamn:',
	'mwoauth-consumer-version' => 'Konsumentversion:',
	'mwoauth-consumer-user' => 'Utgivare:',
	'mwoauth-consumer-stage' => 'Aktuell status:',
	'mwoauth-consumer-email' => 'E-postadress:',
	'mwoauth-consumer-description' => 'Beskrivning av applikationen',
	'mwoauth-consumer-callbackurl' => 'OAuth "callback"-URL:',
	'mwoauth-consumer-grantsneeded' => 'Tillämpliga stipendium:',
	'mwoauth-consumer-required-grant' => 'Tillämplig för konsumenten',
	'mwoauth-consumer-wiki' => 'Tillämplig wiki:',
	'mwoauth-consumer-restrictions' => 'Användningsbegränsningar:',
	'mwoauth-consumer-restrictions-json' => 'Användningsbegränsningar (JSON):',
	'mwoauth-consumer-rsakey' => 'Offentlig RSA-nyckel:',
	'mwoauth-consumer-secretkey' => 'Konsumentens hemliga token:',
	'mwoauth-consumer-accesstoken' => 'Åtkomst-token:',
	'mwoauth-consumer-reason' => 'Orsak:',
	'mwoauth-consumer-email-unconfirmed' => 'Din e-postadress till kontot har ännu inte bekräftats.',
	'mwoauth-consumer-email-mismatched' => 'Den angivna e-postadressen måste matcha den som är kopplad till ditt konto.',
	'mwoauth-consumer-alreadyexists' => 'En konsument med denna kombination av namn/version/utgivare finns redan',
	'mwoauth-consumer-alreadyexistsversion' => 'En konsument med denna kombination av namn/utgivare finns redan med en likvärdig eller högre version ("$1")',
	'mwoauth-consumer-not-proposed' => 'Konsumenten föreslås inte för närvarande',
	'mwoauth-consumer-not-disabled' => 'Konsumenten är inte inaktiverad för närvarande',
	'mwoauth-consumer-not-approved' => 'Konsumenten inte är godkänd (den kan ha inaktiverats)',
	'mwoauth-missing-consumer-key' => 'Ingen konsumentnyckel angavs.',
	'mwoauth-invalid-consumer-key' => 'Ingen konsument finns med den nyckel.',
	'mwoauth-invalid-access-token' => 'Ingen åtkomst-token finns med den nyckeln.',
	'mwoauth-invalid-access-wrongwiki' => 'Konsumenten kan endast användas på wiki "$1".',
	'mwoauth-consumer-conflict' => 'Någon ändrat attributen för denna konsument när du tittade på den. Vänligen försök igen. Du kanske vill ta en titt på ändringsloggen.',
	'mwoauth-consumer-stage-proposed' => 'föreslagna',
	'mwoauth-consumer-stage-rejected' => 'avvisade',
	'mwoauth-consumer-stage-expired' => 'utgångna',
	'mwoauth-consumer-stage-approved' => 'godkända',
	'mwoauth-consumer-stage-disabled' => 'inaktiverade',
	'mwoauth-consumer-stage-suppressed' => 'undertryckta',
	'mwoauthconsumerregistration' => 'OAuth konsumentenregistrering',
	'mwoauthconsumerregistration-notloggedin' => 'Du behöver vara inloggad för att komma åt denna sida.',
	'mwoauthconsumerregistration-navigation' => 'Navigering:',
	'mwoauthconsumerregistration-propose' => 'Föreslå ny kund',
	'mwoauthconsumerregistration-list' => 'Min konsumentlista',
	'mwoauthconsumerregistration-main' => 'Huvudsida',
	'mwoauthconsumerregistration-update-text' => 'Använd formuläret nedan för att uppdatera delar av en OAuth-konsument du styr.

Alla värden här skriver över eventuella tidigare värden. Lämna inte tomma fält om du inte tänker ta bort dessa värden.',
	'mwoauthconsumerregistration-propose-legend' => 'Ny OAuth-konsumentapplikation',
	'mwoauthconsumerregistration-update-legend' => 'Uppdatera OAuth-konsumentapplikation',
	'mwoauthconsumerregistration-propose-submit' => 'Föreslå konsument',
	'mwoauthconsumerregistration-update-submit' => 'Uppdatera konsument',
	'mwoauthconsumerregistration-none' => 'Du kontrollerar inte några OAuth-konsumenter.',
	'mwoauthconsumerregistration-name' => 'Konsument',
	'mwoauthconsumerregistration-user' => 'Utgivare',
	'mwoauthconsumerregistration-description' => 'Beskrivning',
	'mwoauthconsumerregistration-email' => 'Kontakt email',
	'mwoauthconsumerregistration-consumerkey' => 'Konsumentnyckel',
	'mwoauthconsumerregistration-stage' => 'Status',
	'mwoauthconsumerregistration-lastchange' => 'Senaste ändringen',
	'mwoauthconsumerregistration-manage' => 'hantera',
	'mwoauthconsumerregistration-resetsecretkey' => 'Återställ den hemliga nyckeln till ett nytt värde',
	'mwoauthconsumerregistration-proposed' => "Du har tilldelats konsumenttoken av '''$1''' och en hemlig token av '''$2'''. ''Vänligen anteckna dessa för framtida bruk.''",
	'mwoauthconsumerregistration-updated' => 'Ditt OAuth konsumentregister uppdaterades framgångsrikt',
	'mwoauthconsumerregistration-secretreset' => "Du har blivit tilldelad en konsumenttoken av '''$1'''. ''Vänligen anteckna detta för framtida bruk.''",
	'mwoauthmanageconsumers' => 'Hantera OAuthkonsumenter',
	'mwoauthmanageconsumers-notloggedin' => 'Du behöver vara inloggad för att komma åt denna sida.',
	'mwoauthmanageconsumers-type' => 'Köer:',
	'mwoauthmanageconsumers-showproposed' => 'Föreslagna ansökningar',
	'mwoauthmanageconsumers-showrejected' => 'Avslagna ansökningar',
	'mwoauthmanageconsumers-showexpired' => 'Utgångna ansökningar',
	'mwoauthmanageconsumers-main' => 'Huvudsida',
	'mwoauthmanageconsumers-maintext' => 'Denna sida är ämnad för hanteringen av OAuths (se http://oauth.net) konsumentapplikationsförfrågningar samt hanteringen av etablerade OAuth konsumenter.',
	'mwoauthmanageconsumers-queues' => 'Välj konsumentbekräftelsekö nedan:',
	'mwoauthmanageconsumers-q-proposed' => 'Kö med föreslagna konsumentförfrågningar',
	'mwoauthmanageconsumers-q-rejected' => 'Kö med avslagna konsumentförfrågningar',
	'mwoauthmanageconsumers-q-expired' => 'Kö med utgånga konsumentförfrågningar',
	'mwoauthmanageconsumers-lists' => 'Välj en konsumentstatuslista nedan:',
	'mwoauthmanageconsumers-l-approved' => 'Lista över nyligen godkända konsumenter',
	'mwoauthmanageconsumers-l-disabled' => 'Lista över nyligen avaktiverade konsumenter',
	'mwoauthmanageconsumers-none-proposed' => 'Inga föreslagna konsumenter i denna lista.',
	'mwoauthmanageconsumers-none-rejected' => 'Inga föreslagna konsumenter i denna lista.',
	'mwoauthmanageconsumers-none-expired' => 'Inga föreslagna konsumenter i denna lista.',
	'mwoauthmanageconsumers-none-approved' => 'Inga konsumenter möter detta kriterium.',
	'mwoauthmanageconsumers-none-disabled' => 'Inga konsumenter möter detta kriterium.',
	'mwoauthmanageconsumers-name' => 'Konsument',
	'mwoauthmanageconsumers-user' => 'Utgivare',
	'mwoauthmanageconsumers-description' => 'Beskrivning',
	'mwoauthmanageconsumers-email' => 'E-post kontakt',
	'mwoauthmanageconsumers-consumerkey' => 'Konsumentnyckel',
	'mwoauthmanageconsumers-lastchange' => 'Senaste ändringen',
	'mwoauthmanageconsumers-review' => 'granska/hantera',
	'mwoauthmanageconsumers-confirm-text' => 'Använd detta formulär för att godkänna, avvisa, inaktivera eller återaktivera denna konsument.',
	'mwoauthmanageconsumers-confirm-legend' => 'Hantera OAuthkonsument',
	'mwoauthmanageconsumers-action' => 'Ändra status:',
	'mwoauthmanageconsumers-approve' => 'Godkänd',
	'mwoauthmanageconsumers-reject' => 'Avvisad',
	'mwoauthmanageconsumers-rsuppress' => 'Avvisade och undertryckta',
	'mwoauthmanageconsumers-disable' => 'Inaktiverad',
	'mwoauthmanageconsumers-dsuppress' => 'Inaktiverade och undertryckta',
	'mwoauthmanageconsumers-reenable' => 'Godkänd',
	'mwoauthmanageconsumers-reason' => 'Orsak:',
	'mwoauthmanageconsumers-confirm-submit' => 'Uppdatera konsumentstatus',
	'mwoauthmanageconsumers-viewing' => 'Användare "$1"  tittar på denna konsument för tillfället',
	'mwoauthmanageconsumers-success-approved' => 'Begäran har godkänts.',
	'mwoauthmanageconsumers-success-rejected' => 'Begäran har avslagits.',
	'mwoauthmanageconsumers-success-disabled' => 'Konsumenten har inaktiverats.',
	'mwoauthmanageconsumers-success-reanable' => 'Konsumenten har återaktiverats.',
	'mwoauthmanageconsumers-search-name' => 'Konsument med detta namn',
	'mwoauthmanageconsumers-search-publisher' => 'Konsumenter av denna användare',
	'mwoauthlistconsumers' => 'Lista OAuthkonsumenter',
	'mwoauthlistconsumers-legend' => 'Sök efter OAuthkonsumenter',
	'mwoauthlistconsumers-view' => 'detaljer',
	'mwoauthlistconsumers-none' => 'Inget konsumenter hittade som uppfyller detta kriterium.',
	'mwoauthlistconsumers-name' => 'Applikationsnamn',
	'mwoauthlistconsumers-version' => 'Konsumentversion',
	'mwoauthlistconsumers-user' => 'Utgivare',
	'mwoauthlistconsumers-description' => 'Beskrivning',
	'mwoauthlistconsumers-wiki' => 'Tillämplig wiki',
	'mwoauthlistconsumers-callbackurl' => 'OAuth "callback URL"',
	'mwoauthlistconsumers-grants' => 'Tillämpliga bidrag',
	'mwoauthlistconsumers-basicgrantsonly' => '(endast grundläggande tillgång)',
	'mwoauthlistconsumers-status' => 'Status',
	'mwoauth-consumer-stage-any' => 'alla',
	'mwoauthlistconsumers-status-proposed' => 'föreslagna',
	'mwoauthlistconsumers-status-approved' => 'godkända',
	'mwoauthlistconsumers-status-disabled' => 'inaktiverade',
	'mwoauthlistconsumers-status-rejected' => 'avvisad',
	'mwoauthlistconsumers-status-expired' => 'utgången',
	'mwoauthmanagemygrants' => 'Hantera konto OAuthsbidrag',
	'mwoauthmanagemygrants-text' => 'Denna sida listar alla applikationer som kan använda ditt konto. För varje sådan applikation är dess tillträde begränsat av de behörigheter vilka du auktoriserade när du valde att låta den agera åt dina vägnar. Om du separat auktoriserar en konsument att tillgå olika systerprojekt åt dina vägnar kommer du se separat konfiguration för varje sådant projekt nedan.',
	'mwoauthmanagemygrants-notloggedin' => 'Du måste vara inloggad för att komma åt denna sida.',
	'mwoauthmanagemygrants-navigation' => 'Navigering:',
	'mwoauthmanagemygrants-showlist' => 'Accepterad konsumentlista',
	'mwoauthmanagemygrants-none' => 'Inga applikationer är för närvarande anslutna till ditt konto.',
	'mwoauthmanagemygrants-name' => 'Konsumentnamn',
	'mwoauthmanagemygrants-user' => 'Utgivare',
	'mwoauthmanagemygrants-description' => 'Beskrivning',
	'mwoauthmanagemygrants-wiki' => 'Tillämplig wiki',
	'mwoauthmanagemygrants-wikiallowed' => 'Tillåten på wiki',
	'mwoauthmanagemygrants-grants' => 'Tillämpliga stipendier',
	'mwoauthmanagemygrants-grantsallowed' => 'Stipendier tillåtna',
	'mwoauthmanagemygrants-applicablegrantsallowed' => 'Tillämpliga bidrag accepteras:',
	'mwoauthmanagemygrants-consumerkey' => 'Konsumentnyckel',
	'mwoauthmanagemygrants-review' => 'hantera åtkomst',
	'mwoauthmanagemygrants-revoke' => 'återkalla åtkomst',
	'mwoauthmanagemygrants-grantaccept' => 'Beviljas',
	'mwoauthmanagemygrants-update-text' => 'Använd formuläret nedan för att ändra de behörigheter som beviljats för en applikation (OAuth konsument)  att agera åt dina vägnar. 
* Om du separat auktoriserat en applikation för att tillgå olika systerprojekt åt dina vägnar har du separata konfigurationer för varje sådant projekt för den applikationen.
* Att använda "*" i wikifältet ger tillgång till alla projekt på denna webbplats: att använda ett wikiprojektID begränsar åtkomst till ett enskilt projekt. Förinställda projektinställningar har företräde.', # Fuzzy
	'mwoauthmanagemygrants-revoke-text' => 'Använd formuläret nedan för att återkalla åtkomst för en applikation (OAuth konsument) att agera åt dina vägnar. 
* Om du separat har auktoriserat en applikation för att få åtkomst till ett annat systerprojekt åt dina vägnar så kommer du att ha separata konfigurationer för varje enskilt projekt för den applikationen.
* Om du helt vill återkalla åtkomst till en applikation, se till att återkalla den från alla projekt där du accepterat den.',
	'mwoauthmanagemygrants-confirm-legend' => 'Hantera konsumentåtkomst-token',
	'mwoauthmanagemygrants-update' => 'Uppdatera bidrag',
	'mwoauthmanagemygrants-renounce' => 'Avauktorisera',
	'mwoauthmanagemygrants-action' => 'Ändra status:',
	'mwoauthmanagemygrants-confirm-submit' => 'Uppdatera åtkomsttokenstatus',
	'mwoauthmanagemygrants-success-update' => 'Åtkomst-token för denna konsument har uppdaterats.',
	'mwoauthmanagemygrants-success-renounce' => 'Åtkomst-token för denna konsument har tagits bort.',
	'logentry-mwoauthconsumer-propose' => '$1 {{GENDER:$2|Föreslog}} en OAuthkonsument (konsumentnyckel $4)',
	'logentry-mwoauthconsumer-update' => '$1 {{GENDER:$2|uppdaterade}} en OAuthkonsument (konsumentnyckel $4)',
	'logentry-mwoauthconsumer-approve' => '$1 {{GENDER:$2|Godkände}} en OAuthkonsument av $3 (konsumentnyckel $4)',
	'logentry-mwoauthconsumer-reject' => '$1 {{GENDER:$2|Avslog}} en OAuthkonsument av $3 (konsumentnyckel $4)',
	'logentry-mwoauthconsumer-disable' => '$1 {{GENDER:$2|Inaktiverade}} en OAuthkonsument av $3 (konsumentnyckel $4)',
	'logentry-mwoauthconsumer-reenable' => '$1 {{GENDER:$2|Återaktiverade}} en OAuthkonsument av $3 (konsumentnyckel $4)',
	'mwoauthconsumer-consumer-logpage' => 'OAuthkonsumentslogg',
	'mwoauthconsumer-consumer-logpagetext' => 'Logg över godkännanden, avslag och inaktivering av registrerade OAuthkonsumenter.',
	'mwoauth-bad-request' => 'Det uppstod ett fel i din OAuthbegäran.',
	'mwoauthdatastore-access-token-not-found' => 'Inget godkänt bidrag har hittats för den auktoriseringstoken.',
	'mwoauthdatastore-request-token-not-found' => 'Ingen begäran hittades för den token.',
	'mwoauthdatastore-bad-token' => 'Ingen token hittades som matchade din begäran.',
	'mwoauthdatastore-bad-verifier' => 'Verifikationskoden som givits var inte giltig.',
	'mwoauthdatastore-invalid-token-type' => 'Den begärda tokentypen är ogiltig.',
	'mwoauthgrants-general-error' => 'Det uppstod ett fel i din OAuthbegäran.',
	'mwoauthserver-bad-consumer' => 'Ingen godkänd konsument hittas för den nyckel du angav.',
	'mwoauthserver-insufficient-rights' => 'Du har inte tillräcklig behörighet för att utföra denna åtgärd.',
	'mwoauthserver-invalid-request-token' => 'Ogiltig token i din begäran.',
	'mwoauthserver-invalid-user-hookabort' => 'Denna användare kan inte använda OAuth.',
	'mwoauth-invalid-authorization-title' => 'OAuth auktoriseringsfel',
	'mwoauth-invalid-authorization' => 'Auktoriseringsrubriker i din begäran är inte giltiga: $1',
	'mwoauth-invalid-authorization-wrong-wiki' => 'De auktoriserade rubrikerna i din begäran är inte giltiga för $1',
	'mwoauth-invalid-authorization-invalid-user' => 'Auktoriseringsrubrikerna i din begäran är för användare som inte existerar här',
	'mwoauth-invalid-authorization-wrong-user' => 'Auktoriseringsrubrikerna i din begäran är för en annan användare',
	'mwoauth-invalid-authorization-not-approved' => 'Auktoriseringsrubrikerna i din begäran är för en OAuthkonsument som för närvarande inte är godkänd',
	'mwoauth-invalid-authorization-blocked-user' => 'Auktoriseringsrubrikerna i din begäran är för en användare som är blockerad',
	'mwoauth-form-description-allwikis' => "Hej $1,
'''$2''' skulle vilja göra följande åtgärder åt dina vägnar på alla projekt på denna sida:

$4",
	'mwoauth-form-description-onewiki' => "Hej $1,

'''$2'''' skulle vilja göra följande åtgärder åt dina vägnar på '''$4''':

$5",
	'mwoauth-form-description-allwikis-nogrants' => "Hej $1,

'''$2''' skulle vilka ha grundläggande åtkomst åt dina vägnar för alla projekt på denna sida.",
	'mwoauth-form-description-onewiki-nogrants' => "Hej $1,

'''$2''' önskar få grundläggande åtkomst åt dina vägnar på '''$4'''.",
	'mwoauth-form-privacypolicy-link' => '[[{{ns:Project}}:Privacy policy|Integritetspolicy]]',
	'mwoauth-form-button-approve' => 'Tillåt',
	'mwoauth-form-button-cancel' => 'Avbryt',
	'mwoauth-authorize-form-invalid-user' => 'Detta användarkonto kan inte nyttja OAuth då kontot på denna wiki och kontot på den centrala OAuth wikin inte är sammankopplade.',
	'mwoauth-error' => 'OAuth error',
	'mwoauth-grants-heading' => 'Begärda tillstånd:',
	'mwoauth-grants-nogrants' => 'Ansökan har inte begärt något tillstånd.',
	'mwoauth-acceptance-cancelled' => 'Du har avbrutit denna begäran att auktorisera en OAuthkonsument att agera åt dina vägnar.',
	'mwoauth-grant-group-page-interaction' => 'Interagera med sidor',
	'mwoauth-grant-group-file-interaction' => 'Interagera med media',
	'mwoauth-grant-group-watchlist-interaction' => 'Interagera med din bevakningslista',
	'mwoauth-grant-group-email' => 'Skicka e-post',
	'mwoauth-grant-group-high-volume' => 'Utför hög volymaktivitet',
	'mwoauth-grant-group-customization' => 'Anpassning och inställningar',
	'mwoauth-grant-group-administration' => 'Utför administrativa åtgärder',
	'mwoauth-grant-group-other' => 'Diverseaktivitet',
	'mwoauth-grant-blockusers' => 'Blockera och avblockera användare',
	'mwoauth-grant-createaccount' => 'Skapa konton',
	'mwoauth-grant-createeditmovepage' => 'Skapa, redigera och flytta sidor',
	'mwoauth-grant-delete' => 'Ta bort sidor, revideringar och loggposter',
	'mwoauth-grant-editinterface' => 'Redigera MediaWiki-namnrymden och CSS/JS för användaren',
	'mwoauth-grant-editmycssjs' => 'Redigera din CSS/JS för din egen användare',
	'mwoauth-grant-editmywatchlist' => 'Redigera din bevakningslista',
	'mwoauth-grant-editpage' => 'Redigera befintliga sidor',
	'mwoauth-grant-editprotected' => 'Redigera skyddade sidor',
	'mwoauth-grant-highvolume' => 'Högvolymsredigering',
	'mwoauth-grant-oversight' => 'Dölj användare och undertryck revideringar',
	'mwoauth-grant-patrol' => 'Patrullera ändringar på sidor',
	'mwoauth-grant-protect' => 'Skydda och ta bort skydd på sidor',
	'mwoauth-grant-rollback' => 'Rulla tillbaka ändringar på sidor',
	'mwoauth-grant-sendemail' => 'Skicka e-post till andra användare',
	'mwoauth-grant-uploadeditmovefile' => 'Ladda upp, byt och flytta filer',
	'mwoauth-grant-uploadfile' => 'Ladda upp nya filer',
	'mwoauth-grant-useoauth' => 'Grundläggande rättigheter',
	'mwoauth-grant-viewdeleted' => 'Visa raderad information',
	'mwoauth-grant-viewmywatchlist' => 'Visa din bevakningslista',
	'mwoauth-oauth-exception' => 'Ett fel uppstod i OAuth-protokollet: $1',
	'mwoauth-callback-not-oob' => 'oauth_callback måste anges och måste ställas in på "oob" (skiftlägeskänslig)',
	'right-mwoauthproposeconsumer' => 'Föreslå nya OAuth-konsumenter',
	'right-mwoauthupdateownconsumer' => 'Uppdatera OAuth-konsumenter du styr',
	'right-mwoauthmanageconsumer' => 'Hantera OAuth-konsumenter',
	'right-mwoauthsuppress' => 'Undertryck OAuth-konsumenter',
	'right-mwoauthviewsuppressed' => 'Visa undertryckta OAuth-konsumenter',
	'right-mwoauthviewprivate' => 'Visa privat OAuth-data',
	'right-mwoauthmanagemygrants' => 'Hantera OAuthbidrag',
	'action-mwoauthmanageconsumer' => 'Hantera OAuthkonsument',
	'action-mwoauthmanagemygrants' => 'Hantera dina OAuthbidrag',
	'action-mwoauthproposeconsumer' => 'Föreslå nya OAuthkonsument',
	'action-mwoauthupdateownconsumer' => 'Uppdatera OAuthkonsumenter du kontrollerar',
	'action-mwoauthviewsuppressed' => 'Visa upphävda OAuthkonsumenter',
);

/** Turkish (Türkçe)
 * @author Rapsar
 */
$messages['tr'] = array(
	'mwoauth-form-button-approve' => 'Evet, izin ver', # Fuzzy
);

/** Ukrainian (українська)
 * @author Andriykopanytsia
 * @author Ата
 */
$messages['uk'] = array(
	'mwoauth-desc' => 'OAuth 1.0a API авторизація',
	'mwoauth-verified' => "Цій програмі зараз дозволений доступ до Медіавікі від вашого імені.

Для завершення процесу надайте це перевірене значення програмі: '''$1'''",
	'mwoauth-missing-field' => 'Відсутнє значення для поля "$1"',
	'mwoauth-invalid-field' => 'Неприпустиме значення для поля "$1"',
	'mwoauth-invalid-field-generic' => 'Надано неприпустиме значення',
	'mwoauth-field-hidden' => '(ця інформація прихована)',
	'mwoauth-field-private' => '(ця інформація є конфіденційною)',
	'mwoauth-grant-generic' => 'Пучок прав "$1"',
	'mwoauth-prefs-managegrants' => 'Підключені додатки:',
	'mwoauth-prefs-managegrantslink' => "Управляти $1 {{PLURAL:$1|з'єднаною програмою|з'єднаними програмами}}",
	'mwoauth-consumer-allwikis' => 'Всі проекти на цьому сайті',
	'mwoauth-consumer-key' => 'Ключ споживача:',
	'mwoauth-consumer-name' => 'Назва програми:',
	'mwoauth-consumer-version' => 'Споживча версія:',
	'mwoauth-consumer-user' => 'Видавець:',
	'mwoauth-consumer-stage' => 'Поточний статус:',
	'mwoauth-consumer-email' => 'Контактна адреса електронної пошти:',
	'mwoauth-consumer-description' => 'Опис програми:',
	'mwoauth-consumer-callbackurl' => 'URL "зворотнього виклику" OAuth :',
	'mwoauth-consumer-grantsneeded' => 'Придатні гранти:',
	'mwoauth-consumer-required-grant' => 'Застосовні до споживача',
	'mwoauth-consumer-wiki' => 'Придатні вікі:',
	'mwoauth-consumer-wiki-thiswiki' => 'Поточне вікі ($1)',
	'mwoauth-consumer-wiki-other' => 'Конкретне вікі',
	'mwoauth-consumer-restrictions' => 'Обмеження на використання:',
	'mwoauth-consumer-restrictions-json' => 'Обмеження на користування (JSON):',
	'mwoauth-consumer-rsakey' => 'Відкритий ключ RSA:',
	'mwoauth-consumer-secretkey' => 'Секретний маркер споживача:',
	'mwoauth-consumer-accesstoken' => 'Маркер доступу:',
	'mwoauth-consumer-reason' => 'Причина:',
	'mwoauth-consumer-email-unconfirmed' => 'Ваша адреса електронної пошти облікового запису ще не підтверджена.',
	'mwoauth-consumer-email-mismatched' => 'Вказана адреса електронної пошти повинна відповідати вашому обліковому запису.',
	'mwoauth-consumer-alreadyexists' => 'Споживач з таким поєднанням імен/версії/видавця вже існує',
	'mwoauth-consumer-alreadyexistsversion' => 'Споживач з таким поєднанням імені/видавця вже існує з тою самою чи новішою версією  ("$1")',
	'mwoauth-consumer-not-accepted' => 'Не вдалося оновити відомості для запиту на очікуваного споживача',
	'mwoauth-consumer-not-proposed' => 'Споживач не пропонується в даний час',
	'mwoauth-consumer-not-disabled' => 'Споживач в даний час невимкнений',
	'mwoauth-consumer-not-approved' => 'Споживач незатверджений (це може було вимкнено)',
	'mwoauth-missing-consumer-key' => 'Немає наданого ключа споживача.',
	'mwoauth-invalid-consumer-key' => 'Не існує споживача з даним ключем.',
	'mwoauth-invalid-access-token' => 'Немає маркера доступу з даним ключем.',
	'mwoauth-invalid-access-wrongwiki' => 'Споживач може використовуватися тільки на вікі "$1".',
	'mwoauth-consumer-conflict' => 'Хтось змінив параметри даного споживача, якого ви дивилися. Будь ласка, спробуйте ще раз. Ви можете перевірити журнал змін.',
	'mwoauth-consumer-stage-proposed' => 'запропоновано',
	'mwoauth-consumer-stage-rejected' => 'відхилено',
	'mwoauth-consumer-stage-expired' => 'застаріле',
	'mwoauth-consumer-stage-approved' => 'затверджено',
	'mwoauth-consumer-stage-disabled' => 'вимкнено',
	'mwoauth-consumer-stage-suppressed' => 'пригнічено',
	'mwoauthconsumerregistration' => 'Реєстрація покупця OAuth',
	'mwoauthconsumerregistration-notloggedin' => 'Ви повинні увійти в систему для доступу до цієї сторінки.',
	'mwoauthconsumerregistration-navigation' => 'Навігація:',
	'mwoauthconsumerregistration-propose' => 'Запропонувати нового споживача',
	'mwoauthconsumerregistration-list' => 'Мій список споживачів',
	'mwoauthconsumerregistration-main' => 'Головна',
	'mwoauthconsumerregistration-propose-text' => 'Розробники можуть використовувати форму нижче, щоб запропонувати нового споживача OAuth (див. [//www.mediawiki.org/wiki/Extension:OAuth extension documentation] за подробицями). Після надсилання цієї форми ви отримаєте маркер для вашої програми, який буде використовуватися нею при ідентифікації на Медіавікі. Адміністратор OAuth повинен затвердити вашу програму, перш ніж вона буде авторизована іншими користувачами.

Кілька рекомендацій і зауважень:
* Постарайтеся використовувати якомога менше дозволів за можливості. Уникайте дозволів, які насправді не потрібні зараз.
* Версії мають форму "major.minor.release" (останні дві необов\'язкові) і вони збільшуються, якщо необхідні зміни дозволів.
* Будь ласка, вкажіть відкритий ключ RSA (у форматі PEM), якщо можливо; в іншому випадку (менш безпечно) таємний маркер повинен використовуватися.
* Використовуйте обмеження поля JSON  для обмеження доступу споживача до IP-адрес в тих  діапазонах CIDR.
* Ви можете використовувати ідентифікатор вікі, аби обмежувати споживача в одному вікі-проекті на цьому сайті (використовуйте "*" для всіх вікі).
* Надана адреса електронної пошти повинна збігатися з вашим обліковим записом (який повинен бути підтвердженим).',
	'mwoauthconsumerregistration-update-text' => 'Використовуйте форму нижче, щоб оновити аспекти споживача OAuth, які ви контролюєте.

Всі значення тут будуть переписувати будь-які попередні. Не залишайте порожні поля, якщо ви не маєте наміру вилучити ці значення.',
	'mwoauthconsumerregistration-maintext' => 'Ця сторінка дозволяє розробникам пропонувати та оновлювати клієнтські програми OAuth у реєстрі сайту.

Звідси ви можете:
* [[Special:MWOAuthConsumerRegistration/propose|Запитувати маркер для нового клієнта]].
* [[Special:MWOAuthConsumerRegistration/list|Управляти вашими наявними клієнтами]].

For more information about OAuth, please see the [//www.mediawiki.org/wiki/Extension: документацція про розширення OAuth].',
	'mwoauthconsumerregistration-propose-legend' => 'Нова програма OAuth споживача',
	'mwoauthconsumerregistration-update-legend' => 'Оновити програму споживача OAuth',
	'mwoauthconsumerregistration-propose-submit' => 'Запропонувати споживача',
	'mwoauthconsumerregistration-update-submit' => 'Оновити споживача',
	'mwoauthconsumerregistration-none' => 'Ви не контролюєте жодного споживача OAuth.',
	'mwoauthconsumerregistration-name' => 'Споживач',
	'mwoauthconsumerregistration-user' => 'Видавець',
	'mwoauthconsumerregistration-description' => 'Опис',
	'mwoauthconsumerregistration-email' => 'Адреса ел. пошти',
	'mwoauthconsumerregistration-consumerkey' => 'Ключ споживача',
	'mwoauthconsumerregistration-stage' => 'Статус',
	'mwoauthconsumerregistration-lastchange' => 'Остання зміна',
	'mwoauthconsumerregistration-manage' => 'керування',
	'mwoauthconsumerregistration-resetsecretkey' => 'Очистити секретний ключ до нового значення',
	'mwoauthconsumerregistration-proposed' => "Ваш запит споживача OAuth був отриманий.

Вам призначений маркер споживача ''\$1''' і таємний маркер '''\$2'''. \"Будь ласка, запишіть їх для використання в майбутньому.\"",
	'mwoauthconsumerregistration-updated' => 'Ваш реєстр споживача OAuth успішно оновлено.',
	'mwoauthconsumerregistration-secretreset' => "Вам призначений секретний маркер споживача '''$1'''. „Будь ласка, запишіть це на майбутнє“.",
	'mwoauthmanageconsumers' => 'Управління споживачами OAuth',
	'mwoauthmanageconsumers-notloggedin' => 'Ви повинні увійти в систему для доступу до цієї сторінки.',
	'mwoauthmanageconsumers-type' => 'Черги:',
	'mwoauthmanageconsumers-showproposed' => 'Запропоновані запити',
	'mwoauthmanageconsumers-showrejected' => 'Відхилені запити',
	'mwoauthmanageconsumers-showexpired' => 'Застарілі запити',
	'mwoauthmanageconsumers-main' => 'Головна',
	'mwoauthmanageconsumers-maintext' => 'Ця сторінка призначена для обробки запитів споживачів програми OAuth (див. http://oauth.net) і управління встановленими споживачами OAuth.',
	'mwoauthmanageconsumers-queues' => 'Оберіть чергу підтвердження запитів споживача нижче:',
	'mwoauthmanageconsumers-q-proposed' => 'Черга пропонованих запитів споживачів',
	'mwoauthmanageconsumers-q-rejected' => 'Черга відхилених запитів споживачів',
	'mwoauthmanageconsumers-q-expired' => 'Черга протермінованих запитів споживачів',
	'mwoauthmanageconsumers-lists' => 'Виберіть статус споживача з списку нижче:',
	'mwoauthmanageconsumers-l-approved' => 'Список на даний час затверджених споживачів',
	'mwoauthmanageconsumers-l-disabled' => 'Перелік наразі вимкнених споживачів',
	'mwoauthmanageconsumers-none-proposed' => 'Немає запропонованих споживачів в цьому списку.',
	'mwoauthmanageconsumers-none-rejected' => 'Немає запропонованих споживачів в цьому списку.',
	'mwoauthmanageconsumers-none-expired' => 'Немає запропонованих споживачів в цьому списку.',
	'mwoauthmanageconsumers-none-approved' => 'Жодний споживач не відповідає цьому критерію.',
	'mwoauthmanageconsumers-none-disabled' => 'Жодний споживач не відповідає цьому критерію.',
	'mwoauthmanageconsumers-name' => 'Споживач',
	'mwoauthmanageconsumers-user' => 'Видавець',
	'mwoauthmanageconsumers-description' => 'Опис',
	'mwoauthmanageconsumers-email' => 'Контактна адреса електронної пошти',
	'mwoauthmanageconsumers-consumerkey' => 'Ключ споживача',
	'mwoauthmanageconsumers-lastchange' => 'Остання зміна',
	'mwoauthmanageconsumers-review' => 'перевірка/керування',
	'mwoauthmanageconsumers-confirm-text' => 'Використовуйте цю форму, щоб затвердити, відхилити, вимкнути або повторно увімкнути цього споживача.',
	'mwoauthmanageconsumers-confirm-legend' => 'Управління споживачем OAuth',
	'mwoauthmanageconsumers-action' => 'Змінити статус:',
	'mwoauthmanageconsumers-approve' => 'Затверджено',
	'mwoauthmanageconsumers-reject' => 'Відхилено',
	'mwoauthmanageconsumers-rsuppress' => 'Відхилено і пригнічено',
	'mwoauthmanageconsumers-disable' => 'Вимкнено',
	'mwoauthmanageconsumers-dsuppress' => 'Вимкнено і пригнічено',
	'mwoauthmanageconsumers-reenable' => 'Затверджено',
	'mwoauthmanageconsumers-reason' => 'Причина:',
	'mwoauthmanageconsumers-confirm-submit' => 'Оновити статус споживача',
	'mwoauthmanageconsumers-viewing' => 'Користувач " $1 " в даний час переглядає цього споживача',
	'mwoauthmanageconsumers-success-approved' => 'Запит був схвалений.',
	'mwoauthmanageconsumers-success-rejected' => 'Запит був відхилений.',
	'mwoauthmanageconsumers-success-disabled' => 'Споживач вже вимкнений.',
	'mwoauthmanageconsumers-success-reanable' => 'Споживач вже повторно увімкнений.',
	'mwoauthmanageconsumers-search-name' => 'споживачі з цим іменем',
	'mwoauthmanageconsumers-search-publisher' => 'споживачі даного користувача',
	'mwoauthlistconsumers' => 'Список споживачів OAuth',
	'mwoauthlistconsumers-legend' => 'Перегляд OAuth споживачів',
	'mwoauthlistconsumers-view' => 'подробиці',
	'mwoauthlistconsumers-none' => 'Не знайдено жодного споживача за цим критерієм.',
	'mwoauthlistconsumers-name' => 'Назва програми',
	'mwoauthlistconsumers-version' => 'Споживча версія',
	'mwoauthlistconsumers-user' => 'Видавець',
	'mwoauthlistconsumers-description' => 'Опис',
	'mwoauthlistconsumers-wiki' => 'Застосовне вікі',
	'mwoauthlistconsumers-callbackurl' => 'URL "зворотнього виклику" OAuth',
	'mwoauthlistconsumers-grants' => 'Застосовні ґранти',
	'mwoauthlistconsumers-basicgrantsonly' => '(тільки для базового доступу)',
	'mwoauthlistconsumers-status' => 'Статус',
	'mwoauth-consumer-stage-any' => 'будь-яка',
	'mwoauthlistconsumers-status-proposed' => 'запропоновано',
	'mwoauthlistconsumers-status-approved' => 'затверджено',
	'mwoauthlistconsumers-status-disabled' => 'вимкнено',
	'mwoauthlistconsumers-status-rejected' => 'відхилено',
	'mwoauthlistconsumers-status-expired' => 'застаріле',
	'mwoauthmanagemygrants' => 'Управління ґрантами облікового запису OAuth',
	'mwoauthmanagemygrants-text' => 'На цій сторінці перераховані всі програми, які можуть використовувати ваш обліковий запис. Для будь-яких таких програм сферу їхнього доступу обмежена дозволами, наданими програмі, коли ви уповноважили її діяти від вашого імені. Якщо ви окремо уповноважили споживача для доступу до різних "сестринських" проектів від вашого імені, то ви побачите окремі налаштування для кожного такого проекту, нижче.',
	'mwoauthmanagemygrants-notloggedin' => 'Ви повинні увійти в систему для доступу до цієї сторінки.',
	'mwoauthmanagemygrants-navigation' => 'Навігація:',
	'mwoauthmanagemygrants-showlist' => 'Список прийнятих споживачів',
	'mwoauthmanagemygrants-none' => 'Жодна програма зараз не підключена до вашого облікового запису.',
	'mwoauthmanagemygrants-name' => "Ім'я споживача",
	'mwoauthmanagemygrants-user' => 'Видавець',
	'mwoauthmanagemygrants-description' => 'Опис',
	'mwoauthmanagemygrants-wiki' => 'Застосовні вікі',
	'mwoauthmanagemygrants-wikiallowed' => 'Дозволено на вікі',
	'mwoauthmanagemygrants-grants' => 'Застосовні ґранти',
	'mwoauthmanagemygrants-grantsallowed' => 'Ґранти, які дозволили',
	'mwoauthmanagemygrants-applicablegrantsallowed' => 'Застосовні гранти дозволено:',
	'mwoauthmanagemygrants-consumerkey' => 'Ключ споживача',
	'mwoauthmanagemygrants-review' => 'управління доступом',
	'mwoauthmanagemygrants-revoke' => 'скасувати доступ',
	'mwoauthmanagemygrants-grantaccept' => 'Надано',
	'mwoauthmanagemygrants-update-text' => 'Використовуйте форму нижче, щоб змінювати дозволи надані застосунку (OAuth споживачу) діяти від вашого імені.
 * якщо ви окремо уповноважили додаток для доступу до різних "сестринських сайтів" проектів від вашого імені, то у вас буде окрема конфігурація для кожного такого проекту для цього застосунку.
 * використання "*" у вікі полі надає доступ до всіх проектів на цьому сайті; використання ІД вікі-проекту обмежує доступ до одного проекту. Налаштування на проект мають вищий пріоритет.', # Fuzzy
	'mwoauthmanagemygrants-revoke-text' => 'Використовуйте форму нижче, щоб скасувати доступ для програми (OAuth), щоб діяти від вашого імені.
* Якщо ви окремо авторизували програму для доступу до різних "сестринських сайтів" проектів від вашого імені, то ви будете мати окремі налаштування для кожного такого проекту для даного додатка.
* Якщо ви хочете повністю заборонити доступ до додатку, переконайтеся, що відкликали його з усіх проектів,у які ви прийняли його.',
	'mwoauthmanagemygrants-confirm-legend' => 'Управління маркером доступу споживача',
	'mwoauthmanagemygrants-update' => 'Оновити ґранти',
	'mwoauthmanagemygrants-renounce' => 'Скасувати авторизацію',
	'mwoauthmanagemygrants-action' => 'Змінити статус:',
	'mwoauthmanagemygrants-confirm-submit' => 'Оновити стан маркера доступу',
	'mwoauthmanagemygrants-success-update' => 'Вже оновлено маркер доступу для цього споживача.',
	'mwoauthmanagemygrants-success-renounce' => 'Маркер доступу для цього споживача вже видалено.',
	'logentry-mwoauthconsumer-propose' => '$1 {{GENDER:$2|запропонував|запропонувала}} споживача OAuth (ключ споживача $4)',
	'logentry-mwoauthconsumer-update' => '$1 {{GENDER:$2|оновив|оновила}} споживача OAuth (ключ споживача $4)',
	'logentry-mwoauthconsumer-approve' => '$1 {{GENDER:$2|схвалив|схвалила}} споживача OAuth на $3 (ключ споживача $4)',
	'logentry-mwoauthconsumer-reject' => '$1 {{GENDER:$2|відхилив|відхилила}} споживача OAuth на $3 (ключ споживача $4)',
	'logentry-mwoauthconsumer-disable' => '$1 {{GENDER:$2|вимкнув|вимкнула}} споживача OAuth на $3 (ключ споживача $4)',
	'logentry-mwoauthconsumer-reenable' => '$1 {{GENDER:$2|повторно увімкнув|повторно увімкнула}} споживача OAuth на $3 (ключ споживача $4)',
	'mwoauthconsumer-consumer-logpage' => 'Журнал споживача OAuth',
	'mwoauthconsumer-consumer-logpagetext' => 'Журнал затвердження, відхилення і вимкнення зареєстрованих споживачів OAuth.',
	'mwoauth-bad-request' => 'Помилка у вашому запиті на OAuth.',
	'mwoauthdatastore-access-token-not-found' => 'Не знайдено схваленого ґранту для цього маркера авторизації.',
	'mwoauthdatastore-request-token-not-found' => 'Не знайдено запиту для цього маркера.',
	'mwoauthdatastore-bad-token' => 'Не знайдено маркера, відповідного вашому запиту.',
	'mwoauthdatastore-bad-verifier' => 'Наданий код підтвердження недійсний.',
	'mwoauthdatastore-invalid-token-type' => 'Неприпустимий маркер запитаного типу.',
	'mwoauthgrants-general-error' => 'Помилка у вашому запиті на OAuth.',
	'mwoauthserver-bad-consumer' => 'Не знайдено затвердженого споживача для наданого ключа.',
	'mwoauthserver-insufficient-rights' => 'У вас не вистачає прав для виконання цієї дії.',
	'mwoauthserver-invalid-request-token' => 'Неприпустимий маркер у вашому запиті.',
	'mwoauthserver-invalid-user-hookabort' => 'Цей користувач не може використовувати OAuth.',
	'mwoauth-invalid-authorization-title' => 'Помилка авторизації OAuth',
	'mwoauth-invalid-authorization' => 'Неприпустимі заголовки авторизації у вашому запиті:$1',
	'mwoauth-invalid-authorization-wrong-wiki' => 'Неприпустимі заголовки авторизації у вашому запиті для $1',
	'mwoauth-invalid-authorization-invalid-user' => 'Не існують заголовки авторизації у вашому запиті для користувача',
	'mwoauth-invalid-authorization-wrong-user' => 'Заголовки авторизації у вашому запиті призначені для іншого користувача',
	'mwoauth-invalid-authorization-not-approved' => 'Заголовки авторизації у вашому запитів призначені для ще незатвердженого споживача OAuth',
	'mwoauth-invalid-authorization-blocked-user' => 'Заголовки авторизації у вашому запитів призначені для заблокованого користувача',
	'mwoauth-form-description-allwikis' => "Привіт $1,

'''$2'''  хотіла б зробити наступні дії від вашого імені за всіма проектами сайту:


$4",
	'mwoauth-form-description-onewiki' => 'Привіт $1,

"\'$2"\' хотіла б зробити наступні дії від вашого імені на "$4":


$5',
	'mwoauth-form-description-allwikis-nogrants' => "Привіт $1,

'''$2''' хотів би мати базовий доступ від вашого імені на всі проекти з цього сайту.",
	'mwoauth-form-description-onewiki-nogrants' => "Привіт $1,

'''$2''' хотів би мати базовий доступ від вашого імені на  ''$4''.",
	'mwoauth-form-privacypolicy-link' => ' [[{{ns:Project}}:Privacy policy|Privacy Policy]]',
	'mwoauth-form-button-approve' => 'Дозволити',
	'mwoauth-form-button-cancel' => 'Скасувати',
	'mwoauth-authorize-form-invalid-user' => "Цей обліковий запис користувача не може використовувати OAuth, тому що облікового запису на цій вікі і обліковий запис у вікі OAuth не пов'язані.",
	'mwoauth-error' => 'Помилка OAuth',
	'mwoauth-grants-heading' => 'Потрібні дозволи:',
	'mwoauth-grants-nogrants' => 'Програма не вимагає жодних дозволів.',
	'mwoauth-acceptance-cancelled' => 'Ви скасували цей запит авторизації OAuth, щоб діяти від вашого імені.',
	'mwoauth-grant-group-page-interaction' => 'Взаємодіяти з сторінками',
	'mwoauth-grant-group-file-interaction' => 'Взаємодіяти з медіа',
	'mwoauth-grant-group-watchlist-interaction' => 'Взаємодіяти з вашим списком спостереження',
	'mwoauth-grant-group-email' => 'Надіслати листа',
	'mwoauth-grant-group-high-volume' => 'Виконати великий обсяг діяльності',
	'mwoauth-grant-group-customization' => 'Налаштування і переваги',
	'mwoauth-grant-group-administration' => 'Виконати адміністративні дії',
	'mwoauth-grant-group-other' => 'Різна діяльність',
	'mwoauth-grant-blockusers' => 'Блокування і розблокування користувачів',
	'mwoauth-grant-createaccount' => 'Створити облікові записи',
	'mwoauth-grant-createeditmovepage' => 'Створення, редагування і переміщення сторінок',
	'mwoauth-grant-delete' => 'Видалення сторінок, версій і записів журналу',
	'mwoauth-grant-editinterface' => 'Редагування простору імен Медіавікі та користувача CSS/JS',
	'mwoauth-grant-editmycssjs' => 'Редагування власного користувацького CSS/JS',
	'mwoauth-grant-editmywatchlist' => 'Редагувати список спостереження',
	'mwoauth-grant-editpage' => 'Редагувати наявні сторінки',
	'mwoauth-grant-editprotected' => 'Редагувати захищені сторінки',
	'mwoauth-grant-highvolume' => 'Редагування великих обсягів',
	'mwoauth-grant-oversight' => 'Приховати користувачів і подавити версії',
	'mwoauth-grant-patrol' => 'Патрулювати зміни на сторінках',
	'mwoauth-grant-protect' => 'Захистити і зняти захист сторінок',
	'mwoauth-grant-rollback' => 'Відкат змін на сторінках',
	'mwoauth-grant-sendemail' => 'Відправляти пошту іншим користувачам',
	'mwoauth-grant-uploadeditmovefile' => 'Завантажити, замінити та перемістити файли',
	'mwoauth-grant-uploadfile' => 'Завантажити нові файли',
	'mwoauth-grant-useoauth' => 'Основні права',
	'mwoauth-grant-viewdeleted' => 'Перегляд видаленої інформації',
	'mwoauth-grant-viewmywatchlist' => 'Перегляд списку спостереження',
	'mwoauth-oauth-exception' => 'Сталася помилка у протоколі OAuth:$1',
	'mwoauth-callback-not-oob' => 'oauth_callback мусить бути заданим і слід задати "oob" (з урахуванням регістру)',
	'right-mwoauthproposeconsumer' => 'Запропонувати нових споживачів OAuth',
	'right-mwoauthupdateownconsumer' => 'Оновлення споживачів OAuth, яких ви контролюєте',
	'right-mwoauthmanageconsumer' => 'Управління споживачами OAuth',
	'right-mwoauthsuppress' => 'Придушити споживачів OAuth',
	'right-mwoauthviewsuppressed' => 'Перегляд пригнічених споживачів OAuth',
	'right-mwoauthviewprivate' => 'Переглянути приватні дані OAuth',
	'right-mwoauthmanagemygrants' => 'Керувати грантами OAuth',
	'action-mwoauthmanageconsumer' => 'управляти споживачами OAuth',
	'action-mwoauthmanagemygrants' => 'управляти вашими ґрантами OAuth',
	'action-mwoauthproposeconsumer' => 'пропонувати нових споживачів OAuth',
	'action-mwoauthupdateownconsumer' => 'оновити споживачів OAuth, яких ви контролюєте',
	'action-mwoauthviewsuppressed' => 'переглянути пригнічених споживачів OAuth',
);

/** Vietnamese (Tiếng Việt)
 * @author Minh Nguyen
 */
$messages['vi'] = array(
	'mwoauth-verified' => "Ứng dụng hiện được phép truy cập MediaWiki thay mặt bạn.

Để hoàn thành quá trình này, xin hãy nhập giá trị xác minh này vào ứng dụng: '''$1'''",
	'mwoauth-missing-field' => 'Thiếu giá trị cho trường “$1”',
	'mwoauth-invalid-field' => 'Giá trị không hợp lệ được đưa vào trường “$1”',
	'mwoauth-invalid-field-generic' => 'Giá trị không hợp lệ được cung cấp',
	'mwoauth-field-hidden' => '(thông tin này bị ẩn)',
	'mwoauth-field-private' => '(thông tin này là bí mật)',
	'mwoauth-grant-generic' => 'Gói quyền “$1”',
	'mwoauth-prefs-managegrants' => 'Ứng dụng kết nối:',
	'mwoauth-prefs-managegrantslink' => 'Quản lý $1 ứng dụng kết nối',
	'mwoauth-consumer-name' => 'Tên ứng dụng:',
	'mwoauth-consumer-user' => 'Nhà xuất bản:',
	'mwoauth-consumer-stage' => 'Trạng thái hiện tại:',
	'mwoauth-consumer-email' => 'Địa chỉ thư điện tử liên lạc:',
	'mwoauth-consumer-description' => 'Miêu tả ứng dụng:',
	'mwoauth-consumer-callbackurl' => 'URL “gọi lại” OAuth:',
	'mwoauth-consumer-grantsneeded' => 'Các quyền có liên quan:',
	'mwoauth-consumer-wiki' => 'Wiki có liên quan:',
	'mwoauth-consumer-restrictions' => 'Hạn chế sử dụng:',
	'mwoauth-consumer-restrictions-json' => 'Hạn chế sử dụng (JSON):',
	'mwoauth-consumer-rsakey' => 'Chìa khóa RSA công cộng:',
	'mwoauth-consumer-accesstoken' => 'Dấu hiệu truy cập:',
	'mwoauth-consumer-reason' => 'Lý do:',
	'mwoauth-consumer-email-unconfirmed' => 'Địa chỉ thư điện tử tài khoản của bạn chưa được xác nhận.',
	'mwoauth-consumer-email-mismatched' => 'Địa chỉ thư điện tử được cung cấp phải cũng là địa chỉ thư điện tử của tài khoản của bạn.',
	'mwoauth-invalid-access-token' => 'Không có dấu hiện truy cập với chìa khóa được cung cấp.',
	'mwoauth-consumer-stage-proposed' => 'đề xuất',
	'mwoauth-consumer-stage-rejected' => 'từ chối',
	'mwoauth-consumer-stage-expired' => 'hết hạn',
	'mwoauth-consumer-stage-approved' => 'chấp nhận',
	'mwoauth-consumer-stage-disabled' => 'tắt',
	'mwoauth-consumer-stage-suppressed' => 'ẩn',
	'mwoauthconsumerregistration-notloggedin' => 'Bạn phải đăng nhập để truy cấp trang này.',
	'mwoauthconsumerregistration-navigation' => 'Điều hướng:',
	'mwoauthconsumerregistration-main' => 'Chính',
	'mwoauthconsumerregistration-user' => 'Nhà xuất bản',
	'mwoauthconsumerregistration-description' => 'Miêu tả',
	'mwoauthconsumerregistration-email' => 'Địa chỉ thư điện tử liên lạc',
	'mwoauthconsumerregistration-stage' => 'Trạng thái',
	'mwoauthconsumerregistration-lastchange' => 'Thay đổi cuối cùng',
	'mwoauthconsumerregistration-manage' => 'quản lý',
	'mwoauthconsumerregistration-resetsecretkey' => 'Đặt lại chìa khóa bí mật thành một giá trị mới',
	'mwoauthmanageconsumers-notloggedin' => 'Bạn phải đăng nhập để truy cấp trang này.',
	'mwoauthmanageconsumers-type' => 'Hàng đợi:',
	'mwoauthmanageconsumers-showproposed' => 'Yêu cầu được đề xuất',
	'mwoauthmanageconsumers-showrejected' => 'Yêu cầu bị từ chối',
	'mwoauthmanageconsumers-showexpired' => 'Yêu cầu đã hết hạn',
	'mwoauthmanageconsumers-main' => 'Chính',
	'mwoauthmanageconsumers-user' => 'Nhà xuất bản',
	'mwoauthmanageconsumers-description' => 'Miêu tả',
	'mwoauthmanageconsumers-email' => 'Địa chỉ thư điện tử liên lạc',
	'mwoauthmanageconsumers-lastchange' => 'Thay đổi cuối cùng',
	'mwoauthmanageconsumers-review' => 'xem lại/quản lý',
	'mwoauthmanageconsumers-action' => 'Thay đổi trạng thái:',
	'mwoauthmanageconsumers-approve' => 'Chấp nhận',
	'mwoauthmanageconsumers-reject' => 'Từ chối',
	'mwoauthmanageconsumers-rsuppress' => 'Từ chối và ẩn',
	'mwoauthmanageconsumers-disable' => 'Tắt',
	'mwoauthmanageconsumers-dsuppress' => 'Tắt và ẩn',
	'mwoauthmanageconsumers-reenable' => 'Chấp nhận',
	'mwoauthmanageconsumers-reason' => 'Lý do:',
	'mwoauthmanageconsumers-success-approved' => 'Yêu cầu đã được chấp nhận.',
	'mwoauthmanageconsumers-success-rejected' => 'Yêu cầu đã bị từ chối.',
	'mwoauthlistconsumers-view' => 'chi tiết',
	'mwoauthlistconsumers-name' => 'Tên ứng dụng',
	'mwoauthlistconsumers-user' => 'Nhà xuất bản',
	'mwoauthlistconsumers-description' => 'Miêu tả',
	'mwoauthlistconsumers-wiki' => 'Wiki có liên quan',
	'mwoauthlistconsumers-callbackurl' => '“URL gọi lại” OAuth',
	'mwoauthlistconsumers-basicgrantsonly' => '(chỉ truy cập cơ bản)',
	'mwoauthlistconsumers-status' => 'Trạng thái',
	'mwoauthmanagemygrants-notloggedin' => 'Bạn phải đăng nhập để truy cấp trang này.',
	'mwoauthmanagemygrants-navigation' => 'Điều hướng:',
	'mwoauthmanagemygrants-user' => 'Nhà xuất bản',
	'mwoauthmanagemygrants-description' => 'Miêu tả',
	'mwoauthmanagemygrants-wiki' => 'Wiki có liên quan',
	'mwoauthmanagemygrants-wikiallowed' => 'Được cho phép trên wiki',
	'mwoauthmanagemygrants-grants' => 'Các quyền có liên quan',
	'mwoauthmanagemygrants-grantsallowed' => 'Các quyền được cấp',
	'mwoauthmanagemygrants-applicablegrantsallowed' => 'Các quyền được cấp có liên quan:',
	'mwoauthmanagemygrants-review' => 'quản lý truy cập',
	'mwoauthmanagemygrants-update' => 'Cập nhật các dấu hiệu được cấp',
	'mwoauthmanagemygrants-action' => 'Thay đổi trạng thái:',
	'mwoauthmanagemygrants-confirm-submit' => 'Cập nhật trạng thái của dấu hiệu truy cập',
	'mwoauth-bad-request' => 'Có lỗi trong yêu cầu OAuth của bạn.',
	'mwoauthdatastore-request-token-not-found' => 'Không tìm thấy yêu cầu ứng với dấu hiệu này.',
	'mwoauthdatastore-bad-token' => 'Không tìm thấy dấu hiệu ứng với yêu cầu của bạn.',
	'mwoauthdatastore-bad-verifier' => 'Mã xác minh được cung cấp là không hợp lệ.',
	'mwoauthdatastore-invalid-token-type' => 'Đã yêu cầu kiểu dấu hiệu không hợp lệ.',
	'mwoauthgrants-general-error' => 'Có lỗi trong yêu cầu OAuth của bạn.',
	'mwoauthserver-insufficient-rights' => 'Bạn không có đủ quyền để thực hiện thao tác này.',
	'mwoauthserver-invalid-request-token' => 'Dấu hiệu không hợp lệ trong yêu cầu của bạn.',
	'mwoauthserver-invalid-user-hookabort' => 'Người dùng này không thể dùng OAuth.',
	'mwoauth-form-privacypolicy-link' => '[[{{ns:Project}}:Privacy policy|Quy định quyền riêng tư]]',
	'mwoauth-form-button-approve' => 'Cho phép',
	'mwoauth-form-button-cancel' => 'Hủy bỏ',
	'mwoauth-authorize-form-invalid-user' => 'Tài khoản người dùng này không thể sử dụng OAuth vì tài khoản trên wiki này không được liên kết với tài khoản trên wiki OAuth trung ương.',
	'mwoauth-error' => 'Lỗi OAuth',
	'mwoauth-grants-heading' => 'Các quyền được yêu cầu:',
	'mwoauth-grants-nogrants' => 'Ứng dụng không yêu cầu quyền nào.',
	'mwoauth-grant-group-page-interaction' => 'Tương tác với trang',
	'mwoauth-grant-group-file-interaction' => 'Tương tác với tập tin',
	'mwoauth-grant-group-watchlist-interaction' => 'Tương tác với danh sách theo dõi của bạn',
	'mwoauth-grant-group-email' => 'Gửi thư điện tử',
	'mwoauth-grant-group-customization' => 'Tùy biến và tùy chọn',
	'mwoauth-grant-group-administration' => 'Thực hiện các hành động bảo quản',
	'mwoauth-grant-group-other' => 'Hoạt động khác',
	'mwoauth-grant-blockusers' => 'Cấm và bỏ cấm người dùng',
	'mwoauth-grant-createaccount' => 'Mở tài khoản',
	'mwoauth-grant-createeditmovepage' => 'Tạo, sửa, và di chuyển trang',
	'mwoauth-grant-delete' => 'Xóa trang, phiên bản, và mục nhật ký',
	'mwoauth-grant-editinterface' => 'Sửa không gian tên MediaWiki và CSS/JS cá nhân',
	'mwoauth-grant-editmycssjs' => 'Sửa đổi CSS/JS cá nhân của bạn',
	'mwoauth-grant-editmywatchlist' => 'Sửa danh sách theo dõi của bạn',
	'mwoauth-grant-editpage' => 'Sửa đổi các trang đã tồn tại',
	'mwoauth-grant-editprotected' => 'Sửa đội các trang bị khóa',
	'mwoauth-grant-highvolume' => 'Sửa đổi tốc độ cao',
	'mwoauth-grant-oversight' => 'Ẩn người dùng và phiên bản',
	'mwoauth-grant-patrol' => 'Tuần tra các thay đổi trang',
	'mwoauth-grant-protect' => 'Khóa và mở khóa các trang',
	'mwoauth-grant-rollback' => 'Lùi một loạt thay đổi vào một trang',
	'mwoauth-grant-sendemail' => 'Gửi thư điện tử cho người dùng khác',
	'mwoauth-grant-uploadeditmovefile' => 'Tải lên, thay thế, và di chuyển tập tin',
	'mwoauth-grant-uploadfile' => 'Tải lên tập tin mới',
	'mwoauth-grant-useoauth' => 'Quyền cơ bản',
	'mwoauth-grant-viewdeleted' => 'Xem thông tin bị xóa',
	'mwoauth-grant-viewmywatchlist' => 'Xem danh sách theo dõi của bạn',
	'mwoauth-oauth-exception' => 'Đã xuất hiện lỗi trong giao thức OAuth: $1',
	'mwoauth-callback-not-oob' => 'oauth_callback phải được xác định là “oob” (phải là chữ thường)',
	'right-mwoauthviewprivate' => 'Xem dữ liệu riêng OAuth',
	'right-mwoauthmanagemygrants' => 'Quản lý các quyền OAuth được cấp',
	'action-mwoauthmanagemygrants' => 'quản lý các quyền OAuth mà bạn cấp',
);

/** Volapük (Volapük)
 * @author Malafaya
 */
$messages['vo'] = array(
	'mwoauthlistconsumers-description' => 'Bepenam',
);

/** Wu (吴语)
 * @author Benojan
 * @author 十弌
 */
$messages['wuu'] = array(
	'mwoauth-consumer-allwikis' => '箇網站個全部項目',
	'mwoauth-form-button-approve' => '允許',
	'mwoauth-form-button-cancel' => '取消',
	'mwoauth-grant-group-page-interaction' => '搭頁面互動',
	'mwoauth-grant-group-file-interaction' => '搭媒體互動',
	'mwoauth-grant-group-watchlist-interaction' => '搭爾個監視表互動',
	'mwoauth-grant-group-email' => '發電子信',
	'mwoauth-grant-rollback' => '畀修改擂轉到頁面',
	'mwoauth-grant-sendemail' => '發電子信畀各許用戶',
);

/** Yiddish (ייִדיש)
 * @author פוילישער
 */
$messages['yi'] = array(
	'mwoauth-consumer-version' => 'קאנסומענט ווערסיע:',
	'mwoauth-consumer-email-unconfirmed' => 'אייער קאנטע ע־פאסט אדרעס איז נאך נישט געווארן באשטעטיגט.',
);

/** Simplified Chinese (中文（简体）‎)
 * @author Hzy980512
 * @author Liuxinyu970226
 * @author Qiyue2001
 * @author Shirayuki
 */
$messages['zh-hans'] = array(
	'mwoauth-invalid-field-generic' => '提供的值无效',
	'mwoauth-field-hidden' => '（这些信息已被隐藏）',
	'mwoauth-field-private' => '（这些信息不是公开的）',
	'mwoauth-prefs-managegrants' => '连接的应用程序：',
	'mwoauth-consumer-allwikis' => '本站的所有项目',
	'mwoauth-consumer-stage' => '当前状态：',
	'mwoauth-consumer-reason' => '原因：',
	'mwoauthconsumerregistration-description' => '说明',
	'mwoauthconsumerregistration-stage' => '状态',
	'mwoauthmanageconsumers-action' => '更改状态：',
	'mwoauthmanageconsumers-approve' => '已批准',
	'mwoauthmanageconsumers-reject' => '已回绝',
	'mwoauthmanageconsumers-rsuppress' => '已回绝并禁止',
	'mwoauthmanageconsumers-disable' => '已禁用',
	'mwoauthmanageconsumers-dsuppress' => '已禁用并禁止',
	'mwoauthmanageconsumers-reenable' => '已批准',
	'mwoauthmanageconsumers-reason' => '原因：',
	'mwoauthmanageconsumers-success-approved' => '请求已批准。',
	'mwoauthmanageconsumers-success-rejected' => '请求已被拒绝。',
	'mwoauthlistconsumers-view' => '详情',
	'mwoauthlistconsumers-name' => '应用程序名称',
	'mwoauthlistconsumers-version' => '消费者版本',
	'mwoauthlistconsumers-user' => '发布者',
	'mwoauthlistconsumers-description' => '描述',
	'mwoauthlistconsumers-basicgrantsonly' => '（仅适用于基本访问）',
	'mwoauthlistconsumers-status' => '状态',
	'mwoauth-consumer-stage-any' => '任何',
	'mwoauthlistconsumers-status-proposed' => '建议',
	'mwoauthlistconsumers-status-disabled' => '停用',
	'mwoauthmanagemygrants-none' => '当前没有应用程序连接到您的帐户。',
	'mwoauthmanagemygrants-description' => '描述',
	'mwoauthmanagemygrants-review' => '管理访问',
	'mwoauthmanagemygrants-grantaccept' => '授权',
	'mwoauthmanagemygrants-update' => '更新补助',
	'mwoauthmanagemygrants-renounce' => '取消授权',
	'mwoauth-bad-request' => '您的OAuth请求有错误。',
	'mwoauthserver-invalid-user-hookabort' => '此用户不能使用。',
	'mwoauth-invalid-authorization-title' => 'OAuth认证错误',
	'mwoauth-form-button-approve' => '允许',
	'mwoauth-form-button-cancel' => '取消',
	'mwoauth-grant-group-email' => '发送电邮',
	'mwoauth-grant-group-customization' => '自定义与设置',
	'mwoauth-grant-blockusers' => '封禁与解封用户',
	'mwoauth-grant-createaccount' => '注册账户',
	'mwoauth-grant-createeditmovepage' => '创建、编辑与移动页面',
	'mwoauth-grant-editmycssjs' => '编辑您自己的用户CSS/JS',
	'mwoauth-grant-editmywatchlist' => '编辑您的监视列表',
	'mwoauth-grant-editpage' => '编辑存在的页面',
	'mwoauth-grant-editprotected' => '编辑受保护页面',
	'mwoauth-grant-protect' => '保护页面和取消页面保护',
	'mwoauth-grant-rollback' => '回退更改到页',
	'mwoauth-grant-sendemail' => '给其他用户发送电子邮件',
	'mwoauth-grant-uploadeditmovefile' => '上传，替换和移动文件',
	'mwoauth-grant-uploadfile' => '上传新文件',
	'mwoauth-grant-useoauth' => '基本权限',
	'mwoauth-grant-viewdeleted' => '查看已删除信息',
	'mwoauth-grant-viewmywatchlist' => '查看您的监视列表',
	'mwoauth-oauth-exception' => 'OAuth 协议发生错误：$1',
);
