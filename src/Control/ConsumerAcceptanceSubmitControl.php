<?php

/**
 * (c) Aaron Schulz 2013, GPL
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License along
 * with this program; if not, write to the Free Software Foundation, Inc.,
 * 59 Temple Place - Suite 330, Boston, MA 02111-1307, USA.
 * http://www.gnu.org/copyleft/gpl.html
 */

namespace MediaWiki\Extension\OAuth\Control;

use MediaWiki\Context\IContextSource;
use MediaWiki\Extension\OAuth\Backend\Consumer;
use MediaWiki\Extension\OAuth\Backend\ConsumerAcceptance;
use MediaWiki\Extension\OAuth\Backend\MWOAuthException;
use MediaWiki\Extension\OAuth\Backend\Utils;
use MediaWiki\Extension\OAuth\Lib\OAuthException;
use MediaWiki\Extension\OAuth\Repository\AccessTokenRepository;
use MediaWiki\Json\FormatJson;
use MediaWiki\Logger\LoggerFactory;
use MediaWiki\MediaWikiServices;
use Wikimedia\Rdbms\IDatabase;

/**
 * This handles the core logic of submitting/approving application
 * consumer requests and the logic of managing approved consumers
 *
 * This control can be used on any wiki, not just the management one
 *
 * @TODO: improve error messages
 */
class ConsumerAcceptanceSubmitControl extends SubmitControl {
	/** @var IDatabase */
	protected $dbw;

	/** @var int */
	protected $oauthVersion;

	/**
	 * @param IContextSource $context
	 * @param array $params
	 * @param IDatabase $dbw Result of Utils::getCentralDB( DB_PRIMARY )
	 * @param int $oauthVersion
	 */
	public function __construct(
		IContextSource $context, array $params, IDatabase $dbw, $oauthVersion
	) {
		parent::__construct( $context, $params );
		$this->dbw = $dbw;
		$this->oauthVersion = (int)$oauthVersion;
	}

	protected function getRequiredFields() {
		$required = [
			'update'   => [
				'acceptanceId' => '/^\d+$/',
				'grants'      => static function ( $s ) {
					$grants = FormatJson::decode( $s, true );
					return is_array( $grants ) && Utils::grantsAreValid( $grants );
				}
			],
			'renounce' => [
				'acceptanceId' => '/^\d+$/',
			],
		];
		if ( $this->isOAuth2() ) {
			$required['accept'] = [
				'client_id' => '/^[0-9a-f]{32}$/',
				'confirmUpdate' => '/^[01]$/',
			];
		} else {
			$required['accept'] = [
				'consumerKey'   => '/^[0-9a-f]{32}$/',
				'requestToken'  => '/^[0-9a-f]{32}$/',
				'confirmUpdate' => '/^[01]$/',
			];
		}

		return $required;
	}

	protected function checkBasePermissions() {
		$user = $this->getUser();
		$services = MediaWikiServices::getInstance();
		$permissionManager = $services->getPermissionManager();
		$readOnlyMode = $services->getReadOnlyMode();

		if ( !$user->getID() ) {
			return $this->failure( 'not_logged_in', 'badaccess-group0' );
		} elseif ( !$permissionManager->userHasRight( $user, 'mwoauthmanagemygrants' ) ) {
			return $this->failure( 'permission_denied', 'badaccess-group0' );
		} elseif ( $readOnlyMode->isReadOnly() ) {
			return $this->failure( 'readonly', 'readonlytext', $readOnlyMode->getReason() );
		}
		return $this->success();
	}

