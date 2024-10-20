<?php

namespace MediaWiki\Extension\OAuth\Control;

use Composer\Semver\VersionParser;
use Exception;
use LogicException;
use ManualLogEntry;
use MediaWiki\Api\ApiMessage;
use MediaWiki\Context\IContextSource;
use MediaWiki\Extension\Notifications\Model\Event;
use MediaWiki\Extension\OAuth\Backend\Consumer;
use MediaWiki\Extension\OAuth\Backend\ConsumerAcceptance;
use MediaWiki\Extension\OAuth\Backend\MWOAuthDataStore;
use MediaWiki\Extension\OAuth\Backend\Utils;
use MediaWiki\Extension\OAuth\Entity\ClientEntity;
use MediaWiki\Extension\OAuth\OAuthServices;
use MediaWiki\Json\FormatJson;
use MediaWiki\Logger\LoggerFactory;
use MediaWiki\MediaWikiServices;
use MediaWiki\Parser\Sanitizer;
use MediaWiki\Registration\ExtensionRegistry;
use MediaWiki\SpecialPage\SpecialPage;
use MediaWiki\Title\Title;
use MediaWiki\User\User;
use MediaWiki\WikiMap\WikiMap;
use MWCryptRand;
use MWException;
use StatusValue;
use UnexpectedValueException;
use Wikimedia\Rdbms\IDatabase;
use Wikimedia\Rdbms\SelectQueryBuilder;

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

/**
 * This handles the core logic of approving/disabling consumers
 * from using particular user accounts
 *
 * This control can only be used on the management wiki
 *
 * @TODO: improve error messages
 */
class ConsumerSubmitControl extends SubmitControl {
	/**
	 * Names of the actions that can be performed on a consumer. These are the same as the
	 * options in getRequiredFields().
	 * @var string[]
	 */
	public static $actions = [ 'propose', 'update', 'approve', 'reject', 'disable', 'reenable' ];

	/** @var IDatabase */
	protected $dbw;

	/**
	 * MySQL Blob Size is 2^16 - 1 = 65535 as per "L + 2 bytes, where L < 216" on
	 * https://dev.mysql.com/doc/refman/8.0/en/storage-requirements.html
	 */
	private const BLOB_SIZE = 65535;

	/**
	 * @param IContextSource $context
	 * @param array $params
	 * @param IDatabase $dbw Result of Utils::getCentralDB( DB_PRIMARY )
	 */
	public function __construct( IContextSource $context, array $params, IDatabase $dbw ) {
		parent::__construct( $context, $params );
		$this->dbw = $dbw;
	}

