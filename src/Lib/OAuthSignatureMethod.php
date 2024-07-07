<?php
// vim: foldmethod=marker
/**
 * The MIT License
 *
 * Copyright (c) 2007 Andy Smith
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files ( the "Software" ), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in
 * all copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
 * THE SOFTWARE.
 */

namespace MediaWiki\Extension\OAuth\Lib;

use MediaWiki\Extension\OAuth\Lib\OAuthConsumer;
use MediaWiki\Extension\OAuth\Lib\OAuthRequest;
use MediaWiki\Extension\OAuth\Lib\OAuthToken;
use MediaWiki\Logger\LoggerFactory;
use Psr\Log\LoggerInterface;

/**
 * A class for implementing a Signature Method
 * See section 9 ( "Signing Requests" ) in the spec
 */
abstract class OAuthSignatureMethod {

	/** @var LoggerInterface */
	protected $logger;

	public function __construct() {
		$this->logger = LoggerFactory::getInstance( 'OAuth' );
	}

	/**
	 * Needs to return the name of the Signature Method ( ie HMAC-SHA1 )
	 * @return string
	 */
	abstract public function get_name();

	/**
	 * Build up the signature
	 * NOTE: The output of this function MUST NOT be urlencoded.
	 * the encoding is handled in OAuthRequest when the final
	 * request is serialized
	 * @param OAuthRequest $request
	 * @param OAuthConsumer $consumer
	 * @param OAuthToken $token
	 * @return string
	 */
	abstract public function build_signature( $request, $consumer, $token );

	/**
	 * Verifies that a given signature is correct
	 * @param OAuthRequest $request
	 * @param OAuthConsumer $consumer
	 * @param OAuthToken $token
	 * @param string|null $signature
	 * @return bool
	 */
	public function check_signature( $request, $consumer, $token, $signature ) {
		$signature ??= '';
		$this->logger->debug( __METHOD__ . ": Expecting: '$signature'" );
		$built = $this->build_signature( $request, $consumer, $token );
		$this->logger->debug( __METHOD__ . ": Built: '$built'" );

		return hash_equals( $built, $signature );
	}
}
