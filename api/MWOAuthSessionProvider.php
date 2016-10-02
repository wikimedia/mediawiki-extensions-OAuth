<?php

namespace MediaWiki\Extensions\OAuth;

use ApiMessage;
use MediaWiki\Session\SessionBackend;
use MediaWiki\Session\SessionManager;
use MediaWiki\Session\SessionInfo;
use MediaWiki\Session\UserInfo;
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
 * hooks RecentChange_save to tag revisions made via the provider.
 */
class MWOAuthSessionProvider extends \MediaWiki\Session\ImmutableSessionProviderWithCookie {

	public function __construct( array $params = [] ) {
		global $wgHooks;

		parent::__construct( $params );

		$wgHooks['ApiCheckCanExecute'][] = $this;
		$wgHooks['RecentChange_save'][] = $this;
	}

	/**
	 * Throw an exception, later
	 *
	 * @param string $key Key for the error message
	 * @param mixed $params,... Parameters as strings.
	 * @return SessionInfo
	 */
	private function makeException( $key /*, ... */ ) {
		global $wgHooks;

		// First, schedule the throwing of the exception for later when the API
		// is ready to catch it
		$params = func_get_args();
		array_shift( $params );
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
		if ( !defined( 'MW_API' ) ) {
			return null;
		}

		if ( !MWOAuthUtils::hasOAuthHeaders( $request ) ) {
			return null;
		}

		$logData = [
			'clientip' => $request->getIP(),
			'user' => false,
			'consumer' => '',
			'result' => 'fail',
		];

		try {
			$server = MWOAuthUtils::newMWOAuthServer();
			$oauthRequest = MWOAuthRequest::fromRequest( $request );
			$logData['consumer'] = $oauthRequest->getConsumerKey();
			list( , $accesstoken ) = $server->verify_request( $oauthRequest );
		} catch ( OAuthException $ex ) {
			$this->logger->debug( 'Bad OAuth request from {ip}', $logData + [ 'exception' => $ex ] );
			return $this->makeException( 'mwoauth-invalid-authorization', $ex->getMessage() );
		}

		$wiki = wfWikiID();
		$dbr = MWOAuthUtils::getCentralDB( DB_REPLICA );

		$access = MWOAuthConsumerAcceptance::newFromToken( $dbr, $accesstoken->key );
		$logData['user'] = MWOAuthUtils::getCentralUserNameFromId( $access->getUserId(), 'raw' );

		// Access token is for this wiki
		if ( $access->getWiki() !== '*' && $access->getWiki() !== $wiki ) {
			$this->logger->debug( 'OAuth request for wrong wiki from user {user}', $logData );
			return $this->makeException( 'mwoauth-invalid-authorization-wrong-wiki', $wiki );
		}

		// There exists a local user
		$localUser = MWOAuthUtils::getLocalUserFromCentralId( $access->getUserId() );
		if ( !$localUser || !$localUser->isLoggedIn() ) {
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
			return $this->makeException( 'mwoauth-invalid-authorization-not-approved' );
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
				'key' => $accesstoken->key,
				'rights' => \MWGrants::getGrantRights( $access->getGrants() ),
			],
		] );
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
			'Authorization' => [
				'substr="OAuth "',
			],
		];
	}

	/**
	 * Fetch the access data, if any, for this user-session
	 * @param \\User|null $user
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
		$data = $this->getSessionData( $rc->getPerformer() ?: null );
		if ( $data ) {
			$dbr = MWOAuthUtils::getCentralDB( DB_REPLICA );
			$access = MWOAuthConsumerAcceptance::newFromToken( $dbr, $data['key'] );
			$consumerId = $access->getConsumerId();
			$consumer = MWOAuthConsumer::newFromId( $dbr, $consumerId );
			if ( !$consumer->getOwnerOnly() ) {
				$rc->addTags( "OAuth CID: $consumerId" );
			}
		}
		return true;
	}

}
