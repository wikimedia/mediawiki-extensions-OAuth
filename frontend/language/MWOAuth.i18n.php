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

	'mwoauth-missing-field' => 'Missing value for "$1" field',
	'mwoauth-invalid-field' => 'Invalid value provided for "$1" field',

	'mwoauth-field-hidden' => '(this information is hidden)',
	'mwoauth-field-private' => '(this information is private)',

	'mwoauth-grant-generic' => '"$1" rights bundle',
	'mwoauth-prefs-managegrants' => 'OAuth consumer access:',
	'mwoauth-prefs-managegrantslink' => 'manage grants on behalf of this account',

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
	'mwoauth-consumer-restrictions' => 'Usage restrictions:',
	'mwoauth-consumer-restrictions-json' => 'Usage restrictions (JSON):',
	'mwoauth-consumer-rsakey' => 'Public RSA key:',
	'mwoauth-consumer-secretkey' => 'Consumer secret token:',
	'mwoauth-consumer-accesstoken' => 'Access token:',
	'mwoauth-consumer-reason' => 'Reason:',
	'mwoauth-consumer-alreadyexists' => 'A consumer with this name/version/publisher combination already exists',
	'mwoauth-consumer-alreadyexistsversion' => 'A consumer with this name/publisher combination already exists with an equal or higher version ("$1")',
	'mwoauth-consumer-not-accepted' => 'Cannot update information for a pending consumer request',
	'mwoauth-consumer-not-proposed' => 'The consumer is not currently proposed',
	'mwoauth-consumer-not-disabled' => 'The consumer is not currently disabled',
	'mwoauth-consumer-not-approved' => 'The consumer is not approved (it may have been disabled)',
	'mwoauth-invalid-consumer-key' => 'No consumer exists with the given key.',
	'mwoauth-invalid-access-token' => 'No access token exists with the given key.',
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
	'mwoauthconsumerregistration-propose-text' => 'Use the form below to propose a new OAuth consumer (see http://oauth.net).

A few recommendations and remarks:
* Try to use as few grants as possible. Avoid grants that are not actually needed now.
* Versions are of the form "major.minor.release" (the last two being optional) and increase as grant changes are needed.
* Please provide an RSA key if possible; otherwise a (less secure) secret token will have to be used.
* Use the JSON restrictions field to limit access of this consumer to IP addresses in those CIDR ranges.
* You can use a wiki ID to restrict the consumer to a single wiki on this site (use "*" for all wikis).
* The email address provided must match that of your account (which must have been confirmed).',
	'mwoauthconsumerregistration-update-text' => 'Use the form below to update aspects of an OAuth consumer you control.

All values here will overwrite any previous ones. Do not leave blank fields unless you intend to clear those values.',
	'mwoauthconsumerregistration-maintext' => 'This page is meant for proposing and updating OAuth (see http://oauth.net) consumer applications in this site\'s registry.

From here, you can [[Special:MWOAuthConsumerRegistration/propose|propose a new consumer]] or [[Special:MWOAuthConsumerRegistration/list|manage your existing consumers]].',
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

	'mwoauthmanagemygrants' => 'Manage account OAuth grants',
	'mwoauthmanagemygrants-notloggedin' => 'You have to be logged in to access this page.',
	'mwoauthmanagemygrants-navigation' => 'Navigation:',
	'mwoauthmanagemygrants-showlist' => 'Accepted consumer list',
	'mwoauthmanagemygrants-none' => 'No consumers have access on behalf of your account.',
	'mwoauthmanagemygrants-name' => 'Consumer name',
	'mwoauthmanagemygrants-user' => 'Publisher',
	'mwoauthmanagemygrants-description' => 'Description',
	'mwoauthmanagemygrants-wiki' => 'Applicable wiki',
	'mwoauthmanagemygrants-wikiallowed' => 'Allowed on wiki',
	'mwoauthmanagemygrants-grants' => 'Applicable grants',
	'mwoauthmanagemygrants-grantsallowed' => 'Grants allowed',
	'mwoauthmanagemygrants-applicablegrantsallowed' => 'Applicable grants allowed:',
	'mwoauthmanagemygrants-consumerkey' => 'Consumer key',
	'mwoauthmanagemygrants-review' => 'review/manage access',
	'mwoauthmanagemygrants-grantaccept' => 'Granted to consumer',
	'mwoauthmanagemygrants-confirm-text' => 'Use the form below to revoke access or change grants for an OAuth consumer to act on your behalf.

Note that if you authorized a consumer to only have access to a subset of wikis (site projects), then there will be multiple access tokens for that consumer.',
	'mwoauthmanagemygrants-confirm-legend' => 'Manage consumer access token',
	'mwoauthmanagemygrants-update' => 'Update access token grants',
	'mwoauthmanagemygrants-renounce' => 'De-authorize and delete access token',
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

	'mwoauth-bad-csrf-token' => 'Session failure when submitting form. Please try your submissions again.',
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

	'mwoauth-form-description' => 'The following application is requesting to use MediaWiki on your behalf. The application will be able to perform any actions that are allowed with the list if requested rights below. Only allow applications that you trust to use these permissions as you would.',
	'mwoauth-form-existing' => "'''This application is requesting authorization to MediaWiki on your behalf, but you have already granted access:'''
*  Grants: $1
*  Wiki: $2
*  Authorized on: $3",
	'mwoauth-form-legal' => '',
	'mwoauth-form-button-approve' => 'Yes, allow',
	'mwoauth-form-confirmation' => 'Allow this application to act on your behalf?',
	'mwoauth-form-confirmation-update' => 'Update this authorization to the requested privileges. Leaving this unchecked will keep your existing authorizations',
	'mwoauth-authorize-form' => 'Application details:',
	'mwoauth-authorize-form-user' => 'Application author: $1',
	'mwoauth-authorize-form-name' => 'Application name: $1',
	'mwoauth-authorize-form-description' => 'Application description: $1',
	'mwoauth-authorize-form-version' => 'Application version: $1',
	'mwoauth-authorize-form-wiki' => 'Wiki: $1',
	'mwoauth-authorize-form-invalid-user' => 'This user account cannot use OAuth, because the account on this wiki, and the account on the central OAuth wiki are not linked.',
	'mwoauth-error' => 'OAuth Error',
	'mwoauth-grants-heading' => 'Requested permissions: ',
	'mwoauth-grants-nogrants' => 'The application has not requested any permissions.',

	'mwoauth-grant-blockusers' => 'Block users',
	'mwoauth-grant-createeditmovepage' => 'Create, edit, and move pages',
	'mwoauth-grant-delete' => 'Delete pages, revisions, and log entries',
	'mwoauth-grant-editinterface' => 'Edit the MediaWiki namespace and user CSS/JS',
	'mwoauth-grant-editmycssjs' => 'Edit your own user CSS/JS',
	'mwoauth-grant-editmywatchlist' => 'Edit your watchlist',
	'mwoauth-grant-editpage' => 'Edit existing pages',
	'mwoauth-grant-editprotected' => 'Edit protected pages',
	'mwoauth-grant-highvolume' => 'High-volume editing',
	'mwoauth-grant-oversight' => 'Hide users and suppress revisions',
	'mwoauth-grant-patrol' => 'Patrol',
	'mwoauth-grant-protect' => 'Protect and unprotect pages',
	'mwoauth-grant-rollback' => 'Rollback',
	'mwoauth-grant-sendemail' => 'Send email',
	'mwoauth-grant-uploadeditmovefile' => 'Upload, replace, and move files',
	'mwoauth-grant-uploadfile' => 'Upload new files',
	'mwoauth-grant-useoauth' => 'Basic rights',
	'mwoauth-grant-viewdeleted' => 'View deleted information',
	'mwoauth-grant-viewmywatchlist' => 'View your watchlist',

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
	'mwoauth-missing-field' => 'Parameters:
* $1 - field name
See also:
* {{msg-mw|Mwoauth-invalid-field}}',
	'mwoauth-invalid-field' => 'Parameters:
* $1 - field name
See also:
* {{msg-mw|Mwoauth-missing-field}}',
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
	'mwoauth-prefs-managegrants' => 'Used as label in [[Special:Preferences]].',
	'mwoauth-prefs-managegrantslink' => 'Used in [[Special:Preferences]].

Used as text for the link which points to [[Special:MWOAuthManageMyGrants]].

Preceded by the label {{msg-mw|Mwoauth-prefs-managegrants}}.',
	'mwoauth-consumer-key' => 'Used as label for the "Consumer key" input box.
{{Identical|Consumer key}}',
	'mwoauth-consumer-name' => 'Used as label for the "Application name" input box.
{{Identical|Application name}}',
	'mwoauth-consumer-version' => 'Used as label for the "Version" input box.',
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

Followed by the list of grants.',
	'mwoauth-consumer-required-grant' => 'Used as table column header.',
	'mwoauth-consumer-wiki' => 'Used as label for the input box. The default value for the input box is "*".
{{Identical|Applicable wiki}}',
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
	'mwoauth-invalid-consumer-key' => 'Used as failure message.',
	'mwoauth-invalid-access-token' => 'Used as failure message.',
	'mwoauth-consumer-conflict' => 'Used as failure message.',
	'mwoauth-consumer-stage-proposed' => '{{Related|Mwoauth-consumer-stage}}',
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
	'mwoauthconsumerregistration-email' => 'field on registration form for email',
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
	'mwoauthdatastore-access-token-not-found' => 'Error message when an invalid access token was submitted',
	'mwoauthdatastore-request-token-not-found' => 'Error message when an invalid request token was submitted',
	'mwoauthdatastore-bad-token' => 'Error message when an invalid token was submitted',
	'mwoauthdatastore-bad-verifier' => 'Error message when an invalid verification code was submitted',
	'mwoauthdatastore-invalid-token-type' => 'Error message when an invalid page was requested',
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
	'mwoauthmanagemygrants' => '{{doc-special|MWOAuthManageMyGrants}}',
	'mwoauthmanagemygrants-notloggedin' => 'Used in [[Special:MWOAuthManageMyGrants]] if the user is not logged in.',
	'mwoauthmanagemygrants-navigation' => 'Used as subtitle.

Followed by a link with the link text {{msg-mw|Mwoauthmanagemygrants-showlist}}. It can be without link.
{{Identical|Navigation}}',
	'mwoauthmanagemygrants-showlist' => 'Used as link text or as plain text',
	'mwoauthmanagemygrants-name' => 'Used as table row header.',
	'mwoauthmanagemygrants-none' => 'Message when a user has not authorized any OAuth consumers',
	'mwoauthmanagemygrants-user' => 'Used as table row header for "Central username".
{{Identical|Publisher}}',
	'mwoauthmanagemygrants-description' => 'Used as table row header.
{{Identical|Description}}',
	'mwoauthmanagemygrants-wiki' => 'Used as table row header.
{{Identical|Applicable wiki}}',
	'mwoauthmanagemygrants-consumerkey' => 'Used as table row header.
{{Identical|Consumer key}}',
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
	'mwoauthserver-invalid-user-hookabort' => 'Used as error message.',
	'mwoauthserver-bad-consumer' => 'Error message when an invalid consumer identifier was submitted',
	'mwoauthserver-insufficient-rights' => 'Error message that the user does not have the required rights to perform this request',
	'mwoauthserver-invalid-request-token' => 'Error message when an invalid request token was submitted',
	'mwoauth-invalid-authorization-title' => 'Title of the error page when the Authorization header is invalid',
	'mwoauth-invalid-authorization' => 'Text of the error page when the Authorization header is invalid. Parameters are:
