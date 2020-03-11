<?php

namespace MediaWiki\Extensions\OAuth;

use ApiMessage;
use GuzzleHttp\Psr7\ServerRequest;
use MediaWiki\Extensions\OAuth\Repository\AccessTokenRepository;
use MediaWiki\Session\SessionBackend;
use MediaWiki\Session\SessionInfo;
use MediaWiki\Session\SessionManager;
use MediaWiki\Session\UserInfo;
use User;
use WebRequest;
use Wikimedia\Rdbms\DBError;

/**
 * Session provider for OAuth
 *
 * This is a fairly standard ImmutableSessionProviderWithCookie implementation:
 * the user identity is determined by the OAuth headers included in the
 * request. But since we want to make sure to fail the request when OAuth
 * headers are present but invalid, this takes the somewhat unusual step of
 * returning a bogus SessionInfo and then hooking ApiBeforeMain to throw a
 * fatal exception after MediaWiki is ready to handle it.
 *
 * It also takes advantage of the getAllowedUserRights() method for authz
 * purposes (limiting the rights to those included in the grant), and
 * registers some hooks to tag actions made via the provider.
 */
class MWOAuthSessionProvider extends \MediaWiki\Session\ImmutableSessionProviderWithCookie {

	public function __construct( array $params = [] ) {
		global $wgHooks;

		parent::__construct( $params );

		$wgHooks['ApiCheckCanExecute'][] = $this;
		$wgHooks['RecentChange_save'][] = $this;
		$wgHooks['MarkPatrolled'][] = $this;
	}

	/**
	 * Throw an exception, later
	 *
	 * @param string $key Key for the error message
	 * @param mixed ...$params Parameters as strings.
	 * @return SessionInfo
	 */
	private function makeException( $key, ...$params ) {
		global $wgHooks;

		// First, schedule the throwing of the exception for later when the API
		// is ready to catch it
		$msg = wfMessage( $key, $params );
		$exception = \ApiUsageException::newWithMessage( null, $msg );
		$wgHooks['ApiBeforeMain'][] = function () use ( $exception ) {
			throw $exception;
		};

		// Then return an appropriate SessionInfo
		$id = $this->hashToSessionId( 'bogus' );
		return new SessionInfo( SessionInfo::MAX_PRIORITY, [
			'provider' => $this,
			'id' => $id,
			'userInfo' => UserInfo::newAnonymous(),
			'persisted' => false,
		] );
	}

