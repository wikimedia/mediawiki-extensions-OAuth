<?php

namespace MediaWiki\Extension\OAuth\Control;

use Exception;
use LogicException;
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
use MediaWiki\Logging\ManualLogEntry;
use MediaWiki\MediaWikiServices;
use MediaWiki\Registration\ExtensionRegistry;
use MediaWiki\SpecialPage\SpecialPage;
use MediaWiki\Status\Status;
use MediaWiki\Title\Title;
use MediaWiki\User\User;
use MediaWiki\Utils\MWCryptRand;
use MediaWiki\WikiMap\WikiMap;
use Wikimedia\Rdbms\IDatabase;
use Wikimedia\Rdbms\IDBAccessObject;
use Wikimedia\Rdbms\SelectQueryBuilder;

/**
 * (c) Aaron Schulz 2013, GPL
 *
 * @license GPL-2.0-or-later
 */

/**
 * This handles the core logic of approving/disabling consumers
 * from using particular user accounts
 *
 * This control can only be used on the management wiki
 *
 * @todo improve error messages
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
	 * @param IContextSource $context
	 * @param array $params
	 * @param IDatabase $dbw Result of Utils::getOAuthDB( DB_PRIMARY )
	 */
	public function __construct( IContextSource $context, array $params, IDatabase $dbw ) {
		parent::__construct( $context, $params );
		$this->dbw = $dbw;
	}

	/** @inheritDoc */
	protected function getRequiredFields() {
		$expectedConsumerFields = [
			// list of Consumer properties which appear as fields on the proposal form
			Consumer::FIELD_NAME,
			Consumer::FIELD_VERSION,
			Consumer::FIELD_OAUTH_VERSION,
			Consumer::FIELD_CALLBACK_URL,
			Consumer::FIELD_DESCRIPTION,
			Consumer::FIELD_EMAIL,
			Consumer::FIELD_WIKI,
			Consumer::FIELD_OAUTH2_GRANT_TYPES,
			Consumer::FIELD_GRANTS,
			Consumer::FIELD_RESTRICTIONS,
			Consumer::FIELD_RSA_KEY,
			// FIXME DEVELOPER_AGREEMENT is omitted because the form uses a different field name
		];
		$validator = new ConsumerValidator();
		$validatorCallbacks = $validator->getValidatorCallbacks();
		$validateRsaKey = $validatorCallbacks[Consumer::FIELD_RSA_KEY];
		$validateRestrictions = $validatorCallbacks[Consumer::FIELD_RESTRICTIONS];
		$validateDeveloperAgreement = $validatorCallbacks[Consumer::FIELD_DEVELOPER_AGREEMENT];
		$validatorCallbacks = array_intersect_key( $validatorCallbacks,
			array_fill_keys( $expectedConsumerFields, true ) );

		$suppress = [ 'suppress' => '/^[01]$/' ];
		$base = [
			'consumerKey'  => '/^[0-9a-f]{32}$/',
			'reason'       => '/^.{0,255}$/',
			'changeToken'  => '/^[0-9a-f]{40}$/',
		];

		return [
			// Proposer (application owner) actions:
			'propose' => $validatorCallbacks + [
				'granttype' => '/^(authonly|authonlyprivate|normal)$/',
				'agreement' => $validateDeveloperAgreement,
			],
			'update' => array_merge( $base, [
				'restrictions' => $validateRestrictions,
				'rsaKey' => $validateRsaKey,
				'resetSecret' => static function ( $s ) {
					return is_bool( $s );
				},
			] ),
			// Approver (OAuth admin) actions:
			'approve'     => $base,
			'reject'      => array_merge( $base, $suppress ),
			'disable'     => array_merge( $base, $suppress ),
			'reenable'    => $base,
		];
	}

	/** @inheritDoc */
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

	/** @inheritDoc */
	protected function processAction( $action ): Status {
		$consumerRepository = OAuthServices::wrap( MediaWikiServices::getInstance() )->getConsumerRepository();
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

				if ( $consumerRepository->getByNameVersionUser(
					$this->vals['name'], $this->vals['version'], $centralUserId, IDBAccessObject::READ_LATEST
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

				$consumerRepository->save( $cmr );
				$this->makeLogEntry( Utils::getCentralWikiDB(), $cmr, $logAction, $user, $this->vals['description'] );
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
					OAuthServices::wrap( MediaWikiServices::getInstance() )
						->getConsumerAcceptanceRepository()
						->save( $cmra );
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

				$cmr = $consumerRepository->getByKey( $this->vals['consumerKey'], IDBAccessObject::READ_LATEST );
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
				if ( $consumerRepository->save( $cmr ) ) {
					$this->makeLogEntry( Utils::getCentralWikiDB(), $cmr, $action, $user, $this->vals['reason'] );
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
					OAuthServices::wrap( MediaWikiServices::getInstance() )
						->getConsumerAcceptanceRepository()
						->save( $cmra );
					if ( $cmr instanceof ClientEntity ) {
						$accessToken = $cmr->getOwnerOnlyAccessToken( $cmra, true );
					}
				}

				return $this->success( [ 'consumer' => $cmr, 'accessToken' => $accessToken ] );
			case 'approve':
				if ( !$permissionManager->userHasRight( $user, 'mwoauthmanageconsumer' ) ) {
					return $this->failure( 'permission_denied', 'badaccess-group0' );
				}

				$cmr = $consumerRepository->getByKey( $this->vals['consumerKey'], IDBAccessObject::READ_LATEST );
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
				if ( $consumerRepository->save( $cmr ) ) {
					$this->makeLogEntry( Utils::getCentralWikiDB(), $cmr, $action, $user, $this->vals['reason'] );
					$this->notify( $cmr, $user, $action, $this->vals['reason'] );
				}

				return $this->success( $cmr );
			case 'reject':
				if ( !$permissionManager->userHasRight( $user, 'mwoauthmanageconsumer' ) ) {
					return $this->failure( 'permission_denied', 'badaccess-group0' );
				}

				$cmr = $consumerRepository->getByKey( $this->vals['consumerKey'], IDBAccessObject::READ_LATEST );
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
				if ( $consumerRepository->save( $cmr ) ) {
					$this->makeLogEntry( Utils::getCentralWikiDB(), $cmr, $action, $user, $this->vals['reason'] );
					$this->notify( $cmr, $user, $action, $this->vals['reason'] );
				}

				return $this->success( $cmr );
			case 'disable':
				if ( !$permissionManager->userHasRight( $user, 'mwoauthmanageconsumer' ) ) {
					return $this->failure( 'permission_denied', 'badaccess-group0' );
				} elseif ( $this->vals['suppress'] && !$permissionManager->userHasRight( $user, 'mwoauthsuppress' ) ) {
					return $this->failure( 'permission_denied', 'badaccess-group0' );
				}

				$cmr = $consumerRepository->getByKey( $this->vals['consumerKey'], IDBAccessObject::READ_LATEST );
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
				if ( $consumerRepository->save( $cmr ) ) {
					$this->makeLogEntry( Utils::getCentralWikiDB(), $cmr, $action, $user, $this->vals['reason'] );
					$this->notify( $cmr, $user, $action, $this->vals['reason'] );
				}

				return $this->success( $cmr );
			case 'reenable':
				if ( !$permissionManager->userHasRight( $user, 'mwoauthmanageconsumer' ) ) {
					return $this->failure( 'permission_denied', 'badaccess-group0' );
				}

				$cmr = $consumerRepository->getByKey( $this->vals['consumerKey'], IDBAccessObject::READ_LATEST );
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
				if ( $consumerRepository->save( $cmr ) ) {
					$this->makeLogEntry( Utils::getCentralWikiDB(), $cmr, $action, $user, $this->vals['reason'] );
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
			throw new LogicException( "Invalid action type: $actionType" );
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
}
