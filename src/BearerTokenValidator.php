<?php

namespace MediaWiki\Extension\OAuth;

use League\OAuth2\Server\AuthorizationValidators\BearerTokenValidator as LeagueBearerTokenValidator;
use Psr\Http\Message\ServerRequestInterface;

class BearerTokenValidator extends LeagueBearerTokenValidator {

	/** @inheritDoc */
	public function validateAuthorization( ServerRequestInterface $request ) {
		$request = parent::validateAuthorization( $request );
		$sub = $request->getAttribute( 'oauth_user_id' );
		if ( str_starts_with( $sub, 'mw:' ) ) {
			// convert JWT subject to raw user ID
			$request = $request->withAttribute( 'oauth_user_id', array_last( explode( ':', $sub ) ) );
		}
		return $request;
	}

}
