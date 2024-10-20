<?php

namespace MediaWiki\Extension\OAuth\Backend;

use InvalidArgumentException;
use MediaWiki\Extension\OAuth\Lib\OAuthConsumer;
use MediaWiki\Extension\OAuth\Lib\OAuthDataStore;
use MediaWiki\Linker\Linker;
use MediaWiki\Logger\LoggerFactory;
use MediaWiki\Message\Message;
use MWCryptRand;
use Psr\Log\LoggerInterface;
use Wikimedia\ObjectCache\BagOStuff;
use Wikimedia\Rdbms\IDatabase;

class MWOAuthDataStore extends OAuthDataStore {
	/** @var IDatabase DB for the consumer/grant registry */
	protected $centralReplica;

	/** @var IDatabase|null Primary DB for repeated lookup in case of replication lag problems;
	 *    null if there is no separate primary DB and replica DB
	 */
	protected $centralPrimary;

	/** @var BagOStuff Cache for tokens */
	protected $tokenCache;

	/** @var BagOStuff Cache for nonces */
	protected $nonceCache;

	/** @var LoggerInterface */
	protected $logger;

	/**
	 * @param IDatabase $centralReplica Central DB replica
	 * @param IDatabase|null $centralPrimary Central DB primary (if different)
	 * @param BagOStuff $tokenCache
	 * @param BagOStuff $nonceCache
	 */
	public function __construct(
		IDatabase $centralReplica,
		$centralPrimary,
		BagOStuff $tokenCache,
		BagOStuff $nonceCache
	) {
		if ( $centralPrimary !== null && !( $centralPrimary instanceof IDatabase ) ) {
			throw new InvalidArgumentException(
				__METHOD__ . ': $centralPrimary must be a DB or null'
			);
		}
		$this->centralReplica = $centralReplica;
		$this->centralPrimary = $centralPrimary;
		$this->tokenCache = $tokenCache;
		$this->nonceCache = $nonceCache;
		$this->logger = LoggerFactory::getInstance( 'OAuth' );
	}

	/**
	 * Get an MWOAuthConsumer from the consumer's key
	 *
	 * @param string $consumerKey the string value of the Consumer's key
	 * @return Consumer|false
	 */
	public function lookup_consumer( $consumerKey ) {
		return Consumer::newFromKey( $this->centralReplica, $consumerKey );
	}

	/**
	 * Get either a request or access token from the data store
	 *
	 * @param OAuthConsumer|Consumer $consumer
	 * @param string $token_type
	 * @param string $token String the token
	 * @throws MWOAuthException
	 * @return MWOAuthToken
	 */
	public function lookup_token( $consumer, $token_type, $token ) {
		$this->logger->debug( __METHOD__ . ": Looking up $token_type token '$token'" );

		if ( $token_type === 'request' ) {
			$returnToken = $this->tokenCache->get( Utils::getCacheKey(
				'token',
				$consumer->key,
				$token_type,
				$token
			) );
			if ( $returnToken === '**USED**' ) {
				throw new MWOAuthException( 'mwoauthdatastore-request-token-already-used', [
					Message::rawParam( Linker::makeExternalLink(
						'https://www.mediawiki.org/wiki/Help:OAuth/Errors#E009',
						'E009',
						true
					) ),
					'consumer' => $consumer->key,
				] );
			}
			if ( $token === null || !( $returnToken instanceof MWOAuthToken ) ) {
				throw new MWOAuthException( 'mwoauthdatastore-request-token-not-found', [
					Message::rawParam( Linker::makeExternalLink(
						'https://www.mediawiki.org/wiki/Help:OAuth/Errors#E004',
						'E004',
						true
					) ),
					'consumer' => $consumer->key,
				] );
			}
		} elseif ( $token_type === 'access' ) {
			$cmra = ConsumerAcceptance::newFromToken( $this->centralReplica, $token );
			if ( !$cmra && $this->centralPrimary ) {
				// try primary database in case there is replication lag T124942
				$cmra = ConsumerAcceptance::newFromToken( $this->centralPrimary, $token );
			}
			if ( !$cmra ) {
				throw new MWOAuthException( 'mwoauthdatastore-access-token-not-found' );
			}

			// Ensure the cmra's consumer matches the expected consumer (T103023)
			$mwconsumer = ( $consumer instanceof Consumer )
				? $consumer : $this->lookup_consumer( $consumer->key );
			if ( !$mwconsumer || $mwconsumer->getId() !== $cmra->getConsumerId() ) {
				throw new MWOAuthException( 'mwoauthdatastore-access-token-not-found', [
					'consumer' => $mwconsumer ? $mwconsumer->getConsumerKey() : '',
					'cmra_id' => $cmra->getId(),
				] );
			}

			$secret = Utils::hmacDBSecret( $cmra->getAccessSecret() );
			$returnToken = new MWOAuthToken( $cmra->getAccessToken(), $secret );
		} else {
			throw new MWOAuthException( 'mwoauthdatastore-invalid-token-type', [
				'token_type' => $token_type,
			] );
		}

		return $returnToken;
	}

