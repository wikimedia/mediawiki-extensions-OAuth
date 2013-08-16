<?php
/*
 (c) Aaron Schulz 2013, GPL

 This program is free software; you can redistribute it and/or modify
 it under the terms of the GNU General Public License as published by
 the Free Software Foundation; either version 2 of the License, or
 (at your option) any later version.

 This program is distributed in the hope that it will be useful,
 but WITHOUT ANY WARRANTY; without even the implied warranty of
 MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 GNU General Public License for more details.

 You should have received a copy of the GNU General Public License along
 with this program; if not, write to the Free Software Foundation, Inc.,
 59 Temple Place - Suite 330, Boston, MA 02111-1307, USA.
 http://www.gnu.org/copyleft/gpl.html
*/

/**
 * This handles the core logic of submitting/approving application
 * consumer requests and the logic of managing approved consumers
 *
 * @TODO: improve error messages
 */
class MWOAuthConsumerAcceptanceSubmitControl extends MWOAuthSubmitControl {
	/** @var DBConnRef */
	protected $dbw;

	/**
	 * @param IContextSource $context
	 * @param array $params
	 * @param DBConnRef $dbw Result of MWOAuthUtils::getCentralDB( DB_MASTER )
	 */
	public function __construct( IContextSource $context, array $params, DBConnRef $dbw ) {
		parent::__construct( $context, $params );
		$this->dbw = $dbw;
	}

	protected function getRequiredFields() {
		return array(
			'accept'    => array(
				'consumerKey'  => '/^[0-9a-f]{32}$/',
				'requestToken' => '/^[0-9a-f]{32}$/',
				'wiki'         => function( $s ) {
					return WikiMap::getWiki( $s ) || $s === '*'; },
				'grants'       => function( $s ) {
					$grants = FormatJSON::decode( $s, true );
					return is_array( $grants ) && MWOAuthUtils::grantsAreValid( $grants );
				}
			),
			'update'   => array(
				'acceptanceId' => '/^\d+$/',
				'wiki'        => function( $s ) {
					return WikiMap::getWiki( $s ) || $s === '*'; },
				'grants'      => function( $s ) {
					$grants = FormatJSON::decode( $s, true );
					return is_array( $grants ) && MWOAuthUtils::grantsAreValid( $grants );
				}
			),
			'renounce' => array(
				'acceptanceId' => '/^\d+$/',
			),
		);
	}

	protected function checkBasePermissions() {
		$user = $this->getUser();
		if ( !$user->getID() ) {
			return $this->failure( 'not_logged_in', 'badaccess-group0' );
		} elseif ( !$user->isAllowed( 'mwoauthmanagemygrants' ) ) {
			return $this->failure( 'permission_denied', 'badaccess-group0' );
		} elseif ( wfReadOnly() ) {
			return $this->failure( 'readonly', 'readonlytext', wfReadOnlyReason() );
		} elseif ( !MWOAuthUtils::isCentralWiki() ) { // sanity
			// We attach consumers to the ID of a user on the management wiki
			throw new MWException( "This can only be used from the OAuth management wiki." );
		}
		return $this->success();
	}

	protected function processAction( $action ) {
		$user = $this->getUser(); // proposer or admin
		$dbw = $this->dbw; // convenience

		$centralUserId = MWOAuthUtils::getCentralIdFromLocalUser( $user );
		if ( !$centralUserId ) { // sanity
			return $this->failure( 'permission_denied', 'badaccess-group0' );
		}

		switch ( $action ) {
		/*
		case 'accept': // @TODO: unused (WIP)
			$cmr = MWOAuthConsumer::newFromKey( $dbw, $this->vals['consumerKey'] );
			if ( !$cmr ) {
				return $this->failure( 'invalid_consumer_key', 'mwoauth-invalid-consumer-key' );
			} elseif ( $cmr->get( 'stage' ) !== MWOAuthConsumer::STAGE_APPROVED
				&& !$consumer->isPendingAndOwnedBy( $mwUser ) // let publisher test this
			) {
				return $this->failure( 'permission_denied', 'badaccess-group0' );
			}

			// @TODO: handle exceptions
			$oauthServer = MWOAuthUtils::newMWOAuthServer();
			$callback = $oauthServer->authorize(
				$this->vals['consumerKey'], $this->vals['requestToken'], $this->getUser() );

			// WIP: PUT THIS IN authorize()
			$cmra = MWOAuthConsumerAcceptance::newFromArray( array(
				'wiki'         => $this->vals['wiki'],
				'userId'       => $centralUserId,
				'consumerId'   => $cmr->getId(),
				'accessToken'  => ...,
				'accessSecret' => ...,
				'grants'       => FormatJSON::decode( $this->vals['grants'], true ),
				'accepted'     => wfTimestampNow()
			) );
			return $this->success( $cmra );
			$cmra->save();
		*/
		case 'update':
			$cmra = MWOAuthConsumerAcceptance::newFromId( $dbw, $this->vals['acceptanceId'] );
			if ( !$cmra ) {
				return $this->failure( 'invalid_access_token', 'mwoauth-invalid-access-token' );
			} elseif ( $cmra->get( 'userId' ) !== $centralUserId ) {
				return $this->failure( 'invalid_access_token', 'mwoauth-invalid-access-token' );
			}
			$cmr = MWOAuthConsumer::newFromId( $dbw, $cmra->get( 'consumerId' ) );

			$grants = FormatJSON::decode( $this->vals['grants'], true );
			$cmra->setFields( array(
				'wiki'   => $this->vals['wiki'],
				'grants' => array_intersect( $grants, $cmr->get( 'grants' ) ) // sanity
			) );
			$cmra->save( $dbw );

			return $this->success( $cmra );
		case 'renounce':
			$cmra = MWOAuthConsumerAcceptance::newFromId( $dbw, $this->vals['acceptanceId'] );
			if ( !$cmra ) {
				return $this->failure( 'invalid_access_token', 'mwoauth-invalid-access-token' );
			} elseif ( $cmra->get( 'userId' ) !== $centralUserId ) {
				return $this->failure( 'invalid_access_token', 'mwoauth-invalid-access-token' );
			}

			$cmra->delete( $dbw );

			return $this->success( $cmra );
		}
	}
}
