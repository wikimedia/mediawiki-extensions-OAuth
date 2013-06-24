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
	'mwoauth-consumer-user' => 'Publisher:',
	'mwoauth-consumer-stage' => 'Current status:',
	'mwoauth-consumer-email' => 'Contact E-mail address:',
	'mwoauth-consumer-description' => 'Application description:',
	'mwoauth-consumer-callbackurl' => 'OAuth "callback" URL:',
	'mwoauth-consumer-grantsneeded' => 'Applicable grants (JSON):',
	'mwoauth-consumer-wiki' => 'Applicable wiki:',
	'mwoauth-consumer-restrictions' => 'Usage restictions (JSON):',
	'mwoauth-consumer-rsakey' => 'Consumer RSA key:',
	'mwoauth-consumer-secretkey' => 'Consumer secret token:',
	'mwoauth-consumer-accesstoken' => 'Access token:',
	'mwoauth-consumer-reason' => 'Reason:',
	'mwoauth-consumer-alreadyexists' => 'A consumer with this name/version/author combination already exists',
	'mwoauth-consumer-not-accepted' => 'Cannot update information for a pending consumer request',
	'mwoauth-wrong-consumer-key' => 'The consumer key does not match the application name',
	'mwoauth-consumer-not-proposed' => 'The consumer is not currently proposed',
	'mwoauth-consumer-not-disabled' => 'The consumer is not currently disabled',
	'mwoauth-consumer-not-approved' => 'The consumer is not approved (it may have been disabled)',
	'mwoauth-invalid-consumer-key' => 'No consumer exists with the given key.',
	'mwoauth-invalid-access-token' => 'No access token exists with the given key.',

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

	'mwoauthmanagemygrants' => 'Manage account OAuth grants',
	'mwoauthmanagemygrants-navigation' => 'Navigation:',
	'mwoauthmanagemygrants-showlist' => 'Consumer list',
	'mwoauthmanagemygrants-none' => 'No consumers have access on behalf of your account.',
	'mwoauthmanagemygrants-name' => 'Consumer name',
	'mwoauthmanagemygrants-user' => 'Publisher',
	'mwoauthmanagemygrants-description' => 'Description',
	'mwoauthmanagemygrants-wiki' => 'Applicable wiki',
	'mwoauthmanagemygrants-wikiallowed' => 'Allowed on wiki',
	'mwoauthmanagemygrants-grants' => 'Applicable grants',
	'mwoauthmanagemygrants-grantsallowed' => 'Grants allowed:',
	'mwoauthmanagemygrants-applicablegrantsallowed' => 'Applicable grants allowed:',
	'mwoauthmanagemygrants-consumerkey' => 'Consumer key',
	'mwoauthmanagemygrants-review' => 'Review/manage access',
	'mwoauthmanagemygrants-grantaccept' => 'Granted to consumer',
	'mwoauthmanagemygrants-confirm-text' => 'Use the form below to revoke access or grants for an OAuth consumer to act on your behalf.

Note that if you authorized a consumer to only have access to a subset of wikis (site projects), then there will be multiple access tokens for that consumer.',
	'mwoauthmanagemygrants-confirm-legend' => 'Manage consumer access token',
	'mwoauthmanagemygrants-update' => 'Update access token grants',
	'mwoauthmanagemygrants-renounce' => 'De-authorize and delete access token',
	'mwoauthmanagemygrants-action' => 'Change status:',
	'mwoauthmanagemygrants-confirm-submit' => 'Update access token status',
	'mwoauthmanagemygrants-success-update' => 'The access token for this consumer was successfully updated.',
	'mwoauthmanagemygrants-success-renounce' => 'The access token for this consumer was successfully deleted.',

	'mwoauth-logentry-consumer-propose' => 'proposed an OAuth consumer (consumer key $2)',
	'mwoauth-logentry-consumer-update' => 'updated an OAuth consumer (consumer key $2)',
	'mwoauth-logentry-consumer-approve' => 'approved an OAuth consumer by $1 (consumer key $2)',
	'mwoauth-logentry-consumer-reject' => 'rejected an OAuth consumer by $1 (consumer key $2)',
	'mwoauth-logentry-consumer-disable' => 'disabled an OAuth consumer by $1 (consumer key $2)',
	'mwoauth-logentry-consumer-reenable' => 're-enabled an OAuth consumer by $1 (consumer key $2)',

	'mwoauthconsumer-consumer-logpage' => 'OAuth consumer log',
	'mwoauthconsumer-consumer-logpagetext' => 'Log of approvals, rejections, and disabling of registered OAuth consumers.',


	'mwoauth-bad-csrf-token' => 'Session failure when submitting form. Please try your submissions again.',
	'mwoauth-bad-request' => 'There was an error in your OAuth request.',
	'mwoauthdatastore-access-token-not-found' => 'No approved grant was found for that authorization token',
	'mwoauthdatastore-request-token-not-found' => 'No request was found for that token',
	'mwoauthdatastore-bad-token' => 'No token was found matching your request',
	'mwoauthdatastore-bad-verifier' => 'The verification code provided was not valid',
	'mwoauthdatastore-invalid-token-type' => 'The requested token type is invalid',
	'mwoauthgrants-general-error' => 'There was an error in your OAuth request',
	'mwoauthserver-bad-consumer' => 'No approved cosumer was found for the provided key',
	'mwoauthserver-insufficient-rights' => 'You do not have sufficient rights to perform this action',
	'mwoauthserver-invalid-request-token' => 'Invalid token in your request',
	'mwoauthserver-invalid-user-hookabort' => 'This user cannot use OAuth',

	'mwoauth-form-description' => 'The following application is requesting to use MediaWiki on your behalf. The application will be able to perform any actions that are allowed with the list if reqested rights below. Only allow applications that you trust to use these permissions as you would.',
	'mwoauth-form-legal' => '',
	'mwoauth-form-button-approve' => 'Yes, Allow',
	'mwoauth-form-confirmation' => 'Allow this application to act on your behalf?',
	'mwoauth-authorize-form' => 'Application Details:',
	'mwoauth-authorize-form-user' => 'Application Author: $1',
	'mwoauth-authorize-form-name' => 'Application Name: $1',
	'mwoauth-authorize-form-description' => 'Application Description: $1',
	'mwoauth-authorize-form-version' => 'Application Version: $1',
	'mwoauth-authorize-form-wiki' => 'Wiki: $1',
	'mwoauth-grants-heading' => 'Requested Permissions: ',
	'mwoauth-grants-nogrants' => 'The application has not requested any permissions.',
	'mwoauth-grants-editpages' => 'Edit Pages',
	'mwoauth-grants-editmyinterface' => 'Edit Your Interface (Javascript and CSS) Pages',
	'mwoauth-grants-editinterface' => 'Edit MediaWiki\'s Interface (Javascript and CSS) Pages',
	'mwoauth-grants-movepages' => 'Move Pages',
	'mwoauth-grants-createpages' => 'Create Pages',
	'mwoauth-grants-deletepages' => 'Delete Pages',
	'mwoauth-grants-upload' => 'Upload Files',

);