* $1 - Specific error message from the OAuth layer, probably not localized',
	'mwoauth-invalid-authorization-wrong-wiki' => 'Text of the error page when the Authorization header is for the wrong wiki. Parameters are:
* $1 - wiki id',
	'mwoauth-invalid-authorization-invalid-user' => "Text of the error page when the Authorization header is for a user that doesn't exist",
	'mwoauth-invalid-authorization-wrong-user' => 'Text of the error page when the Authorization header is for the wrong user',
	'mwoauth-invalid-authorization-not-approved' => "Text of the error page when the Authorization header is for a consumer that isn't approved",
	'mwoauth-form-existing' => 'Used if the user has already authorized this consumer. Parameters:
* $1 - list of grants, or the message {{msg-mw|Mwoauth-grants-nogrants}}
* $2 - existing wiki
* $3 - existing accepted',
	'mwoauth-authorize-form' => 'Form section label',
	'mwoauth-authorize-form-user' => 'Used as label. Parameters:
* $1 - ...',
	'mwoauth-authorize-form-name' => '{{Identical|Application name}}',
	'mwoauth-authorize-form-description' => '{{Identical|Application description}}',
	'mwoauth-authorize-form-version' => '{{Identical|Application version}}',
	'mwoauth-authorize-form-wiki' => '{{Identical|Wiki}}',
	'mwoauth-authorize-form-invalid-user' => 'Text of the error page when the user cannot use OAuth.',
	'mwoauth-bad-csrf-token' => 'Warning message when a bad edit token was used in the form',
	'mwoauth-bad-request' => 'General error when there was a problem processing the request',
	'mwoauth-error' => 'Heading on the page, whenever an OAuth error is presented to a user.',
	'mwoauth-form-description' => 'Description of a form requesting the user authorize an OAuth consumer to use MediaWiki on their behalf',

	'mwoauth-form-button-approve' => 'Button label, indicating the user wants to allow access',
	'mwoauth-form-confirmation' => 'Form label, asking if the user is sure they want to allow access',
	'mwoauth-form-confirmation-update' => 'Checkbox label, asking if the user wants to update the permissions they are granting to a consumer, if they have previously granted access to this consumer',
	'mwoauth-grants-heading' => 'Used as label for the grants list.

See also:
* {{msg-mw|Mwoauth-grant-blockusers}}
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
	'mwoauth-grant-blockusers' => 'Name for OAuth grant "blockusers".
{{Identical|Block user}}',
	'mwoauth-grant-createeditmovepage' => 'Name for OAuth grant "createeditmovepage"',
	'mwoauth-grant-delete' => 'Name for OAuth grant "delete"',
	'mwoauth-grant-editinterface' => 'Name for OAuth grant "editinterface"',
	'mwoauth-grant-editmycssjs' => 'Name for OAuth grant "editmycssjs"',
	'mwoauth-grant-editmywatchlist' => 'Name for OAuth grant "editmywatchlist".
{{Identical|Edit your watchlist}}',
	'mwoauth-grant-editpage' => 'Name for OAuth grant "editpage"',
	'mwoauth-grant-editprotected' => 'Name for OAuth grant "editprotected"',
	'mwoauth-grant-highvolume' => 'Name for OAuth grant "highvolume"',
	'mwoauth-grants-nogrants' => 'Warning message that the OAuth consumer has not requested any permissions',
	'mwoauth-grant-oversight' => 'Name for OAuth grant "oversight"',
	'mwoauth-grant-patrol' => 'Name for OAuth grant "patrol"',
	'mwoauth-grant-protect' => 'Name for OAuth grant "protect"',
	'mwoauth-grant-rollback' => 'Name for OAuth grant "rollback".
{{Identical|Rollback}}',
	'mwoauth-grant-sendemail' => 'Name for OAuth grant "sendemail".
{{Identical|E-mail}}',
	'mwoauth-grant-uploadeditmovefile' => 'Name for OAuth grant "uploadeditmovefile"',
	'mwoauth-grant-uploadfile' => 'Name for OAuth grant "uploadfile".
{{Identical|Upload new file}}',
	'mwoauth-grant-useoauth' => 'Name for OAuth grant "useoauth"',
	'mwoauth-grant-viewdeleted' => 'Name for OAuth grant "viewdeleted"',
	'mwoauth-grant-viewmywatchlist' => 'Name for OAuth grant "viewmywatchlist".
{{Identical|View your watchlist}}',
	'mwoauthgrants-general-error' => 'Generic error, when something unexpected happened while processing the OAuth request',
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

/** Asturian (asturianu)
 * @author Xuacu
 */
$messages['ast'] = array(
	'mwoauth-desc' => 'Autenticación API OAuth 1.0a',
	'mwoauth-missing-field' => 'Falta el valor del campu "$1"',
	'mwoauth-invalid-field' => 'Diose un valor inválidu pal campu "$1"',
	'mwoauth-field-hidden' => '(esta información ta tapecida)',
	'mwoauth-field-private' => '(esta información ye privada)',
	'mwoauth-consumer-key' => 'Clave del consumidor:',
	'mwoauth-consumer-name' => "Nome d'aplicación:",
	'mwoauth-consumer-version' => 'Versión mayor:',
	'mwoauth-consumer-stage' => 'Estáu actual:',
	'mwoauth-consumer-email' => 'Direición de corréu-e de contautu:',
	'mwoauth-consumer-description' => "Descripción de l'aplicación:",
	'mwoauth-consumer-callbackurl' => 'URL de "callback" OAuth:',
);

/** Czech (česky)
 * @author Mormegil
 */
$messages['cs'] = array(
	'oauth-desc' => 'Autentizace pomocí rozhraní OAuth 1.0a',
);

/** German (Deutsch)
 * @author Metalhead64
 * @author Se4598
 */
