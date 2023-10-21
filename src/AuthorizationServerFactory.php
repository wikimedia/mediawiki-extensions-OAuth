<?php

namespace MediaWiki\Extension\OAuth;

use InvalidArgumentException;
use League\OAuth2\Server\AuthorizationServer;
use MediaWiki\Extension\OAuth\Repository\AccessTokenRepository;
use MediaWiki\Extension\OAuth\Repository\ClaimStore;
use MediaWiki\Extension\OAuth\Repository\ClientRepository;
use MediaWiki\Extension\OAuth\Repository\ScopeRepository;
use MediaWiki\MediaWikiServices;

class AuthorizationServerFactory {
	/** @var string */
	protected $privateKey;
	/** @var string */
	protected $encryptionKey;
	/** @var string */
	private $canonicalServer;

	/**
	 * @return static
	 */
	public static function factory() {
		$services = MediaWikiServices::getInstance();
		$extConfig = $services->getConfigFactory()->makeConfig( 'mwoauth' );
		$mainConfig = $services->getMainConfig();
		$privateKey = $extConfig->get( 'OAuth2PrivateKey' );
		$encryptionKey = $extConfig->get( 'OAuthSecretKey' ) ?? $mainConfig->get( 'SecretKey' );
		$canonicalServer = $mainConfig->get( 'CanonicalServer' );
		return new static( $privateKey, $encryptionKey, $canonicalServer );
	}

	/**
	 * @param string $privateKey
	 * @param string $encryptionKey
	 * @param string $canonicalServer
	 */
	public function __construct(
		string $privateKey,
		string $encryptionKey,
		string $canonicalServer
	) {
		$this->privateKey = $privateKey;
		$this->encryptionKey = trim( $encryptionKey );

		if ( $this->encryptionKey === '' ) {
			// Empty encryption key would not break the workflow, but would cause security issues
			throw new InvalidArgumentException( 'Encryption key must be set' );
		}

		$this->canonicalServer = $canonicalServer;
	}

	/**
	 * @return AuthorizationServer
	 */
	public function getAuthorizationServer() {
		return new AuthorizationServer(
			new ClientRepository(),
			new AccessTokenRepository( $this->canonicalServer ),
			new ScopeRepository(),
			$this->privateKey,
			$this->encryptionKey,
			null,
			new ClaimStore()
		);
	}
}
