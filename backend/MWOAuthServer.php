<?php

class MWOAuthServer extends OAuthServer {
	/**
	 * Process a request_token request returns the request token on success. This
	 * also checks the IP restriction, which the OAuthServer method did not.
	 *
	 * @param MWOAuthRequest the request
	 * @return MWOAuthToken
	 * @throws MWOAuthException
	 */
	public function fetch_request_token( &$request ) {
		$this->get_version( $request );

		$consumer = $this->get_consumer( $request );

		// Consumer must have a key for us to verify
		if ( !$consumer->get( 'secretKey' ) && !$consumer->get( 'rsaKey' ) ) {
			throw new MWOAuthException( 'invalid-consumer' );
		}

		$this->checkSourceIP( $consumer, $request );

		// no token required for the initial token request
		$token = NULL;

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
	 * @param MWOAuthRequest the request
	 * @return MWOAuthToken
	 * @throws MWOAuthException
	 */
	public function fetch_access_token( &$request ) {
		$this->get_version( $request );

		$consumer = $this->get_consumer( $request );

		// Consumer must have a key for us to verify
		if ( !$consumer->get( 'secretKey' ) && !$consumer->get( 'rsaKey' ) ) {
			throw new MWOAuthException( 'invalid-consumer' );
		}

		$this->checkSourceIP( $consumer, $request );

		// requires authorized request token
		$token = $this->get_token( $request, $consumer, 'request' );

		if ( !$token->secret ) {
			// This token has a blank secret.. something is wrong
			throw new MWOAuthException( 'bad-token' );
		}

		$this->check_signature( $request, $consumer, $token );

		// Rev A change
		$verifier = $request->get_parameter( 'oauth_verifier' );
		wfDebugLog( 'OAuth', __METHOD__ . ": verify code is '$verifier'" );
		$new_token = $this->data_store->new_access_token( $token, $consumer, $verifier );

		return $new_token;
	}

	/**
	 * Ensure the request comes from an approved IP address, if IP restriction has been
	 * setup by the Consumer.
	 *
	 * @param MWOAuthConsumer $consumer
	 * @param MWOAuthRequest $request
	 */
	private function checkSourceIP( $consumer, $request ) {
		$restrictions = $consumer->get( 'restrictions' );
		$requestIP = $request->getSourceIP();

		if ( !isset( $restrictions['IPAddresses'] ) ) {
			return true; // sanity; should not happen
		}

		foreach ( $restrictions['IPAddresses'] as $range ) {
			if ( IP::isInRange( $requestIP, $range ) ) {
				return true;
			}
		}

		return false;
	}

	/**
	 * The user has authorized the request by this consumer, with this request token. Update
	 * everything so that the consumer can swap the request token for an access token. Then
	 * generate the callback URL where we will redirect our user back to the consumer.
	 * @param String $consumerKey
	 * @param String $requestTokenKey
	 * @param User $mwUser user authorizing the request (local user)
	 * @param bool $update update the grants/wiki to those requested by consumer
	 * @return String the callback URL to redirect the user
	 * @throws MWOAuthException
	 */
	public function authorize( $consumerKey, $requestTokenKey, User $mwUser, $update ) {
		// Check that user and consumer are in good standing
		if ( $mwUser->isBlocked() ) {
			throw new MWOAuthException( 'mwoauthserver-insufficient-rights' );
		}
		$consumer = $this->data_store->lookup_consumer( $consumerKey );
		if ( !$consumer ) {
			throw new MWOAuthException( 'mwoauthserver-bad-consumer' );
		} elseif ( $consumer->get( 'stage' ) !== MWOAuthConsumer::STAGE_APPROVED ) {
			throw new MWOAuthException( 'mwoauthserver-bad-consumer' );
		} elseif ( $consumer->get( 'deleted' ) ) { // extra sanity
			throw new MWOAuthException( 'mwoauthserver-bad-consumer' );
		}

		// Generate and Update the tokens:
		// * Generate a new Verification code, and add it to the request token
		// * Either add or update the authorization
		// ** Generate a new access token if this is a new authorization
		// * Resave request token with the access token

		$verifyCode = MWCryptRand::generateHex( 32, true);
		$requestToken = $this->data_store->lookup_token( $consumer, 'request', $requestTokenKey );
		if ( !$requestToken || !( $requestToken instanceof MWOAuthToken ) ) {
			throw new MWOAuthException( 'mwoauthserver-invalid-request-token' );
		}
		$requestToken->addVerifyCode( $verifyCode );

		// CentralAuth may abort here if there is no global account for this user
		$userId = MWOAuthUtils::getCentralIdFromLocalUser( $mwUser );
		if ( !$userId ) {
			throw new MWOAuthException( 'mwoauthserver-invalid-user' );
		}

		// Authorization Token
		$dbw = MWOAuthUtils::getCentralDB( DB_MASTER );

		// Check if this authorization exists
		$cmra = $this->getCurrentAuthorization( $mwUser, $consumer );

		if ( $update ) {
			// This should be an update to an existing authorization
			if ( !$cmra ) {
				// update requested, but no existing key
				throw new MWOAuthException( 'mwoauthserver-invalid-request' );
			}
			$cmra->setFields( array(
				'wiki'   => $consumer->get( 'wiki' ),
				'grants' => $consumer->get( 'grants' )
			) );
			$cmra->save( $dbw );
			$accessToken = new MWOAuthToken( $cmra->get( 'accessToken' ), '' );
		} elseif ( !$cmra ) {
			// Add the Authorization to the database
			$accessToken = MWOAuthDataStore::newToken();
			$cmra = MWOAuthConsumerAcceptance::newFromArray( array(
				'wiki'         => $consumer->get( 'wiki' ),
				'userId'       => $userId,
				'consumerId'   => $consumer->get( 'id' ),
				'accessToken'  => $accessToken->key,
				'accessSecret' => $accessToken->secret,
				'grants'       => $consumer->get( 'grants' ),
				'accepted'     => wfTimestampNow()
			) );
			$cmra->save( $dbw );
		} else {
			// Authorization exists, no updates requested, so no changes to the db
			$accessToken = new MWOAuthToken( $cmra->get( 'accessToken' ), '' );
		}

		$requestToken->addAccessKey( $accessToken->key );
		$this->data_store->updateRequestToken( $requestToken, $consumer );
		wfDebugLog( 'OAuth', "Verification code {$requestToken->getVerifyCode()} for $requestTokenKey (client: $consumerKey)" );
		return $consumer->generateCallbackUrl( $requestToken->getVerifyCode(), $requestTokenKey );
	}

	/**
	 * Attempts to get an authorization by this user, for this consumer. First attempts
	 * to fine an acceptance for the current wiki, when for '*' wikis. In theory, a user
	 * could authorize different grants on a particular wiki vs. all wikis, for a given
	 * consumer.
	 * @param User $mwUser (local wiki user) User who may or may not have authorizations
	 * @param MWOAuthConsumer $consumer
	 * @param integer $flags MWOAuthConsumerAcceptance::READ_* bitfield
	 * @return MWOAuthConsumerAcceptance
	 */
	public function getCurrentAuthorization( User $mwUser, $consumer ) {
		$dbr = MWOAuthUtils::getCentralDB( DB_SLAVE );

		$centralUserId = MWOAuthUtils::getCentralIdFromLocalUser( $mwUser );
		if ( !$centralUserId ) {
			throw new MWOAuthException( 'mwoauthserver-invalid-user' );
		}

		$cmra = MWOAuthConsumerAcceptance::newFromUserConsumerWiki(
			$dbr,
			$centralUserId,
			$consumer,
			wfWikiID()
		);
		if ( !$cmra ) {
			$cmra = MWOAuthConsumerAcceptance::newFromUserConsumerWiki(
				$dbr,
				$centralUserId,
				$consumer,
				'*'
			);
		}
		return $cmra;
	}

}
