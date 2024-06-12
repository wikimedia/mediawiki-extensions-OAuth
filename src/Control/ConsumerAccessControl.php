<?php

namespace MediaWiki\Extension\OAuth\Control;

use MediaWiki\Extension\OAuth\Backend\Consumer;
use MediaWiki\Extension\OAuth\Backend\Utils;
use MediaWiki\Extension\OAuth\Entity\ClientEntity;
use MediaWiki\Message\Message;
use MediaWiki\User\User;
use MWRestrictions;

class ConsumerAccessControl extends DAOAccessControl {
	// accessor fields copied from MWOAuthConsumer, except they can return a Message on access error

	/**
	 * Internal ID (DB primary key).
	 * Returns a Message when the user does not have permission to see this field.
	 * @return int|Message
	 */
	public function getId() {
		return $this->get( 'id' );
	}

	/**
	 * Consumer key (32-character hexadecimal string that's used in the OAuth protocol
	 * and in URLs). This is used as the consumer ID for most external purposes.
	 * Returns a Message when the user does not have permission to see this field.
	 * @return string|Message
	 */
	public function getConsumerKey() {
		return $this->get( 'consumerKey' );
	}

	/**
	 * Name of the consumer.
	 * Returns a Message when the user does not have permission to see this field.
	 * @return string|Message
	 */
	public function getName() {
		return $this->get( 'name' );
	}

	/**
	 * @return int
	 */
	public function getOAuthVersion() {
		return (int)$this->get( 'oauthVersion' );
	}

	/**
	 * Central ID of the owner.
	 * Returns a Message when the user does not have permission to see this field.
	 * @return int|Message
	 */
	public function getUserId() {
		return $this->get( 'userId' );
	}

	/**
	 * Consumer version. This is mostly meant for humans: different versions of the same
	 * application have different keys and are handled as different consumers internally.
	 * Returns a Message when the user does not have permission to see this field.
	 * @return string|Message
	 */
	public function getVersion() {
		return $this->get( 'version' );
	}

	/**
	 * Callback URL (or prefix). The browser will be redirected to this URL at the end of
	 * an OAuth handshake. See getCallbackIsPrefix() for the interpretation of this field.
	 * Returns a Message when the user does not have permission to see this field.
	 * @return string|Message
	 */
	public function getCallbackUrl() {
		return $this->get( 'callbackUrl' );
	}

	/**
	 * When true, getCallbackUrl() returns a prefix; the callback URL can be provided by the caller
	 * as long as the prefix matches. When false, the callback URL will be determined by
	 * getCallbackUrl().
	 * Returns a Message when the user does not have permission to see this field.
	 * @return bool|Message
	 */
	public function getCallbackIsPrefix() {
		return $this->get( 'callbackIsPrefix' );
	}

	/**
	 * Description of the consumer. Currently interpreted as plain text; might change to wikitext
	 * in the future.
	 * Returns a Message when the user does not have permission to see this field.
	 * @return string|Message
	 */
	public function getDescription() {
		return $this->get( 'description' );
	}

	/**
	 * Email address of the owner.
	 * Returns a Message when the user does not have permission to see this field.
	 * @return string|Message
	 */
	public function getEmail() {
		return $this->get( 'email' );
	}

	/**
	 * Date of verifying the email, in TS_MW format. In practice this will be the same as
	 * getRegistration().
	 * Returns null if the timestamp is not set.
	 * Returns a Message when the user does not have permission to see this field.
	 * @return string|null|Message
	 */
	public function getEmailAuthenticated() {
		return $this->get( 'emailAuthenticated' );
	}

	/**
	 * Did the user accept the developer agreement (the terms of use checkbox at the bottom of the
	 * registration form)? Except for very old users, always true.
	 * Returns a Message when the user does not have permission to see this field.
	 * @return bool|Message
	 */
	public function getDeveloperAgreement() {
		return $this->get( 'developerAgreement' );
	}

	/**
	 * Owner-only consumers will use one-legged flow instead of three-legged (see
	 * https://github.com/Mashape/mashape-oauth/blob/master/FLOWS.md#oauth-10a-one-legged ); there
	 * is only one user (who is the same as the owner) and they learn the access token at
	 * consumer registration time.
	 * Returns a Message when the user does not have permission to see this field.
	 * @return bool|Message
	 */
	public function getOwnerOnly() {
		return $this->get( 'ownerOnly' );
	}

