<?php
/**
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
 * 51 Franklin Street, Fifth Floor, Boston, MA 02110-1301, USA.
 * http://www.gnu.org/copyleft/gpl.html
 *
 * @file
 */

namespace MediaWiki\Extension\OAuth\Backend;

use MediaWiki\Extension\AbuseFilter\Hooks\AbuseFilterBuilderHook;
use MediaWiki\Extension\AbuseFilter\Hooks\AbuseFilterComputeVariableHook;
use MediaWiki\Extension\AbuseFilter\Hooks\AbuseFilterGenerateUserVarsHook;
use MediaWiki\Extension\AbuseFilter\Variables\VariableHolder;
use MediaWiki\Extension\OAuth\SessionProvider;
use MediaWiki\User\User;
use RecentChange;

/**
 * @author Taavi "Majavah" Väänänen <hi@taavi.wtf>
 */
class AbuseFilterHookHandler implements
	AbuseFilterBuilderHook,
	AbuseFilterComputeVariableHook,
	AbuseFilterGenerateUserVarsHook
{
	public function onAbuseFilter_builder( array &$realValues ) {
		// Uses: 'abusefilter-edit-builder-vars-oauth-consumer'
		$realValues['vars']['oauth_consumer'] = 'oauth-consumer';
	}

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

	public function onAbuseFilter_generateUserVars( VariableHolder $vars, User $user, ?RecentChange $rc ) {
		$vars->setLazyLoadVar(
			'oauth_consumer',
			'oauth-consumer',
			[ 'user' => $user ]
		);
	}
}
