<?php

namespace MediaWiki\Extension\OAuth\Backend;

use MediaWiki\Extension\OAuth\SessionProvider;
use MediaWiki\Extension\XAnalytics\Hooks\XAnalyticsSetHeaderHook;
use MediaWiki\Output\OutputPage;

class XAnalyticsHook implements XAnalyticsSetHeaderHook {
	/** @inheritDoc */
	public function onXAnalyticsSetHeader( OutputPage $out, array &$headerItems ) {
		$session = $out->getRequest()->getSession();
		if ( $session->getProvider() instanceof SessionProvider ) {
			$metadata = $session->getProviderMetadata();
			// ignore bogus session returned by SessionProvider::makeException()
			if ( $metadata ) {
				$headerItems += [
					'oauth_version' => $metadata['oauthVersion'],
					'oauth_client_id' => $metadata['consumerKey'],
				];
			}
		}
	}
}
