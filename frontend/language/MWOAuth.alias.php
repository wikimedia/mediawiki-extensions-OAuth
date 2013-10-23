<?php
/**
 * Aliases for extension OAuth
 *
 * @file
 * @ingroup Extensions
 */
// @codingStandardsIgnoreFile

$specialPageAliases = array();

/** English (English) */
$specialPageAliases['en'] = array(
	'OAuthConsumerRegistration' => array( 'OAuthConsumerRegistration', 'OAuthRegistration' ),
	'OAuthManageConsumers' => array( 'OAuthManageConsumers' ),
	'OAuthListConsumers' => array( 'OAuthListConsumers' ),
	'OAuthManageMyGrants' => array( 'OAuthManageMyGrants', 'OAuthGrants' ),
	'OAuth' => array( 'OAuth', 'MWOAuth' ),
);

/** German (Deutsch) */
$specialPageAliases['de'] = array(
	'OAuthConsumerRegistration' => array( 'Verbraucherregistrierung' ),
	'OAuthManageConsumers' => array( 'Verbraucher_verwalten' ),
	'OAuthListConsumers' => array( 'Verbraucher_auflisten' ),
	'OAuthManageMyGrants' => array( 'Meine_Berechtigungen_verwalten' ),
	'OAuth' => array( 'OAuth' ),
);

/** Korean (한국어) */
$specialPageAliases['ko'] = array(
	'OAuthConsumerRegistration' => array( 'MWO인증소비자등록' ),
	'OAuthManageConsumers' => array( 'MWO인증소비자관리' ),
	'OAuthManageMyGrants' => array( 'MWO인증내부여관리' ),
	'OAuth' => array( 'MWO인증' ),
);

/** Macedonian (македонски) */
$specialPageAliases['mk'] = array(
	'OAuthConsumerRegistration' => array( 'МВOAuthРегистрацијаПотрошувач' ),
	'OAuthManageConsumers' => array( 'МВOAuthРаководењеПотрошувач' ),
	'OAuthManageMyGrants' => array( 'OAuthРаководењеМоиДоделувања' ),
	'OAuth' => array( 'OAuth' ),
);
