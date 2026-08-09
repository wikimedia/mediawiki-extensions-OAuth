<?php

namespace MediaWiki\Extension\OAuth;

use InvalidArgumentException;
use League\OAuth2\Server\AuthorizationServer;
use League\OAuth2\Server\Repositories\ClientRepositoryInterface;
use MediaWiki\Extension\OAuth\Repository\ClientRepositoryAdapter;
use MediaWiki\Extension\OAuth\Repository\ScopeRepository;
use MediaWiki\MediaWikiServices;

class AuthorizationServerFactory {

	public static function factory(): static {
		$services = MediaWikiServices::getInstance();
		$consumerRepository = OAuthServices::wrap( $services )->getConsumerRepository();
		$clientRepository = new ClientRepositoryAdapter( $consumerRepository );
		$extConfig = $services->getConfigFactory()->makeConfig( 'mwoauth' );
		$mainConfig = $services->getMainConfig();
		$privateKey = $extConfig->get( 'OAuth2PrivateKey' );
		$encryptionKey = $extConfig->get( 'OAuthSecretKey' ) ?? $mainConfig->get( 'SecretKey' );
		return new static( $clientRepository, $privateKey, $encryptionKey );
	}

	public function __construct(
		protected ClientRepositoryInterface $clientRepository,
		protected string $privateKey,
		protected string $encryptionKey
	) {
		$this->encryptionKey = trim( $this->encryptionKey );
		if ( $this->encryptionKey === '' ) {
			// Empty encryption key would not break the workflow, but would cause security issues
			throw new InvalidArgumentException( 'Encryption key must be set' );
		}
	}

	public function getAuthorizationServer(): AuthorizationServer {
		$accessTokenRepository = OAuthServices::wrap( MediaWikiServices::getInstance() )
			->getAccessTokenRepository();
		return new AuthorizationServer(
			$this->clientRepository,
			$accessTokenRepository,
			new ScopeRepository(),
			$this->privateKey,
			$this->encryptionKey,
			null,
			// TODO: This should be injected here.
			// See https://github.com/thephpleague/oauth2-server/pull/1122
			// new ClaimStore()
		);
	}
}
