<?php

namespace MediaWiki\Extension\OAuth\AuthorizationProvider\Grant;

use DateInterval;
use League\OAuth2\Server\Entities\AccessTokenEntityInterface;
use League\OAuth2\Server\Entities\ClientEntityInterface;
use League\OAuth2\Server\Grant\ClientCredentialsGrant;
use LogicException;
use MediaWiki\Extension\OAuth\AuthorizationProvider\GrantWithCustomClaims;
use MediaWiki\Extension\OAuth\Entity\AccessTokenEntity;
use MediaWiki\Extension\OAuth\Entity\ClientEntity;
use Wikimedia\Assert\Assert;

class ClientCredentialsGrantWithCustomClaims extends ClientCredentialsGrant {

	use GrantWithCustomClaims;

	protected function issueAccessToken(
		DateInterval $accessTokenTTL,
		ClientEntityInterface $client,
		?string $userIdentifier,
		array $scopes = []
	): AccessTokenEntityInterface {
		Assert::parameter( $userIdentifier === null, '$userIdentifier', 'must be null' );
		if ( !( $client instanceof ClientEntity ) ) {
			throw new LogicException( 'Unexpected type' );
		}

		$accessToken = parent::issueAccessToken( $accessTokenTTL, $client, $userIdentifier, $scopes );
		if ( !( $accessToken instanceof AccessTokenEntity ) ) {
			throw new LogicException( 'Unexpected type' );
		}

		// T417278 set user ID so the JWT has a `sub` field and doesn't break rate limiting etc.
		// that relies on that field. This does *not* result in the user being authenticated
		// (within MediaWiki at least) - that's based on the oauth2_access_tokens entry having
		// an acceptance ID, the acceptance ID is loaded in
		// AccessTokenEntity::setApprovalFromClientScopesUser(), which is called in the
		// constructor, and the user ID is still unset at that point.
		$accessToken->setUserIdentifier( (string)$client->getUserId() );

		$this->addCustomClaims( $client, $userIdentifier, $accessToken );

		return $accessToken;
	}

}
