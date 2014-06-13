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
	'OAuth' => array( 'أو_أوث', 'مو_أو_أوث' ),
);

/** Egyptian Spoken Arabic (مصرى) */
$specialPageAliases['arz'] = array(
	'OAuthConsumerRegistration' => array( 'تسجيل_او_اوت', 'تسجيل_مستهلك_او_اوت' ),
	'OAuthManageConsumers' => array( 'التحكم_بمستهلكى_او_اوت' ),
	'OAuthListConsumers' => array( 'عرض_مستهلكين_او_اوت' ),
	'OAuthManageMyGrants' => array( 'التحكم-بممنوحاتى_او_اوت', 'ممنوحات_او_اوت' ),
	'OAuth' => array( 'او-اوت', 'مو_او_اوت' ),
);

/** German (Deutsch) */
$specialPageAliases['de'] = array(
	'OAuthConsumerRegistration' => array( 'Verbraucherregistrierung' ),
	'OAuthManageConsumers' => array( 'Verbraucher_verwalten' ),
	'OAuthListConsumers' => array( 'Verbraucher_auflisten' ),
	'OAuthManageMyGrants' => array( 'Meine_Berechtigungen_verwalten' ),
	'OAuth' => array( 'OAuth' ),
);

/** Estonian (eesti) */
$specialPageAliases['et'] = array(
	'OAuthListConsumers' => array( 'OAuthi-rakenduste_loend' ),
	'OAuthManageMyGrants' => array( 'OAuthi-volitused' ),
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

/** Dutch (Nederlands) */
$specialPageAliases['nl'] = array(
	'OAuthConsumerRegistration' => array( 'OAuthGebruikerRegistratie' ),
	'OAuthManageConsumers' => array( 'OAuthBeheerGebruikers' ),
	'OAuthListConsumers' => array( 'OAuthLijstGebruikers' ),
);

/** Swedish (svenska) */
$specialPageAliases['sv'] = array(
	'OAuthListConsumers' => array( 'OAuth-applikationer' ),
	'OAuthManageMyGrants' => array( 'Anslutna_applikationer' ),
);

/** Yiddish (ייִדיש) */
$specialPageAliases['yi'] = array(
	'OAuthManageMyGrants' => array( 'פארוואלטן_פראיעקטן' ),
);

/** Simplified Chinese (中文（简体）‎) */
$specialPageAliases['zh-hans'] = array(
	'OAuthConsumerRegistration' => array( 'OAuth消费者注册', 'OAuth注册' ),
	'OAuthManageConsumers' => array( 'OAuth管理消费者' ),
	'OAuthListConsumers' => array( 'OAuth消费者列表' ),
	'OAuthManageMyGrants' => array( 'OAuth管理我的许可', 'OAuth许可' ),
);

/** Traditional Chinese (中文（繁體）‎) */
$specialPageAliases['zh-hant'] = array(
	'OAuthConsumerRegistration' => array( 'OAuth消費者登記' ),
	'OAuthManageConsumers' => array( 'OAuth消費者管理' ),
	'OAuthListConsumers' => array( 'OAuth消費者列表' ),
	'OAuthManageMyGrants' => array( 'OAuth消費者補助' ),
);