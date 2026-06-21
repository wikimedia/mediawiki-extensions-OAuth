<?php
// vim: foldmethod=marker
/**
 * The MIT License
 *
 * Copyright (c) 2007 Andy Smith
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files ( the "Software" ), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in
 * all copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
 * THE SOFTWARE.
 */

namespace MediaWiki\Extension\OAuth\Lib;

use MediaWiki\Extension\OAuth\Backend\Consumer;
use MediaWiki\Logger\LoggerFactory;
use Psr\Log\LoggerInterface;

/**
 * Handles OAuth 1 token generation / lookup / verification.
 */
class OAuthServer {
	/** Allowed lag / clock drift for OAuth1 signatures */
	protected const TIMESTAMP_THRESHOLD = 300;
	/** @var string Protocol version */
	protected $version = '1.0';
	/** @var array<string,OAuthSignatureMethod> */
	protected $signature_methods = [];

	/** @var OAuthDataStore */
	protected $data_store;

	/** @var LoggerInterface */
	protected $logger;

	/**
	 * @param OAuthDataStore $data_store
	 */
	public function __construct( $data_store ) {
		$this->data_store = $data_store;
		$this->logger = LoggerFactory::getInstance( 'OAuth' );
	}

	/**
	 * @param OAuthSignatureMethod $signature_method
	 * @return void
	 */
	public function add_signature_method( $signature_method ) {
		$this->signature_methods[$signature_method->get_name()] = $signature_method;
	}

	// high level functions

	/**
	 * process a request_token request
	 * returns the request token on success
	 *
	 * @param OAuthRequest &$request
	 * @return OAuthToken
	 */
	public function fetch_request_token( &$request ) {
		$this->get_version( $request );

		$consumer = $this->get_consumer( $request );

		// no token required for the initial token request
		$token = null;

		$this->check_signature( $request, $consumer, $token );

		// Rev A change
		$callback = $request->get_parameter( 'oauth_callback' );
		$new_token = $this->data_store->new_request_token( $consumer, $callback );

		return $new_token;
	}

	/**
	 * process an access_token request
	 * returns the access token on success
	 *
	 * @param OAuthRequest &$request
	 * @return OAuthToken
	 */
	public function fetch_access_token( &$request ) {
		$this->get_version( $request );

		$consumer = $this->get_consumer( $request );

		// requires authorized request token
		$token = $this->get_token( $request, $consumer, "request" );

		$this->check_signature( $request, $consumer, $token );

		// Rev A change
		$verifier = $request->get_parameter( 'oauth_verifier' );
		$new_token = $this->data_store->new_access_token( $token, $consumer, $verifier );

		return $new_token;
	}

	/**
	 * verify an api call, checks all the parameters
	 *
	 * @param OAuthRequest &$request
	 * @return array{Consumer,OAuthToken}
	 */
	public function verify_request( &$request ) {
		$this->get_version( $request );
		$consumer = $this->get_consumer( $request );
		$token = $this->get_token( $request, $consumer, "access" );
		$this->check_signature( $request, $consumer, $token );

		return [
			$consumer,
			$token
		];
	}

	// Internals from here

	/**
	 * version 1
	 *
	 * @param OAuthRequest &$request
	 * @return string
	 * @throws OAuthException
	 */
	protected function get_version( &$request ) {
		$version = $request->get_parameter( "oauth_version" );
		if ( !$version ) {
			// Service Providers MUST assume the protocol version to be 1.0 if this parameter is not present.
			// Chapter 7.0 ( "Accessing Protected Ressources" )
			$version = '1.0';
		}
		if ( $version !== $this->version ) {
			throw new OAuthException( "OAuth version '$version' not supported" );
		}

		return $version;
	}

	/**
	 * figure out the signature with some defaults
	 *
	 * @param OAuthRequest $request
	 * @return OAuthSignatureMethod
	 * @throws OAuthException
	 */
	private function get_signature_method( $request ) {
		$signature_method = $request instanceof OAuthRequest ? $request->get_parameter(
			"oauth_signature_method"
		) : null;

		if ( !$signature_method ) {
			// According to chapter 7 ( "Accessing Protected Ressources" ) the signature-method
			// parameter is required, and we can't just fallback to PLAINTEXT
			throw new OAuthException( 'No signature method parameter. This parameter is required' );
		}

		if ( !( $this->signature_methods[$signature_method] ?? null ) ) {
			throw new OAuthException(
				"Signature method '$signature_method' not supported " . "try one of the following: " . implode(
					", ",
					array_keys( $this->signature_methods )
				)
			);
		}

		return $this->signature_methods[$signature_method];
	}

