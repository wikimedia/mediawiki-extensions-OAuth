<?php

namespace MediaWiki\Extensions\OAuth\Rest\Handler;

use MediaWiki\Extensions\OAuth\AuthorizationProvider\AccessToken as AccessTokenProvider;
use MediaWiki\Extensions\OAuth\AuthorizationProvider\Grant\AuthorizationCodeAuthorization;
use MediaWiki\MediaWikiServices;
use MediaWiki\Rest\Handler;
use MediaWiki\Rest\HttpException;
use Config;
use RequestContext;
use User;

abstract class AuthenticationHandler extends Handler {

	/**
	 * @var User
	 */
	protected $user;

	/**
	 * @var Config
	 */
	protected $config;

	/**
	 * @return AuthenticationHandler
	 */
	public static function factory() {
		$user = RequestContext::getMain()->getUser();
		$config = MediaWikiServices::getInstance()->getConfigFactory()->makeConfig( 'mwoauth' );
		// @phan-suppress-next-line PhanTypeInstantiateAbstractStatic
		return new static( $user, $config );
	}

	/**
	 * @param User $user
	 * @param Config $config
	 */
	protected function __construct( User $user, Config $config ) {
		$this->user = $user;
		$this->config = $config;
	}

	/**
	 * We do not want any permission checks
	 *
	 * @return bool
	 */
	public function needsReadAccess() {
		return false;
	}

	/**
	 * We do not want any permission checks
	 *
	 * @return bool
	 */
	public function needsWriteAccess() {
		return false;
	}

	/**
	 * @throws HttpException
	 * @return AccessTokenProvider|AuthorizationCodeAuthorization
	 */
	protected function getAuthorizationProvider() {
		$grantKey = $this->getGrantKey();
		$validated = $this->getValidatedParams();
		$grantKeyValue = $validated[$grantKey];

		$class = $this->getGrantClass( $grantKeyValue );
		if ( !$class || !is_callable( [ $class, 'factory' ] ) ) {
			throw new HttpException( 'invalid_request', 400 );
		}

		/** @var AccessTokenProvider|AuthorizationCodeAuthorization $authProvider */
		$authProvider = $class::factory();
		'@phan-var AccessTokenProvider|AuthorizationCodeAuthorization $authProvider';
		return $authProvider;
	}

	/**
	 * @param array $query
	 * @return string
	 */
	protected function getQueryParamsCgi( $query = [] ) {
		$queryParams = $this->getRequest()->getQueryParams();
		unset( $queryParams['title'] );

		$queryParams = array_merge( $queryParams, $query );
		return wfArrayToCgi( $queryParams );
	}

	/**
	 * @return string
	 */
	abstract protected function getGrantKey();

	/**
	 * @param string $grantKey
	 * @return string|false
	 */
	abstract protected function getGrantClass( $grantKey );
}
