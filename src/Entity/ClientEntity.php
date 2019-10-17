<?php

namespace MediaWiki\Extensions\OAuth\Entity;

use League\OAuth2\Server\Entities\ClientEntityInterface;
use MediaWiki\Extensions\OAuth\MWOAuthConsumer;
use MediaWiki\Extensions\OAuth\MWOAuthUtils;
use User;
use MWException;

class ClientEntity extends MWOAuthConsumer implements ClientEntityInterface {

	/**
	 * Returns the registered redirect URI (as a string).
	 *
	 * Alternatively return an indexed array of redirect URIs.
	 *
	 * @return string|string[]
	 */
	public function getRedirectUri() {
		return $this->getCallbackUrl();
	}

	/**
	 * Returns true if the client is confidential.
	 *
	 * @return bool
	 */
	public function isConfidential() {
		return $this->oauth2IsConfidential;
	}

	/**
	 * @return mixed
	 */
	public function getIdentifier() {
		return $this->getConsumerKey();
	}

	/**
	 * @param mixed $identifier
	 */
	public function setIdentifier( $identifier ) {
		$this->consumerKey = $identifier;
	}

	/**
	 * Get the grant types this client is allowed to use
	 *
	 * @return array
	 */
	public function getAllowedGrants() {
		return $this->oauth2GrantTypes;
	}

	/**
	 * @return bool|User
	 * @throws MWException
	 */
	public function getUser() {
		return MWOAuthUtils::getLocalUserFromCentralId( $this->getUserId() );
	}

	/**
	 * @param null|string $secret
	 * @param null|string $grantType
	 * @return bool
	 */
	public function validate( $secret, $grantType ) {
		if ( !$secret || !$this->isSecretValid( $secret ) ) {
			return false;
		}

		if ( !$this->isGrantAllowed( $grantType ) ) {
			return false;
		}

		return true;
	}

	/**
	 * @return string
	 */
	public function getOAuthVersion() {
		return static::OAUTH_VERSION_2;
	}

	private function isSecretValid( $secret ) {
		return hash_equals( $secret, MWOAuthUtils::hmacDBSecret( $this->secretKey ) );
	}

	private function isGrantAllowed( $grantType ) {
		return in_array( $grantType, $this->getAllowedGrants() );
	}
}