/** Message documentation (Message documentation)
 * @author Shirayuki
 */
$messages['qqq'] = array(
	'mwoauth-missing-field' => 'Parameters:
* $1 - field name
See also:
* {{msg-mw|Mwoauth-invalid-field}}',
	'mwoauth-invalid-field' => 'Parameters:
* $1 - field name
See also:
* {{msg-mw|Mwoauth-missing-field}}',
	'mwoauth-consumer-key' => '{{Identical|Consumer key}}',
	'mwoauth-consumer-name' => '{{Identical|Application name}}',
	'mwoauth-consumer-version' => 'Used as label for the "Version" input box.',
	'mwoauth-consumer-user' => '{{Identical|Publisher}}',
	'mwoauth-consumer-stage' => '{{Identical|Current status}}',
	'mwoauth-consumer-email' => 'Used as label for the "Email address" input box.',
	'mwoauth-consumer-description' => 'Used as label for the "description" textarea.',
	'mwoauth-consumer-callbackurl' => 'Used as label for the "Callback URL" input box.

See [[w:Callback (computer programming)]].',
	'mwoauth-consumer-grantsneeded' => 'Used as label for the textarea.

The value is written in JSON format.',
	'mwoauth-consumer-wiki' => 'Used as label for the input box. The default value for the input box is "*".
{{Identical|Applicable wiki}}',
	'mwoauth-consumer-restrictions' => 'Used as label for the textarea. (The value is written in JSON format.)

Followed by the textarea or the message {{msg-mw|Mwoauthmanageconsumers-field-hidden}}.',
	'mwoauth-consumer-rsakey' => 'Used as label for the textarea.

Followed by the textarea or the message {{msg-mw|Mwoauthmanageconsumers-field-hidden}}.',
	'mwoauth-consumer-secretkey' => 'Used as label for the textarea.',
	'mwoauth-consumer-reason' => '{{Identical|Reason}}',
	'mwoauth-invalid-consumer-key' => 'Used as error message',
	'mwoauth-consumer-stage-rejected' => '{{Identical|Rejected}}',
	'mwoauth-consumer-stage-expired' => '{{Identical|Expired}}',
	'mwoauth-consumer-stage-approved' => '{{Identical|Approved}}',
	'mwoauth-consumer-stage-disabled' => '{{Identical|Disabled}}',
	'mwoauth-consumer-stage-suppressed' => '{{Identical|Suppressed}}',
	'mwoauthconsumerregistration' => '{{doc-special|MWOAuthConsumerRegistration}}',
	'mwoauthconsumerregistration-propose-legend' => 'Used as fieldset label.',
	'mwoauthconsumerregistration-update-legend' => 'Used as fieldset label.',
	'mwoauthconsumerregistration-propose-submit' => 'Used as label for the Submit button.',
	'mwoauthconsumerregistration-update-submit' => 'Used as label for the Submit button.',
	'mwoauthconsumerregistration-proposed' => 'Used as success message.

Parameters:
* $1 - consumer key
* $2 - secret key',
	'mwoauthmanageconsumers' => '{{doc-special|MWOAuthManageConsumers}}
{{Identical|Manage OAuth consumer}}',
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
	'mwoauthmanageconsumers-user' => '{{Identical|Publisher}}',
	'mwoauthmanageconsumers-description' => '{{Identical|Description}}',
	'mwoauthmanageconsumers-email' => 'Followed by an email address or the message {{msg-mw|Mwoauth-consumer-stage-suppressed}}.',
	'mwoauthmanageconsumers-consumerkey' => '{{Identical|Consumer key}}',
	'mwoauthmanageconsumers-lastchange' => '{{Identical|Last change}}',
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
{{Related|Mwoauthmanageconsumers}}',
	'mwoauthmanageconsumers-reason' => '{{Identical|Reason}}',
	'mwoauthmanageconsumers-confirm-submit' => 'Used as label for the Submit button.',
	'mwoauthmanageconsumers-viewing' => 'Parameters:
* $1 - username',
	'mwoauthmanagemygrants-navigation' => '{{Identical|Navigation}}',
	'mwoauthmanagemygrants-showlist' => 'Used as link text or as plain text',
	'mwoauthmanagemygrants-user' => '{{Identical|Publisher}}',
	'mwoauthmanagemygrants-description' => '{{Identical|Description}}',
	'mwoauthmanagemygrants-wiki' => '{{Identical|Applicable wiki}}',
	'mwoauthmanagemygrants-consumerkey' => '{{Identical|Consumer key}}',
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
	'mwoauthconsumer-consumer-logpage' => '{{doc-logpage}}',
	'mwoauthconsumer-consumer-logpagetext' => 'Description of the OAuth consumer log.',
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
	'mwoauth-desc' => 'OAuth 1.0a API Authentifikation',
	'mwoauth-missing-field' => 'Fehlender Wert für das Feld „$1“',
	'mwoauth-invalid-field' => 'Für das Feld „$1“ wurde ein ungültiger Wert angegeben',
	'mwoauth-field-hidden' => '(diese Information ist versteckt)',
	'mwoauth-field-private' => '(diese Information ist privat)',
	'mwoauth-consumer-key' => 'Verbraucherschlüssel:',
	'mwoauth-consumer-name' => 'Anwendungsname:',
	'mwoauth-consumer-version' => 'Hauptversion:',
	'mwoauth-consumer-user' => 'Herausgeber:',
	'mwoauth-consumer-stage' => 'Aktueller Status:',
	'mwoauth-consumer-email' => 'Kontakt-E-Mail-Adresse:',
	'mwoauth-consumer-description' => 'Anwendungsbeschreibung:',
	'mwoauth-consumer-callbackurl' => 'OAuth-Callback-URL:',
	'mwoauth-consumer-grantsneeded' => 'Anwendbare Bewilligungen (JSON):',
	'mwoauth-consumer-wiki' => 'Anwendbares Wiki:',
	'mwoauth-consumer-restrictions' => 'Benutzungsbeschränkungen (JSON):',
	'mwoauth-consumer-rsakey' => 'Verbraucher-RSA-Schlüssel:',
	'mwoauth-consumer-secretkey' => 'Geheimer Verbrauchertoken:',
	'mwoauth-consumer-accesstoken' => 'Zugriffstoken:',
	'mwoauth-consumer-reason' => 'Grund:',
	'mwoauth-consumer-alreadyexists' => 'Ein Verbraucher mit dieser Namen-/Versions-/Autorenkombination ist bereits vorhanden',
	'mwoauth-consumer-not-accepted' => 'Die Informationen für einen ausstehenden Verbraucherantrag konnten nicht aktualisiert werden',
	'mwoauth-wrong-consumer-key' => 'Der Verbraucherschlüssel entspricht nicht dem Anwendungsnamen',
	'mwoauth-consumer-not-proposed' => 'Der Verbraucher ist derzeit nicht geplant',
	'mwoauth-consumer-not-disabled' => 'Der Verbraucher ist derzeit nicht deaktiviert',
	'mwoauth-consumer-not-approved' => 'Der Verbraucher ist nicht bestätigt (vielleicht wurde er deaktiviert)',
	'mwoauth-invalid-consumer-key' => 'Es ist kein Verbraucher mit dem angegebenen Schlüssel vorhanden.',
	'mwoauth-invalid-access-token' => 'Es ist kein Zugriffstoken mit dem angegebenen Schlüssel vorhanden.',
	'mwoauth-consumer-stage-proposed' => 'geplant',
	'mwoauth-consumer-stage-rejected' => 'abgelehnt',
	'mwoauth-consumer-stage-expired' => 'abgelaufen',
	'mwoauth-consumer-stage-approved' => 'bestätigt',
	'mwoauth-consumer-stage-disabled' => 'deaktiviert',
	'mwoauth-consumer-stage-suppressed' => 'unterdrückt',
	'mwoauthconsumerregistration' => 'OAuth-Verbraucherregistrierung',
	'mwoauthconsumerregistration-propose-text' => 'Verwende das unten stehende Formular, um einen neuen OAuth-Verbraucher zu planen (siehe http://oauth.net).

Hier einige Empfehlungen und Bemerkungen:
* Versuche, so wenig Bewilligungen wie möglich zu verwenden. Vermeide Bewilligungen, die in Wirklichkeit nicht benötigt werden.
* Bitte gib einen RSA-Schlüssel an, falls möglich. Anderenfalls wird dir ein weniger sicherer Geheimtoken zugewiesen.
* Verwende das JSON-Beschränkungsfeld, um den Zugriff dieses Verbrauchers auf IP-Adressen in diesen CIDR-Bereichen zu beschränken.
* Du kannst eine Wikikennung verwenden, um den Verbraucher auf ein einzelnes Wiki auf dieser Website zu beschränken (verwende „*“ für alle Wikis).
* Die angegebene E-Mail-Adresse muss mit der deines Benutzerkontos übereinstimmen und bestätigt sein.',
	'mwoauthconsumerregistration-update-text' => 'Verwende das unten stehende Formular, um Aspekte eines von dir kontrollierten OAuth-Verbrauchers zu aktualisieren.

Alle Werte hier überschreiben alle vorherigen. Hinterlasse keine leeren Felder, außer du beabsichtigst, diese Werte zu löschen.',
	'mwoauthconsumerregistration-maintext' => 'Diese Seite ist gedacht zur Planung und Aktualisierung von OAuth-Verbraucheranwendungen (siehe http://oauth.net) in der Websiteregistrierung.

Du kannst von hier [[Special:MWOAuthConsumerRegistration/propose|einen neuen Verbraucher planen]].',
	'mwoauthconsumerregistration-propose-legend' => 'Neue OAuth-Verbraucheranwendung',
	'mwoauthconsumerregistration-update-legend' => 'OAuth-Verbraucheranwendung aktualisieren',
	'mwoauthconsumerregistration-propose-submit' => 'Verbraucher planen',
	'mwoauthconsumerregistration-update-submit' => 'Verbraucher aktualisieren',
	'mwoauthconsumerregistration-proposed' => "Wir haben deinen Verbraucherantrag erhalten.

Dir wurde der Verbrauchertoken $1 und der Geheimtoken $2 zugewiesen. '''Bitte diese für die Zukunft aufbewahren.'''",
	'mwoauthconsumerregistration-updated' => 'Deine Verbraucherregistrierung wurde erfolgreich aktualisiert.',
	'mwoauthmanageconsumers' => 'OAuth-Verbraucher verwalten',
	'mwoauthmanageconsumers-type' => 'Warteschlangen:',
	'mwoauthmanageconsumers-showproposed' => 'Geplante Anträge',
	'mwoauthmanageconsumers-showrejected' => 'Abgelehnte Anträge',
	'mwoauthmanageconsumers-showexpired' => 'Abgelaufene Anträge',
	'mwoauthmanageconsumers-main' => 'Haupt',
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
	'mwoauthmanageconsumers-reenable' => 'Reaktiviert',
	'mwoauthmanageconsumers-reason' => 'Grund:',
	'mwoauthmanageconsumers-confirm-submit' => 'Verbraucherstatus aktualisieren',
	'mwoauthmanageconsumers-viewing' => '„$1“ betrachtet derzeit diesen Verbraucher',
	'mwoauthmanageconsumers-success-approved' => 'Antrag erfolgreich bestätigt.',
	'mwoauthmanageconsumers-success-rejected' => 'Antrag erfolgreich abgelehnt.',
	'mwoauthmanageconsumers-success-disabled' => 'Verbraucher erfolgreich deaktiviert.',
	'mwoauthmanageconsumers-success-reanable' => 'Verbraucher erfolgreich reaktiviert.',
	'mwoauthmanagemygrants' => 'Benutzerkonten-OAuth-Bewilligungen verwalten',
	'mwoauthmanagemygrants-navigation' => 'Navigation:',
	'mwoauthmanagemygrants-showlist' => 'Verbraucherliste',
	'mwoauthmanagemygrants-none' => 'Keine Verbraucher haben Zugriff im Namen deines Benutzerkontos.',
	'mwoauthmanagemygrants-name' => 'Verbrauchername',
	'mwoauthmanagemygrants-user' => 'Herausgeber',
	'mwoauthmanagemygrants-description' => 'Beschreibung',
	'mwoauthmanagemygrants-wiki' => 'Anwendbares Wiki',
	'mwoauthmanagemygrants-wikiallowed' => 'Erlaubt auf Wiki',
	'mwoauthmanagemygrants-grants' => 'Anwendbare Bewilligungen',
	'mwoauthmanagemygrants-grantsallowed' => 'Erlaubte Bewilligungen:',
	'mwoauthmanagemygrants-applicablegrantsallowed' => 'Erlaubte anwendbare Bewilligungen:',
	'mwoauthmanagemygrants-consumerkey' => 'Verbraucherschlüssel',
	'mwoauthmanagemygrants-review' => 'Zugriff überprüfen/verwalten',
	'mwoauthmanagemygrants-grantaccept' => 'Dem Verbraucher bewilligt',
	'mwoauthmanagemygrants-confirm-text' => 'Verwende das unten stehende Formular, um den Zugriff oder Bewilligungen für einen OAuth-Verbraucher zu entziehen, der auf deinen Namen handelt.

Falls du nur einen Verbraucher autorisiert hast, um Zugriff auf eine Wikiuntergruppe (Websiteprojekte) zu haben, gibt es für diesen Verbraucher mehrere Zugriffstokens.',
	'mwoauthmanagemygrants-confirm-legend' => 'Verbraucherzugriffstoken verwalten',
	'mwoauthmanagemygrants-update' => 'Zugriffstokenbewilligungen aktualisieren',
	'mwoauthmanagemygrants-renounce' => 'Deautorisieren und Zugriffstoken löschen',
	'mwoauthmanagemygrants-action' => 'Status ändern:',
	'mwoauthmanagemygrants-confirm-submit' => 'Zugriffstokenstatus aktualisieren',
	'mwoauthmanagemygrants-success-update' => 'Der Zugriffstoken für diesen Verbraucher wurde erfolgreich aktualisiert.',
	'mwoauthmanagemygrants-success-renounce' => 'Der Zugriffstoken für diesen Verbraucher wurde erfolgreich gelöscht.',
	'mwoauth-logentry-consumer-propose' => 'plante einen OAuth-Verbraucher (Verbraucherschlüssel $2)',
	'mwoauth-logentry-consumer-update' => 'aktualisierte einen OAuth-Verbraucher (Verbraucherschlüssel $2)',
	'mwoauth-logentry-consumer-approve' => 'bestätigte einen OAuth-Verbraucher von $1 (Verbraucherschlüssel $2)',
	'mwoauth-logentry-consumer-reject' => 'lehnte einen OAuth-Verbraucher von $1 ab (Verbraucherschlüssel $2)',
	'mwoauth-logentry-consumer-disable' => 'deaktivierte einen OAuth-Verbraucher von $1 (Verbraucherschlüssel $2)',
	'mwoauth-logentry-consumer-reenable' => 'reaktivierte einen OAuth-Verbraucher von $1 (Verbraucherschlüssel $2)',
	'mwoauthconsumer-consumer-logpage' => 'OAuth-Verbraucher-Logbuch',
	'mwoauthconsumer-consumer-logpagetext' => 'Logbuch von Bestätigungen, Ablehnungen und Deaktivierungen registrierter OAuth-Verbraucher.',
);