	/**
	 * The wiki on which the consumer is allowed to access user accounts. A wiki ID or '*' for all.
	 * Returns a Message when the user does not have permission to see this field.
	 * @return string|Message
	 */
	public function getWiki() {
		return $this->get( 'wiki' );
	}

	/**
	 * The list of grants required by this application.
	 * Returns a Message when the user does not have permission to see this field.
	 * @return string[]|Message
	 */
	public function getGrants() {
		return $this->get( 'grants' );
	}

	/**
	 * Consumer registration date in TS_MW format.
	 * Returns a Message when the user does not have permission to see this field.
	 * @return string|Message
	 */
	public function getRegistration() {
		return $this->get( 'registration' );
	}

	/**
	 * Secret key used to derive the consumer secret for HMAC-SHA1 signed OAuth requests.
	 * The actual consumer secret will be calculated via Utils::hmacDBSecret() to mitigate
	 * DB leaks.
	 * Returns a Message when the user does not have permission to see this field.
	 * @return string|Message
	 */
	public function getSecretKey() {
		return $this->get( 'secretKey' );
	}

	/**
	 * Public RSA key for RSA-SHA1 signed OAuth requests.
	 * Returns a Message when the user does not have permission to see this field.
	 * @return string|Message
	 */
	public function getRsaKey() {
		return $this->get( 'rsaKey' );
	}

	/**
	 * Application restrictions (such as allowed IPs).
	 * Returns a Message when the user does not have permission to see this field.
	 * @return MWRestrictions|Message
	 */
	public function getRestrictions() {
		return $this->get( 'restrictions' );
	}

	/**
	 * Stage at which the consumer is in the review workflow (proposed, approved etc).
	 * Returns a Message when the user does not have permission to see this field.
	 * @return int|Message One of the STAGE_* constants
	 */
	public function getStage() {
		return $this->get( 'stage' );
	}

	/**
	 * Date at which the consumer was moved to the current stage, in TS_MW format.
	 * Returns a Message when the user does not have permission to see this field.
	 * @return string|Message
	 */
	public function getStageTimestamp() {
		return $this->get( 'stageTimestamp' );
	}

	/**
	 * Is the consumer suppressed? (There is no plain deletion; the closest equivalent is the
	 * rejected/disabled stage.)
	 * Returns a Message when the user does not have permission to see this field.
	 * @return bool|Message
	 */
	public function getDeleted() {
		return $this->get( 'deleted' );
	}

	// accessors for common formatting

	/**
	 * Owner username.
	 * Note that this method triggers a DB lookup.
	 * @param User|bool $audience show hidden names based on this user, or false for public
	 * @return string|Message
	 */
	public function getUserName( $audience = false ) {
		return $this->get( 'userId', static function ( $id ) use ( $audience ) {
			return Utils::getCentralUserNameFromId( $id, $audience );
		} );
	}

	/**
	 * Pretty wiki name.
	 * @return string|Message
	 */
	public function getWikiName() {
		return $this->get( 'wiki', static function ( $wikiId ) {
			return Utils::getWikiIdName( $wikiId );
		} );
	}

	/**
	 * Consumer name and version in a "Foo [1.0]" format.
	 * @return string|Message
	 */
	public function getNameAndVersion() {
		return $this->get( 'name', function ( $s ) {
			return $s . ' ' . $this->msg( 'brackets', $this->getVersion() )->plain();
		} );
	}

	/**
	 * Whether the consumer is confidential or not.
	 * Only meaningful for OAuth 2.0 consumers (see {@link getOAuthVersion()})
	 * and must not be called otherwise.
	 * @return bool
	 */
	public function isConfidential() {
		return (bool)$this->get( 'oauth2IsConfidential' );
	}

	/**
	 * @return Consumer|ClientEntity
	 */
	public function getDAO() {
		// @phan-suppress-next-line PhanTypeMismatchReturnSuperType
		return $this->dao;
	}
}