$messages['de'] = array(
	'mwoauth-desc' => 'OAuth-1.0a-API-Authentifikation',
	'mwoauth-missing-field' => 'Fehlender Wert für das Feld „$1“',
	'mwoauth-invalid-field' => 'Für das Feld „$1“ wurde ein ungültiger Wert angegeben',
	'mwoauth-field-hidden' => '(diese Information ist versteckt)',
	'mwoauth-field-private' => '(diese Information ist privat)',
	'mwoauth-grant-generic' => 'Rechtegruppe „$1“',
	'mwoauth-prefs-managegrants' => 'OAuth-Verbraucherzugriff:',
	'mwoauth-prefs-managegrantslink' => 'Berechtigungen im Namen dieses Kontos verwalten',
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
	'mwoauth-consumer-restrictions' => 'Benutzungsbeschränkungen:',
	'mwoauth-consumer-restrictions-json' => 'Benutzungsbeschränkungen (JSON):',
	'mwoauth-consumer-rsakey' => 'Öffentlicher RSA-Schlüssel:',
	'mwoauth-consumer-secretkey' => 'Geheimer Verbrauchertoken:',
	'mwoauth-consumer-accesstoken' => 'Zugriffstoken:',
	'mwoauth-consumer-reason' => 'Grund:',
	'mwoauth-consumer-alreadyexists' => 'Ein Verbraucher mit dieser Namen-/Versions-/Herausgeberkombination ist bereits vorhanden',
	'mwoauth-consumer-alreadyexistsversion' => 'Ein Verbraucher mit dieser Namen-/Herausgeber-Kombination ist bereits mit einer gleichen oder höheren Version vorhanden („$1“)',
	'mwoauth-consumer-not-accepted' => 'Die Informationen für einen ausstehenden Verbraucherantrag konnten nicht aktualisiert werden',
	'mwoauth-consumer-not-proposed' => 'Der Verbraucher ist derzeit nicht geplant',
	'mwoauth-consumer-not-disabled' => 'Der Verbraucher ist derzeit nicht deaktiviert',
	'mwoauth-consumer-not-approved' => 'Der Verbraucher ist nicht bestätigt (vielleicht wurde er deaktiviert)',
	'mwoauth-invalid-consumer-key' => 'Es ist kein Verbraucher mit dem angegebenen Schlüssel vorhanden.',
	'mwoauth-invalid-access-token' => 'Es ist kein Zugriffstoken mit dem angegebenen Schlüssel vorhanden.',
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
	'mwoauthconsumerregistration-propose-text' => 'Verwende das unten stehende Formular, um einen neuen OAuth-Verbraucher zu planen (siehe http://oauth.net).

Hier einige Empfehlungen und Bemerkungen:
* Versuche, so wenig Berechtigungen wie möglich zu verwenden. Vermeide Berechtigungen, die in Wirklichkeit nicht benötigt werden.
* Versionen haben die Form „Hauptversion.Nebenversion.Release“ (die letzten zwei sind optional) und steigen mit der Notwendigkeit von Berechtigungsänderungen an.
* Bitte gib einen RSA-Schlüssel an, falls möglich. Anderenfalls muss ein wenig sicherer Geheimtoken benutzt werden.
* Verwende das JSON-Beschränkungsfeld, um den Zugriff dieses Verbrauchers auf IP-Adressen in diesen CIDR-Bereichen zu beschränken.
* Du kannst eine Wikikennung verwenden, um den Verbraucher auf ein einzelnes Wiki auf dieser Website zu beschränken (verwende „*“ für alle Wikis).
* Die angegebene E-Mail-Adresse muss mit der deines Benutzerkontos übereinstimmen und bestätigt sein.',
	'mwoauthconsumerregistration-update-text' => 'Verwende das unten stehende Formular, um Aspekte eines von dir kontrollierten OAuth-Verbrauchers zu aktualisieren.

Alle Werte hier überschreiben alle vorherigen. Hinterlasse keine leeren Felder, außer du beabsichtigst, diese Werte zu löschen.',
	'mwoauthconsumerregistration-maintext' => 'Diese Seite ist gedacht zur Planung und Aktualisierung von OAuth-Verbraucheranwendungen (siehe http://oauth.net) in der Websiteregistrierung.

Du kannst von hier [[Special:MWOAuthConsumerRegistration/propose|einen neuen Verbraucher planen]] oder [[Special:MWOAuthConsumerRegistration/list|deine vorhandenen Verbraucher verwalten]].',
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
	'mwoauthmanagemygrants' => 'Benutzerkonten-OAuth-Berechtigungen verwalten',
	'mwoauthmanagemygrants-notloggedin' => 'Du musst angemeldet sein, um auf diese Seite zugreifen zu können.',
	'mwoauthmanagemygrants-navigation' => 'Navigation:',
	'mwoauthmanagemygrants-showlist' => 'Liste akzeptierter Verbraucher',
	'mwoauthmanagemygrants-none' => 'Keine Verbraucher haben Zugriff im Namen deines Benutzerkontos.',
	'mwoauthmanagemygrants-name' => 'Verbrauchername',
	'mwoauthmanagemygrants-user' => 'Herausgeber',
	'mwoauthmanagemygrants-description' => 'Beschreibung',
	'mwoauthmanagemygrants-wiki' => 'Anwendbares Wiki',
	'mwoauthmanagemygrants-wikiallowed' => 'Erlaubt auf Wiki',
	'mwoauthmanagemygrants-grants' => 'Anwendbare Berechtigungen',
	'mwoauthmanagemygrants-grantsallowed' => 'Erlaubte Berechtigungen:',
	'mwoauthmanagemygrants-applicablegrantsallowed' => 'Erlaubte anwendbare Berechtigungen:',
	'mwoauthmanagemygrants-consumerkey' => 'Verbraucherschlüssel',
	'mwoauthmanagemygrants-review' => 'Zugriff überprüfen/verwalten',
	'mwoauthmanagemygrants-grantaccept' => 'Dem Verbraucher gewährt',
	'mwoauthmanagemygrants-confirm-text' => 'Verwende das unten stehende Formular, um den Zugriff zu entziehen oder um Berechtigungen für einen OAuth-Verbraucher zu ändern, der auf deinen Namen handelt.

Falls du nur einen Verbraucher autorisiert hast, um Zugriff auf eine Wikiuntergruppe (Websiteprojekte) zu haben, gibt es für diesen Verbraucher mehrere Zugriffstokens.',
	'mwoauthmanagemygrants-confirm-legend' => 'Verbraucherzugriffstoken verwalten',
	'mwoauthmanagemygrants-update' => 'Zugriffstokenberechtigungen aktualisieren',
	'mwoauthmanagemygrants-renounce' => 'Deautorisieren und Zugriffstoken löschen',
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
	'mwoauth-bad-csrf-token' => 'Beim Übermitteln des Formulars ist ein Sitzungsfehler aufgetreten. Bitte erneut versuchen.',
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
	'mwoauth-form-description' => 'Die folgende Anwendung will MediaWiki in deinem Namen benutzen. Die Anwendung wird jede Aktion ausführen können, die in der unten stehenden Liste erlaubt wird. Lasse nur Anwendungen zu, denen du eine Verwendung dieser Berechtigungen zutraust.',
	'mwoauth-form-existing' => "'''Diese Anwendung fordert Berechtigungen für MediaWiki auf deinen Namen an, du hast jedoch bereits Zugriff gewährt:'''
*  Berechtigungen: $1
*  Wiki: $2
*  Genehmigt am: $3",
	'mwoauth-form-button-approve' => 'Ja, erlauben',
	'mwoauth-form-confirmation' => 'Darf diese Anwendung auf deinen Namen handeln?',
	'mwoauth-form-confirmation-update' => 'Aktualisiere diese Genehmigung für die angeforderten Berechtigungen. Das ungeprüft lassen behält deine vorhandenen Genehmigungen bei.',
	'mwoauth-authorize-form' => 'Anwendungsdetails:',
	'mwoauth-authorize-form-user' => 'Anwendungsentwickler: $1',
	'mwoauth-authorize-form-name' => 'Anwendungsname: $1',
	'mwoauth-authorize-form-description' => 'Anwendungsbeschreibung: $1',
	'mwoauth-authorize-form-version' => 'Anwendungsversion: $1',
	'mwoauth-authorize-form-wiki' => 'Wiki: $1',
	'mwoauth-authorize-form-invalid-user' => 'Dieses Benutzerkonto kann nicht OAuth verwenden, da das Konto auf diesem Wiki und das Konto auf dem zentralen OAuth-Wiki nicht verknüpft sind.',
	'mwoauth-error' => 'OAuth-Fehler',
	'mwoauth-grants-heading' => 'Angeforderte Berechtigungen:',
	'mwoauth-grants-nogrants' => 'Die Anwendung hat keine Berechtigungen beantragt.',
	'mwoauth-grant-blockusers' => 'Benutzer sperren',
	'mwoauth-grant-createeditmovepage' => 'Seiten erstellen, bearbeiten und verschieben',
	'mwoauth-grant-delete' => 'Seiten, Versionen und Logbucheinträge löschen',
	'mwoauth-grant-editinterface' => 'MediaWiki-Namensraum und Benutzer-CSS/JS bearbeiten',
	'mwoauth-grant-editmycssjs' => 'Deine eigene Benutzer-CSS/JS bearbeiten',
	'mwoauth-grant-editmywatchlist' => 'Deine Beobachtungsliste bearbeiten',
	'mwoauth-grant-editpage' => 'Vorhandene Seiten bearbeiten',
	'mwoauth-grant-editprotected' => 'Geschützte Seiten bearbeiten',
	'mwoauth-grant-highvolume' => 'Massenbearbeitungen',
	'mwoauth-grant-oversight' => 'Benutzer verstecken und Versionen unterdrücken',
	'mwoauth-grant-patrol' => 'Kontrollieren',
	'mwoauth-grant-protect' => 'Seiten schützen und freigeben',
	'mwoauth-grant-rollback' => 'Zurücksetzen',
	'mwoauth-grant-sendemail' => 'E-Mails versenden',
	'mwoauth-grant-uploadeditmovefile' => 'Dateien hochladen, ersetzen und verschieben',
	'mwoauth-grant-uploadfile' => 'Neue Dateien hochladen',
	'mwoauth-grant-useoauth' => 'Basisrechte',
	'mwoauth-grant-viewdeleted' => 'Gelöschte Informationen ansehen',
	'mwoauth-grant-viewmywatchlist' => 'Deine Beobachtungsliste ansehen',
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
	'mwoauthmanagemygrants-confirm-text' => 'Use the form below to revoke access or change grants for an OAuth consumer to act on your behalf.

Note that if you authorised a consumer to only have access to a subset of wikis (site projects), then there will be multiple access tokens for that consumer.',
	'mwoauthmanagemygrants-renounce' => 'De-authorise and delete access token',
	'mwoauthdatastore-access-token-not-found' => 'No approved grant was found for that authorisation token',
	'mwoauth-invalid-authorization-title' => 'OAuth authorisation error',
	'mwoauth-invalid-authorization' => 'The authorisation headers in your request are not valid: $1',
	'mwoauth-invalid-authorization-wrong-wiki' => 'The authorisation headers in your request are not valid for $1',
	'mwoauth-invalid-authorization-invalid-user' => "The authorisation headers in your request are for a user that doesn't exist here",
	'mwoauth-invalid-authorization-wrong-user' => 'The authorisation headers in your request are for a different user',
	'mwoauth-invalid-authorization-not-approved' => 'The authorisation headers in your request are for an OAuth consumer that is not currently approved',
	'mwoauth-form-existing' => "'''This application is requesting authorisation to MediaWiki on your behalf, but you have already granted access:'''
*  Grants: $1
*  Wiki: $2
*  Authorised on: $3",
	'mwoauth-form-confirmation-update' => 'Update this authorisation to the requested privileges. Leaving this unchecked will keep your existing authorisations',
);

/** French (français)
 * @author Crochet.david
 * @author Gomoko
 * @author Linedwell
 * @author Louperivois
 * @author Wyz
 */