	protected function getRequiredFields() {
		$validateRsaKey = static function ( $s ) {
			if ( trim( $s ) === '' ) {
				return true;
			}
			if ( strlen( $s ) > self::BLOB_SIZE ) {
				return false;
			}
			$key = openssl_pkey_get_public( $s );
			if ( $key === false ) {
				return false;
			}
			$info = openssl_pkey_get_details( $key );

			return ( $info['type'] === OPENSSL_KEYTYPE_RSA );
		};

		$suppress = [ 'suppress' => '/^[01]$/' ];
		$base = [
			'consumerKey'  => '/^[0-9a-f]{32}$/',
			'reason'       => '/^.{0,255}$/',
			'changeToken'  => '/^[0-9a-f]{40}$/'
		];

		$validateBlobSize = static function ( $s ) {
			return strlen( $s ?? '' ) < self::BLOB_SIZE;
		};

		return [
			// Proposer (application administrator) actions:
			'propose' => [
				'name' => '/^.{1,128}$/',
				'version' => static function ( $s ) {
					if ( strlen( $s ) > 32 ) {
						return false;
					}
					$parser = new VersionParser();
					try {
						$parser->normalize( $s );
						return true;
					} catch ( UnexpectedValueException $e ) {
						return false;
					}
				},
				'oauthVersion' => static function ( $i ) {
					return in_array( $i, [ Consumer::OAUTH_VERSION_1, Consumer::OAUTH_VERSION_2 ] );
				},
				'callbackUrl' => static function ( $s, $vals ) {
					$isOAuth1 = (int)$vals['oauthVersion'] === Consumer::OAUTH_VERSION_1;
					$isOAuth2 = !$isOAuth1;

					if ( strlen( $s ?? '' ) > 2000 ) {
						return false;
					} elseif ( $vals['ownerOnly'] ) {
						return true;
					}

					$urlParts = wfParseUrl( $s );
					if ( !$urlParts ) {
						return false;
					} elseif ( $isOAuth2 && !self::isSecureContext( $urlParts ) ) {
						return StatusValue::newFatal(
							new ApiMessage( 'mwoauth-error-callback-url-must-be-https', 'invalid_callback_url' )
						);
					} elseif ( ( $isOAuth1 || $vals['oauth2IsConfidential'] )
						&& WikiMap::getWikiFromUrl( $s )
					) {
						return StatusValue::newGood()->warning(
							new ApiMessage( 'mwoauth-error-callback-server-url', 'invalid_callback_url' )
						);
					} elseif ( ( $isOAuth2 || !$vals['callbackIsPrefix'] )
						&& in_array( $urlParts['path'] ?? '', [ '', '/' ], true )
						&& !( $urlParts['query'] ?? false )
						&& !( $urlParts['fragment'] ?? false )
					) {
						$message = $isOAuth1
							? 'mwoauth-error-callback-bare-domain-oauth1'
							: 'mwoauth-error-callback-bare-domain-oauth2';
						return StatusValue::newGood()->warning(
							new ApiMessage( $message, 'invalid_callback_url' )
						);
					}
					return true;
				},
				'description' => $validateBlobSize,
				'email' => static function ( $s ) {
					return Sanitizer::validateEmail( $s );
				},
				'wiki' => static function ( $s ) {
					global $wgConf;
					return ( $s === '*'
						|| in_array( $s, $wgConf->getLocalDatabases() )
						|| in_array( $s, Utils::getAllWikiNames() )
					);
				},
				'oauth2GrantTypes' => static function ( $a, $vals ) {
					if ( $vals['oauthVersion'] == Consumer::OAUTH_VERSION_1 ) {
						return true;
					}

					// OAuth 2 apps must have at least one grant type
					return count( $a ) > 0 && strlen( FormatJson::encode( $a ) ) <= self::BLOB_SIZE;
				},
				'granttype' => '/^(authonly|authonlyprivate|normal)$/',
				'grants' => static function ( $s ) {
					if ( strlen( $s ) > self::BLOB_SIZE ) {
						return false;
					}
					$grants = FormatJson::decode( $s, true );
					return is_array( $grants ) && Utils::grantsAreValid( $grants );
				},
				'restrictions' => $validateBlobSize,
				'rsaKey' => $validateRsaKey,
				'agreement' => static function ( $s ) {
					return ( $s == true );
				},
			],
			'update' => array_merge( $base, [
				'restrictions' => $validateBlobSize,
				'rsaKey' => $validateRsaKey,
				'resetSecret' => static function ( $s ) {
					return is_bool( $s );
				},
			] ),
			// Approver (project administrator) actions:
			'approve'     => $base,
			'reject'      => array_merge( $base, $suppress ),
			'disable'     => array_merge( $base, $suppress ),
			'reenable'    => $base
		];
	}

	protected function checkBasePermissions() {
		global $wgBlockDisablesLogin;
		$user = $this->getUser();
		$readOnlyMode = MediaWikiServices::getInstance()->getReadOnlyMode();
		if ( !$user->getId() ) {
			return $this->failure( 'not_logged_in', 'badaccess-group0' );
		} elseif ( $user->isLocked() || ( $wgBlockDisablesLogin && $user->getBlock() ) ) {
			return $this->failure( 'user_blocked', 'badaccess-group0' );
		} elseif ( $readOnlyMode->isReadOnly() ) {
			return $this->failure( 'readonly', 'readonlytext', $readOnlyMode->getReason() );
		} elseif ( !Utils::isCentralWiki() ) {
			// This logs consumer changes to the local logging table on the central wiki
			throw new LogicException( "This can only be used from the OAuth management wiki." );
		}
		return $this->success();
	}

