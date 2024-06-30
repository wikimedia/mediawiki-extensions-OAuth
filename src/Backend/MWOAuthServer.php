<?php

namespace MediaWiki\Extension\OAuth\Backend;

use MediaWiki\Extension\OAuth\Lib\OAuthServer;
use MediaWiki\Linker\Linker;
use MediaWiki\Message\Message;
use MediaWiki\SpecialPage\SpecialPage;
use MediaWiki\User\User;

class MWOAuthServer extends OAuthServer {
	/** @var MWOAuthDataStore */
	protected $data_store;

	/**
	 * Return a consumer key associated with the given request token.
	 *
	 * @param string $requestToken
	 * @return string|false the consumer key or false if nothing is stored for the request token
	 */
	public function getConsumerKey( $requestToken ) {
		return $this->data_store->getConsumerKey( $requestToken );
	}

	/**
	 * Process a request_token request returns the request token on success. This
	 * also checks the IP restriction, which the OAuthServer method did not.
	 *
	 * @param MWOAuthRequest &$request
	 * @return MWOAuthToken
	 * @throws MWOAuthException
	 */
	public function fetch_request_token( &$request ) {
		$this->get_version( $request );

		/** @var Consumer $consumer */
		$consumer = $this->get_consumer( $request );

		// Consumer must not be owner-only
		if ( $consumer->getOwnerOnly() ) {
			throw new MWOAuthException( 'mwoauthserver-consumer-owner-only', [
				'consumer_name' => $consumer->getName(),
				'update_url' => SpecialPage::getTitleFor(
					'OAuthConsumerRegistration', 'update/' . $consumer->getConsumerKey()
				),
				Message::rawParam( Linker::makeExternalLink(
					'https://www.mediawiki.org/wiki/Help:OAuth/Errors#E010',
					'E010',
					true
				) ),
				'consumer' => $consumer->getConsumerKey(),
			] );
		}

		// Consumer must have a key for us to verify
		if ( !$consumer->getSecretKey() && !$consumer->getRsaKey() ) {
			throw new MWOAuthException( 'mwoauthserver-consumer-no-secret', [
				Message::rawParam( Linker::makeExternalLink(
					'https://www.mediawiki.org/wiki/Help:OAuth/Errors#E011',
					'E011',
					true
				) ),
				'consumer' => $consumer->getConsumerKey(),
				'consumer_name' => $consumer->getName(),
			] );
		}

		$this->checkSourceIP( $consumer, $request );

		// no token required for the initial token request
		$token = null;

		$this->check_signature( $request, $consumer, $token );

		$callback = $request->get_parameter( 'oauth_callback' );

		$this->checkCallback( $consumer, $callback );

		$new_token = $this->data_store->new_request_token( $consumer, $callback );
		// @phan-suppress-next-line PhanUndeclaredProperty Class uses AllowDynamicProperties for php8.2
		$new_token->oauth_callback_confirmed = 'true';
		return $new_token;
	}

