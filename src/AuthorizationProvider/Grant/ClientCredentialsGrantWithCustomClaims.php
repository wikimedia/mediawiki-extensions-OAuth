<?php

namespace MediaWiki\Extension\OAuth\AuthorizationProvider\Grant;

use DateInterval;
use League\OAuth2\Server\Entities\AccessTokenEntityInterface;
use League\OAuth2\Server\Entities\ClientEntityInterface;
use League\OAuth2\Server\Exception\OAuthServerException;
use League\OAuth2\Server\Grant\ClientCredentialsGrant;
use LogicException;
use MediaWiki\Extension\OAuth\AuthorizationProvider\GrantWithCustomClaims;
use MediaWiki\Extension\OAuth\Entity\AccessTokenEntity;
use MediaWiki\Extension\OAuth\Entity\ClientEntity;
use Wikimedia\Assert\Assert;

class ClientCredentialsGrantWithCustomClaims extends ClientCredentialsGrant {

	use AccessTokenLogger;
	use GrantWithCustomClaims;

	protected function issueAccessToken(
		DateInterval $accessTokenTTL,
		ClientEntityInterface $client,
		?string $userIdentifier,
		array $scopes = []
	): AccessTokenEntityInterface {
		Assert::parameter( $userIdentifier === null, '$userIdentifier', 'must be null' );
		if ( !( $client instanceof ClientEntity ) ) {
			throw new LogicException( 'Impossible but makes static checkers happy' );
		}
		$effectiveUserIdentifier = $client->clientCredentialsAuthenticateAsOwner()
			? (string)$client->getUserId()
			: $userIdentifier;

		$accessToken = parent::issueAccessToken( $accessTokenTTL, $client, $effectiveUserIdentifier, $scopes );
		if ( !( $accessToken instanceof AccessTokenEntity ) ) {
			throw new LogicException( 'Impossible but makes static checkers happy' );
		}

		if ( $client->clientCredentialsAuthenticateAsOwner() && !$accessToken->getApproval() ) {
			throw OAuthServerException::accessDenied(
				'Client credentials owner authorization has been revoked'
			);
		}

		if ( $effectiveUserIdentifier === null ) {
			// T417278 set user ID so the JWT has a `sub` field and doesn't break rate limiting etc.
			// that relies on that field. This does *not* result in the user being authenticated
			// (within MediaWiki at least) - that's based on the oauth2_access_tokens entry having
			// an acceptance ID, the acceptance ID is loaded in
			// AccessTokenEntity::setApprovalFromClientScopesUser(), which is called in the
			// constructor, and the user ID is still unset at that point.
			$accessToken->setUserIdentifier( (string)$client->getUserId() );
		}

		$this->addCustomClaims( $client, $effectiveUserIdentifier, $accessToken );

		$this->logAccessToken( $accessToken, $client );

		return $accessToken;
	}

}
