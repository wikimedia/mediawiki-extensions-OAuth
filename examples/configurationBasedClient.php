<?php

/**
 * Examples of apps managed via configuration.
 * NOTE: this intentionally avoids using constants like Consumer::FIELD_* because the autoloader
 * might not be fully initialized at the point when configuration is parsed.
 * TODO: mechanism for obtaining owner-only access tokens
 * @see \MediaWiki\Extension\OAuth\Repository\ArrayConsumerRepository::addArray()
 * @see \MediaWiki\Extension\OAuth\Control\ConsumerValidator::expandConsumerData()
 */

// Make sure this file doesn't accidentally get executed somehow.
// phpcs:disable Squiz.PHP.NonExecutableCode.Unreachable
die();

// OAuth 1 multi-user app
$wgOAuthStaticApps[] = [
	// required keys

	// database ID, obtained via ManageConfigurationBasedClient.php --create
	'id' => 100001,
	// OAuth 1 or OAuth 2?
	'oauthVersion' => 1,
	// public identifier (OAuth 1 consumer key), obtained via ManageConfigurationBasedClient.php --create
	'consumerKey' => '3760590d032bfa72caf018d33d7ef2b3',
	// visible app name
	'name' => 'Our first-party OAuth 1 app',
	// semver version number
	'version' => '1.0.0',
	// visible app description - plain text
	'description' => '...',
	// list of permission bundles to restrict what actions can be performed via the app, see Special:ListGrants
	// or one of the special grants from Consumer::AUTH_ONLY_GRANTS if the app doesn't need API access
	'grants' => [ 'basic', 'highvolume', 'editpage', ],
	// callback URL used for the OAuth handshake
	'callbackUrl' => 'https://example.com/oauth/callback',
	// secret key, obtained via ManageConfigurationBasedClient.php --create
	// NOTE: this is not the value you need to provide to the client as secret key!
	'secretKey' => 'af798fe30b5f480195d4ce4638b40c78',
	// alternatively: 'rsaKey' => <RSA public key string>
	// central user ID, usually obtained via ManageConfigurationBasedClient.php --create
	'userId' => 1234,

	// optional keys

	// restrict the app to a single wiki (value is a wiki ID)
	'wiki' => 'somewiki',
	// when set, the app can append further path components to callbackUrl
	'callbackIsPrefix' => true,
	// further restrictions for what the app can do; the output of MWRestrictions::toArray()
	'restrictions' => [ 'IPAddresses' => [ '127.0.0.1' ], 'Pages' => [ 'Main Page' ] ],
];

// OAuth 1 single-user (owner-only) app
$wgOAuthStaticApps[] = [
	// required keys

	// database ID, obtained via ManageConfigurationBasedClient.php --create
	'id' => 100002,
	// OAuth 1 or OAuth 2?
	'oauthVersion' => 1,
	// can only act in the name of a specific user
	'ownerOnly' => true,
	// public identifier (OAuth 1 consumer key), obtained via ManageConfigurationBasedClient.php --create
	'consumerKey' => 'a59068b645fbb202a34cdfb7642be3e7',
	// visible app name
	'name' => 'Our first-party OAuth 1 owner-only app',
	// semver version number
	'version' => '1.0.0',
	// visible app description - plain text
	'description' => '...',
	// list of permission bundles to restrict what actions can be performed via the app, see Special:ListGrants
	// or one of the special grants from Consumer::AUTH_ONLY_GRANTS if the app doesn't need API access
	'grants' => [ 'basic', 'highvolume', 'editpage', ],
	// secret key, obtained via ManageConfigurationBasedClient.php --create
	// NOTE: this is not the value you need to provide to the client as secret key!
	'secretKey' => 'f63b9252a2881b16dcae648085f4058f',
	// alternatively: 'rsaKey' => <RSA public key string>
	// central user ID as given by CentralIdLookup. The app will always act as this user.
	'userId' => 1234,

	// optional keys

	// restrict the app to a single wiki (value is a wiki ID)
	'wiki' => 'somewiki',
	// further restrictions for what the app can do; the output of MWRestrictions::toArray()
	'restrictions' => [ 'IPAddresses' => [ '127.0.0.1' ], 'Pages' => [ 'Main Page' ] ],
];

