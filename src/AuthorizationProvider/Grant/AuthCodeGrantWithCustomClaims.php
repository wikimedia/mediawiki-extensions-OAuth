<?php

namespace MediaWiki\Extension\OAuth\AuthorizationProvider\Grant;

use DateInterval;
use League\OAuth2\Server\Entities\AccessTokenEntityInterface;
use League\OAuth2\Server\Entities\ClientEntityInterface;
use League\OAuth2\Server\Grant\AuthCodeGrant;
use LogicException;
use MediaWiki\Extension\OAuth\AuthorizationProvider\GrantWithCustomClaims;
use MediaWiki\Extension\OAuth\Entity\AccessTokenEntity;
use MediaWiki\Extension\OAuth\Entity\ClientEntity;

class AuthCodeGrantWithCustomClaims extends AuthCodeGrant {

	use AccessTokenLogger;
	use GrantWithCustomClaims;

	protected function issueAccessToken(
		DateInterval $accessTokenTTL,
		ClientEntityInterface $client,
		?string $userIdentifier,
		array $scopes = []
	): AccessTokenEntityInterface {
		$accessToken = parent::issueAccessToken( $accessTokenTTL, $client, $userIdentifier, $scopes );

		if ( !( $client instanceof ClientEntity )
			|| !( $accessToken instanceof AccessTokenEntity )
		) {
			throw new LogicException( 'Impossible but makes static checkers happy' );
		}

		$this->addCustomClaims( $client, $userIdentifier, $accessToken );

		$this->logAccessToken( $accessToken, $client );

		return $accessToken;
	}

}
