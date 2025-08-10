<?php

namespace MediaWiki\Extension\OAuth\Exception;

use League\OAuth2\Server\Exception\OAuthServerException;

class ClientApprovalDenyException extends OAuthServerException {

	public function __construct( ?string $redirectUri ) {
		parent::__construct(
			wfMessage( 'mwoauth-oauth2-error-user-approval-deny' )->plain(),
			401,
			'unauthorized_client',
			400,
			null,
			$redirectUri
		);
	}
}