	public function provideSessionInfo( WebRequest $request ) {
		// For some reason MWOAuth is restricted to be API-only.
		if ( !defined( 'MW_API' ) && !defined( 'MW_REST_API' ) ) {
			return null;
		}

		$oauthVersion = $this->getOAuthVersionFromRequest( $request );
		if ( $oauthVersion === null ) {
			// Not an OAuth request
			return null;
		}

		$logData = [
			'clientip' => $request->getIP(),
			'user' => false,
			'consumer' => '',
			'result' => 'fail',
		];

		$dbr = MWOAuthUtils::getCentralDB( DB_REPLICA );
		$access = null;
		try {
			if ( $oauthVersion === MWOAuthConsumer::OAUTH_VERSION_2 ) {
				$resourceServer = ResourceServer::factory();
				$accessTokenKey = $this->verifyOAuth2Request( $resourceServer, $request );
				$accessTokenRepo = new AccessTokenRepository();
				$accessId = $accessTokenRepo->getApprovalId( $accessTokenKey );
				if ( $accessId === 0 ) {
					if (
						$resourceServer->getUser()->getId() === 0 &&
						$resourceServer->getClient()->getOwnerOnly() === false
					) {
						// This tell us, with good degree of certainty, that the AT
						// was issued to a machine and represents no particular user
						$access = MWOAuthConsumerAcceptance::newFromArray( [
							'id'           => null,
							'wiki'         => $resourceServer->getClient()->getWiki(),
							'userId'       => 0,
							'consumerId'   => $resourceServer->getClient()->getId(),
							'accessToken'  => '',
							'accessSecret' => '',
							'grants'       => $resourceServer->getClient()->getGrants(),
							'accepted'     => wfTimestampNow(),
							'oauth_version' => MWOAuthConsumer::OAUTH_VERSION_2
						] );
					}
				} else {
					$access = MWOAuthConsumerAcceptance::newFromId(
						MWOAuthUtils::getCentralDB( DB_REPLICA ), $accessId
					);
				}
				if ( !$access ) {
					throw new MWOAuthException( 'mwoauth-oauth2-error-create-at-no-user-approval' );
				}

				// Set the scopes that are verified for this request
				$access->setField( 'grants', array_keys( $resourceServer->getScopes() ) );
			} else {
				$server = MWOAuthUtils::newMWOAuthServer();
				$oauthRequest = MWOAuthRequest::fromRequest( $request );
				$logData['consumer'] = $oauthRequest->getConsumerKey();
				list( , $accessToken ) = $server->verify_request( $oauthRequest );
				$accessTokenKey = $accessToken->key;
				$access = MWOAuthConsumerAcceptance::newFromToken( $dbr, $accessTokenKey );
			}
		} catch ( \Exception $ex ) {
			$this->logger->info( 'Bad OAuth request from {ip}', $logData + [ 'exception' => $ex ] );
			return $this->makeException( 'mwoauth-invalid-authorization', $ex->getMessage() );
		}

		$logData['user'] = MWOAuthUtils::getCentralUserNameFromId( $access->getUserId(), 'raw' );

		$wiki = wfWikiID();
		// Access token is for this wiki
		if ( $access->getWiki() !== '*' && $access->getWiki() !== $wiki ) {
			$this->logger->debug( 'OAuth request for wrong wiki from user {user}', $logData );
			return $this->makeException( 'mwoauth-invalid-authorization-wrong-wiki', $wiki );
		}

		// There exists a local user
		$localUser = MWOAuthUtils::getLocalUserFromCentralId( $access->getUserId() );
		if ( !$localUser ) {
			$localUser = User::newFromId( 0 );
		}
		// If there is an actual approval, but user bound to it does not exist
		if ( $access->getId() > 0 && $localUser->getId() === 0 ) {
			$this->logger->debug( 'OAuth request for invalid or non-local user {user}', $logData );
			return $this->makeException( 'mwoauth-invalid-authorization-invalid-user',
				\Message::rawParam( \Linker::makeExternalLink(
					'https://www.mediawiki.org/wiki/Help:OAuth/Errors#E008',
					'E008',
					true
				) )
			);
		}
		if ( $localUser->isLocked() ||
			( $this->config->get( 'BlockDisablesLogin' ) && $localUser->isBlocked() )
		) {
			$this->logger->debug( 'OAuth request for blocked user {user}', $logData );
			return $this->makeException( 'mwoauth-invalid-authorization-blocked-user' );
		}

		// The consumer is approved or owned by $localUser, and is for this wiki.
		$consumer = MWOAuthConsumer::newFromId( $dbr, $access->getConsumerId() );
		if ( !$consumer->isUsableBy( $localUser ) ) {
			$this->logger->debug(
				'OAuth request for consumer {consumer} not approved by user {user}', $logData
			);
			return $this->makeException( 'mwoauth-invalid-authorization-not-approved',
				$consumer->getName() );
		} elseif ( $consumer->getWiki() !== '*' && $consumer->getWiki() !== $wiki ) {
			$this->logger->debug( 'OAuth request for consumer {consumer} to incorrect wiki', $logData );
			return $this->makeException( 'mwoauth-invalid-authorization-wrong-wiki', $wiki );
		}

		// Ok, use this user!
		if ( $this->sessionCookieName === null ) {
			// We're not configured to use cookies, so concatenate some of the
			// internal consumer-acceptance state to generate an ID.
			$id = $this->hashToSessionId( implode( "\n", [
				$access->getId(),
				$access->getWiki(),
				$access->getUserId(),
				$access->getConsumerId(),
				$access->getAccepted(),
				$wiki,
			] ) );
			$persisted = false;
			$forceUse = true;
		} else {
			$id = $this->getSessionIdFromCookie( $request );
			$persisted = $id !== null;
			$forceUse = false;
		}

		$logData['result'] = 'success';
		$this->logger->debug( 'OAuth request for consumer {consumer} by user {user}', $logData );

		return new SessionInfo( SessionInfo::MAX_PRIORITY, [
			'provider' => $this,
			'id' => $id,
			'userInfo' => UserInfo::newFromUser( $localUser, true ),
			'persisted' => $persisted,
			'forceUse' => $forceUse,
			'metadata' => [
				'oauthVersion' => $oauthVersion,
				'consumerId' => $consumer->getOwnerOnly() ? null : $consumer->getId(),
				'key' => $accessTokenKey,
				'rights' => \MWGrants::getGrantRights( $access->getGrants() ),
			],
		] );
	}

	/**
	 * Determine OAuth version of the request
	 *
	 * @param WebRequest $request
	 * @return int|null if request is not using OAuth header
	 */
	private function getOAuthVersionFromRequest( WebRequest $request ) {
		if ( MWOAuthUtils::hasOAuthHeaders( $request ) ) {
			return MWOAuthConsumer::OAUTH_VERSION_1;
		}
		if ( ResourceServer::isOAuth2Request( $request ) ) {
			return MWOAuthConsumer::OAUTH_VERSION_2;
		}

		return null;
	}

	/**
	 * @param ResourceServer &$resourceServer
	 * @param WebRequest $request
	 * @return string
	 * @throws MWOAuthException
	 */
	private function verifyOAuth2Request( ResourceServer &$resourceServer, WebRequest $request ) {
		$request = ServerRequest::fromGlobals()->withHeader(
			'authorization',
			$request->getHeader( 'authorization' )
		);

		$response = new Response();
		$valid = false;
		$resourceServer->verify(
			$request,
			$response,
			function ( $request, $response ) use ( &$valid ) {
				$valid = true;
			}
		);

		if ( $valid ) {
			return $resourceServer->getAccessTokenId();
		}

		throw new MWOAuthException( 'mwoauth-oauth2-invalid-access-token' );
	}