	protected function processAction( $action ) {
		$context = $this->getContext();
		// proposer or admin
		$user = $this->getUser();
		$dbw = $this->dbw;

		$centralUserId = Utils::getCentralIdFromLocalUser( $user );
		if ( !$centralUserId ) {
			return $this->failure( 'permission_denied', 'badaccess-group0' );
		}

		$permissionManager = MediaWikiServices::getInstance()->getPermissionManager();

		switch ( $action ) {
			case 'propose':
				if ( !$permissionManager->userHasRight( $user, 'mwoauthproposeconsumer' ) ) {
					return $this->failure( 'permission_denied', 'badaccess-group0' );
				} elseif ( !$user->isEmailConfirmed() ) {
					return $this->failure( 'email_not_confirmed', 'mwoauth-consumer-email-unconfirmed' );
				} elseif ( $user->getEmail() !== $this->vals['email'] ) {
					// @TODO: allow any email and don't set emailAuthenticated below
					return $this->failure( 'email_mismatched', 'mwoauth-consumer-email-mismatched' );
				}

				if ( Consumer::newFromNameVersionUser(
					$dbw, $this->vals['name'], $this->vals['version'], $centralUserId
				) ) {
					return $this->failure( 'consumer_exists', 'mwoauth-consumer-alreadyexists' );
				}

				$wikiNames = Utils::getAllWikiNames();
				$dbKey = array_search( $this->vals['wiki'], $wikiNames );
				if ( $dbKey !== false ) {
					$this->vals['wiki'] = $dbKey;
				}

				$curVer = $dbw->newSelectQueryBuilder()
					->select( 'oarc_version' )
					->from( 'oauth_registered_consumer' )
					->where( [ 'oarc_name' => $this->vals['name'], 'oarc_user_id' => $centralUserId ] )
					->orderBy( 'oarc_registration', SelectQueryBuilder::SORT_DESC )
					->forUpdate()
					->caller( __METHOD__ )
					->fetchField();
				if ( $curVer !== false && version_compare( $curVer, $this->vals['version'], '>=' ) ) {
					return $this->failure( 'consumer_exists',
						'mwoauth-consumer-alreadyexistsversion', $curVer );
				}

				// Handle owner-only mode
				if ( $this->vals['ownerOnly'] ) {
					$this->vals['callbackUrl'] = SpecialPage::getTitleFor( 'OAuth', 'verified' )
						->getLocalURL();
					$this->vals['callbackIsPrefix'] = '';
					$stage = Consumer::STAGE_APPROVED;
				} else {
					$stage = Consumer::STAGE_PROPOSED;
				}

				// Handle grant types
				$grants = [];
				switch ( $this->vals['granttype'] ) {
					case 'authonly':
						$grants = [ 'mwoauth-authonly' ];
						break;
					case 'authonlyprivate':
						$grants = [ 'mwoauth-authonlyprivate' ];
						break;
					case 'normal':
						$grants = array_unique( array_merge(
							// implied grants
							MediaWikiServices::getInstance()
								->getGrantsInfo()
								->getHiddenGrants(),
							FormatJson::decode( $this->vals['grants'], true )
						) );
						break;
				}

				$now = wfTimestampNow();
				$cmr = Consumer::newFromArray(
					[
						'id'                 => null,
						'consumerKey'        => MWCryptRand::generateHex( 32 ),
						'userId'             => $centralUserId,
						'email'              => $user->getEmail(),
						'emailAuthenticated' => $now,
						'developerAgreement' => 1,
						'secretKey'          => MWCryptRand::generateHex( 32 ),
						'registration'       => $now,
						'stage'              => $stage,
						'stageTimestamp'     => $now,
						'grants'             => $grants,
						'restrictions'       => $this->vals['restrictions'],
						'deleted'            => 0
					] + $this->vals
				);

				$logAction = 'propose';
				$oauthServices = OAuthServices::wrap( MediaWikiServices::getInstance() );
				$workflow = $oauthServices->getWorkflow();
				$autoApproved = $workflow->consumerCanBeAutoApproved( $cmr );
				if ( $cmr->getOwnerOnly() ) {
					// FIXME the stage is set a few dozen lines earlier - should simplify this
					$logAction = 'create-owner-only';
				} elseif ( $autoApproved ) {
					$cmr->setField( 'stage', Consumer::STAGE_APPROVED );
					$logAction = 'propose-autoapproved';
				}

				$cmr->save( $dbw );
				$this->makeLogEntry( $dbw, $cmr, $logAction, $user, $this->vals['description'] );
				if ( !$cmr->getOwnerOnly() && !$autoApproved ) {
					// Notify admins if the consumer needs to be approved.
					if ( $cmr->getStage() === Consumer::STAGE_PROPOSED ) {
						$this->notify( $cmr, $user, $action, '' );
					}
				}

				// If it's owner-only, automatically accept it for the user too.
				$accessToken = null;
				if ( $cmr->getOwnerOnly() ) {
					$accessToken = MWOAuthDataStore::newToken();
					$cmra = ConsumerAcceptance::newFromArray( [
						'id'           => null,
						'wiki'         => $cmr->getWiki(),
						'userId'       => $centralUserId,
						'consumerId'   => $cmr->getId(),
						'accessToken'  => $accessToken->key,
						'accessSecret' => $accessToken->secret,
						'grants'       => $cmr->getGrants(),
						'accepted'     => $now,
						'oauth_version' => $cmr->getOAuthVersion()
					] );
					$cmra->save( $dbw );
					if ( $cmr instanceof ClientEntity ) {
						// OAuth2 client
						try {
							$accessToken = $cmr->getOwnerOnlyAccessToken( $cmra );
						} catch ( Exception $ex ) {
							return $this->failure(
								'unable_to_retrieve_access_token',
								'mwoauth-oauth2-unable-to-retrieve-access-token',
								$ex->getMessage()
							);
						}
					}
				}

				return $this->success( [ 'consumer' => $cmr, 'accessToken' => $accessToken ] );
			case 'update':
				if ( !$permissionManager->userHasRight( $user, 'mwoauthupdateownconsumer' ) ) {
					return $this->failure( 'permission_denied', 'badaccess-group0' );
				}

				$cmr = Consumer::newFromKey( $dbw, $this->vals['consumerKey'] );
				if ( !$cmr ) {
					return $this->failure( 'invalid_consumer_key', 'mwoauth-invalid-consumer-key' );
				} elseif ( $cmr->getUserId() !== $centralUserId ) {
					return $this->failure( 'permission_denied', 'badaccess-group0' );
				} elseif (
					$cmr->getStage() !== Consumer::STAGE_APPROVED
					&& $cmr->getStage() !== Consumer::STAGE_PROPOSED
				) {
					return $this->failure( 'permission_denied', 'badaccess-group0' );
				} elseif ( $cmr->getDeleted()
					&& !$permissionManager->userHasRight( $user, 'mwoauthsuppress' ) ) {
					return $this->failure( 'permission_denied', 'badaccess-group0' );
				} elseif ( !$cmr->checkChangeToken( $context, $this->vals['changeToken'] ) ) {
					return $this->failure( 'change_conflict', 'mwoauth-consumer-conflict' );
				}

				$cmr->setFields( [
					'rsaKey'       => $this->vals['rsaKey'],
					'restrictions' => $this->vals['restrictions'],
					'secretKey'    => $this->vals['resetSecret']
						? MWCryptRand::generateHex( 32 )
						: $cmr->getSecretKey(),
				] );

				// Log if something actually changed
				if ( $cmr->save( $dbw ) ) {
					$this->makeLogEntry( $dbw, $cmr, $action, $user, $this->vals['reason'] );
					$this->notify( $cmr, $user, $action, $this->vals['reason'] );
				}

				$accessToken = null;
				if ( $cmr->getOwnerOnly() && $this->vals['resetSecret'] ) {
					$cmra = $cmr->getCurrentAuthorization( $user, WikiMap::getCurrentWikiId() );
					$accessToken = MWOAuthDataStore::newToken();
					$fields = [
						'wiki'         => $cmr->getWiki(),
						'userId'       => $centralUserId,
						'consumerId'   => $cmr->getId(),
						'accessSecret' => $accessToken->secret,
						'grants'       => $cmr->getGrants(),
					];

					if ( $cmra ) {
						$accessToken->key = $cmra->getAccessToken();
						$cmra->setFields( $fields );
					} else {
						$cmra = ConsumerAcceptance::newFromArray( $fields + [
							'id'           => null,
							'accessToken'  => $accessToken->key,
							'accepted'     => wfTimestampNow(),
						] );
					}
					$cmra->save( $dbw );
					if ( $cmr instanceof ClientEntity ) {
						$accessToken = $cmr->getOwnerOnlyAccessToken( $cmra, true );
					}
				}

				return $this->success( [ 'consumer' => $cmr, 'accessToken' => $accessToken ] );
			case 'approve':
				if ( !$permissionManager->userHasRight( $user, 'mwoauthmanageconsumer' ) ) {
					return $this->failure( 'permission_denied', 'badaccess-group0' );
				}

				$cmr = Consumer::newFromKey( $dbw, $this->vals['consumerKey'] );
				if ( !$cmr ) {
					return $this->failure( 'invalid_consumer_key', 'mwoauth-invalid-consumer-key' );
				} elseif ( !in_array( $cmr->getStage(), [
					Consumer::STAGE_PROPOSED,
					Consumer::STAGE_EXPIRED,
					Consumer::STAGE_REJECTED,
				] ) ) {
					return $this->failure( 'not_proposed', 'mwoauth-consumer-not-proposed' );
				} elseif ( $cmr->getDeleted() && !$permissionManager->userHasRight( $user, 'mwoauthsuppress' ) ) {
					return $this->failure( 'permission_denied', 'badaccess-group0' );
				} elseif ( !$cmr->checkChangeToken( $context, $this->vals['changeToken'] ) ) {
					return $this->failure( 'change_conflict', 'mwoauth-consumer-conflict' );
				}

				$cmr->setFields( [
					'stage'          => Consumer::STAGE_APPROVED,
					'stageTimestamp' => wfTimestampNow(),
					'deleted'        => 0 ] );

				// Log if something actually changed
				if ( $cmr->save( $dbw ) ) {
					$this->makeLogEntry( $dbw, $cmr, $action, $user, $this->vals['reason'] );
					$this->notify( $cmr, $user, $action, $this->vals['reason'] );
				}

				return $this->success( $cmr );
			case 'reject':
				if ( !$permissionManager->userHasRight( $user, 'mwoauthmanageconsumer' ) ) {
					return $this->failure( 'permission_denied', 'badaccess-group0' );
				}

				$cmr = Consumer::newFromKey( $dbw, $this->vals['consumerKey'] );
				if ( !$cmr ) {
					return $this->failure( 'invalid_consumer_key', 'mwoauth-invalid-consumer-key' );
				} elseif ( $cmr->getStage() !== Consumer::STAGE_PROPOSED ) {
					return $this->failure( 'not_proposed', 'mwoauth-consumer-not-proposed' );
				} elseif ( $cmr->getDeleted() && !$permissionManager->userHasRight( $user, 'mwoauthsuppress' ) ) {
					return $this->failure( 'permission_denied', 'badaccess-group0' );
				} elseif ( $this->vals['suppress'] && !$permissionManager->userHasRight( $user, 'mwoauthsuppress' ) ) {
					return $this->failure( 'permission_denied', 'badaccess-group0' );
				} elseif ( !$cmr->checkChangeToken( $context, $this->vals['changeToken'] ) ) {
					return $this->failure( 'change_conflict', 'mwoauth-consumer-conflict' );
				}

				$cmr->setFields( [
					'stage'          => Consumer::STAGE_REJECTED,
					'stageTimestamp' => wfTimestampNow(),
					'deleted'        => $this->vals['suppress'] ] );

				// Log if something actually changed
				if ( $cmr->save( $dbw ) ) {
					$this->makeLogEntry( $dbw, $cmr, $action, $user, $this->vals['reason'] );
					$this->notify( $cmr, $user, $action, $this->vals['reason'] );
				}

				return $this->success( $cmr );
			case 'disable':
				if ( !$permissionManager->userHasRight( $user, 'mwoauthmanageconsumer' ) ) {
					return $this->failure( 'permission_denied', 'badaccess-group0' );
				} elseif ( $this->vals['suppress'] && !$permissionManager->userHasRight( $user, 'mwoauthsuppress' ) ) {
					return $this->failure( 'permission_denied', 'badaccess-group0' );
				}

				$cmr = Consumer::newFromKey( $dbw, $this->vals['consumerKey'] );
				if ( !$cmr ) {
					return $this->failure( 'invalid_consumer_key', 'mwoauth-invalid-consumer-key' );
				} elseif ( $cmr->getStage() !== Consumer::STAGE_APPROVED
				&& $cmr->getDeleted() == $this->vals['suppress']
				) {
					return $this->failure( 'not_approved', 'mwoauth-consumer-not-approved' );
				} elseif ( $cmr->getDeleted() && !$permissionManager->userHasRight( $user, 'mwoauthsuppress' ) ) {
					return $this->failure( 'permission_denied', 'badaccess-group0' );
				} elseif ( !$cmr->checkChangeToken( $context, $this->vals['changeToken'] ) ) {
					return $this->failure( 'change_conflict', 'mwoauth-consumer-conflict' );
				}

				$cmr->setFields( [
					'stage'          => Consumer::STAGE_DISABLED,
					'stageTimestamp' => wfTimestampNow(),
					'deleted'        => $this->vals['suppress'] ] );

				// Log if something actually changed
				if ( $cmr->save( $dbw ) ) {
					$this->makeLogEntry( $dbw, $cmr, $action, $user, $this->vals['reason'] );
					$this->notify( $cmr, $user, $action, $this->vals['reason'] );
				}

				return $this->success( $cmr );
			case 'reenable':
				if ( !$permissionManager->userHasRight( $user, 'mwoauthmanageconsumer' ) ) {
					return $this->failure( 'permission_denied', 'badaccess-group0' );
				}

				$cmr = Consumer::newFromKey( $dbw, $this->vals['consumerKey'] );
				if ( !$cmr ) {
					return $this->failure( 'invalid_consumer_key', 'mwoauth-invalid-consumer-key' );
				} elseif ( $cmr->getStage() !== Consumer::STAGE_DISABLED ) {
					return $this->failure( 'not_disabled', 'mwoauth-consumer-not-disabled' );
				} elseif ( $cmr->getDeleted() && !$permissionManager->userHasRight( $user, 'mwoauthsuppress' ) ) {
					return $this->failure( 'permission_denied', 'badaccess-group0' );
				} elseif ( !$cmr->checkChangeToken( $context, $this->vals['changeToken'] ) ) {
					return $this->failure( 'change_conflict', 'mwoauth-consumer-conflict' );
				}

				$cmr->setFields( [
					'stage'          => Consumer::STAGE_APPROVED,
					'stageTimestamp' => wfTimestampNow(),
					'deleted'        => 0 ] );

				// Log if something actually changed
				if ( $cmr->save( $dbw ) ) {
					$this->makeLogEntry( $dbw, $cmr, $action, $user, $this->vals['reason'] );
					$this->notify( $cmr, $user, $action, $this->vals['reason'] );
				}

				return $this->success( $cmr );
		}
	}