// OAuth 2 multi-user app
$wgOAuthStaticApps[] = [
	// required keys

	// database ID, obtained via ManageConfigurationBasedClient.php --create
	'id' => 100003,
	// OAuth 1 or OAuth 2?
	'oauthVersion' => 2,
	// public identifier (OAuth 2 client ID), obtained via ManageConfigurationBasedClient.php --create
	'consumerKey' => '6f487f556856f6a76dd462a04cf301d2',
	// visible app name
	'name' => 'Our first-party OAuth 2 app',
	// semver version number
	'version' => '1.0.0',
	// visible app description - plain text
	'description' => '...',
	// can the appp keep the secret key secret? Usually true for server-based apps, false for
	// mobile and desktop apps.
	'oauth2IsConfidential' => true,
	// OAuth 2 scopes - list of permission bundles to restrict what actions can be performed via the app,
	// see Special:ListGrants or one of the special grants from Consumer::AUTH_ONLY_GRANTS if the app
	// doesn't need API access.
	// NOTE: this field is called 'grants' internally, following the OAuth 1 terminology; it is not
	// related to OAuth 2 grants at all.
	'grants' => [ 'basic', 'highvolume', 'editpage', ],
	// OAuth 2 allowed grants (mechanisms to obtain an access token). You should probably leave this as is.
	'oauth2GrantTypes' => [ 'authorization_code', 'refresh_token' ],
	// callback URL (redirect URI) used for the OAuth handshake in the authorization code flow
	'callbackUrl' => 'https://example.com/oauth/callback',
	// secret key, obtained via ManageConfigurationBasedClient.php --create
	// NOTE: this is not the value you need to provide to the client as secret key!
	// FIXME do we need this for non-confidential apps?
	'secretKey' => 'fc36e789678f87f2acb50f66916adff7',
	// central user ID, usually obtained via ManageConfigurationBasedClient.php --create
	'userId' => 1234,

	// optional keys

	// restrict the app to a single wiki (value is a wiki ID)
	'wiki' => 'somewiki',
	// further restrictions for what the app can do; the output of MWRestrictions::toArray()
	'restrictions' => [ 'IPAddresses' => [ '127.0.0.1' ], 'Pages' => [ 'Main Page' ] ],
];

// OAuth 2 single-user (client credentials) app
// FIXME doesn't really work until T420297 is fixed
$wgOAuthStaticApps[] = [
	// required keys

	// database ID, obtained via ManageConfigurationBasedClient.php --create
	'id' => 100004,
	// OAuth 1 or OAuth 2?
	'oauthVersion' => 2,
	// public identifier (OAuth 2 client ID), obtained via ManageConfigurationBasedClient.php --create
	'consumerKey' => '89e6f4296d3dd374a4d88f337e9224bd',
	// visible app name
	'name' => 'Our first-party OAuth 2 client credentials app',
	// semver version number
	'version' => '1.0.0',
	// visible app description - plain text
	'description' => '...',
	// can the appp keep the secret key secret? Usually true for server-based apps, false for
	// mobile and desktop apps.
	'oauth2IsConfidential' => true,
	// OAuth 2 scopes - list of permission bundles to restrict what actions can be performed via the app,
	// see Special:ListGrants or one of the special grants from Consumer::AUTH_ONLY_GRANTS if the app
	// doesn't need API access.
	// NOTE: this field is called 'grants' internally, following the OAuth 1 terminology; it is not
	// related to OAuth 2 grants at all.
	'grants' => [ 'basic', 'highvolume', 'editpage', ],
	// OAuth 2 allowed grants (mechanisms to obtain an access token). You should probably leave this as is.
	'oauth2GrantTypes' => [ 'client_credentials' ],
	// secret key, obtained via ManageConfigurationBasedClient.php --create
	// NOTE: this is not the value you need to provide to the client as secret key!
	// FIXME do we need this for non-confidential apps?
	'secretKey' => 'd868f9c72a3a52984e8ad90c5cf537bd',
	// central user ID as given by CentralIdLookup. The app will always act as this user.
	'userId' => 1234,

	// optional keys

	// restrict the app to a single wiki (value is a wiki ID)
	'wiki' => 'somewiki',
	// further restrictions for what the app can do; the output of MWRestrictions::toArray()
	'restrictions' => [ 'IPAddresses' => [ '127.0.0.1' ], 'Pages' => [ 'Main Page' ] ],
];

// OAuth 2 single-user (owner-only) app
$wgOAuthStaticApps[] = [
	// required keys

	// database ID, obtained via ManageConfigurationBasedClient.php --create
	'id' => 100005,
	// OAuth 1 or OAuth 2?
	'oauthVersion' => 2,
	// can only act in the name of a specific user
	'ownerOnly' => true,
	// public identifier (OAuth 2 client ID), obtained via ManageConfigurationBasedClient.php --create
	'consumerKey' => '9c7574fa572e1edb6df3b8d07e440161',
	// visible app name
	'name' => 'Our first-party OAuth 2 owner-only app',
	// semver version number
	'version' => '1.0.0',
	// visible app description - plain text
	'description' => '...',
	// can the appp keep the secret key secret? Usually true for server-based apps, false for
	// mobile and desktop apps.
	// FIXME can an owner-only app be non-confidential?
	'oauth2IsConfidential' => true,
	// OAuth 2 scopes - list of permission bundles to restrict what actions can be performed via the app,
	// see Special:ListGrants or one of the special grants from Consumer::AUTH_ONLY_GRANTS if the app
	// doesn't need API access.
	// NOTE: this field is called 'grants' internally, following the OAuth 1 terminology; it is not
	// related to OAuth 2 grants at all.
	'grants' => [ 'basic', 'highvolume', 'editpage', ],
	// secret key, obtained via ManageConfigurationBasedClient.php --create
	// NOTE: this is not the value you need to provide to the client as secret key!
	'secretKey' => 'f81cd2139680c2f613584b71c81c30ea',
	// central user ID as given by CentralIdLookup. The app will always act as this user.
	'userId' => 1234,

	// optional keys

	// restrict the app to a single wiki (value is a wiki ID)
	'wiki' => 'somewiki',
	// further restrictions for what the app can do; the output of MWRestrictions::toArray()
	'restrictions' => [ 'IPAddresses' => [ '127.0.0.1' ], 'Pages' => [ 'Main Page' ] ],
];