	/**
	 * Ensure the callback is "oob" or that the registered callback is a valid
	 * prefix of the supplied callback. It throws an exception if callback is
	 * invalid.
	 *
	 * In MediaWiki, we require the callback to be established at
	 * registration. OAuth 1.0a (rfc5849, section 2.1) specifies that
	 * oauth_callback is required for the temporary credentials, and "If the
	 * client is unable to receive callbacks or a callback URI has been
	 * established via other means, the parameter value MUST be set to "oob"
	 * (case sensitive), to indicate an out-of-band configuration." Otherwise,
	 * client can provide a callback and the configured callback must be
	 * a prefix of the supplied callback. The matching performed here is based
	 * on parsed URL components rather than strict string matching. Protocol
	 * upgrades from http to https are also allowed, and the registered callback
	 * can be made to match any port number, by specifying port 1. (This is
	 * less secure, and only meant for demo consumers for local development.)
	 *
	 * @param Consumer $consumer
	 * @param string $callback
	 * @return void
	 * @throws MWOAuthException
	 */
	private function checkCallback( $consumer, $callback ) {
		if ( !$consumer->getCallbackIsPrefix() ) {
			if ( $callback !== 'oob' ) {
				throw new MWOAuthException( 'mwoauth-callback-not-oob', [
					'consumer' => $consumer->getConsumerKey(),
					'consumer_name' => $consumer->getName(),
					'callback_url' => $callback,
				] );
			}

			return;
		}

		if ( !$callback ) {
			throw new MWOAuthException( 'mwoauth-callback-not-oob-or-prefix', [
				'consumer' => $consumer->getConsumerKey(),
				'consumer_name' => $consumer->getName(),
			] );
		}
		if ( $callback === 'oob' ) {
			return;
		}

		$reqCallback = wfParseUrl( $callback );
		if ( $reqCallback === false ) {
			throw new MWOAuthException( 'mwoauth-callback-not-oob-or-prefix', [
				'consumer' => $consumer->getConsumerKey(),
				'consumer_name' => $consumer->getName(),
				'callback_url' => $callback,
			] );
		}

		$knownCallback = wfParseUrl( $consumer->getCallbackUrl() );
		$exactPath = array_key_exists( 'query', $knownCallback );

		$match =
			// Protocol can be upgraded from http to https
			self::looseSchemeMatch( $knownCallback['scheme'], $reqCallback['scheme'] ) &&
			// Host must match exactly
			$knownCallback['host'] === $reqCallback['host'] &&
			// Port must be either missing from both or an exact match,
			// unless the registered callback allows any port, which is specified
			// by using port 1.
			( static::getOrNull( 'port', $knownCallback ) === 1 ||
				static::getOrNull( 'port', $knownCallback ) ===
					static::getOrNull( 'port', $reqCallback )
			) &&
			// Path must be an exact match if query is provided in the
			// registered callback. Otherwise it must be a prefix match if
			// provided in the registered callback or anything if no path was
			// included in the registered callback at all.
			static::componentMatches( 'path', $knownCallback, $reqCallback, $exactPath ) &&
			// Query string must be aprefix match if provided in the
			// registered callback.
			static::componentMatches( 'query', $knownCallback, $reqCallback );

		if ( !$match ) {
			throw new MWOAuthException( 'mwoauth-callback-not-oob-or-prefix', [
				'consumer' => $consumer->getConsumerKey(),
				'consumer_name' => $consumer->getName(),
				'callback_url' => $callback,
				'consumer_callback_prefix' => $consumer->getCallbackUrl(),
			] );
		}
	}

	/**
	 * Compare URL schemes for a match.
	 *
	 * Allows 'https' to match an expected 'http' value.
	 *
	 * @param string $want
	 * @param string $got
	 * @return bool
	 */
	private static function looseSchemeMatch( $want, $got ) {
		if ( $want === 'http' ) {
			return in_array( $got, [ 'http', 'https' ], true );
		} else {
			return $want === $got;
		}
	}

	/**
	 * Get a named value from an array or return null if the key does not
	 * exist.
	 *
	 * @param string $key
	 * @param array $arr
	 * @return mixed
	 */
	private static function getOrNull( $key, $arr ) {
		return array_key_exists( $key, $arr ) ? $arr[$key] : null;
	}

	/**
	 * Check that a callback URL component matches the expected value.
	 *
	 * @param string $part URL component name
	 * @param array $expect Expected URL components
	 * @param array $got Posted URl components
	 * @param bool $exact Perform exact match instead of prefix match
	 * @return bool
	 */
	private static function componentMatches(
		$part, $expect, $got, $exact = false
	) {
		if ( !array_key_exists( $part, $expect ) ) {
			// Anything in the request is ok if we do not have the URL part in
			// the expected values
			$match = true;
		} elseif ( !array_key_exists( $part, $got ) ) {
			$match = false;
		} elseif ( $exact ) {
			$match = $expect[$part] === $got[$part];
		} else {
			$want = (string)$expect[$part];
			$have = (string)$got[$part];
			$match = strpos( $have, $want ) === 0;
		}
		return $match;
	}

