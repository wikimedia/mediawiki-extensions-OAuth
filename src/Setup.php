<?php

namespace MediaWiki\Extension\OAuth;

use MediaWiki\Extension\OAuth\Backend\Utils;
use MediaWiki\Hook\TestCanonicalRedirectHook;
use MediaWiki\Output\OutputPage;
use MediaWiki\Request\WebRequest;
use MediaWiki\Title\Title;

/**
 * Class containing hooked functions for an OAuth environment
 */
class Setup implements TestCanonicalRedirectHook {
	/**
	 * Prevent redirects to canonical titles, since that's not what the OAuth
	 * request signed.
	 * @param WebRequest $request
	 * @param Title $title
	 * @param OutputPage $output
	 * @return bool
	 */
	public function onTestCanonicalRedirect( $request, $title, $output ) {
		return !self::isOAuthRequest( $request );
	}

	protected static function isOAuthRequest( $request ) {
		if ( Utils::hasOAuthHeaders( $request ) ) {
			return true;
		}
		return ResourceServer::isOAuth2Request( $request );
	}
}
