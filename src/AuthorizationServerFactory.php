<?php

namespace MediaWiki\Extension\OAuth;

use InvalidArgumentException;
use League\OAuth2\Server\AuthorizationServer;
use MediaWiki\Extension\OAuth\Backend\Utils;
use MediaWiki\Extension\OAuth\Repository\AccessTokenRepository;
use MediaWiki\Extension\OAuth\Repository\ClaimStore;
use MediaWiki\Extension\OAuth\Repository\ClientRepository;
use MediaWiki\Extension\OAuth\Repository\ScopeRepository;
use MediaWiki\MediaWikiServices;

class AuthorizationServerFactory {

	public static function factory(): static {
		$services = MediaWikiServices::getInstance();
		$extConfig = $services->getConfigFactory()->makeConfig( 'mwoauth' );
		$mainConfig = $services->getMainConfig();
		$privateKey = $extConfig->get( 'OAuth2PrivateKey' );
		$encryptionKey = $extConfig->get( 'OAuthSecretKey' ) ?? $mainConfig->get( 'SecretKey' );
		$issuer = Utils::getJwtIssuer();
		return new static( $privateKey, $encryptionKey, $issuer );
	}

	public function __construct(
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
			new ClientRepository(),
			new AccessTokenRepository( $this->issuer ),
			new ScopeRepository(),
			$this->privateKey,
			$this->encryptionKey,
			null,
			new ClaimStore()
		);
	}
}
