<?php

// This class is intentionally not in the normal OAuth namespace, for easy access.

use Defuse\Crypto\Crypto;
use League\OAuth2\Server\AuthorizationValidators\BearerTokenValidator;
use League\OAuth2\Server\CryptKey;
use MediaWiki\Extension\OAuth\Backend\Utils;
use MediaWiki\Extension\OAuth\Repository\AccessTokenRepository;
use MediaWiki\MediaWikiServices;
use Wikimedia\ObjectCache\BagOStuff;
use Wikimedia\TestingAccessWrapper;

/**
 * Debug helper class for OAuth.
 * @see MW
 * @internal must not be used in code, anywhere
 */
class MWOA {

	public static function sessionCache(): BagOStuff {
		return Utils::getSessionCache();
	}

	public static function nonceCache(): BagOStuff {
		return Utils::getNonceCache();
	}

	/**
	 * Decrypt an OAuth 2 access token without validation and return the contents.
	 *
	 * The meaning of the fields:
	 * - aud: app ID (oarc_consumer_key)
	 * - jti: unique token ID (oaat_identifier)
	 * - iat: issuance date
	 * - nbf: "not before" date, in practice same as issuance date
	 * - exp: expiry date
	 * - sub: user's central ID
	 * - iss: canonical server URL
	 * - scopes: app scopes ("grants" in OAuth 1 / MW core terminology)
	 */
	public static function decryptAccessToken( string $token ): array {
		$config = MediaWikiServices::getInstance()->getConfigFactory()->makeConfig( 'mwoauth' );
		$accessTokenRepository = new AccessTokenRepository( $config->get( 'CanonicalServer' ) );
		$validator = new BearerTokenValidator( $accessTokenRepository );
		$validator->setPublicKey( new CryptKey( $config->get( 'OAuth2PublicKey' ) ) );
		/** @var \Lcobucci\JWT\Configuration $jwtConfiguration */
		$jwtConfiguration = TestingAccessWrapper::newFromObject( $validator )->jwtConfiguration;
		return $jwtConfiguration->parser()->parse( $token )->claims()->all();
	}

	/**
	 * Decrypt an OAuth 2 refresh token without validation and return the contents.
	 *
	 * The meaning of the fields:
	 * - client_id: app ID (oarc_consumer_key)
	 * - refresh_token_id: key segment for the refresh token data in the session cache
	 *   (will be prefixed with 'RefreshToken:')
	 * - access_token_id: oaat_identifier
	 * - scopes: app scopes ("grants" in OAuth 1 / MW core terminology)
	 * - user_id: user's central ID
	 */
	public static function decryptRefreshToken( string $token ): array {
		$config = MediaWikiServices::getInstance()->getConfigFactory()->makeConfig( 'mwoauth' );
		$oAuthSecretKey = $config->get( 'OAuthSecretKey' );
		$json = Crypto::decryptWithPassword( $token, $oAuthSecretKey );
		return json_decode( $json, true );
	}

	/**
	 * Convert a client secret stored in the database (oarc_secret_key) to the salted format
	 * that's given to the client.
	 */
	public static function clientSecret( string $oarcSecretKey ): string {
		return Utils::hmacDBSecret( $oarcSecretKey );
	}

}