	public function preventSessionsForUser( $username ) {
		$id = MWOAuthUtils::getCentralIdFromUserName( $username );
		$dbw = MWOAuthUtils::getCentralDB( DB_MASTER );

		$dbw->startAtomic( __METHOD__ );
		try {
			// Remove any approvals for the user's consumers before deleting them
			$dbw->deleteJoin(
				'oauth_accepted_consumer',
				'oauth_registered_consumer',
				'oaac_consumer_id',
				'oarc_id',
				[ 'oarc_user_id' => $id ],
				__METHOD__
			);
			$dbw->delete(
				'oauth_registered_consumer',
				[ 'oarc_user_id' => $id ],
				__METHOD__
			);

			// Remove any approvals by this user, too
			$dbw->delete(
				'oauth_accepted_consumer',
				[ 'oaac_user_id' => $id ],
				__METHOD__
			);
		} catch ( DBError $e ) {
			$dbw->rollback( __METHOD__ );
			throw $e;
		}
		$dbw->endAtomic( __METHOD__ );
	}

	public function getVaryHeaders() {
		return [
			'Authorization' => null,
		];
	}

	/**
	 * Fetch the access data, if any, for this user-session
	 * @param \User|null $user
	 * @return array|null
	 */
	private function getSessionData( \User $user = null ) {
		if ( $user ) {
			$session = $user->getRequest()->getSession();
			if ( $session->getProvider() === $this &&
				$user->equals( $session->getUser() )
			) {
				return $session->getProviderMetadata();
			}
		} else {
			$session = SessionManager::getGlobalSession();
			if ( $session->getProvider() === $this ) {
				return $session->getProviderMetadata();
			}
		}

		return null;
	}

	public function getAllowedUserRights( SessionBackend $backend ) {
		if ( $backend->getProvider() !== $this ) {
			throw new \InvalidArgumentException( 'Backend\'s provider isn\'t $this' );
		}
		$data = $backend->getProviderMetadata();
		if ( $data ) {
			return $data['rights'];
		}

		// Should never happen
		$this->logger->debug( __METHOD__ . ': No provider metadata, returning no rights allowed' );
		return [];
	}

	/**
	 * Disable certain API modules when used with OAuth
	 *
	 * @param \ApiBase $module
	 * @param \User $user
	 * @param string|array &$message
	 * @return bool
	 */
	public function onApiCheckCanExecute( \ApiBase $module, \User $user, &$message ) {
		global $wgMWOauthDisabledApiModules;
		if ( !$this->getSessionData( $user ) ) {
			return true;
		}

		foreach ( $wgMWOauthDisabledApiModules as $badModule ) {
			if ( $module instanceof $badModule ) {
				$message = ApiMessage::create(
					[ 'mwoauth-api-module-disabled', $module->getModuleName() ],
					'mwoauth-api-module-disabled'
				);
				return false;
			}
		}

		return true;
	}

	/**
	 * Record the fact that OAuth was used for anything added to RecentChanges.
	 *
	 * @param \RecentChange $rc
	 * @return bool true
	 */
	public function onRecentChange_save( $rc ) {
		$consumerId = $this->getPublicConsumerId( $rc->getPerformer() ?: null );
		if ( $consumerId !== null ) {
			$rc->addTags( MWOAuthUtils::getTagName( $consumerId ) );
		}
		return true;
	}

	/**
	 * Get the consumer ID of the non-owner-only OAuth consumer associated with this user, or null.
	 * @param User|null $user
	 * @return int|null
	 */
	protected function getPublicConsumerId( User $user = null ) {
		$data = $this->getSessionData( $user );
		if ( $data && isset( $data['consumerId'] ) ) {
			return $data['consumerId'];
		}
		return null;
	}

	/**
	 * Record the fact that OAuth was used for marking an existing RecentChange as patrolled.
	 * (RecentChange::doMarkPatrolled() does not use RecentChange::save()
	 * and therefore bypasses the above hook handler.)
	 *
	 * @param int $rcid
	 * @param User $user
	 * @param bool $wcOnlySysopsCanPatrol
	 * @param bool $auto
	 * @param string[] &$tags
	 *
	 * @return bool true
	 */
	public function onMarkPatrolled(
		$rcid,
		User $user,
		$wcOnlySysopsCanPatrol,
		$auto,
		array &$tags
	) {
		$consumerId = $this->getPublicConsumerId( $user );
		if ( $consumerId !== null ) {
			$tags[] = MWOAuthUtils::getTagName( $consumerId );
		}
		return true;
	}

	/**
	 * OAuth tokens already protect against CSRF. CSRF tokens are not required.
	 *
	 * @return bool true
	 */
	public function safeAgainstCsrf() {
		return true;
	}
}
