<?php

namespace MediaWiki\Extension\OAuth\Tests;

use MediaWiki\Extension\OAuth\Lib\OAuthConsumer;
use MediaWiki\Extension\OAuth\Lib\OAuthDataStore;
use MediaWiki\Extension\OAuth\Lib\OAuthToken;

/**
 * A mock store for testing
 */
class MockOAuthDataStore extends OAuthDataStore {
	private OAuthConsumer $consumer;
	private OAuthToken $request_token;
	private OAuthToken $access_token;
	private string $nonce;

	public function __construct() {
		$this->consumer = new OAuthConsumer( "key", "secret", null );
		$this->request_token = new OAuthToken( "requestkey", "requestsecret", 1 );
		$this->access_token = new OAuthToken( "accesskey", "accesssecret", 1 );
		$this->nonce = "nonce";
	}

	/**
	 * @param string $consumer_key
	 *
	 * @return OAuthConsumer|null
	 */
	public function lookup_consumer( $consumer_key ) {
		if ( $consumer_key == $this->consumer->key ) {
			return $this->consumer;
		}
		return null;
	}

	/**
	 * @param OAuthConsumer $consumer
	 * @param string $token_type
	 * @param string $token
	 *
	 * @return string|null
	 */
	public function lookup_token( $consumer, $token_type, $token ) {
		$token_attrib = $token_type . "_token";
		if ( $consumer->key == $this->consumer->key
			&& $token == $this->$token_attrib->key ) {
			return $this->$token_attrib;
		}
		return null;
	}

	/**
	 * @param OAuthConsumer $consumer
	 * @param string $token
	 * @param string $nonce
	 * @param int $timestamp
	 *
	 * @return string|null
	 */
	public function lookup_nonce( $consumer, $token, $nonce, $timestamp ) {
		if ( $consumer->key == $this->consumer->key
			&& ( ( $token && $token->key == $this->request_token->key )
				|| ( $token && $token->key == $this->access_token->key ) )
			&& $nonce == $this->nonce ) {
			return $this->nonce;
		}
		return null;
	}

	/**
	 * @param OAuthConsumer $consumer
	 * @param callable|null $callback
	 *
	 * @return OAuthToken|null
	 */
	public function new_request_token( $consumer, $callback = null ) {
		if ( $consumer->key == $this->consumer->key ) {
			return $this->request_token;
		}
		return null;
	}

	/**
	 * @param OAuthToken|string $token
	 * @param OAuthConsumer $consumer
	 * @param int|null $verifier
	 *
	 * @return OAuthToken|null
	 */
	public function new_access_token( $token, $consumer, $verifier = null ) {
		if ( $consumer->key == $this->consumer->key
			&& $token->key == $this->request_token->key ) {
			return $this->access_token;
		}
		return null;
	}
}
