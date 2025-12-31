<?php

namespace MediaWiki\Extension\OAuth\Backend;

use MediaWiki\User\User;
use MWCryptRand;

/**
 * (c) Dejan Savuljesku 2019, GPL
 *
 * @license GPL-2.0-or-later
 */

/**
 * This class mainly exists to enable clean separation
 * of OAuth 1.0a and OAuth 2.0 code
 *
 * Representation of an OAuth 1.0a consumer.
 */
class OAuth1Consumer extends Consumer {

	/**
	 * The user has authorized the request by this consumer, with this request token. Update
	 * everything so that the consumer can swap the request token for an access token. Then
	 * generate the callback URL where we will redirect our user back to the consumer.
	 *
	 * @param User $mwUser
	 * @param bool $update
	 * @param array $grants
	 * @param string|null $requestTokenKey
	 * @return string
	 * @throws MWOAuthException
	 */
	public function authorize( User $mwUser, $update, $grants, $requestTokenKey = null ) {
		$this->conductAuthorizationChecks( $mwUser );

		// Generate and Update the tokens:
		// * Generate a new Verification code, and add it to the request token
		// * Either add or update the authorization
		// ** Generate a new access token if this is a new authorization
		// * Resave request token with the access token
		$verifyCode = MWCryptRand::generateHex( 32 );
		$store = Utils::newMWOAuthDataStore();
		$requestToken = $store->lookup_token( $this, 'request', $requestTokenKey );
		if ( !$requestToken || !( $requestToken instanceof MWOAuthToken ) ) {
			throw new MWOAuthException( 'mwoauthserver-invalid-request-token', [
				'consumer' => $this->getConsumerKey(),
				'consumer_name' => $this->getName(),
				'token_key' => $requestTokenKey,
			] );
		}
		$requestToken->addVerifyCode( $verifyCode );

		$cmra = $this->saveAuthorization( $mwUser, $update, $grants );
		$accessToken = new MWOAuthToken( $cmra->getAccessToken(), '' );

		$requestToken->addAccessKey( $accessToken->key );
		$store->updateRequestToken( $requestToken, $this );
		return $this->generateCallbackUrl(
			$store, $requestToken->getVerifyCode(), $requestTokenKey
		);
	}

	public function getOAuthVersion(): int {
		return static::OAUTH_VERSION_1;
	}
}
