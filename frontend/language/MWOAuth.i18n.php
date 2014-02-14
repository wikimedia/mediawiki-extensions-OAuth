<?php
/**
 * Internationalisation file for OAuth extension.
 *
 * @file
 * @ingroup Extensions
 */

$messages = array();

$messages['en'] = array(
	'oauth' => 'OAuth',
	'mwoauth-desc' => 'Allows usage of OAuth 1.0a for API authorization',

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
	'mwoauth-consumer-wiki' => 'Applicable project:',
	'mwoauth-consumer-wiki-thiswiki' => 'Current project ($1)',
	'mwoauth-consumer-wiki-other' => 'Specific project',
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
	'mwoauth-invalid-access-wrongwiki' => 'The consumer can only be used on project "$1".',
	'mwoauth-consumer-conflict' => 'Someone changed the attributes of this consumer as you viewed it. Please try again. You may want to check the change log.',
	'mwoauth-consumer-grantshelp' => 'Each grant gives access to listed user rights that a user account already has. See the [[Special:OAuth/grants|table of grants]] for more information.',

	'mwoauth-consumer-stage-proposed' => 'proposed',
	'mwoauth-consumer-stage-rejected' => 'rejected',
	'mwoauth-consumer-stage-expired' => 'expired',
	'mwoauth-consumer-stage-approved' => 'approved',
	'mwoauth-consumer-stage-disabled' => 'disabled',
	'mwoauth-consumer-stage-suppressed' => 'suppressed',

	'oauthconsumerregistration' => 'OAuth consumer registration',
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
* You can use a project ID to restrict the consumer to a single project on this site (use "*" for all projects).
* The email address provided must match that of your account (which must have been confirmed).',
	'mwoauthconsumerregistration-update-text' => 'Use the form below to update aspects of an OAuth consumer you control.

All values here will overwrite any previous ones. Do not leave blank fields unless you intend to clear those values.',
	'mwoauthconsumerregistration-maintext' => 'This page is for letting developers propose and update OAuth consumer applications in this site\'s registry.

From here, you can:
* [[Special:OAuthConsumerRegistration/propose|Request a token for a new consumer]].
* [[Special:OAuthConsumerRegistration/list|Manage your existing consumers]].

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

	'oauthmanageconsumers' => 'Manage OAuth consumers',
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
	'mwoauthmanageconsumers-viewing' => '{{GENDER:$1|User}} "$1" is currently viewing this consumer',
	'mwoauthmanageconsumers-success-approved' => 'Request has been approved.',
	'mwoauthmanageconsumers-success-rejected' => 'Request has been rejected.',
	'mwoauthmanageconsumers-success-disabled' => 'Consumer has been disabled.',
	'mwoauthmanageconsumers-success-reanable' => 'Consumer has been re-enabled.',
	'mwoauthmanageconsumers-search-name' => 'consumers with this name',
	'mwoauthmanageconsumers-search-publisher' => 'consumers by this user',

	'oauthlistconsumers' => 'List OAuth applications',
	'mwoauthlistconsumers-legend' => 'Browse OAuth applications',
	'mwoauthlistconsumers-view' => 'details',
	'mwoauthlistconsumers-none' => 'No applications found meeting this criteria.',
	'mwoauthlistconsumers-name' => 'Application name',
	'mwoauthlistconsumers-version' => 'Consumer version',
	'mwoauthlistconsumers-user' => 'Publisher',
	'mwoauthlistconsumers-description' => 'Description',
	'mwoauthlistconsumers-wiki' => 'Applicable project',
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

	'oauthmanagemygrants' => 'Manage connected applications',
	'mwoauthmanagemygrants-text' => 'This page lists any applications that can use your account. For any such application, the scope of its access is limited by the permissions that you granted to the application when you authorized it to act on your behalf. If you separately authorized an application to access different sister projects on your behalf, then you will see separate configuration for each such project below.

Connected applications access your account by using the OAuth protocol. <span class="plainlinks">([https://www.mediawiki.org/wiki/Special:MyLanguage/Help:OAuth Learn more about connected applications])</span>',
	'mwoauthmanagemygrants-notloggedin' => 'You have to be logged in to access this page.',
	'mwoauthmanagemygrants-navigation' => 'Navigation:',
	'mwoauthmanagemygrants-showlist' => 'Connected application list',
	'mwoauthmanagemygrants-none' => 'There are no applications connected to your account.',
	'mwoauthmanagemygrants-user' => 'Publisher:',
	'mwoauthmanagemygrants-description' => 'Description',
	'mwoauthmanagemygrants-wikiallowed' => 'Allowed on project:',
	'mwoauthmanagemygrants-grants' => 'Applicable grants',
	'mwoauthmanagemygrants-grantsallowed' => 'Grants allowed',
	'mwoauthmanagemygrants-applicablegrantsallowed' => 'Applicable grants allowed:',
	'mwoauthmanagemygrants-review' => 'manage access',
	'mwoauthmanagemygrants-revoke' => 'revoke access',
	'mwoauthmanagemygrants-grantaccept' => 'Granted',
	'mwoauthmanagemygrants-update-text' => 'Use the form below to modify the permissions granted to an application to act on your behalf.',
	'mwoauthmanagemygrants-revoke-text' => 'Use the form below to revoke access for an application to act on your behalf.',
	'mwoauthmanagemygrants-confirm-legend' => 'Manage connected application',
	'mwoauthmanagemygrants-update' => 'Update grants',
	'mwoauthmanagemygrants-renounce' => 'Deauthorize',
	'mwoauthmanagemygrants-action' => 'Change status:',
	'mwoauthmanagemygrants-confirm-submit' => 'Update access token status',
	'mwoauthmanagemygrants-success-update' => 'The access token for this consumer has been updated.',
	'mwoauthmanagemygrants-success-renounce' => 'The access token for this consumer has been deleted.',
	'mwoauthmanagemygrants-useoauth-tooltip' => 'Why can\'t I update this grant? This grant gives your connected application basic permissions which it requires to function properly. If you don\'t want this connected application to have these rights, you should revoke the application\'s access.',

	'logentry-mwoauthconsumer-propose' => '$1 {{GENDER:$2|proposed}} an OAuth consumer (consumer key $4)',
	'logentry-mwoauthconsumer-update' => '$1 {{GENDER:$2|updated}} an OAuth consumer (consumer key $4)',
	'logentry-mwoauthconsumer-approve' => '$1 {{GENDER:$2|approved}} an OAuth consumer by $3 (consumer key $4)',
	'logentry-mwoauthconsumer-reject' => '$1 {{GENDER:$2|rejected}} an OAuth consumer by $3 (consumer key $4)',
	'logentry-mwoauthconsumer-disable' => '$1 {{GENDER:$2|disabled}} an OAuth consumer by $3 (consumer key $4)',
	'logentry-mwoauthconsumer-reenable' => '$1 {{GENDER:$2|re-enabled}} an OAuth consumer by $3 (consumer key $4)',

	'mwoauthconsumer-consumer-logpage' => 'OAuth consumer log',
	'mwoauthconsumer-consumer-logpagetext' => 'Log of approvals, rejections, and disabling of registered OAuth consumers.',

	'mwoauth-bad-request-missing-params' => 'Sorry, something went wrong configuring this connected application. <span class="plainlinks">[https://www.mediawiki.org/wiki/Special:MyLanguage/Help:OAuth Contact support]</span> to get help fixing it.

<span class="plainlinks mw-mwoautherror-details">OAuth missing parameters, [https://www.mediawiki.org/wiki/Help:OAuth/Errors#E001 E001]</span>',
	'mwoauth-bad-request-invalid-action' => 'Sorry, something went wrong, you\'ll need to contact the application author for help with this.

<span class="plainlinks mw-mwoautherror-details">Unknown URL, [https://www.mediawiki.org/wiki/Help:OAuth/Errors#E002 E002]</span>',
	'mwoauth-bad-request-invalid-action-contact' => 'Sorry, something went wrong. You\'ll need to [$1 contact] the application author for help with this.

<span class="plainlinks mw-mwoautherror-details">Unknown URL, [https://www.mediawiki.org/wiki/Help:OAuth/Errors#E003 E003]</span>',
	'mwoauthdatastore-access-token-not-found' => 'No approved grant was found for that authorization token.',
	'mwoauthdatastore-request-token-not-found' => 'Sorry, something went wrong connecting this application.
Go back and try to connect your account again, or contact the application author.

<span class="plainlinks mw-mwoautherror-details">OAuth token not found, [https://www.mediawiki.org/wiki/Help:OAuth/Errors#E004 E004]</span>',
	'mwoauthdatastore-bad-token' => 'No token was found matching your request.',
	'mwoauthdatastore-bad-verifier' => 'The verification code provided was not valid.',
	'mwoauthdatastore-invalid-token-type' => 'The requested token type is invalid.',
	'mwoauthgrants-general-error' => 'There was an error in your OAuth request.',
	'mwoauthserver-bad-consumer' => '"$1" is no longer approved as a Connected App, [$2 contact] the application author for help.

<span class="plainlinks mw-mwoautherror-details">Connected OAuth app not approved, [https://www.mediawiki.org/wiki/Help:OAuth/Errors#E005 E005]</span>',
	'mwoauthserver-bad-consumer-key' => 'Sorry, something went wrong connecting this application.

<span class="plainlinks mw-mwoautherror-details">Unknown OAuth key, [https://www.mediawiki.org/wiki/Help:OAuth/Errors#E006 E006]</span>',
	'mwoauthserver-insufficient-rights' => 'Your account is not allowed to use Connected Apps, contact your site administrator to find out why.

<span class="plainlinks mw-mwoautherror-details">Insufficient OAuth user rights, [https://www.mediawiki.org/wiki/Help:OAuth/Errors#E007 E007]</span>',
	'mwoauthserver-invalid-request-token' => 'Invalid token in your request.',
	'mwoauthserver-invalid-user' => 'To use Connected Apps on this site, you must have an account across all projects. When you have an account on all projects, you can try to connect "$1" again.

<span class="plainlinks mw-mwoautherror-details">Unified login needed, [https://www.mediawiki.org/wiki/Help:OAuth/Errors#E008 E008]</span>',

	'mwoauth-invalid-authorization-title' => 'OAuth authorization error',
	'mwoauth-invalid-authorization' => 'The authorization headers in your request are not valid: $1',
	'mwoauth-invalid-authorization-wrong-wiki' => 'The authorization headers in your request are not valid for $1',
	'mwoauth-invalid-authorization-invalid-user' => 'The authorization headers in your request are for a user that does not exist here',
	'mwoauth-invalid-authorization-wrong-user' => 'The authorization headers in your request are for a different user',
	'mwoauth-invalid-authorization-not-approved' => 'The app that you are trying to connect seems to be set up incorrectly. Contact the author of "$1" for help.',
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
	'mwoauth-error' => 'Application Connection Error',
	'mwoauth-grants-heading' => 'Requested permissions:',
	'mwoauth-grants-nogrants' => 'The application has not requested any permissions.',
	'mwoauth-acceptance-cancelled' => 'You have chosen not to allow "$1" to access your account. "$1" will not work unless you allow it access. You can go back to "$1" or [[Special:OAuthManageMyGrants|manage]] your connected apps.',

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
	'mwoauth-grant-editinterface' => 'Edit the MediaWiki namespace and user CSS/JavaScript',
	'mwoauth-grant-editmycssjs' => 'Edit your own user CSS/JavaScript',
        'mwoauth-grant-editmyoptions' => 'Edit your own user preferences',
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

	'mwoauth-listgrantrights-summary' => 'The following is a list of OAuth grants, with their associated access to user rights. Users can authorize applications to use their account, but with limited permissions based on the grants the user gave to the application. An application acting on behalf of a user cannot actually use rights that the user does not have however.
There may be [[{{MediaWiki:Listgrouprights-helppage}}|additional information]] about individual rights.',
	'mwoauth-listgrants-grant' => 'Grant',
	'mwoauth-listgrants-rights' => 'Rights',
	'mwoauth-listgrantrights-right-display' => '$1 <code>($2)</code>', # only translate this message to other languages if you have to change it
);

/** Message documentation (Message documentation)
 * @author Amire80
 * @author Raymond
 * @author Shirayuki
 * @author Siebrand
 */
$messages['qqq'] = array(
	'oauth' => 'Title of MWOAuth page',
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
* {{msg-mw|Mwoauth-grant-checkuser}}
* {{msg-mw|Mwoauth-grant-blockusers}}
* {{msg-mw|Mwoauth-grant-createaccount}}
* {{msg-mw|Mwoauth-grant-createeditmovepage}}
* {{msg-mw|Mwoauth-grant-delete}}
* {{msg-mw|Mwoauth-grant-editinterface}}
* {{msg-mw|Mwoauth-grant-editmycssjs}}
* {{msg-mw|Mwoauth-grant-editmyoptions}}
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

Used as text for the link which points to [[Special:OAuthManageMyGrants]].

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
{{Identical|Applicable project}}',
	'mwoauth-consumer-wiki-thiswiki' => 'Label for selection-list option, indicating the wiki this user is currently visiting.

Parameters:
* $1 - wiki ID',
	'mwoauth-consumer-wiki-other' => "Label for selection-list option, indicating the user wants to type in another wiki's name",
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
	'mwoauth-consumer-not-proposed' => 'Used as failure message when approving or rejecting the consumer.

See also:
* {{msg-mw|Mwoauth-consumer-not-disabled}}',
	'mwoauth-consumer-not-disabled' => 'Used as failure message when re-enabling the consumer.

See also:
* {{msg-mw|Mwoauth-consumer-not-proposed}}',
	'mwoauth-consumer-not-approved' => 'Used as failure message.',
	'mwoauth-missing-consumer-key' => 'Used as error message when showing consumer information.',
	'mwoauth-invalid-consumer-key' => 'Used as failure message.',
	'mwoauth-invalid-access-token' => 'Used as failure message.',
	'mwoauth-invalid-access-wrongwiki' => 'Used as error message. Parameters:
* $1 - the wiki ID the consumer is applicable to',
	'mwoauth-consumer-conflict' => 'Used as failure message.',
	'mwoauth-consumer-grantshelp' => 'Help text shown on consumer proposal form.',
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
	'oauthconsumerregistration' => '{{doc-special|MWOAuthConsumerRegistration}}',
	'mwoauthconsumerregistration-notloggedin' => 'Used if not blocked, not read-only and not logged in.',
	'mwoauthconsumerregistration-navigation' => 'Used in page subtitle.
{{Identical|Navigation}}',
	'mwoauthconsumerregistration-propose' => 'Text for the link that developers follow to request that their application is accepted as an OAuth application on this site.',
	'mwoauthconsumerregistration-list' => 'Used in page subtitle link text',
	'mwoauthconsumerregistration-main' => 'Used as label for "View all" link.

Preceded by list of the links ("|" separated) which have any one of the following link texts:
* {{msg-mw|Mwoauthconsumerregistration-propose}}
* {{msg-mw|Mwoauthconsumerregistration-list}}
{{Identical|Main}}',
	'mwoauthconsumerregistration-propose-text' => 'Used as introduction text for the form.',
	'mwoauthconsumerregistration-update-text' => 'Used as introduction text for the form.',
	'mwoauthconsumerregistration-maintext' => 'Used as introduction text in [[Special:OAuthConsumerRegistration]].',
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
	'oauthmanageconsumers' => '{{doc-special|MWOAuthManageConsumers}}
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
	'mwoauthmanageconsumers-maintext' => 'Used in [[Special:OAuthManageConsumers]].

Followed by the message {{msg-mw|Mwoauthmanageconsumers-queues}}.',
	'mwoauthmanageconsumers-queues' => 'Used as label.

Followed by a list of links which point to [[Special:OAuthManageConsumers]].

Text for the link is any one of the following messages:
* {{msg-mw|Mwoauthmanageconsumers-q-proposed}}
* {{msg-mw|Mwoauthmanageconsumers-q-rejected}}
* {{msg-mw|Mwoauthmanageconsumers-q-expired}}',
	'mwoauthmanageconsumers-q-proposed' => 'Used as text for the link which points to [[Special:OAuthManageConsumers]].

The list is preceded by the label {{msg-mw|Mwoauthmanageconsumers-queues}}.',
	'mwoauthmanageconsumers-q-rejected' => 'Used as text for the link which points to [[Special:OAuthManageConsumers]].

The list is preceded by the label {{msg-mw|Mwoauthmanageconsumers-queues}}.',
	'mwoauthmanageconsumers-q-expired' => 'Used as text for the link which points to [[Special:OAuthManageConsumers]].

The list is preceded by the label {{msg-mw|Mwoauthmanageconsumers-queues}}.',
	'mwoauthmanageconsumers-lists' => 'Used as subtitle which is followed by a list of links.

The links are points to [[Special:OAuthManageConsumers]].

The text fo the link is any one of the following messages:
* {{msg-mw|Mwoauthmanageconsumers-l-approved}}
* {{msg-mw|Mwoauthmanageconsumers-l-disabled}}',
	'mwoauthmanageconsumers-l-approved' => 'Used as text for the link which points to [[Special:OAuthManageConsumers]].

The list is preceded by the label {{msg-mw|Mwoauthmanageconsumers-lists}}.',
	'mwoauthmanageconsumers-l-disabled' => 'Used as text for the link which points to [[Special:OAuthManageConsumers]].

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
	'mwoauthmanageconsumers-review' => 'Used as label for the link which points to [[Special:OAuthManageConsumers]].',
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
	'oauthlistconsumers' => '{{doc-special|MWOAuthListConsumers}}',
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
{{Identical|Applicable project}}',
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
	'oauthmanagemygrants' => '{{doc-special|MWOAuthManageMyGrants}}',
	'mwoauthmanagemygrants-text' => 'Explanatory text for Special:OAuthManageMyGrants page',
	'mwoauthmanagemygrants-notloggedin' => 'Used in [[Special:OAuthManageMyGrants]] if the user is not logged in.',
	'mwoauthmanagemygrants-navigation' => 'Used as subtitle.

Followed by a link with the link text {{msg-mw|Mwoauthmanagemygrants-showlist}}. It can be without link.
{{Identical|Navigation}}',
	'mwoauthmanagemygrants-showlist' => 'Used as link text or as plain text',
	'mwoauthmanagemygrants-none' => 'Message when a user has not authorized any OAuth consumers',
	'mwoauthmanagemygrants-user' => 'Used as table row header for "Central username".
{{Identical|Publisher}}',
	'mwoauthmanagemygrants-description' => 'Used as table row header.
{{Identical|Description}}',
	'mwoauthmanagemygrants-wikiallowed' => 'Used as field label',
	'mwoauthmanagemygrants-grants' => 'Used as field label.
{{Identical|Applicable grant}}',
	'mwoauthmanagemygrants-grantsallowed' => 'Used as field label',
	'mwoauthmanagemygrants-applicablegrantsallowed' => 'Used as field label',
	'mwoauthmanagemygrants-review' => 'Used as link text.',
	'mwoauthmanagemygrants-revoke' => 'Used as link text.',
	'mwoauthmanagemygrants-grantaccept' => 'Used as checkbox column label',
	'mwoauthmanagemygrants-update-text' => 'Explanatory text for [[Special:OAuthManageMyGrants]] form',
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
	'mwoauthmanagemygrants-useoauth-tooltip' => 'Message for the tooltip shown next to the disabled "Basic rights" checkbox on [[Special:OAuthManageMyGrants]], explaining why the checkbox cannot be modified.',
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
	'mwoauth-bad-request-missing-params' => 'Error message when MediaWiki makes an error during the authorization process, and fails to send all the required url parameters',
	'mwoauth-bad-request-invalid-action' => 'Error, when the 3rd-party OAuth developers sends users to a bad authorization url',
	'mwoauth-bad-request-invalid-action-contact' => 'Error, when the 3rd-party OAuth developers sends users to a bad authorization url, but we know which application made the request and we can link the user to a page to contact the developer.

Parameters:
* $1 - URL',
	'mwoauthdatastore-access-token-not-found' => 'Error message when an invalid access token was submitted',
	'mwoauthdatastore-request-token-not-found' => 'Error message when an invalid request token was submitted',
	'mwoauthdatastore-bad-token' => 'Error message when an invalid token was submitted',
	'mwoauthdatastore-bad-verifier' => 'Error message when an invalid verification code was submitted',
	'mwoauthdatastore-invalid-token-type' => 'Error message when an invalid page was requested',
	'mwoauthgrants-general-error' => 'Generic error, when something unexpected happened while processing the OAuth request',
	'mwoauthserver-bad-consumer' => "Error message when an invalid consumer identifier was submitted. Parameters:
* $1 - application name
* $2 - central wiki's user talk page",
	'mwoauthserver-bad-consumer-key' => 'Generic error for users when a 3rd-party OAuth developer sends users to an invalid url',
	'mwoauthserver-insufficient-rights' => 'Error message that the user does not have the required rights to perform this request',
	'mwoauthserver-invalid-request-token' => 'Error message when an invalid request token was submitted',
	'mwoauthserver-invalid-user' => 'Error when the user attempts to use OAuth, but they do not have a unified (SUL) account, which is required.

Parameters:
* $1 - application name',
	'mwoauth-invalid-authorization-title' => 'Title of the error page when the Authorization header is invalid',
	'mwoauth-invalid-authorization' => 'Text of the error page when the Authorization header is invalid. Parameters are:
* $1 - Specific error message from the OAuth layer, probably not localized',
	'mwoauth-invalid-authorization-wrong-wiki' => 'Text of the error page when the Authorization header is for the wrong wiki. Parameters are:
* $1 - wiki id',
	'mwoauth-invalid-authorization-invalid-user' => "Text of the error page when the Authorization header is for a user that doesn't exist",
	'mwoauth-invalid-authorization-wrong-user' => 'Text of the error page when the Authorization header is for the wrong user',
	'mwoauth-invalid-authorization-not-approved' => "Text of the error page when the Authorization header is for a consumer that isn't approved.

Parameters:
* $1 - ...",
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
{{Identical|Allow}}',
	'mwoauth-form-button-cancel' => 'Button label, indicating the user wants to cancel granting access.

See also:
* {{msg-mw|Mwoauth-form-button-approve}}
{{Identical|Cancel}}',
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
	'mwoauth-acceptance-cancelled' => 'Message shown when an OAuth authorization request is declined. Parameters:
* $1 - consumer name',
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
	'mwoauth-grant-editmyoptions' => 'Name for OAuth grant "editmyoptions".
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
	'mwoauth-listgrantrights-summary' => 'Explanatory text shown at the top of the grant/rights mapping table.

Refers to {{msg-mw|Listgrouprights-helppage}}.',
	'mwoauth-listgrants-grant' => 'Used as table header for the grant/rights mapping table.
{{Identical|Grant}}',
	'mwoauth-listgrants-rights' => 'Used as table header for the grant/rights mapping table.
{{Identical|Right}}',
	'mwoauth-listgrantrights-right-display' => 'Used to format rights descriptions on the grant/rights mapping table. Parameters:
* $1 - ...
* $2 - ...',
);

/** Arabic (العربية)
 * @author Claw eg
 * @author Ibrahim.ID
 * @author Tarawneh
 * @author مشعل الحربي
 */
$messages['ar'] = array(
	'mwoauth-verified' => "تم السماح لهذا التطبيق بالولوج الى الميدياويكي نيابة عنك.

لإتمام هذه العملية، الرجاء نقل رمز التأكيد الى التطبيق : '''$1'''",
	'mwoauth-missing-field' => 'لم يتم إدخال قيمة في حقل "$1"',
	'mwoauth-invalid-field' => 'قيمة غير صحيحة ضمن حقل :$1"',
	'mwoauth-invalid-field-generic' => 'تم إعطاء قيمة غير صحيحة',
	'mwoauth-field-hidden' => '(هذه المعلومة مخفية)',
	'mwoauth-field-private' => '(هذه المعلومات خاصة)',
	'mwoauth-grant-generic' => '"$1" حزمة الصلاحيات',
	'mwoauth-prefs-managegrants' => 'التطبيقات المتصلة:',
	'mwoauth-prefs-managegrantslink' => 'تحكم $1 {{PLURAL:$1|بالتطبيق المتصل|التطبيقات المتصلة}}',
	'mwoauth-consumer-allwikis' => 'جميع المشاريع ضمن هذا الموقع',
	'mwoauth-consumer-key' => 'رمز المستهلك:',
	'mwoauth-consumer-name' => 'اسم التطبيق:',
	'mwoauth-consumer-version' => 'إصدار المستهلك:',
	'mwoauth-consumer-user' => 'النّاشر:',
	'mwoauth-consumer-stage' => 'الوضع الحالي:',
	'mwoauth-consumer-email' => 'البريد الإلكتروني:',
	'mwoauth-consumer-description' => 'وصف للتطبيق:',
	'mwoauth-consumer-wiki-thiswiki' => 'الويكي الحالية ($1)', # Fuzzy
	'mwoauth-consumer-wiki-other' => 'مشروع معين',
	'mwoauth-consumer-reason' => 'السبب:',
	'mwoauth-consumer-email-unconfirmed' => 'بريدك الألكتروني لم يتم تفعيله بعد.',
	'mwoauth-consumer-email-mismatched' => 'يجب أن يتطابق البريد الإلكتروني مع الحساب الخاص بك.',
	'mwoauthconsumerregistration-main' => 'الرئيسي',
	'mwoauthlistconsumers-view' => 'التفاصيل',
	'mwoauthlistconsumers-name' => 'اسم التطبيق',
	'mwoauthlistconsumers-description' => 'الوصف',
	'mwoauthlistconsumers-status-proposed' => 'مقترح',
	'mwoauthlistconsumers-status-disabled' => 'معطل',
);

/** Assamese (অসমীয়া)
 * @author Gitartha.bordoloi
 */
$messages['as'] = array(
	'mwoauth-prefs-managegrantslink' => 'সংযুক্ত $1 {{PLURAL:$1|এপ্লিকেছনৰ|এপ্লিকেছনসমূহৰ}} পৰিচালনা কৰক',
	'oauthmanagemygrants' => 'সংযোজিত এপ্লিকেছনসমূহৰ ব্যৱস্থাপনা কৰক',
);

/** Asturian (asturianu)
 * @author Xuacu
 */
$messages['ast'] = array(
	'mwoauth-desc' => 'Permite usar OAuth 1.0a pa la identificación de la API',
	'mwoauth-verified' => "Agora, esta aplicación tien permisu pa acceder a MediaWiki nel so nome.

Pa completar el procesu, de-y esti valor de comprobación a la aplicación: '''$1'''",
	'mwoauth-missing-field' => 'Falta el valor del campu "$1"',
	'mwoauth-invalid-field' => 'Diose un valor inválidu pal campu "$1"',
	'mwoauth-invalid-field-generic' => 'Dióse un valor inválidu',
	'mwoauth-field-hidden' => '(esta información ta tapecida)',
	'mwoauth-field-private' => '(esta información ye privada)',
	'mwoauth-grant-generic' => 'Conxuntu de drechos "$1"',
	'mwoauth-prefs-managegrants' => 'Aplicaciones coneutaes:',
	'mwoauth-prefs-managegrantslink' => 'Xestionar $1 {{PLURAL:$1|aplicación coneutada|aplicaciones coneutaes}}',
	'mwoauth-consumer-allwikis' => "Tolos proyeutos d'esti sitiu",
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
	'mwoauth-consumer-wiki' => 'Proyeutu aplicable:',
	'mwoauth-consumer-wiki-thiswiki' => 'Proyeutu actual ($1)',
	'mwoauth-consumer-wiki-other' => 'Proyeutu específicu',
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
	'mwoauth-missing-consumer-key' => 'Nun se dio nenguna clave de consumidor.',
	'mwoauth-invalid-consumer-key' => 'Nun esiste dengún consumidor cola clave dada.',
	'mwoauth-invalid-access-token' => "Nun esiste dengún pase d'accesu cola clave dada.",
	'mwoauth-invalid-access-wrongwiki' => 'El consumidor solo pue usase nel proyeutu «$1».',
	'mwoauth-consumer-conflict' => "Dalguién camudó los atributos d'esti consumidor mentanto lu vía. Por favor, vuelva a intentalo. Pue comprobar el rexistru de cambios.",
	'mwoauth-consumer-grantshelp' => "Cada concesión da accesu a los permisos d'usuariu de la llista qu'una cuenta d'usuariu yá tenga. Vea la [[Special:OAuth/grants|tabla de concesiones]] pa más información.",
	'mwoauth-consumer-stage-proposed' => 'propuestu',
	'mwoauth-consumer-stage-rejected' => 'refugáu',
	'mwoauth-consumer-stage-expired' => 'caducáu',
	'mwoauth-consumer-stage-approved' => 'aprobáu',
	'mwoauth-consumer-stage-disabled' => 'desactiváu',
	'mwoauth-consumer-stage-suppressed' => 'suprimíu',
	'oauthconsumerregistration' => "Rexistru de consumidor d'OAuth",
	'mwoauthconsumerregistration-notloggedin' => "Tien d'aniciar sesión pa entrar nesta páxina.",
	'mwoauthconsumerregistration-navigation' => 'Navegación:',
	'mwoauthconsumerregistration-propose' => 'Proponer un consumidor nuevu',
	'mwoauthconsumerregistration-list' => 'La mio llista de consumidores',
	'mwoauthconsumerregistration-main' => 'Principal',
	'mwoauthconsumerregistration-propose-text' => "Los desendolcadores tendríen d'usar el formulariu de más abaxo pa proponer un nuevu consumidor d'OAuth (vea la [//www.mediawiki.org/wiki/Extension:OAuth documentación de la estensión] pa más detalles). Dempués d'unviar esti formulariu, recibirá un pase qu'usará la so aplicación pa identificase con MediaWiki. Un alministrador d'OAuth tendrá d'aprobar la so aplicación enantes de qu'otros usuarios puedan autorizala.

Delles recomendaciones y comentarios:
* Intente usar les mínimes concesiones posibles. Evite les concesiones que nun se necesiten realmente nesti momentu.
* Les versiones tienen la forma \"mayor.menor.versión\" (les dos últimes son opcionales) y s'incrementen según se necesiten cambios na concesión.
* Por favor, ufra una clave pública RSA (en formatu PEM) si ye posible; d'otra manera tendrá d'usase un pase secretu (menos seguru).
* Use'l campu JSON de restricciones pa llendar l'accesu d'esti consumidor a direiciones IP d'esos rangos CIDR.
* Pue usar una ID de proyeutu pa restrinxir el consumidor a un único proyeutu d'esti sitiu (use \"*\" pa tolos proyeutos).
* La direición de corréu dada tien de casar cola de la so cuenta (que tien de tar confirmada).",
	'mwoauthconsumerregistration-update-text' => "Utilice'l formulariu d'abaxo p'anovar aspeutos d'un consumidor d'OAuth que controle.

Tolos valores d'equí sobreescribirán a cualquiera anterior. Nun dexe campos baleros a menos que quiera llimpiar eses valores.",
	'mwoauthconsumerregistration-maintext' => "Esta páxina ye pa permitir que los desendolcadores propongan y anueven les aplicaciones consumidores d'OAuth del rexistru d'esti sitiu.

Dende equí, pue:
* [[Special:OAuthConsumerRegistration/propose|Solicitar un pase pa un consumidor nuevu]].
* [[Special:OAuthConsumerRegistration/list|Alministrar los consumidores esistentes]].

Pa más información tocante a OAuth, vea la [//www.mediawiki.org/wiki/Extension:OAuth documentación de la estensión].",
	'mwoauthconsumerregistration-propose-legend' => "Nueva aplicación consumidora d'OAuth",
	'mwoauthconsumerregistration-update-legend' => "Anovar una aplicación consumidora d'OAuth",
	'mwoauthconsumerregistration-propose-submit' => 'Proponer un consumidor',
	'mwoauthconsumerregistration-update-submit' => 'Anovar un consumidor',
	'mwoauthconsumerregistration-none' => "Nun controla nengún consumidor d'OAuth.",
	'mwoauthconsumerregistration-name' => 'Consumidor',
	'mwoauthconsumerregistration-user' => 'Editor',
	'mwoauthconsumerregistration-description' => 'Descripción',
	'mwoauthconsumerregistration-email' => 'Corréu de contactu',
	'mwoauthconsumerregistration-consumerkey' => 'Clave del consumidor',
	'mwoauthconsumerregistration-stage' => 'Estáu',
	'mwoauthconsumerregistration-lastchange' => 'Cambéu postreru',
	'mwoauthconsumerregistration-manage' => 'alministrar',
	'mwoauthconsumerregistration-resetsecretkey' => 'Reaniciar la clave secreta a un valor nuevu',
	'mwoauthconsumerregistration-proposed' => "Recibióse la so solicitú de consumidor d'OAuth.

Dióse-y el pase de consumidor '''$1''' ya'l pase secretu '''$2'''. ''Por favor, guarde estos datos pa futures consultes.''",
	'mwoauthconsumerregistration-updated' => "Anovóse correutamente'l so rexistru de consumidor d'OAuth.",
	'mwoauthconsumerregistration-secretreset' => "Dióse-y un pase secretu de consumidor '''$1'''. ''Por favor, guarde esto pa futures consultes.''",
	'oauthmanageconsumers' => "Xestionar consumidores d'Oauth",
	'mwoauthmanageconsumers-notloggedin' => "Tien d'aniciar sesión pa entrar nesta páxina.",
	'mwoauthmanageconsumers-type' => 'Coles:',
	'mwoauthmanageconsumers-showproposed' => 'Solicitúes propuestes',
	'mwoauthmanageconsumers-showrejected' => 'Solicitúes refugaes',
	'mwoauthmanageconsumers-showexpired' => 'Solicitúes caducaes',
	'mwoauthmanageconsumers-main' => 'Principal',
	'mwoauthmanageconsumers-maintext' => "Esta páxina tien l'envís de xestionar solicitúes d'aplicaciones de consumidor d'OAuth (ver http://oauth.net) y alministrar los consumidores d'OAuth establecíos.",
	'mwoauthmanageconsumers-queues' => "Seleicione una cola de confirmación de consumidor d'abaxo:",
	'mwoauthmanageconsumers-q-proposed' => 'Cola de solicitúes de consumidor propuestes',
	'mwoauthmanageconsumers-q-rejected' => 'Cola de solicitúes de consumidor refugaes',
	'mwoauthmanageconsumers-q-expired' => 'Cola de solicitúes de consumidor caducaes',
	'mwoauthmanageconsumers-lists' => "Seleicione una llista d'estáu de consumidor d'abaxo:",
	'mwoauthmanageconsumers-l-approved' => 'Llista de consumidores aprobaos actualmente',
	'mwoauthmanageconsumers-l-disabled' => 'Llista de consumidores desactivaos actualmente',
	'mwoauthmanageconsumers-none-proposed' => 'Nun hai consumidores propuestos nesta llista.',
	'mwoauthmanageconsumers-none-rejected' => 'Nun hai consumidores propuestos nesta llista.',
	'mwoauthmanageconsumers-none-expired' => 'Nun hai consumidores propuestos nesta llista.',
	'mwoauthmanageconsumers-none-approved' => 'Dengún consumidor cumple estos criterios.',
	'mwoauthmanageconsumers-none-disabled' => 'Dengún consumidor cumple estos criterios.',
	'mwoauthmanageconsumers-name' => 'Consumidor',
	'mwoauthmanageconsumers-user' => 'Editor',
	'mwoauthmanageconsumers-description' => 'Descripción',
	'mwoauthmanageconsumers-email' => 'Corréu de contactu',
	'mwoauthmanageconsumers-consumerkey' => 'Clave del consumidor',
	'mwoauthmanageconsumers-lastchange' => 'Cambéu postreru',
	'mwoauthmanageconsumers-review' => 'revisar/alministrar',
	'mwoauthmanageconsumers-confirm-text' => "Use esti formulariu p'aprobar, refugar, desactivar o reactivar esti consumidor.",
	'mwoauthmanageconsumers-confirm-legend' => "Xestionar consumidor d'Oauth",
	'mwoauthmanageconsumers-action' => "Cambiar l'estáu:",
	'mwoauthmanageconsumers-approve' => 'Aprobáu',
	'mwoauthmanageconsumers-reject' => 'Refugáu',
	'mwoauthmanageconsumers-rsuppress' => 'Refugáu y desaniciáu',
	'mwoauthmanageconsumers-disable' => 'Desactiváu',
	'mwoauthmanageconsumers-dsuppress' => 'Desactiváu y desaniciáu',
	'mwoauthmanageconsumers-reenable' => 'Aprobáu',
	'mwoauthmanageconsumers-reason' => 'Motivu:',
	'mwoauthmanageconsumers-confirm-submit' => "Anovar l'estáu del consumidor",
	'mwoauthmanageconsumers-viewing' => "{{GENDER:$1|L'usuariu|La usuaria}} «$1» ta viendo actualmente esti consumidor",
	'mwoauthmanageconsumers-success-approved' => 'Aprobóse la solicitú.',
	'mwoauthmanageconsumers-success-rejected' => 'Refugóse la solicitú.',
	'mwoauthmanageconsumers-success-disabled' => "Desactivóse'l consumidor.",
	'mwoauthmanageconsumers-success-reanable' => "Reactivóse'l consumidor.",
	'mwoauthmanageconsumers-search-name' => 'consumidores con esti nome',
	'mwoauthmanageconsumers-search-publisher' => "consumidores d'esti usuariu",
	'oauthlistconsumers' => "Llista d'aplicaciones OAuth",
	'mwoauthlistconsumers-legend' => 'Ver les aplicaciones OAuth',
	'mwoauthlistconsumers-view' => 'detalles',
	'mwoauthlistconsumers-none' => "Nun s'alcontraron aplicaciones que cumplan estos criterios.",
	'mwoauthlistconsumers-name' => "Nome d'aplicación",
	'mwoauthlistconsumers-version' => 'Versión de consumidor',
	'mwoauthlistconsumers-user' => 'Editor',
	'mwoauthlistconsumers-description' => 'Descripción',
	'mwoauthlistconsumers-wiki' => 'Proyeutu aplicable',
	'mwoauthlistconsumers-callbackurl' => 'URL de "callback" OAuth',
	'mwoauthlistconsumers-grants' => 'Concesiones aplicables',
	'mwoauthlistconsumers-basicgrantsonly' => '(sólo accesu basicu)',
	'mwoauthlistconsumers-status' => 'Estáu',
	'mwoauth-consumer-stage-any' => 'cualesquiera',
	'mwoauthlistconsumers-status-proposed' => 'propuesta',
	'mwoauthlistconsumers-status-approved' => 'aprobada',
	'mwoauthlistconsumers-status-disabled' => 'desactivada',
	'mwoauthlistconsumers-status-rejected' => 'refugada',
	'mwoauthlistconsumers-status-expired' => 'caducada',
	'oauthmanagemygrants' => 'Alministrar les aplicaciones coneutaes',
	'mwoauthmanagemygrants-text' => "Esta páxina recueye toles aplicaciones que puen usar la so cuenta. Pa cualquier aplicación, l'ámbitu d'accesu ta llendáu polos permisos que-y concediera cuando la autorizó a actuar nel so nome. Si autorizó separadamente a una aplicación l'accesu nel so nome a otros proyeutos rellacionaos, más abaxo verá configuraciones separaes pa caún d'esos proyeutos.

Les aplicaciones coneutaes anicien sesión na so cuenta usando'l protocolu OAuth. <span class=\"plainlinks\">([https://www.mediawiki.org/wiki/Special:MyLanguage/Help:OAuth Más información sobro aplicaciones coneutaes])</span>",
	'mwoauthmanagemygrants-notloggedin' => "Tien d'aniciar sesión pa entrar nesta páxina.",
	'mwoauthmanagemygrants-navigation' => 'Navegación:',
	'mwoauthmanagemygrants-showlist' => "Llista d'aplicaciones coneutaes",
	'mwoauthmanagemygrants-none' => 'Nun tien aplicaciones coneutaes cola so cuenta.',
	'mwoauthmanagemygrants-user' => 'Editorial:',
	'mwoauthmanagemygrants-description' => 'Descripción',
	'mwoauthmanagemygrants-wikiallowed' => 'Permitida nel proyeutu:',
	'mwoauthmanagemygrants-grants' => 'Permisos aplicables',
	'mwoauthmanagemygrants-grantsallowed' => 'Permisos concedíos',
	'mwoauthmanagemygrants-applicablegrantsallowed' => 'Permisos aplicables concedíos:',
	'mwoauthmanagemygrants-review' => "alministrar l'accesu",
	'mwoauthmanagemygrants-revoke' => 'torgar accesu',
	'mwoauthmanagemygrants-grantaccept' => 'Permitíu',
	'mwoauthmanagemygrants-update-text' => "Use'l formulariu de más abaxo pa camudar los permisos concedíos a una aplicación p'actuar nel so nome.",
	'mwoauthmanagemygrants-revoke-text' => "Use'l formulariu de más abaxo pa torgar l'accesu a una aplicación p'actuar nel so nome.",
	'mwoauthmanagemygrants-confirm-legend' => 'Alministrar aplicación coneutada',
	'mwoauthmanagemygrants-update' => 'Anovar permisos',
	'mwoauthmanagemygrants-renounce' => 'Desautorizar',
	'mwoauthmanagemygrants-action' => "Cambiar l'estáu:",
	'mwoauthmanagemygrants-confirm-submit' => "Anovar l'estáu del pase d'accesu",
	'mwoauthmanagemygrants-success-update' => "Anovóse'l pase d'accesu d'esti consumidor.",
	'mwoauthmanagemygrants-success-renounce' => "Desanicióse'l pase d'accesu d'esti consumidor.",
	'logentry-mwoauthconsumer-propose' => '$1 {{GENDER:$2|propunxo}} un consumidor OAuth (clave de consumidor $4)',
	'logentry-mwoauthconsumer-update' => '$1 {{GENDER:$2|anovó}} un consumidor OAuth (clave de consumidor $4)',
	'logentry-mwoauthconsumer-approve' => '$1 {{GENDER:$2|aprobó}} un consumidor OAuth pa $3 (clave de consumidor $4)',
	'logentry-mwoauthconsumer-reject' => '$1 {{GENDER:$2|refugó}} un consumidor OAuth pa $3 (clave de consumidor $4)',
	'logentry-mwoauthconsumer-disable' => '$1 {{GENDER:$2|desactivó}} un consumidor OAuth pa $3 (clave de consumidor $4)',
	'logentry-mwoauthconsumer-reenable' => '$1 {{GENDER:$2|reactivó}} un consumidor OAuth pa $3 (clave de consumidor $4)',
	'mwoauthconsumer-consumer-logpage' => 'Rexistru de consumidor OAuth',
	'mwoauthconsumer-consumer-logpagetext' => "Rexistru d'aprobaciones, refugos y desactivaciones de los consumidores OAuth rexistraos.",
	'mwoauthdatastore-bad-token' => "Nun s'alcontró dengún pase que case cola so solicitú",
	'mwoauthdatastore-bad-verifier' => 'El códigu de comprobación que se dio nun ye válidu',
	'mwoauthdatastore-invalid-token-type' => 'O tipu de pase solicitáu ye inválidu.',
	'mwoauthgrants-general-error' => 'Hebo un error na so solicitú OAuth.',
);

/** Belarusian (Taraškievica orthography) (беларуская (тарашкевіца)‎)
 * @author Red Winged Duck
 */
$messages['be-tarask'] = array(
	'mwoauth-prefs-managegrants' => 'Падключаныя дадаткі:',
	'mwoauth-prefs-managegrantslink' => 'Кіраваць $1 {{PLURAL:$1|падключаным дадаткам|падключанымі дадаткамі}}',
	'oauthlistconsumers' => 'Сьпіс праграмаў OAuth',
	'oauthmanagemygrants' => 'Кіраваньне падключанымі праграмамі',
	'right-mwoauthproposeconsumer' => 'прапаноўваць новых спажыўцоў OAuth',
	'action-mwoauthproposeconsumer' => 'прапанову новых спажыўцоў OAuth',
);

/** Bengali (বাংলা)
 * @author Aftab1995
 * @author Gitartha.bordoloi
 * @author Tauhid16
 */
$messages['bn'] = array(
	'mwoauth-prefs-managegrants' => 'সংযুক্ত অ্যাপগুলি:',
	'mwoauth-prefs-managegrantslink' => 'সংযুক্ত $1টি {{PLURAL:$1|অ্যাপ্লিকেশন}} পরিচালনা করুন',
	'mwoauthconsumerregistration-main' => 'প্রধান',
	'mwoauthconsumerregistration-description' => 'বিবরণ',
	'mwoauthconsumerregistration-stage' => 'অবস্থা',
	'mwoauthconsumerregistration-lastchange' => 'সর্বশেষ পরিবর্তন',
	'mwoauthmanageconsumers-main' => 'প্রধান',
	'mwoauthmanageconsumers-reason' => 'কারণ:',
	'mwoauth-grant-editmyoptions' => 'আপনি আপনার পছন্দসমূহ সম্পাদনা করুন',
	'mwoauth-grant-editmywatchlist' => 'আপনার নজরতালিকা সম্পাদনা করুন',
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
	'mwoauth-listgrants-rights' => 'Gwirioù',
);

/** Catalan (català)
 * @author Pginer
 */
$messages['ca'] = array(
	'mwoauthlistconsumers-view' => 'detalls',
);

/** Chechen (нохчийн)
 * @author Умар
 */
$messages['ce'] = array(
	'mwoauth-desc' => 'OAuth 1.0a лелича йиш хуьлуьйту API чудаха',
	'mwoauth-prefs-managegrants' => 'ТӀетесна тӀетохарш:',
	'mwoauth-prefs-managegrantslink' => 'ТӀетесна $1 {{PLURAL:$1|1=тӀетохаран урхалладар|тӀетохаршан урхалладар}}', # Fuzzy
	'mwoauth-consumer-allwikis' => 'ХӀокху сайтан массо проекташ',
	'mwoauth-consumer-name' => 'ТӀетохаран цӀе:',
	'mwoauth-consumer-stage' => 'Карара хьал:',
	'mwoauth-consumer-description' => 'Цуьнах лаьцна:',
	'mwoauth-consumer-wiki' => 'Проектан лело мега:',
	'mwoauthconsumerregistration-stage' => 'Хьал',
	'mwoauthmanageconsumers-description' => 'Цуьнах лаьцна',
	'oauthlistconsumers' => 'ТеӀетохаршан могӀа',
	'mwoauthlistconsumers-description' => 'Цуьнах лаьцна',
	'mwoauthlistconsumers-wiki' => 'Проектан лело мега',
	'mwoauthlistconsumers-callbackurl' => '«URL-адрес юха кхайкхаман» OAuth:',
	'mwoauthlistconsumers-basicgrantsonly' => '(базан тӀекхачар бен)',
	'mwoauthlistconsumers-status' => 'Хьал',
	'oauthmanagemygrants' => 'ТӀетесна тӀетохаршан урхалладар',
	'mwoauthmanagemygrants-showlist' => 'ТӀетесна тӀетохаршан могӀа',
	'mwoauthmanagemygrants-none' => 'Хьан декъашхочун дӀаяздаран хӀинца яц тӀетесна тӀетохарш.',
	'mwoauthmanagemygrants-description' => 'Цуьнах лаьцна',
	'mwoauth-grant-group-email' => 'ДӀадахьийта кехат',
	'mwoauth-grant-editmyoptions' => 'Табе хьай гӀирс',
	'mwoauth-grant-editmywatchlist' => 'Хьан тергаме могӀам табар',
	'mwoauth-grant-editpage' => 'Тае йолуш йолу агӀо',
	'mwoauth-grant-editprotected' => 'ГӀоралла дина агӀонаш таяр',
	'mwoauth-grant-uploadfile' => 'Чуяха керла файлаш',
	'mwoauth-grant-viewmywatchlist' => 'Шен тергаме могӀаме хьажар',
	'mwoauth-listgrants-rights' => 'Бакъонаш',
);

/** Sorani Kurdish (کوردی)
 * @author Calak
 */
$messages['ckb'] = array(
	'oauthmanageconsumers' => 'بەڕێوەبەردنی بەکاربەرانی OAuth',
	'right-mwoauthproposeconsumer' => 'پێشنیاری بەکاربەرانی OAuthی نوێ',
	'right-mwoauthupdateownconsumer' => 'نوێکردنەوەی بەکاربەرانی OAuthی تاوتوێی دەکەی',
	'right-mwoauthmanageconsumer' => 'بەڕێوەبەردنی بەکاربەرانی OAuth',
	'right-mwoauthmanagemygrants' => 'بەڕێوەبەردنی مافەکانی OAuth',
	'action-mwoauthmanageconsumer' => 'بەڕێوەبەردنی بەکاربەرانی OAuth',
	'action-mwoauthmanagemygrants' => 'بەڕێوەبەردنی مافەکانی OAuthەکەت',
	'action-mwoauthproposeconsumer' => 'پێشنیاری بەکاربەرانی OAuthی نوێ',
	'action-mwoauthupdateownconsumer' => 'نوێکردنەوەی بەکاربەرانی OAuthی تاوتوێی دەکەی',
);

/** Czech (čeština)
 * @author Matěj Grabovský
 * @author Matěj Suchánek
 * @author Mormegil
 */
$messages['cs'] = array(
	'mwoauth-desc' => 'Umožňuje využití OAuth 1.0a pro autorizaci přístupu k API',
	'mwoauth-verified' => "Tato aplikace má nyní oprávnění přistupovat k MediaWiki vaším jménem.

Pro dokončení procesu poskytněte aplikaci tuto ověřovací hodnotu: '''$1'''",
	'mwoauth-missing-field' => 'Chybějící hodnota pole „$1“',
	'mwoauth-invalid-field' => 'Uvedena neplatná hodnota pole „$1“',
	'mwoauth-invalid-field-generic' => 'Zadána neplatná hodnota',
	'mwoauth-field-hidden' => '(tato informace je skryta)',
	'mwoauth-field-private' => '(tato informace je soukromá)',
	'mwoauth-grant-generic' => 'Balíček oprávnění „$1“',
	'mwoauth-prefs-managegrants' => 'Připojené aplikace:',
	'mwoauth-prefs-managegrantslink' => 'Spravovat $1 {{PLURAL:$1|připojenou aplikaci|připojené aplikace|připojených aplikací}}',
	'mwoauth-consumer-allwikis' => 'Všechny projekty na tomto webu',
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
	'mwoauth-consumer-wiki' => 'Použitelný projekt:',
	'mwoauth-consumer-wiki-thiswiki' => 'Tento projekt ($1)',
	'mwoauth-consumer-wiki-other' => 'Konkrétní projekt',
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
	'mwoauth-missing-consumer-key' => 'Nebyl poskytnut klíč konzumenta.',
	'mwoauth-invalid-consumer-key' => 'Žádný konzument s daným klíčem neexistuje.',
	'mwoauth-invalid-access-token' => 'Žádný přístupový token s daným klíčem neexistuje.',
	'mwoauth-invalid-access-wrongwiki' => 'Tohoto konzumenta lze používat pouze na projektu „$1“.',
	'mwoauth-consumer-conflict' => 'Zatímco jste si tohoto konzumenta {{GENDER:|prohlížel|prohlížela|prohlíželi}}, někdo změnil jeho atributy. Možná si budete chtít prohlédnout protokol změn.',
	'mwoauth-consumer-grantshelp' => 'Každé oprávnění přiděluje přístup k uvedeným uživatelským právům, která příslušný uživatelský účet již má. Více informací najdete v [[Special:OAuth/grants|tabulce oprávnění]].',
	'mwoauth-consumer-stage-proposed' => 'navržený',
	'mwoauth-consumer-stage-rejected' => 'odmítnutý',
	'mwoauth-consumer-stage-expired' => 'propadlý',
	'mwoauth-consumer-stage-approved' => 'schválený',
	'mwoauth-consumer-stage-disabled' => 'zakázaný',
	'mwoauth-consumer-stage-suppressed' => 'utajený',
	'oauthconsumerregistration' => 'Registrace konzumenta OAuth',
	'mwoauthconsumerregistration-notloggedin' => 'Pro přístup k této stránce musíte být přihlášen(a).',
	'mwoauthconsumerregistration-navigation' => 'Navigace:',
	'mwoauthconsumerregistration-propose' => 'Navrhnout nového konzumenta',
	'mwoauthconsumerregistration-list' => 'Seznam mých konzumentů',
	'mwoauthconsumerregistration-main' => 'Hlavní',
	'mwoauthconsumerregistration-propose-text' => 'Vývojáři by měli používat níže zobrazený formulář k navržení nového konzumenta OAuth (podrobnosti najdete v [//www.mediawiki.org/wiki/Extension:OAuth?uselang=cs dokumentaci rozšíření]). Po odeslání tohoto formuláře obdržíte token, pomocí kterého se vaše aplikace bude identifikovat MediaWiki. Předtím, než budou moci ostatní uživatelé autorizovat vaši aplikaci, bude ji muset schválit některý správce OAuth.

Několik doporučení a poznámek:
* Snažte se používat co nejméně oprávnění. Vyhněte se těm, která ve skutečnosti zatím nepotřebujete.
* Verze má tvar „major.minor.release“ (poslední dvě části jsou nepovinné) a zvyšuje se, když jsou potřeba změny oprávnění.
* Pokud je to možné, poskytněte veřejný klíč RSA (ve formátu PEM), jinak se musí používat (méně bezpečný) tajný token.
* Pomocí omezení v JSON můžete omezit tomuto konzumentu přístup jen na IP adresy v daných rozsazích CIDR.
* Pomocí ID wiki můžete omezit tohoto konzumenta na jediný projekt na tomto serveru (pro všechny projekty uveďte „*“).
* Zadaná e-mailová adresa musí odpovídat té na vašem uživatelském účtu (která musí být ověřena).',
	'mwoauthconsumerregistration-update-text' => 'Pomocí níže uvedeného formuláře můžete změnit vlastnosti konzumenta OAuth, kterého spravujete.

Všechny uvedené hodnoty přepíšou ty původní. Neponechávejte žádná pole prázdná, pokud nechcete jejich hodnoty smazat.',
	'mwoauthconsumerregistration-maintext' => 'Tato stránka slouží k navrhování a změnám konzumentských aplikací OAuth (vizte http://oauth.net) v registru tohoto serveru.

Můžete zde [[Special:OAuthConsumerRegistration/propose|navrhnout nového konzumenta]] nebo [[Special:OAuthConsumerRegistration/list|spravovat své existující konzumenty]].',
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
	'oauthmanageconsumers' => 'Správa konzumentů OAuth',
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
	'mwoauthmanageconsumers-search-name' => 'konzumenti s tímto názvem',
	'mwoauthmanageconsumers-search-publisher' => 'konzumenti tohoto uživatele',
	'oauthlistconsumers' => 'Seznam aplikací OAuth',
	'mwoauthlistconsumers-legend' => 'Procházet aplikace OAuth',
	'mwoauthlistconsumers-view' => 'podrobnosti',
	'mwoauthlistconsumers-none' => 'Nenalezena žádná aplikace odpovídající těmto kritériím.',
	'mwoauthlistconsumers-name' => 'Název aplikace',
	'mwoauthlistconsumers-version' => 'Verze konzumenta',
	'mwoauthlistconsumers-user' => 'Vydavatel',
	'mwoauthlistconsumers-description' => 'Popis',
	'mwoauthlistconsumers-wiki' => 'Použitelný projekt',
	'mwoauthlistconsumers-callbackurl' => 'URL pro OAuth „callback“',
	'mwoauthlistconsumers-grants' => 'Použitelná oprávnění',
	'mwoauthlistconsumers-basicgrantsonly' => '(pouze základní přístup)',
	'mwoauthlistconsumers-status' => 'Stav',
	'mwoauth-consumer-stage-any' => 'všechny',
	'mwoauthlistconsumers-status-proposed' => 'navržený',
	'mwoauthlistconsumers-status-approved' => 'schválený',
	'mwoauthlistconsumers-status-disabled' => 'zakázaný',
	'mwoauthlistconsumers-status-rejected' => 'odmítnutý',
	'mwoauthlistconsumers-status-expired' => 'propadlý',
	'oauthmanagemygrants' => 'Správa připojených aplikací',
	'mwoauthmanagemygrants-text' => 'Tato stránka obsahuje seznam aplikací, které mohou využívat váš účet. U každé takové aplikace je rozsah jejího přístupu omezen oprávněními, která jste aplikaci {{GENDER:|přidělil|přidělila|přidělili}} v okamžiku, kdy jste jí {{GENDER:|dovolil|dovolila|dovolili}} jednat vaším jménem. Pokud jste aplikaci {{GENDER:|dovolil|dovolila|dovolili}} jednat vaším jménem nezávisle na různých sesterských projektech, uvidíte níže oddělené konfigurace pro každý takový projekt.

Připojené aplikace přistupují k vašemu účtu pomocí protokolu OAuth. <span class="plainlinks">([https://www.mediawiki.org/wiki/Special:MyLanguage/Help:OAuth?uselang=cs Více informací o připojených aplikacích])</span>',
	'mwoauthmanagemygrants-notloggedin' => 'Pro přístup k této stránce musíte být přihlášen(a).',
	'mwoauthmanagemygrants-navigation' => 'Navigace:',
	'mwoauthmanagemygrants-showlist' => 'Seznam připojených aplikací',
	'mwoauthmanagemygrants-none' => 'K vašemu účtu nejsou připojeny žádné aplikace.',
	'mwoauthmanagemygrants-user' => 'Vydavatel:',
	'mwoauthmanagemygrants-description' => 'Popis',
	'mwoauthmanagemygrants-wikiallowed' => 'Povoleno na projektu:',
	'mwoauthmanagemygrants-grants' => 'Použitelná oprávnění',
	'mwoauthmanagemygrants-grantsallowed' => 'Přidělená oprávnění',
	'mwoauthmanagemygrants-applicablegrantsallowed' => 'Přidělená použitelná oprávnění:',
	'mwoauthmanagemygrants-review' => 'spravovat přístup',
	'mwoauthmanagemygrants-revoke' => 'odebrat přístup',
	'mwoauthmanagemygrants-grantaccept' => 'Přiděleno',
	'mwoauthmanagemygrants-update-text' => 'Pomocí níže zobrazeného formuláře můžete změnit oprávnění přidělená aplikaci, aby mohla jednat vaším jménem.
* Pokud jste aplikaci {{GENDER:|dovolil|dovolila|dovolili}} jednat vaším jménem nezávisle na různých sesterských projektech, budete pro tuto aplikaci mít oddělené konfigurace pro každý takový projekt.',
	'mwoauthmanagemygrants-revoke-text' => 'Pomocí níže zobrazeného formuláře můžete odvolat oprávnění aplikaci jednat vaším jménem.
* Pokud jste aplikaci {{GENDER:|dovolil|dovolila|dovolili}} jednat vaším jménem nezávisle na různých sesterských projektech, budete pro tuto aplikaci mít oddělené konfigurace pro každý takový projekt.
* Pokud chcete aplikaci oprávnění zcela odebrat, ujistěte se, že jste jí oprávnění {{GENDER:|odebral|odebrala|odebrali}} pro všechny projekty, na kterých jste ji {{GENDER:|schválil|schválila|schválili}}.',
	'mwoauthmanagemygrants-confirm-legend' => 'Správa připojené aplikace',
	'mwoauthmanagemygrants-update' => 'Aktualizovat oprávnění',
	'mwoauthmanagemygrants-renounce' => 'Zrušit autorizaci',
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
	'mwoauth-bad-request-missing-params' => 'Omlouváme se, ale při konfiguraci této připojené aplikace se něco rozbilo. Pro pomoc s řešením <span class="plainlinks">[https://www.mediawiki.org/wiki/Special:MyLanguage/Help:OAuth?uselang=cs kontaktujte podporu]</span>.

<span class="plainlinks mw-mwoautherror-details">Chybí parametry OAuth, [https://www.mediawiki.org/wiki/Help:OAuth/Errors?uselang=cs#E001 E001]</span>',
	'mwoauth-bad-request-invalid-action' => 'Omlouváme se, něco se rozbilo, pro pomoc s řešením budete muset kontaktovat autora aplikace.

<span class="plainlinks mw-mwoautherror-details">Neznámé URL, [https://www.mediawiki.org/wiki/Help:OAuth/Errors?uselang=cs#E002 E002]</span>',
	'mwoauth-bad-request-invalid-action-contact' => 'Omlouváme se, něco se rozbilo. Pro pomoc s řešením budete muset [$1 kontaktovat] autora aplikace.

<span class="plainlinks mw-mwoautherror-details">Neznámé URL, [https://www.mediawiki.org/wiki/Help:OAuth/Errors?uselang=cs#E003 E003]</span>',
	'mwoauthdatastore-access-token-not-found' => 'K tomuto schválenému autorizačnímu tokenu nebylo nalezeno žádné schválené oprávnění.',
	'mwoauthdatastore-request-token-not-found' => 'Omlouváme se, ale při připojování této aplikace se něco rozbilo.
Vraťte se a zkuste znovu připojit svůj účet, nebo kontaktujte autora aplikace.

<span class="plainlinks mw-mwoautherror-details">OAuth token nebyl nalezen, [https://www.mediawiki.org/wiki/Help:OAuth/Errors?uselang=cs#E004 E004]</span>',
	'mwoauthdatastore-bad-token' => 'Žádný token odpovídající vašemu požadavku nebyl nalezen.',
	'mwoauthdatastore-bad-verifier' => 'Poskytnutý ověřovací kód nebyl platný.',
	'mwoauthdatastore-invalid-token-type' => 'Požadovaný typ tokenu není platný.',
	'mwoauthgrants-general-error' => 'Ve vašem OAuth požadavku byla chyba.',
	'mwoauthserver-bad-consumer' => '„$1“ už není schválená Připojená aplikace, pro pomoc [$2 kontaktujte] autora aplikace.

<span class="plainlinks mw-mwoautherror-details">Připojená OAuth aplikace není schválena, [https://www.mediawiki.org/wiki/Help:OAuth/Errors?uselang=cs#E005 E005]</span>',
	'mwoauthserver-bad-consumer-key' => 'Omlouváme se, ale při připojování této aplikace se něco rozbilo.

<span class="plainlinks mw-mwoautherror-details">Neznámý klíč OAuth, [https://www.mediawiki.org/wiki/Help:OAuth/Errors?uselang=cs#E006 E006]</span>',
	'mwoauthserver-insufficient-rights' => 'Váš účet nemá dovoleno využívat Připojené aplikace, pro zjištění důvodů kontaktujte správce vašeho serveru.

<span class="plainlinks mw-mwoautherror-details">Nedostatečná práva uživatele OAuth, [https://www.mediawiki.org/wiki/Help:OAuth/Errors?uselang=cs#E007 E007]</span>',
	'mwoauthserver-invalid-request-token' => 'Váš požadavek obsahuje neplatný token.',
	'mwoauthserver-invalid-user' => 'Abyste na tomto serveru {{GENDER:|mohl|mohla|mohli}} používat Připojené aplikace, musíte mít účet sjednocený přes všechny projekty. Jakmile budete mít sjednocený účet, můžete zkusit znovu připojit aplikaci „$1“.

<span class="plainlinks mw-mwoautherror-details">Vyžadován sjednocený účet, [https://www.mediawiki.org/wiki/Help:OAuth/Errors?uselang=cs#E008 E008]</span>',
	'mwoauth-invalid-authorization-title' => 'Chyba autorizace OAuth',
	'mwoauth-invalid-authorization' => 'Autorizační hlavičky ve vašem požadavku nejsou platné: $1',
	'mwoauth-invalid-authorization-wrong-wiki' => 'Autorizační hlavičky ve vašem požadavku nejsou pro $1 platné',
	'mwoauth-invalid-authorization-invalid-user' => 'Autorizační hlavičky ve vašem požadavku jsou pro uživatele, který zde neexistuje',
	'mwoauth-invalid-authorization-wrong-user' => 'Autorizační hlavičky ve vašem požadavku jsou pro jiného uživatele',
	'mwoauth-invalid-authorization-not-approved' => 'Aplikace, kterou chcete připojit, byla zřejmě chybně nastavena. Pro pomoc kontaktujte autora aplikace „$1“.',
	'mwoauth-invalid-authorization-blocked-user' => 'Autorizační hlavičky ve vašem požadavku jsou pro uživatele, který je zablokován',
	'mwoauth-form-description-allwikis' => "{{GENDER:$1|Uživateli|Uživatelko}} $1,

aplikace '''$2''' by chtěla vaším jménem na všech projektech tohoto webu provádět následující aktivity:

$4",
	'mwoauth-form-description-onewiki' => "{{GENDER:$1|Uživateli|Uživatelko}} $1,

aplikace '''$2''' by chtěla vaším jménem na ''{{grammar:6sg|$4}}'' provádět následující aktivity:

$5",
	'mwoauth-form-description-allwikis-nogrants' => "{{GENDER:$1|Uživateli|Uživatelko}} $1,

aplikace '''$2''' by chtěla získat základní přístup vaším jménem na všechny projekty tohoto webu.",
	'mwoauth-form-description-onewiki-nogrants' => "{{GENDER:$1|Uživateli|Uživatelko}} $1,

aplikace '''$2''' by chtěla získat základní přístup vaším jménem na ''{{grammar:6sg|$4}}''.",
	'mwoauth-form-privacypolicy-link' => '[[{{ns:Project}}:Privacy policy|Ochrana osobních údajů]]',
	'mwoauth-form-button-approve' => 'Dovolit',
	'mwoauth-form-button-cancel' => 'Storno',
	'mwoauth-error' => 'Chyba připojení aplikace',
	'mwoauth-grants-heading' => 'Vyžadovaná oprávnění:',
	'mwoauth-grants-nogrants' => 'Tato aplikace nevyžaduje žádná oprávnění.',
	'mwoauth-acceptance-cancelled' => '{{GENDER:|Rozhodl|Rozhodla|Rozhodli}} jste se nedovolit aplikaci „$1“ přístup k vašemu účtu. „$1“ nebude fungovat, dokud jí přístup nedovolíte. Můžete jít zpět na „$1“, nebo [[Special:OAuthManageMyGrants|spravovat]] vaše připojené aplikace.',
	'mwoauth-grant-group-page-interaction' => 'Interakce se stránkami',
	'mwoauth-grant-group-file-interaction' => 'Interakce se soubory',
	'mwoauth-grant-group-watchlist-interaction' => 'Interakce s vaším seznamem sledovaných stránek',
	'mwoauth-grant-group-email' => 'Rozesílání e-mailů',
	'mwoauth-grant-group-high-volume' => 'Velkoobjemové činnosti',
	'mwoauth-grant-group-customization' => 'Nastavení a přizpůsobení',
	'mwoauth-grant-group-administration' => 'Provádění správcovských činností',
	'mwoauth-grant-group-other' => 'Různé činnosti',
	'mwoauth-grant-blockusers' => 'Blokovat a odblokovávat uživatele',
	'mwoauth-grant-createaccount' => 'Zakládat účty',
	'mwoauth-grant-createeditmovepage' => 'Zakládat, editovat a přesouvat stránky',
	'mwoauth-grant-delete' => 'Mazat stránky, revize a protokolovací záznamy',
	'mwoauth-grant-editinterface' => 'Editovat jmenný prostor MediaWiki a uživatelské CSS/JS',
	'mwoauth-grant-editmycssjs' => 'Editovat vaše vlastní uživatelské CSS/JS',
	'mwoauth-grant-editmywatchlist' => 'Upravovat váš seznam sledovaných stránek',
	'mwoauth-grant-editpage' => 'Editovat existující stránky',
	'mwoauth-grant-editprotected' => 'Editovat zamčené stránky',
	'mwoauth-grant-highvolume' => 'Hromadné editace',
	'mwoauth-grant-oversight' => 'Skrývat uživatele a utajovat revize',
	'mwoauth-grant-patrol' => 'Patrolovat změny stránek',
	'mwoauth-grant-protect' => 'Zamykat a odemykat stránky',
	'mwoauth-grant-rollback' => 'Vracet editace zpět',
	'mwoauth-grant-sendemail' => 'Posílat e-maily ostatním uživatelům',
	'mwoauth-grant-uploadeditmovefile' => 'Načítat, nahrazovat a přesouvat soubory',
	'mwoauth-grant-uploadfile' => 'Načítat nové soubory',
	'mwoauth-grant-useoauth' => 'Základní oprávnění',
	'mwoauth-grant-viewdeleted' => 'Prohlížet si smazané údaje',
	'mwoauth-grant-viewmywatchlist' => 'Prohlížet si váš seznam sledovaných stránek',
	'mwoauth-oauth-exception' => 'V protokolu OAuth došlo k chybě: $1',
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
	'mwoauth-listgrantrights-summary' => 'Následující seznam obsahuje oprávnění OAuth s odpovídajícím přístupem k uživatelským právům. Uživatelé mohou aplikace autorizovat k využití jejich účtu, ale s omezenými právy na základě oprávnění, která uživatel aplikaci přidělil. Aplikace konající jménem uživatele ale nemůže využít oprávnění, která uživatel nemá.
K jednotlivým oprávněním mohou existovat [[{{MediaWiki:Listgrouprights-helppage}}|doplňující informace]].',
	'mwoauth-listgrants-grant' => 'Oprávnění OAuth',
	'mwoauth-listgrants-rights' => 'Uživatelská práva',
);

/** German (Deutsch)
 * @author Metalhead64
 * @author Se4598
 * @author Wnme
 */
$messages['de'] = array(
	'mwoauth-desc' => 'Ermöglicht die Verwendung von OAuth 1.0a zur API-Autorisierung',
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
	'mwoauth-consumer-allwikis' => 'Alle Projekte dieser Website',
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
	'mwoauth-consumer-wiki' => 'Anwendbares Projekt:',
	'mwoauth-consumer-wiki-thiswiki' => 'Aktuelles Projekt ($1)',
	'mwoauth-consumer-wiki-other' => 'Spezielles Projekt',
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
	'mwoauth-invalid-access-wrongwiki' => 'Der Verbraucher kann nur auf dem Projekt „$1“ verwendet werden.',
	'mwoauth-consumer-conflict' => 'Ein anderer hat bereits die Attribute dieses Verbrauchers geändert. Bitte erneut versuchen. Du kannst auch das Änderungs-Logbuch überprüfen.',
	'mwoauth-consumer-grantshelp' => 'Jede Berechtigung ermöglicht einen Zugriff auf gelistete Benutzerrechte, die das Benutzerkonto bereits hat. Siehe die [[Special:OAuth/grants|tabellarische Übersicht]] für mehr Informationen.',
	'mwoauth-consumer-stage-proposed' => 'geplant',
	'mwoauth-consumer-stage-rejected' => 'abgelehnt',
	'mwoauth-consumer-stage-expired' => 'abgelaufen',
	'mwoauth-consumer-stage-approved' => 'bestätigt',
	'mwoauth-consumer-stage-disabled' => 'deaktiviert',
	'mwoauth-consumer-stage-suppressed' => 'unterdrückt',
	'oauthconsumerregistration' => 'OAuth-Anwendungsregistrierung',
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
* Du kannst eine Projektkennung verwenden, um den Verbraucher auf ein einzelnes Projekt auf dieser Website zu beschränken (verwende „*“ für alle Projekte).
* Die angegebene E-Mail-Adresse muss mit der deines Benutzerkontos übereinstimmen und bestätigt sein.',
	'mwoauthconsumerregistration-update-text' => 'Verwende das unten stehende Formular, um Aspekte eines von dir kontrollierten OAuth-Verbrauchers zu aktualisieren.

Alle Werte hier überschreiben alle vorherigen. Hinterlasse keine leeren Felder, außer du beabsichtigst, diese Werte zu löschen.',
	'mwoauthconsumerregistration-maintext' => 'Diese Seite dient zum Vorschlagen und Aktualisieren von OAuth-Anwendungen in der Websiteregistrierung durch Entwickler.

Du kannst hier
* [[Special:OAuthConsumerRegistration/propose|einen Token für eine neue Anwendung anfordern]] oder
* [[Special:OAuthConsumerRegistration/list|deine vorhandenen Anwendungen verwalten]].

Für mehr Informationen über OAuth, siehe die [//www.mediawiki.org/wiki/Extension:OAuth Erweiterungsdokumentation].',
	'mwoauthconsumerregistration-propose-legend' => 'Neue OAuth-Verbraucheranwendung',
	'mwoauthconsumerregistration-update-legend' => 'OAuth-Verbraucheranwendung aktualisieren',
	'mwoauthconsumerregistration-propose-submit' => 'Verbraucher planen',
	'mwoauthconsumerregistration-update-submit' => 'Verbraucher aktualisieren',
	'mwoauthconsumerregistration-none' => 'Du kontrollierst keine OAuth-Anwendungen.',
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
	'oauthmanageconsumers' => 'OAuth-Anwendungen verwalten',
	'mwoauthmanageconsumers-notloggedin' => 'Du musst angemeldet sein, um auf diese Seite zugreifen zu können.',
	'mwoauthmanageconsumers-type' => 'Warteschlangen:',
	'mwoauthmanageconsumers-showproposed' => 'Geplante Anträge',
	'mwoauthmanageconsumers-showrejected' => 'Abgelehnte Anträge',
	'mwoauthmanageconsumers-showexpired' => 'Abgelaufene Anträge',
	'mwoauthmanageconsumers-main' => 'Start',
	'mwoauthmanageconsumers-maintext' => 'Diese Seite ist zur Abwicklung von OAuth-Anwendungsanträgen (siehe http://oauth.net) und zum Verwalten von bestehenden OAuth-Anwendungen gedacht.',
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
	'mwoauthmanageconsumers-confirm-legend' => 'OAuth-Anwendung verwalten',
	'mwoauthmanageconsumers-action' => 'Status ändern:',
	'mwoauthmanageconsumers-approve' => 'Bestätigt',
	'mwoauthmanageconsumers-reject' => 'Abgelehnt',
	'mwoauthmanageconsumers-rsuppress' => 'Abgelehnt und unterdrückt',
	'mwoauthmanageconsumers-disable' => 'Deaktiviert',
	'mwoauthmanageconsumers-dsuppress' => 'Deaktiviert und unterdrückt',
	'mwoauthmanageconsumers-reenable' => 'Bestätigt',
	'mwoauthmanageconsumers-reason' => 'Grund:',
	'mwoauthmanageconsumers-confirm-submit' => 'Verbraucherstatus aktualisieren',
	'mwoauthmanageconsumers-viewing' => '{{GENDER:$1|Der Benutzer|Die Benutzerin}} „$1“ betrachtet derzeit diesen Verbraucher',
	'mwoauthmanageconsumers-success-approved' => 'Der Antrag wurde bestätigt.',
	'mwoauthmanageconsumers-success-rejected' => 'Der Antrag wurde abgelehnt.',
	'mwoauthmanageconsumers-success-disabled' => 'Der Verbraucher wurde deaktiviert.',
	'mwoauthmanageconsumers-success-reanable' => 'Der Verbraucher wurde reaktiviert.',
	'mwoauthmanageconsumers-search-name' => 'Verbraucher mit diesem Namen',
	'mwoauthmanageconsumers-search-publisher' => 'Verbraucher von diesem Benutzer',
	'oauthlistconsumers' => 'Liste der OAuth-Anwendungen',
	'mwoauthlistconsumers-legend' => 'OAuth-Anwendungen durchsuchen',
	'mwoauthlistconsumers-view' => 'Einzelheiten',
	'mwoauthlistconsumers-none' => 'Es wurden keine Anwendungen gefunden, die diesen Kriterien entsprechen.',
	'mwoauthlistconsumers-name' => 'Anwendungsname',
	'mwoauthlistconsumers-version' => 'Verbraucherversion',
	'mwoauthlistconsumers-user' => 'Herausgeber',
	'mwoauthlistconsumers-description' => 'Beschreibung',
	'mwoauthlistconsumers-wiki' => 'Anwendbares Projekt',
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
	'oauthmanagemygrants' => 'Verbundene Anwendungen verwalten',
	'mwoauthmanagemygrants-text' => 'Diese Seite listet alle Anwendungen auf, die dein Benutzerkonto verwenden können. Für jede Anwendung ist der Zugriffsbereich durch die von dir gewährten Berechtigungen beschränkt, wenn du die Anwendung zum Handeln auf deinen Namen autorisiert hast. Falls du eine Anwendung separat autorisiert hast, um auf unterschiedliche Schwesterprojekte zuzugreifen, dann wirst du unten separate Konfigurationen für jedes Projekt sehen.

Verbundene Anwendungen greifen auf dein Benutzerkonto durch Verwendung eines OAuth-Protokolls zu. <span class="plainlinks">([https://www.mediawiki.org/wiki/Special:MyLanguage/Help:OAuth Mehr über verbundene Anwendungen erfahren])</span>',
	'mwoauthmanagemygrants-notloggedin' => 'Du musst angemeldet sein, um auf diese Seite zugreifen zu können.',
	'mwoauthmanagemygrants-navigation' => 'Navigation:',
	'mwoauthmanagemygrants-showlist' => 'Liste verbundener Anwendungen',
	'mwoauthmanagemygrants-none' => 'Es sind keine Anwendungen mit deinem Benutzerkonto verbunden.',
	'mwoauthmanagemygrants-user' => 'Herausgeber:',
	'mwoauthmanagemygrants-description' => 'Beschreibung',
	'mwoauthmanagemygrants-wikiallowed' => 'Erlaubt auf Projekt:',
	'mwoauthmanagemygrants-grants' => 'Anwendbare Berechtigungen',
	'mwoauthmanagemygrants-grantsallowed' => 'Erlaubte Berechtigungen:',
	'mwoauthmanagemygrants-applicablegrantsallowed' => 'Erlaubte anwendbare Berechtigungen:',
	'mwoauthmanagemygrants-review' => 'Zugriff verwalten',
	'mwoauthmanagemygrants-revoke' => 'Zugriff entziehen',
	'mwoauthmanagemygrants-grantaccept' => 'Gewährt',
	'mwoauthmanagemygrants-update-text' => 'Benutze das unten stehende Formular, um die gewährten Berechtigungen für eine Anwendung zu ändern, die auf deinen Namen handelt.',
	'mwoauthmanagemygrants-revoke-text' => 'Benutze das unten stehende Formular, um den Zugriff für eine Anwendung zu entziehen, die auf deinen Namen handelt.',
	'mwoauthmanagemygrants-confirm-legend' => 'Verbundene Anwendung verwalten',
	'mwoauthmanagemygrants-update' => 'Berechtigungen aktualisieren',
	'mwoauthmanagemygrants-renounce' => 'Deautorisieren',
	'mwoauthmanagemygrants-action' => 'Status ändern:',
	'mwoauthmanagemygrants-confirm-submit' => 'Zugriffstokenstatus aktualisieren',
	'mwoauthmanagemygrants-success-update' => 'Der Zugriffstoken für diesen Verbraucher wurde aktualisiert.',
	'mwoauthmanagemygrants-success-renounce' => 'Der Zugriffstoken für diesen Verbraucher wurde gelöscht.',
	'mwoauthmanagemygrants-useoauth-tooltip' => 'Warum kann ich diese Berechtigung nicht aktualisieren? Diese Berechtigung gibt deiner verbundenen Anwendung Basisrechte, die erforderlich sind, um einwandfrei funktionieren zu können. Wenn diese verbundene Anwendung diese Rechte nicht haben soll, solltest du den Zugriff der Anwendung entziehen.',
	'logentry-mwoauthconsumer-propose' => '$1 {{GENDER:$2|plante}} einen OAuth-Verbraucher (Verbraucherschlüssel $4)',
	'logentry-mwoauthconsumer-update' => '$1 {{GENDER:$2|aktualisierte}} einen OAuth-Verbraucher (Verbraucherschlüssel $4)',
	'logentry-mwoauthconsumer-approve' => '$1 {{GENDER:$2|bestätigte}} einen OAuth-Verbraucher von $3 (Verbraucherschlüssel $4)',
	'logentry-mwoauthconsumer-reject' => '$1 {{GENDER:$2|lehnte}} einen OAuth-Verbraucher von $3 ab (Verbraucherschlüssel $4)',
	'logentry-mwoauthconsumer-disable' => '$1 deaktivierte einen OAuth-Verbraucher von $3 (Verbraucherschlüssel $4)',
	'logentry-mwoauthconsumer-reenable' => '$1 {{GENDER:$2|reaktivierte}} einen OAuth-Verbraucher von $3 (Verbraucherschlüssel $4)',
	'mwoauthconsumer-consumer-logpage' => 'OAuth-Anwendungs-Logbuch',
	'mwoauthconsumer-consumer-logpagetext' => 'Logbuch von Bestätigungen, Ablehnungen und Deaktivierungen registrierter OAuth-Verbraucher.',
	'mwoauth-bad-request-missing-params' => 'Leider ist etwas mit der Konfiguration dieser verbundenen Anwendung schief gelaufen. <span class="plainlinks">[https://www.mediawiki.org/wiki/Special:MyLanguage/Help:OAuth Kontaktiere den Support]</span>, um Hilfe bei der Behebung zu erhalten.

<span class="plainlinks mw-mwoautherror-details">Fehlende Parameter, [https://www.mediawiki.org/wiki/Help:OAuth/Errors#E001 E001]</span>',
	'mwoauth-bad-request-invalid-action' => 'Leider ist etwas schief gelaufen. Du wirst den Anwendungsautor kontaktieren müssen, um Hilfe für dieses Problem zu erhalten.

<span class="plainlinks mw-mwoautherror-details">Unbekannte URL, [https://www.mediawiki.org/wiki/Help:OAuth/Errors#E002 E002]</span>',
	'mwoauth-bad-request-invalid-action-contact' => 'Leider ist etwas schief gelaufen. Du wirst den Anwendungsautor [$1 kontaktieren] müssen, um Hilfe für dieses Problem zu erhalten.

<span class="plainlinks mw-mwoautherror-details">Unbekannte URL, [https://www.mediawiki.org/wiki/Help:OAuth/Errors#E003 E003]</span>',
	'mwoauthdatastore-access-token-not-found' => 'Für diesen Autorisierungstoken wurde keine bestätigte Berechtigung gefunden',
	'mwoauthdatastore-request-token-not-found' => 'Bei der Verbindung dieser Anwendung ist leider etwas schief gelaufen.
Gehe zurück und versuche, dein Benutzerkonto erneut zu verbinden oder kontaktiere den Anwendungsautor.

<span class="plainlinks mw-mwoautherror-details">OAuth-Token nicht gefunden, [https://www.mediawiki.org/wiki/Help:OAuth/Errors#E004 E004]</span>',
	'mwoauthdatastore-bad-token' => 'Es wurde kein Token gefunden, der deiner Anfrage entspricht.',
	'mwoauthdatastore-bad-verifier' => 'Der angegebene Verifikationscode war nicht gültig',
	'mwoauthdatastore-invalid-token-type' => 'Der angeforderte Tokentyp ist ungültig',
	'mwoauthgrants-general-error' => 'In deiner OAuth-Anfrage gab es einen Fehler',
	'mwoauthserver-bad-consumer' => '$1 ist nicht mehr als verbundene Anwendung bestätigt. Um Hilfe zu erhalten, [$2 kontaktiere] den Anwendungsautor.

<span class="plainlinks mw-mwoautherror-details">Verbundene OAuth-Anwendung nicht bestätigt, [https://www.mediawiki.org/wiki/Help:OAuth/Errors#E005 E005]</span>',
	'mwoauthserver-bad-consumer-key' => 'Bei der Verbindung dieser Anwendung ist leider etwas schief gelaufen.

<span class="plainlinks mw-mwoautherror-details">Unbekannter OAuth-Schlüssel, [https://www.mediawiki.org/wiki/Help:OAuth/Errors#E006 E006]</span>',
	'mwoauthserver-insufficient-rights' => 'Dein Benutzerkonto ist nicht berechtigt, verbundene Anwendungen zu verwenden. Kontaktiere deinen Websiteadministrator, um den Grund herauszufinden.

<span class="plainlinks mw-mwoautherror-details">Nicht ausreichende OAuth-Benutzerrechte, [https://www.mediawiki.org/wiki/Help:OAuth/Errors#E007 E007]</span>',
	'mwoauthserver-invalid-request-token' => 'Deine Anfrage enthält einen ungültigen Token',
	'mwoauthserver-invalid-user' => 'Um verbundene Anwendungen auf dieser Website zu verwenden, musst du ein Benutzerkonto für alle Projekte besitzen. Falls du ein Benutzerkonto auf allen Projekten besitzt, kannst du versuchen, $1 erneut zu verbinden.

<span class="plainlinks mw-mwoautherror-details">Einheitliche Anmeldung erforderlich, [https://www.mediawiki.org/wiki/Help:OAuth/Errors#E008 E008]</span>',
	'mwoauth-invalid-authorization-title' => 'OAuth-Autorisierungsfehler',
	'mwoauth-invalid-authorization' => 'Die Autorisierungsheader in deiner Anfrage sind nicht gültig: $1',
	'mwoauth-invalid-authorization-wrong-wiki' => 'Die Autorisierungsheader in deiner Anfrage sind nicht gültig für $1',
	'mwoauth-invalid-authorization-invalid-user' => 'Die Autorisierungsheader in deiner Anfrage sind für einen Benutzer, der hier nicht vorhanden ist.',
	'mwoauth-invalid-authorization-wrong-user' => 'Die Autorisierungsheader in deiner Anfrage sind für einen anderen Benutzer',
	'mwoauth-invalid-authorization-not-approved' => 'Die Anwendung, die du verbinden willst, scheint nicht korrekt konfiguriert zu sein. Kontaktiere für Hilfe den Autor von $1.',
	'mwoauth-invalid-authorization-blocked-user' => 'Die Autorisierungsheader in deiner Anfrage sind für einen Benutzer, der gesperrt ist.',
	'mwoauth-form-description-allwikis' => "Hallo $1,

'''$2''' will die folgenden Aktionen auf allen Projekten dieser Website in deinem Namen ausführen:

$4.",
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
	'mwoauth-error' => 'Anwendungsverbindungsfehler',
	'mwoauth-grants-heading' => 'Angeforderte Berechtigungen:',
	'mwoauth-grants-nogrants' => 'Die Anwendung hat keine Berechtigungen beantragt.',
	'mwoauth-acceptance-cancelled' => 'Du hast dich entschieden, dass $1 nicht auf dein Benutzerkonto zugreifen darf. $1 wird nicht laufen, bis du den Zugriff erlaubt hast. Du kannst zu $1 zurückgehen oder deine verbundenen Anwendungen [[Special:OAuthManageMyGrants|verwalten]].',
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
	'mwoauth-grant-editmyoptions' => 'Eigene Benutzereinstellungen bearbeiten',
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
	'right-mwoauthproposeconsumer' => 'Neue OAuth-Anwendungen vorschlagen',
	'right-mwoauthupdateownconsumer' => 'Kontrollierte OAuth-Anwendungen aktualisieren',
	'right-mwoauthmanageconsumer' => 'OAuth-Anwendungen verwalten',
	'right-mwoauthsuppress' => 'OAuth-Anwendungen unterdrücken',
	'right-mwoauthviewsuppressed' => 'Unterdrückte OAuth-Anwendungen ansehen',
	'right-mwoauthviewprivate' => 'Private OAuth-Daten ansehen',
	'right-mwoauthmanagemygrants' => 'OAuth-Berechtigungen verwalten',
	'action-mwoauthmanageconsumer' => 'OAuth-Anwendungen zu verwalten',
	'action-mwoauthmanagemygrants' => 'deine OAuth-Berechtigungen zu verwalten',
	'action-mwoauthproposeconsumer' => 'neue OAuth-Anwendungen vorzuschlagen',
	'action-mwoauthupdateownconsumer' => 'kontrollierte OAuth-Anwendungen zu aktualisieren',
	'action-mwoauthviewsuppressed' => 'unterdrückte OAuth-Anwendungen anzusehen',
	'mwoauth-listgrantrights-summary' => 'Es folgt eine Liste mit OAuth-Berechtigungen mit ihrem verknüpften Zugriff auf Benutzerrechte. Benutzer können Anwendungen autorisieren, um ihr Benutzerkonto zu verwenden, aber mit beschränkten Berechtigungen basierend auf den Rechten, die der Benutzer der Anwendung gegeben hat. Eine Anwendung agiert im Namen eines Benutzers, die keine Rechte verwenden kann, die der Benutzer nicht hat.
Es gibt [[{{MediaWiki:Listgrouprights-helppage}}|zusätzliche Informationen]] über einzelne Rechte.',
	'mwoauth-listgrants-grant' => 'Berechtigung',
	'mwoauth-listgrants-rights' => 'Berechtigungen',
);

/** Zazaki (Zazaki)
 * @author Marmase
 */
$messages['diq'] = array(
	'mwoauth-prefs-managegrantslink' => '$1 ya greyın {{PLURAL:$1|dezgi|dezgan}} idare ke',
);

/** British English (British English)
 * @author Shirayuki
 */
$messages['en-gb'] = array(
	'mwoauth-desc' => 'Allows usage of OAuth 1.0a for API authorisation',
	'mwoauthconsumerregistration-propose-text' => 'Developers should use the form below to propose a new OAuth consumer (see the [//www.mediawiki.org/wiki/Extension:OAuth extension documentation] for more details). After submitting this form, you will receive a token that your application will use to identify itself to MediaWiki. An OAuth administrator will need to approve your application before it can be authorised by other users.

A few recommendations and remarks:
* Try to use as few grants as possible. Avoid grants that are not actually needed now.
* Versions are of the form "major.minor.release" (the last two being optional) and increase as grant changes are needed.
* Please provide a public RSA key (in PEM format) if possible; otherwise a (less secure) secret token will have to be used.
* Use the JSON restrictions field to limit access of this consumer to IP addresses in those CIDR ranges.
* You can use a project ID to restrict the consumer to a single project on this site (use "*" for all projects).
* The email address provided must match that of your account (which must have been confirmed).',
	'mwoauthmanagemygrants-text' => 'This page lists any applications that can use your account. For any such application, the scope of its access is limited by the permissions that you granted to the application when you authorised it to act on your behalf. If you separately authorised an application to access different "sister" projects on your behalf, then you will see separate configuration for each such project below.

Connected applications access your account by using the OAuth protocol. <span class="plainlinks">([https://www.mediawiki.org/wiki/Special:MyLanguage/Help:OAuth Learn more about connected applications])</span>',
	'mwoauthmanagemygrants-renounce' => 'Deauthorise',
	'mwoauthdatastore-access-token-not-found' => 'No approved grant was found for that authorisation token',
	'mwoauth-invalid-authorization-title' => 'OAuth authorisation error',
	'mwoauth-invalid-authorization' => 'The authorisation headers in your request are not valid: $1',
	'mwoauth-invalid-authorization-wrong-wiki' => 'The authorisation headers in your request are not valid for $1',
	'mwoauth-invalid-authorization-invalid-user' => 'The authorisation headers in your request are for a user that does not exist here',
	'mwoauth-invalid-authorization-wrong-user' => 'The authorisation headers in your request are for a different user',
	'mwoauth-invalid-authorization-not-approved' => 'The app that you are trying to connect seems to be set up incorrectly. Contact the author of "$1" for help.',
	'mwoauth-invalid-authorization-blocked-user' => 'The authorisation headers in your request are for a user who is blocked',
	'mwoauth-acceptance-cancelled' => 'You have chosen not to allow "$1" to access your account. "$1" will not work unless you allow it access. You can go back to "$1" or [[Special:OAuthManageMyGrants|manage]] your connected apps.',
	'mwoauth-grant-group-customization' => 'Customisation and preferences',
	'mwoauth-listgrantrights-summary' => 'The following is a list of OAuth grants, with their associated access to user rights. Users can authorise applications to use their account, but with limited permissions based on the grants the user gave to the application. An application acting on behalf of a user cannot actually use rights that the user does not have however.
There may be [[{{MediaWiki:Listgrouprights-helppage}}|additional information]] about individual rights.',
);

/** Spanish (español)
 * @author Fitoschido
 * @author Ovruni
 */
$messages['es'] = array(
	'mwoauth-verified' => "La aplicación ahora puede acceder a MediaWiki en tu nombre.

Para completar el proceso, proporciona este valor de comprobación a la aplcación: '''$1'''",
	'mwoauth-invalid-field-generic' => 'Se ha proporcionado un valor no válido',
	'mwoauth-prefs-managegrants' => 'Aplicaciones conectadas:',
	'mwoauth-prefs-managegrantslink' => 'Gestionar $1 {{PLURAL:$1|aplicación conectada|aplicaciones conectadas}}',
	'mwoauth-consumer-allwikis' => 'Todos los proyectos en este sitio',
	'mwoauthmanagemygrants-text' => 'Esta página muestra las aplicaciones que pueden utilizar tu cuenta. Para cualquier aplicación, el alcance de su acceso está limitado por los permisos que se le otorgaron al momento de autorizarla. Si has autorizado una aplicación para que acceda a varios proyectos en tu nombre, verás ajustes separados a continuación por cada uno de los proyectos.

Las aplicaciones conectadas acceden a tu cuenta mediante el protocolo OAuth. <span class="plainlinks">([https://www.mediawiki.org/wiki/Special:MyLanguage/Help:OAuth Más información sobre las aplicaciones conectadas])</span>',
	'mwoauthmanagemygrants-showlist' => 'Lista de aplicaciones conectadas',
	'mwoauthmanagemygrants-review' => 'administrar el acceso',
	'mwoauthmanagemygrants-grantaccept' => 'Concedido',
	'mwoauthmanagemygrants-confirm-legend' => 'Gestionar aplicación conectada',
	'mwoauthmanagemygrants-update' => 'Actualizar permisos',
	'mwoauthmanagemygrants-renounce' => 'No autorizado',
	'mwoauth-bad-request-missing-params' => 'Ha ocurrido un error al configurar esta aplicación conectada. <span class="plainlinks">[https://www.mediawiki.org/wiki/Special:MyLanguage/Help:OAuth Ponte en contacto con el equipo de asistencia]</span> para corregir el problema.

<span class="plainlinks mw-mwoautherror-details">Faltan parámetros de OAuth, [https://www.mediawiki.org/wiki/Help:OAuth/Errors#E001 E001]</span>',
	'mwoauth-form-button-approve' => 'Permitir',
	'mwoauth-form-button-cancel' => 'Cancelar',
	'mwoauth-grant-group-page-interaction' => 'Interactuar con páginas',
	'mwoauth-grant-group-email' => 'Enviar correo electrónico',
	'mwoauth-grant-createaccount' => 'Crear cuentas',
	'mwoauth-grant-sendemail' => 'Enviar un correo electrónico a otros usuarios',
	'mwoauth-oauth-exception' => 'Ha ocurrido un error en el protocolo OAuth: $1',
);

/** Estonian (eesti)
 * @author Pikne
 */
$messages['et'] = array(
	'mwoauth-desc' => 'Võimaldab kasutada OAuth 1.0a-d API-volitamiseks.',
	'mwoauth-prefs-managegrants' => 'Ühendatud rakendused:',
	'mwoauth-prefs-managegrantslink' => '{{PLURAL:$1|Halda üht ühendatud rakendust|Halda $1 ühendatud rakendust|0=Puuduvad}}',
	'mwoauth-consumer-allwikis' => 'Kõigis selle võrgukoha projektides',
	'mwoauth-consumer-name' => 'Rakenduse nimi:',
	'mwoauth-consumer-version' => 'Tarvitusversioon:',
	'mwoauth-consumer-user' => 'Väljaandja:',
	'mwoauth-consumer-stage' => 'Praegune olek:',
	'mwoauth-consumer-description' => 'Rakenduse kirjeldus:',
	'mwoauth-consumer-callbackurl' => 'OAuthi tagasisuunamis-URL:',
	'mwoauth-consumer-grantsneeded' => 'Rakenduse volitused:',
	'mwoauth-invalid-access-token' => 'Antud võtmega juurdepääsuluba puudub.',
	'mwoauth-consumer-stage-proposed' => 'ettepanek',
	'mwoauth-consumer-stage-rejected' => 'tagasilükatud',
	'mwoauth-consumer-stage-expired' => 'iganenud',
	'mwoauth-consumer-stage-approved' => 'kinnitatud',
	'mwoauth-consumer-stage-disabled' => 'keelatud',
	'oauthlistconsumers' => 'OAuthi-rakenduste loend',
	'mwoauthlistconsumers-legend' => 'OAuthi-rakenduste sirvimine',
	'mwoauthlistconsumers-view' => 'üksikasjad',
	'mwoauthlistconsumers-name' => 'Rakenduse nimi',
	'mwoauthlistconsumers-version' => 'Tarvitusversioon',
	'mwoauthlistconsumers-user' => 'Väljaandja',
	'mwoauthlistconsumers-description' => 'Kirjeldus',
	'mwoauthlistconsumers-wiki' => 'Rakendamise projekt',
	'mwoauthlistconsumers-callbackurl' => 'Rakenduse internetiaadress',
	'mwoauthlistconsumers-grants' => 'Rakenduse load',
	'mwoauthlistconsumers-basicgrantsonly' => '(ainult põhijuurdepääs)',
	'mwoauthlistconsumers-status' => 'Olek',
	'mwoauth-consumer-stage-any' => 'ükskõik',
	'mwoauthlistconsumers-status-proposed' => 'ettepanek',
	'mwoauthlistconsumers-status-approved' => 'kinnitatud',
	'mwoauthlistconsumers-status-disabled' => 'keelatud',
	'mwoauthlistconsumers-status-rejected' => 'tagasilükatud',
	'mwoauthlistconsumers-status-expired' => 'iganenud',
	'oauthmanagemygrants' => 'Ühendatud rakenduste haldamine',
	'mwoauthmanagemygrants-text' => 'Siin leheküljel on loetletud kõik rakendused, mida ühenduses sinu kontoga saab kasutada. Kõigi nende rakendamise ulatus on piiratud volitustega, mille rakendusega sidusid, kui nõustusid seda enda nimel kasutama. Kui volitasid rakenduse enda nimel kasutuse eri sõsarprojektides eraldi, näed allpool iga projekti jaoks eraldi häälestust.

Rakendused kasutavad ühenduseks sinu kontoga OAuthi protokolli. <span class="plainlinks">([https://www.mediawiki.org/wiki/Special:MyLanguage/Help:OAuth Lisateave ühendatud rakenduste kohta])</span>',
	'mwoauthmanagemygrants-navigation' => 'Navigeerimine:',
	'mwoauthmanagemygrants-showlist' => 'Ühendatud rakenduste loend',
	'mwoauthmanagemygrants-none' => 'Puuduvad sinu kontoga ühendatud rakendused.',
	'mwoauthmanagemygrants-user' => 'Väljaandja:',
	'mwoauthmanagemygrants-description' => 'Kirjeldus',
	'mwoauthmanagemygrants-wikiallowed' => 'Lubatud järgmises projektis:',
	'mwoauthmanagemygrants-applicablegrantsallowed' => 'Rakenduse lubatud volitused:',
	'mwoauthmanagemygrants-review' => 'halda juurdepääsu',
	'mwoauthmanagemygrants-revoke' => 'tühista juurdepääs',
	'mwoauthmanagemygrants-grantaccept' => 'Volitatud',
	'mwoauthmanagemygrants-update-text' => 'Kasuta seda vormi, et muuta sinu nimel toimivate rakenduste volitusi.',
	'mwoauthmanagemygrants-revoke-text' => 'Kasuta seda vormi, et tühistada sinu nimel toimivate rakenduste volitused.',
	'mwoauthmanagemygrants-confirm-legend' => 'Ühendatud rakenduse haldamine',
	'mwoauthmanagemygrants-update' => 'Uuenda volitusi',
	'mwoauthmanagemygrants-renounce' => 'Tühista volitused',
	'mwoauthmanagemygrants-success-update' => 'Selle tarvituse juurdepääsuluba on uuendatud.',
	'mwoauthmanagemygrants-success-renounce' => 'Selle tarvituse juurdepääsuluba on kustutatud.',
	'mwoauthdatastore-request-token-not-found' => 'Selle rakendusega läks kahjuks midagi valesti.
Mine tagasi ja ürita uuesti oma kontot ühendada või võta ühendust rakenduse autoriga.

<span class="plainlinks mw-mwoautherror-details">OAuthi-luba ei leitud, [https://www.mediawiki.org/wiki/Help:OAuth/Errors#E004 E004]</span>',
	'mwoauthserver-bad-consumer-key' => 'Selle rakendusega läks kahjuks midagi valesti.

<span class="plainlinks mw-mwoautherror-details">Tundmatu OAuthi-võti, [https://www.mediawiki.org/wiki/Help:OAuth/Errors#E006 E006]</span>',
	'mwoauth-form-description-allwikis' => "Tere, $1.

'''$2''' vajab luba, et teha sinu nimel kõigis selle võrgukoha projektides järgmisi toiminguid:

$4",
	'mwoauth-form-description-onewiki' => "Tere, $1.

'''$2''' vajab luba, et teha sinu nimel võrgukohas ''$4'' järgmisi toiminguid:

$5",
	'mwoauth-form-description-allwikis-nogrants' => "Tere, $1.

'''$2''' vajab luba põhijuurdepääsuks sinu nimel kõigis selle võrgukoha projektides.",
	'mwoauth-form-description-onewiki-nogrants' => "Tere, $1.

'''$2''' vajab luba põhijuurdepääsuks sinu nimel võrgukohas $4.",
	'mwoauth-form-privacypolicy-link' => '[[{{ns:Project}}:Privacy policy|Privaatsus]]',
	'mwoauth-form-button-approve' => 'Luba',
	'mwoauth-form-button-cancel' => 'Loobu',
	'mwoauth-grant-group-page-interaction' => 'Interaktsioon lehekülgedega',
	'mwoauth-grant-group-file-interaction' => 'Interaktsioon meediafailidega',
	'mwoauth-grant-group-watchlist-interaction' => 'Interaktsioon sinu jälgimisloendiga',
	'mwoauth-grant-group-email' => 'E-kirja saatmine',
	'mwoauth-grant-group-high-volume' => 'Suuremahuline tegevus',
	'mwoauth-grant-group-customization' => 'Kohandamine ja eelistused',
	'mwoauth-grant-group-administration' => 'Administraatori toimingud',
	'mwoauth-grant-group-other' => 'Mitmesugused toimingud',
	'mwoauth-grant-blockusers' => 'Kasutajate blokeerimine ja blokeeringute eemaldamine',
	'mwoauth-grant-createaccount' => 'Kontode loomine',
	'mwoauth-grant-createeditmovepage' => 'Lehekülgede alustamine, redigeerimine ja teisaldamine',
	'mwoauth-grant-delete' => 'Lehekülgede, redaktsioonide ja logisissekannete kustutamine',
	'mwoauth-grant-editinterface' => 'MediaWiki nimeruumi ning kasutaja CSSi ja JavaScripti redigeerimine',
	'mwoauth-grant-editmycssjs' => 'Oma CSSi või JavaScripti muutmine',
	'mwoauth-grant-editmywatchlist' => 'Oma jälgimisloendi muutmine',
	'mwoauth-grant-editpage' => 'Olemasolevate lehekülgede redigeerimine',
	'mwoauth-grant-editprotected' => 'Kaitstud lehekülgede redigeerimine',
	'mwoauth-grant-highvolume' => 'Suuremahuline redigeerimine',
	'mwoauth-grant-patrol' => 'Lehekülgede muudatuste kontroll',
	'mwoauth-grant-protect' => 'Lehekülgede kaitsmine ja kaitse eemaldamine',
	'mwoauth-grant-rollback' => 'Lehekülgede muudatuste tühistamine',
	'mwoauth-grant-sendemail' => 'Kasutajatele e-kirjade saatmine',
	'mwoauth-grant-uploadeditmovefile' => 'Failide üleslaadimine, asendamine ja teisaldamine',
	'mwoauth-grant-uploadfile' => 'Uute failide üleslaadimine',
	'mwoauth-grant-useoauth' => 'Põhiõigused',
	'mwoauth-grant-viewdeleted' => 'Kustutatud teabe vaatamine',
	'mwoauth-grant-viewmywatchlist' => 'Oma jälgimisloendi vaatamine',
	'right-mwoauthproposeconsumer' => 'Esitada uusi OAuthi-rakendusi',
	'right-mwoauthupdateownconsumer' => 'Uuendada OAuthi-rakendusi, mida valdad',
	'right-mwoauthmanageconsumer' => 'Hallata OAuthi-rakendusi',
	'right-mwoauthmanagemygrants' => 'Hallata OAuthi-volitusi',
	'action-mwoauthmanageconsumer' => 'OAuthi-rakendusi hallata',
	'action-mwoauthmanagemygrants' => 'oma OAuthi-volitusi hallata',
	'action-mwoauthproposeconsumer' => 'esitada uusi OAuthi-rakendusi',
	'action-mwoauthupdateownconsumer' => 'uuendada OAuthi-rakendusi, mida valdad',
	'mwoauth-listgrantrights-summary' => 'See on OAuthi-volituste ja neile vastavate kasutajaõiguste loend. Kasutaja saab volitada rakenduse tarvituse enda nimel, aga vaid kasutaja valitud volituste piires. Rakenduse abil ei saa kasutaja nimel siiski kasutada õigusi, mida kasutajal pole.
Üksikute õiguste kohta võib leiduda [[{{MediaWiki:Listgrouprights-helppage}}|lisateavet]].',
	'mwoauth-listgrants-grant' => 'Volitus',
	'mwoauth-listgrants-rights' => 'Õigused',
);

/** Persian (فارسی)
 * @author Armin1392
 * @author Ebraminio
 * @author Reza1615
 * @author Taha
 */
$messages['fa'] = array(
	'mwoauth-desc' => 'استفاده از رابط برنامه‌نویسی هویت‌سنجی OAuth ۱.۰ اجازه می‌دهد',
	'mwoauth-verified' => "این برنامه اجازهٔ دسترسی به مدیاویکی از طرف شما را دارد.

برای تکمیل این فرایند، مقدار تأیید را برای برنامه فراهم کنید: '''$1'''",
	'mwoauth-missing-field' => 'مقدار ناموجود برای مورد "$1"',
	'mwoauth-invalid-field' => 'مقدار نامعتبر برای مورد "$1"',
	'mwoauth-invalid-field-generic' => 'مقدار نامعتبر ارائه شده',
	'mwoauth-field-hidden' => '(این اطلاعات پنهان است)',
	'mwoauth-field-private' => '(این اطلاعات خصوصی است)',
	'mwoauth-grant-generic' => '" $1 " حقوق بسته نرم‌افزاری',
	'mwoauth-prefs-managegrants' => 'برنامه‌های متصل:',
	'mwoauth-prefs-managegrantslink' => 'مدیریت $1 {{PLURAL:$1|برنامهٔ}} متصل',
	'mwoauth-consumer-allwikis' => 'همه پروژه‌ها در این وب‌گاه',
	'mwoauth-consumer-key' => 'کلید مصرف‌کننده:',
	'mwoauth-consumer-name' => 'نام برنامه:',
	'mwoauth-consumer-version' => 'نسخهٔ مصرف‌کنندگان:',
	'mwoauth-consumer-user' => 'ناشر:',
	'mwoauth-consumer-stage' => 'وضعیت فعلی:',
	'mwoauth-consumer-email' => 'نشانی رایانامه:',
	'mwoauth-consumer-description' => 'توضیحات نرم‌افزار:',
	'mwoauth-consumer-callbackurl' => 'نشانی پاسخ OAuth:',
	'mwoauth-consumer-grantsneeded' => 'اعطاهای اجرا شدنی:',
	'mwoauth-consumer-required-grant' => 'قابل استفاده برای مصرف‌کننده',
	'mwoauth-consumer-wiki' => 'پروژه‌های قابل اجرا:',
	'mwoauth-consumer-wiki-thiswiki' => 'پروژه فعلی ( $1 )',
	'mwoauth-consumer-wiki-other' => 'پروژه‌های خاص',
	'mwoauth-consumer-restrictions' => 'محدودیت استفاده از:',
	'mwoauth-consumer-restrictions-json' => 'محدودیت استفاده از  (JSON):',
	'mwoauth-consumer-rsakey' => 'کلید عمومی RSA:',
	'mwoauth-consumer-secretkey' => ' نشانه‌های مخفی مصرف‌کننده:',
	'mwoauth-consumer-accesstoken' => 'رمز دسترسی:',
	'mwoauth-consumer-reason' => 'دلیل:',
	'mwoauth-consumer-email-unconfirmed' => 'نشانی رایانامهٔ شما هنوز تأیید  نشده‌است.',
	'mwoauth-consumer-email-mismatched' => 'آدرس ایمیل ارائه شده باید با حساب کاربریتان مطابقت داشته باشد.',
	'mwoauth-consumer-alreadyexists' => 'مصرف‌کنندگان با این ترکیب ناشر/نام/نسخه در حال حاضر وجود دارد',
	'mwoauth-consumer-alreadyexistsversion' => 'مصرف‌کننده‌ای با این ترکیب نام/منتشرکننده با نسخه‌ای برابر یا بالاتر در حال حاضر موجود است («$1»)',
	'mwoauth-consumer-not-accepted' => 'نمی‌توان اطلاعات یک درخواست معلف مصرف‌کننده به‌روز کرد',
	'mwoauth-consumer-not-proposed' => 'مصرف‌کننده در حال حاضر پیشنهاد نشده‌است',
	'mwoauth-consumer-not-disabled' => 'مصرف‌کننده در حال حاضر غیرفعال شده‌است',
	'mwoauth-consumer-not-approved' => 'مصرف‌کننده تأییدنشده (ممکن است غیرفعال شده باشد)',
	'mwoauth-missing-consumer-key' => 'کلید مصرف‌کننده‌ای فراهم نشده‌است.',
	'mwoauth-invalid-consumer-key' => 'مصرف‌کننده‌ای با کلید داده‌شده موجود نیست.',
	'mwoauth-invalid-access-token' => 'نشان عدم دسترسی با کلید داده موجود است.',
	'mwoauth-invalid-access-wrongwiki' => 'مصرف‌کننده فقط می‌تواند در پروژه «$1» استفاده شود.',
	'mwoauth-consumer-conflict' => 'یک نفر خصوصیات این مصرف‌کننده را از زمانی که شما دیدید، تغییر داده است. لطفاً دوباره تلاش کنید. شاید لازم باشد سیاهه تغییرات را مشاهده کنید.',
	'mwoauth-consumer-grantshelp' => 'هر اعطا امکان دسترسی به فهرست دسترسی‌های کاربران را می دهد. برای اطلاعات بیشتر [[Special:OAuth/grants|جدول اعطاها]] را مشاهده کنید.',
	'mwoauth-consumer-stage-proposed' => 'پیشنهاد شده',
	'mwoauth-consumer-stage-rejected' => 'رد شده',
	'mwoauth-consumer-stage-expired' => 'منقضی شده',
	'mwoauth-consumer-stage-approved' => 'تأیید شده',
	'mwoauth-consumer-stage-disabled' => 'غیرفعال شد',
	'mwoauth-consumer-stage-suppressed' => 'توقیف شد',
	'oauthconsumerregistration' => 'ثبت نام OAuth مصرف‌کننده',
	'mwoauthconsumerregistration-notloggedin' => 'برای دسترسی به این صفحه باید وارد سامانه شوید.',
	'mwoauthconsumerregistration-navigation' => 'ناوبری:',
	'mwoauthconsumerregistration-propose' => 'پیشنهاد مصرف کننده جدید',
	'mwoauthconsumerregistration-list' => 'فهرست مصرف‌کننده من',
	'mwoauthconsumerregistration-main' => 'اصلی',
	'mwoauthconsumerregistration-update-text' => 'فرم زیر را برای بروزرسانی جوانب مصرف‌کنندگان OAuth که شما کنترل می‌کنید، استفاده کنید.

همه مقادیر برروی مقادیر گذشته ذخیره می‌شوند. هیچ مقداری را خالی نگذارید مگر اینکه قصد پاک کردن آن مقادیر را داشته باشید.',
	'mwoauthconsumerregistration-propose-legend' => 'برنامهٔ مصرف‌کننده جدید OAuth',
	'mwoauthconsumerregistration-update-legend' => 'به‌روزرسانی برنامهٔ مصرف‌کننده OAuth',
	'mwoauthconsumerregistration-propose-submit' => 'پشیناهد مصرف‌کننده',
	'mwoauthconsumerregistration-update-submit' => 'به‌روزرسانی مصرف‌کننده',
	'mwoauthconsumerregistration-none' => 'شما هیچ مصرف‌کنندهٔ OAuth را کنترل نمی‌کنید.',
	'mwoauthconsumerregistration-name' => 'مصرف‌کننده',
	'mwoauthconsumerregistration-user' => 'ناشر',
	'mwoauthconsumerregistration-description' => 'توضیحات',
	'mwoauthconsumerregistration-email' => 'رایانامه تماس',
	'mwoauthconsumerregistration-consumerkey' => 'کلید مصرف‌کننده',
	'mwoauthconsumerregistration-stage' => 'وضعیت',
	'mwoauthconsumerregistration-lastchange' => 'آخرین تغییر',
	'mwoauthconsumerregistration-manage' => 'مدیریت',
	'mwoauthconsumerregistration-resetsecretkey' => 'بازنشانی کلید رمز به مقداری جدید',
	'mwoauthconsumerregistration-updated' => 'ثبت OAuth شما با موفقیت به‌روزرسانی شد.',
	'oauthmanageconsumers' => 'مدیریت مصرف‌کننده‌های OAuth',
	'mwoauthmanageconsumers-notloggedin' => 'شما برای دسترسی به این صفحه باید وارد شده باشید.',
	'mwoauthmanageconsumers-type' => 'صف:',
	'mwoauthmanageconsumers-showproposed' => 'درخواست‌های پیشنهادی',
	'mwoauthmanageconsumers-showrejected' => 'درخواست‌های رد شده',
	'mwoauthmanageconsumers-showexpired' => 'درخواست‌های منقضی‌شده',
	'mwoauthmanageconsumers-main' => 'اصلی',
	'mwoauthmanageconsumers-queues' => 'یک صف تأیید مصرف‌کننده از زیر انتخاب کنید:',
	'mwoauthmanageconsumers-q-proposed' => 'به صف‌کردن درخواست پینشهاد مصرف‌کننده',
	'mwoauthmanageconsumers-q-rejected' => 'صف درخواست‌های رده شده مصرف‌کننده',
	'mwoauthmanageconsumers-q-expired' => 'صف درخواست‌های منقضی‌شده مصرف‌کننده',
	'mwoauthmanageconsumers-lists' => 'انتخاب فهرست وضعیت مصرف‌کننده از پائین:',
	'mwoauthmanageconsumers-l-approved' => 'فهرست مصرف‌کنندگان در حال حاضر تائید شده',
	'mwoauthmanageconsumers-l-disabled' => 'فهرست مصرف‌کنندگان در حال حاضر غیر فعال',
	'mwoauthmanageconsumers-none-proposed' => 'در این فهرست مصرف‌کنندگان وجود ندارد.',
	'mwoauthmanageconsumers-none-rejected' => 'در این فهرست مصرف‌کنندگان وجود ندارد.',
	'mwoauthmanageconsumers-none-expired' => 'در این فهرست مصرف‌کنندگان وجود ندارد.',
	'mwoauthmanageconsumers-none-approved' => 'هیچ مصرف‌کننده‌ای شامل این محدوده نمی‌شود.',
	'mwoauthmanageconsumers-none-disabled' => 'هیچ مصرف‌کننده‌ای این بخش را ندیده‌است.',
	'mwoauthmanageconsumers-name' => 'مصرف‌کننده',
	'mwoauthmanageconsumers-user' => 'ناشر',
	'mwoauthmanageconsumers-description' => 'توضیحات',
	'mwoauthmanageconsumers-email' => 'رایانامه تماس',
	'mwoauthmanageconsumers-consumerkey' => 'کلید مصرف‌کننده',
	'mwoauthmanageconsumers-lastchange' => 'آخرین تغییر',
	'mwoauthmanageconsumers-review' => 'بررسی/مدیریت',
	'mwoauthmanageconsumers-confirm-text' => 'از این فرم برای تائید، رد، غیرفعال یا دوباره فعال کردن این مصرف‌کننده استفاده کنید.',
	'mwoauthmanageconsumers-confirm-legend' => 'مدیریت مصرف OAuth',
	'mwoauthmanageconsumers-action' => 'تغییر وضعیت:',
	'mwoauthmanageconsumers-approve' => 'تأیید شده',
	'mwoauthmanageconsumers-reject' => 'رد شده',
	'mwoauthmanageconsumers-rsuppress' => 'رد و توقیف شد',
	'mwoauthmanageconsumers-disable' => 'غیرفعال شد',
	'mwoauthmanageconsumers-dsuppress' => 'غیر فعال و توقیف شد',
	'mwoauthmanageconsumers-reenable' => 'تأیید شده',
	'mwoauthmanageconsumers-reason' => 'دلیل:',
	'mwoauthmanageconsumers-confirm-submit' => 'به‌روز رسانی وضعیت مصرف‌کننده',
	'mwoauthmanageconsumers-viewing' => '{{GENDER:$1|کاربر}} " $1 " در حال مشاهدهٔ این مصرف‌کننده است',
	'mwoauthmanageconsumers-success-approved' => 'درخواست تایید شده است.',
	'mwoauthmanageconsumers-success-rejected' => 'درخواست رد شده است.',
	'mwoauthmanageconsumers-success-disabled' => 'مصرف‌کننده غیر فعال شده است.',
	'mwoauthmanageconsumers-success-reanable' => 'مصرف‌کننده دوباره فعال شده است.',
	'mwoauthmanageconsumers-search-name' => 'مصرف‌کنندگان با این نام',
	'mwoauthmanageconsumers-search-publisher' => 'مصرف‌کنندگان توسط این کاربر',
	'oauthlistconsumers' => 'فهرست برنامه‌های کاربردی OAuth',
	'mwoauthlistconsumers-legend' => 'مرور برنامه‌های کاربردی OAuth',
	'mwoauthlistconsumers-view' => 'جزئیات',
	'mwoauthlistconsumers-none' => 'هیچ برنامه‌ای از این محدوده استفاده نمی‌کند.',
	'mwoauthlistconsumers-name' => 'نام برنامه کاربردی',
	'mwoauthlistconsumers-version' => 'نسخهٔ مصرف‌کننده',
	'mwoauthlistconsumers-user' => 'ناشر',
	'mwoauthlistconsumers-description' => 'توضیحات',
	'mwoauthlistconsumers-wiki' => 'پروژه‌های قابل اجرا',
	'mwoauthlistconsumers-callbackurl' => 'نشانی پاسخ OAuth',
	'mwoauthlistconsumers-grants' => 'اعطاهای اجرا شدنی',
	'mwoauthlistconsumers-basicgrantsonly' => '(فقط دسترسی پایه)',
	'mwoauthlistconsumers-status' => 'وضعیت',
	'mwoauth-consumer-stage-any' => 'همه',
	'mwoauthlistconsumers-status-proposed' => 'پیشنهاد شده',
	'mwoauthlistconsumers-status-approved' => 'تأیید شده',
	'mwoauthlistconsumers-status-disabled' => 'غیرفعال شد',
	'mwoauthlistconsumers-status-rejected' => 'رد شده',
	'mwoauthlistconsumers-status-expired' => 'منقضی شده',
	'oauthmanagemygrants' => 'مدیریت برنامه‌های کاربردی متصل',
	'mwoauthmanagemygrants-text' => 'در این صفحه فهرست همه برنامه‌های کاربردیی که از کاربری شما می‌تواند برای ویرایش استفاده کند فهرست شده‌است. هدف از اجازه‌ای که به برنامه داده‌اید که به جای شما ویرایش کند، محدودسازی دامنه عملکرد آن برنامه به جای شما است. اگر به صورت جداگانه برای پروژه‌های خواهر دسترسی به برنامه‌های کاربردی داده‌اید، تنظیمات دسترسی‌ها را به صورت مجزا در پائین مشاهده خواهید کرد.


ارتباط با کاربری شما بر پایه پروتکل OAuth است.<span class="plainlinks">([https://www.mediawiki.org/wiki/Special:MyLanguage/Help:OAuth یادگیری بیشتر در مورد برنامه های کاربردی متصل])</span>',
	'mwoauthmanagemygrants-notloggedin' => 'برای دسترسی به این صفحه باید وارد سامانه شوید.',
	'mwoauthmanagemygrants-navigation' => 'ناوبری:',
	'mwoauthmanagemygrants-showlist' => 'فهرست برنامه‌های متصل',
	'mwoauthmanagemygrants-none' => 'هیچ برنامه‌ای به حسابتان متصل نیست.',
	'mwoauthmanagemygrants-user' => 'ناشر:',
	'mwoauthmanagemygrants-description' => 'توضیحات',
	'mwoauthmanagemygrants-wikiallowed' => 'مجاز در پروژه:',
	'mwoauthmanagemygrants-grants' => 'اعطاهای اجرا شدنی',
	'mwoauthmanagemygrants-grantsallowed' => 'اعطاهای مجاز',
	'mwoauthmanagemygrants-applicablegrantsallowed' => 'کمک‌های مالی مجاز:',
	'mwoauthmanagemygrants-review' => 'مدیریت دسترسی',
	'mwoauthmanagemygrants-revoke' => 'لغو دسترسی',
	'mwoauthmanagemygrants-grantaccept' => 'اعطا',
	'mwoauthmanagemygrants-confirm-legend' => 'مدیریت برنامه‌های متصل',
	'mwoauthmanagemygrants-update' => 'اعطای بارگذاری',
	'mwoauthmanagemygrants-renounce' => 'برداشتن ثبت',
	'mwoauthmanagemygrants-action' => 'تغییر وضعیت:',
	'mwoauthmanagemygrants-confirm-submit' => 'به‌روزرسانی وضعیت رمز دسترسی',
	'mwoauthmanagemygrants-success-update' => 'رمز دسترسی برای این مصرف‌کننده به‌روز شد.',
	'mwoauthmanagemygrants-success-renounce' => 'رمز دسترسی برای این مصرف‌کننده حذف  شد.',
	'logentry-mwoauthconsumer-propose' => '$1 {{GENDER:$2|پیشنهاد }} یک مصرف‌کننده  OAuth داد  (کد مصرف‌کننده $4)',
	'logentry-mwoauthconsumer-update' => '$1 {{GENDER:$2|به‌روز کرد}} یک مصرف‌کننده  OAuth با  (کد مصرف‌کننده $4)',
	'logentry-mwoauthconsumer-approve' => '$1  یک مصرف‌کننده  OAuth  برای  $3 {{GENDER:$2|تائید کرد}}  (کد مصرف‌کننده $4)',
	'logentry-mwoauthconsumer-reject' => '$1  یک مصرف‌کننده  OAuth  برای  $3 {{GENDER:$2|ردکرد}}  (کد مصرف‌کننده $4)',
	'logentry-mwoauthconsumer-disable' => '$1  یک مصرف‌کننده  OAuth  برای  $3 {{GENDER:$2|غیرفعال کرد}}  (کد مصرف‌کننده $4)',
	'logentry-mwoauthconsumer-reenable' => '$1  یک مصرف‌کننده  OAuth  برای  $3 {{GENDER:$2|دوباره فعال کرد}}  (کد مصرف‌کننده $4)',
	'mwoauthconsumer-consumer-logpage' => 'سیاهه مصرف OAuth',
	'mwoauthconsumer-consumer-logpagetext' => 'سیاههٔ تائیدها، ردها و غیرفعال‌سازی مصرف‌کنندگان رد شده OAuth.',
	'mwoauth-bad-request-invalid-action' => 'پوزش، خطایی رخ داد. برای تماس با برنامه‌نویس برای کمک به آدرس زیر مراجعه کنید.

<span class="plainlinks mw-mwoautherror-details">Unknown URL, [https://www.mediawiki.org/wiki/Help:OAuth/Errors#E002 E002]</span>',
	'mwoauth-bad-request-invalid-action-contact' => 'پوزش، خطایی رخ داد. برای [$1 تماس]  با برنامه‌نویس برای کمک به آدرس زیر مراجعه کنید.

<span class="plainlinks mw-mwoautherror-details">Unknown URL, [https://www.mediawiki.org/wiki/Help:OAuth/Errors#E003 E003]</span>',
	'mwoauthdatastore-access-token-not-found' => 'هیچ اعطای تائید برای رمز اختیار یافت نشد.',
	'mwoauthdatastore-bad-token' => 'هیچ نشانه مطابق درخواست شما یافت نشد.',
	'mwoauthdatastore-bad-verifier' => 'کد تأیید صحت ارائه‌شده معتبر نیست.',
	'mwoauthdatastore-invalid-token-type' => 'نوع رمز درخواستی نامعتبر است.',
	'mwoauthgrants-general-error' => 'خطایی در درخواست OAuth شما وجود داشت.',
	'mwoauthserver-bad-consumer' => '" $1 " دیگر یک برنامه وصل تایید شده نیست، برای کمک با نویسندهٔ برنامه [ $2  تماس بگیرید].
N!<span class="plainlinks mw-mwoautherror-details">نرم افزار متصل‌شدهٔ OAuth مورد تایید نیست، [https://www.mediawiki.org/wiki/Help:OAuth و اشتباهات #E005 E005]</span>',
	'mwoauthserver-bad-consumer-key' => 'با عرض پوزش، مشکلی در طی اتصال این برنامه پیش‌آمد.
N!<span class="plainlinks mw-mwoautherror-details">کلید ناشناخته OAuth، [https://www.mediawiki.org/wiki/Help:OAuth و اشتباهات #E006 E006]</span>',
	'mwoauthserver-invalid-request-token' => 'علامت نامعتبر در درخواست شما.',
	'mwoauth-invalid-authorization-title' => 'خطای مجوز OAuth',
	'mwoauth-invalid-authorization' => 'سرصفحه‌های اختیار در درخواست شما، معتبر نیستند: $1',
	'mwoauth-invalid-authorization-wrong-wiki' => 'سرصفحه‌های اختیار در درخواست شما، برای $1 معتبر نیستند',
	'mwoauth-invalid-authorization-invalid-user' => 'سرصفحه‌های اختیار در درخواست شما، برای کاربری ناموجود است.',
	'mwoauth-invalid-authorization-wrong-user' => 'سرصفحه‌های اختیار در درخواست شما، برای کاربر متفاوت است.',
	'mwoauth-form-privacypolicy-link' => '[[{{ns:Project}}:Privacy policy|سیاست حریم شخصی]]',
	'mwoauth-form-button-approve' => 'اجازه می‌دهد',
	'mwoauth-form-button-cancel' => 'انصراف',
	'mwoauth-error' => 'خطای اتصال برنامه کاربردی',
	'mwoauth-grants-heading' => 'دسترسی‌های درخواست شده:',
	'mwoauth-grants-nogrants' => 'برنامه نیازمند هیچ دسترسی نخواسته است.',
	'mwoauth-grant-group-page-interaction' => 'تعامل با صفحات',
	'mwoauth-grant-group-file-interaction' => 'تعامل با رسانه',
	'mwoauth-grant-group-watchlist-interaction' => 'تعامل با فهرست پیگیری‌های شما',
	'mwoauth-grant-group-email' => 'ارسال رایانامه',
	'mwoauth-grant-group-high-volume' => 'انجام فعالیت‌های حجم بالا',
	'mwoauth-grant-group-customization' => 'سفارشی‌سازی و تنظیمات',
	'mwoauth-grant-group-administration' => 'انجام اقدامات مدیریتی',
	'mwoauth-grant-group-other' => 'فعالیت‌های متفرقه',
	'mwoauth-grant-blockusers' => 'بستن و باز کردن کاربرها',
	'mwoauth-grant-createaccount' => 'ایجاد حساب‌های کاربری',
	'mwoauth-grant-createeditmovepage' => 'ایجاد، ویرایش و انتقال صفحات',
	'mwoauth-grant-delete' => 'حذف صفحات، نسخه‌های ویرایش و سیاهه ورودی',
	'mwoauth-grant-editinterface' => 'ویرایش CSS کاربر/جاوااسکریپت  و فضای نام مدیاویکی',
	'mwoauth-grant-editmycssjs' => 'ویرایش  CSS /جاوااسکریپت  کاربری خودتان',
	'mwoauth-grant-editmyoptions' => 'اولویت‌های کاربری خود را ویرایش کنید',
	'mwoauth-grant-editmywatchlist' => 'ویرایش فهرست پی‌گیری‌هایتان',
	'mwoauth-grant-editpage' => 'ویرایش صفحات موجود',
	'mwoauth-grant-editprotected' => 'ویرایش صفحه حفاظت شده',
	'mwoauth-grant-highvolume' => 'ویرایش با حجم بالا',
	'mwoauth-grant-oversight' => 'پنهان‌کردن ویرایش‌ها',
	'mwoauth-grant-patrol' => 'تغییرات گشت صفحات',
	'mwoauth-grant-protect' => 'حفاظت و عدم حفاظت صفحات',
	'mwoauth-grant-rollback' => 'واگردانی  تغییرات صفحات',
	'mwoauth-grant-sendemail' => 'ارسال رایانامه به دیگر کاربران',
	'mwoauth-grant-uploadeditmovefile' => 'بارگذاری، جایگزینی و انتقال پرونده‌ها',
	'mwoauth-grant-uploadfile' => 'بازگذاری پرونده‌های جدید',
	'mwoauth-grant-useoauth' => 'دسترسی‌های اولیه',
	'mwoauth-grant-viewdeleted' => 'مشاهدهٔ اطلاعات حذف',
	'mwoauth-grant-viewmywatchlist' => 'مشاهدۀ فهرست پیگیری‌هایتان',
	'mwoauth-oauth-exception' => 'خطایی در پروتکل OAuth رخ داد :$1',
	'right-mwoauthproposeconsumer' => 'پیشنهاد مصرف‌کننده جدید OAuth',
	'right-mwoauthupdateownconsumer' => 'به‌روزرسانی مصرف‌کننده‌های OAuth که شما کنرتل می‌کنید',
	'right-mwoauthmanageconsumer' => 'مدیریت مصرف‌کننده‌های OAuth',
	'right-mwoauthsuppress' => 'توقیف مصرف‌کنندهٔ OAuth',
	'right-mwoauthviewsuppressed' => 'مشاهده مصرف‌کننده‌های OAuth توقیف‌شده',
	'right-mwoauthviewprivate' => 'مشاهدهٔ داده‌های شخصی OAuth',
	'right-mwoauthmanagemygrants' => 'مدیریت OAuthهای اعطاشده',
	'action-mwoauthmanageconsumer' => 'مدیریت مصرف‌کننده‌های OAuth',
	'action-mwoauthmanagemygrants' => 'مدیریت اعطاشده‌های OAuth شما',
	'action-mwoauthproposeconsumer' => 'پیشنهاد مصرف‌کنندهٔ جدید OAuth',
	'action-mwoauthupdateownconsumer' => 'به‌روزرسانی مصرف‌کننده‌های OAuth که شما کنترل می‌کنید',
	'action-mwoauthviewsuppressed' => 'نمایش مصرف‌کننده‌های OAuth توقیف‌شده',
	'mwoauth-listgrantrights-summary' => 'فهرست روبرو OAuthهای اعطاشده با دسترسی‌هایشان به دسترسی‌های کاربری است. کاربران می‌توانند برنامه‌ها را به حسابشان دسترسی دهند ولی با دسترسی محدود بر پایهٔ اعطاشده‌های کاربر به برنامه. یک برنامه از طرف کاربر عمل می‌کند و نمی‌تواند کاری را انجام دهد که کاربر بدان دسترسی ندارد.
اینجا احتمالاً [[{{MediaWiki:Listgrouprights-helppage}}|اطلاعات بیشتری]] دربارهٔ دسترسی‌های منحصر به‌فرد وجود دارد.',
	'mwoauth-listgrants-grant' => 'اعطا',
	'mwoauth-listgrants-rights' => 'دسترسی‌ها',
);

/** Finnish (suomi)
 * @author Nike
 * @author Pxos
 * @author Stryn
 */
$messages['fi'] = array(
	'mwoauth-prefs-managegrants' => 'Liitetyt sovellukset:',
	'mwoauth-prefs-managegrantslink' => 'Hallinnoi $1 {{PLURAL:$1|yhdistettyä sovellusta}}',
	'mwoauth-consumer-name' => 'Sovelluksen nimi:',
	'mwoauth-consumer-user' => 'Julkaisija:',
	'mwoauth-consumer-stage' => 'Nykyinen status:',
	'mwoauth-consumer-wiki-thiswiki' => 'Nykyinen projekti ($1)',
	'mwoauth-consumer-reason' => 'Syy:',
	'mwoauth-consumer-stage-proposed' => 'ehdotettu',
	'mwoauth-consumer-stage-rejected' => 'hylätty',
	'mwoauth-consumer-stage-expired' => 'vanhentunut',
	'mwoauth-consumer-stage-approved' => 'hyväksytty',
	'mwoauth-consumer-stage-disabled' => 'poistettu käytöstä',
	'mwoauth-consumer-stage-suppressed' => 'häivytetty',
	'oauthlistconsumers' => 'Luettele OAuth-sovellukset',
	'mwoauthlistconsumers-legend' => 'Selaa OAuth-sovelluksia',
	'mwoauthlistconsumers-name' => 'Sovelluksen nimi',
	'mwoauth-consumer-stage-any' => 'mikä tahansa',
	'mwoauthlistconsumers-status-proposed' => 'ehdotettu',
	'mwoauthlistconsumers-status-approved' => 'hyväksytty',
	'mwoauthlistconsumers-status-disabled' => 'poistettu käytöstä',
	'mwoauthlistconsumers-status-rejected' => 'hylätty',
	'mwoauthlistconsumers-status-expired' => 'vanhentunut',
	'oauthmanagemygrants' => 'Yhdistettyjen sovellusten hallinta',
	'mwoauthmanagemygrants-none' => 'Yhtään sovellusta ei ole tällä hetkellä liitetty tunnukseesi.', # Fuzzy
	'mwoauthmanagemygrants-user' => 'Julkaisija:',
	'mwoauth-grant-group-customization' => 'Mukautus ja asetukset',
);

/** French (français)
 * @author Crochet.david
 * @author Dr Brains
 * @author Gomoko
 * @author Jean-Frédéric
 * @author Linedwell
 * @author Louperivois
 * @author Ltrlg
 * @author McDutchie
 * @author VIGNERON
 * @author Wyz
 */
$messages['fr'] = array(
	'mwoauth-desc' => 'Autorise l’utilisation de OAuth 1.0a pour l’authentification de l’API',
	'mwoauth-verified' => "L’application peut maintenant accéder à MediaWiki en votre nom.

Pour terminer le processus, veuillez fournir cette valeur de vérification à l’application : ''' $1 '''",
	'mwoauth-missing-field' => 'Valeur manquante pour le champ « $1 »',
	'mwoauth-invalid-field' => 'Valeur invalide fournie pour le champ « $1 »',
	'mwoauth-invalid-field-generic' => 'Valeur non valide fournie',
	'mwoauth-field-hidden' => '(cette information est masquée)',
	'mwoauth-field-private' => '(cette information est privée)',
	'mwoauth-grant-generic' => 'ensemble de droits « $1 »',
	'mwoauth-prefs-managegrants' => 'Applications connectées :',
	'mwoauth-prefs-managegrantslink' => 'Gérer $1 {{PLURAL:$1|application connectée|applications connectées}}',
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
	'mwoauth-consumer-wiki' => 'Projet applicable :',
	'mwoauth-consumer-wiki-thiswiki' => 'Projet actuel ($1)',
	'mwoauth-consumer-wiki-other' => 'Projet spécifique',
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
	'mwoauth-missing-consumer-key' => 'Aucune clé de consommateur n’a été fournie.',
	'mwoauth-invalid-consumer-key' => 'Aucun consommateur n’existe avec la clé fournie.',
	'mwoauth-invalid-access-token' => 'Aucun jeton d’accès n’existe pour la clé fournie',
	'mwoauth-invalid-access-wrongwiki' => 'Le consommateur ne peut être utilisé que sur le projet « $1 ».',
	'mwoauth-consumer-conflict' => 'Quelqu’un a modifié les attributs de ce consommateur pendant que vous le consultiez. Veuillez réessayer. Vous pouvez aussi vérifier le journal des modifications.',
	'mwoauth-consumer-grantshelp' => 'Chaque droit accorde l’accès aux droits d&utilisateur listés qu’un compte utilisateur possède déjà. Voyez le [[Special:OAuth/grants|tableau des droits]] pour plus d’information.',
	'mwoauth-consumer-stage-proposed' => 'proposé',
	'mwoauth-consumer-stage-rejected' => 'rejeté',
	'mwoauth-consumer-stage-expired' => 'expiré',
	'mwoauth-consumer-stage-approved' => 'approuvé',
	'mwoauth-consumer-stage-disabled' => 'désactivé',
	'mwoauth-consumer-stage-suppressed' => 'supprimé',
	'oauthconsumerregistration' => 'Inscription de consommateur OAuth',
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
* Vous pouvez utiliser un ID de projet pour limiter ce consommateur à un unique projet de ce site (utilisez "*" pour tous les projets).
* L’adresse de courriel fournie doit correspondre à celle de votre compte (qui doit avoir été confirmée).',
	'mwoauthconsumerregistration-update-text' => 'Utilisez le formulaire ci-dessous pour mettre à jour les aspects d’un consommateur OAuth que vous contrôlez.

Toutes les valeurs ici écraseront les précédentes. Ne laissez aucun champ blanc sauf si vous désirez vraiment effacer ces valeurs.',
	'mwoauthconsumerregistration-maintext' => 'Cette page sert à laisser les développeurs proposer et mettre à jour des applications consommatrices OAuth dans le registre de ce site.

Depuis ici, vous pouvez :
* [[Special:OAuthConsumerRegistration/propose|Demander un jeton pour un nouveau consommateur]].
* [[Special:OAuthConsumerRegistration/list|Gérer os consommateurs existants]].

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
	'oauthmanageconsumers' => 'Gérer les consommateurs OAuth',
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
	'mwoauthmanageconsumers-viewing' => '{{GENDER:$1|L’utilisateur|L’utilisatrice}} « $1 » est actuellement en train de visualiser ce consommateur',
	'mwoauthmanageconsumers-success-approved' => 'La requête a été approuvée.',
	'mwoauthmanageconsumers-success-rejected' => 'La requête a été rejetée.',
	'mwoauthmanageconsumers-success-disabled' => 'Le consommateur a été désactivé.',
	'mwoauthmanageconsumers-success-reanable' => 'Le consommateur a été réactivé.',
	'mwoauthmanageconsumers-search-name' => 'consommateurs avec ce nom',
	'mwoauthmanageconsumers-search-publisher' => 'consommateurs par cet utilisateur',
	'oauthlistconsumers' => 'Lister les applications OAuth',
	'mwoauthlistconsumers-legend' => 'Naviguer dans les applications OAuth',
	'mwoauthlistconsumers-view' => 'détails',
	'mwoauthlistconsumers-none' => 'Aucune application correspondant à ce critère n’a été trouvé.',
	'mwoauthlistconsumers-name' => 'Nom de l’application',
	'mwoauthlistconsumers-version' => 'Version du consommateur',
	'mwoauthlistconsumers-user' => 'Éditeur',
	'mwoauthlistconsumers-description' => 'Description',
	'mwoauthlistconsumers-wiki' => 'Projet applicable',
	'mwoauthlistconsumers-callbackurl' => '« URL de rappel » de OAuth',
	'mwoauthlistconsumers-grants' => 'Droits applicables',
	'mwoauthlistconsumers-basicgrantsonly' => '(accès de base uniquement)',
	'mwoauthlistconsumers-status' => 'État',
	'mwoauth-consumer-stage-any' => 'tous',
	'mwoauthlistconsumers-status-proposed' => 'proposé',
	'mwoauthlistconsumers-status-approved' => 'approuvé',
	'mwoauthlistconsumers-status-disabled' => 'désactivé',
	'mwoauthlistconsumers-status-rejected' => 'rejeté',
	'mwoauthlistconsumers-status-expired' => 'expiré',
	'oauthmanagemygrants' => 'Gérer les applications connectées',
	'mwoauthmanagemygrants-text' => 'Cette page liste toutes les applications qui peuvent utiliser votre compte. Pour chacune de ces applications, son périmètre d’accès est limité par les droits que vous lui avez accordés quand vous l’avez autorisée à agir en votre nom. Si vous avez autorisé une application de façon séparée à accéder à différents projets « frères » en votre nom, alors vous verrez une configuration distincte pour chacun de ces projets ci-dessous.

Les applications connectées accèdent à votre compte en utilisant le protocole OAuth. <span class="plainlinks">([https://www.mediawiki.org/wiki/Special:MyLanguage/Help:OAuth En savoir plus sur les applications connectées])</span>',
	'mwoauthmanagemygrants-notloggedin' => 'Vous devez être connecté pour accéder à cette page.',
	'mwoauthmanagemygrants-navigation' => 'Navigation :',
	'mwoauthmanagemygrants-showlist' => 'Liste des applications connectées',
	'mwoauthmanagemygrants-none' => 'Il n’y a aucune application connectée à votre compte.',
	'mwoauthmanagemygrants-user' => 'Éditeur ː',
	'mwoauthmanagemygrants-description' => 'Description',
	'mwoauthmanagemygrants-wikiallowed' => 'Autorisé sur le projet ː',
	'mwoauthmanagemygrants-grants' => 'Droits applicables',
	'mwoauthmanagemygrants-grantsallowed' => 'Droits accordés :',
	'mwoauthmanagemygrants-applicablegrantsallowed' => 'Droits applicables accordés :',
	'mwoauthmanagemygrants-review' => 'gérer l’accès',
	'mwoauthmanagemygrants-revoke' => 'révoquer l’accès',
	'mwoauthmanagemygrants-grantaccept' => 'Accordé',
	'mwoauthmanagemygrants-update-text' => 'Utiliser le formulaire ci-dessous pour modifier les droits accordés à une application pour agir en votre nom.',
	'mwoauthmanagemygrants-revoke-text' => 'Utiliser le formulaire ci-dessous pour révoquer le droit, pour une application, d’agir en votre nom.',
	'mwoauthmanagemygrants-confirm-legend' => 'Gérer les applications connectées',
	'mwoauthmanagemygrants-update' => 'Mettre à jour les droits',
	'mwoauthmanagemygrants-renounce' => 'Ne plus autoriser',
	'mwoauthmanagemygrants-action' => 'Modifier l’état :',
	'mwoauthmanagemygrants-confirm-submit' => 'Mettre à jour l’état du jeton d’accès',
	'mwoauthmanagemygrants-success-update' => 'Le jeton d’accès pour ce consommateur a été mis à jour.',
	'mwoauthmanagemygrants-success-renounce' => 'Le jeton d’accès pour ce consommateur a été supprimé.',
	'mwoauthmanagemygrants-useoauth-tooltip' => 'Pourquoi ne puis-je pas mettre à jour cette autorisation ? Celle-ci donne à votre application connectée des droits de base dont elle a besoin pour fonctionner correctement. Si vous ne voulez pas que cette application connectée ait ces droits, vous devez révoquer l’accès de cette application.',
	'logentry-mwoauthconsumer-propose' => '$1 {{GENDER:$2|a proposé}} un consommateur OAuth (clé du consommateur $4)',
	'logentry-mwoauthconsumer-update' => '$1 {{GENDER:$2|a mis à jour}} un consommateur OAuth (clé du consommateur $4)',
	'logentry-mwoauthconsumer-approve' => '$1 {{GENDER:$2|a approuvé}} un consommateur OAuth proposé par $3 (clé du consommateur $4)',
	'logentry-mwoauthconsumer-reject' => '$1 {{GENDER:$2|a rejeté}} un consommateur OAuth proposé par $3 (clé du consommateur $4)',
	'logentry-mwoauthconsumer-disable' => '$1 a désactivé un consommateur OAuth proposé par $3 (clé du consommateur $4)',
	'logentry-mwoauthconsumer-reenable' => '$1 {{GENDER:$2|a réactivé}} un consommateur OAuth proposé par $3 (clé du consommateur $4)',
	'mwoauthconsumer-consumer-logpage' => 'Journal du consommateur OAuth',
	'mwoauthconsumer-consumer-logpagetext' => 'Journal des approbations, rejets et désactivations de consommateurs OAuth enregistrés.',
	'mwoauth-bad-request-missing-params' => 'Désolé,quelque chose s’est mal passé lors de la configuration de cette application connectée. <span class="plainlinks">[https://www.mediawiki.org/wiki/Special:MyLanguage/Help:OAuth Contactez le support]</span> pour vous aider à le corriger.

<span class="plainlinks mw-mwoautherror-details">Paramètres OAuth manquants, [https://www.mediawiki.org/wiki/Help:OAuth/Errors#E001 E001]</span>',
	'mwoauth-bad-request-invalid-action' => 'Désolé, quelque chose s’est mal passé, vous devez contacter l’auteur de l’application pour vous aider.

<span class="plainlinks mw-mwoautherror-details">URL inconnue, [https://www.mediawiki.org/wiki/Help:OAuth/Errors#E002 E002]</span>',
	'mwoauth-bad-request-invalid-action-contact' => 'Désolé, quelque chose s’est mal passé. Vous aurez besoin de [$1 contacter] l’auteur de l’application pour obtenir de l’aide.

<span class="plainlinks mw-mwoautherror-détail">URL inconnue, [https://www.mediawiki.org/wiki/Help:OAuth/Errors#E003 E003]</span>',
	'mwoauthdatastore-access-token-not-found' => 'Aucun droit approuvé n’a été trouvé pour ce jeton d’autorisation.',
	'mwoauthdatastore-request-token-not-found' => 'Désolé, quelque chose s’est mal passé lors de la connexion de l’application.
Revenez en arrière et essayez de reconnecter votre compte, ou contactez l’auteur de l’application.

<span class="plainlinks mw-mwoautherror-details">Jeton OAuth introuvable, [https://www.mediawiki.org/wiki/Help:OAuth/Errors#E004 E004]</span>',
	'mwoauthdatastore-bad-token' => 'Aucun jeton correspondant à votre demande n’a été trouvé',
	'mwoauthdatastore-bad-verifier' => 'Le code de vérification fourni n’était pas valide',
	'mwoauthdatastore-invalid-token-type' => 'Le type de jeton demandé n’est pas valide',
	'mwoauthgrants-general-error' => 'Il y a eu une erreur dans votre demande OAuth',
	'mwoauthserver-bad-consumer' => '$1 n’est plus approuvé comme App Connectée, [$2 contactez] l’auteur de l’application pour de l’aide.

<span class="plainlinks mw-mwoautherror-details">Application connectée OAuth non approuvée, [https://www.mediawiki.org/wiki/Help:OAuth/Errors#E005 E005]</span>',
	'mwoauthserver-bad-consumer-key' => 'Désolé, quelque chose s’est mal passé lors de la connexion de cette application.

<span class="plainlinks mw-mwoautherror-details">Clé de OAuth inconnue, [https://www.mediawiki.org/wiki/Help:OAuth/Errors#E006 E006]</span>',
	'mwoauthserver-insufficient-rights' => 'Votre compte n’est pas autorisé à utiliser les Applications connectées, contactez l’administrateur de votre site pour savoir pourquoi.

<span class="plainlinks mw-mwoautherror-details">Droits utilisateur OAuth insuffisants, [https://www.mediawiki.org/wiki/Help:OAuth/Errors#E007 E007]</span>',
	'mwoauthserver-invalid-request-token' => 'Jeton non valide dans votre demande',
	'mwoauthserver-invalid-user' => 'Pour utiliser les Applications connectées sur ce site, vous devez avoir un compte transverse à tous les projets. Quand vous aurez un compte sur tous les projets, vous pouvez essayer de vous reconnecter à $1.

<span class="plainlinks mw-mwoautherror-details">Connexion unifiée nécessaire, [https://www.mediawiki.org/wiki/Help:OAuth/Errors#E008 E008]</span>',
	'mwoauth-invalid-authorization-title' => 'Erreur d’autorisation OAuth',
	'mwoauth-invalid-authorization' => 'Les entêtes d’autorisation dans votre requête ne sont pas valides : $1',
	'mwoauth-invalid-authorization-wrong-wiki' => 'Les entêtes d’autorisation dans votre requête ne sont pas valides pour $1',
	'mwoauth-invalid-authorization-invalid-user' => 'Les entêtes d’autorisation dans votre requête concernent un utilisateur qui n’existe pas ici',
	'mwoauth-invalid-authorization-wrong-user' => 'Les entêtes d’autorisation dans votre requête concernent un autre utilisateur',
	'mwoauth-invalid-authorization-not-approved' => 'L’application à laquelle vous essayez de vous connecter semble mal paramétrée, contactez l’auteur de $1 pour de l’aide.',
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
	'mwoauth-error' => 'Erreur de connexion de l’application',
	'mwoauth-grants-heading' => 'Droits requis :',
	'mwoauth-grants-nogrants' => 'L’application n’a demandé aucun droit.',
	'mwoauth-acceptance-cancelled' => 'Vous avez choisi de ne pas autoriser $1 à accéder à votre compte. $1 ne fonctionnera pas à moins que vous lui autorisiez l’accès. Vous pouvez revenir à $1 ou [[Special:OAuthManageMyGrants|gérer]] vos applications connectées.',
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
	'mwoauth-grant-editmyoptions' => 'Modifier vos préférences utilisateur',
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
	'mwoauth-listgrantrights-summary' => 'Voici une liste des droits OAuth, avec leur accès associé aux droits utilisateur. Les utilisateurs peuvent autoriser les applications à utiliser leur compte, mais avec des droits limités d’après les droits que l’utilisateur a donnés à l’application. Un application agissant au nom d’un utilisateur ne peut toutefois pas, de fait, utiliser des droits que l’utilisateur ne possède pas.
Il peut y avoir [[{{MediaWiki:Listgrouprights-helppage}}|plus d’information]] sur les droits individuels.',
	'mwoauth-listgrants-grant' => 'Accorder',
	'mwoauth-listgrants-rights' => 'Droits',
);

/** Western Frisian (Frysk)
 * @author Kening Aldgilles
 */
$messages['fy'] = array(
	'mwoauth-form-button-cancel' => 'Ofbrekke',
	'mwoauth-grant-group-email' => 'E-mail stjoere',
);

/** Galician (galego)
 * @author Elisardojm
 * @author Toliño
 */
$messages['gl'] = array(
	'mwoauth-desc' => 'Autenticación API OAuth 1.0a', # Fuzzy
	'mwoauth-verified' => "Agora, esta aplicación ten permitido acceder a MediaWiki no seu nome.

Para completar o proceso, achegue este valor de verificación á aplicación: '''$1'''",
	'mwoauth-missing-field' => 'Falta o valor para o campo "$1"',
	'mwoauth-invalid-field' => 'Achegouse un valor non válido para o campo "$1"',
	'mwoauth-invalid-field-generic' => 'O valor proporcionado non é válido',
	'mwoauth-field-hidden' => '(esta información está agochada)',
	'mwoauth-field-private' => '(esta información é privada)',
	'mwoauth-grant-generic' => 'conxunto de dereitos "$1"',
	'mwoauth-prefs-managegrants' => 'Acceso de consumidor OAuth:', # Fuzzy
	'mwoauth-prefs-managegrantslink' => 'Administrar as concesións en nome desta conta', # Fuzzy
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
	'mwoauth-consumer-wiki' => 'Wiki aplicable:', # Fuzzy
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
	'oauthconsumerregistration' => 'Rexistro de consumidores OAuth', # Fuzzy
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
* O enderezo de correo electrónico achegado debe coincidir co da súa conta (que debeu ser confirmado).', # Fuzzy
	'mwoauthconsumerregistration-update-text' => 'Utilice o formulario inferior para actualizar aspectos dun consumidor OAuth que controle.

Todos os valores que haxa aquí sobrescribirán os anteriores. Non deixe campos en branco a menos que queira limpar eses valores.',
	'mwoauthconsumerregistration-maintext' => 'Esta páxina está destinada a que os desenvolvedores propoñan e actualicen aplicacións de consumidores OAuth no rexistro do sitio.

Desde aquí, pode:
* [[Special:OAuthConsumerRegistration/propose|Solicitar un pase para un novo consumidor]].
* [[Special:OAuthConsumerRegistration/list|Administrar os consumidores existentes]].

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
	'oauthmanageconsumers' => 'Administrar os consumidores OAuth', # Fuzzy
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
	'mwoauthmanageconsumers-viewing' => 'O usuario "$1" está vendo este consumidor nestes intres', # Fuzzy
	'mwoauthmanageconsumers-success-approved' => 'Aprobouse a solicitude.',
	'mwoauthmanageconsumers-success-rejected' => 'Rexeitouse a solicitude.',
	'mwoauthmanageconsumers-success-disabled' => 'Desactivouse o consumidor.',
	'mwoauthmanageconsumers-success-reanable' => 'Reactivouse o consumidor.',
	'oauthmanagemygrants' => 'Administrar as concesión de conta OAuth', # Fuzzy
	'mwoauthmanagemygrants-notloggedin' => 'Debe acceder ao sistema para acceder a esta páxina.',
	'mwoauthmanagemygrants-navigation' => 'Navegación:',
	'mwoauthmanagemygrants-showlist' => 'Lista de consumidores aceptados', # Fuzzy
	'mwoauthmanagemygrants-none' => 'Ningún consumidor ten acceso á súa conta.', # Fuzzy
	'mwoauthmanagemygrants-user' => 'Editor', # Fuzzy
	'mwoauthmanagemygrants-description' => 'Descrición',
	'mwoauthmanagemygrants-wikiallowed' => 'Permitido no wiki', # Fuzzy
	'mwoauthmanagemygrants-grants' => 'Concesións aplicables',
	'mwoauthmanagemygrants-grantsallowed' => 'Concesións permitidas:',
	'mwoauthmanagemygrants-applicablegrantsallowed' => 'Concesións aplicables permitidas:',
	'mwoauthmanagemygrants-review' => 'administrar o acceso',
	'mwoauthmanagemygrants-grantaccept' => 'Concedido',
	'mwoauthmanagemygrants-confirm-legend' => 'Administrar o pase de acceso do consumidor', # Fuzzy
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
	'mwoauthdatastore-access-token-not-found' => 'Non se atopou ningunha concesión aprobada para ese pase de autorización',
	'mwoauthdatastore-request-token-not-found' => 'Non se atopou ningunha solicitude para ese pase', # Fuzzy
	'mwoauthdatastore-bad-token' => 'Non se atopou ningún pase que coincidise coa solicitude',
	'mwoauthdatastore-bad-verifier' => 'O código de verificación achegado non é válido',
	'mwoauthdatastore-invalid-token-type' => 'O tipo de pase solicitado non é válido',
	'mwoauthgrants-general-error' => 'Houbo un erro na súa solicitude OAuth',
	'mwoauthserver-bad-consumer' => 'Non se atopou ningún consumidor aprobado para a clave achegada', # Fuzzy
	'mwoauthserver-insufficient-rights' => 'Non ten os dereitos necesarios para levar a cabo esta acción', # Fuzzy
	'mwoauthserver-invalid-request-token' => 'Pase non válido na súa solicitude',
	'mwoauth-invalid-authorization-title' => 'Erro de autorización OAuth',
	'mwoauth-invalid-authorization' => 'As cabeceiras de autorización da súa solicitude non son válidas: $1',
	'mwoauth-invalid-authorization-wrong-wiki' => 'As cabeceiras de autorización da súa solicitude non son válidas para $1',
	'mwoauth-invalid-authorization-invalid-user' => 'As cabeceiras de autorización da súa solicitude son para un usuario que non existe aquí',
	'mwoauth-invalid-authorization-wrong-user' => 'As cabeceiras de autorización da súa solicitude son para un usuario diferente',
	'mwoauth-invalid-authorization-not-approved' => 'As cabeceiras de autorización da súa solicitude son para un consumidor OAuth que non está aprobado actualmente', # Fuzzy
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
	'mwoauth-error' => 'Erro OAuth', # Fuzzy
	'mwoauth-grants-heading' => 'Permisos solicitados:',
	'mwoauth-grants-nogrants' => 'A aplicación non solicitou ningún permiso.',
	'mwoauth-acceptance-cancelled' => 'Cancelou esta solicitude de autorización para que o consumidor OAuth actúe no seu nome.', # Fuzzy
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
 * @author Amire80
 * @author GilCahana
 * @author Guycn2
 * @author אור שפירא
 */
$messages['he'] = array(
	'mwoauth-desc' => 'מאפשרת שימוש ב־OAuth 1.0a לאישור ב־API',
	'mwoauth-verified' => "עכשיו היישום הזה מורשה להתחבר למדיה־ויקי בשמך.

כדי להשלים את התהליך, יש לתת את ערך האימות הבא ליישום: '''$1'''",
	'mwoauth-missing-field' => 'חסר ערך עבור שדה "$1"',
	'mwoauth-invalid-field' => 'ערך לא חוקי עבור שדה "$1"',
	'mwoauth-invalid-field-generic' => 'סופק ערך לא חוקי',
	'mwoauth-field-hidden' => '(מידע זה מוסתר)',
	'mwoauth-field-private' => '(מידע זה הוא פרטי)',
	'mwoauth-grant-generic' => 'חבילת ההרשאות "$1"',
	'mwoauth-prefs-managegrants' => 'יישומים מחוברים:',
	'mwoauth-prefs-managegrantslink' => 'ניהול {{PLURAL:$1|היישום המחובר|$1 היישומים המחוברים}}',
	'mwoauth-consumer-allwikis' => 'כל הפרויקטים באתר זה',
	'mwoauth-consumer-key' => 'מפתח צרכן:',
	'mwoauth-consumer-name' => 'שם היישום:',
	'mwoauth-consumer-version' => 'גרסת צרכן:',
	'mwoauth-consumer-user' => 'מפרסם:',
	'mwoauth-consumer-stage' => 'מצב נוכחי:',
	'mwoauth-consumer-email' => 'כתובת דוא"ל נוכחית:',
	'mwoauth-consumer-description' => 'תיאור היישום:',
	'mwoauth-consumer-callbackurl' => 'כתובת מענה (callback) של OAuth:',
	'mwoauth-consumer-grantsneeded' => 'זיכיונות מתאימים:',
	'mwoauth-consumer-required-grant' => 'חל על צרכן',
	'mwoauth-consumer-wiki' => 'מיזם מתאים:',
	'mwoauth-consumer-wiki-thiswiki' => 'מיזם נוכחי ($1):',
	'mwoauth-consumer-wiki-other' => 'מיזם מסוים',
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
	'mwoauth-consumer-not-proposed' => 'הצרכן אינו מוצע כעת',
	'mwoauth-consumer-not-disabled' => 'הצרכן אינו מבוטל כרגע',
	'mwoauth-consumer-not-approved' => 'הצרכן לא מאושר (ייתכן שהוא מבוטל)',
	'mwoauth-missing-consumer-key' => 'לא ניתן מפתח צרכן',
	'mwoauth-invalid-consumer-key' => 'אין צרכן עם מפתח זה.',
	'mwoauth-invalid-access-token' => 'אין אסימון גישה עם המפתח.',
	'mwoauth-invalid-access-wrongwiki' => 'הצרכן יכול לשמש רק במיזם "$1".',
	'mwoauth-consumer-conflict' => 'מישהו שינה את המאפיינים של הצרכן הזה בזמן שצפית בו. נא לנסות שוב. אפשר לבדוק את יומן השינויים.',
	'mwoauth-consumer-grantshelp' => "כל זיכיון נותן גישה להרשאות המשתמש הרשומות שכבר ניתנו לחשבון משתמש. ר' את הדף [[Special:OAuth/grants|טבלת הזיכיונות]] למידע נוסף.",
	'mwoauth-consumer-stage-proposed' => 'מוצע',
	'mwoauth-consumer-stage-rejected' => 'נדחה',
	'mwoauth-consumer-stage-expired' => 'פג תוקף',
	'mwoauth-consumer-stage-approved' => 'אושר',
	'mwoauth-consumer-stage-disabled' => 'בוטל',
	'mwoauth-consumer-stage-suppressed' => 'מועלם',
	'oauthconsumerregistration' => 'רישום צרכן OAuth',
	'mwoauthconsumerregistration-notloggedin' => 'עליכם להיות מחוברים כדי לגשת לדף זה.',
	'mwoauthconsumerregistration-navigation' => 'ניווט:',
	'mwoauthconsumerregistration-propose' => 'הצעת צרכן חדש.',
	'mwoauthconsumerregistration-list' => 'רשימת הצרכנים שלי',
	'mwoauthconsumerregistration-main' => 'ראשי',
	'mwoauthconsumerregistration-propose-text' => 'המפתחים צריכים להשתמש בטופס להלן כדי להציע צרכן OAuth חדש (ר\' את [//www.mediawiki.org/wiki/Extension:OAuth תיעוד ההרחבה] לפרטים נוספים). אחרי שליחת הטופס הזה יישלח אליך אסימון שהיישום שלך ישתמש בו כדי להזדהות בפני מדיה־ויקי. מנהל OAuth יצטרך לאשר את הפנייה שלך לפני שהיא תאושר על־ידי משתמשים אחרים.

מספר עצות והערות:
* מומלץ להשתמש בכמה שפחות זיכיונות. מומלץ להימנע שאינם נחוצים כעת.
* הגרסאות נכתבות בצורה "major.minor.release" (לא חובה לכתוב את שני החלקים האחרונים) ועולים בכל פעם שהזיכיונות משתנים.
* נא לספק מפתח RSA ציבורי (בתסדיר PEM) אם אפשר; אחרת ישמש אסימון סודי אחור (מאובטח פחות).
* נא להשתמש בשדה ה־restrictions ב־JSON כדי להגביל את הגישה של הצרכן הזה לכתובות IP בטווחי ה־CIDR האלה.
* באפשרותך להשתמש במזהה מיזם כדי להגביל את הצרכן למיזם אחד באתר הזה (אפשר להשתמש ב־"*" לכל המיזמים).
* כתובת הדוא"ל שניתנה צריכה לתאום לכתובת של החשבון שלך (שצריכה להיות מאומתת).',
	'mwoauthconsumerregistration-update-text' => 'השתמשו בטופס שלהלן כדי לעדכן את פרטי צרכן OAuth.

כל הערכים כאן יחליפו את כל הקודמים. אין להשאיר שדות ריקים אלא אם כן אתם מתכוונים לנקות את הערכים האלה.',
	'mwoauthconsumerregistration-maintext' => 'הדף הזה מאפשר למפתחים להציע ולעדכן יישומים שיהיו צרכני OAuth ברישום של האתר הזה.

מכאן אפשר:
* [[Special:OAuthConsumerRegistration/propose|לבקש אסימון בשביל צרכן חדש]].
* [[Special:OAuthConsumerRegistration/list|לנהל את הצרכנים הנוכחיים שלך]].

למידע נוסף על OAuth, נא לראות את [//www.mediawiki.org/wiki/Extension:OAuth התיעוד של ההרחבה].',
	'mwoauthconsumerregistration-propose-legend' => 'יישום צרכן OAuth חדש',
	'mwoauthconsumerregistration-update-legend' => 'עדכון יישום צרכן OAuth',
	'mwoauthconsumerregistration-propose-submit' => 'הצעת צרכן',
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
	'mwoauthconsumerregistration-proposed' => "ההצעה שלך לצרכן OAuth חדש התקלבה.

הוקצה לך אסימון הצרכן '''$1''' והאסימון הסודי '''$2'''. נא לרשום אותם במקום שזמין לך.",
	'mwoauthconsumerregistration-updated' => 'רישום צרכן OAuth שלך עודכן בהצלחה.',
	'mwoauthconsumerregistration-secretreset' => "ניתן לך אסימון הצרכן הסודי '''$1'''. נא לרשום אותם במקום שזמין לך.",
	'oauthmanageconsumers' => 'ניהול צרכני OAuth',
	'mwoauthmanageconsumers-notloggedin' => 'עליכם להיות מחוברים כדי לגשת לדף זה.',
	'mwoauthmanageconsumers-type' => 'תורים:',
	'mwoauthmanageconsumers-showproposed' => 'בקשות שהוצעו',
	'mwoauthmanageconsumers-showrejected' => 'בקשות שנדחו',
	'mwoauthmanageconsumers-showexpired' => 'בקשות שפג תוקפן',
	'mwoauthmanageconsumers-main' => 'ראשי',
	'mwoauthmanageconsumers-maintext' => "הדף הזה מיועד לטיפול בבקשות יישומים צרכנים של OAuth (ר' http://oauth.net) וניהול צרכני OAuth מוּכרים.",
	'mwoauthmanageconsumers-queues' => 'בחירת תור אישור צרכן להלן:',
	'mwoauthmanageconsumers-q-proposed' => 'תור בקשות צרכן',
	'mwoauthmanageconsumers-q-rejected' => 'תור בקשות צרכן שנדחן',
	'mwoauthmanageconsumers-q-expired' => 'תור בקשות צרכן שפגו',
	'mwoauthmanageconsumers-lists' => 'נא לבחור מצב צרכן מתוך רשימה להלן:',
	'mwoauthmanageconsumers-l-approved' => 'רשימת הצרכנים שאושרו',
	'mwoauthmanageconsumers-l-disabled' => 'רשימת הצרכנים הכבויים',
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
	'mwoauthmanageconsumers-review' => 'סקירה/ניהול',
	'mwoauthmanageconsumers-confirm-text' => 'טופס זה מיועד לאשר, לבטל, או לאפשר צרכן זה.',
	'mwoauthmanageconsumers-confirm-legend' => 'ניהול צרכן OAuth',
	'mwoauthmanageconsumers-action' => 'שינוי מצב:',
	'mwoauthmanageconsumers-approve' => 'אושר',
	'mwoauthmanageconsumers-reject' => 'נדחה',
	'mwoauthmanageconsumers-rsuppress' => 'דחויים ומועלמים',
	'mwoauthmanageconsumers-disable' => 'בוטל',
	'mwoauthmanageconsumers-dsuppress' => 'כבויים ומועלמים',
	'mwoauthmanageconsumers-reenable' => 'אושר',
	'mwoauthmanageconsumers-reason' => 'סיבה:',
	'mwoauthmanageconsumers-confirm-submit' => 'עדכון מצב צרכן',
	'mwoauthmanageconsumers-viewing' => '{{GENDER:$1|המשתמש|המשתמשת}} "$1" מופה כעת בצרכן הזה',
	'mwoauthmanageconsumers-success-approved' => 'הבקשה אושרה',
	'mwoauthmanageconsumers-success-rejected' => 'הבקשה נדחתה',
	'mwoauthmanageconsumers-success-disabled' => 'צרכן בוטל',
	'mwoauthmanageconsumers-success-reanable' => 'צרכן אופשר מחדש',
	'mwoauthmanageconsumers-search-name' => 'צרכנים עם השם הזה',
	'mwoauthmanageconsumers-search-publisher' => 'צרכנים שיצר המשתמש הזה',
	'oauthlistconsumers' => 'רשימות יישומי OAuth',
	'mwoauthlistconsumers-legend' => 'עיון ביישומי OAuth',
	'mwoauthlistconsumers-view' => 'פרטים',
	'mwoauthlistconsumers-none' => 'לא נמצאו יישומים שמתאימים לתנאים האלה.',
	'mwoauthlistconsumers-name' => 'שם היישום',
	'mwoauthlistconsumers-version' => 'גרסת צרכן',
	'mwoauthlistconsumers-user' => 'מפרסם',
	'mwoauthlistconsumers-description' => 'תיאור',
	'mwoauthlistconsumers-wiki' => 'מיזם מתאים',
	'mwoauthlistconsumers-callbackurl' => 'כתובת מענה של OAuth',
	'mwoauthlistconsumers-grants' => 'זיכיונות מתאימים',
	'mwoauthlistconsumers-basicgrantsonly' => '(רק גישה בסיסית)',
	'mwoauthlistconsumers-status' => 'מצב',
	'mwoauth-consumer-stage-any' => 'הכול',
	'mwoauthlistconsumers-status-proposed' => 'מוצע',
	'mwoauthlistconsumers-status-approved' => 'מאושר',
	'mwoauthlistconsumers-status-disabled' => 'כבוי',
	'mwoauthlistconsumers-status-rejected' => 'דחוי',
	'mwoauthlistconsumers-status-expired' => 'פג־תוקף',
	'oauthmanagemygrants' => 'ניהול יישומים מחוברים',
	'mwoauthmanagemygrants-text' => 'בדף הזה נמצאת רשימה של כל היישומים שיכולים להשתמש בחשבון שלך. לכל יישום כזה טווח הגישה מוגבל בהרשאות שנתת לו כאשר אישרת לו לפעול בשמך. אם נתת אישור נפרד ליישום לגשת בשמך למיזמים אחרים, תראה את ההגדרות הנפרדות לכל מיזם כזה להלן.

היישומים המחוברים ניגשים לחשבון שלך באמצעות פרוטוקול OAuth. <span class="plainlinks">([https://www.mediawiki.org/wiki/Special:MyLanguage/Help:OAuth מידע נוסף על יישומים מחוברים])</span>',
	'mwoauthmanagemygrants-notloggedin' => 'עליכם להיות מחוברים כדי לגשת לדף זה.',
	'mwoauthmanagemygrants-navigation' => 'ניווט:',
	'mwoauthmanagemygrants-showlist' => 'רשימת היישומים המחוברים',
	'mwoauthmanagemygrants-none' => 'לא מחובר שום יישום לחשבון שלך.',
	'mwoauthmanagemygrants-user' => 'מפרסם:',
	'mwoauthmanagemygrants-description' => 'תיאור',
	'mwoauthmanagemygrants-wikiallowed' => 'מורשה במיזם:',
	'mwoauthmanagemygrants-grants' => 'זיכיונות מתאימים',
	'mwoauthmanagemygrants-grantsallowed' => 'זיכיונות מותרים',
	'mwoauthmanagemygrants-applicablegrantsallowed' => 'זיכיונות מתאימים מותרים:',
	'mwoauthmanagemygrants-review' => 'ניהול גישה',
	'mwoauthmanagemygrants-revoke' => 'שלילת גישה',
	'mwoauthmanagemygrants-grantaccept' => 'ניתן זיכיון',
	'mwoauthmanagemygrants-update-text' => 'הטופס להלן משמש לשינוי ההרשאות שניתנו ליישום לפעול בשמך.',
	'mwoauthmanagemygrants-revoke-text' => 'הטופס להלן משמש לשלילת ההרשאות שניתנו ליישום לפעול בשמך.',
	'mwoauthmanagemygrants-confirm-legend' => 'ניהול יישום מחובר',
	'mwoauthmanagemygrants-update' => 'עדכון זיכיונות',
	'mwoauthmanagemygrants-renounce' => 'שלילת אישור',
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
	'mwoauthconsumer-consumer-logpage' => 'יומן צרכן OAuth',
	'mwoauthconsumer-consumer-logpagetext' => 'יומן של אישורים, שלילות וכיבויים של צרכני OAuth רשומים.',
	'mwoauth-bad-request-missing-params' => 'סליחה, משהו השתבש עם הגדרות היישום המחובר. <span class="plainlinks">[https://www.mediawiki.org/wiki/Special:MyLanguage/Help:OAuth נא ליצור קשר עם התמיכה]</span> כדי לקבל עזרה בתיקון.

<span class="plainlinks mw-mwoautherror-details">חסרים פרמטרים ל־OAuth, [https://www.mediawiki.org/wiki/Help:OAuth/Errors#E001 E001]</span>',
	'mwoauth-bad-request-invalid-action' => 'סליחה, משהו השתבש ויש ליצור קשיר עם יוצר היישום כדי לקבל עזרה עם זה.

<span class="plainlinks mw-mwoautherror-details">כתובת בלתי ידועה, [https://www.mediawiki.org/wiki/Help:OAuth/Errors#E002 E002]</span>',
	'mwoauth-bad-request-invalid-action-contact' => 'סליחה, משהו השתבש. יש [$1 ליצור קשר] עם יוצר היישום כדי לקבל עזרה עם זה.

<span class="plainlinks mw-mwoautherror-details">כתובת לא ידועה, [https://www.mediawiki.org/wiki/Help:OAuth/Errors#E003 E003]</span>',
	'mwoauthdatastore-access-token-not-found' => 'לא נמצא שום זיכיון מאושר עבור אסימון האישור הזה.',
	'mwoauthdatastore-request-token-not-found' => 'סליחה, משהו השתבש עם היישום הזה.
נא לחזור ולנסות להתחבר שוב לחשבונך או ליצור קשר עם יוצר היישום.

<span class="plainlinks mw-mwoautherror-details">לא נמצא אסימון OAuth, [https://www.mediawiki.org/wiki/Help:OAuth/Errors#E004 E004]</span>',
	'mwoauthdatastore-bad-token' => 'לא נמצא אסימון מתאים לבקשתך.',
	'mwoauthdatastore-bad-verifier' => 'קוד האימות שסופק לא חוקי.',
	'mwoauthdatastore-invalid-token-type' => 'האסימון המבוקש אינו תקין.',
	'mwoauthgrants-general-error' => 'אירעה שגיאה בבקשת OAuth.',
	'mwoauthserver-bad-consumer' => '"$1" כבר לא נתמך בתור יישום מחבור. נא [$2 ליצור קשר] עם מחבר היישום כדי לקבל עזרה.

<span class="plainlinks mw-mwoautherror-details">יישום ה־OAuth אינו מאושר, [https://www.mediawiki.org/wiki/Help:OAuth/Errors#E005 E005]</span>',
	'mwoauthserver-bad-consumer-key' => 'סליחה, משהו השתבש עם היישום הזה.

<span class="plainlinks mw-mwoautherror-details">מפתח OAuth לא ידוע, [https://www.mediawiki.org/wiki/Help:OAuth/Errors#E006 E006]</span>',
	'mwoauthserver-insufficient-rights' => 'לחשבון שלך אין אישור להשתמש ביישומים מחוברים. נא ליצור קשר עם מנהל האתר שלך כדי להבין למה.

<span class="plainlinks mw-mwoautherror-details">הרשאות משתמש OAuth לא מספיקות, [https://www.mediawiki.org/wiki/Help:OAuth/Errors#E007 E007]</span>',
	'mwoauthserver-invalid-request-token' => 'אסימון לא חוקי בבקשתך.',
	'mwoauthserver-invalid-user' => 'כדי להשתמש ביישומים מחוברים באתר הזה, צריך להיות לך חשבון בכל המיזמים. כאשר יהיה לך חשבון בכל המיזמים, אפשר לנסות לחבר את "$1" שוב.

<span class="plainlinks mw-mwoautherror-details">דרושה כניסה מאוחדת, [https://www.mediawiki.org/wiki/Help:OAuth/Errors#E008 E008]</span>',
	'mwoauth-invalid-authorization-title' => 'שגיאת אימות OAuth',
	'mwoauth-invalid-authorization' => 'כותרות האישור בבקשה שלך אינן תקניות: $1',
	'mwoauth-invalid-authorization-wrong-wiki' => 'כותרות האישור בבקשה שלך אינן תקינות עבור $1',
	'mwoauth-invalid-authorization-invalid-user' => 'כותרות האישור בבקשה שלך הן עבור משתמש שאינו קיים כאן',
	'mwoauth-invalid-authorization-wrong-user' => 'כותרות האישור בבקשה שלך מיועדות למשתמש אחר',
	'mwoauth-invalid-authorization-not-approved' => 'נראה שהיישום שניסית לחבר מוגדר באופן שגוי. נא ליצור קשר עם היוצר של $1 כדי לקבל עזרה.',
	'mwoauth-invalid-authorization-blocked-user' => 'כותרות האישור בבקשה שלך הן עבור משתמש חסום',
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
	'mwoauth-error' => 'שגיאה בחיבור היישום',
	'mwoauth-grants-heading' => 'הרשאות מבוקשות:',
	'mwoauth-grants-nogrants' => 'היישום לא ביקש שום הרשאות.',
	'mwoauth-acceptance-cancelled' => 'בחרת לא לאפשר ל{{GRAMMAR:תחילית|$1}} לגשת לחשבון שלך. $1 לא יעבוד אלא אם כן תיתן לו גישה. ניתן לחזור על $1 או [[Special:OAuthManageMyGrants|לנהל]] את היישומים המחוברים שלך.',
	'mwoauth-grant-group-page-interaction' => 'פעילות בדפים',
	'mwoauth-grant-group-file-interaction' => 'פעילות במדיה',
	'mwoauth-grant-group-watchlist-interaction' => 'פעילות ברשימת מעקב',
	'mwoauth-grant-group-email' => 'שליחת דוא"ל',
	'mwoauth-grant-group-high-volume' => 'ביצוי פעולות מרובות',
	'mwoauth-grant-group-customization' => 'התאמה אישית והעדפות',
	'mwoauth-grant-group-administration' => 'ביצוע פעולות ניהול',
	'mwoauth-grant-group-other' => 'פעילות שונה',
	'mwoauth-grant-blockusers' => 'חסימה ושחרור משתמשים',
	'mwoauth-grant-createaccount' => 'יצירת חשבונות',
	'mwoauth-grant-createeditmovepage' => 'יצירה, עריכה והעברת דפים.',
	'mwoauth-grant-delete' => 'מחיקת דפים, גרסאות ויומנים',
	'mwoauth-grant-editinterface' => 'עריכת שם מדיה-ויקי ומשתמש CSS/JS',
	'mwoauth-grant-editmycssjs' => 'עריכת CSS/JS של המשתמש שלך',
	'mwoauth-grant-editmyoptions' => 'עריכת ההעדפות שלך',
	'mwoauth-grant-editmywatchlist' => 'עריכת רשימת המעקב שלך',
	'mwoauth-grant-editpage' => 'עריכת דפים קיימים',
	'mwoauth-grant-editprotected' => 'עריכת דפים מוגנים',
	'mwoauth-grant-highvolume' => 'ביצוע עריכות מרובות',
	'mwoauth-grant-oversight' => 'החבאת משתמשים והעלמת גרסאות',
	'mwoauth-grant-patrol' => 'ניטור שינויים לדפים',
	'mwoauth-grant-protect' => 'הפעלת הגנה והסרת הגנה מדפים',
	'mwoauth-grant-rollback' => 'שחזור שינויים בדפים',
	'mwoauth-grant-sendemail' => 'שליחת דואר אלקטרוני למשתמשים אחרים',
	'mwoauth-grant-uploadeditmovefile' => 'העלאת קבצים, החלפת קבצים, והעברתם.',
	'mwoauth-grant-uploadfile' => 'העלאת קבצים חדשים',
	'mwoauth-grant-useoauth' => 'הרשאות בסיסיות',
	'mwoauth-grant-viewdeleted' => 'צפייה במידע שנמחק',
	'mwoauth-grant-viewmywatchlist' => 'צפייה ברשימת מעקב',
	'mwoauth-oauth-exception' => 'אירעה שגיאה בפרוטוקול OAuth:$1',
	'mwoauth-callback-not-oob' => 'הפרמטר oauth_callback צריך להיות מוגדר והערך שלו חייב להיות "oob" (תלוי־רישיות)',
	'right-mwoauthproposeconsumer' => 'הצעת צרכני OAuth חדשים',
	'right-mwoauthupdateownconsumer' => 'עדכון צרכני OAuth שבשליטת המשתמש עצמו',
	'right-mwoauthmanageconsumer' => 'ניהול צרכני OAuth',
	'right-mwoauthsuppress' => 'העלמת צרכני OAuth',
	'right-mwoauthviewsuppressed' => 'הצגת צרכני OAuth מועלמים',
	'right-mwoauthviewprivate' => 'הצגת נתוני OAuth פרטיים',
	'right-mwoauthmanagemygrants' => 'ניהול זיכיונות OAuth',
	'action-mwoauthmanageconsumer' => 'לנהול צרכני OAuth',
	'action-mwoauthmanagemygrants' => 'לנהל את זיכיונות ה־OAuth שלך',
	'action-mwoauthproposeconsumer' => 'להציע צרכני OAuth חדשים',
	'action-mwoauthupdateownconsumer' => 'לעדכן צרכני OAuth שבשליטתך',
	'action-mwoauthviewsuppressed' => 'להציג צרכני OAuth מועלמים',
	'mwoauth-listgrantrights-summary' => 'להלן רשימת זיכיונות של OAuth, עם הגישות להרשאות משתמש שמשויכות אליהן. משתמשים יכולים לאשר ליישומים להשתמש בחשבון שלהם, אבל עם הרשאות מוגבלות בהתאם לזיכיון שהשמשתמש נתן ליישום. יישום שפועל בשמו של המשתמש אינו יכול להשתמש בהרשאות שאין למשתמש.
ייתכן שיש [[{{MediaWiki:Listgrouprights-helppage}}|מידע נוסף]] על הרשאות פרטניות.',
	'mwoauth-listgrants-grant' => 'זיכיון',
	'mwoauth-listgrants-rights' => 'הרשאות',
);

/** Croatian (hrvatski)
 * @author MaGa
 */
$messages['hr'] = array(
	'mwoauth-prefs-managegrants' => 'Povezane aplikacije',
	'mwoauth-prefs-managegrantslink' => 'Upravljaj s ukupno $1 {{PLURAL:$1|aplikacijom|aplikacije|aplikacija}}',
);

/** Interlingua (interlingua)
 * @author McDutchie
 */
$messages['ia'] = array(
	'mwoauth-desc' => 'Permitte usar OAuth 1.0a pro autorisation via API',
	'mwoauth-missing-field' => 'Manca un valor pro le campo "$1"',
	'mwoauth-invalid-field' => 'Valor non valide fornite pro le campo "$1"',
	'mwoauth-field-hidden' => '(iste information es celate)',
	'mwoauth-field-private' => '(iste information es private)',
	'mwoauth-grant-generic' => 'gruppo de derectos "$1"',
	'mwoauth-prefs-managegrants' => 'Applicationes connectite:',
	'mwoauth-prefs-managegrantslink' => 'Gerer $1 {{PLURAL:$1|application|applicationes}} connectite',
	'mwoauth-consumer-key' => 'Clave de consumitor:',
	'mwoauth-consumer-name' => 'Nomine del application:',
	'mwoauth-consumer-version' => 'Version del consumitor:',
	'mwoauth-consumer-user' => 'Editor:',
	'mwoauth-consumer-stage' => 'Stato actual:',
	'mwoauth-consumer-email' => 'Adresse de e-mail de contacto:',
	'mwoauth-consumer-description' => 'Description del application:',
	'mwoauth-consumer-callbackurl' => 'URL de retorno pro OAuth:',
	'mwoauth-consumer-grantsneeded' => 'Concessiones applicabile:',
	'mwoauth-consumer-required-grant' => 'Applicabile al consumitor',
	'mwoauth-consumer-wiki' => 'Projecto applicabile:',
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
	'oauthconsumerregistration' => 'Registration de consumitor OAuth',
	'mwoauthconsumerregistration-navigation' => 'Navigation:',
	'mwoauthconsumerregistration-propose' => 'Proponer nove consumitor',
	'mwoauthconsumerregistration-list' => 'Mi lista de consumitores',
	'mwoauthconsumerregistration-main' => 'Principal',
	'mwoauthconsumerregistration-propose-text' => 'Programmatores deberea usar le formulario hic infra pro proponer un nove consumitor OAuth (vide le [//www.mediawiki.org/wiki/Extension:OAuth documentation del extension] pro plus detalios). Post haber submittite iste formulario, tu recipera un indicio le qual tu application usara pro identificar se pro MediaWiki. Un administrator de OAuth debera approbar tu application ante que illo pote esser autorisate per altere usatores.

Ecce alcun recommendationes e remarcas:
* Concede le minus derectos possibile. Evita concessiones que non es necessari in iste momento.
* Le versiones es in forma "major.minor.edition" (le ultime duo es optional) e augmenta a mesura que cambiamentos de concession es necessari.
* Forni un clave RSA (in formato PEM) si possibile; alteremente un indicio secrete (e minus secur) te essera assignate.
* Usa le campo de restrictiones JSON pro limitar le accesso de iste consumitor al adresses IP in iste rangos CIDR.
* Tu pote usar un ID de projecto pro limitar le consumitor a un singule projecto in iste sito (usa "*" pro tote le projectos).
* Le adresse de e-mail fornite debe esser identic a illo de tu conto (que debe esser confirmate).',
	'mwoauthconsumerregistration-update-text' => 'Le formulario sequente permitte actualisar aspectos de un consumitor OAuth que tu controla.

Tote le valores hic superscribera omne previe valores. Non lassa campos vacue si tu non ha le intention de rader iste valores.',
	'mwoauthconsumerregistration-maintext' => 'Iste pagina es pro proponer e actualisar applicationes de consumitor OAuth (vide http://oauth.net) in le base de registration de iste sito.

Ab hic, tu pote [[Special:OAuthConsumerRegistration/propose|proponer un nove consumitor]] o [[Special:OAuthConsumerRegistration/list|gerer tu consumitores existente]].',
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
	'oauthmanageconsumers' => 'Gerer consumitores OAuth',
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
	'mwoauthmanageconsumers-viewing' => 'Le {{GENDER:$1|usator}} "$1" actualmente visualisa iste consumitor',
	'mwoauthmanageconsumers-success-approved' => 'Le requesta ha essite approbate.',
	'mwoauthmanageconsumers-success-rejected' => 'Le requesta ha essite rejectate.',
	'mwoauthmanageconsumers-success-disabled' => 'Le consumitor ha essite disactivate.',
	'mwoauthmanageconsumers-success-reanable' => 'Le consumitor ha essite reactivate.',
	'oauthmanagemygrants' => 'Gerer applicationes connectite',
	'mwoauthmanagemygrants-navigation' => 'Navigation:',
	'mwoauthmanagemygrants-showlist' => 'Lista de applicationes connectite',
	'mwoauthmanagemygrants-none' => 'Nulle application es connectite a tu conto.',
	'mwoauthmanagemygrants-user' => 'Editor:',
	'mwoauthmanagemygrants-description' => 'Description',
	'mwoauthmanagemygrants-wikiallowed' => 'Permittite in projecto:',
	'mwoauthmanagemygrants-grants' => 'Concessiones applicabile',
	'mwoauthmanagemygrants-grantsallowed' => 'Concessiones permittite',
	'mwoauthmanagemygrants-applicablegrantsallowed' => 'Concessiones applicabile permittite:',
	'mwoauthmanagemygrants-review' => 'gerer accesso',
	'mwoauthmanagemygrants-grantaccept' => 'Concedite',
	'mwoauthmanagemygrants-confirm-legend' => 'Gerer application connectite',
	'mwoauthmanagemygrants-update' => 'Actualisar concessiones',
	'mwoauthmanagemygrants-renounce' => 'Disautorisar',
	'mwoauthmanagemygrants-action' => 'Cambiar stato:',
	'mwoauthmanagemygrants-confirm-submit' => 'Actualisar le stato de indicio de accesso',
	'mwoauthmanagemygrants-success-update' => 'Le indicio de accesso pro iste consumitor ha essite actualisate.',
	'mwoauthmanagemygrants-success-renounce' => 'Le indicio de accesso pro iste consumitor ha essite delite.',
	'logentry-mwoauthconsumer-propose' => '$1 $2proponeva un consumitor OAuth (clave de consumitor $4)',
	'logentry-mwoauthconsumer-update' => '$1 {{GENDER:$2|actualisava}} un consumitor OAuth (clave de consumitor $4)',
	'logentry-mwoauthconsumer-approve' => '$1 {{GENDER:$2|approbava}} un consumitor OAuth per $3 (clave de consumitor $4)',
	'logentry-mwoauthconsumer-reject' => '$1 {{GENDER:$2|rejectava}} un consumitor OAuth per $3 (clave de consumitor $4)',
	'logentry-mwoauthconsumer-disable' => '$1 disactivava un consumitor OAuth per $3 (clave de consumitor $4)',
	'logentry-mwoauthconsumer-reenable' => '$1 {{GENDER:$2|reactivava}} un consumitor OAuth per $3 (clave de consumitor $4)',
	'mwoauthconsumer-consumer-logpage' => 'Registro de consumitores OAuth',
	'mwoauthconsumer-consumer-logpagetext' => 'Registro de approbation, rejection e disactivation de consumitores OAuth registrate.',
	'mwoauthdatastore-access-token-not-found' => 'Nulle concession approbate ha essite trovate pro iste indicio de autorisation.',
	'mwoauthdatastore-request-token-not-found' => 'Un error ha occurrite durante le tentativa de connecter iste application.
Retorna e reproba connecter tu conto, o contacta le autor del application.

<span class="plainlinks mw-mwoautherror-details">Indicio OAuth non trovate, [https://www.mediawiki.org/wiki/Help:OAuth/Errors#E004 E004]</span>',
	'mwoauthdatastore-bad-token' => 'Nulle indicio ha essite trovate que corresponde a tu requesta.',
	'mwoauthdatastore-bad-verifier' => 'Le codice de verification fornite non es valide.',
	'mwoauthdatastore-invalid-token-type' => 'Le typo de indicio requestate non es valide.',
	'mwoauthgrants-general-error' => 'Il habeva un error in tu requesta OAuth.',
	'mwoauthserver-bad-consumer' => '"$1" non plus es approbate como application connectite. [$2 Contacta] le autor del application pro adjuta.

<span class="plainlinks mw-mwoautherror-details">Application OAuth connectite non approbate, [https://www.mediawiki.org/wiki/Help:OAuth/Errors#E005 E005]</span>',
	'mwoauthserver-insufficient-rights' => 'Tu conto non es autorisate a connecter applicationes. Contacta le administrator del sito pro saper proque.

<span class="plainlinks mw-mwoautherror-details">Derectos de usator OAuth insufficiente, [https://www.mediawiki.org/wiki/Help:OAuth/Errors#E007 E007]</span>',
	'mwoauthserver-invalid-request-token' => 'Il ha un indicio non valide in tu requesta.',
	'mwoauth-invalid-authorization-title' => 'Error de autorisation OAuth',
	'mwoauth-invalid-authorization' => 'Le capites de autorisation in tu requesta non es valide: $1',
	'mwoauth-invalid-authorization-wrong-wiki' => 'Le capites de autorisation in tu requesta non es valide pro $1',
	'mwoauth-invalid-authorization-invalid-user' => 'Le capites de autorisation in tu requesta es pro un usator que non existe hic',
	'mwoauth-invalid-authorization-wrong-user' => 'Le capites de autorisation in tu requesta es pro un altere usator',
	'mwoauth-invalid-authorization-not-approved' => 'Le application que tu tenta connecter sembla mal configurate. Contacta le autor de "$1" pro adjuta.',
	'mwoauth-form-button-approve' => 'Autorisar',
	'mwoauth-grants-heading' => 'Permissiones requestate:',
	'mwoauth-grants-nogrants' => 'Le application non ha requestate alcun permission.',
	'mwoauth-grant-blockusers' => 'Blocar e disblocar usatores',
	'mwoauth-grant-createeditmovepage' => 'Crear, modificar e renominar paginas',
	'mwoauth-grant-delete' => 'Deler paginas, versiones e entratas de registro',
	'mwoauth-grant-editinterface' => 'Modificar le spatio de nomines MediaWiki e le CSS/JS de usatores',
	'mwoauth-grant-editmycssjs' => 'Modificar le CSS/JS del proprie usator',
	'mwoauth-grant-editmywatchlist' => 'Modificar le proprie observatorio',
	'mwoauth-grant-editpage' => 'Modificar paginas existente',
	'mwoauth-grant-editprotected' => 'Modificar paginas protegite',
	'mwoauth-grant-highvolume' => 'Modification in massa',
	'mwoauth-grant-oversight' => 'Celar usatores e supprimer versiones',
	'mwoauth-grant-patrol' => 'Patruliar cambiamentos in paginas',
	'mwoauth-grant-protect' => 'Proteger e disproteger paginas',
	'mwoauth-grant-rollback' => 'Revocar cambiamentos in paginas',
	'mwoauth-grant-sendemail' => 'Inviar e-mail a altere usatores',
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
	'mwoauth-desc' => "Consente l'utilizzo di OAuth 1.0a per le API di autorizzazione",
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
	'mwoauth-consumer-wiki' => 'Progetti applicabili:',
	'mwoauth-consumer-wiki-thiswiki' => 'Progetto attuale ($1)',
	'mwoauth-consumer-wiki-other' => 'Progetto specifico',
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
	'mwoauth-invalid-access-wrongwiki' => 'Il cliente può essere utilizzato solo nel progetto "$1".',
	'mwoauth-consumer-conflict' => 'Qualcuno ha cambiato gli attributi di questo cliente, come si visto. Per favore riprova. Si consiglia di controllare il registro delle modifiche.',
	'mwoauth-consumer-grantshelp' => "Ogni concessione dà accesso ai diritti elencati per cui l'utenza già dispone. Vedi la [[Special:OAuth/grants|tabella delle assegnazioni]] per ulteriori informazioni.",
	'mwoauth-consumer-stage-proposed' => 'proposto',
	'mwoauth-consumer-stage-rejected' => 'respinto',
	'mwoauth-consumer-stage-expired' => 'scaduto',
	'mwoauth-consumer-stage-approved' => 'approvato',
	'mwoauth-consumer-stage-disabled' => 'disabilitato',
	'mwoauth-consumer-stage-suppressed' => 'soppresso',
	'oauthconsumerregistration' => 'Registrazione cliente OAuth',
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
* è possibile utilizzare un ID progetto per limitare il cliente ad un singolo progetto su questo sito (usa \"*\" per tutti i progetti)
* l'indirizzo email fornito deve corrispondere a quello della tua utenza (che deve essere confermato).",
	'mwoauthconsumerregistration-update-text' => 'Utilizza il modulo qui sotto per aggiornare gli aspetti di un cliente OAuth che controlli.

I valori qui sovrascriveranno tutti quelli precedenti. Non lasciarli in bianco se non hai intenzione di cancellare quei valori.',
	'mwoauthconsumerregistration-maintext' => "Questa pagina è per consentire agli sviluppatori di proporre e l'aggiornare le applicazioni OAuth registrate in questo sito.

Da qui, è possibile:
* [[Special:OAuthConsumerRegistration/propose|richiedere un token per un nuovo cliente]]
* [[Special:OAuthConsumerRegistration/list|gestire i tuoi clienti esistenti]].

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
	'oauthmanageconsumers' => 'Gestione clienti OAuth',
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
	'mwoauthmanageconsumers-viewing' => '{{GENDER:$1|L\'utente}} "$1" sta attualmente vedendo questo cliente',
	'mwoauthmanageconsumers-success-approved' => 'La richiesta è stata approvata.',
	'mwoauthmanageconsumers-success-rejected' => 'La richiesta è stata respinta.',
	'mwoauthmanageconsumers-success-disabled' => 'Il cliente è stato disabilitato.',
	'mwoauthmanageconsumers-success-reanable' => 'Il cliente è stato riabilitato.',
	'mwoauthmanageconsumers-search-name' => 'clienti con questo nome',
	'mwoauthmanageconsumers-search-publisher' => 'clienti di questo utente',
	'oauthlistconsumers' => 'Elenco applicazioni OAuth',
	'mwoauthlistconsumers-legend' => 'Naviga applicazioni OAuth',
	'mwoauthlistconsumers-view' => 'dettagli',
	'mwoauthlistconsumers-none' => 'Nessuna applicazione trovata che soddisfa questo criterio.',
	'mwoauthlistconsumers-name' => 'Nome applicazione',
	'mwoauthlistconsumers-version' => 'Versione cliente',
	'mwoauthlistconsumers-user' => 'Editore',
	'mwoauthlistconsumers-description' => 'Descrizione',
	'mwoauthlistconsumers-wiki' => 'Progetti applicabili',
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
	'oauthmanagemygrants' => 'Gestione applicazioni connesse',
	'mwoauthmanagemygrants-text' => 'Questa pagina elenca tutte le applicazioni che possono utilizzare la tua utenza. Per tali applicazioni, l\'ambito del loro accesso è limitata dalle autorizzazioni concesse all\'applicazione quando è stata autorizzata ad agire per vostro conto. Se autorizzi separatamente un\'applicazione all\'accesso per vostro conto su diversi progetti "fratelli", poi vedrai configurazioni separate per ognuno dei progetti sotto.

Le applicazioni connesse accedono alla tua utenza usando il protocollo OAuth. <span class="plainlinks">([https://www.mediawiki.org/wiki/Special:MyLanguage/Help:OAuth Ulteriori informazioni sulle applicazioni connesse])</span>',
	'mwoauthmanagemygrants-notloggedin' => "Devi effettuare l'accesso per accedere a questa pagina.",
	'mwoauthmanagemygrants-navigation' => 'Navigazione:',
	'mwoauthmanagemygrants-showlist' => 'Elenco applicazioni connesse',
	'mwoauthmanagemygrants-none' => 'Non ci sono applicazioni collegate alla tua utenza.',
	'mwoauthmanagemygrants-user' => 'Editore:',
	'mwoauthmanagemygrants-description' => 'Descrizione',
	'mwoauthmanagemygrants-wikiallowed' => 'Consentito su progetto:',
	'mwoauthmanagemygrants-grants' => 'Assegnazioni applicabili',
	'mwoauthmanagemygrants-grantsallowed' => 'Diritti consentiti',
	'mwoauthmanagemygrants-applicablegrantsallowed' => 'Assegnazioni applicabili consentite:',
	'mwoauthmanagemygrants-review' => 'gestisci accesso',
	'mwoauthmanagemygrants-revoke' => "revoca l'accesso",
	'mwoauthmanagemygrants-grantaccept' => 'Assegnazioni',
	'mwoauthmanagemygrants-update-text' => "Utilizza il seguente modulo per modificare le autorizzazioni concesse a un'applicazione di agire per vostro conto.",
	'mwoauthmanagemygrants-revoke-text' => "Utilizza il seguente modulo per revocare le autorizzazioni concesse a un'applicazione di agire per vostro conto.",
	'mwoauthmanagemygrants-confirm-legend' => 'Gestione applicazione connessa',
	'mwoauthmanagemygrants-update' => 'Aggiorna le assegnazioni',
	'mwoauthmanagemygrants-renounce' => "Rimuovi l'autorizzazione",
	'mwoauthmanagemygrants-action' => 'Modifica stato:',
	'mwoauthmanagemygrants-confirm-submit' => 'Aggiorna lo stato del token di accesso',
	'mwoauthmanagemygrants-success-update' => 'Il token di accesso per questo cliente è stato aggiornato.',
	'mwoauthmanagemygrants-success-renounce' => 'Il token di accesso per questo cliente è stato cancellato.',
	'mwoauthmanagemygrants-useoauth-tooltip' => "Perché non posso aggiornare questa assegnazione? Questa concessione offre le autorizzazioni di base per l'applicazione connessa che sono richieste per funzionare correttamente. Se non si desidera che questa applicazione connessa abbia questi diritti, si dovrebbe revocare l'accesso all'applicazione.",
	'logentry-mwoauthconsumer-propose' => '$1 {{GENDER:$2|ha proposto}} un cliente OAuth (chiave cliente $4)',
	'logentry-mwoauthconsumer-update' => '$1 {{GENDER:$2|ha aggiornato}} un cliente OAuth (chiave cliente $4)',
	'logentry-mwoauthconsumer-approve' => '$1 {{GENDER:$2|ha approvato}} un cliente OAuth di $3 (chiave cliente $4)',
	'logentry-mwoauthconsumer-reject' => '$1 {{GENDER:$2|ha respinto}} un cliente OAuth di $3 (chiave cliente $4)',
	'logentry-mwoauthconsumer-disable' => '$1 {{GENDER:$2|ha disabilitato}} un cliente OAuth di $3 (chiave cliente $4)',
	'logentry-mwoauthconsumer-reenable' => '$1 {{GENDER:$2|ha riabilitato}} un cliente OAuth di $3 (chiave cliente $4)',
	'mwoauthconsumer-consumer-logpage' => 'Clienti OAuth',
	'mwoauthconsumer-consumer-logpagetext' => 'Registro dei clienti OAuth approvati, respinti o disabilitati.',
	'mwoauth-bad-request-missing-params' => 'Spiacenti, qualcosa è andato storto nella configurazione della connessione per questa applicazione. <span class="plainlinks">[https://www.mediawiki.org/wiki/Special:MyLanguage/Help:OAuth Contatta il supporto]</span> per avere aiuto su come correggere.

<span class="plainlinks mw-mwoautherror-details">OAuth parametri mancanti, [https://www.mediawiki.org/wiki/Help:OAuth/Errors#E001 E001]</span>',
	'mwoauth-bad-request-invalid-action' => 'Spiacenti, qualcosa è andato storto, dovresti contattare l\'autore dell\'applicazione per avere aiuto su come correggere.

<span class="plainlinks mw-mwoautherror-details">URL sconosciuto, [https://www.mediawiki.org/wiki/Help:OAuth/Errors#E002 E002]</span>',
	'mwoauth-bad-request-invalid-action-contact' => 'Spiacenti, qualcosa è andato storto, dovresti [$1 contattare] l\'autore dell\'applicazione per avere aiuto su come correggere.

<span class="plainlinks mw-mwoautherror-details">URL sconosciuto, [https://www.mediawiki.org/wiki/Help:OAuth/Errors#E003 E003]</span>',
	'mwoauthdatastore-access-token-not-found' => 'Non è stata trovata alcuna assegnazione approvata per il token di autorizzazione.',
	'mwoauthdatastore-request-token-not-found' => 'Spiacenti, qualcosa è andato storto, durante la connessione a questa applicazione. Torna indietro e prova a connetterti nuovamente, o contattare l\'autore dell\'applicazione.

<span class="plainlinks mw-mwoautherror-details">Token OAuth non trovato, [https://www.mediawiki.org/wiki/Help:OAuth/Errors#E004 E004]</span>',
	'mwoauthdatastore-bad-token' => 'Non è stato trovato alcun token che corrisponde alla tua richiesta.',
	'mwoauthdatastore-bad-verifier' => 'Il codice di verifica fornito non è valido.',
	'mwoauthdatastore-invalid-token-type' => 'Il tipo di token richiesto non è valido.',
	'mwoauthgrants-general-error' => "C'è un errore nella tua richiesta OAuth.",
	'mwoauthserver-bad-consumer' => '"$1" non è più un\'applicazione approvata, dovresti [$2 contattare] l\'autore dell\'applicazione per avere aiuto.

<span class="plainlinks mw-mwoautherror-details">Applicazione non approvata, [https://www.mediawiki.org/wiki/Help:OAuth/Errors#E005 E005]</span>',
	'mwoauthserver-bad-consumer-key' => 'Spiacenti, qualcosa è andato storto, durante la connessione a questa applicazione.

<span class="plainlinks mw-mwoautherror-details">Chiave OAuth sconosciuta, [https://www.mediawiki.org/wiki/Help:OAuth/Errors#E006 E006]</span>',
	'mwoauthserver-insufficient-rights' => 'Non è consentito alla tua utenza di usare le applicazioni, contatta l\'amministratore del sito per capirne il motivo.

<span class="plainlinks mw-mwoautherror-details">Diritti utente OAuth insufficienti, [https://www.mediawiki.org/wiki/Help:OAuth/Errors#E007 E007]</span>',
	'mwoauthserver-invalid-request-token' => 'Token non valido nella tua richiesta.',
	'mwoauthserver-invalid-user' => 'Per usare le applicazioni connesse su questo sito, è obbligatoria un\'utenza unificata fra tutti i progetti. Una volta che avrai l\'utenza su tutti i progetti, prova a connetterti a "$1" nuovamente.

<span class="plainlinks mw-mwoautherror-details">Necessaria l\'utenza globale, [https://www.mediawiki.org/wiki/Help:OAuth/Errors#E008 E008]</span>',
	'mwoauth-invalid-authorization-title' => 'Errore autorizzazione OAuth',
	'mwoauth-invalid-authorization' => "L'intestazione dell'autorizzazione nella tua richiesta non è valida: $1",
	'mwoauth-invalid-authorization-wrong-wiki' => "L'intestazione dell'autorizzazione nella tua richiesta non è valida per $1",
	'mwoauth-invalid-authorization-invalid-user' => "L'intestazione dell'autorizzazione nella tua richiesta si riferisce ad un utente che non esiste qui",
	'mwoauth-invalid-authorization-wrong-user' => "L'intestazione dell'autorizzazione nella tua richiesta si riferisce ad un altro utente",
	'mwoauth-invalid-authorization-not-approved' => 'L\'applicazione a cui stai tentando di connetterti sembra essere impostata in modo errato. Contatta l\'autore di "$1" per avere aiuto.',
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
	'mwoauth-error' => "Errore di connessione dell'applicazione",
	'mwoauth-grants-heading' => 'Autorizzazioni richieste:',
	'mwoauth-grants-nogrants' => "L'applicazione non ha richiesto alcuna autorizzazione.",
	'mwoauth-acceptance-cancelled' => 'Hai scelto di non consentire a "$1" ad accedere alla tua utenza. "$1" non funzionerà a meno che non gli permetti l\'accesso. Puoi tornare a "$1" o nella [[Special:OAuthManageMyGrants|gestione]] delle tue applicazioni connesse.',
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
	'mwoauth-grant-editmyoptions' => 'Modifica le proprie preferenze utente',
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
	'mwoauth-listgrantrights-summary' => "Di seguito è riportato un elenco delle concessioni OAuth, con i loro diritti utente associati. Gli utenti possono autorizzare le applicazioni a utilizzare la propria utenza, ma con autorizzazioni limitate in base alle assegnazioni che l'utente ha dato all'applicazione. Tuttavia, un'applicazione che agisce per conto di un utente non può effettivamente utilizzare i diritti di cui l'utente non dispone.
Potresti trovare [[{{MediaWiki:Listgrouprights-helppage}}|ulteriori informazioni]] sui diritti individuali.",
	'mwoauth-listgrants-grant' => 'Assegnazioni',
	'mwoauth-listgrants-rights' => 'Diritti',
);

/** Japanese (日本語)
 * @author Fryed-peach
 * @author Shirayuki
 * @author Whym
 */
$messages['ja'] = array(
	'oauth' => 'OAuth',
	'mwoauth-desc' => 'API 認証に OAuth 1.0a を使用できるようにする',
	'mwoauth-verified' => "このアプリケーションがあなたに代わって MediaWiki にアクセスすることが許可されました。

この手続きを完了するには次の検証トークンをアプリケーションに提供してください: '''$1'''",
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
	'mwoauth-consumer-wiki-thiswiki' => '現在のプロジェクト ($1)',
	'mwoauth-consumer-wiki-other' => '特定のプロジェクト',
	'mwoauth-consumer-restrictions' => '使用制限:',
	'mwoauth-consumer-restrictions-json' => '使用制限 (JSON):',
	'mwoauth-consumer-rsakey' => '公開 RSA キー:',
	'mwoauth-consumer-secretkey' => 'コンシューマー秘密トークン:',
	'mwoauth-consumer-accesstoken' => 'アクセス トークン:',
	'mwoauth-consumer-reason' => '理由:',
	'mwoauth-consumer-email-unconfirmed' => 'アカウントのメールアドレスがまだ確認されていません。',
	'mwoauth-consumer-email-mismatched' => '指定したメールアドレスは、アカウントのものと一致しません。',
	'mwoauth-consumer-alreadyexists' => 'この名前/バージョン/発行者の組み合わせを持つコンシューマーは既に存在します',
	'mwoauth-consumer-not-proposed' => 'このコンシューマーは現在提案されていません。',
	'mwoauth-consumer-not-disabled' => 'このコンシューマーは現在無効化されていません',
	'mwoauth-consumer-not-approved' => 'このコンシューマーは承認されていません (無効化された可能性があります)。',
	'mwoauth-missing-consumer-key' => 'コンシューマー キーを指定していません。',
	'mwoauth-invalid-consumer-key' => '指定したキーのコンシューマーは存在しません。',
	'mwoauth-invalid-access-token' => '指定したキーのアクセス トークンは存在しません。',
	'mwoauth-invalid-access-wrongwiki' => 'プロジェクト「$1」のみで使用できるコンシューマーです。',
	'mwoauth-consumer-stage-proposed' => '提案中',
	'mwoauth-consumer-stage-rejected' => '却下済み',
	'mwoauth-consumer-stage-expired' => '期限切れ',
	'mwoauth-consumer-stage-approved' => '承認済',
	'mwoauth-consumer-stage-disabled' => '無効',
	'oauthconsumerregistration' => 'OAuth コンシューマー登録',
	'mwoauthconsumerregistration-notloggedin' => 'このページにアクセスするにはログインしてください。',
	'mwoauthconsumerregistration-navigation' => 'ナビゲーション:',
	'mwoauthconsumerregistration-propose' => '新しいコンシューマーの提案',
	'mwoauthconsumerregistration-list' => '自分のコンシューマー一覧',
	'mwoauthconsumerregistration-main' => 'メイン',
	'mwoauthconsumerregistration-update-legend' => 'OAuth コンシューマー アプリケーションの更新',
	'mwoauthconsumerregistration-update-submit' => 'コンシューマーを更新',
	'mwoauthconsumerregistration-none' => 'あなたが制御できる Oauth コンシューマーはありません。',
	'mwoauthconsumerregistration-name' => 'コンシューマー',
	'mwoauthconsumerregistration-user' => '発行者',
	'mwoauthconsumerregistration-description' => '説明',
	'mwoauthconsumerregistration-email' => '連絡先メールアドレス',
	'mwoauthconsumerregistration-consumerkey' => 'コンシューマー キー',
	'mwoauthconsumerregistration-stage' => '状態',
	'mwoauthconsumerregistration-lastchange' => '最新の変更',
	'mwoauthconsumerregistration-manage' => '管理',
	'mwoauthconsumerregistration-updated' => 'あなたの OAuth コンシューマー レジストリを更新しました。',
	'mwoauthconsumerregistration-secretreset' => "「'''$1'''」のコンシューマー秘密トークンを割り当てました。''今後のためこれを記録しておいてください。''",
	'oauthmanageconsumers' => 'OAuthコンシューマー管理', # Fuzzy
	'mwoauthmanageconsumers-notloggedin' => 'このページにアクセスするにはログインしてください。',
	'mwoauthmanageconsumers-type' => 'キュー:',
	'mwoauthmanageconsumers-main' => 'メイン',
	'mwoauthmanageconsumers-queues' => '以下からコンシューマー確認のキューを選択:',
	'mwoauthmanageconsumers-q-proposed' => '提案中のコンシューマー依頼のキュー',
	'mwoauthmanageconsumers-q-rejected' => '却下済みのコンシューマー依頼のキュー',
	'mwoauthmanageconsumers-q-expired' => '期限切れのコンシューマー依頼のキュー',
	'mwoauthmanageconsumers-lists' => '以下からコンシューマーの状態の一覧を選択:',
	'mwoauthmanageconsumers-l-approved' => '承認済みのコンシューマーの一覧',
	'mwoauthmanageconsumers-l-disabled' => '無効化されたコンシューマーの一覧',
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
	'mwoauthmanageconsumers-approve' => '承認済',
	'mwoauthmanageconsumers-reject' => '却下済み',
	'mwoauthmanageconsumers-disable' => '無効',
	'mwoauthmanageconsumers-reenable' => '承認済',
	'mwoauthmanageconsumers-reason' => '理由:',
	'mwoauthmanageconsumers-confirm-submit' => 'コンシューマーの状態を更新',
	'mwoauthmanageconsumers-viewing' => '{{GENDER:$1|利用者}}「$1」が現在このコンシューマーを閲覧中です',
	'mwoauthmanageconsumers-success-approved' => 'リクエストを承認しました。',
	'mwoauthmanageconsumers-success-rejected' => 'リクエストを却下しました。',
	'mwoauthmanageconsumers-success-disabled' => 'コンシューマーを無効にしました。',
	'mwoauthmanageconsumers-success-reanable' => 'コンシューマーを再度有効にしました。',
	'mwoauthmanageconsumers-search-name' => 'この名前のコンシューマー',
	'mwoauthmanageconsumers-search-publisher' => 'この利用者のコンシューマー',
	'oauthlistconsumers' => 'OAuthアプリケーション一覧',
	'mwoauthlistconsumers-legend' => 'OAuth アプリケーションの参照',
	'mwoauthlistconsumers-view' => '詳細',
	'mwoauthlistconsumers-none' => 'この条件に該当するアプリケーションが見つかりません。',
	'mwoauthlistconsumers-name' => 'アプリケーション名',
	'mwoauthlistconsumers-version' => 'コンシューマーのバージョン',
	'mwoauthlistconsumers-user' => '発行者',
	'mwoauthlistconsumers-description' => '説明',
	'mwoauthlistconsumers-callbackurl' => 'OAuth コールバック URL',
	'mwoauthlistconsumers-basicgrantsonly' => '(基本的なアクセスのみ)',
	'mwoauthlistconsumers-status' => '状態',
	'mwoauth-consumer-stage-any' => 'すべて',
	'mwoauthlistconsumers-status-approved' => '承認済',
	'mwoauthlistconsumers-status-disabled' => '無効',
	'mwoauthlistconsumers-status-rejected' => '却下',
	'mwoauthlistconsumers-status-expired' => '期限切れ',
	'oauthmanagemygrants' => '接続済みアプリケーションの管理',
	'mwoauthmanagemygrants-text' => 'このページでは、あなたのアカウントを使用できるアプリケーションをすべて列挙します。各アプリケーションについて、あなたの代わりに実行することを承認した際に許可した範囲に、そのアプリケーションの権限が制限されています。If you separately authorized an application to access different sister projects on your behalf, then you will see separate configuration for each such project below.

Connected applications access your account by using the OAuth protocol. <span class="plainlinks">([https://www.mediawiki.org/wiki/Special:MyLanguage/Help:OAuth Learn more about connected applications])</span>', # Fuzzy
	'mwoauthmanagemygrants-notloggedin' => 'このページにアクセスするにはログインしてください。',
	'mwoauthmanagemygrants-navigation' => 'ナビゲーション:',
	'mwoauthmanagemygrants-showlist' => '接続済みアプリケーション一覧',
	'mwoauthmanagemygrants-none' => 'あなたのアカウントに接続されているアプリケーションはありません。',
	'mwoauthmanagemygrants-user' => '発行者:',
	'mwoauthmanagemygrants-description' => '説明',
	'mwoauthmanagemygrants-review' => 'アクセスを管理',
	'mwoauthmanagemygrants-revoke' => 'アクセスを取り消す',
	'mwoauthmanagemygrants-confirm-legend' => '接続済みアプリケーションの管理',
	'mwoauthmanagemygrants-renounce' => '認証解除',
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
	'mwoauthserver-insufficient-rights' => 'あなたにはこの操作を実行する権限がありません。', # Fuzzy
	'mwoauthserver-invalid-request-token' => 'リクエストに無効なトークンがあります。',
	'mwoauth-invalid-authorization-title' => 'OAuth 認証エラー',
	'mwoauth-form-privacypolicy-link' => '[[{{ns:Project}}:Privacy policy|プライバシー・ポリシー]]',
	'mwoauth-form-button-approve' => '許可',
	'mwoauth-form-button-cancel' => 'キャンセル',
	'mwoauth-error' => 'アプリケーション接続エラー',
	'mwoauth-grant-group-email' => 'メールの送信',
	'mwoauth-grant-group-customization' => 'カスタマイズと個人設定',
	'mwoauth-grant-group-other' => 'その他の活動',
	'mwoauth-grant-blockusers' => '利用者をブロック/ブロック解除',
	'mwoauth-grant-createaccount' => 'アカウントを作成',
	'mwoauth-grant-createeditmovepage' => 'ページを作成/編集/移動',
	'mwoauth-grant-delete' => 'ページ、版、記録項目を削除',
	'mwoauth-grant-editinterface' => 'MediaWiki 名前空間および利用者 CSS/JavaScript を編集',
	'mwoauth-grant-editmycssjs' => '自身の利用者 CSS/JavaScript を編集',
	'mwoauth-grant-editmyoptions' => '自身の個人設定を編集',
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
	'mwoauth-listgrants-rights' => '権限',
	'mwoauth-listgrantrights-right-display' => '$1 <code>($2)</code>',
);

/** Georgian (ქართული)
 * @author David1010
 */
$messages['ka'] = array(
	'mwoauth-prefs-managegrants' => 'დაკავშირებული აპლიკაციები:',
	'mwoauth-prefs-managegrantslink' => '$1 დაკავშირებული {{PLURAL:$1|აპლიკაციის}} მართვა',
	'oauthmanagemygrants' => 'დაკავშირებული აპლიკაციების მართვა',
	'mwoauthmanagemygrants-confirm-legend' => 'დაკავშირებული აპლიკაციის მართვა',
);

/** Korean (한국어)
 * @author Hym411
 * @author Priviet
 * @author 아라
 */
$messages['ko'] = array(
	'mwoauth-desc' => 'OAuth 1.0a API 인증의 사용을 허용',
	'mwoauth-verified' => "프로세스를 완성하기 위해서는 이 증명값을 이 응용프로그램에 입력해야 합니다: '''$1'''",
	'mwoauth-missing-field' => '"$1" 필드에 대한 값이 없습니다',
	'mwoauth-invalid-field' => '"$1" 필드에 제공한 값이 잘못되었습니다',
	'mwoauth-invalid-field-generic' => '제공한 값이 잘못되었습니다',
	'mwoauth-field-hidden' => '(이 정보는 숨겨져 있습니다)',
	'mwoauth-field-private' => '(이 정보는 비공개입니다)',
	'mwoauth-grant-generic' => '"$1" 권한 번들',
	'mwoauth-prefs-managegrants' => '연결된 애플리케이션:',
	'mwoauth-prefs-managegrantslink' => '$1개의 연결된 애플리케이션 관리',
	'mwoauth-consumer-allwikis' => '이 사이트의 모든 프로젝트',
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
	'mwoauth-consumer-wiki' => '적용할 수 있는 프로젝트:',
	'mwoauth-consumer-wiki-thiswiki' => '현재 프로젝트($1)',
	'mwoauth-consumer-wiki-other' => '특정 프로젝트',
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
	'mwoauth-missing-consumer-key' => '컨슈머 키가 입력되지 않습니다.',
	'mwoauth-invalid-consumer-key' => '주어진 키로 된 컨슈머가 존재하지 않습니다.',
	'mwoauth-invalid-access-token' => '주어진 키로 된 접근 토큰이 존재하지 않습니다.',
	'mwoauth-invalid-access-wrongwiki' => '컨슈머는 "$1" 프로젝트에서만 사용할 수 있습니다.',
	'mwoauth-consumer-conflict' => '누군가 당신이 보기 전에 이 고객의 속성을 바꿨습니다. 다시 시도해주세요. 바뀜 기록을 확인하실 수 있습니다.',
	'mwoauth-consumer-grantshelp' => '각각 부여된 값은 목록에서 사용자 계정을 이미 갖고 있는 사용자 권한에 접근할 수 있는 권한을 줍니다. [[Special:OAuth/grants|table of grants]]에서 더 자세한 정보를 얻을 수 있습니다.',
	'mwoauth-consumer-stage-proposed' => '제안됨',
	'mwoauth-consumer-stage-rejected' => '거부됨',
	'mwoauth-consumer-stage-expired' => '만료됨',
	'mwoauth-consumer-stage-approved' => '승인됨',
	'mwoauth-consumer-stage-disabled' => '비활성화됨',
	'mwoauth-consumer-stage-suppressed' => '억제됨',
	'oauthconsumerregistration' => 'OAuth 컨슈머 등록',
	'mwoauthconsumerregistration-notloggedin' => '이 페이지에 접근하려면 로그인해야 합니다.',
	'mwoauthconsumerregistration-navigation' => '둘러보기:',
	'mwoauthconsumerregistration-propose' => '새 컨슈머 제안',
	'mwoauthconsumerregistration-list' => '내 컨슈머 목록',
	'mwoauthconsumerregistration-main' => '주요',
	'mwoauthconsumerregistration-propose-text' => '개발자는 다음 양식을 사용하여 새로운 OAuth 고객을 제안해야합니다.(잣한 정보는 [//www.mediawiki.org/wiki/Extension:OAuth 확장 기능 문서]를 참조하세요) 이 양식을 제출하고 나면 당신의 지원서가 미디어위키에서 식별될 수 있도록 토큰을 받게 됩니다. OAuth 관리자는 다른 사용자가권한을 받기 전에 지원서를 승인해야 합니다.

몇 가지 추천 사항과 제안:
* 부여된 권한을 가능한 사용하도록 해주세요.
* 버전은 "major.minor.release" 양식이며(마지막 2가지는 선택 사항) 권한 변경이 필요하므로 증가시켜주세요.
* 가능하면 공개 RSA 키(PEM 포맷)을 입력해주세요; 아니면 비밀 토큰(덜 안전함)을 사용해야 합니다.
* JSON 제한 필드를 사용하여 고객이 CIDR 범위에서 
IP 주소에 접근하는 것을 제한해주세요.
* 프로젝트 ID를 사용하여 고객이 이 사이트의 하나의 프로젝트에 접근하는 것 제한합니다.
* 제공된 이메일 주소가 당신의 계정와 일치해야 합니다.(확인받아야 합니다)',
	'mwoauthconsumerregistration-update-text' => '아래의 양식을 사용하여 당신이 관리하는 OAuth 고객의 속성을 업데이트하세요. 

여기의 모든 값은 이전 값을 덮어쓸 것입니다. 이 값을 지우려고 하지 않는다면 빈 필드는 비워두세요.',
	'mwoauthconsumerregistration-maintext' => '이 문서는 개발자가 제안하고 이 사이트의 레지스트리에서 OAuth 컨슈머 응용 프로그램을 업데이트할 수 있도록 합니다.

여기서 할 수 있는 작업:
* [[Special:OAuthConsumerRegistration/propose|새로운 고객에게 토큰을 요청]].
* [[Special:OAuthConsumerRegistration/list|기존 고객 관리]].

OAuth에 대한 더 자세한 정보를 얻으려면 [//www.mediawiki.org/wiki/Extension:OAuth 확장 기능 문서]를 참조하세요.',
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
	'mwoauthconsumerregistration-proposed' => "당신의 OAuth 고객 요청을 받았습니다.

'''\$1''' 컨슈머 토큰과 '''\$2''' 비밀 토큰을 재할당했습니다.
\"나중에 참조하기 위해서 이것을 기록해주세요.\"",
	'mwoauthconsumerregistration-updated' => 'OAuth 컨슈머 등록을 성공적으로 업데이트했습니다.',
	'mwoauthconsumerregistration-secretreset' => "고객에게 '''\$1'''의 비밀 토큰을 할당했습니다. \"나중에 참조하기 위해서 이것을 기록해주세요.\"",
	'oauthmanageconsumers' => 'OAuth 컨슈머 관리',
	'mwoauthmanageconsumers-notloggedin' => '이 페이지에 접근하려면 로그인해야 합니다.',
	'mwoauthmanageconsumers-type' => '대기열:',
	'mwoauthmanageconsumers-showproposed' => '제안된 요청',
	'mwoauthmanageconsumers-showrejected' => '거부된 요청',
	'mwoauthmanageconsumers-showexpired' => '만료된 요청',
	'mwoauthmanageconsumers-main' => '주요',
	'mwoauthmanageconsumers-maintext' => '이 문서는 OAuth(see http://oauth.net)를 다루는 컨슈머 지원서 요청과 인증된 OAuth 고객 관리용입니다.',
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
	'mwoauthmanageconsumers-viewing' => '"$1" {{GENDER:$1|사용자}}는 현재 이 컨슈머를 보는 중입니다',
	'mwoauthmanageconsumers-success-approved' => '요청이 승인되었습니다.',
	'mwoauthmanageconsumers-success-rejected' => '요청이 거부되었습니다.',
	'mwoauthmanageconsumers-success-disabled' => '컨슈머가 비활성화되었습니다.',
	'mwoauthmanageconsumers-success-reanable' => '컨슈머가 다시 활성화되었습니다.',
	'mwoauthmanageconsumers-search-name' => '이 이름을 가진 컨슈머',
	'mwoauthmanageconsumers-search-publisher' => '이 사용자의 컨슈머',
	'oauthlistconsumers' => 'OAuth 응용 프로그램 목록',
	'mwoauthlistconsumers-legend' => 'OAuth 응용 프로그램 찾아보기',
	'mwoauthlistconsumers-view' => '자세한 사항',
	'mwoauthlistconsumers-none' => '이 기준에 부합하는 응용 프로그램을 찾을 수 없습니다.',
	'mwoauthlistconsumers-name' => '응용 프로그램 이름',
	'mwoauthlistconsumers-version' => '컨슈머 버전',
	'mwoauthlistconsumers-user' => '게시자',
	'mwoauthlistconsumers-description' => '설명',
	'mwoauthlistconsumers-wiki' => '적용할 수 있는 프로젝트',
	'mwoauthlistconsumers-callbackurl' => 'OAuth "콜백 URL"',
	'mwoauthlistconsumers-grants' => '적용할 수 있는 부여',
	'mwoauthlistconsumers-basicgrantsonly' => '(기본 액세스의 경우에만)',
	'mwoauthlistconsumers-status' => '상태',
	'mwoauth-consumer-stage-any' => '모두',
	'mwoauthlistconsumers-status-proposed' => '제안됨',
	'mwoauthlistconsumers-status-approved' => '승인됨',
	'mwoauthlistconsumers-status-disabled' => '비활성화됨',
	'mwoauthlistconsumers-status-rejected' => '거부됨',
	'mwoauthlistconsumers-status-expired' => '만료됨',
	'oauthmanagemygrants' => '연결된 응용 프로그램 관리',
	'mwoauthmanagemygrants-text' => '이 문서는 당신의 계정을 사용할 수 있는 모든 응용 프로그램을 나열합니다.
응용 프로그램이 당신을 대신하여 인증할 때 응용 프로그램에 부여한 권한이 접근 범위를 제한합니다. 


만약 응용프로그램이 당신을 대신해 자매 프로젝트에 접근하도록 하기 위해서 응용프로그램에 개별적으로 권한을 부여한다면  각각의 프로젝트들에 대한 개별 설정이 아래에 보일 것입니다.

연결된 응용 프로그램들은 OAuth 프로토콜을 사용하여 계정에 접근합니다. <span class="plainlinks">([https://www.mediawiki.org/wiki/Special:MyLanguage/Help:OAuth 연결된 응용 프로그램에 대해서 알아보세요])</span>',
	'mwoauthmanagemygrants-notloggedin' => '이 페이지에 접근하려면 로그인해야 합니다.',
	'mwoauthmanagemygrants-navigation' => '둘러보기:',
	'mwoauthmanagemygrants-showlist' => '연결된 응용 프로그램 목록',
	'mwoauthmanagemygrants-none' => '계정에 연결된 응용 프로그램이 없습니다.',
	'mwoauthmanagemygrants-user' => '게시자:',
	'mwoauthmanagemygrants-description' => '설명',
	'mwoauthmanagemygrants-wikiallowed' => '프로젝트에 허용됨:',
	'mwoauthmanagemygrants-grants' => '적용할 수 있는 부여',
	'mwoauthmanagemygrants-grantsallowed' => '허용된 부여',
	'mwoauthmanagemygrants-applicablegrantsallowed' => '적용할 수 있는 허용된 부여:',
	'mwoauthmanagemygrants-review' => '접근 관리',
	'mwoauthmanagemygrants-revoke' => '접근 취소',
	'mwoauthmanagemygrants-grantaccept' => '컨슈머 부여됨',
	'mwoauthmanagemygrants-update-text' => '이 양식을 사용해서 당신을 대신하여 응용 프로그램에 부여한 권한을 수정하세요',
	'mwoauthmanagemygrants-revoke-text' => '아래 양식을 사용하여 당신을 대신하여 응용 프로그램에 접근할 권한을 회수하세요.',
	'mwoauthmanagemygrants-confirm-legend' => '연결된 응용프로그램 관리',
	'mwoauthmanagemygrants-update' => '접근 토큰 업데이트',
	'mwoauthmanagemygrants-renounce' => '접근 토큰 인증 해제',
	'mwoauthmanagemygrants-action' => '상태 바꾸기:',
	'mwoauthmanagemygrants-confirm-submit' => '접근 토큰 상태 업데이트',
	'mwoauthmanagemygrants-success-update' => '이 컨슈머에 대한 접근 토큰이 업데이트되었습니다.',
	'mwoauthmanagemygrants-success-renounce' => '이 컨슈머에 대한 접근 토큰이 삭제되었습니다.',
	'mwoauthmanagemygrants-useoauth-tooltip' => '권한 부여를 왜 업데이트할 수 없나요? 이 권한 부여는 당신의 연결된 응용 프로그램에 제대로 작동하기 위해 필요한 기본 권한을 부여합니다. 만약 당신이 이 연결된 응용 프로그램에 이 권한을 부여하고 싶지 않다면 응용 프로그램의 접근을 취소해야합니다.',
	'logentry-mwoauthconsumer-propose' => '$1 사용자가 OAuth 컨슈머를 {{GENDER:$2|제안했습니다}} (컨슈머 키 $4)',
	'logentry-mwoauthconsumer-update' => '$1 사용자가 OAuth 컨슈머를 {{GENDER:$2|업데이트했습니다}} (컨슈머 키 $4)',
	'logentry-mwoauthconsumer-approve' => '$1 사용자가 $3에 의해 OAuth 컨슈머를 {{GENDER:$2|승인했습니다}} (컨슈머 키 $4)',
	'logentry-mwoauthconsumer-reject' => '$1 사용자가 $3에 의해 OAuth 컨슈머를 {{GENDER:$2|거부했습니다}} (컨슈머 키 $4)',
	'logentry-mwoauthconsumer-disable' => '$1 사용자가 $3에 의해 OAuth 컨슈머를 {{GENDER:$2|비활성화했습니다}} (컨슈머 키 $4)',
	'logentry-mwoauthconsumer-reenable' => '$1 사용자가 $3에 의해 OAuth 컨슈머를 {{GENDER:$2|다시 활성화했습니다}} (컨슈머 키 $4)',
	'mwoauthconsumer-consumer-logpage' => 'OAuth 컨슈머 기록',
	'mwoauthconsumer-consumer-logpagetext' => 'OAuth 컨슈머에 등록된 승인, 거부와 비활성화의 기록입니다.',
	'mwoauth-bad-request-missing-params' => '죄송합니다. 연결된 응용 프로그램을 설정하는 도중 문제가 발생했습니다. <span class="plainlinks">[https://www.mediawiki.org/wiki/Special:MyLanguage/Help:OAuth Contact support]</span>에서 고치는 데 도움을 받을 수 있습니다.

<span class="plainlinks mw-mwoautherror-details">OAuth 매개변수 없음, [https://www.mediawiki.org/wiki/Help:OAuth/Errors#E001 E001]</span>',
	'mwoauth-bad-request-invalid-action' => '죄송합니다. 문제가 발생했습니다. 도움을 받으려면 다음의 링크에서 응용 프로그램 작성자와 연락하세요.

<span class="plainlinks mw-mwoautherror-details">Unknown URL, [https://www.mediawiki.org/wiki/Help:OAuth/Errors#E002 E002]</span>',
	'mwoauth-bad-request-invalid-action-contact' => '죄송합니다. 문제가 발생했습니다. 도움을 받으려면 다음의 링크에서 응용 프로그램 작성자와 [$1 연락]하세요.

<span class="plainlinks mw-mwoautherror-details">Unknown URL, [https://www.mediawiki.org/wiki/Help:OAuth/Errors#E003 E003]</span>',
	'mwoauthdatastore-access-token-not-found' => '인증 토큰에 대한 승인된 부여를 찾을 수 없습니다.',
	'mwoauthdatastore-request-token-not-found' => '죄송합니다. 해당 응용 프로그램에 연결하는 도중 문제가 발생하였습니다.
뒤로 돌아가셔서 계정에 연결을 다시 시도하시거나 응용 프로그램 작성자와 연락해보십시요.

<span class="plainlinks mw-mwoautherror-details">OAuth 토큰 찾을 수 없음, [https://www.mediawiki.org/wiki/Help:OAuth/Errors#E004 E004]</span>',
	'mwoauthdatastore-bad-token' => '요청과 일치하는 토큰을 찾을 수 없습니다.',
	'mwoauthdatastore-bad-verifier' => '제공한 인증 코드는 올바르지 않습니다.',
	'mwoauthdatastore-invalid-token-type' => '요청한 토큰 유형이 잘못되었습니다.',
	'mwoauthgrants-general-error' => 'OAuth 요청에 오류가 있습니다.',
	'mwoauthserver-bad-consumer' => '"$1" 프로그램은 더 이상 승인된 연결 응용 프로그램이 아니며 응용 프로그램 작성자에게 도움을 [$2 요청해주세요].

<span class="plainlinks mw-mwoautherror-details">연결된 OAuth 앱이 승인되지 않음,[https://www.mediawiki.org/wiki/Help:OAuth/Errors#E005 E005]</span>',
	'mwoauthserver-bad-consumer-key' => '죄송합니다. 이 응용 프로그램과 연결하는 도중 문제가 발생했습니다.

<span class="plainlinks mw-mwoautherror-details">Unknown OAuth key, [https://www.mediawiki.org/wiki/Help:OAuth/Errors#E006 E006]</span>',
	'mwoauthserver-insufficient-rights' => '당신의 계정은 연결된 응용 프로그램을 사용하는 것이 허용되지 않으며 사이트관리자에게 문의하세요.

<span class="plainlinks mw-mwoautherror-details">OAuth 사용자 권한 부족, [https://www.mediawiki.org/wiki/Help:OAuth/Errors#E007 E007]</span>',
	'mwoauthserver-invalid-request-token' => '요청에 잘못된 토큰이 있습니다.',
	'mwoauthserver-invalid-user' => '이 사이트에서 연결된 응용 프로그램을 사용하려면 모든 프로젝트에서 계정을 가지고 있어야 합니다. 모든 프로젝트에서 계정을 취득하고 나면 "$1"에 다시 연결해주세요.

<span class="plainlinks mw-mwoautherror-details">Unified login needed, [https://www.mediawiki.org/wiki/Help:OAuth/Errors#E008 E008]</span>',
	'mwoauth-invalid-authorization-title' => 'OAuth 인증 오류',
	'mwoauth-invalid-authorization' => '요청에 있는 인증 헤더가 올바르지 않습니다: $1',
	'mwoauth-invalid-authorization-wrong-wiki' => '요청에 있는 인증 헤더가 $1에 대해 올바르지 않습니다',
	'mwoauth-invalid-authorization-invalid-user' => '요청에 있는 인증 헤더가 여기에서 존재하지 않는 사용자를 위한 것입니다',
	'mwoauth-invalid-authorization-wrong-user' => '요청에 있는 인증 헤더가 다른 사용자를 위한 것입니다',
	'mwoauth-invalid-authorization-not-approved' => '연결하려는 응용 프로그램이 정확하지 않습니다. "$1"의 작성자에게 도움을 요청하세요.',
	'mwoauth-invalid-authorization-blocked-user' => '요청에 있는 인증 헤더가 차단된 사용자를 위한 것입니다',
	'mwoauth-form-description-allwikis' => "$1 님, 안녕하세요.


'''$2''' 프로그램이 당신을 대신하여 이 사이트의 모든 프로젝트에 대해 다음 명령을 수행하려고 합니다:


$4",
	'mwoauth-form-description-onewiki' => "$1 님, 안녕하세요.


'''$2''' 프로그램이 당신을 대신하여 ''$4'' 위키에 다음 명령을 수행하려고 합니다:


$5",
	'mwoauth-form-description-allwikis-nogrants' => "$1 님, 안녕하세요.


'''$2''' 프로그램이 당신을 대신하여 이 사이트의 모든 프로젝트에 기본 접근하려고 합니다.",
	'mwoauth-form-description-onewiki-nogrants' => "$1 님, 안녕하세요.


'''$2''' 프로그램이 당신을 대신하여 ''$4'' 위키에 기본 접근하려고 합니다.",
	'mwoauth-form-privacypolicy-link' => '[[{{ns:Project}}:Privacy policy|개인 정보 정책]]',
	'mwoauth-form-button-approve' => '허용합니다',
	'mwoauth-form-button-cancel' => '취소합니다.',
	'mwoauth-error' => '응용 프로그램 연결 오류',
	'mwoauth-grants-heading' => '요청된 권한:',
	'mwoauth-grants-nogrants' => '애플리케이션은 권한을 요청하지 않았습니다.',
	'mwoauth-acceptance-cancelled' => '"$1" 고객이 당신의 계정에 접근하지 못하도록 선택했습니다. 접근할 수 없으면 "$1" 고객이 제대로 동작하지 못할 수도 있습니다. "$1"에 돌아가거나 연결된 응용 프로그램을 [[Special:OAuthManageMyGrants|관리]]해주세요.',
	'mwoauth-grant-group-page-interaction' => '문서로 상호 작용',
	'mwoauth-grant-group-file-interaction' => '미디어로 상호 작용',
	'mwoauth-grant-group-watchlist-interaction' => '당신의 주시문서로 상호작용',
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
	'mwoauth-grant-editmyoptions' => '사용자 환경 설정 편집하기',
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
	'mwoauth-listgrantrights-summary' => '다음은 사용자 권한에 관련된 접근 권한을 통해 부여된 OAuth 부여 목록입니다. 사용자는 자신의 계정에 대해 권한을 부여 할 수 있지만, 사용자가 응용 프로그램에 부여한 권한 설정에 따라 제한이 있습니다. 사용자를 대신하여 동작하는 응용 프로그램은 사용자가 갖고 있지 않은 권한은 사용할 수 없습니다. 
각각의 권한에 대한 [[{{MediaWiki:Listgrouprights-helppage}}|추가 정보]]가 있습니다.',
	'mwoauth-listgrants-grant' => '부여',
	'mwoauth-listgrants-rights' => '권한',
);

/** Luxembourgish (Lëtzebuergesch)
 * @author Robby
 */
$messages['lb'] = array(
	'mwoauth-desc' => 'De Gebrauch vun OAuth 1.0a fir API Autorisatiounerlaben',
	'mwoauth-missing-field' => 'De Wäert fir d\'Feld "$1" feelt',
	'mwoauth-invalid-field-generic' => 'Net valabele Wäert uginn',
	'mwoauth-field-hidden' => '(dës Informatioun ass verstoppt)',
	'mwoauth-field-private' => '(dës Informatioun ass privat)',
	'mwoauth-consumer-allwikis' => 'All Projeten op dësem Site',
	'mwoauth-consumer-name' => 'Numm vun der Applicatioun:',
	'mwoauth-consumer-stage' => 'Aktuelle Status:',
	'mwoauth-consumer-email' => 'Kontakt-E-Mail-Adress:',
	'mwoauth-consumer-description' => 'Beschreiwung vum Programm:',
	'mwoauth-consumer-wiki-thiswiki' => 'Aktuelle Projet ($1)',
	'mwoauth-consumer-wiki-other' => 'Spezifesche Projet',
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
	'mwoauthlistconsumers-name' => 'Numm vun der Applicatioun',
	'mwoauthlistconsumers-description' => 'Beschreiwung',
	'mwoauthlistconsumers-wiki' => 'Applicabele Projet',
	'mwoauthlistconsumers-status-proposed' => 'proposéiert',
	'mwoauthlistconsumers-status-disabled' => 'desaktivéiert',
	'mwoauthmanagemygrants-notloggedin' => 'Dir musst ageloggt si fir op dës Säit ze kommen.',
	'mwoauthmanagemygrants-navigation' => 'Navigatioun:',
	'mwoauthmanagemygrants-description' => 'Beschreiwung',
	'mwoauthmanagemygrants-wikiallowed' => 'Um Projet erlaabt:',
	'mwoauthmanagemygrants-grantaccept' => 'Accordéiert',
	'mwoauthmanagemygrants-update' => 'Rechter aktualiséieren',
	'mwoauthmanagemygrants-renounce' => 'Autorisatioun ewechhuelen',
	'mwoauthmanagemygrants-action' => 'Status änneren:',
	'mwoauth-invalid-authorization-title' => "OAuth Autorisatioun's-Feeler",
	'mwoauth-invalid-authorization-blocked-user' => "D'Autorisatiounen an Ärer Ufro si fir ee Benotzer dee gespaart ass",
	'mwoauth-form-privacypolicy-link' => '[[{{ns:Project}}:Privacy policy|Dateschutzrichtlinnen]]',
	'mwoauth-form-button-approve' => 'Erlaben',
	'mwoauth-form-button-cancel' => 'Ofbriechen',
	'mwoauth-error' => 'Verbinndungsfeeler vun der Software',
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
	'mwoauth-grant-editmyoptions' => 'Ännert Är eege Benotzerastellungen',
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
	'mwoauth-listgrants-rights' => 'Rechter',
);

/** Macedonian (македонски)
 * @author Bjankuloski06
 */
$messages['mk'] = array(
	'mwoauth-desc' => 'Овозможува употреба на OAuth 1.0a за заверка на прилози (API)',
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
	'mwoauth-consumer-wiki' => 'Применлив проект:',
	'mwoauth-consumer-wiki-thiswiki' => 'Тековен проект ($1)',
	'mwoauth-consumer-wiki-other' => 'Конкретен проект',
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
	'mwoauth-invalid-access-wrongwiki' => 'Потрошувачот може да се користи само на проектот „$1“.',
	'mwoauth-consumer-conflict' => 'Некои ги изменил атрибутети на овој потрошувач додека го разгледувавте. Обидете се повторно. Може да го погледате и дневникот на измени.',
	'mwoauth-consumer-grantshelp' => 'Секое доделување дава пристап до список до наведени права што веќе ги има корисничката сметка. Повеќе ќе најдете на [[Special:OAuth/grants|табелата со доделувања]].',
	'mwoauth-consumer-stage-proposed' => 'предложен',
	'mwoauth-consumer-stage-rejected' => 'одбиен',
	'mwoauth-consumer-stage-expired' => 'истечен',
	'mwoauth-consumer-stage-approved' => 'одобрен',
	'mwoauth-consumer-stage-disabled' => 'оневозможен',
	'mwoauth-consumer-stage-suppressed' => 'притаен',
	'oauthconsumerregistration' => 'Регистрација на потрошувач на OAuth',
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
* Можете да го ограничите потрошувачот на само еден проект на ова мрежно место, внесувајќи ја назнаката на проектот („*“ за сите проект).
* Укажаната е-пошта мора да одговара на онаа на вашата сметка (која пак, мора да биде потврдена).',
	'mwoauthconsumerregistration-update-text' => 'Образецот подолу служи за менување на аспекти на потрошувачот на OAuth што се во ваша контрола.

Сите вредности тука ќе се презапишат врз претходните. Не оставајте празни полиња, освен ако не сакате да ги исчистите истите.',
	'mwoauthconsumerregistration-maintext' => 'Оваа страница им овозможува на програмерите да предлагаат и подновуваат (менуваат) потрошувачки прилози за OAuth (погл. http://oauth.net) во регистарот на ова мрежно место.

Од овде можете да: [[Special:OAuthConsumerRegistration/propose|предложите нов потрошувач]] или пак [[Special:OAuthConsumerRegistration/list|раководите со вашите постоечки потрошувачи]].',
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
	'oauthmanageconsumers' => 'Раководење со потрошувачи на OAuth',
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
	'mwoauthmanageconsumers-viewing' => '{{GENDER:$1|Корисникот}} „$1“ во моментов го гледа потрошувачов',
	'mwoauthmanageconsumers-success-approved' => 'Барањето е одобрено.',
	'mwoauthmanageconsumers-success-rejected' => 'Барањето е одбиено.',
	'mwoauthmanageconsumers-success-disabled' => 'Потрошувачот е оневозможен.',
	'mwoauthmanageconsumers-success-reanable' => 'Потрошувачот е преовозможен.',
	'mwoauthmanageconsumers-search-name' => 'потрошувачи со ова име',
	'mwoauthmanageconsumers-search-publisher' => 'потрошувачи од овој корисник',
	'oauthlistconsumers' => 'Список на прилози на OAuth',
	'mwoauthlistconsumers-legend' => 'Прелистај прилози на OAuth',
	'mwoauthlistconsumers-view' => 'подробности',
	'mwoauthlistconsumers-none' => 'Нема прилози што одговараат на дадените услови.',
	'mwoauthlistconsumers-name' => 'Назив на прилогот',
	'mwoauthlistconsumers-version' => 'Потрошувачка верзија',
	'mwoauthlistconsumers-user' => 'Издавач',
	'mwoauthlistconsumers-description' => 'Опис',
	'mwoauthlistconsumers-wiki' => 'Применлив проект',
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
	'oauthmanagemygrants' => 'Раководење со поврзани прилози',
	'mwoauthmanagemygrants-text' => 'На страницава се наведени прилозите што можат да ја користат вашата сметка. Нивниот степен на пристап е ограничен од тоа што сте им дозволиле да прават кога сте им го одобриле пристапот. Ако на прилогот сте му дале посебно овластување за пристап на друг збратимен проект, тогаш подолу ќе ви се појават посебни поставки за секој одделен проект.

Поврзаните прилози ја користат вашата сметка преку протокол на OAuth. <span class="plainlinks">([https://www.mediawiki.org/wiki/Special:MyLanguage/Help:OAuth?uselang=mk Дознајте повеќе за поврзаните прилози])</span>',
	'mwoauthmanagemygrants-notloggedin' => 'Треба да сте најавени за да ја отворите страницата.',
	'mwoauthmanagemygrants-navigation' => 'Навигација:',
	'mwoauthmanagemygrants-showlist' => 'Список на поврзани прилози',
	'mwoauthmanagemygrants-none' => 'Нема прилози поврзани со вашата сметка.',
	'mwoauthmanagemygrants-user' => 'Издавач:',
	'mwoauthmanagemygrants-description' => 'Опис',
	'mwoauthmanagemygrants-wikiallowed' => 'Дозволен на проектот:',
	'mwoauthmanagemygrants-grants' => 'Применливи доделувања',
	'mwoauthmanagemygrants-grantsallowed' => 'Дозволени доделувања:',
	'mwoauthmanagemygrants-applicablegrantsallowed' => 'Дозволени применливи доделувања:',
	'mwoauthmanagemygrants-review' => 'раковод. со пристап',
	'mwoauthmanagemygrants-revoke' => 'одземи пристап',
	'mwoauthmanagemygrants-grantaccept' => 'Доделено',
	'mwoauthmanagemygrants-update-text' => 'Со овој образец можете да ги измените дозволите доделени на некој прилог за да делува во ваше име.',
	'mwoauthmanagemygrants-revoke-text' => 'Со овој образец можете да му го одземете пристапот на некој прилог за да делува во ваше име.',
	'mwoauthmanagemygrants-confirm-legend' => 'Раководење со поврзан прилог',
	'mwoauthmanagemygrants-update' => 'Измени доделувања',
	'mwoauthmanagemygrants-renounce' => 'Одземи дозвола',
	'mwoauthmanagemygrants-action' => 'Смени статус:',
	'mwoauthmanagemygrants-confirm-submit' => 'Измени статус на пристап. шифра',
	'mwoauthmanagemygrants-success-update' => 'Пристапната шифра на овој потрошувач е изменета.',
	'mwoauthmanagemygrants-success-renounce' => 'Пристапната шифра на овој потрошувач е избришана.',
	'mwoauthmanagemygrants-useoauth-tooltip' => 'Зошто не можам да го подновам ова доделување? Ова доделување му ги дава на поврзаниот прилог потребните дозволи за да работи како што треба. Ако не сакате да ги има тие права, треба да му го одземете пристапот.',
	'logentry-mwoauthconsumer-propose' => '$1 {{GENDER:$2|предложи}} потрошувач на OAuth (потрошувачки клуч $4)',
	'logentry-mwoauthconsumer-update' => '$1 {{GENDER:$2|измени}} потрошувач на OAuth (потрошувачки клуч $4)',
	'logentry-mwoauthconsumer-approve' => '$1 {{GENDER:$2|одобри}} потрошувач на OAuth со $3 (потрошувачки клуч $4)',
	'logentry-mwoauthconsumer-reject' => '$1 {{GENDER:$2|одби}} потрошувач на OAuth со $3 (потрошувачки клуч $4)',
	'logentry-mwoauthconsumer-disable' => '$1 оневозможи потрошувач на OAuth со $3 (потрошувачки клуч $4)',
	'logentry-mwoauthconsumer-reenable' => '$1 {{GENDER:$2|преовозможи}} потрошувач на OAuth со $3 (потрошувачки клуч $4)',
	'mwoauthconsumer-consumer-logpage' => 'Потрошувачки дневник за OAuth',
	'mwoauthconsumer-consumer-logpagetext' => 'Дневник на одобрувања, одбивања и оневозможувања на регистрирани потрошувачи на OAuth.',
	'mwoauth-bad-request-missing-params' => 'Нажалост, се јави проблем при поставувањето на прилогот. Обратете се на <span class="plainlinks">[https://www.mediawiki.org/wiki/Special:MyLanguage/Help:OAuth местото за поддршка]</span> за да дознаете како да го решите.

<span class="plainlinks mw-mwoautherror-details">OAuth без параметри, [https://www.mediawiki.org/wiki/Help:OAuth/Errors#E001 E001]</span>',
	'mwoauth-bad-request-invalid-action' => 'Нажалост, се јави проблем при поставувањето на прилогот. Обратете се кај неговиот автор за да го решите.

<span class="plainlinks mw-mwoautherror-details">Непозната URL, [https://www.mediawiki.org/wiki/Help:OAuth/Errors#E002 E002]</span>',
	'mwoauth-bad-request-invalid-action-contact' => 'Нажалост, се јави некаков проблем. Ќе треба да се [$1 обратите] кај авторот на прилогот за да го решите.

<span class="plainlinks mw-mwoautherror-details">Непозната URL, [https://www.mediawiki.org/wiki/Help:OAuth/Errors#E003 E003]</span>',
	'mwoauthdatastore-access-token-not-found' => 'Не пронајдов одобрено доделување со таа повластена шифра',
	'mwoauthdatastore-request-token-not-found' => 'Нажалост, се јави проблем при поврзувањето на прилогот.
Вратете се и пробајте одново, или пак обратете се кај неговиот автор.

<span class="plainlinks mw-mwoautherror-details">Не е пронајдена шифрата за OAuth, [https://www.mediawiki.org/wiki/Help:OAuth/Errors#E004 E004]</span>',
	'mwoauthdatastore-bad-token' => 'Не пронајдов барање што одговара на бараното',
	'mwoauthdatastore-bad-verifier' => 'Укажаниот потврден код е неважечки',
	'mwoauthdatastore-invalid-token-type' => 'Побараниот тип на шифра е неважечки.',
	'mwoauthgrants-general-error' => 'Се појави грешка во барањето за OAuth',
	'mwoauthserver-bad-consumer' => '„$1“ повеќе не е одобрен како поврзан прилог. Ако ви треба помош, [$2 обратете се] кај неговиот автор.

<span class="plainlinks mw-mwoautherror-details">Неодобрен поврзан прилог на OAuth, [https://www.mediawiki.org/wiki/Help:OAuth/Errors#E005 E005]</span>',
	'mwoauthserver-bad-consumer-key' => 'Нажалост, нешто не е во ред со прилогот.

<span class="plainlinks mw-mwoautherror-details">Непознат клуч за OAuth, [https://www.mediawiki.org/wiki/Help:OAuth/Errors#E006 E006]</span>',
	'mwoauthserver-insufficient-rights' => 'На вашата сметка не ѝ е дозволено да користи Поврзани прилози. Обратете се кај администраторот на мрежното место за да дознаете зошто.

<span class="plainlinks mw-mwoautherror-details">Недоволни кориснички права за OAuth, [https://www.mediawiki.org/wiki/Help:OAuth/Errors#E007 E007]</span>',
	'mwoauthserver-invalid-request-token' => 'Неважечкa шифра во барањето.',
	'mwoauthserver-invalid-user' => 'За да користите поврзани прилози на ова мрежно место, ќе мора да имате сметка на сите проекти (обединета сметка). Обидете се повторно да го поврзете „$1“ кога ќе направите таква сметка.

<span class="plainlinks mw-mwoautherror-details">Се бара обединета сметка, [https://www.mediawiki.org/wiki/Help:OAuth/Errors#E008 E008]</span>',
	'mwoauth-invalid-authorization-title' => 'Грешка со овластувањето во OAuth',
	'mwoauth-invalid-authorization' => 'Овластителните заглавија во вашето барање се неисправни: $1',
	'mwoauth-invalid-authorization-wrong-wiki' => 'Овластителните заглавија во вашето барање се неисправни за $1',
	'mwoauth-invalid-authorization-invalid-user' => 'Овластителните заглавија во вашето барање се однесуваат на корисникот што тука не постои',
	'mwoauth-invalid-authorization-wrong-user' => 'Овластителните заглавија во вашето барање се однесуваат на друг корисник',
	'mwoauth-invalid-authorization-not-approved' => 'Прилогот што сакате да го поврзете е исправно поставен. За помоп, обратете се кај авторот на „$1“.',
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
	'mwoauth-error' => 'Грешка во поврзувањето со прилогот',
	'mwoauth-grants-heading' => 'Побарани дозволи:',
	'mwoauth-grants-nogrants' => 'Прилогот нема побарано ниедна дозвола.',
	'mwoauth-acceptance-cancelled' => 'Одбравте на „$1“ да не му дозволите пристап на вашата сметка. „$1“ ќе работи само ако му дадете дозвола. Можете да се вратите на „$1“ или пак да направите [[Special:OAuthManageMyGrants|нагодувања]] на поврзаните прилози.',
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
	'mwoauth-grant-editmyoptions' => 'Уредете ги вашите кориснички нагодувања',
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
	'mwoauth-listgrantrights-summary' => 'Ова е список на доделувања на OAuth, секое со своите права. Корисниците можат да овластуваат прилози што ќе ги користат сметки, но со ограничувања во дозволите што им се доделени. Покрај ова, прилогот што делува во име на корисникот е ограничен на нештата на кои има права самиот корисник.
Може да најтете [[{{MediaWiki:Listgrouprights-helppage}}|уште информации]] за поединечните права.',
	'mwoauth-listgrants-grant' => 'Доделување',
	'mwoauth-listgrants-rights' => 'Права',
);

/** Malayalam (മലയാളം)
 * @author Kavya Manohar
 * @author Praveenp
 * @author Raghith
 * @author Santhosh.thottingal
 */
$messages['ml'] = array(
	'mwoauth-desc' => 'ഒഓത് 1.0എ എ.പി.ഐ. അനുമതി ഉപയോഗിക്കാൻ അനുവദിക്കുന്നു',
	'mwoauth-verified' => "ഈ സൗകര്യം ഇപ്പോൾ താങ്കളുടെ പേരിൽ മീഡിയവിക്കി എടുക്കാൻ അനുംതി നൽകുന്നു.

പ്രക്രിയ പൂർണ്ണമാക്കാൻ, ഈ സാധൂകരണ വില അപേക്ഷയിൽ നൽകുക: '''$1'''",
	'mwoauth-missing-field' => "'''$1''' എന്ന മണ്ഡലത്തിൽ വില നൽകിയിട്ടില്ല",
	'mwoauth-invalid-field' => "''$1'' എന്ന മണ്ഡലത്തിൽ അസാധുവായ വിലയാണ് നൽകിയിരിക്കുന്നത്",
	'mwoauth-invalid-field-generic' => 'അസാധുവായ വിലയാണ് നൽകിയത്',
	'mwoauth-field-hidden' => '(ഈ വിവരം മറയ്ക്കപ്പെട്ടിരിക്കുന്നു)',
	'mwoauth-field-private' => '(ഈ വിവരം സ്വകാര്യമാണ്)',
	'mwoauth-grant-generic' => '"$1" അവകാശ സഞ്ചയം',
	'mwoauth-prefs-managegrants' => 'ബന്ധപ്പെട്ടിരിക്കുന്ന ആപുകൾ:',
	'mwoauth-prefs-managegrantslink' => 'ബന്ധപ്പെട്ടിരിക്കുന്ന {{PLURAL:$1|സൗകര്യം|$1 സൗകര്യങ്ങൾ}} കൈകാര്യം ചെയ്യുക',
	'mwoauth-consumer-allwikis' => 'ഈ സൈറ്റിലെ എല്ലാ പദ്ധതികളും',
	'mwoauth-consumer-key' => 'ഉപഭോക്തൃചാവി:',
	'mwoauth-consumer-name' => 'അപേക്ഷകന്റെ/യുടെ പേര്:',
	'mwoauth-consumer-version' => 'ഉപയോക്തൃപതിപ്പ്:',
	'mwoauth-consumer-user' => 'പ്രസാധക(ൻ):',
	'mwoauth-consumer-stage' => 'തത്സ്ഥിതി:',
	'mwoauth-consumer-email' => 'ബന്ധപ്പെടാനുള്ള ഇമെയിൽ വിലാസം:',
	'mwoauth-consumer-description' => 'ആപ്ലിക്കേഷൻ വിവരണം:',
	'mwoauth-consumer-callbackurl' => "ഒഓത് ''പിൻവിളി'' യു.ആർ.എൽ:",
	'mwoauth-consumer-grantsneeded' => 'ബാധകമായ സഹായങ്ങൾ:',
	'mwoauth-consumer-required-grant' => 'ഉപഭോക്താവിനു ബാധകം',
	'mwoauth-consumer-wiki' => 'ബാധകമായ പദ്ധതി:',
	'mwoauth-consumer-wiki-thiswiki' => 'ഇപ്പോഴത്തെ പദ്ധതി ($1)',
	'mwoauth-consumer-wiki-other' => 'കൃത്യമായ പദ്ധതി',
	'mwoauth-consumer-restrictions' => 'ഉപയോഗത്തിന്റെ പരിമിതപ്പെടുത്തലുകൾ:',
	'mwoauth-consumer-restrictions-json' => 'ഉപയോഗത്തിന്റെ പരിമിതപ്പെടുത്തലുകൾ (ജെസൺ):',
	'mwoauth-consumer-rsakey' => 'പൊതു ആർ.എസ്.എ. ചാവി:',
	'mwoauth-consumer-secretkey' => 'ഉപഭോക്താവിനുള്ള രഹസ്യ ചീട്ട്:',
	'mwoauth-consumer-accesstoken' => 'അഭിഗമ്യതാ ചീട്ട്:',
	'mwoauth-consumer-reason' => 'കാരണം:',
	'mwoauth-consumer-email-unconfirmed' => 'താങ്കളുടെ അംഗത്വ ഇമെയിൽ ഇതുവരെ സ്ഥിരീകരിച്ചിട്ടില്ല.',
	'mwoauth-consumer-email-mismatched' => 'നൽകിയിരിക്കുന്ന ഇമെയിൽ വിലാസം താങ്കളുടെ അംഗത്വത്തിന്റേതുമായി ചേർച്ചയുള്ളതാവണം.',
	'mwoauth-consumer-alreadyexists' => 'ഈ പേര്/പതിപ്പ്/പ്രസാധകർ മിശ്രം നിലവിലുള്ള ഒരു ഉപഭോക്താവ്',
	'mwoauth-consumer-alreadyexistsversion' => 'ഇതിനു തുല്യമായതോ പുതിയതായതോ ആയ പതിപ്പിൽ ("$1") പേര്/പ്രസാധകർ മിശ്രം നിലവിലുള്ള ഒരു ഉപഭോക്താവ്',
	'mwoauth-consumer-not-accepted' => 'പണികൾ അവശേഷിക്കുന്ന ഉപഭോക്തൃ അഭ്യർത്ഥന സംബന്ധിച്ച വിവരങ്ങൾ പുതുക്കാൻ കഴിയില്ല',
	'mwoauth-consumer-not-proposed' => 'ഉപഭോക്താവ് ഇപ്പോൾ നിർദ്ദേശിച്ചിട്ടില്ല',
	'mwoauth-consumer-not-disabled' => 'ഉപഭോക്താവ് ഇപ്പോൽ പ്രവർത്തനരഹിതമല്ല',
	'mwoauth-consumer-not-approved' => 'ഉപഭോക്താവിനെ അംഗീകരിച്ചിട്ടില്ല (നിർജ്ജീവവുമായിരിക്കാം)',
	'mwoauth-missing-consumer-key' => 'ഉപഭോക്തൃചാവിയൊന്നും നൽകിയിട്ടില്ല.',
	'mwoauth-invalid-consumer-key' => 'നൽകിയിരിക്കുന്ന ചാവിക്ക് അനുയോജ്യമായ ഉപഭോക്താവ് ഇല്ല.',
	'mwoauth-invalid-access-token' => 'നൽകിയിരിക്കുന്ന ചാവിക്ക് യോജിച്ച പ്രവേശന ചീട്ട് നിലവിലില്ല.',
	'mwoauth-invalid-access-wrongwiki' => 'ഉപഭോക്തവിനെ "$1" പദ്ധതിയിൽ മാത്രമേ ഉപയോഗിക്കാനാവൂ.',
	'oauthlistconsumers' => 'ഓഓത് ആപ്ലിക്കേഷനുകൾ പട്ടികയായി കാണിക്കുക',
	'oauthmanagemygrants' => 'ബന്ധപ്പെടുത്തിയിട്ടുള്ള ആപ്ലിക്കേഷനുകൾ കൈകാര്യം ചെയ്യുക',
	'mwoauthmanagemygrants-user' => 'പ്രസാധക(ൻ)', # Fuzzy
	'mwoauth-form-button-cancel' => 'റദ്ദാക്കുക',
	'mwoauth-grant-sendemail' => 'ഇമെയിൽ അയയ്ക്കുക', # Fuzzy
);

/** Marathi (मराठी)
 * @author V.narsikar
 */
$messages['mr'] = array(
	'mwoauth-prefs-managegrantslink' => 'या खात्याचे वतीने अनुदानांचे व्यवस्थापन करा', # Fuzzy
	'mwoauth-consumer-email-unconfirmed' => 'तुमचा विपत्र (ई-मेल) पत्ता अद्याप प्रमाणित झाला नाही.',
	'mwoauth-consumer-email-mismatched' => 'आपल्या खात्याचा विपत्रपत्ता दिलेल्या विपत्रपत्त्याशी जुळावयास हवा.',
	'mwoauth-grant-blockusers' => 'सदस्य प्रतिबंधन', # Fuzzy
	'mwoauth-grant-delete' => 'पाने, आवृत्त्या व नोंदी वगळा',
	'mwoauth-grant-editinterface' => 'मिडियाविकि नामविश्व व सदस्यांची CSS/JS संपादा',
	'mwoauth-grant-editmycssjs' => 'आपले स्वतःचे CSS/JS पान संपादित करा',
	'mwoauth-grant-editmyoptions' => "आपल्या स्वत:चा 'पसंतीक्रम' संपादा",
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
	'mwoauth-desc' => 'Kelulusan OAuth 1.0a API', # Fuzzy
	'mwoauth-verified' => "Aplikasi ini kini dibenarkan untuk mengakses MediaWiki bagi pihak anda.

Untuk melengkapkan proses ini, berikan nilai penentusahan ini kepada aplikasi: '''$1'''",
	'mwoauth-missing-field' => 'Nilai tertinggal untuk ruangan "$1"',
	'mwoauth-invalid-field' => 'Nilai yang diberikan tidak sah untuk ruangan "$1"',
	'mwoauth-invalid-field-generic' => 'Nilai yang diberikan tidak sah',
	'mwoauth-field-hidden' => '(maklumat ini tersembunyi)',
	'mwoauth-field-private' => '(maklumat ini adalah peribadi)',
);

/** Norwegian Bokmål (norsk bokmål)
 * @author Danmichaelo
 */
$messages['nb'] = array(
	'mwoauth-desc' => 'Muliggjør bruk av OAuth 1.0a for API-autorisering',
	'mwoauth-verified' => "Applikasjonen har nå tilgang til MediaWiki på dine vegne.

For å fullføre prosessen, vennligst angi verifikasjonsverdien til applikasjonen: '''$1'''",
	'mwoauth-missing-field' => 'Mangler verdi for «$1»-feltet',
	'mwoauth-invalid-field' => 'Ugyldig verdi angitt for «$1»-feltet',
	'mwoauth-invalid-field-generic' => 'Ugyldig verdi angitt',
	'mwoauth-field-hidden' => '(denne informasjonen er skjult)',
	'mwoauth-field-private' => '(denne informasjonen er privat)',
	'mwoauth-grant-generic' => 'Rettighetspakken «$1»',
	'mwoauth-prefs-managegrants' => 'Tilkoblede apper:',
	'mwoauth-prefs-managegrantslink' => 'Håndter $1 {{PLURAL:$1|tilkoblet app|tilkoblede apper}}',
	'mwoauth-consumer-allwikis' => 'Ikke begrenset til prosjekt',
	'mwoauth-consumer-key' => 'Konsumentnøkkel:',
	'mwoauth-consumer-name' => 'Applikasjonsnavn:',
	'mwoauth-consumer-version' => 'Versjon:',
	'mwoauth-consumer-user' => 'Utgiver:',
	'mwoauth-consumer-stage' => 'Status:',
	'mwoauth-consumer-email' => 'E-postadresse',
	'mwoauth-consumer-description' => 'Beskrivelse av appen:',
	'mwoauth-consumer-callbackurl' => 'OAuth «callback»-URL:',
	'mwoauth-consumer-grantsneeded' => 'Tilgjengelige tildelinger:',
	'mwoauth-consumer-required-grant' => 'Tilgjengelig for konsumentappen',
	'mwoauth-consumer-wiki' => 'Begrens til prosjekt:',
	'mwoauth-consumer-wiki-thiswiki' => 'Nåværende prosjekt ($1)',
	'mwoauth-consumer-wiki-other' => 'Spesifikt prosjekt',
	'mwoauth-consumer-restrictions' => 'Bruksbegrensninger:',
	'mwoauth-consumer-restrictions-json' => 'Bruksbegrensninger (JSON):',
	'mwoauth-consumer-rsakey' => 'Offentlig RSA-nøkkel:',
	'mwoauth-consumer-secretkey' => 'Konsumentens hemmelige nøkkel:',
	'mwoauth-consumer-accesstoken' => 'Tilgangstoken:',
	'mwoauth-consumer-reason' => 'Årsak:',
	'mwoauth-consumer-email-unconfirmed' => 'E-postadressen din har ikke blitt bekreftet enda.',
	'mwoauth-consumer-email-mismatched' => 'Den oppgitte e-postadressen må stemme med den som er koblet til kontoen din.',
	'mwoauth-consumer-alreadyexists' => 'Det eksisterer allerede en konsument med denne kombinasjonen av navn/versjon/utgiver.',
	'mwoauth-consumer-alreadyexistsversion' => 'Det eksisterer allerede en konsument med denne kombinasjonen av navn/utgiver, med en lik eller høyere versjon («$1»)',
	'mwoauth-consumer-not-accepted' => 'Kan ikke oppdatere informasjon om en pågående konsumentsøknad',
	'mwoauth-consumer-not-proposed' => 'Konsumenten er ikke foreslått',
	'mwoauth-consumer-not-disabled' => 'Denne konsumenten er ikke deaktivert på det nåværende tidspunkt',
	'mwoauth-consumer-not-approved' => 'Denne konsumenten er godkjent (den kan ha vært deaktivert)',
	'mwoauth-missing-consumer-key' => 'Ingen konsumentnøkkel ble gitt.',
	'mwoauth-invalid-consumer-key' => 'Det eksisterer ingen konsument med den gitte nøkkelen.',
	'mwoauth-invalid-access-token' => 'Det eksisterer ingen tilgangstoken med den gitte nøkkelen.',
	'mwoauth-invalid-access-wrongwiki' => 'Konsumenten kan kun brukes på prosjektet «$1».',
	'mwoauth-consumer-conflict' => 'Noen endret attributtene til denne konsumenten mens du så på den. Vennligst prøv igjen. Se evt. i endringsloggen.',
	'mwoauth-consumer-grantshelp' => 'Hver tildeling gir tilgang til en eller flere brukerrettigheter som brukeren allerede har. Se [[Special:OAuth/grants|tabell over tildelinger]] for mer informasjon.',
	'mwoauth-consumer-stage-proposed' => 'foreslått',
	'mwoauth-consumer-stage-rejected' => 'avslått',
	'mwoauth-consumer-stage-expired' => 'utgått',
	'mwoauth-consumer-stage-approved' => 'godkjent',
	'mwoauth-consumer-stage-disabled' => 'deaktivert',
	'mwoauth-consumer-stage-suppressed' => 'underslått',
	'oauthconsumerregistration' => 'OAuth-konsumentregistrering',
	'mwoauthconsumerregistration-notloggedin' => 'Du må logge inn for å vise denne siden.',
	'mwoauthconsumerregistration-navigation' => 'Navigasjon:',
	'mwoauthconsumerregistration-propose' => 'Foreslå ny konsument',
	'mwoauthconsumerregistration-list' => 'Min konsumentliste',
	'mwoauthconsumerregistration-main' => 'Hovedside',
	'mwoauthconsumerregistration-propose-text' => 'Utviklere kan bruke skjemaet under for å søke om en ny OAuth-konsument (se [//www.mediawiki.org/wiki/Extension:OAuth dokumentasjonen for MediaWiki-tillegget] for flere detaljer). Etter at skjemaet er sendt inn, vil du få et token som applikasjonen kan bruke for å identifisere seg for MediaWiki. En OAuth-administrator må godkjenne søknaden før applikasjonen kan brukes av andre brukere.

Noen anbefalinger:
* Prøv å bruke så få rettighetstildelinger som mulig. Unngå tildelinger som strengt tatt ikke er nødvendige.
* Versjonsnumre følger formen «major.minor.release» (de siste to er valgfrie) og øker når endringer i tildelinger er nødvendige.
* Bruk JSON-bruksbegrensningsfeltet for å begrense konsumentapplikasjonen til IP-adresser i en bestemt CIDR-rekke.
* Du kan begrense applikasjonen til et bestemt prosjekt.
* E-postadressen du oppgir må være den samme som er knyttet til brukerkontoen din (og denne må være bekreftet).',
	'mwoauthconsumerregistration-update-text' => 'Bruk skjemaet under for å oppdatere innstillinger for en OAuth-konsument du kontrollerer.',
	'mwoauthconsumerregistration-maintext' => 'Denne siden er for å la utviklere foreslå og oppdatere OAuth-konsumentapplikasjoner i registeret.

Herfra kan du
* [[Special:OAuthConsumerRegistration/propose|Søke om et token for en ny konsumentapplikasjon]].
* [[Special:OAuthConsumerRegistration/list|Håndtere dine eksisterende konsumentapplikasjoner]].

For mer informasjon om OAuth, se [//www.mediawiki.org/wiki/Extension:OAuth dokumentasjonen for MediaWiki-tillegget].',
	'mwoauthconsumerregistration-propose-legend' => 'Ny OAuth-konsumentapplikasjon',
	'mwoauthconsumerregistration-update-legend' => 'Oppdater OAuth-konsumentapplikasjon',
	'mwoauthconsumerregistration-propose-submit' => 'Foreslå konsument',
	'mwoauthconsumerregistration-update-submit' => 'Oppdater konsument',
	'mwoauthconsumerregistration-none' => 'Du kontrollerer ingen OAuth-konsumenter.',
	'mwoauthconsumerregistration-name' => 'Konsument',
	'mwoauthconsumerregistration-user' => 'Utgiver',
	'mwoauthconsumerregistration-description' => 'Beskrivelse',
	'mwoauthconsumerregistration-email' => 'Kontakt-epost',
	'mwoauthconsumerregistration-consumerkey' => 'Konsumentnøkkel',
	'mwoauthconsumerregistration-stage' => 'Status',
	'mwoauthconsumerregistration-lastchange' => 'Siste endring',
	'mwoauthconsumerregistration-manage' => 'håndter',
	'mwoauthconsumerregistration-resetsecretkey' => 'Tilbakestill den hemmelige nøkkelen til en ny verdi',
	'mwoauthconsumerregistration-proposed' => "Forespørselen din om en OAuth-konsument har blitt levert.

Du har fått tildelt et konsumenttoken '''$1''' og et hemmelig token '''$2'''. ''Vennligst ta vare på disse til fremtidig bruk.''",
	'mwoauthconsumerregistration-updated' => 'Ditt OAuth-konsumentregister ble oppdatert.',
	'mwoauthconsumerregistration-secretreset' => "Du har fått tildelt et hemmelig konsumenttoken '''$1'''. ''Vennligst ta vare på denne til fremtidig bruk.''",
	'oauthmanageconsumers' => 'Håndter OAuth-konsumenter',
	'mwoauthmanageconsumers-notloggedin' => 'Du må være innlogget for å vise denne siden.',
	'mwoauthmanageconsumers-type' => 'Køer:',
	'mwoauthmanageconsumers-showproposed' => 'Ubehandlede søknader',
	'mwoauthmanageconsumers-showrejected' => 'Avslåtte søknader',
	'mwoauthmanageconsumers-showexpired' => 'Utgåtte søknader',
	'mwoauthmanageconsumers-main' => 'Hovedside',
	'mwoauthmanageconsumers-maintext' => 'Denne siden er til for å behandle OAuth-konsumentapplikasjonforespørsler (se http://oauth.net) og håndtere etablerte OAuth-konsumenter.',
	'mwoauthmanageconsumers-queues' => 'Velg en konsumentsøknadskø under:',
	'mwoauthmanageconsumers-q-proposed' => 'Kø av ubehandlede konsumentsøknader',
	'mwoauthmanageconsumers-q-rejected' => 'Kø av avslåtte konsumentsøknader',
	'mwoauthmanageconsumers-q-expired' => 'Kø av utgåtte konsumentsøknader',
	'mwoauthmanageconsumers-lists' => 'Velg en konsumentstatusliste under:',
	'mwoauthmanageconsumers-l-approved' => 'Liste over godkjente konsumenter',
	'mwoauthmanageconsumers-l-disabled' => 'Liste over deaktiverte konsumenter',
	'mwoauthmanageconsumers-none-proposed' => 'Ingen foreslåtte konsumenter i denne listen.',
	'mwoauthmanageconsumers-none-rejected' => 'Ingen foreslåtte konsumenter i denne listen.',
	'mwoauthmanageconsumers-none-expired' => 'Ingen foreslåtte konsumenter i denne listen.',
	'mwoauthmanageconsumers-none-approved' => 'Ingen konsumenter oppfyller disse kriteriene.',
	'mwoauthmanageconsumers-none-disabled' => 'Ingen konsumenter oppfyller disse kriteriene.',
	'mwoauthmanageconsumers-name' => 'Kunde',
	'mwoauthmanageconsumers-user' => 'Utgiver',
	'mwoauthmanageconsumers-description' => 'Beskrivelse',
	'mwoauthmanageconsumers-email' => 'E-postadresse',
	'mwoauthmanageconsumers-consumerkey' => 'Kundenøkkel',
	'mwoauthmanageconsumers-lastchange' => 'Siste endring',
	'mwoauthmanageconsumers-review' => 'behandle/håndter',
	'mwoauthmanageconsumers-confirm-text' => 'Bruk dette skjemaet for å godkjenne, avslå, deaktivere eller gjenaktivere denne konsumenten.',
	'mwoauthmanageconsumers-confirm-legend' => 'Håndter OAuth-konsument',
	'mwoauthmanageconsumers-action' => 'Endre status:',
	'mwoauthmanageconsumers-approve' => 'Godkjent',
	'mwoauthmanageconsumers-reject' => 'Avslått',
	'mwoauthmanageconsumers-rsuppress' => 'Avslått eller undertrykket',
	'mwoauthmanageconsumers-disable' => 'Deaktivert',
	'mwoauthmanageconsumers-dsuppress' => 'Deaktivert og undertrykket',
	'mwoauthmanageconsumers-reenable' => 'Godkjent',
	'mwoauthmanageconsumers-reason' => 'Årsak:',
	'mwoauthmanageconsumers-confirm-submit' => 'Oppdater kundestatus',
	'mwoauthmanageconsumers-viewing' => '{{GENDER:$1|Brukeren}} "$1" ser for øyeblikket på denne konsumenten',
	'mwoauthmanageconsumers-success-approved' => 'Søknaden har blitt godkjent.',
	'mwoauthmanageconsumers-success-rejected' => 'Forespørselen har blitt avslått.',
	'mwoauthmanageconsumers-success-disabled' => 'Kunden har blitt deaktivert.',
	'mwoauthmanageconsumers-success-reanable' => 'Kunden har blitt reaktivert.',
	'mwoauthmanageconsumers-search-name' => 'kunder med dette navn',
	'mwoauthmanageconsumers-search-publisher' => 'kunder for denne brukeren',
	'oauthlistconsumers' => 'Liste over OAuth-applikasjoner',
	'mwoauthlistconsumers-legend' => 'Bla i OAuth-applikasjoner',
	'mwoauthlistconsumers-view' => 'detaljer',
	'mwoauthlistconsumers-none' => 'Ingen applikasjoner oppfyller disse kriteriene.',
	'mwoauthlistconsumers-name' => 'Applikasjonsnavn',
	'mwoauthlistconsumers-version' => 'Kundeversjon',
	'mwoauthlistconsumers-user' => 'Utgiver',
	'mwoauthlistconsumers-description' => 'Beskrivelse',
	'mwoauthlistconsumers-wiki' => 'Begrenset til prosjekt',
	'mwoauthlistconsumers-callbackurl' => 'OAuth «callback-URL»',
	'mwoauthlistconsumers-grants' => 'Tilgjengelige tildelinger:',
	'mwoauthlistconsumers-basicgrantsonly' => '(kun grunnleggende tilgang)',
	'mwoauthlistconsumers-status' => 'Status',
	'mwoauth-consumer-stage-any' => 'alle',
	'mwoauthlistconsumers-status-proposed' => 'foreslått',
	'mwoauthlistconsumers-status-approved' => 'godkjent',
	'mwoauthlistconsumers-status-disabled' => 'deaktivert',
	'mwoauthlistconsumers-status-rejected' => 'avslått',
	'mwoauthlistconsumers-status-expired' => 'utgått',
	'oauthmanagemygrants' => 'Behandle tilkoblede applikasjoner',
	'mwoauthmanagemygrants-text' => 'Denne siden lister opp alle applikasjoner som kan bruke kontoen din. Hver applikasjons tilgang er begrenset til de rettigheter du har godkjent. Hvis du uavhengig har godkjent en applikasjon for bruk på flere søsterprosjekter, vil du se uavhengige konfigurasjonsmuligheter for hvert prosjekt under.

Applikasjonene har tilgang til kontoen din over OAuth-protokollen. <span class="plainlinks">([https://www.mediawiki.org/wiki/Special:MyLanguage/Help:OAuth Lær mer om tilkoblede applikasjoner])</span>',
	'mwoauthmanagemygrants-notloggedin' => 'Du må være innlogget for å vise denne siden.',
	'mwoauthmanagemygrants-navigation' => 'Navigasjon:',
	'mwoauthmanagemygrants-showlist' => 'Liste over tilkoblede applikasjoner',
	'mwoauthmanagemygrants-none' => 'Det er ingen applikasjoner tilknyttet kontoen din.',
	'mwoauthmanagemygrants-user' => 'Utgiver:',
	'mwoauthmanagemygrants-description' => 'Beskrivelse',
	'mwoauthmanagemygrants-wikiallowed' => 'Tillatt på prosjektet:',
	'mwoauthmanagemygrants-grants' => 'Tilgjengelige tildelinger',
	'mwoauthmanagemygrants-grantsallowed' => 'Tildelinger tillatt',
	'mwoauthmanagemygrants-applicablegrantsallowed' => 'Aktuelle tillatte tildelinger:',
	'mwoauthmanagemygrants-review' => 'håndter tilgang',
	'mwoauthmanagemygrants-revoke' => 'tilbakekall tilgang',
	'mwoauthmanagemygrants-grantaccept' => 'Bevilget',
	'mwoauthmanagemygrants-update-text' => 'Bruk skjemaet under for å tilpasse hvilke typer tilgang du vil tildele applikasjon for å handle på dine vegne.',
	'mwoauthmanagemygrants-revoke-text' => 'Bruk skjemaet under for å tilbakekalle en applikasjons tilgang til å handle på dine vegne.',
	'mwoauthmanagemygrants-confirm-legend' => 'Håndter tilkoblet applikasjon',
	'mwoauthmanagemygrants-update' => 'Oppdater tildelinger',
	'mwoauthmanagemygrants-renounce' => 'Avautorisér',
	'mwoauthmanagemygrants-action' => 'Endre status:',
	'mwoauthmanagemygrants-confirm-submit' => 'Oppdater status for tilgangstoken',
	'mwoauthmanagemygrants-success-update' => 'Tilgangstokenet for denne konsumenten har blitt oppdatert.',
	'mwoauthmanagemygrants-success-renounce' => 'Tilgangstokenet for denne konsumenten har blitt slettet.',
	'logentry-mwoauthconsumer-propose' => '$1 {{GENDER:$2|foreslo}} en OAuth-konsument (konsumentnøkkel $4)',
	'logentry-mwoauthconsumer-update' => '$1 {{GENDER:$2|oppdaterte}} en OAuth-konsument (konsumentnøkkel $4)',
	'logentry-mwoauthconsumer-approve' => '$1 {{GENDER:$2|godkjente}} en OAuth-konsument fra $3 (konsumentnøkkel $4)',
	'logentry-mwoauthconsumer-reject' => '$1 {{GENDER:$2|avslo}} en OAuth-konsument fra $3 (konsumentnøkkel $4)',
	'logentry-mwoauthconsumer-disable' => '$1 {{GENDER:$2|deaktiverte}} en OAuth-konsument fra $3 (konsumentnøkkel $4)',
	'logentry-mwoauthconsumer-reenable' => '$1 {{GENDER:$2|re-aktiverte}} en OAuth-konsument fra $3 (konsumentnøkkel $4)',
	'mwoauthconsumer-consumer-logpage' => 'OAuth-konsumentlogg',
	'mwoauthconsumer-consumer-logpagetext' => 'Logg over godkjennelser, avslag og deaktiveringer for registrerte OAuth-konsumenter.',
	'mwoauth-bad-request-missing-params' => 'Beklager, noe gikk galt under konfigurasjonen av den tilkoblede applikasjonen. <span class="plainlinks">[https://www.mediawiki.org/wiki/Special:MyLanguage/Help:OAuth Kontakt support]</span> for å få hjelp med å løse problemet.

<span class="plainlinks mw-mwoautherror-details">Manglende OAuth-parametere, [https://www.mediawiki.org/wiki/Help:OAuth/Errors#E001 E001]</span>',
	'mwoauth-bad-request-invalid-action' => 'Beklager, noe gikk galt, du må kontakte utvikleren av applikasjonen for å få hjelp med dette.

<span class="plainlinks mw-mwoautherror-details">Ukjent URL, [https://www.mediawiki.org/wiki/Help:OAuth/Errors#E002 E002]</span>',
	'mwoauth-bad-request-invalid-action-contact' => 'Beklager, noe gikk galt. Du må [$1 kontakte] utvikleren av applikasjonen for hjelp til å løse dette.

<span class="plainlinks mw-mwoautherror-details">Ukjent URL, [https://www.mediawiki.org/wiki/Help:OAuth/Errors#E003 E003]</span>',
	'mwoauthdatastore-access-token-not-found' => 'Ingen godkjente tildelinger ble funnet for dette autorisasjonstokenet.',
	'mwoauthdatastore-request-token-not-found' => 'Beklager, noe gikk galt ved tilkobling av denne applikasjonen.
Gå tilbake og prøv å koble til kontoen igjen, eller kontakt applikasjonsutvikleren.

<span class="plainlinks mw-mwoautherror-details">OAuth-token ble ikke funnet, [https://www.mediawiki.org/wiki/Help:OAuth/Errors#E004 E004]</span>',
	'mwoauthdatastore-bad-token' => 'Det ble ikke funnet noe token som matchet forespørselen din.',
	'mwoauthdatastore-bad-verifier' => 'Den angitte verifikasjonskoden var ikke gyldig.',
	'mwoauthdatastore-invalid-token-type' => 'Den forespurte tokentypen er ugyldig.',
	'mwoauthgrants-general-error' => 'Det var en feil i OAuth-forespørselen.',
	'mwoauthserver-bad-consumer' => '«$1» er ikke lenger en godkjent app, [$2 kontakt] apputvikleren for hjelp.

<span class="plainlinks mw-mwoautherror-details">Tilkoblet OAuth-app er ikke godkjent, [https://www.mediawiki.org/wiki/Help:OAuth/Errors#E005 E005]</span>',
	'mwoauthserver-bad-consumer-key' => 'Beklager, noe gikk galt under tilkoblingen til denne applikasjonen.

<span class="plainlinks mw-mwoautherror-details">Ukjent OAuth-nøkkel, [https://www.mediawiki.org/wiki/Help:OAuth/Errors#E006 E006]</span>',
	'mwoauthserver-insufficient-rights' => 'Kontoen din har ikke tilgang til å bruke tilkoblede applikasjoner, kontakt din sideadministrator for å finne ut hvorfor.

<span class="plainlinks mw-mwoautherror-details">Utilstrekkelige OAuth-brukerrettigheter, [https://www.mediawiki.org/wiki/Help:OAuth/Errors#E007 E007]</span>',
	'mwoauthserver-invalid-request-token' => 'Ugyldig token i forespørselen din.',
	'mwoauthserver-invalid-user' => "For å bruke tilkoblede applikasjoner på denne siden, må du ha en konto som fungerer på alle prosjektene (''unified login''). Når du har fått en slik konto kan du prøve å koble til «\$1» igjen.

<span class=\"plainlinks mw-mwoautherror-details\">Trenger ''unified login'', [https://www.mediawiki.org/wiki/Help:OAuth/Errors#E008 E008]</span>",
	'mwoauth-invalid-authorization-title' => 'OAuth-autoriseringsfeil',
	'mwoauth-invalid-authorization' => 'Autorisasjonsheaderne i forespørselen er ikke gyldige: $1',
	'mwoauth-invalid-authorization-wrong-wiki' => 'Autorisasjonsheaderne i forespørselen er ikke gyldige for $1',
	'mwoauth-invalid-authorization-invalid-user' => 'Autorisasjonsheaderne i forespørselen tilhører en bruker som ikke eksisterer her',
	'mwoauth-invalid-authorization-wrong-user' => 'Autorisasjonsheaderne i forespørselen tilhører en annen bruker',
	'mwoauth-invalid-authorization-not-approved' => 'Appen du prøver å koble til ser ikke ut til å være satt opp riktig. Kontakt utvikleren av «$1» for hjelp.',
	'mwoauth-invalid-authorization-blocked-user' => 'Autorisasjonsheaderne i forespørselen tilhører en bruker som er blokkert.',
	'mwoauth-form-description-allwikis' => "Hei $1,

'''$2''' ønsker tillatelse til å utføre følgende handlinger på dine vegne på alle prosjekter på dette nettstedet:

$4",
	'mwoauth-form-description-onewiki' => "Hei $1,

'''$2''' ønsker tillatelse til å utføre følgende handlinger på dine vegne på ''$4'':

$5",
	'mwoauth-form-description-allwikis-nogrants' => "Hei $1,

'''$2''' ønsker grunnleggende rettigheter på dine vegne på alle prosjekter på dette nettstedet.",
	'mwoauth-form-description-onewiki-nogrants' => "Hei $1,

'''$2''' ønsker tilgang til grunnleggende rettigheter på dine vegne på ''$4''.",
	'mwoauth-form-privacypolicy-link' => '[[{{ns:Project}}:Privacy policy|Personvern]]',
	'mwoauth-form-button-approve' => 'Godkjenn',
	'mwoauth-form-button-cancel' => 'Avbryt',
	'mwoauth-error' => 'Tilkoblingsfeil for applikasjonen',
	'mwoauth-grants-heading' => 'Ønskede rettigheter:',
	'mwoauth-grants-nogrants' => 'Applikasjonen har ikke bedt om noen rettigheter.',
	'mwoauth-acceptance-cancelled' => 'Du har valgt å ikke gi «$1» tilgang til kontoen din. «$1» vil ikke fungere uten denne tilgangen. Du kan velge å gå tilbake til «$1» eller [[Special:OAuthManageMyGrants|håndtere]] dine tilkoblede apper.',
	'mwoauth-grant-group-page-interaction' => 'Interagere med sider',
	'mwoauth-grant-group-file-interaction' => 'Interagere med media',
	'mwoauth-grant-group-watchlist-interaction' => 'Interagere med overvåkningslisten din',
	'mwoauth-grant-group-email' => 'Sende e-post',
	'mwoauth-grant-group-high-volume' => 'Utføre høyvolumaktivitet',
	'mwoauth-grant-group-customization' => 'Tilpasninger og innstillinger',
	'mwoauth-grant-group-administration' => 'Utføre administrative handlinger',
	'mwoauth-grant-group-other' => 'Andre ting',
	'mwoauth-grant-blockusers' => 'Blokkere og avblokkere brukere',
	'mwoauth-grant-createaccount' => 'Opprette kontoer',
	'mwoauth-grant-createeditmovepage' => 'Opprette, redigere og flytte sider',
	'mwoauth-grant-delete' => 'Slette sider, revisjoner og logginnlegg',
	'mwoauth-grant-editinterface' => 'Redigere i MediaWiki-navnerommet og CSS/JavaScript i brukernavnerommet',
	'mwoauth-grant-editmycssjs' => 'Redigere din egen CSS/JavaScript',
	'mwoauth-grant-editmyoptions' => 'Rediger dine egne innstillinger',
	'mwoauth-grant-editmywatchlist' => 'Redigere overvåkningslisten din',
	'mwoauth-grant-editpage' => 'Redigere eksisterende sider',
	'mwoauth-grant-editprotected' => 'Redigere beskyttede sider',
	'mwoauth-grant-highvolume' => 'Høyvolumredigering',
	'mwoauth-grant-oversight' => 'Skjule brukere og undertrykke revisjoner',
	'mwoauth-grant-patrol' => 'Patruljere sideendringer',
	'mwoauth-grant-protect' => 'Beskytte og avbeskytte sider',
	'mwoauth-grant-rollback' => 'Tilbakestille sideendringer',
	'mwoauth-grant-sendemail' => 'Sende e-post til andre brukere',
	'mwoauth-grant-uploadeditmovefile' => 'Laste opp, erstatte, og flytte filer',
	'mwoauth-grant-uploadfile' => 'Laste opp nye filer',
	'mwoauth-grant-useoauth' => 'Grunnleggende rettigheter',
	'mwoauth-grant-viewdeleted' => 'Vise slettet informasjon',
	'mwoauth-grant-viewmywatchlist' => 'Vise overvåkningslisten din',
	'mwoauth-oauth-exception' => 'Det oppsto en feil i OAuth-protokollen: $1',
	'mwoauth-callback-not-oob' => 'oauth_callback må angis, og må settes lik «oob» (skiftsensitiv)',
	'right-mwoauthproposeconsumer' => 'Foreslå nye OAuth-konsumenter',
	'right-mwoauthupdateownconsumer' => 'Oppdatere OAuth-konsumenter du kontrollerer',
	'right-mwoauthmanageconsumer' => 'Håndtere OAuth-konsumenter',
	'right-mwoauthsuppress' => 'Underslå OAuth-konsumenter',
	'right-mwoauthviewsuppressed' => 'Vise underslåtte OAuth-konsumenter',
	'right-mwoauthviewprivate' => 'Vise private OAuth-data',
	'right-mwoauthmanagemygrants' => 'Håndtere OAuth-tildelinger',
	'action-mwoauthmanageconsumer' => 'håndter OAuth-konsumenter',
	'action-mwoauthmanagemygrants' => 'håndter dine OAuth-tildelinger',
	'action-mwoauthproposeconsumer' => 'foreslå nye OAuth-konsumenter',
	'action-mwoauthupdateownconsumer' => 'oppdater OAuth-konsumenter du kontrollerer',
	'action-mwoauthviewsuppressed' => 'vis underslåtte OAuth-konsumenter',
	'mwoauth-listgrantrights-summary' => 'Følgende er en liste over OAuth-tildelinger og hvilke brukerrettigheter de gir tilgang til. Brukere kan autorisere applikasjoner til å bruke kontoen deres, med rettigheter begrenset til de gitt av tildelingene brukeren har godkjent. En applikasjon som handler på vegne av en bruker kan imidlertid aldri benytte seg av rettigheter brukeren ikke selv har.
Det kan finnes [[{{MediaWiki:Listgrouprights-helppage}}|ytterligere informasjon]] om de ulike rettighetene.',
	'mwoauth-listgrants-grant' => 'Tildeling',
	'mwoauth-listgrants-rights' => 'Rettigheter',
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
 * @author SPQRobin
 * @author Siebrand
 * @author Sjoerddebruin
 */
$messages['nl'] = array(
	'mwoauth-desc' => 'Maakt het mogelijk OAuth 1.0a te gebruik voor API-toestemming',
	'mwoauth-verified' => "De toepassing heeft nu namens u toegang tot MediaWiki.

Geef deze controlewaarde op in de toepassing om het proces te voltooien: '''$1'''",
	'mwoauth-missing-field' => 'Waarde voor het veld "$1" ontbreekt',
	'mwoauth-invalid-field' => 'Er is een ongeldige waarde opgegeven voor het veld "$1"',
	'mwoauth-invalid-field-generic' => 'Er is een ongeldige waarde opgegeven',
	'mwoauth-field-hidden' => '(deze gegevens zijn verborgen)',
	'mwoauth-field-private' => '(deze gegevens zijn privé)',
	'mwoauth-grant-generic' => 'Rechtengroep "$1"',
	'mwoauth-prefs-managegrants' => 'Gekoppelde apps:',
	'mwoauth-prefs-managegrantslink' => '$1 gekoppelde {{PLURAL:$1|toepassing|toepassingen}} beheren',
	'mwoauth-consumer-allwikis' => 'Alle projecten op deze site',
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
	'mwoauth-consumer-wiki' => 'Van toepassing op project:',
	'mwoauth-consumer-wiki-thiswiki' => 'Huidig project ($1)',
	'mwoauth-consumer-wiki-other' => 'Specifiek project',
	'mwoauth-consumer-restrictions' => 'Gebruiksbeperkingen:',
	'mwoauth-consumer-restrictions-json' => 'Gebruiksbeperkingen (JSON):',
	'mwoauth-consumer-rsakey' => 'Openbare SSH-sleutel:',
	'mwoauth-consumer-secretkey' => 'Geheim token consumer:',
	'mwoauth-consumer-accesstoken' => 'Toegangstoken:',
	'mwoauth-consumer-reason' => 'Reden:',
	'mwoauth-consumer-email-unconfirmed' => 'Het e-mailadres van uw gebruiker is nog niet bevestigd.',
	'mwoauth-consumer-email-mismatched' => 'Het opgegeven e-mailadres moet overeenkomen met dat van uw gebruiker.',
	'mwoauth-consumer-alreadyexists' => 'Er bestaat al een toepassing met deze combinatie van naam, versie en uitgever',
	'mwoauth-consumer-alreadyexistsversion' => 'Er bestaat al een toepassing met deze combinatie van naam en uitgever met een versie die gelijk of hoger is ("$1")',
	'mwoauth-consumer-not-accepted' => 'Het was niet mogelijk om gegevens van een openstaand toepassingsverzoek bij te werken',
	'mwoauth-consumer-not-proposed' => 'De toepassing wordt op dit moment niet voorgesteld',
	'mwoauth-consumer-not-disabled' => 'De toepassing is op dit moment niet uitgeschakeld',
	'mwoauth-consumer-not-approved' => 'De toepassing is niet goedgekeurd (deze kan uitgeschakeld zijn)',
	'mwoauth-missing-consumer-key' => 'Er is geen toepassingssleutel opgegeven.',
	'mwoauth-invalid-consumer-key' => 'Er bestaat geen toepassing met deze sleutel.',
	'mwoauth-invalid-access-token' => 'Er bestaat geen toegangstoken met de opgegeven sleutel.',
	'mwoauth-invalid-access-wrongwiki' => 'De toepassing kan alleen gebruikt worden op het project "$1".',
	'mwoauth-consumer-conflict' => 'Iemand heeft de eigenschappen van deze toepassing aangepast terwijl u deze aan het bekijken was. U kunt het wijzigingenlogboek bekijken.',
	'mwoauth-consumer-grantshelp' => 'Iedere toestemming geeft toegang tot de opgegeven gebruikersrechten die een gebruiker al heeft. Zie de [[Special:OAuth/grants|tabel met toestemmingen]] voor meer informatie.',
	'mwoauth-consumer-stage-proposed' => 'voorgesteld',
	'mwoauth-consumer-stage-rejected' => 'afgewezen',
	'mwoauth-consumer-stage-expired' => 'vervallen',
	'mwoauth-consumer-stage-approved' => 'goedgekeurd',
	'mwoauth-consumer-stage-disabled' => 'uitgeschakeld',
	'mwoauth-consumer-stage-suppressed' => 'onderdrukt',
	'oauthconsumerregistration' => 'Registratie van OAuth-toepassingen',
	'mwoauthconsumerregistration-notloggedin' => 'U moet aangemeld zijn om toegang te krijgen tot deze pagina.',
	'mwoauthconsumerregistration-navigation' => 'Navigatie:',
	'mwoauthconsumerregistration-propose' => 'Nieuwe consumer voorstellen',
	'mwoauthconsumerregistration-list' => 'Uw consumerlijst',
	'mwoauthconsumerregistration-main' => 'Startpagina',
	'mwoauthconsumerregistration-propose-text' => 'Ontwikkelaars moeten het onderstaande formulier gebruiken om een nieuwe OAuthtoepassing voor te stellen (zie de [//www.mediawiki.org/wiki/Extension:OAuth documentatie van de uitbreiding] voor meer details). Na het indienden van dit formulier ontvangt u een token dat uw programma gaat gebruiken om zichzelf te identificeren bij MediaWiki. Een OAuthbeheerder moet uw aanvraag goedkeuren voor het door andere gebruikers kan worden toegestaan.

Een paar aanbevelingen en opmerkingen:
* Probeer zo min mogelijk bevoegdheden te gebruiken  Vermijd bevoegdheden die niet echt nodig zijn;
* Versies hebben de opmaak "groot.klein.release" (de laatste twee elementen zijn optioneel) en moeten oplopen als er wijzigingen voor de toestemmingen nodig zijn;
* Gebruik als mogelijk een RSA-sleutel (in PEM-opmaak); als dat niet mogelijk is, wordt u een (minder veilig) geheim token toegewezen;
* Gebruik het veld JSON-beperkingen om de toegang voor deze toepassing te beperken tot IP-adressen in de opgegeven CIDR-bereiken;
* U kunt een project-ID gebruiken om de toepassing te beperken tot één enkel project op deze site (gebruik "*" voor alle projecten);
* Het e-mailadres moet overeenkomen met dat van uw gebruiker (en het e-mailadres moet zijn bevestigd).',
	'mwoauthconsumerregistration-update-text' => 'Gebruik het onderstaande formulier om bepaalde aspecten van de OAuthtoepassing die u beheert bij te werken.

Alle waarden hier overschrijven eerdere waarden. Laat velden niet leeg, tenzij u inderdaad waarden wilt verwijderen.',
	'mwoauthconsumerregistration-maintext' => 'Op deze pagina kunnen ontwikkelaars OAuthtoepassingen voorstellen en bijwerken in het register van deze site.

Vanaf hier kunt u:
* [[Special:OAuthConsumerRegistration/propose|Een token aanvragen voor een nieuwe toepassing]];
* [[Special:OAuthConsumerRegistration/list|Uw lijst met bestaande toepassing beheren]].

Voor meer informatie over OAuth kunt u de [https://www.mediawiki.org/wiki/Extension:OAuth uitbreidingsdocumentatie] raadplegen.',
	'mwoauthconsumerregistration-propose-legend' => 'Nieuwe OAuthconsumertoepassing',
	'mwoauthconsumerregistration-update-legend' => 'OAuthconsumertoepassing bijwerken',
	'mwoauthconsumerregistration-propose-submit' => 'Consumer voorstellen',
	'mwoauthconsumerregistration-update-submit' => 'Consumer bijwerken',
	'mwoauthconsumerregistration-none' => 'U hebt geen controle over OAuthapplicaties.',
	'mwoauthconsumerregistration-name' => 'Consumer',
	'mwoauthconsumerregistration-user' => 'Uitgever',
	'mwoauthconsumerregistration-description' => 'Beschrijving',
	'mwoauthconsumerregistration-email' => 'E-mailadres voor contact',
	'mwoauthconsumerregistration-consumerkey' => 'Consumersleutel',
	'mwoauthconsumerregistration-stage' => 'Status',
	'mwoauthconsumerregistration-lastchange' => 'Laatste wijziging',
	'mwoauthconsumerregistration-manage' => 'beheren',
	'mwoauthconsumerregistration-resetsecretkey' => 'Geheime sleutel op een nieuwe waarde instellen',
	'mwoauthconsumerregistration-proposed' => 'Uw OAuthapplicatieverzoek is geregistreerd.

U hebt het applicatietoken <strong>$1</strong> toegewezen gekregen en een geheim token <strong>$2</strong>. <em>Bewaar deze gegevens zorgvuldig.</em>',
	'mwoauthconsumerregistration-updated' => 'Uw OAuthapplicatieregister is bijgewerkt.',
	'mwoauthconsumerregistration-secretreset' => 'U hebt het geheime applicatietoken <strong>$1</strong>toegewezen gekregen. <em>Bewaar deze gegevens zorgvuldig.</em>',
	'oauthmanageconsumers' => 'OAuth-toepassingen beheren',
	'mwoauthmanageconsumers-notloggedin' => 'U moet aangemeld zijn om toegang te krijgen tot deze pagina.',
	'mwoauthmanageconsumers-type' => 'Wachtrijen:',
	'mwoauthmanageconsumers-showproposed' => 'Voorgestelde verzoeken',
	'mwoauthmanageconsumers-showrejected' => 'Afgewezen verzoeken',
	'mwoauthmanageconsumers-showexpired' => 'Verlopen aanvragen',
	'mwoauthmanageconsumers-main' => 'Startpagina',
	'mwoauthmanageconsumers-maintext' => 'Deze pagina is bedoeld voor het afhandelen van OAuthapplicatieverzoeken en het beheren van geregistreerde OAuthapplicaties. Zie http://oauth.net voor meer informatie over OAuth.',
	'mwoauthmanageconsumers-queues' => 'Kies hieronder een wachtrij voor applicatiebevestiging:',
	'mwoauthmanageconsumers-q-proposed' => 'Wachtrij met voorgestelde toepassingsverzoeken',
	'mwoauthmanageconsumers-q-rejected' => 'Wachtrij met afgewezen toepassingsverzoeken',
	'mwoauthmanageconsumers-q-expired' => 'Wachtrij met verlopen toepassingsverzoeken',
	'mwoauthmanageconsumers-lists' => 'Selecteer een consumerstatus uit de onderstaande lijst:',
	'mwoauthmanageconsumers-l-approved' => 'Wachtrij met goedgekeurde toepassingen',
	'mwoauthmanageconsumers-l-disabled' => 'Lijst met uitgeschakelde toepassingen',
	'mwoauthmanageconsumers-none-proposed' => 'Geen voorgestelde toepassingen.',
	'mwoauthmanageconsumers-none-rejected' => 'Geen voorgestelde toepassingen.',
	'mwoauthmanageconsumers-none-expired' => 'Geen voorgestelde consumers in deze lijst.',
	'mwoauthmanageconsumers-none-approved' => 'Er zijn geen consumers die aan deze voorwaarden voldoen.',
	'mwoauthmanageconsumers-none-disabled' => 'Er zijn geen toepassingen die aan de criteria voldoen.',
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
	'mwoauthmanageconsumers-viewing' => '{{GENDER:$1|Gebruiker}} "$1" bekijkt op dit moment deze toepassing',
	'mwoauthmanageconsumers-success-approved' => 'Het verzoek is goedgekeurd.',
	'mwoauthmanageconsumers-success-rejected' => 'Het verzoek is afgewezen.',
	'mwoauthmanageconsumers-success-disabled' => 'De consumer is uitgeschakeld.',
	'mwoauthmanageconsumers-success-reanable' => 'De consumer is opnieuw ingeschakeld.',
	'mwoauthmanageconsumers-search-name' => 'toepassingen met deze naam',
	'mwoauthmanageconsumers-search-publisher' => 'toepassingen van deze gebruiker',
	'oauthlistconsumers' => 'Lijst met OAuth-toepassingen',
	'mwoauthlistconsumers-legend' => 'OAuth-toepassingen bekijken',
	'mwoauthlistconsumers-view' => 'details',
	'mwoauthlistconsumers-none' => 'Er zijn geen toepassingen die aan de criteria voldoen.',
	'mwoauthlistconsumers-name' => 'Naam toepassing',
	'mwoauthlistconsumers-version' => 'Versie toepassing',
	'mwoauthlistconsumers-user' => 'Uitgever',
	'mwoauthlistconsumers-description' => 'Beschrijving',
	'mwoauthlistconsumers-wiki' => 'Van toepassing op project',
	'mwoauthlistconsumers-callbackurl' => 'OAuth callback-URL',
	'mwoauthlistconsumers-grants' => 'Toestemmingen',
	'mwoauthlistconsumers-basicgrantsonly' => '(alleen basistoegang)',
	'mwoauthlistconsumers-status' => 'Status',
	'mwoauth-consumer-stage-any' => 'alle',
	'mwoauthlistconsumers-status-proposed' => 'voorgesteld',
	'mwoauthlistconsumers-status-approved' => 'goedgekeurd',
	'mwoauthlistconsumers-status-disabled' => 'uitgeschakeld',
	'mwoauthlistconsumers-status-rejected' => 'afgewezen',
	'mwoauthlistconsumers-status-expired' => 'vervallen',
	'oauthmanagemygrants' => 'Gekoppelde toepassingen beheren',
	'mwoauthmanagemygrants-text' => 'Op deze pagina worden alle toepassingen weergegeven die toegang hebben tot uw gebruiker. Iedere toepassing kan alleen dat doen waar u de toepassing voor hebt gemachtigd. Als u toepassingen afzonderlijk toegang hebt gegevens tot uw gebruikers op zusterprojecten, dan ziet u hieronder afzonderlijke instellingen voor elk project.

Gekoppelde toepassingen hebben toegang tot uw gebruiker via het protocol OAuth. <span class="plainlinks">[https://www.mediawiki.org/wiki/Special:MyLanguage/Help:OAuth Meer over gekoppelde toepassingen]</span>.',
	'mwoauthmanagemygrants-notloggedin' => 'U moet aangemeld zijn om toegang te krijgen tot deze pagina.',
	'mwoauthmanagemygrants-navigation' => 'Navigatie:',
	'mwoauthmanagemygrants-showlist' => 'Lijst met gekoppelde toepassingen',
	'mwoauthmanagemygrants-none' => 'Er zijn geen toepassingen die toegang hebben namens uw gebruiker.',
	'mwoauthmanagemygrants-user' => 'Uitgever:',
	'mwoauthmanagemygrants-description' => 'Beschrijving',
	'mwoauthmanagemygrants-wikiallowed' => 'Toegestaan op project:',
	'mwoauthmanagemygrants-grants' => 'Van toepassing zijnde rechten',
	'mwoauthmanagemygrants-grantsallowed' => 'Toegestane rechten:',
	'mwoauthmanagemygrants-applicablegrantsallowed' => 'Van toepassing zijnde rechten toegestaan:',
	'mwoauthmanagemygrants-review' => 'toegang beheren',
	'mwoauthmanagemygrants-revoke' => 'toegang intrekken',
	'mwoauthmanagemygrants-grantaccept' => 'Toegestaan',
	'mwoauthmanagemygrants-update-text' => 'Gebruik het onderstaande formulier om de rechten te wijzigen die worden gegeven aan een toepassing om namens u te handelen.
* Als u toepassingen afzonderlijk toegang hebt gegevens tot uw gebruikers op zusterprojecten, dan heeft u afzonderlijke instellingen voor elk project voor een toepassing.',
	'mwoauthmanagemygrants-revoke-text' => 'Gebruik het onderstaande formulier om toegang voor een toepassing om namen u te handelen in te trekken.
* Als u toepassingen afzonderlijk toegang hebt gegevens tot uw gebruikers op zusterprojecten, dan heeft u afzonderlijke instellingen voor elk project voor een toepassing.
* Als u alle rechten voor toepassing wilt intrekken, zorg er dan voor dat u deze op alle projecten waar u toegang hebt verstrekt weer intrekt.',
	'mwoauthmanagemygrants-confirm-legend' => 'Gekoppelde toepassing beheren',
	'mwoauthmanagemygrants-update' => 'Toegang bijwerken',
	'mwoauthmanagemygrants-renounce' => 'Machtiging intrekken',
	'mwoauthmanagemygrants-action' => 'Statuswijziging:',
	'mwoauthmanagemygrants-confirm-submit' => 'Toegangstokenstatus bijwerken',
	'mwoauthmanagemygrants-success-update' => 'Het toegangstoken voor deze consumer is bijgewerkt.',
	'mwoauthmanagemygrants-success-renounce' => 'Het toegangstoken voor deze consumer is verwijderd.',
	'logentry-mwoauthconsumer-propose' => '$1 {{GENDER:$2|heeft}} een OAuthtoepassing voorgesteld (toepassingssleutel $4)',
	'logentry-mwoauthconsumer-update' => '$1 {{GENDER:$2|heeft}} een OAuthtoepassing bijgewerkt (toepassingssleutel $4)',
	'logentry-mwoauthconsumer-approve' => '$1 {{GENDER:$2|heeft}} een OAuthtoepassing goedgekeurd van $3 (toepassingssleutel $4)',
	'logentry-mwoauthconsumer-reject' => '$1 {{GENDER:$2|heeft}} een OAuthtoepassing afgekeurd van $3 (toepassingssleutel $4)',
	'logentry-mwoauthconsumer-disable' => '$1 {{GENDER:$2|heeft}} een OAuthtoepassing uitgeschakeld van $3 (toepassingssleutel $4)',
	'logentry-mwoauthconsumer-reenable' => '$1 {{GENDER:$2|heeft}} een OAuthtoepassing opnieuw ingeschakeld van $3 (toepassingssleutel $4)',
	'mwoauthconsumer-consumer-logpage' => 'OAuthconsumerlogboek',
	'mwoauthconsumer-consumer-logpagetext' => 'Logboek met goedkeuringen, afwijzingen en uitschakelingen van geregistreerde OAuthconsumers.',
	'mwoauth-bad-request-missing-params' => 'Er is helaas iets mis gegaan tijdens instellen van deze gekoppelde toepassing. <span class="plainlinks">[https://www.mediawiki.org/wiki/Special:MyLanguage/Help:OAuth Zoek ondersteuning]</span> om hulp te krijgen bij het oplossen van dit probleem.

<span class="plainlinks mw-mwoautherror-details">Parameters voor OAuth ontbreken, [https://www.mediawiki.org/wiki/Help:OAuth/Errors#E001 E001]</span>',
	'mwoauth-bad-request-invalid-action' => 'Er is helaas iets mis gegaan. U moet contact opnemen met de uitgever van de toepassing om dit probleem op te lossen.

<span class="plainlinks mw-mwoautherror-details">Onbekende URL, [https://www.mediawiki.org/wiki/Help:OAuth/Errors#E002 E002]</span>',
	'mwoauth-bad-request-invalid-action-contact' => 'Er is helaas iets mis gegaan. U moet [$1 contact opnemen] met de uitgever van de toepassing om dit probleem op te lossen.

<span class="plainlinks mw-mwoautherror-details">Onbekende URL, [https://www.mediawiki.org/wiki/Help:OAuth/Errors#E003 E003]</span>',
	'mwoauthdatastore-access-token-not-found' => 'Er is geen goedgekeurde toestemming gevonden voor dit autorisatietoken.',
	'mwoauthdatastore-request-token-not-found' => 'Er is helaas iets mis gegaan tijdens het koppelen van deze toepassing.
Ga terug, en probeer de koppeling opnieuw tot stand te brengen of neem contact op met de uitgever.

<span class="plainlinks mw-mwoautherror-details">OAuth-token niet gevonden, [https://www.mediawiki.org/wiki/Help:OAuth/Errors#E004 E004]</span>',
	'mwoauthdatastore-bad-token' => 'Er is geen token gevonden dat hoort bij uw verzoek',
	'mwoauthdatastore-bad-verifier' => 'De verificatiecode die is opgegeven is niet geldig',
	'mwoauthdatastore-invalid-token-type' => 'Het verzoektokentype is ongeldig',
	'mwoauthgrants-general-error' => 'Er is een fout opgetreden in uw OAthverzoek',
	'mwoauthserver-bad-consumer' => '"$1" is niet langer toegestaan als gekoppelde toepassing, voor hulp kunt u [$2 contact opnemen] met de uitgever van de toepassing.

<span class="plainlinks mw-mwoautherror-details">Gekoppelde OAuth-toepassing niet toegestaan, [https://www.mediawiki.org/wiki/Help:OAuth/Errors#E005 E005]</span>',
	'mwoauthserver-bad-consumer-key' => 'Er is helaas iets mis gegaan tijdens het koppelen van deze toepassing.

<span class="plainlinks mw-mwoautherror-details">Onbekende sleutel voor OAuth, [https://www.mediawiki.org/wiki/Help:OAuth/Errors#E006 E006]</span>',
	'mwoauthserver-insufficient-rights' => 'Uw gebruiker mag geen toepassingen koppelen. Neem contact op met de beheerder als u wilt weten waarom dit zo is.

<span class="plainlinks mw-mwoautherror-details">Onvoldoende gebruikersrechten voor OAuth, [https://www.mediawiki.org/wiki/Help:OAuth/Errors#E007 E007]</span>',
	'mwoauthserver-invalid-request-token' => 'Ongeldig token in uw verzoek',
	'mwoauthserver-invalid-user' => 'Om gebruik te kunnen maken van gekoppelde toepassingen op deze site, moet u een gebruiker hebben op alle projecten. Als u een gebruikers hebt op alle projecten, kunt u "$1" opnieuw proberen te koppelen.

<span class="plainlinks mw-mwoautherror-details">Samengevoegd aanmelden noodzakelijk, [https://www.mediawiki.org/wiki/Help:OAuth/Errors#E008 E008]</span>',
	'mwoauth-invalid-authorization-title' => 'Autorisatiefout van OAuth',
	'mwoauth-invalid-authorization' => 'De autorisatieheaders in uw verzoek zijn niet geldig: $1',
	'mwoauth-invalid-authorization-wrong-wiki' => 'De autorisatieheaders in uw verzoek zijn niet geldig voor $1',
	'mwoauth-invalid-authorization-invalid-user' => 'De autorisatieheaders in uw verzoek zijn voor een gebruiker die hier niet bestaat',
	'mwoauth-invalid-authorization-wrong-user' => 'De autorisatieheaders in uw verzoek zijn voor een andere gebruiker',
	'mwoauth-invalid-authorization-not-approved' => 'De toepassing die u probeert te koppelen lijkt onjuist te zijn opgezet. Neem contact op met de uitgever van "$1" voor hulp.',
	'mwoauth-invalid-authorization-blocked-user' => 'De autorisatieheaders in uw verzoek zijn voor een gebruiker die is geblokkeerd',
	'mwoauth-form-description-allwikis' => 'Hallo $1,

<strong>$2</strong> wil de volgende handelingen namens u kunnen uitvoeren op alle projecten van deze site:

$4',
	'mwoauth-form-description-onewiki' => 'Hallo $1,

<strong>$2</strong> wil de volgende handelingen namens u kunnen uitvoeren op <em>$4</em>:

$5',
	'mwoauth-form-description-allwikis-nogrants' => 'Hallo $1,

<strong>$2</strong> wil namens u basistoegang hebben tot alle projecten van deze site.',
	'mwoauth-form-description-onewiki-nogrants' => 'Hallo $1,

<strong>$2</strong> wil namens u basistoegang hebben tot <em>$4</em>.',
	'mwoauth-form-privacypolicy-link' => '[[{{ns:Project}}:Privacy policy|Privacybeleid]]',
	'mwoauth-form-button-approve' => 'Toestaan',
	'mwoauth-form-button-cancel' => 'Annuleren',
	'mwoauth-error' => 'Fout bij koppelen toepassing',
	'mwoauth-grants-heading' => 'Aangevraagde rechten:',
	'mwoauth-grants-nogrants' => 'De toepassing heeft geen rechten aangevraagd.',
	'mwoauth-acceptance-cancelled' => 'U hebt gekozen "$1" geen toegang te geven tot uw gebruiker. "$1" werkt niet, tenzij u de toepassing toegang geeft. U kunt teruggaan naar "$1" of uw gekoppelde toepassingen [[Special:OAuthManageMyGrants|beheren]].',
	'mwoauth-grant-group-page-interaction' => "Werken met pagina's",
	'mwoauth-grant-group-file-interaction' => 'Werken met media',
	'mwoauth-grant-group-watchlist-interaction' => 'Werken met uw volglijst',
	'mwoauth-grant-group-email' => 'E-mail verzenden',
	'mwoauth-grant-group-high-volume' => 'Activiteiten met hoog volume uitvoeren',
	'mwoauth-grant-group-customization' => 'Aanpassingen en voorkeuren',
	'mwoauth-grant-group-administration' => 'Beheerdershandelingen uitvoeren',
	'mwoauth-grant-group-other' => 'Diverse handelingen',
	'mwoauth-grant-blockusers' => 'Gebruikers (de)blokkeren',
	'mwoauth-grant-createaccount' => 'Gebruikers aanmaken',
	'mwoauth-grant-createeditmovepage' => "Pagina's aanmaken, bewerken en hernoemen",
	'mwoauth-grant-delete' => "Pagina's, wijzigingen en vermeldingen in het logboek verwijderen",
	'mwoauth-grant-editinterface' => 'De naamruimte MediaWiki en CSS en JavaScript van gebruikers bewerken',
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
	'mwoauth-oauth-exception' => 'Er is een fout opgetreden in het OAuth-protocol: $1',
	'mwoauth-callback-not-oob' => 'oauth_callback moet worden ingesteld, en moet worden ingesteld op "oob" (hoofdlettergevoelig)',
	'right-mwoauthproposeconsumer' => 'Nieuwe OAuthconsumers voorstellen',
	'right-mwoauthupdateownconsumer' => 'OAuthconsumers bijwerken',
	'right-mwoauthmanageconsumer' => 'OAuthconsumers beheren',
	'right-mwoauthsuppress' => 'OAuthconsumers onderdrukken',
	'right-mwoauthviewsuppressed' => 'Onderdrukte OAuthconsumers bekijken',
	'right-mwoauthviewprivate' => 'Beschermde OAuthgegevens bekijken',
	'right-mwoauthmanagemygrants' => 'OAuthbevoegdheden beheren',
	'action-mwoauthmanageconsumer' => 'OAuthconsumers te beheren',
	'action-mwoauthmanagemygrants' => 'uw OAuthbevoegdheden te beheren',
	'action-mwoauthproposeconsumer' => 'nieuwe OAuthconsumers voor te stellen',
	'action-mwoauthupdateownconsumer' => 'OAuthconsumers bij te werken',
	'action-mwoauthviewsuppressed' => 'onderdrukte OAuthconsumers te bekijken',
	'mwoauth-listgrantrights-summary' => 'Hieronder staat een lijst met OAuthtoestemmingen en de bijbehorende gebruikersrechten. Gebruikers kunnen toepassingen machtigen voor toegang tot hun gebruikers, maar met beperkte rechten gebaseerd op de toestemmingen die de gebruiker aan de toepassing heeft gegeven. Een toepassing die namens een gebruiker handelt, kan nooit rechten gebruiken die een gebruiker niet heeft.
Er zijn mogelijk [[{{MediaWiki:Listgrouprights-helppage}}|aanvullende  gegevens]] over individuele rechten.',
	'mwoauth-listgrants-grant' => 'Toestemming',
	'mwoauth-listgrants-rights' => 'Rechten',
);

/** Norwegian Nynorsk (norsk nynorsk)
 * @author Njardarlogar
 */
$messages['nn'] = array(
	'mwoauth-prefs-managegrants' => 'Tilkopla småprogram:',
	'mwoauth-prefs-managegrantslink' => 'Handsam {{PLURAL:$1|eitt tilknytt småprogram|$1 tilknytte småprogram}}',
	'mwoauthmanagemygrants-none' => 'Det finst ingen småprogram knytte til kontoen din.',
);

/** Occitan (occitan)
 * @author Cedric31
 */
$messages['oc'] = array(
	'mwoauth-desc' => 'API d’autentificacion OAuth 1.0a', # Fuzzy
	'mwoauth-missing-field' => 'Valor mancanta pel camp « $1 »',
	'mwoauth-invalid-field' => 'Valor invalida provesida pel camp « $1 »',
	'mwoauth-field-hidden' => '(aquesta informacion es amagada)',
	'mwoauth-field-private' => '(aquesta informacion es privada)',
	'mwoauth-grant-generic' => 'ensemble de dreches « $1 »',
	'mwoauth-prefs-managegrants' => 'Aplicacions connectadas :',
	'mwoauth-prefs-managegrantslink' => 'Gerir $1 {{PLURAL:$1|aplicacion connectada|aplicacions connectadas}}',
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
	'mwoauth-consumer-wiki' => 'Projècte aplicable :',
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
	'oauthconsumerregistration' => 'Inscripcion de consomidor OAuth',
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
	'oauthmanageconsumers' => 'Gerir los consomidors OAuth',
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
	'mwoauthmanagemygrants-user' => 'Editor :',
	'mwoauthmanagemygrants-description' => 'Descripcion',
	'mwoauthmanagemygrants-wikiallowed' => 'Autorizat sul projècte :',
	'mwoauth-error' => 'Error de connexion de l’aplicacion',
	'mwoauth-grant-blockusers' => 'Blocar e desblocar los utilizaires',
	'mwoauth-grant-patrol' => 'Marcar de paginas coma patrolhadas',
);

/** Polish (polski)
 * @author Chrumps
 * @author Peter Bowman
 * @author Rzuwig
 * @author Ty221
 * @author Vuh
 */
$messages['pl'] = array(
	'mwoauth-prefs-managegrants' => 'Włączone aplikacje:',
	'mwoauth-prefs-managegrantslink' => 'Zarządzaj $1 {{PLURAL:$1|włączoną aplikacją|włączonymi aplikacjami}}',
	'mwoauth-consumer-allwikis' => 'Wszystkie projekty na tej stronie',
	'mwoauth-consumer-name' => 'Nazwa aplikacji:',
	'mwoauth-consumer-user' => 'Wydawca:',
	'mwoauth-consumer-stage' => 'Aktualny status:',
	'mwoauth-consumer-reason' => 'Powód:',
	'mwoauth-consumer-stage-proposed' => 'proponowane',
	'mwoauth-consumer-stage-rejected' => 'odrzucone',
	'mwoauth-consumer-stage-expired' => 'przeterminowane',
	'mwoauth-consumer-stage-approved' => 'zatwierdzone',
	'mwoauth-consumer-stage-disabled' => 'wyłączone',
	'oauthconsumerregistration' => 'Rejestracja konsumenta OAuth',
	'mwoauthmanageconsumers-reason' => 'Powód:',
	'oauthlistconsumers' => 'Lista aplikacji OAuth',
	'mwoauthlistconsumers-legend' => 'Przegląd aplikacji OAuth',
	'mwoauth-consumer-stage-any' => 'dowolny',
	'oauthmanagemygrants' => 'Zarządzaj włączonymi aplikacjami',
	'mwoauthmanagemygrants-none' => 'Nie ma aplikacji związanych z Twoim kontem.',
	'mwoauthmanagemygrants-user' => 'Wydawca:',
	'mwoauthmanagemygrants-wikiallowed' => 'Dozwolone w projekcie:',
	'mwoauthmanagemygrants-review' => 'zarządzanie dostępem',
	'mwoauthmanagemygrants-revoke' => 'usunięcie dostępu',
	'mwoauthmanagemygrants-update' => 'Aktualizuj',
	'mwoauthmanagemygrants-renounce' => 'Anuluj dostęp',
	'mwoauth-grant-group-customization' => 'Dostosowywanie i preferencje',
	'mwoauth-grant-createeditmovepage' => 'Tworzenie, edycja i przenoszenie stron',
	'mwoauth-grant-editmyoptions' => 'Edytuj swoje preferencje',
	'mwoauth-grant-editpage' => 'Edytowanie istniejących stron',
	'mwoauth-grant-useoauth' => 'Podstawowe uprawnienia',
	'right-mwoauthproposeconsumer' => 'Proponowanie nowych konsumentów OAuth',
	'right-mwoauthupdateownconsumer' => 'Aktualizowanie kontrolowanych konsumentów OAuth',
	'right-mwoauthmanageconsumer' => 'Zarządzanie konsumentami OAuth',
	'right-mwoauthviewprivate' => 'Podgląd prywatnych danych OAuth',
	'right-mwoauthmanagemygrants' => 'Zarządzanie włączonymi aplikacjami OAuth',
	'action-mwoauthmanageconsumer' => 'zarządzanie konsumentami OAuth',
);

/** Pashto (پښتو)
 * @author Ahmed-Najib-Biabani-Ibrahimkhel
 */
$messages['ps'] = array(
	'mwoauth-prefs-managegrants' => 'اړيکمن کاريالونه:',
	'mwoauth-grant-blockusers' => 'په کارنانو بنديز لگول او بنديز ليرې کول',
);

/** Portuguese (português)
 * @author Dannyps
 * @author Lijealso
 * @author Vitorvicentevalente
 */
$messages['pt'] = array(
	'mwoauth-desc' => 'Permite o uso do OAuth 1.0a para autorização de API',
	'mwoauth-verified' => "O aplicativo possui permissão para aceder ao MediaWiki através da sua conta.

Para concluir o processo, proceda à verificação para o aplicativo através deste valor: '''$1'''",
	'mwoauth-missing-field' => 'Valor em falta para o campo "$1"',
	'mwoauth-invalid-field' => 'Valor inválido fornecido para o campo "$1"',
	'mwoauth-invalid-field-generic' => 'Valor fornecido inválido',
	'mwoauth-field-hidden' => '(esta informação está oculta)',
	'mwoauth-field-private' => '(esta informação é confidencial)',
	'mwoauth-prefs-managegrants' => 'Aplicativos conectados:',
	'mwoauth-prefs-managegrantslink' => 'Gerir $1 {{PLURAL:$1|aplicativo|aplicativos}} conectados',
	'mwoauth-consumer-allwikis' => 'Todos os projectos neste sítio',
	'mwoauth-consumer-key' => 'Chave de utilizador:',
	'mwoauth-consumer-name' => 'Nome do aplicativo:',
	'mwoauth-consumer-version' => 'Versão:',
	'mwoauth-consumer-user' => 'Editor:',
	'mwoauth-consumer-stage' => 'Estado actual:',
	'mwoauth-consumer-email' => 'Endereço de e-mail:',
	'mwoauth-consumer-description' => 'Descrição do aplicativo:',
	'mwoauth-consumer-wiki-thiswiki' => 'Projecto actual ($1)',
	'mwoauth-consumer-wiki-other' => 'Projecto específico',
	'mwoauth-consumer-restrictions' => 'Restrições de uso:',
	'mwoauth-consumer-restrictions-json' => 'Restrições de uso (JSON)',
	'mwoauth-consumer-reason' => 'Motivo:',
	'mwoauth-consumer-email-unconfirmed' => 'O seu endereço de correio electrónico ainda não foi confirmado.',
	'mwoauth-consumer-email-mismatched' => 'O endereço de correio electrónico fornecido deve coincidir com o da sua conta.',
	'mwoauth-consumer-stage-rejected' => 'rejeitado',
	'mwoauth-consumer-stage-expired' => 'expirado',
	'mwoauth-consumer-stage-approved' => 'aprovado',
	'mwoauth-consumer-stage-disabled' => 'desactivado',
	'mwoauth-consumer-stage-suppressed' => 'suprimido',
	'mwoauthconsumerregistration-notloggedin' => 'Precisa de estar ligado à sua conta para aceder a esta página.',
	'mwoauthconsumerregistration-navigation' => 'Navegação:',
	'mwoauthconsumerregistration-main' => 'Principal',
	'mwoauthconsumerregistration-user' => 'Editor',
	'mwoauthconsumerregistration-description' => 'Descrição',
	'mwoauthconsumerregistration-email' => 'E-mail de contacto',
	'mwoauthconsumerregistration-stage' => 'Estado',
	'mwoauthconsumerregistration-lastchange' => 'Última alteração',
	'mwoauthconsumerregistration-manage' => 'gerir',
	'mwoauthconsumerregistration-resetsecretkey' => 'Redefinir chave secreta para um novo valor',
	'mwoauthmanageconsumers-notloggedin' => 'Precisa de estar ligado à sua conta para aceder a esta página.',
	'mwoauthmanageconsumers-showproposed' => 'Pedidos propostos',
	'mwoauthmanageconsumers-showrejected' => 'Pedidos rejeitados',
	'mwoauthmanageconsumers-showexpired' => 'Pedidos expirados',
	'mwoauthmanageconsumers-main' => 'Principal',
	'mwoauthmanageconsumers-user' => 'Editor',
	'mwoauthmanageconsumers-description' => 'Descrição',
	'mwoauthmanageconsumers-email' => 'E-mail de contacto',
	'mwoauthmanageconsumers-consumerkey' => 'Chave de utilizador',
	'mwoauthmanageconsumers-lastchange' => 'Última alteração',
	'mwoauthmanageconsumers-review' => 'rever/gerir',
	'mwoauthmanageconsumers-action' => 'Alterar estado:',
	'mwoauthmanageconsumers-approve' => 'Aprovado',
	'mwoauthmanageconsumers-reject' => 'Rejeitado',
	'mwoauthmanageconsumers-rsuppress' => 'Rejeitado e suprimido',
	'mwoauthmanageconsumers-disable' => 'Desactivado',
	'mwoauthmanageconsumers-dsuppress' => 'Desactivado e suprimido',
	'mwoauthmanageconsumers-reenable' => 'Aprovado',
	'mwoauthmanageconsumers-reason' => 'Motivo:',
	'mwoauthmanageconsumers-success-approved' => 'O pedido foi aprovado.',
	'mwoauthmanageconsumers-success-rejected' => 'O pedido foi rejeitado.',
	'oauthlistconsumers' => 'Listar aplicativos OAuth',
	'mwoauthlistconsumers-legend' => 'Procurar aplicativos OAuth',
	'mwoauthlistconsumers-view' => 'detalhes',
	'mwoauthlistconsumers-none' => 'Não foram encontrados aplicativos com estes critérios.',
	'mwoauthlistconsumers-name' => 'Nome do aplicativo',
	'mwoauthlistconsumers-version' => 'Versão',
	'mwoauthlistconsumers-user' => 'Editor',
	'mwoauthlistconsumers-description' => 'Descrição',
	'mwoauthlistconsumers-wiki' => 'Projecto aplicável',
	'mwoauthlistconsumers-basicgrantsonly' => '(apenas acesso básico)',
	'mwoauthlistconsumers-status' => 'Estado',
	'mwoauth-consumer-stage-any' => 'qualquer',
	'mwoauthlistconsumers-status-proposed' => 'proposto',
	'mwoauthlistconsumers-status-approved' => 'aprovado',
	'mwoauthlistconsumers-status-disabled' => 'desactivado',
	'mwoauthlistconsumers-status-rejected' => 'rejeitado',
	'mwoauthlistconsumers-status-expired' => 'expirado',
	'oauthmanagemygrants' => 'Gerir aplicativos conectados',
	'mwoauthmanagemygrants-text' => 'Esta página lista todos os aplicativos que podem usar a sua conta. Para qualquer pedido, o âmbito do seu acesso é limitado por permissões que você concede ao aplicativo quando o autoriza a aceder à sua conta. Se autorizou separadamente o acesso de um aplicativo em seu nome em diferentes projectos irmãos, terá então uma configuração separada para cada projecto abaixo.

Os aplicativos conectados à sua conta utilizam o protocolo OAuth. <span class="plainlinks">([https://www.mediawiki.org/wiki/Special:MyLanguage/Help:OAuth Saiba mais])</span>',
	'mwoauthmanagemygrants-notloggedin' => 'Precisa de estar ligado à sua conta para aceder a esta página.',
	'mwoauthmanagemygrants-navigation' => 'Navegação:',
	'mwoauthmanagemygrants-showlist' => 'Lista de aplicativos conectados',
	'mwoauthmanagemygrants-none' => 'Não existem aplicativos ligados à sua conta.',
	'mwoauthmanagemygrants-user' => 'Editor:',
	'mwoauthmanagemygrants-description' => 'Descrição',
	'mwoauthmanagemygrants-wikiallowed' => 'Permitido no projecto:',
	'mwoauthmanagemygrants-review' => 'gerir acesso',
	'mwoauthmanagemygrants-revoke' => 'revogar acesso',
	'mwoauthmanagemygrants-grantaccept' => 'Concedido',
	'mwoauthmanagemygrants-update-text' => 'Utilize o formulário abaixo para modificar as permissões concedidas a um aplicativo para aceder à sua conta.
* Se autorizou separadamente um aplicativo em diferentes projectos irmãos, irá de seguida configurar esse aplicativo para cada projecto em separado.',
	'mwoauthmanagemygrants-revoke-text' => 'Utilize o formulário abaixo para revogar o acesso a um aplicativo que esteja a aceder à sua conta.
* Se autorizou separadamente um aplicativo em diferentes projectos irmãos, irá de seguida configurar esse aplicativo para cada projecto em separado.
* Se deseja revogar o acesso total de um aplicativo, certifique-se que revoga em todos os projectos em que o autorizou.',
	'mwoauthmanagemygrants-confirm-legend' => 'Gerir aplicativos conectados',
	'mwoauthmanagemygrants-action' => 'Alterar estado:',
	'mwoauthdatastore-bad-verifier' => 'O código de verificação fornecido não era válido.',
	'mwoauthgrants-general-error' => 'Ocorreu um erro no seu pedido OAuth.',
	'mwoauth-invalid-authorization-title' => 'Erro de autorização OAuth',
	'mwoauth-form-privacypolicy-link' => '[[{{ns:Project}}:Política de privacidade|Política de privacidade]]', # Fuzzy
	'mwoauth-form-button-approve' => 'Permitir',
	'mwoauth-form-button-cancel' => 'Cancelar',
	'mwoauth-error' => 'Erro de conexão do aplicativo',
	'mwoauth-grants-heading' => 'Permissões solicitadas:',
	'mwoauth-grants-nogrants' => 'Este aplicativo não solicitou quaisquer permissões.',
	'mwoauth-acceptance-cancelled' => 'Escolheu que o aplicativo "$1" não pode aceder à sua conta. "$1" não irá funcionar a menos que permita o seu acesso. Pode regressar a "$1" ou [[Special:OAuthManageMyGrants|gerir]] os seus aplicativos conectados.',
	'mwoauth-grant-group-page-interaction' => 'Interagir com páginas',
	'mwoauth-grant-group-file-interaction' => 'Interagir com multimédia',
	'mwoauth-grant-group-watchlist-interaction' => 'Interagir com a sua lista de vigiados',
	'mwoauth-grant-group-email' => 'Enviar correio electrónico',
	'mwoauth-grant-group-customization' => 'Personalização e preferências',
	'mwoauth-grant-group-administration' => 'Executar acções administrativas',
	'mwoauth-grant-group-other' => 'Actividade diversa',
	'mwoauth-grant-blockusers' => 'Bloquear e desbloquear utilizadores',
	'mwoauth-grant-createaccount' => 'Criar contas',
	'mwoauth-grant-createeditmovepage' => 'Criar, editar e mover páginas',
	'mwoauth-grant-editmywatchlist' => 'Editar a sua lista de vigiados',
	'mwoauth-grant-editpage' => 'Editar páginas existentes',
	'mwoauth-grant-editprotected' => 'Editar páginas protegidas',
	'mwoauth-grant-oversight' => 'Ocultar utilizadores e edições suprimidas',
	'mwoauth-grant-patrol' => 'Patrulhar alterações a páginas',
	'mwoauth-grant-protect' => 'Proteger e desproteger páginas',
	'mwoauth-grant-rollback' => 'Reverter alterações a páginas',
	'mwoauth-grant-sendemail' => 'Enviar correio electrónico a outros utilizadores',
	'mwoauth-grant-uploadeditmovefile' => 'Carregar, substituir e mover ficheiros',
	'mwoauth-grant-uploadfile' => 'Carregar novos ficheiros',
	'mwoauth-grant-viewdeleted' => 'Ver informação eliminada',
	'mwoauth-grant-viewmywatchlist' => 'Ver a sua lista de vigiados',
	'mwoauth-oauth-exception' => 'Ocorreu um erro no protocolo OAuth: $1',
	'right-mwoauthviewprivate' => 'Ver dados privados do OAuth',
	'mwoauth-listgrants-grant' => 'Conceder',
	'mwoauth-listgrants-rights' => 'Privilégios',
);

/** Brazilian Portuguese (português do Brasil)
 * @author Helder.wiki
 * @author Luckas
 */
$messages['pt-br'] = array(
	'mwoauth-prefs-managegrantslink' => 'Gerenciar $1 {{PLURAL:$1|aplicativo|aplicativos}} conectados',
	'mwoauth-form-button-approve' => 'Permitir',
	'mwoauth-form-button-cancel' => 'Cancelar',
	'mwoauth-grant-createaccount' => 'Criar contas',
);

/** tarandíne (tarandíne)
 * @author Joetaras
 */
$messages['roa-tara'] = array(
	'mwoauth-desc' => 'OAuth 1.0a API de autendicazione', # Fuzzy
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
	'mwoauth-consumer-wiki' => 'Uicchi applicabbile:', # Fuzzy
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

/** Russian (русский)
 * @author Illythr
 * @author Kaganer
 * @author Midnight Gambler
 * @author Okras
 * @author Putnik
 * @author Rubin
 * @author Rubin16
 * @author Yurik
 */
$messages['ru'] = array(
	'mwoauth-desc' => 'Позволяет использование OAuth 1.0a для API авторизации',
	'mwoauth-verified' => "Приложению теперь разрешён доступ к МедиаВики от вашего имени.

Для завершения процесса, предоставьте приложению это проверочное значение: ''' $1 '''",
	'mwoauth-missing-field' => 'Отсутствует значение для поля «$1»',
	'mwoauth-invalid-field' => 'Недопустимое значение для поля «$1»',
	'mwoauth-invalid-field-generic' => 'Недопустимое значение',
	'mwoauth-field-hidden' => '(эта информация скрыта)',
	'mwoauth-field-private' => '(эта информация является конфиденциальной)',
	'mwoauth-prefs-managegrants' => 'Подключенные приложения:',
	'mwoauth-prefs-managegrantslink' => 'Управление {{PLURAL:$1|$1 подключённым приложением|$1 подключёнными приложениями|1=подключённым приложением}}',
	'mwoauth-consumer-allwikis' => 'Все проекты на этом сайте',
	'mwoauth-consumer-name' => 'Название приложения:',
	'mwoauth-consumer-stage' => 'Текущее состояние:',
	'mwoauth-consumer-email' => 'Контактный адрес электронной почты:',
	'mwoauth-consumer-description' => 'Описание приложения:',
	'mwoauth-consumer-callbackurl' => 'URL-адрес «обратного вызова» OAuth:',
	'mwoauth-consumer-wiki' => 'Применимо к проекту:',
	'mwoauth-consumer-wiki-thiswiki' => 'Текущий проект ($1)',
	'mwoauth-consumer-wiki-other' => 'Конкретный проект',
	'mwoauth-consumer-restrictions' => 'Ограничения на использование:',
	'mwoauth-consumer-restrictions-json' => 'Ограничения на использование (JSON):',
	'mwoauth-consumer-reason' => 'Причина:',
	'mwoauth-consumer-email-unconfirmed' => 'Адрес электронной почты вашей учетной записи еще не был подтверждён.',
	'mwoauth-consumer-email-mismatched' => 'Указанный адрес электронной почты должен совпадать с адресом вашей учётной записи.',
	'mwoauthconsumerregistration-navigation' => 'Навигацияː',
	'mwoauthconsumerregistration-description' => 'Описание',
	'mwoauthconsumerregistration-email' => 'Контактный адрес электронной почты',
	'mwoauthconsumerregistration-stage' => 'Состояние',
	'mwoauthconsumerregistration-lastchange' => 'Последнее изменение',
	'mwoauthconsumerregistration-resetsecretkey' => 'Сбросить секретный ключ, установив новое значение',
	'mwoauthmanageconsumers-notloggedin' => 'Вы должны войти в систему для доступа к этой странице.',
	'mwoauthmanageconsumers-type' => 'Очереди:',
	'mwoauthmanageconsumers-showproposed' => 'Предлагаемые запросы',
	'mwoauthmanageconsumers-showrejected' => 'Отклонённые запросы',
	'mwoauthmanageconsumers-showexpired' => 'Устаревшие запросы',
	'mwoauthmanageconsumers-description' => 'Описание',
	'mwoauthmanageconsumers-email' => 'Контактный адрес электронной почты',
	'mwoauthmanageconsumers-lastchange' => 'Последнее изменение',
	'mwoauthmanageconsumers-action' => 'Изменить состояние:',
	'mwoauthmanageconsumers-reason' => 'Причина:',
	'mwoauthmanageconsumers-success-approved' => 'Запрос был одобрен.',
	'mwoauthmanageconsumers-success-rejected' => 'Запрос был отклонён.',
	'mwoauthlistconsumers-view' => 'подробности',
	'mwoauthlistconsumers-none' => 'Приложений по этим критериям не найдено.',
	'mwoauthlistconsumers-name' => 'Название приложения',
	'mwoauthlistconsumers-description' => 'Описание',
	'mwoauthlistconsumers-wiki' => 'Применимо к проекту',
	'mwoauthlistconsumers-callbackurl' => '«URL-адрес обратного вызова» OAuth:',
	'mwoauthlistconsumers-basicgrantsonly' => '(только базовый доступ)',
	'mwoauthlistconsumers-status' => 'Состояние',
	'mwoauth-consumer-stage-any' => 'любой',
	'oauthmanagemygrants' => 'Управление подключенными приложениями',
	'mwoauthmanagemygrants-text' => 'На этой странице перечислены все приложения, которые могут использовать вашу учетную запись. Область доступа каждого такого приложения ограничена разрешениями, которые вы ему предоставили для действий от вашего имени. Если вы предоставили приложению доступ к вашей учётной записи на различных родственных проектах, то вы можете просмотреть отдельные настройки для каждого такого проекта ниже.

Подключенные приложения получают доступ к вашей учетной записи с помощью протокола OAuth. <span class="plainlinks">([https://www.mediawiki.org/wiki/Special:MyLanguage/Help:OAuth Узнать больше о подключаемых приложениях])</span>',
	'mwoauthmanagemygrants-navigation' => 'Навигацияː',
	'mwoauthmanagemygrants-showlist' => 'Список подключенных приложений',
	'mwoauthmanagemygrants-none' => 'Ещё нет приложений, подключенных к вашей учётной записи.',
	'mwoauthmanagemygrants-description' => 'Описание',
	'mwoauthmanagemygrants-wikiallowed' => 'Допускается на проекте:',
	'mwoauthmanagemygrants-action' => 'Изменить состояние:',
	'mwoauth-invalid-authorization-title' => 'Ошибка авторизации OAuth',
	'mwoauth-form-button-approve' => 'Разрешить',
	'mwoauth-form-button-cancel' => 'Отменить',
	'mwoauth-error' => 'Ошибка подключения приложения',
	'mwoauth-grants-heading' => 'Запросить разрешения:',
	'mwoauth-grant-group-email' => 'Отправить письмо',
	'mwoauth-grant-blockusers' => 'Блокировать и разблокировать пользователей',
	'mwoauth-grant-createaccount' => 'Создавать учетные записи',
	'mwoauth-grant-createeditmovepage' => 'Создание, правка и переименование страниц',
	'mwoauth-grant-delete' => 'Удаление страниц, правок и записей журнала',
	'mwoauth-grant-editinterface' => 'Правка пространства имён MediaWiki и пользовательских CSS/JavaScript',
	'mwoauth-grant-editmycssjs' => 'Правка собственных пользовательских CSS/JavaScript',
	'mwoauth-grant-editmyoptions' => 'Правка собственных пользовательских настроек',
	'mwoauth-grant-editmywatchlist' => 'Редактировать ваш список наблюдения',
	'mwoauth-grant-editpage' => 'Редактировать существующие страницы',
	'mwoauth-grant-editprotected' => 'Редактировать защищенные страницы',
	'mwoauth-grant-protect' => 'Защищать и снимать защиту со страниц',
	'mwoauth-grant-sendemail' => 'Отправлять электронную почту другим участникам',
	'mwoauth-grant-uploadeditmovefile' => 'Загружать, заменять и переименовывать файлы',
	'mwoauth-grant-uploadfile' => 'Загрузить новые файлы',
	'mwoauth-grant-useoauth' => 'Основные права',
	'mwoauth-grant-viewdeleted' => 'Просмотреть удаленную информацию',
	'mwoauth-grant-viewmywatchlist' => 'Просмотреть свой список наблюдения',
	'mwoauth-oauth-exception' => 'Произошла ошибка в протоколе OAuth: $1',
	'mwoauth-listgrants-rights' => 'Права',
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
	'mwoauth-acceptance-cancelled' => 'Odločili ste se, da »$1« na dovolite dostopa do svojega računa. Oseba »$1« ne bo delovala, razen če ji dovolite dostop. Greste lahko nazaj na »$1« ali na[[Special:OAuthManageMyGrants|urejanje]] vaših povezanih aplikacij.',
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
	'mwoauth-prefs-managegrants' => 'Повезане апликације:',
	'mwoauthlistconsumers-view' => 'детаљи',
	'mwoauthlistconsumers-name' => 'Име апликације',
	'mwoauthlistconsumers-user' => 'Издавач',
	'mwoauthlistconsumers-description' => 'Опис',
	'mwoauthlistconsumers-status' => 'Статус',
	'oauthmanagemygrants' => 'Управљање повезаним апликацијама',
	'mwoauthmanagemygrants-showlist' => 'Списак повезаних аликација',
	'mwoauthmanagemygrants-none' => 'Нема апликација повезаних са вашим налогом.',
	'mwoauth-grant-uploadeditmovefile' => 'Отпремите, замените и преместите датотеке',
	'mwoauth-grant-useoauth' => 'Основна права',
);

/** Serbian (Latin script) (srpski (latinica)‎)
 * @author Milicevic01
 */
$messages['sr-el'] = array(
	'mwoauth-prefs-managegrants' => 'Povezane aplikacije:',
	'mwoauthlistconsumers-description' => 'Opis',
	'mwoauthlistconsumers-status' => 'Status',
	'oauthmanagemygrants' => 'Upravljanje povezanim aplikacijama',
	'mwoauthmanagemygrants-showlist' => 'Spisak povezanih alikacija',
	'mwoauthmanagemygrants-none' => 'Nema aplikacija povezanih sa vašim nalogom.',
);

/** Swedish (svenska)
 * @author Ainali
 * @author Eihpossophie
 * @author Jopparn
 * @author Lokal Profil
 * @author Skalman
 * @author WikiPhoenix
 */
$messages['sv'] = array(
	'mwoauth-desc' => 'Tillåter användning av OAuth 1.0a för API-tillstånd',
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
	'mwoauth-consumer-wiki' => 'Begränsa till projekt:',
	'mwoauth-consumer-wiki-thiswiki' => 'Aktuellt projekt ($1)',
	'mwoauth-consumer-wiki-other' => 'Specifikt projekt',
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
	'mwoauth-invalid-access-wrongwiki' => 'Konsumenten kan endast användas på projektet "$1".',
	'mwoauth-consumer-conflict' => 'Någon ändrat attributen för denna konsument när du tittade på den. Vänligen försök igen. Du kanske vill ta en titt på ändringsloggen.',
	'mwoauth-consumer-stage-proposed' => 'föreslagna',
	'mwoauth-consumer-stage-rejected' => 'avvisade',
	'mwoauth-consumer-stage-expired' => 'utgångna',
	'mwoauth-consumer-stage-approved' => 'godkända',
	'mwoauth-consumer-stage-disabled' => 'inaktiverade',
	'mwoauth-consumer-stage-suppressed' => 'undertryckta',
	'oauthconsumerregistration' => 'Registrering för OAuth-konsumenter',
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
	'oauthmanageconsumers' => 'Hantera OAuth-konsumenter',
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
	'mwoauthmanageconsumers-viewing' => '{{GENDER:$1|Användaren}} "$1"  tittar på denna konsument för tillfället',
	'mwoauthmanageconsumers-success-approved' => 'Begäran har godkänts.',
	'mwoauthmanageconsumers-success-rejected' => 'Begäran har avslagits.',
	'mwoauthmanageconsumers-success-disabled' => 'Konsumenten har inaktiverats.',
	'mwoauthmanageconsumers-success-reanable' => 'Konsumenten har återaktiverats.',
	'mwoauthmanageconsumers-search-name' => 'Konsument med detta namn',
	'mwoauthmanageconsumers-search-publisher' => 'Konsumenter av denna användare',
	'oauthlistconsumers' => 'Lista OAuth-applikationer',
	'mwoauthlistconsumers-legend' => 'Sök efter OAuth-applikationer',
	'mwoauthlistconsumers-view' => 'detaljer',
	'mwoauthlistconsumers-none' => 'Inga applikationer hittades som uppfyller detta kriterium.',
	'mwoauthlistconsumers-name' => 'Applikationsnamn',
	'mwoauthlistconsumers-version' => 'Konsumentversion',
	'mwoauthlistconsumers-user' => 'Utgivare',
	'mwoauthlistconsumers-description' => 'Beskrivning',
	'mwoauthlistconsumers-wiki' => 'Begränsa till projekt',
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
	'oauthmanagemygrants' => 'Hantera anslutna applikationer',
	'mwoauthmanagemygrants-text' => 'Denna sida listar alla applikationer som kan använda ditt konto. För varje sådan applikation är dess tillträde begränsat av de behörigheter vilka du auktoriserade när du valde att låta den agera åt dina vägnar. Om du separat auktoriserar en applikation att tillgå olika systerprojekt åt dina vägnar kommer du se separat konfiguration för varje sådant projekt nedan.

Ansluta applikationer kan få tillgång till ditt konto via OAuth-protokollet. <span class="plainlinks">([https://www.mediawiki.org/wiki/Special:MyLanguage/Help:OAuth Läs mer om ansluta applikationer])</span>',
	'mwoauthmanagemygrants-notloggedin' => 'Du måste vara inloggad för att komma åt denna sida.',
	'mwoauthmanagemygrants-navigation' => 'Navigering:',
	'mwoauthmanagemygrants-showlist' => 'Ansluten applikationslista',
	'mwoauthmanagemygrants-none' => 'Det finns inga applikationer anslutna till ditt konto.',
	'mwoauthmanagemygrants-user' => 'Utgivare:',
	'mwoauthmanagemygrants-description' => 'Beskrivning',
	'mwoauthmanagemygrants-wikiallowed' => 'Tillåten på projekt:',
	'mwoauthmanagemygrants-grants' => 'Tillämpliga stipendier',
	'mwoauthmanagemygrants-grantsallowed' => 'Stipendier tillåtna',
	'mwoauthmanagemygrants-applicablegrantsallowed' => 'Tillämpliga bidrag accepteras:',
	'mwoauthmanagemygrants-review' => 'hantera åtkomst',
	'mwoauthmanagemygrants-revoke' => 'återkalla åtkomst',
	'mwoauthmanagemygrants-grantaccept' => 'Beviljas',
	'mwoauthmanagemygrants-update-text' => 'Använd formuläret nedan för att ändra de behörigheter som beviljats för en applikation att agera åt dina vägnar.
* Om du separat auktoriserat en applikation för att tillgå olika systerprojekt åt dina vägnar har du separata konfigurationer för varje sådant projekt för den applikationen.', # Fuzzy
	'mwoauthmanagemygrants-revoke-text' => 'Använd formuläret nedan för att återkalla åtkomst för en applikation (OAuth konsument) att agera åt dina vägnar.
* Om du separat har auktoriserat en applikation för att få åtkomst till ett annat systerprojekt åt dina vägnar så kommer du att ha separata konfigurationer för varje enskilt projekt för den applikationen.
* Om du helt vill återkalla åtkomst till en applikation, se till att återkalla den från alla projekt där du accepterat den.', # Fuzzy
	'mwoauthmanagemygrants-confirm-legend' => 'Hantera ansluten applikation',
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
	'mwoauth-bad-request-invalid-action-contact' => 'Tyvärr, någonting gick fel. Du måste [$1 kontakta] applikationens upphovsman för att få hjälp med detta.

<span class="plainlinks mw-mwoautherror-details">Okänd webbadress, [https://www.mediawiki.org/wiki/Help:OAuth/Errors#E003 E003]</span>',
	'mwoauthdatastore-access-token-not-found' => 'Inget godkänt bidrag har hittats för den auktoriseringstoken.',
	'mwoauthdatastore-request-token-not-found' => 'Ingen begäran hittades för den token.', # Fuzzy
	'mwoauthdatastore-bad-token' => 'Ingen token hittades som matchade din begäran.',
	'mwoauthdatastore-bad-verifier' => 'Verifikationskoden som givits var inte giltig.',
	'mwoauthdatastore-invalid-token-type' => 'Den begärda tokentypen är ogiltig.',
	'mwoauthgrants-general-error' => 'Det uppstod ett fel i din OAuthbegäran.',
	'mwoauthserver-bad-consumer' => 'Ingen godkänd konsument hittas för den nyckel du angav.', # Fuzzy
	'mwoauthserver-insufficient-rights' => 'Du har inte tillräcklig behörighet för att utföra denna åtgärd.', # Fuzzy
	'mwoauthserver-invalid-request-token' => 'Ogiltig token i din begäran.',
	'mwoauth-invalid-authorization-title' => 'OAuth auktoriseringsfel',
	'mwoauth-invalid-authorization' => 'Auktoriseringsrubriker i din begäran är inte giltiga: $1',
	'mwoauth-invalid-authorization-wrong-wiki' => 'De auktoriserade rubrikerna i din begäran är inte giltiga för $1',
	'mwoauth-invalid-authorization-invalid-user' => 'Auktoriseringsrubrikerna i din begäran är för användare som inte existerar här',
	'mwoauth-invalid-authorization-wrong-user' => 'Auktoriseringsrubrikerna i din begäran är för en annan användare',
	'mwoauth-invalid-authorization-not-approved' => 'Auktoriseringsrubrikerna i din begäran är för en OAuthkonsument som för närvarande inte är godkänd', # Fuzzy
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
	'mwoauth-error' => 'OAuth error', # Fuzzy
	'mwoauth-grants-heading' => 'Begärda tillstånd:',
	'mwoauth-grants-nogrants' => 'Ansökan har inte begärt något tillstånd.',
	'mwoauth-acceptance-cancelled' => 'Du har avbrutit denna begäran att auktorisera en OAuthkonsument att agera åt dina vägnar.', # Fuzzy
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
	'mwoauth-grant-editmyoptions' => 'Redigera dina egna användarinställningar',
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
	'mwoauth-listgrants-rights' => 'Rättigheter',
);

/** Turkish (Türkçe)
 * @author Incelemeelemani
 * @author Rapsar
 */
$messages['tr'] = array(
	'mwoauth-desc' => 'OAuth 1.0a kullanımı için API yetkilendirme kullanımı',
	'mwoauth-prefs-managegrants' => 'Bağlı uygulamalar:',
	'mwoauth-prefs-managegrantslink' => '$1 bağlı {{PLURAL:$1|uygulamayı|uygulamaları}} yönet',
	'mwoauth-consumer-name' => 'Uygulama adı:',
	'mwoauth-consumer-user' => 'Yayıncı:',
	'mwoauth-consumer-stage' => 'Geçerli durumu:',
	'mwoauth-consumer-stage-proposed' => 'önerilen',
	'mwoauth-consumer-stage-rejected' => 'reddedilen',
	'mwoauth-consumer-stage-expired' => 'süresi dolmuş',
	'mwoauth-consumer-stage-approved' => 'onaylı',
	'mwoauth-consumer-stage-disabled' => 'devre dışı',
	'mwoauth-consumer-stage-suppressed' => 'bastırılmış',
	'mwoauthconsumerregistration-navigation' => 'Navigasyon:',
	'oauthlistconsumers' => 'Yetkilendirilen uygulamalar listesi',
	'mwoauthlistconsumers-legend' => 'Yetkilendirilen uygulamalara göz atın',
	'oauthmanagemygrants' => 'Bağlı uygulamaları yönet',
	'mwoauthmanagemygrants-text' => 'Bu sayfada hesabınızda kullanılan uygulamaların listesi bulunmaktadır. Bu tür uygulamalar, söz konusu uygulamaya izin verdiğiniz ölçüde sizin adınıza hareket etmeye yetkilidir. Eğer bir uygulama sizin adınıza farklı kardeş projelere erişmek için yetkilendirildiği taktirde, uygulama ile ilgili bölümün altında diğer projeler için yetkilendirme ayarlarını da görebilirsiniz.

Bağlı uygulamalar OAuth protokolünü kullanarak, hesaplarınıza erişim sağlar. <span class="plainlinks">([https://www.mediawiki.org/wiki/Special:MyLanguage/Help:OAuth Bağlı uygulamalar hakkında daha fazla bilgi alın])</span>',
	'mwoauthmanagemygrants-showlist' => 'Bağlı uygulama listesi',
	'mwoauthmanagemygrants-none' => 'Hesabınıza bağlı herhangi bir uygulama yoktur.',
	'mwoauthmanagemygrants-confirm-legend' => 'Bağlı uygulama yönetimi',
	'mwoauth-form-button-approve' => 'Evet, izin ver', # Fuzzy
);

/** Ukrainian (українська)
 * @author Andriykopanytsia
 * @author Base
 * @author Ата
 */
$messages['uk'] = array(
	'mwoauth-desc' => 'Дозволяє використовувати OAuth 1.0a для API авторизації',
	'mwoauth-verified' => "Цій програмі зараз дозволений доступ до Медіавікі від вашого імені.

Для завершення процесу надайте це перевірене значення програмі: '''$1'''",
	'mwoauth-missing-field' => 'Відсутнє значення для поля "$1"',
	'mwoauth-invalid-field' => 'Неприпустиме значення для поля "$1"',
	'mwoauth-invalid-field-generic' => 'Надано неприпустиме значення',
	'mwoauth-field-hidden' => '(ця інформація прихована)',
	'mwoauth-field-private' => '(ця інформація є конфіденційною)',
	'mwoauth-grant-generic' => 'Пучок прав "$1"',
	'mwoauth-prefs-managegrants' => 'Підключені додатки:',
	'mwoauth-prefs-managegrantslink' => "Управляти $1 {{PLURAL:$1|1=з'єднаною програмою|з'єднаними програмами}}",
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
	'mwoauth-consumer-wiki' => 'Придатний проект:',
	'mwoauth-consumer-wiki-thiswiki' => 'Поточний проект ($1)',
	'mwoauth-consumer-wiki-other' => 'Конкретний проект',
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
	'mwoauth-invalid-access-wrongwiki' => 'Споживач може використовуватися тільки на проекті "$1".',
	'mwoauth-consumer-conflict' => 'Хтось змінив параметри даного споживача, якого ви дивилися. Будь ласка, спробуйте ще раз. Ви можете перевірити журнал змін.',
	'mwoauth-consumer-grantshelp' => 'Кожен грант надає доступ до перерахованих прав користувача, які вже має обліковий запис користувача. Подивіться на [[Special:OAuth/grants|таблицю грантів]] для отримання додаткової інформації.',
	'mwoauth-consumer-stage-proposed' => 'запропоновано',
	'mwoauth-consumer-stage-rejected' => 'відхилено',
	'mwoauth-consumer-stage-expired' => 'застаріле',
	'mwoauth-consumer-stage-approved' => 'затверджено',
	'mwoauth-consumer-stage-disabled' => 'вимкнено',
	'mwoauth-consumer-stage-suppressed' => 'пригнічено',
	'oauthconsumerregistration' => 'Реєстрація покупця OAuth',
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
* Ви можете використовувати ідентифікатор проекту, аби обмежувати споживача в одному проекті на цьому сайті (використовуйте "*" для всіх проектів).
* Надана адреса електронної пошти повинна збігатися з вашим обліковим записом (який повинен бути підтвердженим).',
	'mwoauthconsumerregistration-update-text' => 'Використовуйте форму нижче, щоб оновити аспекти споживача OAuth, які ви контролюєте.

Всі значення тут будуть переписувати будь-які попередні. Не залишайте порожні поля, якщо ви не маєте наміру вилучити ці значення.',
	'mwoauthconsumerregistration-maintext' => 'Ця сторінка дозволяє розробникам пропонувати та оновлювати клієнтські програми OAuth у реєстрі сайту.

Звідси ви можете:
* [[Special:OAuthConsumerRegistration/propose|Запитувати маркер для нового клієнта]].
* [[Special:OAuthConsumerRegistration/list|Управляти вашими наявними клієнтами]].

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
	'oauthmanageconsumers' => 'Управління споживачами OAuth',
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
	'mwoauthmanageconsumers-viewing' => '{{GENDER:$1|Користувач|Користувачка}} "$1" в даний час переглядає цього споживача',
	'mwoauthmanageconsumers-success-approved' => 'Запит був схвалений.',
	'mwoauthmanageconsumers-success-rejected' => 'Запит був відхилений.',
	'mwoauthmanageconsumers-success-disabled' => 'Споживач вже вимкнений.',
	'mwoauthmanageconsumers-success-reanable' => 'Споживач вже повторно увімкнений.',
	'mwoauthmanageconsumers-search-name' => 'споживачі з цим іменем',
	'mwoauthmanageconsumers-search-publisher' => 'споживачі даного користувача',
	'oauthlistconsumers' => 'Список програм OAuth',
	'mwoauthlistconsumers-legend' => 'Перегляд програм OAuth',
	'mwoauthlistconsumers-view' => 'подробиці',
	'mwoauthlistconsumers-none' => 'Не знайдено жодної програми за цим критерієм.',
	'mwoauthlistconsumers-name' => 'Назва програми',
	'mwoauthlistconsumers-version' => 'Споживча версія',
	'mwoauthlistconsumers-user' => 'Видавець',
	'mwoauthlistconsumers-description' => 'Опис',
	'mwoauthlistconsumers-wiki' => 'Придатний проект',
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
	'oauthmanagemygrants' => 'Управління підключеними програмами',
	'mwoauthmanagemygrants-text' => 'На цій сторінці перераховані всі програми, які можуть використовувати ваш обліковий запис. Для будь-яких таких програм сферу їхнього доступу обмежена дозволами, наданими програмі, коли ви уповноважили її діяти від вашого імені. Якщо ви окремо уповноважили програму для доступу до різних "сестринських" проектів від вашого імені, то ви побачите окремі налаштування для кожного такого проекту, нижче.',
	'mwoauthmanagemygrants-notloggedin' => 'Ви повинні увійти в систему для доступу до цієї сторінки.',
	'mwoauthmanagemygrants-navigation' => 'Навігація:',
	'mwoauthmanagemygrants-showlist' => 'Список підключених програм',
	'mwoauthmanagemygrants-none' => 'Жодна програма не підключена до вашого облікового запису.',
	'mwoauthmanagemygrants-user' => 'Видавець:',
	'mwoauthmanagemygrants-description' => 'Опис',
	'mwoauthmanagemygrants-wikiallowed' => 'Дозволено на проекті:',
	'mwoauthmanagemygrants-grants' => 'Застосовні ґранти',
	'mwoauthmanagemygrants-grantsallowed' => 'Ґранти, які дозволили',
	'mwoauthmanagemygrants-applicablegrantsallowed' => 'Застосовні гранти дозволено:',
	'mwoauthmanagemygrants-review' => 'управління доступом',
	'mwoauthmanagemygrants-revoke' => 'скасувати доступ',
	'mwoauthmanagemygrants-grantaccept' => 'Надано',
	'mwoauthmanagemygrants-update-text' => 'Використовуйте форму нижче, щоб змінювати дозволи надані програмі діяти від Вашого імені.',
	'mwoauthmanagemygrants-revoke-text' => 'Використовуйте форму нижче, щоб скасувати доступ для програми, щоб діяти від вашого імені.',
	'mwoauthmanagemygrants-confirm-legend' => 'Управління підключеною програмою',
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
	'mwoauth-bad-request-missing-params' => 'На жаль, щось пішло не так при налаштуванні цієї підключеної програми. <span class="plainlinks">[https://www.mediawiki.org/wiki/Special:MyLanguage/Help:OAuth Contact support]</span> для отримання допомоги по виправленню.

<span class="plainlinks mw-mwoautherror-details">Пропущені параметри OAuth, [https://www.mediawiki.org/wiki/Help:OAuth/Errors#E001 E001]</span>',
	'mwoauth-bad-request-invalid-action' => 'Вибачте, щось пішло не так. Вам потрібно зв\'язатися з  автором програми для допомоги з цього питання.

<span class="plainlinks mw-mwoautherror-details">Unknown URL, [https://www.mediawiki.org/wiki/Help:OAuth/Errors#E002 E002]</span>',
	'mwoauth-bad-request-invalid-action-contact' => 'Вибачте, щось пішло не так. Вам потрібно зв\'язатися з  автором програми [$1]  для допомоги з цього питання.

<span class="plainlinks mw-mwoautherror-details">Unknown URL, [https://www.mediawiki.org/wiki/Help:OAuth/Errors#E003 E003]</span>',
	'mwoauthdatastore-access-token-not-found' => 'Не знайдено схваленого ґранту для цього маркера авторизації.',
	'mwoauthdatastore-request-token-not-found' => 'На жаль, щось пішло невірно при підключенні цієї програми. Поверніться назад та спробуйте підключити ваш профіль знову або зв\'яжіться з автором програми.

<span class="plainlinks mw-mwoautherror-details">Маркер OAuth не знайдений, [https://www.mediawiki.org/wiki/Help:OAuth/Errors#E004 E004]</span>',
	'mwoauthdatastore-bad-token' => 'Не знайдено маркера, відповідного вашому запиту.',
	'mwoauthdatastore-bad-verifier' => 'Наданий код підтвердження недійсний.',
	'mwoauthdatastore-invalid-token-type' => 'Неприпустимий маркер запитаного типу.',
	'mwoauthgrants-general-error' => 'Помилка у вашому запиті на OAuth.',
	'mwoauthserver-bad-consumer' => '$1 більше не схвалена як підключена програма, [$2 зв\'яжіться] з автором програми за допомогою.

<span class="plainlinks mw-mwoautherror-details">Підключена програма OAuth  не схвалена, [https://www.mediawiki.org/wiki/Help:OAuth/Errors#E005 E005]</span>',
	'mwoauthserver-bad-consumer-key' => 'На жаль, щось пішло не так при підключенні цієї програми.

<span class="plainlinks mw-mwoautherror-details">Невідомий ключ OAuth, [https://www.mediawiki.org/wiki/Help:OAuth/Errors#E006 E006]</span>',
	'mwoauthserver-insufficient-rights' => 'Вашому профілю не дозволено використовувати підключені програми, зв\'яжіться з адміністратором вашого сайту для з\'ясування причин.

<span class="plainlinks mw-mwoautherror-details">Недостатні права користувача OAuth, [https://www.mediawiki.org/wiki/Help:OAuth/Errors#E007 E007]</span>',
	'mwoauthserver-invalid-request-token' => 'Неприпустимий маркер у вашому запиті.',
	'mwoauthserver-invalid-user' => 'Для використання підключених програм на сайті вам потрібно мати профіль на всі проекти. Коли у вас є обліковий запис на усі проекти, можете спробувати знову підключити $1.

<span class="plainlinks mw-mwoautherror-details">Необхідний єдиний вхід, [https://www.mediawiki.org/wiki/Help:OAuth/Errors#E008 E008]</span>',
	'mwoauth-invalid-authorization-title' => 'Помилка авторизації OAuth',
	'mwoauth-invalid-authorization' => 'Неприпустимі заголовки авторизації у вашому запиті:$1',
	'mwoauth-invalid-authorization-wrong-wiki' => 'Неприпустимі заголовки авторизації у вашому запиті для $1',
	'mwoauth-invalid-authorization-invalid-user' => 'Не існують заголовки авторизації у вашому запиті для користувача',
	'mwoauth-invalid-authorization-wrong-user' => 'Заголовки авторизації у вашому запиті призначені для іншого користувача',
	'mwoauth-invalid-authorization-not-approved' => "Програма, якою ви намагаєтеся з'єднатися, схоже, налаштована неправильно, зверніться до автора $1.",
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
	'mwoauth-error' => 'Помилка підключення застосунку',
	'mwoauth-grants-heading' => 'Потрібні дозволи:',
	'mwoauth-grants-nogrants' => 'Програма не вимагає жодних дозволів.',
	'mwoauth-acceptance-cancelled' => 'Ви вже вибрали не надавати $1 доступу до вашого профілю. $1 не буде працювати без вашого дозволу. Ви можете повернутися до $1 або до [[Special:OAuthManageMyGrants|управління]] вашими підключеними програмами.',
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
	'mwoauth-grant-editmyoptions' => 'Редагувати свої власні налаштування користувача',
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
	'mwoauth-listgrantrights-summary' => "Нижче наведено список OAuth грантів, з їхнім пов'язаним доступом до прав користувача. Користувачі можуть дозволити програмам використовувати свій обліковий запис, але з обмеженими правами на основі грантів користувача наданих до заяви. Програма діє від імені користувача, однак насправді не може використовувати права, які користувач не має.
Там може бути [[{{MediaWiki:Listgrouprights-helppage}}|додаткова інформація]] про індивідуальні права.",
	'mwoauth-listgrants-grant' => 'Грант',
	'mwoauth-listgrants-rights' => 'Права',
);

/** Uzbek (oʻzbekcha)
 * @author Sociologist
 */
$messages['uz'] = array(
	'mwoauth-prefs-managegrants' => 'Yoqilgan dasturlar:',
);

/** Vietnamese (Tiếng Việt)
 * @author Minh Nguyen
 * @author Withoutaname
 */
$messages['vi'] = array(
	'mwoauth-desc' => 'Cho phép sử dụng OAuth 1.0a để xác minh khi truy cập API',
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
	'mwoauth-consumer-allwikis' => 'Tất cả các dự án trên mạng này',
	'mwoauth-consumer-name' => 'Tên ứng dụng:',
	'mwoauth-consumer-user' => 'Nhà xuất bản:',
	'mwoauth-consumer-stage' => 'Trạng thái hiện tại:',
	'mwoauth-consumer-email' => 'Địa chỉ thư điện tử liên lạc:',
	'mwoauth-consumer-description' => 'Miêu tả ứng dụng:',
	'mwoauth-consumer-callbackurl' => 'URL “gọi lại” OAuth:',
	'mwoauth-consumer-grantsneeded' => 'Các quyền có liên quan:',
	'mwoauth-consumer-wiki' => 'Dự án có liên quan:',
	'mwoauth-consumer-wiki-thiswiki' => 'Dự án hiện tại ($1)',
	'mwoauth-consumer-wiki-other' => 'Dự án cụ thể',
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
	'mwoauthlistconsumers-wiki' => 'Dự án có liên quan',
	'mwoauthlistconsumers-callbackurl' => '“URL gọi lại” OAuth',
	'mwoauthlistconsumers-basicgrantsonly' => '(chỉ truy cập cơ bản)',
	'mwoauthlistconsumers-status' => 'Trạng thái',
	'mwoauth-consumer-stage-any' => 'bất cứ',
	'mwoauthlistconsumers-status-proposed' => 'đề xuất',
	'mwoauthlistconsumers-status-approved' => 'chấp nhận',
	'mwoauthlistconsumers-status-disabled' => 'tắt',
	'mwoauthlistconsumers-status-rejected' => 'từ chối',
	'mwoauthlistconsumers-status-expired' => 'hết hạn',
	'oauthmanagemygrants' => 'Quản lý các ứng dụng kết nối',
	'mwoauthmanagemygrants-notloggedin' => 'Bạn phải đăng nhập để truy cấp trang này.',
	'mwoauthmanagemygrants-navigation' => 'Điều hướng:',
	'mwoauthmanagemygrants-showlist' => 'Danh sách các ứng dụng kết nối',
	'mwoauthmanagemygrants-none' => 'Không có ứng dụng nào được kết nối với tài khoản của bạn.',
	'mwoauthmanagemygrants-user' => 'Nhà xuất bản:',
	'mwoauthmanagemygrants-description' => 'Miêu tả',
	'mwoauthmanagemygrants-wikiallowed' => 'Được cho phép trong dự án:',
	'mwoauthmanagemygrants-grants' => 'Các quyền có liên quan',
	'mwoauthmanagemygrants-grantsallowed' => 'Các quyền được cấp',
	'mwoauthmanagemygrants-applicablegrantsallowed' => 'Các quyền được cấp có liên quan:',
	'mwoauthmanagemygrants-review' => 'quản lý truy cập',
	'mwoauthmanagemygrants-revoke' => 'thu hồi quyền truy cập',
	'mwoauthmanagemygrants-grantaccept' => 'Cấp quyền',
	'mwoauthmanagemygrants-update' => 'Cập nhật các dấu hiệu được cấp',
	'mwoauthmanagemygrants-renounce' => 'Rút quyền',
	'mwoauthmanagemygrants-action' => 'Thay đổi trạng thái:',
	'mwoauthmanagemygrants-confirm-submit' => 'Cập nhật trạng thái của dấu hiệu truy cập',
	'mwoauthdatastore-request-token-not-found' => 'Rất tiếc, có trục trặc khi kết nối với ứng dụng này.
Hãy quay lại và thử kết nối với tài khoản của bạn lần nữa hoặc liên lạc với nhà phát triển của ứng dụng.

<span class="plainlinks mw-mwoautherror-details">Không tìm thấy dấu hiệu OAuth, [https://www.mediawiki.org/wiki/Help:OAuth/Errors#E004 E004]</span>',
	'mwoauthdatastore-bad-token' => 'Không tìm thấy dấu hiệu ứng với yêu cầu của bạn.',
	'mwoauthdatastore-bad-verifier' => 'Mã xác minh được cung cấp là không hợp lệ.',
	'mwoauthdatastore-invalid-token-type' => 'Đã yêu cầu kiểu dấu hiệu không hợp lệ.',
	'mwoauthgrants-general-error' => 'Có lỗi trong yêu cầu OAuth của bạn.',
	'mwoauthserver-insufficient-rights' => 'Bạn không có đủ quyền để thực hiện thao tác này.

Tài khoản của bạn không được phép sử dụng tính năng Ứng dụng Kết nối. Hãy liên lạc với quản lý viên trang Web của bạn để tìm hiểu lý do tại sao.

<span class="plainlinks mw-mwoautherror-details">Người dùng không đủ quyền OAuth, [https://www.mediawiki.org/wiki/Help:OAuth/Errors#E007 E007]</span>',
	'mwoauthserver-invalid-request-token' => 'Dấu hiệu không hợp lệ trong yêu cầu của bạn.',
	'mwoauth-invalid-authorization-title' => 'Lỗi xác minh OAuth',
	'mwoauth-form-privacypolicy-link' => '[[{{ns:Project}}:Privacy policy|Quy định quyền riêng tư]]',
	'mwoauth-form-button-approve' => 'Cho phép',
	'mwoauth-form-button-cancel' => 'Hủy bỏ',
	'mwoauth-error' => 'Lỗi Kết nối với Ứng dụng',
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
	'mwoauth-grant-editmyoptions' => 'Sửa đổi tùy chọn của bạn',
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
	'mwoauth-listgrants-rights' => 'Quyền',
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
 * @author Cwek
 * @author Hzy980512
 * @author Liuxinyu970226
 * @author Qiyue2001
 * @author Shirayuki
 * @author Shizhao
 * @author Xiaomingyan
 * @author Yfdyh000
 */
$messages['zh-hans'] = array(
	'mwoauth-desc' => '允许使用OAuth 1.0a用作API授权',
	'mwoauth-verified' => "现在该应用以允许以您的名义访问MediaWiki。

要完成这个过程，请为该应用提供这个校验值：'''$1'''",
	'mwoauth-missing-field' => '缺少值为" $1 "字段',
	'mwoauth-invalid-field' => '为"$1"字段提供的值无效',
	'mwoauth-invalid-field-generic' => '提供的值无效',
	'mwoauth-field-hidden' => '（这些信息已被隐藏）',
	'mwoauth-field-private' => '（这些信息是不公开的）',
	'mwoauth-grant-generic' => '" $1 "权利束',
	'mwoauth-prefs-managegrants' => '已连接应用：',
	'mwoauth-prefs-managegrantslink' => '管理$1个已连接的应用程序',
	'mwoauth-consumer-allwikis' => '本站的所有项目',
	'mwoauth-consumer-key' => 'Consumer key:',
	'mwoauth-consumer-name' => '应用名称：',
	'mwoauth-consumer-version' => 'Consumer版本:',
	'mwoauth-consumer-user' => '发布者：',
	'mwoauth-consumer-stage' => '当前状态：',
	'mwoauth-consumer-email' => '联系人电子邮件地址：',
	'mwoauth-consumer-description' => '应用说明：',
	'mwoauth-consumer-callbackurl' => 'OAuth"回调"URL：',
	'mwoauth-consumer-grantsneeded' => '应用授权：',
	'mwoauth-consumer-required-grant' => '适用于最终用户',
	'mwoauth-consumer-wiki' => '适用项目：',
	'mwoauth-consumer-wiki-thiswiki' => '当前项目 ( $1 )',
	'mwoauth-consumer-wiki-other' => '具体项目',
	'mwoauth-consumer-restrictions' => '使用限制：',
	'mwoauth-consumer-restrictions-json' => '使用限制(JSON) ：',
	'mwoauth-consumer-rsakey' => 'RSA公钥：',
	'mwoauth-consumer-secretkey' => '最终用户的私密令牌：',
	'mwoauth-consumer-accesstoken' => '访问令牌：',
	'mwoauth-consumer-reason' => '原因：',
	'mwoauth-consumer-email-unconfirmed' => '您的帐户的电子邮件地址尚未得到确认。',
	'mwoauth-consumer-email-mismatched' => '提供的电子邮件地址必须与您的帐户相匹配。',
	'mwoauth-consumer-alreadyexists' => '最终用户与此名称/版本/发布者的组合已经存在',
	'mwoauth-consumer-stage-proposed' => '建议',
	'mwoauth-consumer-stage-rejected' => '拒绝',
	'mwoauth-consumer-stage-expired' => '过期',
	'mwoauth-consumer-stage-approved' => '批准',
	'mwoauth-consumer-stage-disabled' => '禁用',
	'mwoauth-consumer-stage-suppressed' => '压制',
	'mwoauthconsumerregistration-notloggedin' => '您必须登录后才能访问此页。',
	'mwoauthconsumerregistration-navigation' => '导航：',
	'mwoauthconsumerregistration-main' => '主要',
	'mwoauthconsumerregistration-propose-submit' => '提议消费者',
	'mwoauthconsumerregistration-update-submit' => '更新消费者',
	'mwoauthconsumerregistration-none' => '您不能控制任何OAuth消费者。',
	'mwoauthconsumerregistration-name' => '消费者',
	'mwoauthconsumerregistration-user' => '发布者',
	'mwoauthconsumerregistration-description' => '说明',
	'mwoauthconsumerregistration-email' => '联系人电子邮件',
	'mwoauthconsumerregistration-consumerkey' => '消费者密钥',
	'mwoauthconsumerregistration-stage' => '状态',
	'mwoauthconsumerregistration-lastchange' => '最后更改',
	'mwoauthconsumerregistration-manage' => '管理',
	'mwoauthconsumerregistration-resetsecretkey' => '将密钥重置为一个新值',
	'oauthmanageconsumers' => '管理OAuth消费者',
	'mwoauthmanageconsumers-notloggedin' => '您必须登录后才能访问此页。',
	'mwoauthmanageconsumers-type' => '队列：',
	'mwoauthmanageconsumers-showproposed' => '拟议的请求',
	'mwoauthmanageconsumers-showrejected' => '被拒绝的请求',
	'mwoauthmanageconsumers-showexpired' => '过期的请求',
	'mwoauthmanageconsumers-main' => '主要',
	'mwoauthmanageconsumers-name' => '消费者',
	'mwoauthmanageconsumers-user' => '发布者',
	'mwoauthmanageconsumers-description' => '说明',
	'mwoauthmanageconsumers-email' => '联系邮件',
	'mwoauthmanageconsumers-consumerkey' => '消费者密钥',
	'mwoauthmanageconsumers-lastchange' => '最近更新',
	'mwoauthmanageconsumers-review' => '审查/管理',
	'mwoauthmanageconsumers-confirm-legend' => '管理OAuth消费者',
	'mwoauthmanageconsumers-action' => '更改状态：',
	'mwoauthmanageconsumers-approve' => '已批准',
	'mwoauthmanageconsumers-reject' => '已回绝',
	'mwoauthmanageconsumers-rsuppress' => '已回绝并禁止',
	'mwoauthmanageconsumers-disable' => '已禁用',
	'mwoauthmanageconsumers-dsuppress' => '已禁用并禁止',
	'mwoauthmanageconsumers-reenable' => '已批准',
	'mwoauthmanageconsumers-reason' => '原因：',
	'mwoauthmanageconsumers-confirm-submit' => '更新消费者状态',
	'mwoauthmanageconsumers-success-approved' => '请求已批准。',
	'mwoauthmanageconsumers-success-rejected' => '请求已被拒绝。',
	'oauthlistconsumers' => '列出OAuth应用程序',
	'mwoauthlistconsumers-legend' => '浏览OAuth应用程序',
	'mwoauthlistconsumers-view' => '详情',
	'mwoauthlistconsumers-name' => '应用程序名称',
	'mwoauthlistconsumers-version' => '消费者版本',
	'mwoauthlistconsumers-user' => '发布者',
	'mwoauthlistconsumers-description' => '描述',
	'mwoauthlistconsumers-callbackurl' => 'OAuth“回调URL”',
	'mwoauthlistconsumers-basicgrantsonly' => '（仅适用于基本访问）',
	'mwoauthlistconsumers-status' => '状态',
	'mwoauth-consumer-stage-any' => '任何',
	'mwoauthlistconsumers-status-proposed' => '建议',
	'mwoauthlistconsumers-status-approved' => '批准',
	'mwoauthlistconsumers-status-disabled' => '停用',
	'mwoauthlistconsumers-status-rejected' => '拒绝',
	'mwoauthlistconsumers-status-expired' => '已过期',
	'oauthmanagemygrants' => '管理已连接的应用程序',
	'mwoauthmanagemygrants-text' => '此页面列出了可以使用您的账户的所有应用程序。这些应用程序的可访问范围取决于您授予这些应用程序可代表您操作时所允许的限制条件。如果您单独授予一个应用程序可代表您访问不同的姊妹项目，那么您将会在下面看到每个项目的单独配置。

已连接的应用程序将通过 OAuth 协议访问您的账户。<span class="plainlinks">([https://www.mediawiki.org/wiki/Special:MyLanguage/Help:OAuth详细了解有关已连接的应用程序])</span>',
	'mwoauthmanagemygrants-notloggedin' => '您必须登录后才能访问此页。',
	'mwoauthmanagemygrants-navigation' => '导航：',
	'mwoauthmanagemygrants-showlist' => '已连接的应用程序列表',
	'mwoauthmanagemygrants-none' => '还没有应用程序连接到你的账户。',
	'mwoauthmanagemygrants-user' => '发布者：',
	'mwoauthmanagemygrants-description' => '描述',
	'mwoauthmanagemygrants-wikiallowed' => '允许在项目上使用：',
	'mwoauthmanagemygrants-grants' => '应用授权：',
	'mwoauthmanagemygrants-grantsallowed' => '允许授权',
	'mwoauthmanagemygrants-review' => '管理访问',
	'mwoauthmanagemygrants-revoke' => '移除授权',
	'mwoauthmanagemygrants-grantaccept' => '授权',
	'mwoauthmanagemygrants-update' => '更新补助',
	'mwoauthmanagemygrants-renounce' => '取消授权',
	'mwoauthmanagemygrants-action' => '更新状态：',
	'mwoauthmanagemygrants-confirm-submit' => '更新授权令牌状态',
	'mwoauthmanagemygrants-success-update' => '已更新此应用的授权令牌。',
	'mwoauthmanagemygrants-success-renounce' => '已删除此应用的授权令牌。',
	'mwoauthconsumer-consumer-logpage' => 'OAuth消费者日志',
	'mwoauthserver-invalid-request-token' => '您的申请中有无效令牌。',
	'mwoauth-invalid-authorization-title' => 'OAuth认证错误',
	'mwoauth-form-description-onewiki' => "您好$1，

'''$2'''很想关注您在''$4''的各种琐事：

$5",
	'mwoauth-form-privacypolicy-link' => '[[{{ns:Project}}:Privacy policy|隐私政策]]',
	'mwoauth-form-button-approve' => '允许',
	'mwoauth-form-button-cancel' => '取消',
	'mwoauth-error' => '应用程序连接错误',
	'mwoauth-grants-heading' => '已申请权限：',
	'mwoauth-grant-group-email' => '发送电邮',
	'mwoauth-grant-group-customization' => '自定义与设置',
	'mwoauth-grant-blockusers' => '封禁与解封用户',
	'mwoauth-grant-createaccount' => '注册账户',
	'mwoauth-grant-createeditmovepage' => '创建、编辑与移动页面',
	'mwoauth-grant-delete' => '删除页面、修改和日志记录',
	'mwoauth-grant-editmycssjs' => '编辑你自己的用户CSS/JS',
	'mwoauth-grant-editmyoptions' => '编辑您自己的用户首选项',
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
	'mwoauth-listgrants-rights' => '权限',
);