$messages['fr'] = array(
	'mwoauth-desc' => 'API d’authentification OAuth 1.0a',
	'mwoauth-missing-field' => 'Valeur manquante pour le champ « $1 »',
	'mwoauth-invalid-field' => 'Valeur invalide fournie pour le champ « $1 »',
	'mwoauth-field-hidden' => '(cette information est masquée)',
	'mwoauth-field-private' => '(cette information est privée)',
	'mwoauth-grant-generic' => 'ensemble de droits « $1 »',
	'mwoauth-prefs-managegrants' => 'Accès du consommateur OAuth :',
	'mwoauth-prefs-managegrantslink' => 'gérer les droits au nom de ce compte',
	'mwoauth-consumer-key' => 'Clé du consommateur :',
	'mwoauth-consumer-name' => "Nom de l'application :",
	'mwoauth-consumer-version' => 'Version majeure :', # Fuzzy
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
	'mwoauth-consumer-alreadyexists' => 'Un consommateur avec cette combinaison de nom/version/éditeur existe déjà',
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
	'mwoauthconsumerregistration-propose-text' => 'Utilisez le formulaire ci-dessous pour proposer un nouveau consommateur OAuth (voir http://oauth.net).

Quelques recommandations et remarques :
* Essayez d’utiliser le moins de droits possibles. Évitez les droits qui ne sont pas vraiment nécessaires pour le moment.
* Veuillez fournir une clé RSA si possible ; sinon, un jeton secret (moins sécurisé) vous sera assigné.
* Utilisez le champ limitations JSON pour limiter l’accès de ce consommateur aux adresses IP dans ces plages de CIDR.
* Vous pouvez utiliser un ID de wiki pour limiter ce consommateur à un unique wiki de ce site (utilisez "*" pour tous les wikis).
* L’adresse de courriel fournie doit correspondre à celle de votre compte (qui doit avoir été confirmée).', # Fuzzy
	'mwoauthconsumerregistration-update-text' => 'Utilisez le formulaire ci-dessous pour mettre à jour les aspects d’un consommateur OAuth que vous contrôlez.

Toutes les valeurs ici écraseront les précédentes. Ne laissez aucun champ blanc sauf si vous désirez vraiment effacer ces valeurs.',
	'mwoauthconsumerregistration-maintext' => 'Cette page a pour but de proposer et mettre à jour des applications consommatrices OAuth (voir http://oauth.net) dans le registre de ce site.

Depuis ici, vous pouvez [[Special:MWOAuthConsumerRegistration/propose|proposer un nouveau consommateur]] ou [[Special:MWOAuthConsumerRegistration/list|gérer vos consommateurs existants]].',
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
	'mwoauthmanagemygrants-review' => 'Revoir/gérer l’accès',
	'mwoauthmanagemygrants-grantaccept' => 'Accordé au consommateur',
	'mwoauthmanagemygrants-confirm-text' => 'Utilisez le formulaire ci-dessous pour révoquer l’accès ou modifier les droits d’un consommateur OAuth à agir en votre nom.

Notez bien que si vous autorisez un consommateur à n’avoir accès qu’à un sous-ensemble de wikis (projets de site), alors il y aura des jetons d’accès multiples pour ce consommateur.',
	'mwoauthmanagemygrants-confirm-legend' => 'Gérer le jeton d’accès du consommateur',
	'mwoauthmanagemygrants-update' => 'Mettre à jour les droits du jeton d’accès',
	'mwoauthmanagemygrants-renounce' => 'Ne plus autoriser et supprimer le jeton d’accès',
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
	'mwoauth-form-description' => 'L’application suivante demande à utiliser MediaWiki de votre part. L’application pourra effectuer n’importe quelle action autorisée dans la liste des droits ci-dessous, si besoin. N’autorisez que les applications auxquelles vous faites confiance à utiliser ces droits comme vous le feriez.',
	'mwoauth-form-existing' => "'''Cette application demande une autorisation d'accès à MediaWiki en votre nom, mais vous avez déjà accordé cet accès :'''
* Droits : $1
* Wiki : $2
* Autorisé le : $3",
	'mwoauth-form-button-approve' => 'Oui, autoriser',
	'mwoauth-form-confirmation' => 'Autoriser cette application à agir en votre nom ?',
	'mwoauth-form-confirmation-update' => 'Mettre à jour cette autorisation avec les privilèges demandés. Le laisser décoché conservera vos autorisations actuelles.',
	'mwoauth-authorize-form' => 'Détails sur l’application :',
	'mwoauth-authorize-form-user' => 'Auteur de l’application : $1',
	'mwoauth-authorize-form-name' => 'Nom de l’application : $1',
	'mwoauth-authorize-form-description' => 'Description de l’application : $1',
	'mwoauth-authorize-form-version' => 'Version de l’application : $1',
	'mwoauth-authorize-form-wiki' => 'Wiki : $1',
	'mwoauth-authorize-form-invalid-user' => 'Ce compte utilisateur ne peut pas utiliser OAuth, parce que le compte de ce wiki et le compte du wiki central OAuth ne sont pas liés.',
	'mwoauth-error' => 'Erreur OAuth',
	'mwoauth-grants-heading' => 'Droits requis :',
	'mwoauth-grants-nogrants' => 'L’application n’a demandé aucun droit.',
	'mwoauth-grant-blockusers' => 'Bloquer les utilisateurs',
	'mwoauth-grant-createeditmovepage' => 'Créer, modifier et renommer des pages',
	'mwoauth-grant-delete' => 'Supprimer les pages, les révisions et les entrées du journal',
	'mwoauth-grant-editinterface' => 'Modifier le CSS et le JS de l’espace de nommage MédiaWiki et de l’utilisateur',
	'mwoauth-grant-editmycssjs' => 'Modifier votre propre CSS/JS utilisateur',
	'mwoauth-grant-editmywatchlist' => 'Modifier votre liste de suivi',
	'mwoauth-grant-editpage' => 'Modifier des pages existantes',
	'mwoauth-grant-editprotected' => 'Modifier les pages protégées',
	'mwoauth-grant-highvolume' => 'Modification de gros volumes',
	'mwoauth-grant-oversight' => 'Masquer les utilisateurs et supprimer les révisions',
	'mwoauth-grant-patrol' => 'Patrouiller',
	'mwoauth-grant-protect' => 'Protéger et déprotéger les pages',
	'mwoauth-grant-rollback' => 'Retour arrière',
	'mwoauth-grant-sendemail' => 'Envoyer des courriels',
	'mwoauth-grant-uploadeditmovefile' => 'Télécharger, remplacer et renommer des fichiers',
	'mwoauth-grant-uploadfile' => 'Importer de nouveaux fichiers',
	'mwoauth-grant-useoauth' => 'Droits de base',
	'mwoauth-grant-viewdeleted' => 'Afficher les informations supprimées',
	'mwoauth-grant-viewmywatchlist' => 'Afficher votre liste de suivi',
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
 * @author Toliño
 */
$messages['gl'] = array(
	'mwoauth-desc' => 'Autenticación API OAuth 1.0a',
	'mwoauth-missing-field' => 'Falta o valor para o campo "$1"',
	'mwoauth-invalid-field' => 'Achegouse un valor non válido para o campo "$1"',
	'mwoauth-field-hidden' => '(esta información está agochada)',
	'mwoauth-field-private' => '(esta información é privada)',
	'mwoauth-grant-generic' => 'conxunto de dereitos "$1"',
	'mwoauth-prefs-managegrants' => 'Acceso de consumidor OAuth:',
	'mwoauth-prefs-managegrantslink' => 'administrar as concesións en nome desta conta',
	'mwoauth-consumer-key' => 'Clave do consumidor:',
	'mwoauth-consumer-name' => 'Nome da aplicación:',
	'mwoauth-consumer-version' => 'Versión maior:',
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
	'mwoauth-consumer-alreadyexists' => 'Xa existe un consumidor con esa combinación de nome/versión/editor',
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
	'mwoauthconsumerregistration-navigation' => ':Navegación',
	'mwoauthconsumerregistration-propose' => 'Propoñer un novo consumidor',
	'mwoauthconsumerregistration-list' => 'A miña lista de consumidores',
	'mwoauthconsumerregistration-main' => 'Principal',
	'mwoauthconsumerregistration-propose-text' => 'Utilice o formulario inferior para propoñer un novo consumidor OAuth (véxase http://oauth.net).

Algunhas recomendacións e observacións:
* Intente utilizar as menos concesións posibles. Evite as concesións que non sexan realmente necesarias agora.
* Achegue unha clave RSA se fose posible; en caso contrario, asignaráselle un pase secreto (menos seguro).
* Utilice o campo de restricións JSON para limitar o acceso deste consumidor aos enderezos IP neses rangos CIDR.
* Pode empregar un ID de wiki para restrinxir o consumidor a un único wiki neste sitio (utilice "*" para todos os wikis).
* O enderezo de correo electrónico achegado debe coincidir co da súa conta (que debeu ser confirmado).',
	'mwoauthconsumerregistration-update-text' => 'Utilice o formulario inferior para actualizar aspectos dun consumidor OAuth que controle.

Todos os valores que haxa aquí sobrescribirán os anteriores. Non deixe campos en branco a menos que queira limpar eses valores.',
	'mwoauthconsumerregistration-maintext' => 'Esta páxina está destinada a propoñer e actualizar aplicacións de consumidor OAuth (véxase http://oauth.net) no rexistro do sitio.

Desde aquí, pode [[Special:MWOAuthConsumerRegistration/propose|propoñer un novo consumidor]] ou [[Special:MWOAuthConsumerRegistration/list|administrar os consumidores existentes]].',
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
	'mwoauthmanagemygrants-review' => 'Revisar/Administrar o acceso',
	'mwoauthmanagemygrants-grantaccept' => 'Concedido ao consumidor',
	'mwoauthmanagemygrants-confirm-text' => 'Utilice o formulario inferior para revogar o acceso ou cambiar as concesións dun consumidor OAuth para que actúe no seu nome.

Teña en conta que se autoriza que un consumidor só teña acceso a un subconxunto de wikis (proxectos de sitio), entón haberá múltiples pases de acceso para ese consumidor.',
	'mwoauthmanagemygrants-confirm-legend' => 'Administrar o pase de acceso do consumidor',
	'mwoauthmanagemygrants-update' => 'Actualizar as concesións de pases de acceso',
	'mwoauthmanagemygrants-renounce' => 'Desautorizar e borrar o pase de acceso',
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
	'mwoauth-form-description' => 'A seguinte aplicación está solicitando utilizar MediaWiki no seu nome. A aplicación poderá realizar calquera acción para a que teña permiso da lista inferior, se o solicitase. Autorice unicamente aplicacións nas que confíe que usarán estes permisos como faría vostede.',
	'mwoauth-form-existing' => "'''Esta aplicación está solicitando autorización a MediaWiki no seu nome, pero vostede xa concedeu este acceso:'''
*  Concesións: $1
*  Wiki: $2
*  Autorizado o: $3",
	'mwoauth-form-button-approve' => 'Si, permitir',
	'mwoauth-form-confirmation' => 'Quere permitir que esta aplicación actúe no seu nome?',
	'mwoauth-form-confirmation-update' => 'Actualizar esta autorización cos privilexios solicitados. Deixar isto sen marcar ha manter as autorizacións actuais',
	'mwoauth-authorize-form' => 'Detalles da aplicación:',
	'mwoauth-authorize-form-user' => 'Autor da aplicación: $1',
	'mwoauth-authorize-form-name' => 'Nome da aplicación: $1',
	'mwoauth-authorize-form-description' => 'Descrición da aplicación: $1',
	'mwoauth-authorize-form-version' => 'Versión da aplicación: $1',
	'mwoauth-authorize-form-wiki' => 'Wiki: $1',
	'mwoauth-grants-heading' => 'Permisos solicitados:',
	'mwoauth-grants-nogrants' => 'A aplicación non solicitou ningún permiso.',
	'mwoauth-grant-blockusers' => 'Bloquear usuarios',
	'mwoauth-grant-createeditmovepage' => 'Crear, editar e mover páxinas',
	'mwoauth-grant-delete' => 'Borrar páxinas, revisións e entradas de rexistro',
	'mwoauth-grant-editinterface' => 'Editar o espazo de nomes MediaWiki e o CSS/JS de usuario',
	'mwoauth-grant-editmycssjs' => 'Editar o propio CSS/JS de usuario',
	'mwoauth-grant-editmywatchlist' => 'Editar a súa lista de vixilancia',
	'mwoauth-grant-editpage' => 'Editar as páxinas existentes',
	'mwoauth-grant-editprotected' => 'Editar as páxinas protexidas',
	'mwoauth-grant-highvolume' => 'Edicións de gran volume',
	'mwoauth-grant-oversight' => 'Agochar usuarios e eliminar revisións',
	'mwoauth-grant-patrol' => 'Patrullar',
	'mwoauth-grant-protect' => 'Protexer e desprotexer páxinas',
	'mwoauth-grant-rollback' => 'Reverter',
	'mwoauth-grant-sendemail' => 'Enviar correos electrónicos',
	'mwoauth-grant-uploadeditmovefile' => 'Cargar, substituír e mover ficheiros',
	'mwoauth-grant-uploadfile' => 'Cargar ficheiros novos',
	'mwoauth-grant-useoauth' => 'Dereitos básicos',
	'mwoauth-grant-viewdeleted' => 'Ver a información borrada',
	'mwoauth-grant-viewmywatchlist' => 'Ver a súa lista de vixilancia',
	'mwoauth-callback-not-oob' => 'oauth_callback debe estar definido e ter o valor "oob" (distingue entre maiúsculas e minúsculas)',
	'right-mwoauthproposeconsumer' => 'Propoñer novos consumidores OAuth',
	'right-mwoauthupdateconsumer' => 'Actualizar os consumidores OAuth',
	'right-mwoauthmanageconsumer' => 'Administrar os consumidores OAuth',
	'right-mwoauthsuppress' => 'Eliminar consumidores OAuth',
	'right-mwoauthviewsuppressed' => 'Ver os consumidores OAuth eliminados',
	'right-mwoauthviewprivate' => 'Ver os datos OAuth privados',
	'right-mwoauthmanagemygrants' => 'Administrar as concesións OAuth',
	'action-mwoauthmanageconsumer' => 'administrar os consumidores OAuth',
	'action-mwoauthmanagemygrants' => 'administrar as súas concesións OAuth',
	'action-mwoauthproposeconsumer' => 'propoñer novos consumidores OAuth',
	'action-mwoauthupdateconsumer' => 'actualizar os consumidores OAuth',
	'action-mwoauthviewsuppressed' => 'ver os consumidores OAuth eliminados',
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
	'mwoauth-prefs-managegrantslink' => 'gerer concessiones in nomine de iste conto',
	'mwoauth-consumer-key' => 'Clave de consumitor:',
	'mwoauth-consumer-name' => 'Nomine del application:',
	'mwoauth-consumer-version' => 'Version major:',
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
* Le adresse de e-mail fornite debe esser identic a illo de tu conto (le qual debe esser confirmate).',
	'mwoauthconsumerregistration-update-text' => 'Le formulario sequente permitte actualisar aspectos de un consumitor OAuth que tu controla.

Tote le valores hic superscribera omne previe valores. Non lassa campos vacue si tu non ha le intention de rader iste valores.',
	'mwoauthconsumerregistration-maintext' => 'Iste pagina es pro proponer e actualisar applicationes de consumitor OAuth (vide http://oauth.net) in le base de registration de iste sito.

Ab hic, tu pote [[Special:MWOAuthConsumerRegistration/propose|proponer un nove consumitor]] o [[Special:MWOAuthConsumerRegistration/list|gerer tu consumitores existente]].',
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
	'mwoauthmanagemygrants-review' => 'revider/gerer accesso',
	'mwoauthmanagemygrants-grantaccept' => 'Concedite al consumitor',
	'mwoauthmanagemygrants-confirm-text' => 'Usa le formulario hic infra pro revocar le accesso o cambiar le concessiones de un consumitor OAuth pro ager in tu nomine.

Nota que si tu ha autorisate un consumitor a haber accesso solmente a un parte del wikis (projectos de sito), alora il habera plure indicios de accesso pro iste consumitor.',
	'mwoauthmanagemygrants-confirm-legend' => 'Gerer indicio de accesso de consumitor',
	'mwoauthmanagemygrants-update' => 'Actualisar concessiones de indicio de accesso',
	'mwoauthmanagemygrants-renounce' => 'Disautorisar e deler indicio de accesso',
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
	'mwoauth-form-description' => 'Le sequente application requesta usar MediaWiki in tu nomine. Le application potera exequer omne actiones que es permittite con le lista de derectos requestate hic infra. Tu debe autorisar solmente le applicationes que tu considera digne de fide pro usar iste permissiones como tu lo facerea.',
	'mwoauth-form-existing' => "'''Iste application requesta autorisation pro MediaWiki in tu nomine, ma tu ha jam concedite le accesso:'''
*  Concessiones: $1
*  Wiki: $2
*  Autorisate le: $3",
	'mwoauth-form-button-approve' => 'Si, autorisar',
	'mwoauth-form-confirmation' => 'Autorisar iste application a ager in tu nomine?',
	'mwoauth-form-confirmation-update' => 'Actualisar iste autorisation al privilegios requestate. Si tu lassa isto non marcate, le autorisationes existente essera mantenite.',
	'mwoauth-authorize-form' => 'Detalios del application:',
	'mwoauth-authorize-form-user' => 'Autor del application: $1',
	'mwoauth-authorize-form-name' => 'Nomine del application: $1',
	'mwoauth-authorize-form-description' => 'Description del application: $1',
	'mwoauth-authorize-form-version' => 'Version del application: $1',
	'mwoauth-authorize-form-wiki' => 'Wiki: $1',
	'mwoauth-grants-heading' => 'Permissiones requestate:',
	'mwoauth-grants-nogrants' => 'Le application non ha requestate alcun permission.',
	'mwoauth-grant-blockusers' => 'Blocar usatores',
	'mwoauth-grant-createeditmovepage' => 'Crear, modificar e renominar paginas',
	'mwoauth-grant-delete' => 'Deler paginas, versiones e entratas de registro',
	'mwoauth-grant-editinterface' => 'Modificar le spatio de nomines MediaWiki e le CSS/JS de usatores',
	'mwoauth-grant-editmycssjs' => 'Modificar le CSS/JS del proprie usator',
	'mwoauth-grant-editmywatchlist' => 'Modificar le proprie observatorio',
	'mwoauth-grant-editpage' => 'Modificar paginas existente',
	'mwoauth-grant-editprotected' => 'Modificar paginas protegite',
	'mwoauth-grant-highvolume' => 'Modification in massa',
	'mwoauth-grant-oversight' => 'Celar usatores e supprimer versiones',
	'mwoauth-grant-patrol' => 'Patruliar',
	'mwoauth-grant-protect' => 'Proteger e disproteger paginas',
	'mwoauth-grant-rollback' => 'Revocar',
	'mwoauth-grant-sendemail' => 'Inviar e-mail',
	'mwoauth-grant-uploadeditmovefile' => 'Actualisar, reimplaciar e renominar files',
	'mwoauth-grant-uploadfile' => 'Incargar nove files',
	'mwoauth-grant-useoauth' => 'Derectos de base',
	'mwoauth-grant-viewdeleted' => 'Vider information delite',
	'mwoauth-grant-viewmywatchlist' => 'Vider le proprie observatorio',
	'mwoauth-callback-not-oob' => 'oauth_callback debe esser definite, e debe esser mittite a "oob" (distingue inter majusculas e minusculas)',
	'right-mwoauthproposeconsumer' => 'Proponer nove consumitores OAuth',
	'right-mwoauthupdateconsumer' => 'Actualisar consumitores OAuth',
	'right-mwoauthmanageconsumer' => 'Gerer consumitores OAuth',
	'right-mwoauthsuppress' => 'Supprimer consumitores OAuth',
	'right-mwoauthviewsuppressed' => 'Vider consumitores OAuth supprimite',
	'right-mwoauthviewprivate' => 'Vider datos OAuth private',
	'right-mwoauthmanagemygrants' => 'Gerer concessiones OAuth',
	'action-mwoauthmanageconsumer' => 'gerer consumitores OAuth',
	'action-mwoauthmanagemygrants' => 'gerer le proprie concessiones OAuth',
	'action-mwoauthproposeconsumer' => 'proponer nove consumitores OAUth',
	'action-mwoauthupdateconsumer' => 'actualisar consumitores OAuth',
	'action-mwoauthviewsuppressed' => 'vider consumitores OAUth supprimite',
);

/** Japanese (日本語)
 * @author Shirayuki
 */
$messages['ja'] = array(
	'mwoauth-desc' => 'OAuth 1.0a API 認証',
	'mwoauth-missing-field' => '「$1」フィールドの値がありません',
	'mwoauth-invalid-field' => '「$1」フィールドに指定した値は無効です',
	'mwoauth-field-hidden' => '(この情報は非表示です)',
	'mwoauth-field-private' => '(この情報は非公開です)',
	'mwoauth-prefs-managegrants' => 'OAuth コンシューマー アクセス:',
	'mwoauth-consumer-key' => 'コンシューマー キー:',
	'mwoauth-consumer-name' => 'アプリケーション名:',
	'mwoauth-consumer-version' => 'コンシューマーのバージョン:',
	'mwoauth-consumer-user' => '発行者:',
	'mwoauth-consumer-stage' => '現在の状態:',
	'mwoauth-consumer-email' => '連絡先メールアドレス:',
	'mwoauth-consumer-description' => 'アプリケーションの説明:',
	'mwoauth-consumer-callbackurl' => 'OAuth コールバック URL:',
	'mwoauth-consumer-restrictions' => '使用制限:',
	'mwoauth-consumer-restrictions-json' => '使用制限 (JSON):',
	'mwoauth-consumer-rsakey' => '公開 RSA キー:',
	'mwoauth-consumer-secretkey' => 'コンシューマー秘密トークン:',
	'mwoauth-consumer-accesstoken' => 'アクセス トークン:',
	'mwoauth-consumer-reason' => '理由:',
	'mwoauth-consumer-alreadyexists' => 'この名前/バージョン/発行者の組み合わせを持つコンシューマーは既に存在します',
	'mwoauth-invalid-consumer-key' => '指定したキーのコンシューマーは存在しません。',
	'mwoauth-invalid-access-token' => '指定したキーのアクセス トークンは存在しません。',
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
	'mwoauthmanagemygrants-notloggedin' => 'このページにアクセスするにはログインしてください。',
	'mwoauthmanagemygrants-navigation' => 'ナビゲーション:',
	'mwoauthmanagemygrants-showlist' => 'コンシューマー一覧', # Fuzzy
	'mwoauthmanagemygrants-name' => 'コンシューマー名',
	'mwoauthmanagemygrants-user' => '発行者',
	'mwoauthmanagemygrants-description' => '説明',
	'mwoauthmanagemygrants-consumerkey' => 'コンシューマー キー',
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
	'mwoauth-authorize-form' => 'アプリケーションの詳細:',
	'mwoauth-authorize-form-user' => 'アプリケーションの作者: $1',
	'mwoauth-authorize-form-name' => 'アプリケーション名: $1',
	'mwoauth-authorize-form-description' => 'アプリケーションの説明: $1',
	'mwoauth-authorize-form-version' => 'アプリケーションのバージョン: $1',
	'mwoauth-authorize-form-wiki' => 'ウィキ: $1',
	'mwoauth-authorize-form-invalid-user' => 'このウィキと中央管理 OAuth ウィキの利用者アカウントがリンクされていないため、このアカウントでは OAuth を使用できません。',
	'mwoauth-error' => 'OAuth エラー',
	'mwoauth-grant-blockusers' => '利用者をブロック',
	'mwoauth-grant-createeditmovepage' => 'ページを作成/編集/移動',
	'mwoauth-grant-delete' => 'ページ、版、記録項目を削除',
	'mwoauth-grant-editinterface' => 'MediaWiki 名前空間および利用者 CSS/JavaScript を編集',
	'mwoauth-grant-editmycssjs' => '自身の利用者 CSS/JavaScript を編集',
	'mwoauth-grant-editmywatchlist' => '自身のウォッチリストを編集',
	'mwoauth-grant-editpage' => '既存のページを編集',
	'mwoauth-grant-editprotected' => '保護されたページを編集',
	'mwoauth-grant-oversight' => '利用者名および版を秘匿',
	'mwoauth-grant-patrol' => '巡回',
	'mwoauth-grant-protect' => 'ページを保護および保護解除',
	'mwoauth-grant-rollback' => '巻き戻し',
	'mwoauth-grant-sendemail' => 'メールを送信',
	'mwoauth-grant-uploadeditmovefile' => 'ファイルをアップロード/置き換え/移動',
	'mwoauth-grant-uploadfile' => '新しいファイルをアップロード',
	'mwoauth-grant-useoauth' => '基本的な権限',
	'mwoauth-grant-viewdeleted' => '削除された情報を閲覧',
	'mwoauth-grant-viewmywatchlist' => '自身のウォッチリストを閲覧',
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

/** Luxembourgish (Lëtzebuergesch)
 * @author Robby
 */
$messages['lb'] = array(
	'mwoauth-missing-field' => 'De Wäert fir d\'Feld "$1" feelt',
	'mwoauth-field-hidden' => '(dës Informatioun ass verstoppt)',
	'mwoauth-field-private' => '(dës Informatioun ass privat)',
	'mwoauth-consumer-name' => 'Numm vun der Applicatioun:',
	'mwoauth-consumer-stage' => 'Aktuelle Status:',
	'mwoauth-consumer-reason' => 'Grond:',
	'mwoauth-consumer-stage-proposed' => 'geplangt',
	'mwoauth-consumer-stage-rejected' => 'refuséiert',
	'mwoauth-consumer-stage-expired' => 'ofgelaf',
	'mwoauth-consumer-stage-disabled' => 'desaktivéiert',
	'mwoauthconsumerregistration-notloggedin' => 'Dir musst ageloggt si fir op dës Säit ze kommen.',
	'mwoauthconsumerregistration-navigation' => 'Navigatioun:',
	'mwoauthconsumerregistration-description' => 'Beschreiwung',
	'mwoauthconsumerregistration-lastchange' => 'Lescht Ännerung',
	'mwoauthmanageconsumers-notloggedin' => 'Dir musst ageloggt si fir op dës Säit ze kommen.',
	'mwoauthmanageconsumers-main' => 'Haapt',
	'mwoauthmanageconsumers-name' => 'Konsument',
	'mwoauthmanageconsumers-description' => 'Beschreiwung',
	'mwoauthmanageconsumers-lastchange' => 'Lescht Ännerung',
	'mwoauthmanageconsumers-review' => 'nokucken/geréieren',
	'mwoauthmanageconsumers-disable' => 'Desaktivéiert',
	'mwoauthmanageconsumers-reason' => 'Grond:',
	'mwoauthmanagemygrants-notloggedin' => 'Dir musst ageloggt si fir op dës Säit ze kommen.',
	'mwoauthmanagemygrants-navigation' => 'Navigatioun:',
	'mwoauthmanagemygrants-description' => 'Beschreiwung',
	'mwoauthmanagemygrants-wiki' => 'Applicabel Wiki',
	'mwoauthserver-invalid-user-hookabort' => 'Dëse Benotzer däerf OAuth net benotzen.',
	'mwoauth-invalid-authorization-title' => "OAuth Autorisatioun's-Feeler",
	'mwoauth-form-button-approve' => 'Jo, erlaben',
	'mwoauth-authorize-form-wiki' => 'Wiki: $1',
	'mwoauth-error' => 'OAuth Feeler',
	'mwoauth-grants-heading' => 'Ugefroten Autorisatiounen:',
	'mwoauth-grant-blockusers' => 'Benotzer spären',
	'mwoauth-grant-editmycssjs' => 'Ären eegene Benotzer CSS/JS änneren',
	'mwoauth-grant-editmywatchlist' => 'Ännert Är Iwwerwaachungslëscht',
	'mwoauth-grant-editprotected' => 'Gespaarte Säiten änneren',
	'mwoauth-grant-oversight' => 'Benotzer verstoppen a Versioune läschen',
	'mwoauth-grant-rollback' => 'Zrécksetzen',
	'mwoauth-grant-sendemail' => 'E-Mail schécken',
	'mwoauth-grant-viewdeleted' => 'Geläschten Informatioune kucken',
	'mwoauth-grant-viewmywatchlist' => 'Kuckt Är Iwwerwaachungslëscht',
);

/** Macedonian (македонски)
 * @author Bjankuloski06
 */
$messages['mk'] = array(
	'mwoauth-desc' => 'Заверка со прилогот OAuth 1.0a',
	'mwoauth-missing-field' => 'Недостасува вредност во полето „$1“',
	'mwoauth-invalid-field' => 'Во полето „$1“ е зададена неважечка вредност',
	'mwoauth-field-hidden' => '(оваа информација е скриена)',
	'mwoauth-field-private' => '(оваа информација е приватна)',
	'mwoauth-grant-generic' => 'Група права „$1“',
	'mwoauth-prefs-managegrants' => 'Пристап на потрошувач на OAuth:',
	'mwoauth-prefs-managegrantslink' => 'раководи со права во име на оваа сметка',
	'mwoauth-consumer-key' => 'Потрошувачки клуч:',
	'mwoauth-consumer-name' => 'Назив на прилогот:',
	'mwoauth-consumer-version' => 'Главна верзија:',
	'mwoauth-consumer-user' => 'Издавач:',
	'mwoauth-consumer-stage' => 'Тековен статус:',
	'mwoauth-consumer-email' => 'Е-пошта за контакт:',
	'mwoauth-consumer-description' => 'Опис на прилогот:',
	'mwoauth-consumer-callbackurl' => 'URL-адреса за повикување на OAuth:',
	'mwoauth-consumer-grantsneeded' => 'Применливи доделувања:',
	'mwoauth-consumer-required-grant' => 'Применлив потрошувач',
	'mwoauth-consumer-wiki' => 'Применливо вики:',
	'mwoauth-consumer-restrictions' => 'Ограничувања на употребата:',
	'mwoauth-consumer-restrictions-json' => 'Ограничувања на употребата (JSON):',
	'mwoauth-consumer-rsakey' => 'Јавен RSA-клуч:',
	'mwoauth-consumer-secretkey' => 'Тајна потрошувачка шифра:',
	'mwoauth-consumer-accesstoken' => 'Пристапна шифра:',
	'mwoauth-consumer-reason' => 'Причина:',
	'mwoauth-consumer-alreadyexists' => 'Веќе постои потрошувач со ваква комбинација од име/верзија/издавач',
	'mwoauth-consumer-not-accepted' => 'Не можам да ги изменам информациите за потрошувачко барање во исчекување',
	'mwoauth-consumer-not-proposed' => 'Потрошувачот во моментов не е предложен',
	'mwoauth-consumer-not-disabled' => 'Потрошувачот во моментов не е оневозможен',
	'mwoauth-consumer-not-approved' => 'Потрошувачот не е одобрен (може да е оневозможен)',
	'mwoauth-invalid-consumer-key' => 'Не постои потрошувач со таков клуч.',
	'mwoauth-invalid-access-token' => 'Не постои пристапна шифра со таков клуч.',
	'mwoauth-consumer-conflict' => 'Некои ги изменил атрибутети на овој потрошувач додека го разгледувавте. Обидете се повторно. Може да го погледате и дневникот на измени.',
	'mwoauth-consumer-stage-proposed' => 'предложен',
	'mwoauth-consumer-stage-rejected' => 'одбиен',
	'mwoauth-consumer-stage-expired' => 'истечен',
	'mwoauth-consumer-stage-approved' => 'одобрен',
	'mwoauth-consumer-stage-disabled' => 'оневозможен',
	'mwoauth-consumer-stage-suppressed' => 'притаен',
	'mwoauthconsumerregistration' => 'Регистрација на потрошувач на OAuth',
	'mwoauthconsumerregistration-navigation' => 'Навигација:',
	'mwoauthconsumerregistration-propose' => 'Предложи нов потрошувач',
	'mwoauthconsumerregistration-list' => 'Список на мои потрошувачи',
	'mwoauthconsumerregistration-main' => 'Главна',
	'mwoauthconsumerregistration-propose-text' => 'Образецов служи за предлагање на нов потрошувач на OAuth (погл. http://oauth.net).

Неколку препораки и напомении:
* Доделувајте што помалку одобренија. Одбегнувајте ги оние што не се потребни во моментов.
* По можност, внесете RSA-клуч; во спротивно (помалку безбедно) ќе ви зададеме таен клуч.
* Користете ги полињата за ограничувања од JSON за да го ограничите пристапот на потрошувачот на IP-адресите во тие CIDR-опсези.
* Можете да го ограничите потрошувачот на само едно вики на ова мрежно место, внесувајќи ја назнаката на викито („*“ за сите викија).
* Укажаната е-пошта мора да одговара на онаа на вашата сметка (која пак, мора да биде потврдена).',
	'mwoauthconsumerregistration-update-text' => 'Образецот подолу служи за менување на аспекти на потрошувачот на OAuth што се во ваша контрола.

Сите вредности тука ќе се презапишат врз претходните. Не оставајте празни полиња, освен ако не сакате да ги исчистите истите.',
	'mwoauthconsumerregistration-maintext' => 'Оваа страница е наменета за предлагање и измена на потрошувачки прилози за OAuth (погл. http://oauth.net) во регистарот на ова мрежно место.

Од овде можете да [[Special:MWOAuthConsumerRegistration/propose|предложите нов потрошувач]] или пак [[Special:MWOAuthConsumerRegistration/list|раководите со вашите постоечки потрошувачи]].',
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
	'mwoauthmanagemygrants' => 'Раководење со доделувања на OAuth на сметки',
	'mwoauthmanagemygrants-navigation' => 'Навигација:',
	'mwoauthmanagemygrants-showlist' => 'Список на прифатени потрошувачи',
	'mwoauthmanagemygrants-none' => 'Нема потрошувачи со пристап во име на вашата сметка.',
	'mwoauthmanagemygrants-name' => 'Име на потрошувач',
	'mwoauthmanagemygrants-user' => 'Издавач',
	'mwoauthmanagemygrants-description' => 'Опис',
	'mwoauthmanagemygrants-wiki' => 'Применливо вики',
	'mwoauthmanagemygrants-wikiallowed' => 'Дозволено на викито',
	'mwoauthmanagemygrants-grants' => 'Применливи доделувања',
	'mwoauthmanagemygrants-grantsallowed' => 'Дозволени доделувања:',
	'mwoauthmanagemygrants-applicablegrantsallowed' => 'Дозволени применливи доделувања:',
	'mwoauthmanagemygrants-consumerkey' => 'Потрошувачки клуч',
	'mwoauthmanagemygrants-review' => 'Разглед./раковод. на пристап',
	'mwoauthmanagemygrants-grantaccept' => 'Доделено на потрошувачот',
	'mwoauthmanagemygrants-confirm-text' => 'Следниот образец служи за одземање на пристап или да ги измените доделените права на потрошувач на OAuth за да дејствува во ваше име.

Имајте предвид дека ако овластите некого со пристап на само дел од викијата (проектите) наместо сите во целина, тогаш тој потрошувач ќе има повеќе пристапни жетони.',
	'mwoauthmanagemygrants-confirm-legend' => 'Раководење со шифра за кориснички пристап',
	'mwoauthmanagemygrants-update' => 'Измени доделувања на пристап. шифра',
	'mwoauthmanagemygrants-renounce' => 'Одземи дозвола и избриши пристап. шифра',
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
	'mwoauth-bad-csrf-token' => 'Се јави проблем во сесијата при поднесувањето на образецот. Обидете се повторно.',
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
	'mwoauth-form-description' => 'Следниов прилог бара да го користи МедијаВики во ваше име. Ќе може да ги извршува сите дејства дозволени во рамките на долунаведениот список со побарани права. Ова треба да го дозволувате само на прилози на кои им верувате.',
	'mwoauth-form-existing' => "'''Овој прилог бара овластување од МедијаВики во ваше име, но веќе има добиено пристап:'''
*  Доделувања: $1
*  Вики: $2
*  Овластен на: $3",
	'mwoauth-form-button-approve' => 'Да, дозволи',
	'mwoauth-form-confirmation' => 'Да му дозволам на прилогот да врши работи во ваше име?',
	'mwoauth-form-confirmation-update' => 'Дополни го ова овластување со побараните привилегии. Ако ова остане нештиклирано, ќе ги задржите постоечките овластувања',
	'mwoauth-authorize-form' => 'Подробности за податокот:',
	'mwoauth-authorize-form-user' => 'Автор на прилогот: $1',
	'mwoauth-authorize-form-name' => 'Име на прилогот: $1',
	'mwoauth-authorize-form-description' => 'Опис на прилогот: $1',
	'mwoauth-authorize-form-version' => 'Верзиај на прилогот: $1',
	'mwoauth-authorize-form-wiki' => 'Вики: $1',
	'mwoauth-grants-heading' => 'Побарани дозволи:',
	'mwoauth-grants-nogrants' => 'Прилогот нема побарано ниедна дозвола.',
	'mwoauth-grant-blockusers' => 'Блокирање корисници',
	'mwoauth-grant-createeditmovepage' => 'Создавање, измена и преместување на страници',
	'mwoauth-grant-delete' => 'Бришење на страници, ревизии и дневнички записи',
	'mwoauth-grant-editinterface' => 'Измена на именскиот простор „МедијаВики“ и кориснички CSS/JS',
	'mwoauth-grant-editmycssjs' => 'Уредување на ваш кориснички CSS/JS',
	'mwoauth-grant-editmywatchlist' => 'Уредување на ваш список на набљудувања',
	'mwoauth-grant-editpage' => 'Измени постоечки страници',
	'mwoauth-grant-editprotected' => 'Уредување на заштитени страници',
	'mwoauth-grant-highvolume' => 'Уредување на ангро',
	'mwoauth-grant-oversight' => 'Скривање на корисници и ревизии',
	'mwoauth-grant-patrol' => 'Патрола',
	'mwoauth-grant-protect' => 'Заштита на незаштитени страници',
	'mwoauth-grant-rollback' => 'Отповикување',
	'mwoauth-grant-sendemail' => 'Испраќање на е-пошта',
	'mwoauth-grant-uploadeditmovefile' => 'Подигање, замена и преместување на податотеки',
	'mwoauth-grant-uploadfile' => 'Подигни нови податотеки',
	'mwoauth-grant-useoauth' => 'Основни права',
	'mwoauth-grant-viewdeleted' => 'Преглед на избришани информации',
	'mwoauth-grant-viewmywatchlist' => 'Преглед на вашите набљудувања',
	'mwoauth-callback-not-oob' => 'oauth_callback мора да е поставено на „oob“ (строго со мали букви)',
	'right-mwoauthproposeconsumer' => 'Предлагање на нови потрошувачи на OAuth',
	'right-mwoauthupdateconsumer' => 'Измена на потрошувачи на OAuth',
	'right-mwoauthmanageconsumer' => 'Раководење со потрошувачи на OAuth',
	'right-mwoauthsuppress' => 'Скривање на потрошувачи на OAuth',
	'right-mwoauthviewsuppressed' => 'Преглед на скриените потрошувачи на OAuth',
	'right-mwoauthviewprivate' => 'Преглед на приватни податоци за OAuth',
	'right-mwoauthmanagemygrants' => 'Раководење со доделувања за OAuth',
	'action-mwoauthmanageconsumer' => 'раководење со потрошувачи на OAuth',
	'action-mwoauthmanagemygrants' => 'раководење со вашите доделувања за OAuth',
	'action-mwoauthproposeconsumer' => 'предлагање на потрошувачи на OAuth',
	'action-mwoauthupdateconsumer' => 'измена на потрошувачи на OAuth',
	'action-mwoauthviewsuppressed' => 'преглед на скриени потрошувачи на OAuth',
);

/** Malayalam (മലയാളം)
 * @author Kavya Manohar
 * @author Raghith
 */
$messages['ml'] = array(
	'mwoauthmanagemygrants-user' => 'പ്രസാധക(ൻ)',
	'mwoauth-grant-sendemail' => 'ഇമെയിൽ അയയ്ക്കുക',
);

/** Marathi (मराठी)
 * @author V.narsikar
 */
$messages['mr'] = array(
	'mwoauth-grant-blockusers' => 'सदस्य प्रतिबंधन',
	'mwoauth-grant-delete' => 'पाने, आवृत्त्या व नोंदी वगळा',
	'mwoauth-grant-editinterface' => 'मिडियाविकि नामविश्व व सदस्यांची CSS/JS संपादा',
	'mwoauth-grant-editmycssjs' => 'आपले स्वतःचे CSS/JS पान संपादित करा',
	'mwoauth-grant-editmywatchlist' => 'आपली निरीक्षणयादी संपादित करा',
	'mwoauth-grant-editprotected' => 'संपादनांपासून सुरक्षित असलेली पाने',
	'mwoauth-grant-highvolume' => 'अत्त्युच्च-जागा घेणारे संपादन',
	'mwoauth-grant-oversight' => 'सदस्य लपवा व आवृत्त्या दाबा',
	'mwoauth-grant-patrol' => 'गस्त',
	'mwoauth-grant-protect' => 'पाने सुरक्षित किंवा असुरक्षित करा',
	'mwoauth-grant-rollback' => 'परतवा',
	'mwoauth-grant-sendemail' => 'विपत्र पाठवा',
	'mwoauth-grant-useoauth' => 'मुळ अधिकार',
	'mwoauth-grant-viewdeleted' => 'वगळलेली माहिती बघा',
	'mwoauth-grant-viewmywatchlist' => 'आपली निरीक्षणसूची बघा',
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
 */
$messages['nl'] = array(
	'mwoauth-desc' => 'Authenticatie via de OAuth 1.0a API',
	'mwoauth-missing-field' => 'Waarde voor het veld "$1" ontbreekt',
	'mwoauth-invalid-field' => 'Er is een ongeldige waarde opgegeven voor het veld "$1"',
	'mwoauth-field-hidden' => '(deze gegevens zijn verborgen)',
	'mwoauth-field-private' => '(deze gegevens zijn privé)',
	'mwoauth-consumer-key' => 'Consumersleutel:',
	'mwoauth-consumer-name' => 'Naam toepassing:',
	'mwoauth-consumer-version' => 'Hoofdversie:',
	'mwoauth-consumer-user' => 'Uitgever:',
	'mwoauth-consumer-stage' => 'Huidige status:',
	'mwoauth-consumer-email' => 'E-mailadres voor contact:',
	'mwoauth-consumer-description' => 'Toepassingsbeschrijving:',
	'mwoauth-consumer-callbackurl' => 'URL voor OAuth-"callback":',
	'mwoauth-consumer-grantsneeded' => 'Van toepassing zijnde rechten:',
	'mwoauth-consumer-required-grant' => 'Van toepassing op consumer',
	'mwoauth-consumer-wiki' => 'Van toepassing op wiki:',
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
	'mwoauthconsumerregistration-propose-text' => 'Gebruik het formulier hieronder om een nieuwe OAuthconsumer voor te stellen (zie http://oauth.net).

Een paar aanbevelingen en opmerkingen:
* Probeer zo min mogelijk bevoegdheden te gebruiken  Vermijd bevoegdheden die niet echt nodig zijn;
* Gebruik als mogelijk een RSA-sleutel; als dat niet mogelijk is, wordt u een (minder veilig) geheim token toegewezen;
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
	'mwoauthmanagemygrants-none' => 'Er zijn geen consumers die toegang hebben namens uw gebruiker.',
	'mwoauthmanagemygrants-name' => 'Consumernaam',
	'mwoauthmanagemygrants-user' => 'Uitgever',
	'mwoauthmanagemygrants-description' => 'Beschrijving',
	'mwoauthmanagemygrants-wiki' => 'Van toepassing op wiki',
	'mwoauthmanagemygrants-wikiallowed' => 'Toegestaan op wiki',
	'mwoauthmanagemygrants-grants' => 'Van toepassing zijnde rechten',
	'mwoauthmanagemygrants-grantsallowed' => 'Toegestane rechten:',
	'mwoauthmanagemygrants-applicablegrantsallowed' => 'Van toepassing zijnde rechten toegestaan:',
	'mwoauthmanagemygrants-consumerkey' => 'Consumersleutel',
	'mwoauthmanagemygrants-review' => 'Toegang controleren en beheren',
	'mwoauthmanagemygrants-grantaccept' => 'Toegewezen aan consumer',
	'mwoauthmanagemygrants-confirm-legend' => 'consumertoegangstoken beheren',
	'mwoauthmanagemygrants-update' => 'Toegangstokenrechten bijwerken',
	'mwoauthmanagemygrants-renounce' => 'Rechten voor toegangstoken intrekken en het verwijderen',
	'mwoauthmanagemygrants-action' => 'Statuswijziging:',
	'mwoauthmanagemygrants-confirm-submit' => 'Toegangstokenstatus bijwerken',
	'mwoauthmanagemygrants-success-update' => 'Het toegangstoken voor deze consumer is bijgewerkt.',
	'mwoauthmanagemygrants-success-renounce' => 'Het toegangstoken voor deze consumer is verwijderd.',
	'mwoauthconsumer-consumer-logpage' => 'OAuthconsumerlogboek',
	'mwoauthconsumer-consumer-logpagetext' => 'Logboek met goedkeuringen, afwijzingen en uitschakelingen van geregistreerde OAuthconsumers.',
	'mwoauth-bad-csrf-token' => 'Er is een sessiefout opgetreden tijdens het opslaan van het formulier. Probeer uw invoer opnieuw op te slaan.',
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
	'mwoauth-form-button-approve' => 'Ja, toestaan',
	'mwoauth-form-confirmation' => 'Deze toepassing toestaan namens u handelingen uit te voeren?',
	'mwoauth-authorize-form' => 'TOepassingsgegevens:',
	'mwoauth-authorize-form-user' => 'Auteur toepassing: $1',
	'mwoauth-authorize-form-name' => 'Naam toepassing: $1',
	'mwoauth-authorize-form-description' => 'Beschrijving toepassing: $1',
	'mwoauth-authorize-form-version' => 'Versie toepassing: $1',
	'mwoauth-authorize-form-wiki' => 'Wiki: $1',
	'mwoauth-grants-heading' => 'Aangevraagde rechten:',
	'mwoauth-grants-nogrants' => 'De toepassing heeft geen rechten aangevraagd.',
	'mwoauth-grant-blockusers' => 'Gebruikers blokkeren',
	'mwoauth-grant-createeditmovepage' => "Pagina's aanmaken, bewerken en hernoemen",
	'mwoauth-grant-delete' => "Pagina's, wijzigingen en vermeldingen in het logboek verwijderen",
	'mwoauth-grant-editmycssjs' => 'Uw eigen CSS/JavaScript bewerken',
	'mwoauth-grant-editmywatchlist' => 'Uw eigen volglijst bewerken',
	'mwoauth-grant-editpage' => "Bestaande pagina's bewerken",
	'mwoauth-grant-editprotected' => "Beveiligde pagina's bewerken",
	'mwoauth-grant-highvolume' => 'Veel bewerkingen in korte tijd maken',
	'mwoauth-grant-oversight' => 'Gebruikers en versies verbergen',
	'mwoauth-grant-patrol' => 'Wijzigingen controleren',
	'mwoauth-grant-protect' => "Pagina's beveiligen en beveiliging opheffen",
	'mwoauth-grant-rollback' => 'Terugdraaien',
	'mwoauth-grant-sendemail' => 'E-mail verzenden',
	'mwoauth-grant-uploadeditmovefile' => 'Bestanden uploaden, vervangen en hernoemen',
	'mwoauth-grant-uploadfile' => 'Nieuwe bestanden uploaden',
	'mwoauth-grant-useoauth' => 'Grondrechten',
	'mwoauth-grant-viewdeleted' => 'Verwijderde gegevens bekijken',
	'mwoauth-grant-viewmywatchlist' => 'Uw volglijst bekijken',
	'right-mwoauthproposeconsumer' => 'Nieuwe OAuthconsumers voorstellen',
	'right-mwoauthupdateconsumer' => 'OAuthconsumers bijwerken',
	'right-mwoauthmanageconsumer' => 'OAuthconsumers beheren',
	'right-mwoauthsuppress' => 'OAuthconsumers onderdrukken',
	'right-mwoauthviewsuppressed' => 'Onderdrukte OAuthconsumers bekijken',
	'right-mwoauthmanagemygrants' => 'OAuthbevoegdheden beheren',
	'action-mwoauthmanageconsumer' => 'OAuthconsumers te beheren',
	'action-mwoauthmanagemygrants' => 'uw OAuthbevoegdheden te beheren',
	'action-mwoauthproposeconsumer' => 'nieuwe OAuthconsumers voor te stellen',
	'action-mwoauthupdateconsumer' => 'OAuthconsumers bij te werken',
	'action-mwoauthviewsuppressed' => 'onderdrukte OAuthconsumers te bekijken',
);

/** Polish (polski)
 * @author Chrumps
 */
$messages['pl'] = array(
	'mwoauth-consumer-reason' => 'Powód:',
	'mwoauthmanageconsumers-reason' => 'Powód:',
);

/** Portuguese (português)
 * @author Dannyps
 */
$messages['pt'] = array(
	'mwoauth-form-button-approve' => 'Sim, permitir',
	'mwoauth-authorize-form-description' => 'Descrição da aplicação:$1',
	'mwoauth-authorize-form-version' => 'Versão da aplicação:$1',
	'mwoauth-grants-heading' => 'Permissões solicitadas:',
	'mwoauth-grants-editpages' => 'Editar páginas',
	'mwoauth-grants-editmyinterface' => 'Editar a sua interface (páginas JavaScript e CSS)',
	'mwoauth-grants-editinterface' => 'Editar (na MediaWiki interface JavaScript e CSS) páginas',
	'mwoauth-grants-createpages' => 'Criar páginas',
	'mwoauth-grants-deletepages' => 'Eliminar páginas',
	'mwoauth-grants-upload' => 'Carregar ficheiros',
);

/** Brazilian Portuguese (português do Brasil)
 * @author Luckas
 */
$messages['pt-br'] = array(
	'mwoauth-form-button-approve' => 'Sim, permitir',
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
	'mwoauth-consumer-version' => 'Versione prengepàle:',
	'mwoauth-consumer-user' => 'Pubblecatore:',
	'mwoauth-consumer-stage' => 'State de mò:',
	'mwoauth-consumer-email' => 'Indirizze email de condatte:',
	'mwoauth-consumer-description' => "Descrizione de l'applicazione:",
	'mwoauth-consumer-rsakey' => 'Chiave pubblche RSA:',
	'mwoauth-consumer-reason' => 'Mutive:',
	'mwoauth-consumer-stage-proposed' => 'proposte',
	'mwoauth-consumer-stage-rejected' => 'scettate',
	'mwoauth-consumer-stage-expired' => 'scadute',
	'mwoauth-consumer-stage-approved' => 'approvate',
	'mwoauth-consumer-stage-disabled' => 'disabbilitate',
	'mwoauth-consumer-stage-suppressed' => 'scangellate',
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

/** Swedish (svenska)
 * @author Jopparn
 */
$messages['sv'] = array(
	'mwoauth-missing-field' => 'Saknar värde för "$1"-fältet',
	'mwoauth-invalid-field' => 'Ogiltigt värde angett för "$1"-fältet',
	'mwoauth-field-hidden' => '(denna information är dold)',
	'mwoauth-field-private' => '(denna information är privata)',
	'mwoauth-consumer-name' => 'Applikationsnamn:',
	'mwoauth-consumer-version' => 'Större version:',
	'mwoauth-consumer-user' => 'Utgivare:',
	'mwoauth-consumer-stage' => 'Aktuell status:',
	'mwoauth-consumer-grantsneeded' => 'Tillämpliga stipendium:',
	'mwoauth-consumer-required-grant' => 'Tillämplig för konsumenten',
	'mwoauth-consumer-restrictions' => 'Användningsbegränsningar:',
	'mwoauth-consumer-restrictions-json' => 'Användningsbegränsningar (JSON):',
	'mwoauth-consumer-rsakey' => 'Offentlig RSA-nyckel:',
	'mwoauth-consumer-reason' => 'Orsak:',
	'mwoauth-consumer-stage-proposed' => 'föreslagna',
	'mwoauth-consumer-stage-rejected' => 'avvisade',
	'mwoauth-consumer-stage-expired' => 'utgångna',
	'mwoauth-consumer-stage-approved' => 'godkända',
	'mwoauth-consumer-stage-disabled' => 'inaktiverade',
	'mwoauth-consumer-stage-suppressed' => 'undertryckta',
	'mwoauthconsumerregistration' => 'OAuth konsumentenregistrering',
	'mwoauthconsumerregistration-navigation' => 'Navigering:',
	'mwoauthconsumerregistration-propose' => 'Föreslå ny kund',
	'mwoauthconsumerregistration-main' => 'Huvudsida',
	'mwoauthconsumerregistration-user' => 'Utgivare',
	'mwoauthconsumerregistration-description' => 'Beskrivning',
	'mwoauthconsumerregistration-stage' => 'Status',
	'mwoauthconsumerregistration-lastchange' => 'Senaste ändringen',
	'mwoauthconsumerregistration-manage' => 'hantera',
	'mwoauthmanageconsumers-type' => 'Köer:',
	'mwoauthmanageconsumers-showproposed' => 'Föreslagna ansökningar',
	'mwoauthmanageconsumers-showrejected' => 'Avslagna ansökningar',
	'mwoauthmanageconsumers-showexpired' => 'Utgångna ansökningar',
	'mwoauthmanageconsumers-main' => 'Huvudsida',
	'mwoauthmanageconsumers-user' => 'Utgivare',
	'mwoauthmanageconsumers-description' => 'Beskrivning',
	'mwoauthmanageconsumers-lastchange' => 'Senaste ändringen',
	'mwoauthmanageconsumers-review' => 'granska/hantera',
	'mwoauthmanageconsumers-action' => 'Ändra status:',
	'mwoauthmanageconsumers-approve' => 'Godkänd',
	'mwoauthmanageconsumers-reject' => 'Avvisad',
	'mwoauthmanageconsumers-rsuppress' => 'Avvisade och undertryckta',
	'mwoauthmanageconsumers-disable' => 'Inaktiverad',
	'mwoauthmanageconsumers-dsuppress' => 'Inaktiverade och undertryckta',
	'mwoauthmanageconsumers-reenable' => 'Godkänd',
	'mwoauthmanageconsumers-reason' => 'Orsak:',
	'mwoauthmanagemygrants-user' => 'Utgivare',
	'mwoauthmanagemygrants-description' => 'Beskrivning',
	'mwoauthmanagemygrants-wiki' => 'Tillämplig wiki',
	'mwoauthmanagemygrants-wikiallowed' => 'Tillåten på wiki',
	'mwoauthmanagemygrants-grants' => 'Tillämpliga stipendier',
	'mwoauthmanagemygrants-grantsallowed' => 'Stipendier tillåtna',
	'mwoauthmanagemygrants-action' => 'Ändra status:',
	'mwoauthserver-insufficient-rights' => 'Du har inte tillräcklig behörighet för att utföra denna åtgärd.',
	'mwoauth-form-button-approve' => 'Ja, tillåt',
	'mwoauth-form-confirmation' => 'Tillåt detta program att agera å dina vägnar?',
	'mwoauth-authorize-form-wiki' => 'Wiki: $1',
	'mwoauth-grant-createeditmovepage' => 'Skapa, redigera och flytta sidor',
	'mwoauth-grant-editpage' => 'Redigera befintliga sidor',
	'mwoauth-grant-uploadeditmovefile' => 'Ladda upp, byt och flytta filer',
	'mwoauth-grant-uploadfile' => 'Ladda upp nya filer',
);

/** Turkish (Türkçe)
 * @author Rapsar
 */
$messages['tr'] = array(
	'mwoauth-form-button-approve' => 'Evet, izin ver',
	'mwoauth-grants-editpages' => 'Sayfaları değiştir',
	'mwoauth-grants-upload' => 'Dosya yükle',
);

/** Ukrainian (українська)
 * @author Andriykopanytsia
 */
$messages['uk'] = array(
	'mwoauth-consumer-key' => 'Ключ покупця:',
	'mwoauth-consumer-name' => 'Назва програми:',
	'mwoauth-consumer-version' => 'Основна версія:', # Fuzzy
	'mwoauth-consumer-user' => 'Видавець:',
	'mwoauth-consumer-stage' => 'Поточний статус:',
	'mwoauth-consumer-email' => 'Контактна адреса електронної пошти:',
	'mwoauth-consumer-description' => 'Опис програми:',
	'mwoauth-consumer-grantsneeded' => 'Придатні гранти:',
	'mwoauth-consumer-required-grant' => 'Застосовний до покупця',
	'mwoauth-consumer-wiki' => 'Придатні вікі:',
	'mwoauth-consumer-restrictions' => 'Обмеження на використання:',
	'mwoauth-consumer-restrictions-json' => 'Обмеження на користування (JSON):',
	'mwoauth-consumer-rsakey' => 'Відкритий ключ RSA:',
	'mwoauth-consumer-accesstoken' => 'Маркер доступу:',
	'mwoauth-consumer-reason' => 'Причина:',
	'mwoauth-consumer-stage-proposed' => 'запропоновано',
	'mwoauth-consumer-stage-rejected' => 'відхилено',
	'mwoauth-consumer-stage-expired' => 'застаріле',
	'mwoauth-consumer-stage-approved' => 'затверджено',
	'mwoauth-consumer-stage-disabled' => 'вимкнено',
	'mwoauthconsumerregistration-navigation' => 'Навігація:',
	'mwoauthconsumerregistration-main' => 'Головна',
	'mwoauthconsumerregistration-user' => 'Видавець',
	'mwoauthconsumerregistration-description' => 'Опис',
	'mwoauthconsumerregistration-stage' => 'Статус',
	'mwoauthconsumerregistration-lastchange' => 'Остання зміна',
	'mwoauthmanageconsumers-user' => 'Видавець',
	'mwoauthmanageconsumers-approve' => 'Затверджено',
	'mwoauthmanageconsumers-reject' => 'Відхилено',
	'mwoauthmanageconsumers-reenable' => 'Затверджено',
	'mwoauthmanageconsumers-reason' => 'Причина:',
	'mwoauthmanagemygrants-navigation' => 'Навігація:',
	'mwoauthmanagemygrants-description' => 'Опис',
	'mwoauthmanagemygrants-action' => 'Змінити статус:',
	'mwoauth-form-button-approve' => 'Так, дозволити',
	'mwoauth-form-confirmation' => 'Дозволити цій програмі діяти від вашого імені?',
	'mwoauth-grant-patrol' => 'Патруль',
);

/** Yiddish (ייִדיש)
 * @author פוילישער
 */
$messages['yi'] = array(
	'mwoauth-consumer-version' => 'קאנסומענט ווערסיע:',
);
