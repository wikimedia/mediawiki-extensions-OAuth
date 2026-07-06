<?php

use MediaWiki\Config\Config;
use MediaWiki\Config\ServiceOptions;
use MediaWiki\Extension\OAuth\Backend\Utils;
use MediaWiki\Extension\OAuth\Control\Workflow;
use MediaWiki\Extension\OAuth\OAuthServices;
use MediaWiki\Extension\OAuth\Repository\ConsumerRepositoryInterface;
use MediaWiki\Extension\OAuth\Repository\DatabaseConsumerRepository;
use MediaWiki\MediaWikiServices;

/** @phpcs-require-sorted-array */
return [

	'OAuthConfig' => static function ( MediaWikiServices $services ): Config {
		return $services->getMainConfig();
	},

	'OAuthConsumerRepository' => static function ( MediaWikiServices $services ): ConsumerRepositoryInterface {
		return new DatabaseConsumerRepository();
	},

	'OAuthWorkflow' => static function ( MediaWikiServices $services ): Workflow {
		$oauthConfig = OAuthServices::wrap( $services )->getConfig();
		$oauthUrlUtils = Utils::getOAuthUrlUtils( $services->getMainConfig() );
		return new Workflow( new ServiceOptions( Workflow::CONSTRUCTOR_OPTIONS, $oauthConfig ), $oauthUrlUtils );
	},

];
