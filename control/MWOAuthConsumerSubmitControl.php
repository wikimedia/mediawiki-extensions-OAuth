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
 * This handles the core logic of approving/disabling consumers
 * from using particular user accounts
 *
 * @TODO: improve error messages
 */
class MWOAuthConsumerSubmitControl extends MWOAuthSubmitControl {
	protected function getRequiredFields() {return array(
			// Proposer (application administrator) actions:
			'propose'     => array(
				'name'         => '/^.{1,128}$/',
				'version'      => '/^.{1,32}$/',
				'callbackUrl'  => function( $s ) {
					return wfParseUrl( $s ) !== null; },
				'description'  => '/^.*$/',
				'email'        => function( $s ) {
					return Sanitizer::validateEmail( $s ); },
				'wiki'         => function( $s ) {
					return WikiMap::getWiki( $s ) || $s === '*'; },
				'grants'       => function( $s ) {
					$grants = FormatJSON::decode( $s, true );
					return is_array( $grants ) && MWOAuthUtils::grantsAreValid( $grants );
				},
				'restrictions' => function( $s ) {
					$restrictions = FormatJSON::decode( $s, true );
					return MWOAuthUtils::restrictionsAreValid( $restrictions );
				},
				'rsaKey'       => '/^.*$/', // @TODO: beef up
			),
			'update'      => array(
				'consumerKey'  => '/^[0-9a-f]{32}$/',
				'restrictions' => function( $s ) {
					$restrictions = FormatJSON::decode( $s, true );
					return MWOAuthUtils::restrictionsAreValid( $restrictions );
				},
				'rsaKey'       => '/^.*$/', // @TODO: beef up
				'reason'       => '/^.{0,255}$/'
			),
			// Approver (project administrator) actions:
			'approve'     => array(
				'consumerKey'  => '/^[0-9a-f]{32}$/',
				'reason'       => '/^.{0,255}$/'
			),
			'reject'      => array(
				'consumerKey'  => '/^[0-9a-f]{32}$/',
				'reason'       => '/^.{0,255}$/',
				'suppress'     => '/^[01]$/',
			),
			'disable'     => array(
				'consumerKey'  => '/^[0-9a-f]{32}$/',
				'reason'       => '/^.{0,255}$/',
				'suppress'     => '/^[01]$/',
			),
			'reenable'    => array(
				'consumerKey'  => '/^[0-9a-f]{32}$/',
				'reason'       => '/^.{0,255}$/'
			)
		);
	}

