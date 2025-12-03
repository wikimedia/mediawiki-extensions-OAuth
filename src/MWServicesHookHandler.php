<?php
/**
 * @license GPL-2.0-or-later
 *
 * @file
 */

namespace MediaWiki\Extension\OAuth;

use MediaWiki\Hook\MediaWikiServicesHook;

class MWServicesHookHandler implements MediaWikiServicesHook {
	/** @inheritDoc */
	public function onMediaWikiServices( $services ): void {
		global $wgMWOAuthCentralWiki, $wgVirtualDomainsMapping;

		if ( $wgMWOAuthCentralWiki && !isset( $wgVirtualDomainsMapping['virtual-oauth'] ) ) {
			if ( !defined( 'MW_PHPUNIT_TEST' ) && !defined( 'MW_QUIBBLE_CI' ) ) {
				wfDeprecatedMsg(
					'Set $wgVirtualDomainsMapping[\'virtual-oauth\'] = [ \'db\' => $wgMWOAuthCentralWiki ];. '
					. 'OAuth now makes use of virtual database domains.',
					'1.45',
					'OAuth'
				);
			}

			$wgVirtualDomainsMapping['virtual-oauth'] = [ 'db' => $wgMWOAuthCentralWiki ];
		}
	}
}
