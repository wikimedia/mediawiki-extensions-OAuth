<?php

namespace MediaWiki\Extension\OAuth;

use MediaWiki\Config\Config;
use MediaWiki\Extension\OAuth\Control\Workflow;
use MediaWiki\Extension\OAuth\Repository\ConsumerAcceptanceRepositoryInterface;
use MediaWiki\Extension\OAuth\Repository\ConsumerRepositoryInterface;
use MediaWiki\MediaWikiServices;

class OAuthServices {

	public function __construct( private readonly MediaWikiServices $coreServices ) {
	}

	/**
	 * Static version of the constructor, for nicer syntax.
	 */
	public static function wrap( MediaWikiServices $coreServices ): static {
		return new static( $coreServices );
	}

	public function getConfig(): Config {
		return $this->coreServices->get( 'OAuthConfig' );
	}

	public function getConsumerRepository(): ConsumerRepositoryInterface {
		return $this->coreServices->get( 'OAuthConsumerRepository' );
	}

	public function getConsumerAcceptanceRepository(): ConsumerAcceptanceRepositoryInterface {
		return $this->coreServices->get( 'OAuthConsumerAcceptanceRepository' );
	}

	public function getWorkflow(): Workflow {
		return $this->coreServices->get( 'OAuthWorkflow' );
	}

}