/** French (français)
 * @author Gomoko
 * @author Louperivois
 */
$messages['fr'] = array(
	'mwoauth-desc' => 'API d’authentification OAuth 1.0a',
	'mwoauth-missing-field' => 'Valeur manquante pour le champ « $1 »',
	'mwoauth-invalid-field' => 'Valeur invalide fournie pour le champ « $1 »',
	'mwoauth-field-hidden' => '(cette information est masquée)',
	'mwoauth-field-private' => '(cette information est privée)',
	'mwoauth-consumer-key' => 'Clé du consommateur :',
	'mwoauth-consumer-name' => "Nom de l'application :",
	'mwoauth-consumer-version' => 'Version majeure :',
	'mwoauth-consumer-user' => 'Éditeur :',
	'mwoauth-consumer-stage' => 'Statut actuel :',
	'mwoauth-consumer-email' => 'Adresse de courriel de contact :',
	'mwoauth-consumer-description' => "Description de l'application :",
	'mwoauth-consumer-callbackurl' => 'URl « de rappel » pour OAuth :',
	'mwoauth-consumer-grantsneeded' => 'Droits applicables (JSON) :',
	'mwoauth-consumer-wiki' => 'Wiki applicable :',
	'mwoauth-consumer-restrictions' => 'Limitations d’utilisation (JSON) :',
	'mwoauth-consumer-rsakey' => 'Clé RSA du consommateur :',
	'mwoauth-consumer-secretkey' => 'Jeton secret du consommateur :',
	'mwoauth-consumer-accesstoken' => 'Jeton d’accès :',
	'mwoauth-consumer-reason' => 'Motif :',
	'mwoauth-consumer-alreadyexists' => 'Un consommateur avec cette combinaison de nom/version/autorisation existe déjà',
	'mwoauth-consumer-not-accepted' => 'Impossible de mettre à jour les informations pour une demande de consommateur en cours',
	'mwoauth-wrong-consumer-key' => 'La clé du consommateur ne correspond pas au nom de l’application',
	'mwoauth-consumer-not-proposed' => 'Le consommateur n’est actuellement pas proposé',
	'mwoauth-consumer-not-disabled' => 'Le consommateur n’est pas désactivé pour le moment',
	'mwoauth-consumer-not-approved' => 'Le consommateur n’est pas approuvé (il peut avoir été désactivé)',
	'mwoauth-invalid-consumer-key' => 'Aucun consommateur n’existe avec la clé fournie.',
	'mwoauth-invalid-access-token' => 'Aucun jeton d’accès n’existe pour la clé fournie',
	'mwoauth-consumer-stage-proposed' => 'proposé',
	'mwoauth-consumer-stage-rejected' => 'rejeté',
	'mwoauth-consumer-stage-expired' => 'expiré',
	'mwoauth-consumer-stage-approved' => 'approuvé',
	'mwoauth-consumer-stage-disabled' => 'désactivé',
	'mwoauth-consumer-stage-suppressed' => 'supprimé',
	'mwoauthconsumerregistration' => 'Inscription du consommateur OAuth',
	'mwoauthconsumerregistration-propose-text' => 'Utilisez le formulaire ci-dessous pour proposer un nouveau consommateur OAuth (voir http://oauth.net).

Quelques recommandations et remarques :
* Essayez d’utiliser le moins de droits possibles. Évitez les droits qui ne sont pas vraiment nécessaires pour le moment.
* Veuillez fournir une clé RSA si possible ; sinon, un jeton secret (moins sécurisé) vous sera assigné.
* Utilisez le champ limitations JSON pour limiter l’accès de ce consommateur aux adresses IP dans ces plages de CIDR.
* Vous pouvez utiliser un ID de wiki pour limiter ce consommateur à un unique wiki de ce site (utilisez "*" pour tous les wikis).
* L’adresse de courriel fournie doit correspondre à celle de votre compte (qui doit avoir été confirmée).',
	'mwoauthconsumerregistration-update-text' => 'Utilisez le formulaire ci-dessous pour mettre à jour les aspects d’un consommateur OAuth que vous contrôlez.

Toutes les valeurs ici écraseront les précédentes. Ne laissez aucun champ blanc sauf si vous désirez vraiment effacer ces valeurs.',
	'mwoauthconsumerregistration-maintext' => 'Cette page a pour but de proposer et mettre à jour des applications consommatrices OAuth (voir http://oauth.net) dans le registre de ce site.

Depuis ici, vous pouvez [[Special:MWOAuthConsumerRegistration/propose|proposer un nouveau consommateur]].',
	'mwoauthconsumerregistration-propose-legend' => 'Nouvelle application consommatrice OAuth',
	'mwoauthconsumerregistration-update-legend' => 'Mettre à jour l’application consommatrice OAuth',
	'mwoauthconsumerregistration-propose-submit' => 'Proposer un consommateur',
	'mwoauthconsumerregistration-update-submit' => 'Mettre à jour un consommateur',
	'mwoauthconsumerregistration-proposed' => "Votre demande de consommateur a bien été reçue.

Il vous a été assigné un jeton de consommateur $1 et un jeton secret $2. '''Veuillez les conserver pour des besoins ultérieurs.'''",
	'mwoauthconsumerregistration-updated' => 'Votre registre de consommateur a bien été mis à jour.',
	'mwoauthmanageconsumers' => 'Gérer les consommateurs OAuth',
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
	'mwoauthmanageconsumers-reenable' => 'Réactivé',
	'mwoauthmanageconsumers-reason' => 'Motif :',
	'mwoauthmanageconsumers-confirm-submit' => 'Mettre à jour l’état du consommateur',
	'mwoauthmanageconsumers-viewing' => 'L’utilisateur « $1 » est actuellement en train de visualiser ce consommateur',
	'mwoauthmanageconsumers-success-approved' => 'Requête approuvée avec succès.',
	'mwoauthmanageconsumers-success-rejected' => 'Requête rejetée avec succès.',
	'mwoauthmanageconsumers-success-disabled' => 'Le consommateur a bien été désactivé.',
	'mwoauthmanageconsumers-success-reanable' => 'Le consommateur a bien été réactivé.',
	'mwoauthmanagemygrants' => 'Gérer les droits de compte OAuth',
	'mwoauthmanagemygrants-navigation' => 'Navigation :',
	'mwoauthmanagemygrants-showlist' => 'Liste de consommateurs',
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
	'mwoauthmanagemygrants-confirm-text' => 'Utilisez le formulaire ci-dessous pour révoquer l’accès ou les droits d’un consommateur OAuth à agir en votre nom.

Notez bien que si vous autorisez un consommateur à n’avoir accès qu’à un sous-ensemble de wikis (projets de site), alors il y aura des jetons d’accès multiples pour ce consommateur.',
	'mwoauthmanagemygrants-confirm-legend' => 'Gérer le jeton d’accès du consommateur',
	'mwoauthmanagemygrants-update' => 'Mettre à jour les droits du jeton d’accès',
	'mwoauthmanagemygrants-renounce' => 'Ne plus autoriser et supprimer le jeton d’accès',
	'mwoauthmanagemygrants-action' => 'Modifier l’état :',
	'mwoauthmanagemygrants-confirm-submit' => 'Mettre à jour l’état du jeton d’accès',
	'mwoauthmanagemygrants-success-update' => 'Le jeton d’accès pour ce consommateur a bien été mis à jour.',
	'mwoauthmanagemygrants-success-renounce' => 'Le jeton d’accès pour ce consommateur a bien été supprimé.',
	'mwoauth-logentry-consumer-propose' => 'un consommateur OAuth proposé (clé du consommateur $2)',
	'mwoauth-logentry-consumer-update' => 'un consommateur OAuth mis à jour (clé du consommateur $2)',
	'mwoauth-logentry-consumer-approve' => 'un consommateur OAuth approuvé par $1 (clé du consommateur $2)',
	'mwoauth-logentry-consumer-reject' => 'un consommateur OAuth rejeté par $1 (clé du consommateur $2)',
	'mwoauth-logentry-consumer-disable' => 'un consommateur OAuth désactivé par $1 (clé du consommateur $2)',
	'mwoauth-logentry-consumer-reenable' => 'un consommateur OAuth réactivé par $1 (clé du consommateur $2)',
	'mwoauthconsumer-consumer-logpage' => 'journal du consommateur OAuth',
	'mwoauthconsumer-consumer-logpagetext' => 'Journal des approbations, rejets et désactivations de consommateurs OAuth enregistrés.',
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
	'mwoauth-desc' => 'OAuth 1.0a API 認証',
	'mwoauth-missing-field' => '「$1」フィールドの値がありません',
	'mwoauth-invalid-field' => '「$1」フィールドに指定した値は無効です',
	'mwoauth-field-hidden' => '(この情報は非表示です)',
	'mwoauth-field-private' => '(この情報は非公開です)',
	'mwoauth-consumer-key' => 'コンシューマー キー:',
	'mwoauth-consumer-name' => 'アプリケーション名:',
	'mwoauth-consumer-version' => 'メジャー バージョン:',
	'mwoauth-consumer-user' => '発行者:',
	'mwoauth-consumer-stage' => '現在の状態:',
	'mwoauth-consumer-email' => '連絡先メールアドレス:',
	'mwoauth-consumer-description' => 'アプリケーションの説明:',
	'mwoauth-consumer-callbackurl' => 'OAuth コールバック URL:',
	'mwoauth-consumer-rsakey' => 'コンシューマー RSA キー:',
	'mwoauth-consumer-secretkey' => 'コンシューマー秘密トークン:',
	'mwoauth-consumer-accesstoken' => 'アクセス トークン:',
	'mwoauth-consumer-reason' => '理由:',
	'mwoauth-invalid-consumer-key' => '指定したキーのコンシューマーは存在しません。',
	'mwoauth-consumer-stage-disabled' => '無効',
	'mwoauthconsumerregistration' => 'OAuth コンシューマー登録',
	'mwoauthconsumerregistration-update-legend' => 'OAuth コンシューマー アプリケーションの更新',
	'mwoauthconsumerregistration-update-submit' => 'コンシューマーを更新',
	'mwoauthmanageconsumers' => 'OAuthコンシューマー管理',
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
	'mwoauthmanagemygrants-navigation' => 'ナビゲーション:',
	'mwoauthmanagemygrants-showlist' => 'コンシューマー一覧',
	'mwoauthmanagemygrants-name' => 'コンシューマー名',
	'mwoauthmanagemygrants-user' => '発行者',
	'mwoauthmanagemygrants-description' => '説明',
	'mwoauthmanagemygrants-consumerkey' => 'コンシューマー キー',
	'mwoauthmanagemygrants-confirm-legend' => 'コンシューマー アクセス トークンの管理',
	'mwoauthmanagemygrants-action' => '状態の変更:',
	'mwoauthmanagemygrants-confirm-submit' => 'アクセス トークンの状態を更新',
	'mwoauth-logentry-consumer-update' => 'OAuth コンシューマーを更新 (コンシューマー キー $2)',
	'mwoauth-logentry-consumer-approve' => '$1 による OAuth コンシューマーを承認 (コンシューマー キー $2)',
	'mwoauth-logentry-consumer-reject' => '$1 による OAuth コンシューマーを却下 (コンシューマー キー $2)',
	'mwoauth-logentry-consumer-disable' => '$1 による OAuth コンシューマーを無効化 (コンシューマー キー $2)',
	'mwoauth-logentry-consumer-reenable' => '$1 による OAuth コンシューマーを再有効化 (コンシューマー キー $2)',
	'mwoauthconsumer-consumer-logpage' => 'OAuth コンシューマー記録',
);

