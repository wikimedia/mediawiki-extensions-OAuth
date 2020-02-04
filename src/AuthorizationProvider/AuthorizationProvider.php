<?php

namespace MediaWiki\Extensions\OAuth\AuthorizationProvider;

use Config;
use DateInterval;
use Exception;
use League\OAuth2\Server\AuthorizationServer;
use League\OAuth2\Server\Grant\GrantTypeInterface;
use MediaWiki\Extensions\OAuth\AuthorizationServerFactory;
use MediaWiki\Extensions\OAuth\Repository\AuthCodeRepository;
use MediaWiki\Extensions\OAuth\Repository\RefreshTokenRepository;
use MediaWiki\Logger\LoggerFactory;
use MediaWiki\MediaWikiServices;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Log\LoggerInterface;
use User;

abstract class AuthorizationProvider implements IAuthorizationProvider {
	/**
	 * @var AuthorizationServer
	 */
	protected $server;

	/**
	 * @var Config|null
	 */
	protected $config;

	/**
	 * @var User
	 */
	protected $user;

	/**
	 * @var LoggerInterface
	 */
	protected $logger;

	/**
	 * @var GrantTypeInterface
	 */
	protected $grant;

	/**
	 * @return AuthorizationProvider
	 * @throws Exception
	 */
	public static function factory() {
		$services = MediaWikiServices::getInstance();
		$config = $services->getConfigFactory()->makeConfig( 'mwoauth' );
		$serverFactory = AuthorizationServerFactory::factory();
		$logger = LoggerFactory::getInstance( 'OAuth' );

		// @phan-suppress-next-line PhanTypeInstantiateAbstractStatic
		return new static( $config, $serverFactory->getAuthorizationServer(), $logger );
	}

	/**
	 * @param Config $config
	 * @param AuthorizationServer $server
	 * @param LoggerInterface $logger
	 * @throws Exception
	 */
	public function __construct( $config, $server, $logger ) {
		$this->config = $config;
		$this->server = $server;
		$this->logger = $logger;

		$this->decorateAuthServer();
	}

	/**
	 * @inheritDoc
	 */
	public function setUser( User $user ) {
		$this->user = $user;
	}

	/**
	 * @inheritDoc
	 */
	public function needsUserApproval() {
		return false;
	}

	/**
	 * @return GrantTypeInterface
	 */
	abstract protected function getGrant() : GrantTypeInterface;

	/**
	 * @return GrantTypeInterface
	 */
	protected function getGrantSingleton() {
		if ( !$this->grant ) {
			$this->grant = $this->getGrant();
		}

		return $this->grant;
	}

	/**
	 * @throws Exception
	 */
	protected function decorateAuthServer() {
		$grant = $this->getGrantSingleton();
		$grant->setRefreshTokenTTL( $this->getRefreshTokenTTL() );
		$this->server->setDefaultScope( '#default' );
		$this->server->enableGrantType(
			$grant,
			$this->getGrantExpirationInterval()
		);
	}

	/**
	 * @return RefreshTokenRepository
	 */
	protected function getRefreshTokenRepo() {
		/** @var RefreshTokenRepository $repo */
		$repo = RefreshTokenRepository::factory();
		return $repo;
	}

	/**
	 * @return AuthCodeRepository
	 */
	protected function getAuthCodeRepo() {
		/** @var AuthCodeRepository $repo */
		$repo = AuthCodeRepository::factory();
		return $repo;
	}

	/**
	 * @return DateInterval
	 * @throws Exception
	 */
	protected function getGrantExpirationInterval() {
		$intervalSpec = 'PT1H';
		if ( $this->config->has( 'OAuth2GrantExpirationInterval' ) ) {
			$intervalSpec = $this->parseExpiration( $this->config->get( 'OAuth2GrantExpirationInterval' ) );
		}
		return new DateInterval( $intervalSpec );
	}

	/**
	 * @return DateInterval
	 * @throws Exception
	 */
	protected function getRefreshTokenTTL() {
		$intervalSpec = 'PT1M';
		if ( $this->config->has( 'OAuth2RefreshTokenTTL' ) ) {
			$intervalSpec = $this->parseExpiration( $this->config->get( 'OAuth2RefreshTokenTTL' ) );
		}

		return new DateInterval( $intervalSpec );
	}

	/**
	 * @param ServerRequestInterface $request
	 * @param string $default
	 * @return mixed|string
	 */
	protected function getClientIdFromRequest( ServerRequestInterface $request, $default = '' ) {
		$params = (array)$request->getParsedBody();

		return $params['client_id'] ?? $default;
	}

	private function parseExpiration( $expiration ) {
		if ( $expiration === false || $expiration === 'infinity' ) {
			// Effectively non-expiring tokens
			$expiration = 'P292277000000Y';
		}

		return $expiration;
	}
}
