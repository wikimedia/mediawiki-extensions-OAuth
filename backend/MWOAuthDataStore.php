<?php

class MWOAuthDataStore extends OAuthDataStore {
	// ObjectCache for Tokens and Nonces
	protected $cache;

	// Persistant storage for logging/audit
	protected $logging;

	/**
	 * @param BagOStuff $cache
	 * @param type $logdb
	 */
	public function __construct( BagOStuff $cache, $logdb ) {
		$this->cache = $cache;
		$this->logging = $logdb;
	}

	/**
	 * Get an MWOAuthConsuer from the consumer's key
	 * @param String $consumerKey the string value of the Consumer's key
	 * @return MWOAuthConsumer
	 */
	public function lookup_consumer( $consumerKey ) {
		$dbr = MWOAuthUtils::getCentralDB( DB_SLAVE );
		return MWOAuthConsumer::newFromKey( $dbr, $consumerKey );
	}

	/**
	 * Get either a request or access token from the data store.
	 * @param OAuthConsumer|MWOAuthConsumer $consumer
	 * @param $token_type
	 * @param $token String the token
	 * @return MWOAuthToken
	 */
	public function lookup_token( $consumer, $token_type, $token ) {
		wfDebugLog( 'OAuth', __METHOD__ . ": Looking up $token_type token '$token'" );

		if ( $token_type == 'request' ) {
			$returnToken = $this->cache->get( MWOAuthUtils::getCacheKey(
				'token',
				$consumer->key,
				$token_type,
				$token
			) );
			if ( $token === null || !( $returnToken instanceof MWOAuthToken ) ) {
				throw new MWOAuthException( 'mwoauthdatastore-request-token-not-found' );
			}
		} elseif ( $token_type == 'access' ) {
			$dbr = MWOAuthUtils::getCentralDB( DB_SLAVE );
			$row = $dbr->selectRow(
				'oauth_accepted_consumer',
				'*',
				array( 'oaac_access_token' => $token ),
				__METHOD__
			);

			if ( !$row ) {
				throw new MWOAuthException( 'mwoauthdatastore-access-token-not-found' );
			}

			$returnToken = new MWOAuthToken( $row->oaac_access_token, $row->oaac_access_secret );
		} else {
			throw new MWOAuthException( 'mwoauthdatastore-invalid-token-type' );
		}

		return $returnToken;
	}

	/**
	 * Check that nonce has not been seen before. Add it on check, so we don't repeat it.
	 * Note, timestamp has already been checked, so this should be a fresh nonce.
	 *
	 * @param MWOAuthConsumer|OAuthConsumer $consumer
	 * @param String $token
	 * @param String $nonce
	 * @param $timestamp
	 * @return boolean
	 */
	public function lookup_nonce( $consumer, $token, $nonce, $timestamp ) {
		$key = MWOAuthUtils::getCacheKey( 'nonce', $consumer->key, $token, $nonce );

		// Do an add for the key associated with this nonce. If the key exisits, nonce has
		// been used. Set timeout 5 minutes in the future of the timestamp, to match
		// OAuthServer. Use the timestamp so the client can also expire their nonce records
		// after 5 mins.
		if ( !$this->cache->add( $key, 1, $timestamp + 300 ) ) {
			wfDebugLog( 'OAuth', "$key exists, so nonce has been used by this consumer+token" );
			return true;
		}
		return false;
	}

	/**
	 * Helper function to generate and return an MWOAuthToken. MWOAuthToken can be used as a
	 * request or access token.
	 * TODO: put in Utils?
	 * @return MWOAuthToken
	 */
	public static function newToken() {
		return new MWOAuthToken(
			MWCryptRand::generateHex( 32, false), //The key doesn't need to be unpredictable
			MWCryptRand::generateHex( 32, true)
		);
	}

	/**
	 * Generate a new token, save it in the cache, and return it
	 * @param MWOAuthConsumer|OAuthConsumer $consumer
	 */
	public function new_request_token( $consumer, $callback = null ) {
		// return a new token attached to this consumer
		$token = $this->newToken();
		$cacheKey = MWOAuthUtils::getCacheKey( 'token', $consumer->key, 'request', $token->key );
		$this->cache->add( $cacheKey, $token, 600 ); //10 minutes. Kindof arbitray.
		wfDebugLog( 'OAuth', __METHOD__ . ": New request token {$token->key} for {$consumer->key}" );
		return $token;
	}

	/**
	 * Return a new access token attached to this consumer for the user associated with this
	 * token if the request token is authorized. Should also invalidate the request token.
	 *
	 * @param MWOAuthToken $token the request token that started this
	 * @param OAuthConsumer $consumer
	 * @param $verifier
	 * @return MWOAuthToken the access token
	 */
	public function new_access_token( $token, $consumer, $verifier = null ) {
		wfDebugLog( 'OAuth', __METHOD__ . ": Getting new access token for token {$token->key}, consumer {$consumer->key}" );

		if ( !$token->getVerifyCode() || !$token->getAccessKey() ) {
			throw new MWOAuthException( 'mwoauthdatastore-bad-token' );
		}

		if ( $token->getVerifyCode() !== $verifier ) {
			throw new MWOAuthException( 'mwoauthdatastore-bad-verifier' );
		}

		$accessToken = $this->lookup_token( $consumer, 'access', $token->getAccessKey() );
		$this->cache->delete( MWOAuthUtils::getCacheKey( 'token', $consumer->get( 'consumerKey' ), 'request', $token->key ) );
		wfDebugLog( 'OAuth', __METHOD__ . ": New access token {$accessToken->key} for {$consumer->key}" );
		return $accessToken;
	}

	/**
	 * Update a request token. The token probably already exists, but had another attribute added.
	 * @param MWOAuthToken $token the token to store
	 * @param MWOAuthConsumer|OAuthConsumer $consumer
	 */
	public function updateRequestToken( $token, $consumer ) {
		$cacheKey = MWOAuthUtils::getCacheKey( 'token', $consumer->key, 'request', $token->key );
		$this->cache->set( $cacheKey, $token, 600 ); //10 more minutes. Kindof arbitray.
	}

	/**
	 * Return the string representing the Consumers's public RSA key
	 *
	 * @param String $consumerKey the string value of the Consumer's key
	 * @return String|null
	 */
	public function getRSAKey( $consumerKey ) {
		$dbr = MWOAuthUtils::getCentralDB( DB_SLAVE );
		$cmr = MWOAuthConsumer::newFromKey( $dbr, $consumerKey );
		return $cmr ? $cmr->get( 'rsaKey' ) : null;
	}
}
