<?php

namespace MediaWiki\Extensions\OAuth\Rest\Handler;

use GuzzleHttp\Psr7\ServerRequest;
use League\OAuth2\Server\Middleware\ResourceServerMiddleware;
use League\OAuth2\Server\ResourceServer;
use MediaWiki\Extensions\OAuth\Entity\ClientEntity;
use MediaWiki\Extensions\OAuth\MWOAuthConsumer;
use MediaWiki\Extensions\OAuth\MWOAuthUtils;
use MediaWiki\Extensions\OAuth\Repository\AccessTokenRepository;
use MediaWiki\Extensions\OAuth\Response;
use MediaWiki\Extensions\OAuth\UserStatementProvider;
use MediaWiki\MediaWikiServices;
use MediaWiki\Rest\Handler;
use MediaWiki\Rest\HttpException;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use FormatJson;
use User;
use MWException;

class Resource extends Handler {
	const TYPE_PROFILE = 'profile';

	/** @var ResourceServerMiddleware */
	protected $middleware;
	/** @var User */
	protected $user;
	/** @var ClientEntity */
	protected $client;

	/**
	 * @return static
	 */
	public static function factory() {
		$config = MediaWikiServices::getInstance()->getConfigFactory()->makeConfig( 'mwoauth' );
		return new static( $config->get( 'OAuth2PublicKey' ) );
	}

	/**
	 * @param string $publicKey
	 */
	protected function __construct( $publicKey ) {
		$accessTokenRepository = new AccessTokenRepository();

		$server = new ResourceServer(
			$accessTokenRepository,
			$publicKey
		);
		$this->middleware = new ResourceServerMiddleware( $server );
	}

	/**
	 * @return ResponseInterface
	 */
	public function execute() {
		$response = new Response();
		$request = ServerRequest::fromGlobals()->withHeader(
			'authorization',
			$this->getRequest()->getHeader( 'authorization' )
		);

		$callback = [ $this, 'doExecuteProtected' ];
		// To be able to invoke directly
		$middleware = $this->middleware;
		$middleware( $request, $response, function ( $request, $response ) use ( $callback ) {
			return $callback( $request, $response );
		} );

		return $response;
	}

	/**
	 * @param ServerRequestInterface $request
	 * @param ResponseInterface $response
	 * @throws HttpException
	 * @return ResponseInterface
	 */
	protected function doExecuteProtected( $request, $response ) {
		// Set authorized user to global context
		$this->setUser( $request );
		$this->setClient( $request );
		// Hint: $request also has attribute 'oauth_access_token_id'

		$type = $this->getRequest()->getPathParam( '{type}' );

		// If there is a need for multiple endpoints, this routing
		// should be handled more robustly
		if ( $type === static::TYPE_PROFILE ) {
			return $this->getProfile( $request, $response );
		}

		throw new HttpException( 'Invalid resource type', 400 );
	}

	/**
	 * Set authorized user to the global context
	 *
	 * @param ServerRequestInterface $request
	 * @throws HttpException
	 */
	private function setUser( ServerRequestInterface $request ) {
		$userId = $request->getAttribute( 'oauth_user_id', 0 );
		if ( $userId === 0 ) {
			throw new HttpException( 'User represented by given access token is invalid', 403 );
		}

		try {
			$user = MWOAuthUtils::getLocalUserFromCentralId( $userId );
		} catch ( MWException $ex ) {
			throw new HttpException( $ex->getMessage(), 403 );
		}

		// At this point user could be set on a global context, if necessary, or session created
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
	 * Return appropriate profile info based on approved scopes
	 * Very simple implementation due to very simple scopes
	 *
	 * @param ServerRequestInterface $request
	 * @param ResponseInterface $response
	 * @return ResponseInterface
	 * @throws HttpException
	 */
	private function getProfile( $request, $response ) {
		// Intersection between approved and requested scopes
		$scopes = $request->getAttribute( 'oauth_scopes', [] );
		$userStatementProvider = UserStatementProvider::factory(
			$this->user,
			$this->client,
			$scopes
		);

		try {
			$statement = $userStatementProvider->getUserProfile();
		} catch ( MWException $ex ) {
			throw new HttpException( $ex->getMessage(), $ex->getCode() );
		}

		$response->getBody()->write( FormatJson::encode( $statement ) );
		return $response;
	}
}
