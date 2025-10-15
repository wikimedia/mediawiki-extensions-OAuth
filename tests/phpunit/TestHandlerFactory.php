<?php

namespace MediaWiki\Extension\OAuth\Tests;

use MediaWiki\Extension\OAuth\Rest\Handler\ListClients;
use MediaWiki\MediaWikiServices;

/**
 * Class TestHandlerFactory
 *
 * Used to retrieve Handlers for unit tests.
 *
 * @package MediaWiki\Extension\OAuth\Rest\Handler
 */
class TestHandlerFactory {

	/**
	 * @return ListClients
	 */
	public static function getListClients() {
		$connProvider = MediaWikiServices::getInstance()->getConnectionProvider();

		return new ListClients( $connProvider );
	}
}
