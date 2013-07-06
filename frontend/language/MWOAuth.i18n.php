<?php
/**
 * Internationalisation file for OAuth extension.
 *
 * @file
 * @ingroup Extensions
 */

$messages = array();

$messages['en'] = array(
	'mwoauth-desc' => 'OAuth 1.0a API authentication',

	'mwoauth-missing-field' => 'Missing value for "$1" field',
	'mwoauth-invalid-field' => 'Invalid value provided for "$1" field',

	'mwoauth-field-hidden' => '(this information is hidden)',
	'mwoauth-field-private' => '(this information is private)',

	'mwoauth-consumer-key' => 'Consumer key:',
	'mwoauth-consumer-name' => 'Application name:',
	'mwoauth-consumer-version' => 'Major version:',
	'mwoauth-consumer-stage' => 'Current status:',
	'mwoauth-consumer-email' => 'Contact E-mail address:',
	'mwoauth-consumer-description' => 'Application description:',
	'mwoauth-consumer-callbackurl' => 'OAuth "callback" URL:',
	'mwoauth-consumer-grantsneeded' => 'Grants used (JSON):',
	'mwoauth-consumer-wiki' => 'Single-wiki usage:',
	'mwoauth-consumer-restrictions' => 'Usage restictions (JSON):',
	'mwoauth-consumer-rsakey' => 'Consumer RSA key:',
	'mwoauth-consumer-secretkey' => 'Consumer secret token:',
	'mwoauth-consumer-reason' => 'Reason:',
	'mwoauth-consumer-alreadyexists' => 'A consumer with this name/version/author combination already exists',
	'mwoauth-consumer-not-accepted' => 'Cannot update information for a pending consumer request',
	'mwoauth-wrong-consumer-key' => 'The consumer key does not match the application name',
	'mwoauth-consumer-not-proposed' => 'The consumer is not currently proposed',
	'mwoauth-consumer-not-disabled' => 'The consumer is not currently disabled',
	'mwoauth-consumer-not-approved' => 'The consumer is not approved (it may have been disabled)',
	'mwoauth-invalid-consumer-key' => 'No consumer exists with the given key.',

	'mwoauth-consumer-stage-proposed' => 'proposed',
	'mwoauth-consumer-stage-rejected' => 'rejected',
	'mwoauth-consumer-stage-expired' => 'expired',
	'mwoauth-consumer-stage-approved' => 'approved',
	'mwoauth-consumer-stage-disabled' => 'disabled',
	'mwoauth-consumer-stage-suppressed' => 'suppressed',

	'mwoauthconsumerregistration' => 'OAuth consumer registration',
	'mwoauthconsumerregistration-propose-text' => 'Use the form below to propose a new OAuth consumer (see http://oauth.net).

A few recommendations and remarks:
* Try to use as few grants as possible. Avoid grants that are not actually needed now.
* Please provide an RSA key if possible; otherwise a (less secure) secret token will be assigned to you.
* Use the JSON restrictions field to limit access of this consumer to IP addresses in those CIDR ranges.
* You can use a wiki ID to restrict the consumer to a single wiki on this site (use "*" for all wikis).
* The email address provided must match that of your account (which must have been confirmed).',
	'mwoauthconsumerregistration-update-text' => 'Use the form below to update aspects of an OAuth consumer you control.

All values here will overwrite any previous ones. Do not leave blank fields unless you intend to clear those values.',
	'mwoauthconsumerregistration-maintext' => 'This page is meant for proposing and updating OAuth (see http://oauth.net) consumer applications in this site\'s registry.

From here, you can [[Special:MWOAuthConsumerRegistration/propose|propose a new consumer]].',
	'mwoauthconsumerregistration-propose-legend' => 'New OAuth consumer application',
	'mwoauthconsumerregistration-update-legend' => 'Update OAuth consumer application',
	'mwoauthconsumerregistration-propose-submit' => 'Propose consumer',
	'mwoauthconsumerregistration-update-submit' => 'Update consumer',
	'mwoauthconsumerregistration-proposed' => 'You consumer request was successfully recieved.

You have been assigned a consumer token of $1 and a secret token of $2. \'\'\'Please record these for future reference.\'\'\'',
	'mwoauthconsumerregistration-updated' => 'You consumer registry was successfully updated.',

	'mwoauthmanageconsumers' => 'Manage OAuth consumers',
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
	'mwoauthmanageconsumers-email' => 'Contact e-mail',
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
	'mwoauthmanageconsumers-reenable' => 'Re-enabled',
	'mwoauthmanageconsumers-reason' => 'Reason:',
	'mwoauthmanageconsumers-confirm-submit' => 'Update consumer status',
	'mwoauthmanageconsumers-viewing' => 'User "$1" is currently viewing this consumer',
	'mwoauthmanageconsumers-success-approved' => 'Request successfuly approved.',
	'mwoauthmanageconsumers-success-rejected' => 'Request successfuly rejected.',
	'mwoauthmanageconsumers-success-disabled' => 'Consumer successfuly disabled.',
	'mwoauthmanageconsumers-success-reanable' => 'Consumer successfuly re-enabled.',

	'mwoauth-logentry-consumer-propose' => 'proposed an OAuth consumer (consumer key $2)',
	'mwoauth-logentry-consumer-update' => 'updated an OAuth consumer (consumer key $2)',
	'mwoauth-logentry-consumer-approve' => 'approved an OAuth consumer by $1 (consumer key $2)',
	'mwoauth-logentry-consumer-reject' => 'rejected an OAuth consumer by $1 (consumer key $2)',
	'mwoauth-logentry-consumer-disable' => 'disabled an OAuth consumer by $1 (consumer key $2)',
	'mwoauth-logentry-consumer-reenable' => 're-enabled an OAuth consumer by $1 (consumer key $2)',

	'mwoauthconsumer-consumer-logpage' => 'OAuth consumer log',
	'mwoauthconsumer-consumer-logpagetext' => 'Log of approvals, rejections, and disabling of registered OAuth consumers.'
);

/** Asturian (asturianu)
 * @author Xuacu
 */
$messages['ast'] = array(
	'oauth-desc' => 'Autenticación API OAuth 1.0a',
);

/** Czech (česky)
 * @author Mormegil
 */
$messages['cs'] = array(
	'oauth-desc' => 'Autentizace pomocí rozhraní OAuth 1.0a',
);

/** German (Deutsch)
 * @author Se4598
 */
$messages['de'] = array(
	'oauth-desc' => 'OAuth 1.0a API Authentifikation',
);

/** French (français)
 * @author Gomoko
 */
$messages['fr'] = array(
	'oauth-desc' => 'API d’authentification OAuth 1.0a',
);

/** Galician (galego)
 * @author Toliño
 */
$messages['gl'] = array(
	'oauth-desc' => 'Autenticación API OAuth 1.0a',
);

/** Japanese (日本語)
 * @author Shirayuki
 */
$messages['ja'] = array(
	'oauth-desc' => 'OAuth 1.0a API 認証',
);

/** Macedonian (македонски)
 * @author Bjankuloski06
 */
$messages['mk'] = array(
	'oauth-desc' => 'Заверка со прилогот OAuth 1.0a',
);

/** tarandíne (tarandíne)
 * @author Joetaras
 */
$messages['roa-tara'] = array(
	'oauth-desc' => 'OAuth 1.0a API de autendicazione',
);
