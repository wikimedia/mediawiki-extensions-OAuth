<?php

namespace MediaWiki\Extension\OAuth;

use League\OAuth2\Server\AuthorizationValidators\BearerTokenValidator as LeagueBearerTokenValidator;
use League\OAuth2\Server\Exception\OAuthServerException;
use MediaWiki\Extension\OAuth\Backend\Utils;
use Psr\Http\Message\ServerRequestInterface;

class BearerTokenValidator extends LeagueBearerTokenValidator {

	/** @inheritDoc */
	public function validateAuthorization( ServerRequestInterface $request ) {
		$request = parent::validateAuthorization( $request );
		$sub = $request->getAttribute( 'oauth_user_id' );
		if ( str_starts_with( $sub, 'mw:' ) ) {
			// convert JWT subject to raw user ID
			$parts = explode( ':', $sub );
			$centralId = array_pop( $parts );
			$lookupScope = Utils::getCentralIdLookup()->getScope();
			if ( implode( ':', $parts ) === "mw:$lookupScope" && is_numeric( $centralId ) ) {
				$request = $request->withAttribute( 'oauth_user_id', $centralId );
			} else {
				throw OAuthServerException::accessDenied( 'Invalid subject scope' );
			}
		}
		return $request;
	}

}
