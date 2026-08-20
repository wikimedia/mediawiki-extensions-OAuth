<?php
/**
 * @license GPL-2.0-or-later
 *
 * @file
 */

namespace MediaWiki\Extension\OAuth\Backend;

use MediaWiki\ChangeTags\ChangeTagsStore;
use MediaWiki\Extension\AbuseFilter\Hooks\AbuseFilterBuilderHook;
use MediaWiki\Extension\AbuseFilter\Hooks\AbuseFilterComputeVariableHook;
use MediaWiki\Extension\AbuseFilter\Hooks\AbuseFilterGenerateUserVarsHook;
use MediaWiki\Extension\AbuseFilter\Variables\VariableHolder;
use MediaWiki\Extension\OAuth\SessionProvider;
use MediaWiki\RecentChanges\RecentChange;
use MediaWiki\User\User;
use Wikimedia\Rdbms\IConnectionProvider;

/**
 * @author Taavi "Majavah" Väänänen <hi@taavi.wtf>
 */
class AbuseFilterHookHandler implements
	AbuseFilterBuilderHook,
	AbuseFilterComputeVariableHook,
	AbuseFilterGenerateUserVarsHook
{

	public function __construct(
		private readonly ChangeTagsStore $changeTagsStore,
		private readonly IConnectionProvider $connectionProvider,
	) {
	}

	/** @inheritDoc */
	public function onAbuseFilter_builder( array &$realValues ) {
		// Uses: 'abusefilter-edit-builder-vars-oauth-consumer'
		$realValues['vars']['oauth_consumer'] = 'oauth-consumer';
	}

	/** @inheritDoc */
	public function onAbuseFilter_computeVariable(
		string $method,
		VariableHolder $vars,
		array $parameters,
		?string &$result
	) {
		if ( $method !== 'oauth-consumer' ) {
			return true;
		}

		$rcId = $parameters['rc_id'] ?? null;
		if ( $rcId !== null ) {
			// The variables are being generated for a past change, e.g. for
			// Special:AbuseFilter/examine. The session the change was made in
			// is gone, so recover the consumer from the change tags instead.
			$result = $this->getConsumerIdFromRcId( $rcId );
			return false;
		}

		/** @var User $user */
		$user = $parameters['user'];

		$session = $user->getRequest()->getSession();

		if (
			// SessionProvider here refers to the OAuth session provider class
			$session->getProvider() instanceof SessionProvider
			// consumerId is only set if the consumer is not owner-only (which is what we want)
			&& isset( $session->getProviderMetadata()['consumerId'] )
		) {
			$result = $session->getProviderMetadata()['consumerId'];
		}

		return false;
	}

	/** @inheritDoc */
	public function onAbuseFilter_generateUserVars( VariableHolder $vars, User $user, ?RecentChange $rc ) {
		$vars->setLazyLoadVar(
			'oauth_consumer',
			'oauth-consumer',
			[
				'user' => $user,
				'rc_id' => $rc ? (int)$rc->getAttribute( 'rc_id' ) : null,
			]
		);
	}

	/**
	 * Recover the consumer ID used for a past change from its change tags.
	 * Changes made by owner-only consumers are not tagged, matching the
	 * live code path, where such consumers do not expose their ID either.
	 */
	private function getConsumerIdFromRcId( int $rcId ): ?int {
		$dbr = $this->connectionProvider->getReplicaDatabase();
		foreach ( $this->changeTagsStore->getTags( $dbr, $rcId ) as $tag ) {
			$consumerId = Utils::parseTagName( $tag );
			if ( $consumerId !== null ) {
				return $consumerId;
			}
		}
		return null;
	}
}