	protected function processAction( $action ) {
		// proposer or admin
		$user = $this->getUser();
		$dbw = $this->dbw;

		$centralUserId = Utils::getCentralIdFromLocalUser( $user );
		if ( !$centralUserId ) {
			return $this->failure( 'permission_denied', 'badaccess-group0' );
		}

		switch ( $action ) {
			case 'accept':
				$payload = [];
				$identifier = $this->isOAuth2() ? 'client_id' : 'consumerKey';
				$cmr = Consumer::newFromKey( $this->dbw, $this->vals[$identifier] );
				if ( !$cmr ) {
					return $this->failure( 'invalid_consumer_key', 'mwoauth-invalid-consumer-key' );
				} elseif ( !$cmr->isUsableBy( $user ) ) {
					return $this->failure( 'permission_denied', 'badaccess-group0' );
				}

				try {
					if ( $this->isOAuth2() ) {
						$scopes = isset( $this->vals['scope'] ) ? explode( ' ', $this->vals['scope'] ) : [];
						$payload = $cmr->authorize( $this->getUser(), (bool)$this->vals['confirmUpdate'], $scopes );
					} else {
						$callback = $cmr->authorize(
							$this->getUser(),
							(bool)$this->vals[ 'confirmUpdate' ],
							$cmr->getGrants(),
							$this->vals[ 'requestToken' ]
						);
						$payload = [ 'callbackUrl' => $callback ];
					}
				} catch ( MWOAuthException $exception ) {
					return $this->failure( 'oauth_exception', $exception->getMessageObject() );
				} catch ( OAuthException $exception ) {
					return $this->failure( 'oauth_exception',
						'mwoauth-oauth-exception', $exception->getMessage() );
				}

				LoggerFactory::getInstance( 'OAuth' )->info(
					'{user} performed action {action} on consumer {consumer}', [
						'action' => 'accept',
						'user' => $user->getName(),
						'consumer' => $cmr->getConsumerKey(),
						'target' => Utils::getCentralUserNameFromId( $cmr->getUserId(), 'raw' ),
						'comment' => '',
						'clientip' => $this->getContext()->getRequest()->getIP(),
					]
				);

				return $this->success( $payload );
			case 'update':
				$cmra = ConsumerAcceptance::newFromId( $dbw, $this->vals['acceptanceId'] );
				if ( !$cmra ) {
					return $this->failure( 'invalid_access_token', 'mwoauth-invalid-access-token' );
				} elseif ( $cmra->getUserId() !== $centralUserId ) {
					return $this->failure( 'invalid_access_token', 'mwoauth-invalid-access-token' );
				}
				$cmr = Consumer::newFromId( $dbw, $cmra->getConsumerId() );

				// requested grants
				$grants = FormatJson::decode( $this->vals['grants'], true );
				$grants = array_unique( array_intersect(
					array_merge(
						// implied grants
						MediaWikiServices::getInstance()
							->getGrantsInfo()
							->getHiddenGrants(),
						$grants
					),
					// Only keep the applicable ones
					$cmr->getGrants()
				) );

				LoggerFactory::getInstance( 'OAuth' )->info(
					'{user} performed action {action} on consumer {consumer}', [
						'action' => 'update-acceptance',
						'user' => $user->getName(),
						'consumer' => $cmr->getConsumerKey(),
						'target' => Utils::getCentralUserNameFromId( $cmr->getUserId(), 'raw' ),
						'comment' => '',
						'clientip' => $this->getContext()->getRequest()->getIP(),
					]
				);
				$cmra->setFields( [
					'grants' => array_intersect( $grants, $cmr->getGrants() )
				] );
				$cmra->save( $dbw );

				return $this->success( $cmra );
			case 'renounce':
				$cmra = ConsumerAcceptance::newFromId( $dbw, $this->vals['acceptanceId'] );
				if ( !$cmra ) {
					return $this->failure( 'invalid_access_token', 'mwoauth-invalid-access-token' );
				} elseif ( $cmra->getUserId() !== $centralUserId ) {
					return $this->failure( 'invalid_access_token', 'mwoauth-invalid-access-token' );
				}

				$cmr = Consumer::newFromId( $dbw, $cmra->get( 'consumerId' ) );
				LoggerFactory::getInstance( 'OAuth' )->info(
					'{user} performed action {action} on consumer {consumer}', [
						'action' => 'renounce',
						'user' => $user->getName(),
						'consumer' => $cmr->getConsumerKey(),
						'target' => Utils::getCentralUserNameFromId( $cmr->getUserId(), 'raw' ),
						'comment' => '',
						'clientip' => $this->getContext()->getRequest()->getIP(),
					]
				);

				if ( $cmr->getOAuthVersion() === Consumer::OAUTH_VERSION_2 ) {
					$this->removeOAuth2AccessTokens( $cmra->getId() );
				}
				$cmra->delete( $dbw );

				return $this->success( $cmra );
		}
	}

	/**
	 * Convenience function
	 *
	 * @return bool
	 */
	private function isOAuth2() {
		return $this->oauthVersion === Consumer::OAUTH_VERSION_2;
	}

	/**
	 * @param int $approvalId
	 */
	private function removeOAuth2AccessTokens( $approvalId ) {
		$accessTokenRepository = new AccessTokenRepository();
		$accessTokenRepository->deleteForApprovalId( $approvalId );
	}
}
