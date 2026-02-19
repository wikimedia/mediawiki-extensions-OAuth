<?php

namespace MediaWiki\Extension\OAuth\AuthorizationProvider\Grant;

use DateInterval;
use League\OAuth2\Server\Entities\AccessTokenEntityInterface;
use League\OAuth2\Server\Entities\ClientEntityInterface;
use League\OAuth2\Server\Grant\AuthCodeGrant;
use MediaWiki\Extension\OAuth\AuthorizationProvider\GrantWithCustomClaims;

class AuthCodeGrantWithCustomClaims extends AuthCodeGrant {

	use GrantWithCustomClaims;

	protected function issueAccessToken(
		DateInterval $accessTokenTTL,
		ClientEntityInterface $client,
		?string $userIdentifier,
		array $scopes = []
	): AccessTokenEntityInterface {
		$accessToken = parent::issueAccessToken( $accessTokenTTL, $client, $userIdentifier, $scopes );

		// @phan-suppress-next-line PhanTypeMismatchArgumentSuperType
		$this->addCustomClaims( $client, $userIdentifier, $accessToken );

		return $accessToken;
	}

}