	/**
	 * @param IDatabase $db
	 * @param int $userId
	 * @return Title
	 */
	protected function getLogTitle( IDatabase $db, $userId ) {
		$name = Utils::getCentralUserNameFromId( $userId );
		return Title::makeTitleSafe( NS_USER, $name );
	}

	/**
	 * @param IDatabase $dbw
	 * @param Consumer $cmr
	 * @param string $action
	 * @param User $performer
	 * @param string $comment
	 */
	protected function makeLogEntry(
		$dbw, Consumer $cmr, $action, User $performer, $comment
	) {
		$logEntry = new ManualLogEntry( 'mwoauthconsumer', $action );
		$logEntry->setPerformer( $performer );
		$target = $this->getLogTitle( $dbw, $cmr->getUserId() );
		$logEntry->setTarget( $target );
		$logEntry->setComment( $comment );
		$logEntry->setParameters( [ '4:consumer' => $cmr->getConsumerKey() ] );
		$logEntry->setRelations( [
			'OAuthConsumer' => [ $cmr->getConsumerKey() ]
		] );
		$logEntry->insert( $dbw );

		LoggerFactory::getInstance( 'OAuth' )->info(
			'{user} performed action {action} on consumer {consumer}', [
				'action' => $action,
				'user' => $performer->getName(),
				'consumer' => $cmr->getConsumerKey(),
				'target' => $target->getText(),
				'comment' => $comment,
				'clientip' => $this->getContext()->getRequest()->getIP(),
			]
		);
	}

