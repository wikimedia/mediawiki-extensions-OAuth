<?php

namespace MediaWiki\Extension\OAuth\Rest\Handler;

use MediaWiki\Context\RequestContext;
use MediaWiki\Extension\OAuth\Backend\Consumer;
use MediaWiki\Extension\OAuth\Backend\Utils;
use MediaWiki\Extension\OAuth\Control\ConsumerAccessControl;
use MediaWiki\Rest\LocalizedHttpException;
use MWRestrictions;
use Wikimedia\Message\MessageValue;
use Wikimedia\ParamValidator\ParamValidator;

class ResetClientSecret extends AbstractClientHandler {

	/**
	 * @inheritDoc
	 */
	protected function getFixedParams(): array {
		return [
			'action' => 'update',
			'rsaKey' => '',
			'resetSecret' => true,
			'restrictions' => MWRestrictions::newDefault(),
		];
	}

	/**
	 * @inheritDoc
	 */
	protected function getUnifiedParams(): array {
		$params = parent::getUnifiedParams();
		$params['client_key'] = $this->getRequest()->getPathParam( 'client_key' );

		$requestContext = RequestContext::getMain();
		$dbr = Utils::getCentralDB( DB_REPLICA );
		$clientAccess = ConsumerAccessControl::wrap(
			Consumer::newFromKey( $dbr, $params['consumerKey'] ), $requestContext
		);
		if ( !$clientAccess ) {
			throw new LocalizedHttpException(
				MessageValue::new( 'mwoauth-invalid-consumer-key' ), 400
			);
		}

		$dataAccessObj = $clientAccess->getDAO();

		if ( $dataAccessObj->getDeleted() ) {
			throw new LocalizedHttpException(
				MessageValue::new( 'mwoauth-consumer-deleted-error' ), 401
			);
		} elseif ( $dataAccessObj->getUserId() !== Utils::getCentralIdFromLocalUser(
				$requestContext->getUser()
			) ) {
			throw new LocalizedHttpException(
				MessageValue::new( 'mwoauth-consumer-user-mismatch' ), 400
			);
		}
		$params['changeToken'] = $dataAccessObj->getChangeToken( $requestContext );

		return $params;
	}

	/**
	 * @inheritDoc
	 */
	public function getParamSettings(): array {
		return [
			'client_key' => [
				self::PARAM_SOURCE => 'path',
				ParamValidator::PARAM_TYPE => 'string',
				ParamValidator::PARAM_REQUIRED => true,
			],
		];
	}

	/**
	 * @inheritDoc
	 */
	public function getBodyParamSettings(): array {
		return [
			'reason' => [
				self::PARAM_SOURCE => 'body',
				ParamValidator::PARAM_TYPE => 'string',
				ParamValidator::PARAM_REQUIRED => false,
				ParamValidator::PARAM_DEFAULT => '',
			],
		];
	}

}