	/**
	 * Check that nonce has not been seen before. Add it on check, so we don't repeat it.
	 * Note, timestamp has already been checked, so this should be a fresh nonce.
	 *
	 * @param Consumer|OAuthConsumer $consumer
	 * @param string $token
	 * @param string $nonce
	 * @param int $timestamp
	 * @return bool
	 */
	public function lookup_nonce( $consumer, $token, $nonce, $timestamp ) {
		$key = Utils::getCacheKey( 'nonce', $consumer->key, $token, $nonce );
		// Do an add for the key associated with this nonce to check if it was already used.
		// Set timeout 5 minutes in the future of the timestamp as OAuthServer does. Use the
		// timestamp so the client can also expire their nonce records after 5 mins.
		if ( !$this->nonceCache->add( $key, 1, $timestamp + 300 ) ) {
			// T308861
			$key = preg_replace(
				"/(oauth_token_secret\=\w+:)/",
				"oauth_token_secret=[REDACTED]:",
				$key );
			$this->logger->info( '{key} exists, so nonce has been used by this consumer+token',
				[ 'key' => $key, 'consumer' => $consumer->key, 'oauth_timestamp' => $timestamp ] );
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
			MWCryptRand::generateHex( 32 ),
			MWCryptRand::generateHex( 32 )
		);
	}

	/**
	 * Generate a new token (attached to this consumer), save it in the cache, and return it
	 *
	 * @param Consumer|OAuthConsumer $consumer
	 * @param string $callback
	 * @return MWOAuthToken
	 */
	public function new_request_token( $consumer, $callback = 'oob' ) {
		$token = self::newToken();
		$cacheConsumerKey = Utils::getCacheKey( 'consumer', 'request', $token->key );
		$cacheTokenKey = Utils::getCacheKey(
			'token', $consumer->key, 'request', $token->key
		);
		$cacheCallbackKey = Utils::getCacheKey(
			'callback', $consumer->key, 'request', $token->key
		);

		// 600s == 10 minutes. Kind of arbitrary.
		$this->tokenCache->add( $cacheConsumerKey, $consumer->key, 600 );
		$this->tokenCache->add( $cacheTokenKey, $token, 600 );
		$this->tokenCache->add( $cacheCallbackKey, $callback, 600 );
		$this->logger->debug( __METHOD__ .
			": New request token {$token->key} for {$consumer->key} with callback {$callback}" );
		return $token;
	}

	/**
	 * Return a consumer key associated with the given request token.
	 *
	 * @param string $requestToken
	 * @return string|false the consumer key or false if nothing is stored for the request token
	 */
	public function getConsumerKey( $requestToken ) {
		$cacheKey = Utils::getCacheKey( 'consumer', 'request', $requestToken );
		return $this->tokenCache->get( $cacheKey );
	}

	/**
	 * Return a stored callback URL parameter given by the consumer in /initiate.
	 * It throws an exception if callback URL parameter does not exist in the cache.
	 * A stored callback URL parameter is deleted from the cache once read for the first
	 * time.
	 *
	 * @param string $consumerKey
	 * @param string $requestKey original request key from /initiate
	 * @throws MWOAuthException
	 * @return string|false the stored callback URL parameter
	 */
	public function getCallbackUrl( $consumerKey, $requestKey ) {
		$cacheKey = Utils::getCacheKey( 'callback', $consumerKey, 'request', $requestKey );
		$callback = $this->tokenCache->get( $cacheKey );
		if ( $callback === null || !is_string( $callback ) ) {
			throw new MWOAuthException( 'mwoauthdatastore-callback-not-found', [
				'consumer' => $consumerKey,
			] );
		}
		$this->tokenCache->delete( $cacheKey );
		return $callback;
	}

	/**
	 * Return a new access token attached to this consumer for the user associated with this
	 * token if the request token is authorized. Should also invalidate the request token.
	 *
	 * @param MWOAuthToken $token the request token that started this
	 * @param Consumer $consumer
	 * @param int|null $verifier
	 * @throws MWOAuthException
	 * @return MWOAuthToken the access token
	 */
	public function new_access_token( $token, $consumer, $verifier = null ) {
		$this->logger->debug( __METHOD__ .
			": Getting new access token for token {$token->key}, consumer {$consumer->key}" );

		if ( !$token->getVerifyCode() || !$token->getAccessKey() ) {
			throw new MWOAuthException( 'mwoauthdatastore-bad-token', [
				'consumer' => $consumer->getConsumerKey(),
				'consumer_name' => $consumer->getName(),
				'token' => $token->key,
			] );
		} elseif ( $token->getVerifyCode() !== $verifier ) {
			throw new MWOAuthException( 'mwoauthdatastore-bad-verifier', [
				'consumer' => $consumer->getConsumerKey(),
				'consumer_name' => $consumer->getName(),
				'token' => $token->key,
			] );
		}

		$cacheKey = Utils::getCacheKey( 'token',
			$consumer->getConsumerKey(), 'request', $token->key );
		$accessToken = $this->lookup_token( $consumer, 'access', $token->getAccessKey() );
		$this->tokenCache->set( $cacheKey, '**USED**', 600 );
		$this->logger->debug( __METHOD__ .
			": New access token {$accessToken->key} for {$consumer->key}" );
		return $accessToken;
	}

	/**
	 * Update a request token. The token probably already exists, but had another attribute added.
	 *
	 * @param MWOAuthToken $token the token to store
	 * @param Consumer|OAuthConsumer $consumer
	 */
	public function updateRequestToken( $token, $consumer ) {
		$cacheKey = Utils::getCacheKey( 'token', $consumer->key, 'request', $token->key );
		// 10 more minutes. Kind of arbitrary.
		$this->tokenCache->set( $cacheKey, $token, 600 );
	}

	/**
	 * Return the string representing the Consumer's public RSA key
	 *
	 * @param string $consumerKey the string value of the Consumer's key
	 * @return string|null
	 */
	public function getRSAKey( $consumerKey ) {
		$cmr = Consumer::newFromKey( $this->centralReplica, $consumerKey );
		return $cmr ? $cmr->getRsaKey() : null;
	}
}