/** Luxembourgish (Lëtzebuergesch)
 * @author Robby
 */
$messages['lb'] = array(
	'mwoauth-missing-field' => 'De Wäert fir d\'Feld "$1" feelt',
	'mwoauth-consumer-name' => 'Numm vun der Applicatioun:',
	'mwoauth-consumer-reason' => 'Grond:',
	'mwoauth-consumer-stage-proposed' => 'geplangt',
	'mwoauth-consumer-stage-rejected' => 'refuséiert',
	'mwoauth-consumer-stage-expired' => 'ofgelaf',
	'mwoauthmanageconsumers-main' => 'Haapt',
	'mwoauthmanageconsumers-name' => 'Konsument',
	'mwoauthmanageconsumers-description' => 'Beschreiwung',
	'mwoauthmanageconsumers-lastchange' => 'Lescht Ännerung',
	'mwoauthmanageconsumers-review' => 'nokucken/geréieren',
	'mwoauthmanageconsumers-disable' => 'Desaktivéiert',
	'mwoauthmanageconsumers-reason' => 'Grond:',
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
	'mwoauth-consumer-key' => 'Потрошувачки клуч:',
	'mwoauth-consumer-name' => 'Назив на прилогот:',
	'mwoauth-consumer-version' => 'Главна верзија:',
	'mwoauth-consumer-user' => 'Издавач:',
	'mwoauth-consumer-stage' => 'Тековен статус:',
	'mwoauth-consumer-email' => 'Е-пошта за контакт:',
	'mwoauth-consumer-description' => 'Опис на прилогот:',
	'mwoauth-consumer-callbackurl' => 'URL-адреса за повикување на OAuth:',
	'mwoauth-consumer-grantsneeded' => 'Применливи доделувања (JSON):',
	'mwoauth-consumer-wiki' => 'Применливо вики:',
	'mwoauth-consumer-restrictions' => 'Ограничувања на употребата (JSON):',
	'mwoauth-consumer-rsakey' => 'Потрошувачки RSA-клуч:',
	'mwoauth-consumer-secretkey' => 'Таен потрошувачки жетон:',
	'mwoauth-consumer-accesstoken' => 'Пристапен жетон:',
	'mwoauth-consumer-reason' => 'Причина:',
	'mwoauth-consumer-alreadyexists' => 'Веќе постои потрошувач со ваква комбинација од име/верзија/автор',
	'mwoauth-consumer-not-accepted' => 'Не можам да ги изменам информациите за потрошувачко барање во исчекување',
	'mwoauth-wrong-consumer-key' => 'Потрошувачкиот клуч не одговара на називот на прилогот',
	'mwoauth-consumer-not-proposed' => 'Потрошувачот во моментов не е предложен',
	'mwoauth-consumer-not-disabled' => 'Потрошувачот во моментов не е оневозможен',
	'mwoauth-consumer-not-approved' => 'Потрошувачот не е одобрен (може да е оневозможен)',
	'mwoauth-invalid-consumer-key' => 'Не постои потрошувач со таков клуч.',
	'mwoauth-invalid-access-token' => 'Не постои пристапен жетон со таков клуч.',
	'mwoauth-consumer-stage-proposed' => 'предложен',
	'mwoauth-consumer-stage-rejected' => 'одбиен',
	'mwoauth-consumer-stage-expired' => 'истечен',
	'mwoauth-consumer-stage-approved' => 'одобрен',
	'mwoauth-consumer-stage-disabled' => 'оневозможен',
	'mwoauth-consumer-stage-suppressed' => 'притаен',
	'mwoauthconsumerregistration' => 'Регистрација на потрошувач на OAuth',
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

Од овде можете да [[Special:MWOAuthConsumerRegistration/propose|предложите нов потрошувач]].',
	'mwoauthconsumerregistration-propose-legend' => 'Нов кориснички прилог за OAuth',
	'mwoauthconsumerregistration-update-legend' => 'Измена на кориснички прилог за OAuth',
	'mwoauthconsumerregistration-propose-submit' => 'Предложи потрошувач',
	'mwoauthconsumerregistration-update-submit' => 'Измени потрошувач',
	'mwoauthconsumerregistration-proposed' => "Вашето потрошувачко барање е успешно примено.

Вашиот потрошувачки жетон гласи $1, а тајниот жетон гласи $2. '''Зачувајте ги бидејќи може да ви затребаат во иднина.'''",
	'mwoauthconsumerregistration-updated' => 'Вашиот потрошувачки регистар е успешно изменет.',
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
	'mwoauthmanageconsumers-reenable' => 'Преовозможен',
	'mwoauthmanageconsumers-reason' => 'Причина:',
	'mwoauthmanageconsumers-confirm-submit' => 'Измени потр. статус',
	'mwoauthmanageconsumers-viewing' => 'Корисникот „$1“ во моментов го гледа потрошувачов',
	'mwoauthmanageconsumers-success-approved' => 'Барањето е успешно одобрено.',
	'mwoauthmanageconsumers-success-rejected' => 'Барањето е успешно одбиено.',
	'mwoauthmanageconsumers-success-disabled' => 'Потрошувачот е успешно оневозможен.',
	'mwoauthmanageconsumers-success-reanable' => 'Потрошувачот е успешно преовозможен.',
	'mwoauthmanagemygrants' => 'Раководење со доделувања на OAuth на сметки',
	'mwoauthmanagemygrants-navigation' => 'Навигација:',
	'mwoauthmanagemygrants-showlist' => 'Список на потрошувачи',
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
	'mwoauthmanagemygrants-confirm-text' => 'Следниот образец служи за одземање на пристап или доделен OAuth на потрошувач за да дејствува во ваше име.

Имајте предвид дека ако овластите некого со пристап на само дел од викијата (проектите) наместо сите во целина, тогаш тој потрошувач ќе има повеќе пристапни жетони.',
	'mwoauthmanagemygrants-confirm-legend' => 'Ракободење со жетон за кориснички пристап',
	'mwoauthmanagemygrants-update' => 'Измени доделувања на прист. жетон',
	'mwoauthmanagemygrants-renounce' => 'Одземи дозвола и избриши пристап. жетон',
	'mwoauthmanagemygrants-action' => 'Смени статус:',
	'mwoauthmanagemygrants-confirm-submit' => 'Измени статус на пристап. жетон',
	'mwoauthmanagemygrants-success-update' => 'Пристапниот жетон на овој потрошувач е успешно изменет.',
	'mwoauthmanagemygrants-success-renounce' => 'Пристапниот жетон на овој потрошувач е успешно избришан.',
	'mwoauth-logentry-consumer-propose' => 'предложен потрошувач на OAuth (потрошувачки клуч: $2)',
	'mwoauth-logentry-consumer-update' => 'изменет потрошувач на OAuth (потрошувачки клуч: $2)',
	'mwoauth-logentry-consumer-approve' => '$1 одобри потрошувач на OAuth (потрошувачки клуч: $2)',
	'mwoauth-logentry-consumer-reject' => '$1 одби потрошувач на OAuth (потрошувачки клуч: $2)',
	'mwoauth-logentry-consumer-disable' => '$1 оневозможи потрошувач на OAuth (потрошувачки клуч: $2)',
	'mwoauth-logentry-consumer-reenable' => '$1 преовозможи потрошувач на OAuth (потрошувачки клуч: $2)',
	'mwoauthconsumer-consumer-logpage' => 'Потрошувачки дневник за OAuth',
	'mwoauthconsumer-consumer-logpagetext' => 'Дневник на одобрувања, одбивања и оневозможувања на регистрирани потрошувачи на OAuth.',
);

