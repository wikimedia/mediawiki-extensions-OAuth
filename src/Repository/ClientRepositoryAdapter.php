<?php

namespace MediaWiki\Extension\OAuth\Repository;

use League\OAuth2\Server\Entities\ClientEntityInterface;
use League\OAuth2\Server\Repositories\ClientRepositoryInterface;
use MediaWiki\Extension\OAuth\Backend\NormalizedOAuthException;
use MediaWiki\Extension\OAuth\Entity\ClientEntity;
use MediaWiki\Extension\OAuth\Lib\OAuthException;

/**
 * Lets a ConsumerRepository be used as a league/oauth2-server ClientRepository.
 */
class ClientRepositoryAdapter implements ClientRepositoryInterface {

	public function __construct(
		private ConsumerRepositoryInterface $repository,
	) {
	}

	/** @inheritDoc */
	public function getClientEntity( string $clientIdentifier ): ?ClientEntityInterface {
		$client = $this->repository->getByKey( $clientIdentifier );
		if ( !$client instanceof ClientEntity ) {
			return null;
		}
		return $client;
	}

	/**
	 * {@inheritDoc}
	 * @return bool True if the secret is valid for the given client and grant type.
	 *   False if the client exists but the secret is not valid.
	 * @throws OAuthException When the client does not exist.
	 */
	public function validateClient(
		string $clientIdentifier,
		?string $clientSecret,
		?string $grantType
	): bool {
		$client = $this->getClientEntity( $clientIdentifier );
		if ( !$client || !$client instanceof ClientEntity ) {
			throw new NormalizedOAuthException( "Client with identifier '{clientIdentifier}' does not exist!", [
				'clientIdentifier' => $clientIdentifier,
			] );
		}
		return $client->validate( $clientSecret, $grantType );
	}
}
