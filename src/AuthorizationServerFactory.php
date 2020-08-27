<?php

namespace MediaWiki\Extensions\OAuth;

use InvalidArgumentException;
use League\OAuth2\Server\AuthorizationServer;
use MediaWiki\Extensions\OAuth\Repository\AccessTokenRepository;
use MediaWiki\Extensions\OAuth\Repository\ClaimStore;
use MediaWiki\Extensions\OAuth\Repository\ClientRepository;
use MediaWiki\Extensions\OAuth\Repository\ScopeRepository;
use MediaWiki\MediaWikiServices;

class AuthorizationServerFactory {
	/** @var string */
	protected $privateKey;
	/** @var string */
	protected $encryptionKey;

	/**
	 * @return static
	 */
	public static function factory() {
		$services = MediaWikiServices::getInstance();
		$extConfig = $services->getConfigFactory()->makeConfig( 'mwoauth' );
		$mainConfig = $services->getMainConfig();
		$privateKey = $extConfig->get( 'OAuth2PrivateKey' );
		$encryptionKey = $extConfig->get( 'OAuthSecretKey' ) ?? $mainConfig->get( 'SecretKey' );

		return new static( $privateKey, $encryptionKey );
	}

	/**
	 * @param string $privateKey
	 * @param string $encryptionKey
	 */
	public function __construct( $privateKey, $encryptionKey ) {
		$this->privateKey = $privateKey;
		$this->encryptionKey = trim( $encryptionKey );

		if ( empty( $this->encryptionKey ) ) {
			// Empty encryption key would not break the workflow, but would cause security issues
			throw new InvalidArgumentException( 'Encryption key must be set' );
		}
	}

	/**
	 * @return AuthorizationServer
	 */
	public function getAuthorizationServer() {
		return new AuthorizationServer(
			new ClientRepository(),
			new AccessTokenRepository(),
			new ScopeRepository(),
			$this->privateKey,
			$this->encryptionKey,
			null,
			new ClaimStore()
		);
	}
}
