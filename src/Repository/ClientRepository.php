<?php

namespace MediaWiki\Extension\OAuth\Repository;

use League\OAuth2\Server\Repositories\ClientRepositoryInterface;
use MediaWiki\Extension\OAuth\Backend\Utils;
use MediaWiki\Extension\OAuth\Entity\ClientEntity;
use Wikimedia\NormalizedException\NormalizedException;

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
			Utils::getOAuthDB( DB_REPLICA ),
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
		$client = ClientEntity::newFromId( Utils::getOAuthDB( DB_REPLICA ), $clientId );
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
	 * @throws NormalizedException
	 */
	public function validateClient( $clientIdentifier, $clientSecret, $grantType ) {
		$client = $this->getClientEntity( $clientIdentifier );
		if ( !$client || !$client instanceof ClientEntity ) {
			throw new NormalizedException(
				"Client with identifier '{clientIdentifier}' does not exist!",
				[
					'clientIdentifier' => $clientIdentifier,
				]
			);
		}

		return $client->validate( $clientSecret, $grantType );
	}
}
