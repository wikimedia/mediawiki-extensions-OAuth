<?php

namespace MediaWiki\Extension\OAuth\Backend;

use MediaWiki\User\User;
use MWCryptRand;

/**
 * (c) Dejan Savuljesku 2019, GPL
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License along
 * with this program; if not, write to the Free Software Foundation, Inc.,
 * 59 Temple Place - Suite 330, Boston, MA 02111-1307, USA.
 * http://www.gnu.org/copyleft/gpl.html
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

	/**
	 * @return int
	 */
	public function getOAuthVersion() {
		return static::OAUTH_VERSION_1;
	}
}
