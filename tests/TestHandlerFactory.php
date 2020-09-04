<?php

namespace MediaWiki\Extensions\OAuth\Tests;

use MediaWiki\Extensions\OAuth\Rest\Handler\ListClients;
use MediaWiki\MediaWikiServices;

/**
 * Class TestHandlerFactory
 *
 * Used to retrieve Handlers for unit tests.
 *
 * @package MediaWiki\Extensions\OAuth\Rest\Handler
 */
class TestHandlerFactory {

	/**
	 * @return ListClients
	 */
	public static function getListClients() {
		$loadBalancer = MediaWikiServices::getInstance()
			->getDBLoadBalancerFactory()
			->getMainLB();

		return new ListClients(
			$loadBalancer
		);
	}
}
