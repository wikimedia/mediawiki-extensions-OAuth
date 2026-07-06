<?php

namespace MediaWiki\Extension\OAuth;

use InvalidArgumentException;
use League\OAuth2\Server\AuthorizationServer;
use League\OAuth2\Server\Repositories\ClientRepositoryInterface;
use MediaWiki\Extension\OAuth\Backend\Utils;
use MediaWiki\Extension\OAuth\Repository\AccessTokenRepository;
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
		$issuer = Utils::getJwtIssuer();
		return new static( $clientRepository, $privateKey, $encryptionKey, $issuer );
	}

	public function __construct(
		protected ClientRepositoryInterface $clientRepository,
		protected string $privateKey,
		protected string $encryptionKey,
		private readonly string $issuer
	) {
		$this->encryptionKey = trim( $this->encryptionKey );
		if ( $this->encryptionKey === '' ) {
			// Empty encryption key would not break the workflow, but would cause security issues
			throw new InvalidArgumentException( 'Encryption key must be set' );
		}
	}

	public function getAuthorizationServer(): AuthorizationServer {
		return new AuthorizationServer(
			$this->clientRepository,
			new AccessTokenRepository( $this->issuer ),
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
