<?php

class MWOAuthSignatureMethod_RSA_SHA1 extends OAuthSignatureMethod_RSA_SHA1 {

	protected $store;

	function __construct( OAuthDataStore $store ) {
		$this->store = $store;
	}


	/**
	 * Get the public certificate, used to verify the request. In our case, we get
	 * the Consumer's key, and lookup the registered cert from the datastore.
	 * @param OAuthRequest request recieved by the server, that we're going to verify
	 * @return String representing the public certificate, used by 
	 */
	protected function fetch_public_cert( &$request ) {
		$consumerKey = $request->get_parameter( 'oauth_consumer_key' );
		return $this->store->getRSAKey( $consumerKey );
	}

	/**
	 * If you want to reuse this code to write your Consumer, implement this function
	 * to get your private key, so you can sign the request.
	 */
	protected function fetch_private_cert( &$request ) {
		throw new OAuthException( "This has not been implemented" );
	}
}
