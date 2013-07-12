<?php
// TODO: This can probably go away now
class MWOAuthGrants {

	// Array of permissions
	public $grants;

	public function __construct( $grants ) {
		if ( !is_array( $grants ) ) {
			throw new MWOAuthException( 'mwoauthgrants-general-error' );
		}
		$this->grants = $grants;
	}

	public static function newFromResult( $row ) {
		$grants = FormatJSON::decode( $row->oarc_grants );
		if ( is_null( $grants ) ) {
			$grants = array();
		}
		return new self( $grants );
	}

	public function toJson() {
		return FormatJSON::encode( $this->grants );
	}

}

