<?php

namespace MediaWiki\Extension\OAuth\AuthorizationProvider\Grant;

use League\OAuth2\Server\Entities\AccessTokenEntityInterface;
use MediaWiki\Extension\OAuth\Entity\ClientEntity;
use MediaWiki\Logger\LoggerFactory;

/**
 * @require-extends  \League\OAuth2\Server\Grant\AbstractGrant
 */
trait AccessTokenLogger {

	abstract public function getIdentifier(): string;

	private function logAccessToken(
		AccessTokenEntityInterface $accessToken,
		ClientEntity $client,
	): void {
		LoggerFactory::getInstance( 'OAuth' )->info(
			'OAuth 2: access token returned via {grant} flow for {consumer_key}',
			[
				'grant' => $this->getIdentifier(),
				'userId' => $accessToken->getUserIdentifier() ?? '-',
			] + $client->getLogContext()
		);
	}

}