/** Malayalam (മലയാളം)
 * @author Raghith
 */
$messages['ml'] = array(
	'mwoauthmanagemygrants-user' => 'പ്രസാധക(ൻ)',
);

/** Polish (polski)
 * @author Chrumps
 */
$messages['pl'] = array(
	'mwoauth-consumer-reason' => 'Powód:',
	'mwoauthmanageconsumers-reason' => 'Powód:',
);

/** tarandíne (tarandíne)
 * @author Joetaras
 */
$messages['roa-tara'] = array(
	'mwoauth-desc' => 'OAuth 1.0a API de autendicazione',
	'mwoauth-missing-field' => 'Valore zumbate pu cambe "$1"',
	'mwoauth-invalid-field' => 'Valore date invalide pu cambe "$1"',
	'mwoauth-consumer-key' => "Chiave d'u consumatore:",
	'mwoauth-consumer-name' => "Nome de l'applicazione:",
	'mwoauth-consumer-version' => 'Versione prengepàle:',
	'mwoauth-consumer-stage' => 'State de mò:',
	'mwoauth-consumer-email' => 'Indirizze email de condatte:',
	'mwoauth-consumer-description' => "Descrizione de l'applicazione:",
	'mwoauth-consumer-reason' => 'Mutive:',
	'mwoauth-consumer-stage-proposed' => 'proposte',
	'mwoauth-consumer-stage-rejected' => 'scettate',
	'mwoauth-consumer-stage-expired' => 'scadute',
	'mwoauth-consumer-stage-approved' => 'approvate',
	'mwoauth-consumer-stage-disabled' => 'disabbilitate',
	'mwoauth-consumer-stage-suppressed' => 'scangellate',
	'mwoauthmanageconsumers-main' => 'Prengepàle',
	'mwoauthmanageconsumers-reason' => 'Mutive:',
);
