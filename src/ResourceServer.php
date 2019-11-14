<?php

namespace MediaWiki\Extensions\OAuth;

use League\OAuth2\Server\Entities\ScopeEntityInterface;
use League\OAuth2\Server\Middleware\ResourceServerMiddleware;
use MediaWiki\Extensions\OAuth\Entity\ClientEntity;
use MediaWiki\Extensions\OAuth\Entity\ScopeEntity;
use MediaWiki\Extensions\OAuth\Repository\AccessTokenRepository;
use MediaWiki\Extensions\OAuth\Repository\ScopeRepository;
use MediaWiki\MediaWikiServices;
use MediaWiki\Rest\HttpException;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use User;
use MWException;
use WebRequest;

class ResourceServer {
	/** @var ResourceServerMiddleware */
	protected $middleware;
	/** @var User */
	protected $user;
	/** @var ClientEntity */
	protected $client;
	/** @var ScopeEntity[] */
	protected $scopes;
	/** @var string */
	protected $accessTokenId;
	/** @var bool */
	protected $verified = false;

	public static function factory() {
		$config = MediaWikiServices::getInstance()->getConfigFactory()->makeConfig( 'mwoauth' );
		return new static( $config->get( 'OAuth2PublicKey' ) );
	}

	/**
	 * @param string $publicKey
	 */
	protected function __construct( $publicKey ) {
		$accessTokenRepository = new AccessTokenRepository();

		$server = new \League\OAuth2\Server\ResourceServer(
			$accessTokenRepository,
			$publicKey
		);
		$this->middleware = new ResourceServerMiddleware( $server );
	}

	/**
	 * Check if the request is an OAuth2 request
	 *
	 * @param WebRequest|ServerRequestInterface $request
	 * @return bool
	 */
	public static function isOAuth2Request( $request ) {
		$authHeader = $request->getHeader( 'authorization' );

		// Normalize to array
		if ( is_string( $authHeader ) ) {
			$authHeader = [ $authHeader ];
		}
		if ( !empty( $authHeader ) && strpos( $authHeader[0], 'Bearer' ) === 0 ) {
			return true;
		}
		return false;
	}

	/**
	 * @param ServerRequestInterface $request
	 * @param ResponseInterface $response
	 * @param callable $callback
	 * @return ResponseInterface
	 */
	public function verify( $request, $response, $callback ) {
		$this->verified = false;

		return $this->middleware->__invoke(
			$request,
			$response,
			function ( $request, $response ) use ( $callback ) {
				$this->setVerifiedInfo( $request );
				return $callback( $request, $response );
			}
		);
	}

	/**
	 * @return User
	 * @throws MWOAuthException
	 */
	public function getUser() {
		$this->assertVerified();
		return $this->user;
	}

	/**
	 * @return ClientEntity
	 * @throws MWOAuthException
	 */
	public function getClient() {
		$this->assertVerified();
		return $this->client;
	}

	/**
	 * @return ScopeEntity[]
	 * @throws MWOAuthException
	 */
	public function getScopes() {
		$this->assertVerified();
		return $this->scopes;
	}

	/**
	 * Get access token this request was made with
	 *
	 * @return string
	 * @throws MWOAuthException
	 */
	public function getAccessTokenId() {
		$this->assertVerified();
		return $this->accessTokenId;
	}

	/**
	 * Check if the scope is allowed
	 *
	 * @param string|ScopeEntityInterface $scope
	 * @return bool
	 * @throws MWOAuthException
	 */
	public function isScopeAllowed( $scope ) {
		$this->assertVerified();

		if ( $scope instanceof ScopeEntityInterface ) {
			$scope = $scope->getIdentifier();
		}

		return isset( $this->scopes[$scope] );
	}

	/**
	 * Read out the verified request and get relevant information
	 *
	 * @param ServerRequestInterface $request
	 * @throws HttpException
	 */
	public function setVerifiedInfo( ServerRequestInterface $request ) {
		$this->setUser( $request );
		$this->setClient( $request );
		$this->setScopes( $request );
		$this->setAccessTokenId( $request );

		$this->verified = true;
	}

	/**
	 * Set authorized user to the global context
	 *
	 * @param ServerRequestInterface $request
	 * @throws HttpException
	 */
	private function setUser( ServerRequestInterface $request ) {
		$userId = $request->getAttribute( 'oauth_user_id', 0 );
		if ( !$userId ) {
			// Set anon user when no user id is present in the AT (machine grant)
			$this->user = User::newFromId( 0 );
			return;
		}

		try {
			$user = MWOAuthUtils::getLocalUserFromCentralId( $userId );
		} catch ( MWException $ex ) {
			throw new HttpException( $ex->getMessage(), 403 );
		}

		$this->user = $user;
	}

	/**
	 * Set the ClientEntity from validated request
	 *
	 * @param ServerRequestInterface $request
	 * @throws HttpException
	 */
	private function setClient( ServerRequestInterface $request ) {
		$this->client = ClientEntity::newFromKey(
			MWOAuthUtils::getCentralDB( DB_REPLICA ),
			$request->getAttribute( 'oauth_client_id' )
		);
		if ( !$this->client || $this->client->getOAuthVersion() !== MWOAuthConsumer::OAUTH_VERSION_2 ) {
			throw new HttpException( 'Client represented by given access token is invalid', 403 );
		}
	}

	/**
	 * Set validated scopes
	 *
	 * @param ServerRequestInterface $request
	 */
	private function setScopes( ServerRequestInterface $request ) {
		$scopeNames = $request->getAttribute( 'oauth_scopes', [] );
		$scopeRepo = new ScopeRepository();
		foreach ( $scopeNames as $scopeName ) {
			$scope = $scopeRepo->getScopeEntityByIdentifier( $scopeName );
			if ( !$scope ) {
				continue;
			}
			$this->scopes[$scope->getIdentifier()] = $scope;
		}
	}

	/**
	 * Set the access token this request was made with
	 *
	 * @param ServerRequestInterface $request
	 */
	private function setAccessTokenId( ServerRequestInterface $request ) {
		$this->accessTokenId = $request->getAttribute( 'oauth_access_token_id' );
	}

	private function assertVerified() {
		if ( !$this->verified ) {
			throw new MWOAuthException( 'mwoauth-oauth2-error-request-not-verified' );
		}
	}
}