	/**
	 * @param Consumer $cmr Consumer which was the subject of the action
	 * @param User $user User who performed the action
	 * @param string $actionType
	 * @param string $comment
	 */
	protected function notify( $cmr, $user, $actionType, $comment ) {
		if ( !in_array( $actionType, self::$actions, true ) ) {
			throw new MWException( "Invalid action type: $actionType" );
		} elseif ( !ExtensionRegistry::getInstance()->isLoaded( 'Echo' ) ) {
			return;
		} elseif ( !Utils::isCentralWiki() ) {
			# sanity; should never get here on a replica wiki
			return;
		}

		Event::create( [
			'type' => 'oauth-app-' . $actionType,
			'agent' => $user,
			'extra' => [
				'action' => $actionType,
				'app-key' => $cmr->getConsumerKey(),
				'owner-id' => $cmr->getUserId(),
				'comment' => $comment,
			],
		] );
	}

	/**
	 * Decide whether the given (parsed) URL corresponds to a secure context.
	 * (This is only an approximation of the algorithm browsers use,
	 * since some considerations such as frames don't apply here.)
	 *
	 * @see https://developer.mozilla.org/en-US/docs/Web/Security/Secure_Contexts
	 *
	 * @param array $urlParts As returned by {@link wfParseUrl()}.
	 * @return bool
	 */
	protected static function isSecureContext( array $urlParts ): bool {
		if ( $urlParts['scheme'] === 'https' ) {
			return true;
		}

		$host = $urlParts['host'];
		if ( $host === 'localhost'
			|| $host === '127.0.0.1'
			|| $host === '[::1]'
			|| str_ends_with( $host, '.localhost' )
			// The wmftest.{com,net,org} domains hosted by the Wikimedia
			// Foundation include a '*.local IN A 127.0.0.1' that is used in
			// some local development environments.
			|| str_ends_with( $host, '.local.wmftest.com' )
			|| str_ends_with( $host, '.local.wmftest.net' )
			|| str_ends_with( $host, '.local.wmftest.org' )
		) {
			return true;
		}

		return false;
	}
}
