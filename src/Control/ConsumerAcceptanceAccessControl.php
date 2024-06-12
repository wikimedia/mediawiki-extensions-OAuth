<?php

namespace MediaWiki\Extension\OAuth\Control;

use MediaWiki\Extension\OAuth\Backend\ConsumerAcceptance;
use MediaWiki\Extension\OAuth\Backend\Utils;
use MediaWiki\Message\Message;

class ConsumerAcceptanceAccessControl extends DAOAccessControl {
	// accessor fields copied from ConsumerAcceptance, except they can return a Message
	// on access error

	/**
	 * Database ID.
	 * Returns a Message when the user does not have permission to see this field.
	 * @return int|Message
	 */
	public function getId() {
		return $this->get( 'id' );
	}

	/**
	 * Wiki on which the user has authorized the consumer to access their account. Wiki ID or '*'
	 * for all.
	 * Returns a Message when the user does not have permission to see this field.
	 * @return string|Message
	 */
	public function getWiki() {
		return $this->get( 'wiki' );
	}

	/**
	 * Central user ID of the authorizing user.
	 * Returns a Message when the user does not have permission to see this field.
	 * @return int|Message
	 */
	public function getUserId() {
		return $this->get( 'userId' );
	}

	/**
	 * Database ID of the consumer.
	 * Returns a Message when the user does not have permission to see this field.
	 * @return int|Message
	 */
	public function getConsumerId() {
		return $this->get( 'consumerId' );
	}

	/**
	 * The access token for the OAuth protocol
	 * Returns a Message when the user does not have permission to see this field.
	 * @return string|Message
	 */
	public function getAccessToken() {
		return $this->get( 'accessToken' );
	}

	/**
	 * Secret key used to derive the access secret for the OAuth protocol.
	 * The actual access secret will be calculated via Utils::hmacDBSecret() to mitigate
	 * DB leaks.
	 * Returns a Message when the user does not have permission to see this field.
	 * @return string|Message
	 */
	public function getAccessSecret() {
		return $this->get( 'accessSecret' );
	}

	/**
	 * The list of grants which have been granted.
	 * Returns a Message when the user does not have permission to see this field.
	 * @return string[]|Message
	 */
	public function getGrants() {
		return $this->get( 'grants' );
	}

	/**
	 * Date of the authorization, in TS_MW format.
	 * Returns a Message when the user does not have permission to see this field.
	 * @return string|Message
	 */
	public function getAccepted() {
		return $this->get( 'accepted' );
	}

	// accessors for common formatting

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
	 * @return ConsumerAcceptance
	 */
	public function getDAO() {
		// @phan-suppress-next-line PhanTypeMismatchReturnSuperType
		return $this->dao;
	}
}
