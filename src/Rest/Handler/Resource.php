<?php

namespace MediaWiki\Extension\OAuth\Rest\Handler;

use GuzzleHttp\Psr7\ServerRequest;
use MediaWiki\Extension\OAuth\Backend\MWOAuthException;
use MediaWiki\Extension\OAuth\ResourceServer;
use MediaWiki\Extension\OAuth\Response;
use MediaWiki\Extension\OAuth\UserStatementProvider;
use MediaWiki\Json\FormatJson;
use MediaWiki\Rest\Handler;
use MediaWiki\Rest\HttpException;
use MWException;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Wikimedia\ParamValidator\ParamValidator;

/**
 * Handles the oauth2/resource/profile and oauth2/resource/scope endpoints, which return
 * information about the user and the grants of the application, respectively.
 */
class Resource extends Handler {

	/**
	 * (string) TYPE_PROFILE constant to specify the profile type of the resource.
	 */
	private const TYPE_PROFILE = 'profile';

	/**
	 * (string) TYPE_SCOPES constant to specify the scopes type of the resource.
	 */
	private const TYPE_SCOPES = 'scopes';

	/** @var ResourceServer */
	protected $resourceServer;

	/**
	 * @return static
	 */
	public static function factory() {
		return new static(
			ResourceServer::factory()
		);
	}

	/**
	 * @param ResourceServer $resourceServer
	 */
	protected function __construct( $resourceServer ) {
		$this->resourceServer = $resourceServer;
	}

	/**
	 * All access controls are handled over OAuth2
	 *
	 * @return bool
	 */
	public function needsReadAccess() {
		return false;
	}

	/**
	 * @return bool
	 */
	public function needsWriteAccess() {
		return false;
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
		return $this->resourceServer->verify( $request, $response, $callback );
	}

	/**
	 * @param ServerRequestInterface $request
	 * @param ResponseInterface $response
	 * @throws HttpException
	 * @return ResponseInterface
	 * @throws MWOAuthException
	 */
	public function doExecuteProtected( $request, $response ) {
		$type = $this->getRequest()->getPathParam( 'type' );

		switch ( $type ) {
			case self::TYPE_PROFILE:
				return $this->getProfile( $response );
			case self::TYPE_SCOPES:
				return $this->getScopes( $response );
		}

		throw new HttpException( 'Invalid resource type', 400 );
	}

	/**
	 * Return appropriate profile info based on approved scopes
	 *
	 * @param ResponseInterface $response
	 * @return ResponseInterface
	 * @throws HttpException
	 * @throws MWOAuthException
	 */
	private function getProfile( $response ) {
		// Intersection between approved and requested scopes
		$scopes = array_keys( $this->resourceServer->getScopes() );
		$userStatementProvider = UserStatementProvider::factory(
			$this->resourceServer->getUser(),
			$this->resourceServer->getClient(),
			$scopes
		);

		try {
			$profile = $userStatementProvider->getUserProfile();
		} catch ( MWException $ex ) {
			throw new HttpException( $ex->getMessage(), $ex->getCode() );
		}

		return $this->respond( $response, $profile );
	}

	/**
	 * Get all available scopes client application can use
	 *
	 * @param ResponseInterface $response
	 * @return ResponseInterface
	 * @throws MWOAuthException
	 */
	private function getScopes( $response ) {
		$grants = $this->resourceServer->getClient()->getGrants();
		return $this->respond( $response, [
			self::TYPE_SCOPES => $grants
		] );
	}

	/**
	 * @param ResponseInterface $response
	 * @param array $data
	 * @return ResponseInterface
	 */
	private function respond( $response, $data = [] ) {
		$response->withHeader( 'Content-Type', 'application/json' )
			->getBody()
			->write( FormatJson::encode( $data ) );
		return $response;
	}

	public function getParamSettings() {
		return [
			'type' => [
				self::PARAM_SOURCE => 'path',
				ParamValidator::PARAM_TYPE => [ self::TYPE_PROFILE, self::TYPE_SCOPES ],
				ParamValidator::PARAM_REQUIRED => true,
			],
		];
	}
}
