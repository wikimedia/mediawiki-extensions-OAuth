<?php

namespace MediaWiki\Extensions\OAuth\Rest\Handler;

use MediaWiki\Extensions\OAuth\Backend\MWOAuthException;
use MediaWiki\Extensions\OAuth\Repository\ScopeRepository;
use MediaWiki\Rest\Validator\Validator;
use MWRestrictions;
use Wikimedia\ParamValidator\ParamValidator;

class RequestClient extends AbstractClientHandler {

	/**
	 * @inheritDoc
	 */
	protected function getFixedParams(): array {
		return [
			'oauthVersion' => '2.0',
			'agreement' => true,
			'action' => 'propose',
			'granttype' => 'normal',
			'rsaKey' => '',
			'restrictions' => MWRestrictions::newDefault(),
		];
	}

	/**
	 * @inheritDoc
	 */
	public function getParamSettings() {
		return [
			'name' => [
				self::PARAM_SOURCE => 'post',
				ParamValidator::PARAM_TYPE => 'string',
				ParamValidator::PARAM_REQUIRED => true,
			],
			'version' => [
				self::PARAM_SOURCE => 'post',
				ParamValidator::PARAM_TYPE => [ '1.0', '2.0' ],
				ParamValidator::PARAM_REQUIRED => false,
				ParamValidator::PARAM_DEFAULT => '1.0',
			],
			'description' => [
				self::PARAM_SOURCE => 'post',
				ParamValidator::PARAM_TYPE => 'string',
				ParamValidator::PARAM_REQUIRED => true,
			],
			'wiki' => [
				self::PARAM_SOURCE => 'post',
				ParamValidator::PARAM_TYPE => 'string',
				ParamValidator::PARAM_REQUIRED => false,
				ParamValidator::PARAM_DEFAULT => '*',
			],
			'owner_only' => [
				self::PARAM_SOURCE => 'post',
				ParamValidator::PARAM_TYPE => 'boolean'
			],
			'callback_url' => [
				self::PARAM_SOURCE => 'post',
				ParamValidator::PARAM_TYPE => 'string',
				ParamValidator::PARAM_REQUIRED => false,
				ParamValidator::PARAM_DEFAULT => ''
			],
			'callback_is_prefix' => [
				self::PARAM_SOURCE => 'post',
				ParamValidator::PARAM_TYPE => 'boolean',
				ParamValidator::PARAM_REQUIRED => false,
				ParamValidator::PARAM_DEFAULT => false,
			],
			'email' => [
				self::PARAM_SOURCE => 'post',
				ParamValidator::PARAM_TYPE => 'string',
				ParamValidator::PARAM_REQUIRED => true,
			],
			'is_confidential' => [
				self::PARAM_SOURCE => 'post',
				ParamValidator::PARAM_TYPE => 'boolean',
				ParamValidator::PARAM_REQUIRED => true,
			],
			'grant_types'  => [
				self::PARAM_SOURCE => 'post',
				ParamValidator::PARAM_TYPE => 'string',
				ParamValidator::PARAM_REQUIRED => true,
				ParamValidator::PARAM_ISMULTI => true
			],
			'scopes' => [
				self::PARAM_SOURCE => 'post',
				ParamValidator::PARAM_TYPE => [
					'#default',
					'mwoauth-authonly',
					'mwoauth-authonlyprivate'
				],
				ParamValidator::PARAM_REQUIRED => true,
				ParamValidator::PARAM_ISMULTI => true
			]
		];
	}

	/**
	 * @inheritDoc
	 */
	protected function getUnifiedParams(): array {
		$params = parent::getUnifiedParams();
		return $this->adjustScopes( $params );
	}

	/**
	 * This is needed to adjust OAuth2 scope array to old grant/granttype params
	 *
	 * @param array $finalParams
	 * @return array
	 */
	private function adjustScopes( array $finalParams ): array {
		$scopeRepo = new ScopeRepository();
		$allowedScopes = $scopeRepo->getAllowedScopes();

		$scopes = array_filter( $finalParams['grants'], function ( $scope ) use ( $allowedScopes ) {
			return in_array( $scope, $allowedScopes );
		} );

		if ( $this->findAndRemoveScope( 'mwoauth-authonly', $scopes ) ) {
			$finalParams['granttype'] = 'authonly';
		}
		if ( $this->findAndRemoveScope( 'mwoauth-authonlyprivate', $scopes ) ) {
			$finalParams['granttype'] = 'authonlyprivate';
		}

		if ( !in_array( 'basic', $scopes ) ) {
			$scopes[] = 'basic';
		}
		$finalParams['grants'] = \FormatJson::encode( $scopes );

		return $finalParams;
	}

	/**
	 * @param string $searchKey
	 * @param array &$values
	 * @return bool
	 */
	private function findAndRemoveScope( $searchKey, array &$values ): bool {
		$index = array_search( $searchKey, $values );
		if ( $index === false ) {
			return false;
		}
		array_splice( $values, $index, 1 );

		return true;
	}

	/**
	 * @inheritDoc
	 */
	public function validate( Validator $restValidator ) {
		parent::validate( $restValidator );

		$params = $this->getValidatedParams();

		if (
			( isset( $params['owner_only'] ) && !$params['owner_only'] ) &&
			( isset( $params['callback_url'] ) && !$params['callback_url'] )
		) {
			throw new MWOAuthException( 'mwoauth-error-missing-callback-url-non-owner' );
		}
	}
}
