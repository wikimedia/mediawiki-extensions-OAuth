<?php

namespace MediaWiki\Extensions\OAuth\Repository;

use InvalidArgumentException;
use League\OAuth2\Server\Repositories\ClientRepositoryInterface;
use MediaWiki\Extensions\OAuth\Entity\ClientEntity;
use MediaWiki\Extensions\OAuth\MWOAuthUtils;

class ClientRepository implements ClientRepositoryInterface {

	/**
	 * Get a client.
	 *
	 * @param string $clientIdentifier The client's identifier
	 *
	 * @return ClientEntity|bool
	 */
	public function getClientEntity( $clientIdentifier ) {
		$client = ClientEntity::newFromKey(
			MWOAuthUtils::getCentralDB( DB_REPLICA ),
			$clientIdentifier
		);
		if ( !$client instanceof ClientEntity ) {
			return false;
		}

		return $client;
	}

	/**
	 * @param int $clientId
	 * @return ClientEntity|bool
	 */
	public function getClientEntityByDBId( $clientId ) {
		$client = ClientEntity::newFromId( MWOAuthUtils::getCentralDB( DB_REPLICA ), $clientId );
		if ( !$client instanceof ClientEntity ) {
			return false;
		}

		return $client;
	}

	/**
	 * Validate a client's secret.
	 *
	 * @param string $clientIdentifier The client's identifier
	 * @param null|string $clientSecret The client's secret (if sent)
	 * @param null|string $grantType The type of grant the client is using (if sent)
	 *
	 * @return bool
	 * @throws InvalidArgumentException
	 */
	public function validateClient( $clientIdentifier, $clientSecret, $grantType ) {
		$client = $this->getClientEntity( $clientIdentifier );
		if ( !$client || !$client instanceof ClientEntity ) {
			throw new InvalidArgumentException(
				"Client with identifier $clientIdentifier does not exist!"
			);
		}

		return $client->validate( $clientSecret, $grantType );
	}
}
