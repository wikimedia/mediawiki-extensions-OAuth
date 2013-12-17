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

/** Arabic (العربية) */
$specialPageAliases['ar'] = array(
	'OAuthConsumerRegistration' => array( 'تسجيل_مستهلك_أو_أوث', 'تسجيل_أو_أوث' ),
	'OAuthManageConsumers' => array( 'التحكم_بمستهلكي_أو_أوث' ),
	'OAuthListConsumers' => array( 'عرض_مستهلكي_أو_أوث' ),
	'OAuthManageMyGrants' => array( 'التحكم_بممنوحاتي_أو_أوث', 'ممنوحات_أو_أوث' ),
);

/** German (Deutsch) */
$specialPageAliases['de'] = array(
	'OAuthConsumerRegistration' => array( 'Verbraucherregistrierung' ),
	'OAuthManageConsumers' => array( 'Verbraucher_verwalten' ),
	'OAuthListConsumers' => array( 'Verbraucher_auflisten' ),
	'OAuthManageMyGrants' => array( 'Meine_Berechtigungen_verwalten' ),
	'OAuth' => array( 'OAuth' ),
);

/** Persian (فارسی) */
$specialPageAliases['fa'] = array(
	'OAuthConsumerRegistration' => array( 'ثبت_OAuth' ),
	'OAuthManageConsumers' => array( 'مدیریت_مصرف‌کننده‌های_OAuth' ),
	'OAuthListConsumers' => array( 'فهرست_مصرف‌کننده‌های_OAuth' ),
	'OAuthManageMyGrants' => array( 'مدیریت_اعطاهای_OAuth_من' ),
	'OAuth' => array( 'OAuth', 'MWOAuth' ),
);

/** Galician (galego) */
$specialPageAliases['gl'] = array(
	'OAuthManageMyGrants' => array( 'Administrar_as_concesión_de_conta_OAuth' ),
);

/** Hebrew (עברית) */
$specialPageAliases['he'] = array(
	'OAuthListConsumers' => array( 'יישומי_OAuth', 'רשימות_יישומי_OAuth' ),
	'OAuthManageMyGrants' => array( 'ניהול_יישומים', 'ניהול_יישומים_מחוברים' ),
);

/** Korean (한국어) */
$specialPageAliases['ko'] = array(
	'OAuthConsumerRegistration' => array( 'OAuth컨슈머등록', 'OAuth등록' ),
	'OAuthManageConsumers' => array( 'OAuth컨슈머관리' ),
	'OAuthListConsumers' => array( 'OAuth컨슈머목록' ),
	'OAuthManageMyGrants' => array( 'OAuth내부여관리' ),
	'OAuth' => array( 'MWO인증' ),
);

/** Macedonian (македонски) */
$specialPageAliases['mk'] = array(
	'OAuthConsumerRegistration' => array( 'МВOAuthРегистрацијаПотрошувач' ),
	'OAuthManageConsumers' => array( 'МВOAuthРаководењеПотрошувач' ),
	'OAuthListConsumers' => array( 'OAuthНаведиПотрошувачи' ),
	'OAuthManageMyGrants' => array( 'OAuthРаководењеМоиДоделувања' ),
	'OAuth' => array( 'OAuth' ),
);

/** Simplified Chinese (中文（简体）‎) */
$specialPageAliases['zh-hans'] = array(
	'OAuthConsumerRegistration' => array( 'OAuth消费者注册', 'OAuth注册' ),
	'OAuthListConsumers' => array( 'OAuth消费者列表' ),
);