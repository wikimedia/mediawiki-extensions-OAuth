<?php

use MediaWiki\Config\Config;
use MediaWiki\Config\ServiceOptions;
use MediaWiki\Extension\OAuth\Control\Workflow;
use MediaWiki\Extension\OAuth\OAuthServices;
use MediaWiki\MediaWikiServices;

return [

	'OAuthConfig' => static function ( MediaWikiServices $services ): Config {
		return $services->getMainConfig();
	},

	'OAuthWorkflow' => static function ( MediaWikiServices $services ): Workflow {
		$oauthConfig = OAuthServices::wrap( $services )->getConfig();
		return new Workflow( new ServiceOptions( Workflow::CONSTRUCTOR_OPTIONS, $oauthConfig ) );
	},

];
