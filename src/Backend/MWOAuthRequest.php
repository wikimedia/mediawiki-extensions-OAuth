<?php

namespace MediaWiki\Extension\OAuth\Backend;

use MediaWiki\Extension\OAuth\Lib\OAuthRequest;
use MediaWiki\Extension\OAuth\Lib\OAuthUtil;
use MediaWiki\Request\WebRequest;

/**
 * @file
 * @ingroup OAuth
 *
 * @license GPL-2.0-or-later
 * @author Chris Steipp
 */

class MWOAuthRequest extends OAuthRequest {
	/** @var string|false */
	private $sourceIP;

	public function __construct( $httpMethod, $httpUrl, $parameters, $sourcIP = false ) {
		$this->sourceIP = $sourcIP;
		parent::__construct( $httpMethod, $httpUrl, $parameters );
	}

	public function getConsumerKey() {
		return $this->parameters['oauth_consumer_key'] ?? '';
	}

	/**
	 * Track the source IP of the request, so we can enforce the allowed IP list
	 * @return string
	 */
	public function getSourceIP() {
		return $this->sourceIP;
	}

	public static function fromRequest( WebRequest $request ) {
		$httpMethod = strtoupper( $request->getMethod() );
		$httpUrl = $request->getFullRequestURL();

		// Find request headers
		$requestHeaders = Utils::getHeaders();

		// Parse the query-string to find GET parameters
		$parameters = $request->getQueryValuesOnly();

		// It's a POST request of the proper content-type, so parse POST
		// parameters and add those overriding any duplicates from GET
		if ( $request->wasPosted()
			&& isset( $requestHeaders['Content-Type'] )
			&& strpos(
				$requestHeaders['Content-Type'],
				'application/x-www-form-urlencoded'
			) === 0
		) {
			$postData = OAuthUtil::parse_parameters( $request->getRawPostString() );
			$parameters = array_merge( $parameters, $postData );
		}

		// We have a Authorization-header with OAuth data. Parse the header
		// and add those overriding any duplicates from GET or POST
		if ( isset( $requestHeaders['Authorization'] )
			&& substr( $requestHeaders['Authorization'], 0, 6 ) == 'OAuth '
		) {
			$headerParameters = OAuthUtil::split_header(
				$requestHeaders['Authorization']
			);
			$parameters = array_merge( $parameters, $headerParameters );
		}

		return new self( $httpMethod, $httpUrl, $parameters, $request->getIP() );
	}
}
