<?php
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

namespace MediaWiki\Extension\OAuth\Tests\Lib;

use MediaWiki\Extension\OAuth\Lib\OAuthConsumer;
use MediaWiki\Extension\OAuth\Lib\OAuthException;
use MediaWiki\Extension\OAuth\Lib\OAuthRequest;
use MediaWiki\Extension\OAuth\Lib\OAuthServer;
use MediaWiki\Extension\OAuth\Lib\OAuthSignatureMethodHmacSha1;
use MediaWiki\Extension\OAuth\Lib\OAuthSignatureMethodPlaintext;
use MediaWiki\Extension\OAuth\Lib\OAuthToken;
use PHPUnit\Framework\TestCase;

/**
 * Tests of OAuthUtil
 * @group OAuth
 * @covers \MediaWiki\Extension\OAuth\Lib\OAuthServer
 */
class OAuthServerTest extends TestCase {

	private $consumer;
	private $request_token;
	private $access_token;
	private $hmac_sha1;
	private $plaintext;
	private $server;

	protected function setUp() : void {
		$this->consumer       = new OAuthConsumer('key', 'secret');
		$this->request_token  = new OAuthToken('requestkey', 'requestsecret');
		$this->access_token   = new OAuthToken('accesskey', 'accesssecret');

		$this->hmac_sha1      = new OAuthSignatureMethodHmacSha1();
		$this->plaintext      = new OAuthSignatureMethodPlaintext();

		$this->server         = new OAuthServer( new MockOAuthDataStore() );
		$this->server->add_signature_method( $this->hmac_sha1 );
		$this->server->add_signature_method( $this->plaintext );
	}

	public function testAcceptValidRequest() {
		$request = OAuthRequest::from_consumer_and_token( $this->consumer, $this->access_token, 'POST', 'http://example.com');
		$request->sign_request( $this->plaintext, $this->consumer, $this->access_token );
		[$consumer, $token] = $this->server->verify_request( $request );
		$this->assertEquals( $this->consumer, $consumer );
		$this->assertEquals( $this->access_token, $token );

		$request->sign_request( $this->hmac_sha1, $this->consumer, $this->access_token );
		[$consumer, $token] = $this->server->verify_request( $request );
		$this->assertEquals( $this->consumer, $consumer );
		$this->assertEquals( $this->access_token, $token );
	}

	public function testAcceptRequestWithoutVersion() {
		$request = OAuthRequest::from_consumer_and_token( $this->consumer, $this->access_token, 'POST', 'http://example.com');
		$request->unset_parameter('oauth_version');
		$request->sign_request( $this->hmac_sha1, $this->consumer, $this->access_token );

		[ $consumer, $token ] = $this->server->verify_request( $request );
		$this->assertEquals( $this->consumer, $consumer );
		$this->assertEquals( $this->access_token, $token );
	}

	public function testRejectRequestSignedWithRequestToken() {
		$request = OAuthRequest::from_consumer_and_token( $this->consumer, $this->request_token, 'POST', 'http://example.com');
		$request->sign_request( $this->plaintext, $this->consumer, $this->request_token );

		$this->expectException(OAuthException::class);
		$this->server->verify_request( $request );
	}

	public static function requiredParameterProvider() {
		// The list of required parameters is taken from
		// Chapter 7 ("Accessing Protected Resources")
		return array(
		   array( 'oauth_consumer_key' ),
		   array( 'oauth_token' ),
		   array( 'oauth_signature_method' ),
		   array( 'oauth_signature' ),
		   array( 'oauth_timestamp' ),
		   array( 'oauth_nonce' ),
		);
	}

	/**
	 * @dataProvider requiredParameterProvider
	 */
	public function testRejectRequestWithMissingParameters( $required ) {
		$request = OAuthRequest::from_consumer_and_token( $this->consumer, $this->access_token, 'POST', 'http://example.com');
		$request->sign_request( $this->plaintext, $this->consumer, $this->access_token );
		$request->unset_parameter( $required );
		$this->expectException(OAuthException::class);
		$this->server->verify_request($request);
	}

	public function testRejectPastTimestamp() {
		// We change the timestamp to be 10 hours ago, it should throw an exception

		$request = OAuthRequest::from_consumer_and_token( $this->consumer, $this->access_token, 'POST', 'http://example.com');
		$request->set_parameter( 'oauth_timestamp', $request->get_parameter('oauth_timestamp') - 10*60*60, false);
		$request->sign_request( $this->plaintext, $this->consumer, $this->access_token );

		$this->expectException(OAuthException::class);
		$this->server->verify_request($request);
	}

	public function testRejectFutureTimestamp() {
		// We change the timestamp to be 10 hours in the future, it should throw an exception

		$request = OAuthRequest::from_consumer_and_token( $this->consumer, $this->access_token, 'POST', 'http://example.com');
		$request->set_parameter( 'oauth_timestamp', $request->get_parameter('oauth_timestamp') + 10*60*60, false);
		$request->sign_request( $this->plaintext, $this->consumer, $this->access_token );

		$this->expectException(OAuthException::class);
		$this->server->verify_request($request);
	}

