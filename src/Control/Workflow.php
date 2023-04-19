<?php

namespace MediaWiki\Extension\OAuth\Control;

use MediaWiki\Config\ServiceOptions;
use MediaWiki\Extension\OAuth\Backend\Consumer;

/** Service class for OAuth workflow-related business logic. */
class Workflow {

	/** @internal For use by ServiceWiring */
	public const CONSTRUCTOR_OPTIONS = [
		'OAuthAutoApprove',
	];

	public const AUTOAPPROVE_RULE_GRANTS = 'grants';

	private ServiceOptions $options;

	/**
	 * @param ServiceOptions $options
	 */
	public function __construct( ServiceOptions $options ) {
		$options->assertRequiredOptions( self::CONSTRUCTOR_OPTIONS );
		$this->options = $options;
	}

	/**
	 * True if this is a low-risk consumer that does not require manual approval from an
	 * OAuth admin, and can go straight to the 'approved' stage after creation.
	 * @param Consumer $consumer
	 * @return bool
	 */
	public function consumerCanBeAutoApproved( Consumer $consumer ): bool {
		foreach ( $this->options->get( 'OAuthAutoApprove' ) as $condition ) {
			// check 'grants' rule
			if ( array_key_exists( self::AUTOAPPROVE_RULE_GRANTS, $condition ) ) {
				$allowedGrants = $condition[self::AUTOAPPROVE_RULE_GRANTS];
				if ( array_diff( $consumer->getGrants(), $allowedGrants ) !== [] ) {
					continue;
				}
				unset( $condition[self::AUTOAPPROVE_RULE_GRANTS] );
			}

			// check for unsupported rules
			if ( $condition ) {
				continue;
			}

			return true;
		}
		// none of the conditions matched
		return false;
	}

}
