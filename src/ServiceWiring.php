<?php

use MediaWiki\Config\Config;
use MediaWiki\Config\ServiceOptions;
use MediaWiki\Extension\OAuth\Backend\Utils;
use MediaWiki\Extension\OAuth\Control\ConsumerValidator;
use MediaWiki\Extension\OAuth\Control\Workflow;
use MediaWiki\Extension\OAuth\OAuthConfigNames;
use MediaWiki\Extension\OAuth\OAuthServices;
use MediaWiki\Extension\OAuth\Repository\ArrayConsumerRepository;
use MediaWiki\Extension\OAuth\Repository\CompositeConsumerRepository;
use MediaWiki\Extension\OAuth\Repository\ConsumerAcceptanceRepositoryInterface;
use MediaWiki\Extension\OAuth\Repository\ConsumerRepositoryInterface;
use MediaWiki\Extension\OAuth\Repository\DatabaseConsumerAcceptanceRepository;
use MediaWiki\Extension\OAuth\Repository\DatabaseConsumerRepository;
use MediaWiki\MediaWikiServices;

/** @phpcs-require-sorted-array */
return [

	'OAuthConfig' => static function ( MediaWikiServices $services ): Config {
		return $services->getMainConfig();
	},

	'OAuthConsumerAcceptanceRepository' => static function (
		MediaWikiServices $services
	): ConsumerAcceptanceRepositoryInterface {
		return new DatabaseConsumerAcceptanceRepository();
	},

	'OAuthConsumerRepository' => static function ( MediaWikiServices $services ): ConsumerRepositoryInterface {
		$dbRepo = $services->get( '_OAuthConsumerRepository_DB' );
		$configRepo = new ArrayConsumerRepository(
			OAuthServices::wrap( $services )->getConsumerValidator(),
		);
		$staticApps = $services->getMainConfig()->get( OAuthConfigNames::OAuthStaticApps );
		foreach ( $staticApps as $app ) {
			$configRepo->addConfigurationArray( $app );
		}
		return new CompositeConsumerRepository( $configRepo, $dbRepo );
	},

	'OAuthConsumerValidator' => static function ( MediaWikiServices $services ): ConsumerValidator {
		$mainConfig = $services->getMainConfig();
		return new ConsumerValidator(
			new ServiceOptions( ConsumerValidator::SERVICE_OPTIONS, $mainConfig ),
			$services->getFormatterFactory(),
		);
	},

	'OAuthWorkflow' => static function ( MediaWikiServices $services ): Workflow {
		$oauthConfig = OAuthServices::wrap( $services )->getConfig();
		$oauthUrlUtils = Utils::getOAuthUrlUtils( $services->getMainConfig() );
		return new Workflow( new ServiceOptions( Workflow::CONSTRUCTOR_OPTIONS, $oauthConfig ), $oauthUrlUtils );
	},

	'_OAuthConsumerRepository_DB' => static function ( MediaWikiServices $services ): ConsumerRepositoryInterface {
		return new DatabaseConsumerRepository();
	}
];
