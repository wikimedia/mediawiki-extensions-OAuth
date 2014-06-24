<?php

namespace MediaWiki\Extensions\OAuth;

class MWOAuthDataStore extends OAuthDataStore {
	/** @var DBConnRef DB for the consumer/grant registry */
	protected $centralDB;
	/** @var BagOStuff Cache for Tokens and Nonces */
	protected $cache;

	/**
	 * @param DBConnRef $centralDB Central DB slave
	 * @param BagOStuff $cache
	 */
	public function __construct( \DBConnRef $centralDB, \BagOStuff $cache ) {
		$this->centralDB = $centralDB;
		$this->cache = $cache;
	}

	/**
	 * Get an MWOAuthConsumer from the consumer's key
	 *
	 * @param String $consumerKey the string value of the Consumer's key
	 * @return MWOAuthConsumer
	 */
	public function lookup_consumer( $consumerKey ) {
		return MWOAuthConsumer::newFromKey( $this->centralDB, $consumerKey );
	}

	/**
	 * Get either a request or access token from the data store
	 *
	 * @param OAuthConsumer|MWOAuthConsumer $consumer
	 * @param $token_type
	 * @param $token String the token
	 * @throws MWOAuthException
	 * @return MWOAuthToken
	 */
	public function lookup_token( $consumer, $token_type, $token ) {
		wfDebugLog( 'OAuth', __METHOD__ . ": Looking up $token_type token '$token'" );

		if ( $token_type === 'request' ) {
			$returnToken = $this->cache->get( MWOAuthUtils::getCacheKey(
				'token',
				$consumer->key,
				$token_type,
				$token
			) );
			if ( $token === null || !( $returnToken instanceof MWOAuthToken ) ) {
				throw new MWOAuthException( 'mwoauthdatastore-request-token-not-found' );
			}
		} elseif ( $token_type === 'access' ) {
			$cmra = MWOAuthConsumerAcceptance::newFromToken( $this->centralDB, $token );
			if ( !$cmra ) {
				throw new MWOAuthException( 'mwoauthdatastore-access-token-not-found' );
			}
			$secret = MWOAuthUtils::hmacDBSecret( $cmra->get( 'accessSecret' ) );
			$returnToken = new MWOAuthToken( $cmra->get( 'accessToken' ), $secret );
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
		// Do an add for the key associated with this nonce to check if it was already used.
		// Set timeout 5 minutes in the future of the timestamp as OAuthServer does. Use the
		// timestamp so the client can also expire their nonce records after 5 mins.
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
			\MWCryptRand::generateHex( 32, false ), // the key doesn't need to be unpredictable
			\MWCryptRand::generateHex( 32, true )
		);
	}

	/**
	 * Generate a new token (attached to this consumer), save it in the cache, and return it
	 *
	 * @param MWOAuthConsumer|OAuthConsumer $consumer
	 * @param null $callback
	 * @return MWOAuthToken
	 */
	public function new_request_token( $consumer, $callback = null ) {
		$token = MWOAuthDataStore::newToken();
		$cacheKey = MWOAuthUtils::getCacheKey( 'token', $consumer->key, 'request', $token->key );
		$this->cache->add( $cacheKey, $token, 600 ); //10 minutes. Kindof arbitray.
		wfDebugLog( 'OAuth', __METHOD__ .
			": New request token {$token->key} for {$consumer->key}" );
		return $token;
	}

	/**
	 * Return a new access token attached to this consumer for the user associated with this
	 * token if the request token is authorized. Should also invalidate the request token.
	 *
	 * @param MWOAuthToken $token the request token that started this
	 * @param OAuthConsumer $consumer
	 * @param $verifier
	 * @throws MWOAuthException
	 * @return MWOAuthToken the access token
	 */
	public function new_access_token( $token, $consumer, $verifier = null ) {
		wfDebugLog( 'OAuth', __METHOD__ .
			": Getting new access token for token {$token->key}, consumer {$consumer->key}" );

		if ( !$token->getVerifyCode() || !$token->getAccessKey() ) {
			throw new MWOAuthException( 'mwoauthdatastore-bad-token' );
		} elseif ( $token->getVerifyCode() !== $verifier ) {
			throw new MWOAuthException( 'mwoauthdatastore-bad-verifier' );
		}

		$cacheKey = MWOAuthUtils::getCacheKey( 'token',
			$consumer->get( 'consumerKey' ), 'request', $token->key );
		$accessToken = $this->lookup_token( $consumer, 'access', $token->getAccessKey() );
		$this->cache->delete( $cacheKey );
		wfDebugLog( 'OAuth', __METHOD__ .
			": New access token {$accessToken->key} for {$consumer->key}" );
		return $accessToken;
	}

	/**
	 * Update a request token. The token probably already exists, but had another attribute added.
	 *
	 * @param MWOAuthToken $token the token to store
	 * @param MWOAuthConsumer|OAuthConsumer $consumer
	 */
	public function updateRequestToken( $token, $consumer ) {
		$cacheKey = MWOAuthUtils::getCacheKey( 'token', $consumer->key, 'request', $token->key );
		$this->cache->set( $cacheKey, $token, 600 ); // 10 more minutes. Kindof arbitray.
	}

	/**
	 * Return the string representing the Consumer's public RSA key
	 *
	 * @param String $consumerKey the string value of the Consumer's key
	 * @return String|null
	 */
	public function getRSAKey( $consumerKey ) {
		$cmr = MWOAuthConsumer::newFromKey( $this->centralDB, $consumerKey );
		return $cmr ? $cmr->get( 'rsaKey' ) : null;
	}
}
