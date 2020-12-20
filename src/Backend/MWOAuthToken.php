<?php

namespace MediaWiki\Extensions\OAuth\Backend;

use MediaWiki\Extensions\OAuth\Lib\OAuthToken;

class MWOAuthToken extends OAuthToken {
	/** @var string|null Keep the verification code here */
	protected $code;
	/** @var string|null Token to find grant in oauth_accepted_consumer */
	protected $accessTokenKey;

	public function addVerifyCode( $code ) {
		$this->code = $code;
	}

	public function getVerifyCode() {
		return $this->code;
	}

	public function addAccessKey( $key ) {
		$this->accessTokenKey = $key;
	}

	public function getAccessKey() {
		return $this->accessTokenKey;
	}
}