	protected function checkBasePermissions() {
		$user = $this->getUser();
		if ( !$user->getID() ) {
			return $this->failure( 'not_logged_in', 'badaccess-group0' );
		} elseif ( $user->isBlocked() ) {
			return $this->failure( 'user_blocked', 'badaccess-group0' );
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
		$dbw = MWOAuthUtils::getCentralDB( DB_MASTER );

		switch ( $action ) {
		case 'propose':
			if ( !$user->isAllowed( 'mwoauthproposeconsumer' ) ) {
				return $this->failure( 'permission_denied', 'badaccess-group0' );
			} elseif ( !$user->isEmailConfirmed() ) {
				return $this->failure( 'email_not_confirmed', 'mwoauth-email-not-confirmed' );
			} elseif ( $user->getEmail() !== $this->vals['email'] ) {
				// @TODO: allow any email and don't set emailAuthenticated below
				return $this->failure( 'email_mismatched', 'mwoauth-email-email_mismatched' );
			}

			if ( MWOAuthConsumer::newFromNameVersionUser(
				$dbw, $this->vals['name'], $this->vals['version'], $user->getId() ) )
			{
				return $this->failure( 'consumer_exists', 'mwoauth-consumer-alreadyexists' );
			}

			$now = wfTimestampNow();
			$cmr = MWOAuthConsumer::newFromArray(
				array(
					'id'                 => null, // auto-increment
					'consumerKey'        => MWCryptRand::generateHex( 32 ),
					'userId'             => $user->getId(),
					'email'              => $user->getEmail(),
					'emailAuthenticated' => $now, // see above
					'secretKey'          => MWCryptRand::generateHex( 32 ),
					'registration'       => $now,
					'stage'              => MWOAuthConsumer::STAGE_PROPOSED,
					'stageTimestamp'     => $now,
					'grants'             => FormatJSON::decode( $this->vals['grants'], true ),
					'restrictions'       => FormatJSON::decode( $this->vals['restrictions'], true ),
					'deleted'            => 0
				) + $this->vals
			);
			$cmr->save( $dbw );

			$logEntry = new ManualLogEntry( 'mwoauthconsumer', 'propose' );
			$logEntry->setPerformer( $user );
			$logEntry->setTarget( $this->getLogTitle( $dbw, $cmr->get( 'userId' ) ) );
			$logEntry->setComment( $this->vals['description'] );
			$logEntry->setParameters( array( '4:consumer' => $cmr->get( 'consumerKey' ) ) );
			$logEntry->setRelations( array(
				'OAuthConsumer' => array( $cmr->get( 'consumerKey' ) )
			) );
			$logEntry->insert( $dbw );

			return $this->success( $cmr );
		case 'update':
			if ( !$user->isAllowed( 'mwoauthupdateconsumer' ) ) {
				return $this->failure( 'permission_denied', 'badaccess-group0' );
			}

			$cmr = MWOAuthConsumer::newFromKey( $dbw, $this->vals['consumerKey'] );
			if ( !$cmr ) {
				return $this->failure( 'invalid_consumer_key', 'mwoauth-invalid-consumer-key' );
			} elseif ( $cmr->get( 'userId' ) !== $user->getId() ) {
				return $this->failure( 'permission_denied', 'badaccess-group0' );
			} elseif ( $cmr->get( 'stage' ) !== MWOAuthConsumer::STAGE_APPROVED ) {
				return $this->failure( 'not_accepted', 'mwoauth-consumer-not-accepted' );
			} elseif ( $cmr->get( 'deleted' ) && !$user->isAllowed( 'mwoauthsuppress' ) ) {
				return $this->failure( 'permission_denied', 'badaccess-group0' ); // sanity
			}

			$oldVals = $cmr->setFields( array(
				'rsaKey'       => $this->vals['rsaKey'],
				'restrictions' => FormatJSON::decode( $this->vals['restrictions'], true ),
				'secretKey'    => $this->vals['secretKey'] ) );
			$cmr->save( $dbw );

			$logEntry = new ManualLogEntry( 'mwoauthconsumer', 'update' );
			$logEntry->setPerformer( $user );
			$logEntry->setTarget( $this->getLogTitle( $dbw, $cmr->get( 'userId' ) ) );
			$logEntry->setComment( $this->vals['reason'] );
			$logEntry->setParameters( array( '4:consumer' => $cmr->get( 'consumerKey' ) ) );
			$logEntry->setRelations( array(
				'OAuthConsumer' => array( $cmr->get( 'consumerKey' ) )
			) );
			$logEntry->insert( $dbw );

			return $this->success( $cmr );
		case 'approve':
			if ( !$user->isAllowed( 'mwoauthmanageconsumer' ) ) {
				return $this->failure( 'permission_denied', 'badaccess-group0' );
			}

			$cmr = MWOAuthConsumer::newFromKey( $dbw, $this->vals['consumerKey'] );
			if ( !$cmr ) {
				return $this->failure( 'invalid_consumer_key', 'mwoauth-invalid-consumer-key' );
			} elseif ( !in_array( $cmr->get( 'stage' ), array(
				MWOAuthConsumer::STAGE_PROPOSED,
				MWOAuthConsumer::STAGE_EXPIRED,
				MWOAuthConsumer::STAGE_REJECTED ) ) )
			{
				return $this->failure( 'not_proposed', 'mwoauth-consumer-not-proposed' );
			} elseif ( $cmr->get( 'deleted' ) && !$user->isAllowed( 'mwoauthsuppress' ) ) {
				return $this->failure( 'permission_denied', 'badaccess-group0' );
			}

			$cmr->setFields( array(
				'stage'          => MWOAuthConsumer::STAGE_APPROVED,
				'stageTimestamp' => wfTimestampNow(),
				'deleted'        => 0 ) );
			$cmr->save( $dbw );

			$logEntry = new ManualLogEntry( 'mwoauthconsumer', 'approve' );
			$logEntry->setPerformer( $user );
			$logEntry->setTarget( $this->getLogTitle( $dbw, $cmr->get( 'userId' ) ) );
			$logEntry->setComment( $this->vals['reason'] );
			$logEntry->setParameters( array( '4:consumer' => $cmr->get( 'consumerKey' ) ) );
			$logEntry->setRelations( array(
				'OAuthConsumer' => array( $cmr->get( 'consumerKey' ) )
			) );
			$logEntry->insert( $dbw );

			// @TODO: email/notifications?

			return $this->success( $cmr );
		case 'reject':
			if ( !$user->isAllowed( 'mwoauthmanageconsumer' ) ) {
				return $this->failure( 'permission_denied', 'badaccess-group0' );
			}

			$cmr = MWOAuthConsumer::newFromKey( $dbw, $this->vals['consumerKey'] );
			if ( !$cmr ) {
				return $this->failure( 'invalid_consumer_key', 'mwoauth-invalid-consumer-key' );
			} elseif ( $cmr->get( 'stage' ) !== MWOAuthConsumer::STAGE_PROPOSED ) {
				return $this->failure( 'not_proposed', 'mwoauth-consumer-not-proposed' );
			} elseif ( $cmr->get( 'deleted' ) && !$user->isAllowed( 'mwoauthsuppress' ) ) {
				return $this->failure( 'permission_denied', 'badaccess-group0' );
			}

			$cmr->setFields( array(
				'stage'          => MWOAuthConsumer::STAGE_REJECTED,
				'stageTimestamp' => wfTimestampNow(),
				'deleted'        => $this->vals['suppress'] ) );
			$cmr->save( $dbw );

			$logEntry = new ManualLogEntry( 'mwoauthconsumer', 'reject' );
			$logEntry->setPerformer( $user );
			$logEntry->setTarget( $this->getLogTitle( $dbw, $cmr->get( 'userId' ) ) );
			$logEntry->setComment( $this->vals['reason'] );
			$logEntry->setParameters( array( '4:consumer' => $cmr->get( 'consumerKey' ) ) );
			$logEntry->setRelations( array(
				'OAuthConsumer' => array( $cmr->get( 'consumerKey' ) )
			) );
			$logEntry->insert( $dbw );

			// @TODO: email/notifications?

			return $this->success( $cmr );
		case 'disable':
			if ( !$user->isAllowed( 'mwoauthmanageconsumer' ) ) {
				return $this->failure( 'permission_denied', 'badaccess-group0' );
			} elseif ( $this->vals['suppress'] && !$user->isAllowed( 'mwoauthsuppress' ) ) {
				return $this->failure( 'permission_denied', 'badaccess-group0' );
			}

			$cmr = MWOAuthConsumer::newFromKey( $dbw, $this->vals['consumerKey'] );
			if ( !$cmr ) {
				return $this->failure( 'invalid_consumer_key', 'mwoauth-invalid-consumer-key' );
			} elseif ( $cmr->get( 'stage' ) !== MWOAuthConsumer::STAGE_APPROVED
				&& $cmr->get( 'deleted' ) == $this->vals['suppress'] )
			{
				return $this->failure( 'not_approved', 'mwoauth-consumer-not-approved' );
			} elseif ( $cmr->get( 'deleted' ) && !$user->isAllowed( 'mwoauthsuppress' ) ) {
				return $this->failure( 'permission_denied', 'badaccess-group0' );
			}

			$cmr->setFields( array(
				'stage'          => MWOAuthConsumer::STAGE_DISABLED,
				'stageTimestamp' => wfTimestampNow(),
				'deleted'        => $this->vals['suppress'] ) );
			$cmr->save( $dbw );

			$logEntry = new ManualLogEntry( 'mwoauthconsumer', 'disable' );
			$logEntry->setPerformer( $user );
			$logEntry->setTarget( $this->getLogTitle( $dbw, $cmr->get( 'userId' ) ) );
			$logEntry->setComment( $this->vals['reason'] );
			$logEntry->setParameters( array( '4:consumer' => $cmr->get( 'consumerKey' ) ) );
			$logEntry->setRelations( array(
				'OAuthConsumer' => array( $cmr->get( 'consumerKey' ) )
			) );
			$logEntry->insert( $dbw );

			// @TODO: email/notifications?

			return $this->success( $cmr );
		case 'reenable':
			if ( !$user->isAllowed( 'mwoauthmanageconsumer' ) ) {
				return $this->failure( 'permission_denied', 'badaccess-group0' );
			}

			$cmr = MWOAuthConsumer::newFromKey( $dbw, $this->vals['consumerKey'] );
			if ( !$cmr ) {
				return $this->failure( 'invalid_consumer_key', 'mwoauth-invalid-consumer-key' );
			} elseif ( $cmr->get( 'stage' ) !== MWOAuthConsumer::STAGE_DISABLED ) {
				return $this->failure( 'not_disabled', 'mwoauth-consumer-not-disabled' );
			} elseif ( $cmr->get( 'deleted' ) && !$user->isAllowed( 'mwoauthsuppress' ) ) {
				return $this->failure( 'permission_denied', 'badaccess-group0' );
			}

			$cmr->setFields( array(
				'stage'          => MWOAuthConsumer::STAGE_APPROVED,
				'stageTimestamp' => wfTimestampNow(),
				'deleted'        => 0 ) );
			$cmr->save( $dbw );

			$logEntry = new ManualLogEntry( 'mwoauthconsumer', 'reenable' );
			$logEntry->setPerformer( $user );
			$logEntry->setTarget( $this->getLogTitle( $dbw, $cmr->get( 'userId' ) ) );
			$logEntry->setComment( $this->vals['reason'] );
			$logEntry->setParameters( array( '4:consumer' => $cmr->get( 'consumerKey' ) ) );
			$logEntry->setRelations( array(
				'OAuthConsumer' => array( $cmr->get( 'consumerKey' ) )
			) );
			$logEntry->insert( $dbw );

			// @TODO: email/notifications?

			return $this->success( $cmr );
		}
	}

	/**
	 * @param DatabaseBase $db
	 * @param int $userId
	 * @return Title
	 */
	protected function getLogTitle( DatabaseBase $db, $userId ) {
		$name = $db->selectField( 'user', 'user_name', array( 'user_id' => $userId ) );
		return Title::makeTitleSafe( NS_USER, $name );
	}
}
