<?php
/**
 * @license GPL-2.0-or-later
 *
 * @file
 */

namespace MediaWiki\Extension\OAuth\Backend;

use MediaWiki\Extension\AbuseFilter\Hooks\AbuseFilterBuilderHook;
use MediaWiki\Extension\AbuseFilter\Hooks\AbuseFilterComputeVariableHook;
use MediaWiki\Extension\AbuseFilter\Hooks\AbuseFilterGenerateUserVarsHook;
use MediaWiki\Extension\AbuseFilter\Variables\VariableHolder;
use MediaWiki\Extension\OAuth\SessionProvider;
use MediaWiki\RecentChanges\RecentChange;
use MediaWiki\User\User;

/**
 * @author Taavi "Majavah" Väänänen <hi@taavi.wtf>
 */
class AbuseFilterHookHandler implements
	AbuseFilterBuilderHook,
	AbuseFilterComputeVariableHook,
	AbuseFilterGenerateUserVarsHook
{
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
			[ 'user' => $user ]
		);
	}
}
