<?php

namespace MediaWiki\Extension\OAuth\Tests;

/**
 * A very simple class that you can pass a base-string, and then have it returned again.
 * Used for testing the signature-methods
 */
class MockOAuthBaseStringRequest {
	private string $provided_base_string;
	/** @var string Retained for legacy reasons. */
	public $base_string;

	/**
	 * @param string $bs
	 */
	public function __construct( $bs ) {
		$this->provided_base_string = $bs;
	}

	/**
	 * @return string
	 */
	public function get_signature_base_string() {
		return $this->provided_base_string;
	}
}
