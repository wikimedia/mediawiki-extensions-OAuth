<?php

namespace MediaWiki\Extension\OAuth;

use MediaWiki\Config\Config;
use MediaWiki\Extension\OAuth\Control\Workflow;
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

	public function getWorkflow(): Workflow {
		return $this->coreServices->get( 'OAuthWorkflow' );
	}

}
