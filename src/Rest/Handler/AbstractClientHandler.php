<?php

namespace MediaWiki\Extension\OAuth\Rest\Handler;

use League\OAuth2\Server\Entities\AccessTokenEntityInterface;
use MediaWiki\Context\RequestContext;
use MediaWiki\Extension\OAuth\Backend\Utils;
use MediaWiki\Extension\OAuth\Control\ConsumerSubmitControl;
use MediaWiki\Extension\OAuth\Entity\ClientEntity;
use MediaWiki\Rest\Handler;
use MediaWiki\Rest\HttpException;
use MediaWiki\Rest\LocalizedHttpException;
use MediaWiki\Rest\ResponseInterface;
use Wikimedia\Message\MessageValue;

/**
 * This class serves as the base class for all operations
 * on OAuth 2.0 clients over the REST API.
 * It provides client initialization and basic checks on it,
 * as well as parameter name mapping between OAuth 2.0 and 1.0 terminology
 */
abstract class AbstractClientHandler extends Handler {

	/**
	 * @return ResponseInterface
	 * @throws HttpException
	 */
	public function execute(): ResponseInterface {
		// At this point we assume user is authenticated and has valid session
		// Authentication can be achieved over CentralAuth or Access token in authorization header
		$responseFactory = $this->getResponseFactory();
		$params = $this->getUnifiedParams();

		$control = new ConsumerSubmitControl(
			RequestContext::getMain(),
			$params,
			Utils::getCentralDB( DB_PRIMARY )
		);

		$status = $control->submit();
		if ( $status->isGood() ) {
			$value = $status->getValue();
			if ( isset( $value['result']['consumer'] ) ) {
				/** @var ClientEntity $client */
				$client = $value['result']['consumer'];
				$data = [
					'name' => $client->getName(),
					'client_key' => $client->getConsumerKey(),
					'secret' => Utils::hmacDBSecret( $client->getSecretKey() )
				];
				if ( $client->getOwnerOnly() ) {
					$accessToken = $value['result']['accessToken'];
					if ( $accessToken instanceof AccessTokenEntityInterface ) {
						$data['access_token'] = (string)$accessToken;
					}
				}

				return $responseFactory->createJson( $data );
			}

			throw new LocalizedHttpException(
				MessageValue::new( 'mwoauth-consumer-submit-error' ), 400
			);
		}
		$value = $status->getValue();
		if ( isset( $value['error'] ) ) {
			throw new HttpException( $value['error'], 400 );
		}

		throw new HttpException( $status->getMessage() );
	}

	/**
	 * Get params that have fixed values and cannot be
	 * changed by the request params
	 *
	 * @return array
	 */
	abstract protected function getFixedParams(): array;

	/**
	 * Maps modern OAuth2 param names to the ones
	 * expected by the SubmitControl
	 *
	 * @return string[]
	 */
	protected function getParamMapping(): array {
		return [
			'oauth2IsConfidential' => 'is_confidential',
			'ownerOnly' => 'owner_only',
			'callbackUrl' => 'callback_url',
			'callbackIsPrefix' => 'callback_is_prefix',
			'oauth2GrantTypes' => 'grant_types',
			'grants' => 'scopes',
			'consumerKey' => 'client_key',
		];
	}

	/**
	 * Merge and adjust all params
	 *
	 * @return array
	 */
	protected function getUnifiedParams(): array {
		$finalParams = [];

		$requestParams = $this->getValidatedParams();
		$mapping = array_flip( $this->getParamMapping() );
		foreach ( $requestParams as $name => $value ) {
			if ( isset( $mapping[$name] ) ) {
				$finalParams[$mapping[$name]] = $value;
			} else {
				$finalParams[$name] = $value;
			}
		}

		$bodyParams = $this->getValidatedBody();
		foreach ( $bodyParams as $name => $value ) {
			if ( isset( $mapping[$name] ) ) {
				$finalParams[$mapping[$name]] = $value;
			} else {
				$finalParams[$name] = $value;
			}
		}

		$finalParams = array_merge(
			$finalParams,
			$this->getFixedParams()
		);

		return $finalParams;
	}

	/**
	 * @return string[]
	 */
	public function getSupportedRequestTypes(): array {
		return [
			'application/json',
			'application/x-www-form-urlencoded'
		];
	}
}
