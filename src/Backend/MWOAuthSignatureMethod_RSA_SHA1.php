<?php

namespace MediaWiki\Extensions\OAuth\Backend;

use MediaWiki\Extensions\OAuth\Lib\OAuthDataStore;
use MediaWiki\Extensions\OAuth\Lib\OAuthException;
use MediaWiki\Extensions\OAuth\Lib\OAuthRequest;
use MediaWiki\Extensions\OAuth\Lib\OAuthSignatureMethod_RSA_SHA1;

class MWOAuthSignatureMethod_RSA_SHA1 extends OAuthSignatureMethod_RSA_SHA1 {
	/** @var MWOAuthDataStore */
	protected $store;
	/** @var string PEM encoded RSA private key */
	private $privateKey;

	/**
	 * @param OAuthDataStore $store
	 * @param string|null $privateKey RSA private key, passed to openssl_get_privatekey
	 */
	public function __construct( OAuthDataStore $store, $privateKey = null ) {
		$this->store = $store;
		$this->privateKey = $privateKey;

		if ( $privateKey !== null ) {
			$key = openssl_pkey_get_private( $privateKey );
			if ( !$key ) {
				throw new OAuthException( "Invalid private key given" );
			}
			$details = openssl_pkey_get_details( $key );
			if ( $details['type'] !== OPENSSL_KEYTYPE_RSA ) {
				throw new OAuthException( "Key is not an RSA key" );
			}
			openssl_pkey_free( $key );
		}
	}

	/**
	 * Get the public certificate, used to verify the request. In our case, we get
	 * the Consumer's key, and lookup the registered cert from the datastore.
	 * @param OAuthRequest &$request request recieved by the server, that we're going to verify
	 * @return string representing the public certificate
	 */
	protected function fetch_public_cert( &$request ) {
		$consumerKey = $request->get_parameter( 'oauth_consumer_key' );
		return $this->store->getRSAKey( $consumerKey );
	}

	/**
	 * If you want to reuse this code to write your Consumer, implement
	 * this function to get your private key, so you can sign the request.
	 * @param OAuthRequest &$request
	 * @return string
	 * @throws OAuthException
	 */
	protected function fetch_private_cert( &$request ) {
		if ( $this->privateKey === null ) {
			throw new OAuthException( "No private key was set" );
		}
		return $this->privateKey;
	}
}