	/**
	 * process an access_token request
	 * returns the access token on success
	 *
	 * @param MWOAuthRequest &$request
	 * @return MWOAuthToken
	 * @throws MWOAuthException
	 */
	public function fetch_access_token( &$request ) {
		$this->get_version( $request );

		/** @var Consumer $consumer */
		$consumer = $this->get_consumer( $request );

		// Consumer must not be owner-only
		if ( $consumer->getOwnerOnly() ) {
			throw new MWOAuthException( 'mwoauthserver-consumer-owner-only', [
				'consumer_name' => $consumer->getName(),
				'update_url' => SpecialPage::getTitleFor(
					'OAuthConsumerRegistration', 'update/' . $consumer->getConsumerKey()
				),
				Message::rawParam( Linker::makeExternalLink(
					'https://www.mediawiki.org/wiki/Help:OAuth/Errors#E010',
					'E010',
					true
				) ),
				'consumer' => $consumer->getConsumerKey(),
			] );
		}

		// Consumer must have a key for us to verify
		if ( !$consumer->getSecretKey() && !$consumer->getRsaKey() ) {
			throw new MWOAuthException( 'mwoauthserver-consumer-no-secret', [
				Message::rawParam( Linker::makeExternalLink(
					'https://www.mediawiki.org/wiki/Help:OAuth/Errors#E011',
					'E011',
					true
				) ),
				'consumer' => $consumer->getConsumerKey(),
				'consumer_name' => $consumer->getName(),
			] );
		}

		$this->checkSourceIP( $consumer, $request );

		// requires authorized request token
		/** @var MWOAuthToken $token */
		$token = $this->get_token( $request, $consumer, 'request' );

		if ( !$token->secret ) {
			// This token has a blank secret.. something is wrong
			throw new MWOAuthException( 'mwoauthdatastore-bad-token', [
				'consumer' => $consumer->getConsumerKey(),
				'consumer_name' => $consumer->getName(),
				'token' => $token->key,
			] );
		}

		$this->check_signature( $request, $consumer, $token );

		// Rev A change
		$verifier = $request->get_parameter( 'oauth_verifier' );
		$this->logger->debug( __METHOD__ . ": verify code is '$verifier'" );
		return $this->data_store->new_access_token( $token, $consumer, $verifier );
	}

	/**
	 * Wrap the call to the parent function and check that the source IP of
	 * the request is allowed by this consumer's restrictions.
	 * @param MWOAuthRequest &$request
	 * @return array
	 */
	public function verify_request( &$request ) {
		[ $consumer, $token ] = parent::verify_request( $request );
		$this->checkSourceIP( $consumer, $request );
		return [ $consumer, $token ];
	}

	/**
	 * Ensure the request comes from an approved IP address, if IP restriction has been
	 * setup by the Consumer. It throws an exception if IP address is invalid.
	 *
	 * @param Consumer $consumer
	 * @param MWOAuthRequest $request
	 * @throws MWOAuthException
	 */
	private function checkSourceIP( $consumer, $request ) {
		$restrictions = $consumer->getRestrictions();
		if ( !$restrictions->checkIP( $request->getSourceIP() ) ) {
			throw new MWOAuthException( 'mwoauthdatastore-bad-source-ip', [
				'consumer' => $consumer->getConsumerKey(),
				'consumer_name' => $consumer->getName(),
				'request_ip' => $request->getSourceIP(),
			] );
		}
	}

	/**
	 * @deprecated User MWOAuthConsumer::authorize(...)
	 *
	 * @param string $consumerKey
	 * @param string $requestTokenKey
	 * @param User $mwUser
	 * @param bool $update
	 * @return string
	 */
	public function authorize( $consumerKey, $requestTokenKey, User $mwUser, $update ) {
		$dbr = Utils::getCentralDB( DB_REPLICA );
		$consumer = Consumer::newFromKey( $dbr, $consumerKey );
		return $consumer->authorize( $mwUser, $update, $consumer->getGrants(), $requestTokenKey );
	}

	/**
	 * Attempts to find an authorization by this user for this consumer. Since a user can
	 * accept a consumer multiple times (once for "*" and once for each specific wiki),
	 * there can several access tokens per-wiki (with varying grants) for a consumer.
	 * This will choose the most wiki-specific access token. The precedence is:
	 * a) The acceptance for wiki X if the consumer is applicable only to wiki X
	 * b) The acceptance for wiki $wikiId (if the consumer is applicable to it)
	 * c) The acceptance for wikis "*" (all wikis)
	 *
	 * Users might want more grants on some wikis than on "*". Note that the reverse would not
	 * make sense, since the consumer could just use the "*" acceptance if it has more grants.
	 *
	 * @param User $mwUser (local wiki user) User who may or may not have authorizations
	 * @param Consumer $consumer
	 * @param string $wikiId
	 * @throws MWOAuthException
	 * @return ConsumerAcceptance
	 * @deprecated Use MWOAuthConsumer::getCurrentAuthorization(...)
	 */
	public function getCurrentAuthorization( User $mwUser, $consumer, $wikiId ) {
		wfDeprecated( __METHOD__ );
		return $consumer->getCurrentAuthorization( $mwUser, $wikiId );
	}
}