	/**
	 * try to find the consumer for the provided request's consumer key
	 *
	 *
	 * @param OAuthRequest $request
	 * @return Consumer
	 * @throws OAuthException
	 */
	protected function get_consumer( $request ) {
		$consumer_key = $request instanceof OAuthRequest ? $request->get_parameter(
			"oauth_consumer_key"
		) : null;

		if ( !$consumer_key ) {
			throw new OAuthException( "Invalid consumer key" );
		}
		$this->logger->debug( __METHOD__ . ": getting consumer for '$consumer_key'" );
		$consumer = $this->data_store->lookup_consumer( $consumer_key );
		if ( !$consumer ) {
			throw new OAuthException( "Invalid consumer" );
		}

		return $consumer;
	}

	/**
	 * try to find the token for the provided request's token key
	 *
	 * @param OAuthRequest $request
	 * @param Consumer $consumer
	 * @param string $token_type 'request' or 'access'
	 * @return OAuthToken
	 * @throws OAuthException
	 */
	protected function get_token( $request, $consumer, $token_type = "access" ) {
		$token_field = $request instanceof OAuthRequest ? $request->get_parameter(
			'oauth_token'
		) : null;

		$token = $this->data_store->lookup_token(
			$consumer,
			$token_type,
			$token_field
		);
		if ( !$token ) {
			throw new OAuthException( "Invalid $token_type token: $token_field" );
		}

		return $token;
	}

	/**
	 * all-in-one function to check the signature on a request
	 * should guess the signature method appropriately
	 *
	 * @param OAuthRequest $request
	 * @param Consumer $consumer
	 * @param ?OAuthToken $token
	 * @throws OAuthException
	 */
	protected function check_signature( $request, $consumer, $token ) {
		// FIXME Consumer is property-access-compatible with OAuthConsumer
		/** @var OAuthConsumer $consumer */'@phan-var OAuthConsumer $consumer';

		// this should probably be in a different method
		$timestamp = $request instanceof OAuthRequest ? $request->get_parameter(
			'oauth_timestamp'
		) : null;
		$nonce = $request instanceof OAuthRequest ? $request->get_parameter( 'oauth_nonce' ) : null;

		$this->check_timestamp( $timestamp );
		$this->check_nonce( $consumer, $token, $nonce, $timestamp );

		$signature_method = $this->get_signature_method( $request );
		$signature = $request->get_parameter( 'oauth_signature' );
		$valid_sig = $signature_method->check_signature(
			$request,
			$consumer,
			$token,
			$signature
		);

		if ( !$valid_sig ) {
			$this->logger->info(
				__METHOD__ . ': Signature check (' . get_class( $signature_method ) . ') failed'
			);
			throw new OAuthException( "Invalid signature" );
		}
	}

	/**
	 * check that the timestamp is new enough
	 *
	 * @param int $timestamp
	 * @return void
	 * @throws OAuthException
	 */
	private function check_timestamp( $timestamp ) {
		if ( !$timestamp ) {
			throw new OAuthException(
				'Missing timestamp parameter. The parameter is required'
			);
		}

		// verify that timestamp is recentish
		$now = time();
		if ( abs( $now - $timestamp ) > self::TIMESTAMP_THRESHOLD ) {
			throw new OAuthException(
				"Expired timestamp, yours $timestamp, ours $now"
			);
		}
	}

	/**
	 * check that the nonce is not repeated
	 *
	 * @param OAuthConsumer $consumer
	 * @param ?OAuthToken $token
	 * @param ?string $nonce
	 * @param int $timestamp
	 * @return void
	 * @throws OAuthException
	 */
	private function check_nonce( $consumer, $token, $nonce, $timestamp ) {
		if ( !$nonce ) {
			throw new OAuthException(
				'Missing nonce parameter. The parameter is required'
			);
		}

		// verify that the nonce is uniqueish
		$found = $this->data_store->lookup_nonce(
			$consumer,
			$token,
			$nonce,
			$timestamp
		);
		if ( $found ) {
			throw new OAuthException( "Nonce already used: $nonce" );
		}
	}

}