	public function testRejectUsedNonce() {
		// We give a known nonce and should see an exception

		$request = OAuthRequest::from_consumer_and_token( $this->consumer, $this->access_token, 'POST', 'http://example.com');
		// The Mock datastore is set to say that the `nonce` nonce is known
		$request->set_parameter( 'oauth_nonce', 'nonce', false);
		$request->sign_request( $this->plaintext, $this->consumer, $this->access_token );

		$this->expectException(OAuthException::class);
		$this->server->verify_request($request);
	}

	public function testRejectInvalidSignature() {
		// We change the signature post-signing to be something invalid

		$request = OAuthRequest::from_consumer_and_token( $this->consumer, $this->access_token, 'POST', 'http://example.com');
		$request->sign_request( $this->plaintext, $this->consumer, $this->access_token );
		$request->set_parameter( 'oauth_signature', '__whatever__', false);

		$this->expectException(OAuthException::class);
		$this->server->verify_request($request);
	}

	public function testRejectInvalidConsumer() {
		// We use the consumer-key "unknown", which isn't known by the datastore.

		$unknown_consumer = new OAuthConsumer('unknown', '__unused__');

		$request = OAuthRequest::from_consumer_and_token( $unknown_consumer, $this->access_token, 'POST', 'http://example.com');
		$request->sign_request( $this->plaintext, $unknown_consumer, $this->access_token );

		$this->expectException(OAuthException::class);
		$this->server->verify_request( $request );
	}

	public function testRejectInvalidToken() {
		// We use the access-token "unknown" which isn't known by the datastore

		$unknown_token = new OAuthToken('unknown', '__unused__');

		$request = OAuthRequest::from_consumer_and_token( $this->consumer, $unknown_token, 'POST', 'http://example.com');
		$request->sign_request( $this->plaintext, $this->consumer, $unknown_token );

		$this->expectException(OAuthException::class);
		$this->server->verify_request( $request );
	}

	public function testRejectUnknownSignatureMethod() {
		// We use a server that only supports HMAC-SHA1, but requests with PLAINTEXT signature

		$request = OAuthRequest::from_consumer_and_token( $this->consumer, $this->access_token, 'POST', 'http://example.com');
		$request->sign_request( $this->plaintext, $this->consumer, $this->access_token );

		$server = new OAuthServer( new MockOAuthDataStore() );
		$server->add_signature_method( $this->hmac_sha1 );

		$this->expectException(OAuthException::class);
		$server->verify_request( $request );
	}

	public function testRejectUnknownVersion() {
		// We use the version "1.0a" which isn't "1.0", so reject the request

		$request = OAuthRequest::from_consumer_and_token( $this->consumer, $this->access_token, 'POST', 'http://example.com');
		$request->sign_request( $this->plaintext, $this->consumer, $this->access_token );
		$request->set_parameter('oauth_version', '1.0a', false);

		$this->expectException(OAuthException::class);
		$this->server->verify_request( $request );
	}

	public function testCreateRequestToken() {
		// We request a new Request Token

		$request = OAuthRequest::from_consumer_and_token( $this->consumer, NULL, 'POST', 'http://example.com');
		$request->sign_request( $this->plaintext, $this->consumer, NULL );

		$token = $this->server->fetch_request_token($request);
		$this->assertEquals($this->request_token, $token);
	}

	public function testRejectSignedRequestTokenRequest() {
		// We request a new Request Token, but the request is signed with a token which should fail

		$request = OAuthRequest::from_consumer_and_token( $this->consumer, $this->request_token, 'POST', 'http://example.com');
		$request->sign_request( $this->plaintext, $this->consumer, $this->request_token );

		$this->expectException(OAuthException::class);
		$token = $this->server->fetch_request_token($request);
	}

	public function testCreateAccessToken() {
		// We request a new Access Token

		$request = OAuthRequest::from_consumer_and_token( $this->consumer, $this->request_token, 'POST', 'http://example.com');
		$request->sign_request( $this->plaintext, $this->consumer, $this->request_token );

		$token = $this->server->fetch_access_token($request);
		$this->assertEquals($this->access_token, $token);
	}

	public function testRejectUnsignedAccessTokenRequest() {
		// We request a new Access Token, but we didn't sign the request with a Access Token

		$request = OAuthRequest::from_consumer_and_token( $this->consumer, NULL, 'POST', 'http://example.com');
		$request->sign_request( $this->plaintext, $this->consumer, NULL );

		$this->expectException(OAuthException::class);
		$token = $this->server->fetch_access_token($request);
	}

	public function testRejectAccessTokenSignedAccessTokenRequest() {
		// We request a new Access Token, but the request is signed with an access token, so fail!

		$request = OAuthRequest::from_consumer_and_token( $this->consumer, $this->access_token, 'POST', 'http://example.com');
		$request->sign_request( $this->plaintext, $this->consumer, $this->access_token );

		$this->expectException(OAuthException::class);
		$token = $this->server->fetch_access_token($request);
	}
}
