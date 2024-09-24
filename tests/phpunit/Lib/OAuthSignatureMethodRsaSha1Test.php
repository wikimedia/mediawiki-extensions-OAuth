<?php

namespace MediaWiki\Extension\OAuth\Tests\Lib;

use MediaWiki\Extension\OAuth\Lib\OAuthConsumer;
use PHPUnit\Framework\TestCase;

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

/**
 * @group OAuth
 * @covers \MediaWiki\Extension\OAuth\Lib\OAuthSignatureMethodRsaSha1
 */
class OAuthSignatureMethodRsaSha1Test extends TestCase {
	private $method;

	protected function setUp() : void {
		$this->method = new MockOAuthSignatureMethodRsaSha1();
	}

	public function testIdentifyAsRsaSha1() {
		$this->assertEquals('RSA-SHA1', $this->method->get_name());
	}

	public function testBuildSignature() {
		if( ! function_exists('openssl_get_privatekey') ) {
			$this->markTestSkipped('OpenSSL not available, can\'t test RSA-SHA1 functionality');
		}

		// Tests taken from http://wiki.oauth.net/TestCases section 9.3 ("RSA-SHA1")
		$request   = new MockOAuthBaseStringRequest('GET&http%3A%2F%2Fphotos.example.net%2Fphotos&file%3Dvacaction.jpg%26oauth_consumer_key%3Ddpf43f3p2l4k3l03%26oauth_nonce%3D13917289812797014437%26oauth_signature_method%3DRSA-SHA1%26oauth_timestamp%3D1196666512%26oauth_version%3D1.0%26size%3Doriginal');
		$consumer  = new OAuthConsumer('dpf43f3p2l4k3l03', '__unused__');
		$token     = NULL;
		$signature = 'jvTp/wX1TYtByB1m+Pbyo0lnCOLIsyGCH7wke8AUs3BpnwZJtAuEJkvQL2/9n4s5wUmUl4aCI4BwpraNx4RtEXMe5qg5T1LVTGliMRpKasKsW//e+RinhejgCuzoH26dyF8iY2ZZ/5D1ilgeijhV/vBka5twt399mXwaYdCwFYE=';
		$this->assertEquals($signature, $this->method->build_signature( $request, $consumer, $token) );
	}

	public function testVerifySignature() {
		if( ! function_exists('openssl_get_privatekey') ) {
			$this->markTestSkipped('OpenSSL not available, can\'t test RSA-SHA1 functionality');
		}

		// Tests taken from http://wiki.oauth.net/TestCases section 9.3 ("RSA-SHA1")
		$request   = new MockOAuthBaseStringRequest('GET&http%3A%2F%2Fphotos.example.net%2Fphotos&file%3Dvacaction.jpg%26oauth_consumer_key%3Ddpf43f3p2l4k3l03%26oauth_nonce%3D13917289812797014437%26oauth_signature_method%3DRSA-SHA1%26oauth_timestamp%3D1196666512%26oauth_version%3D1.0%26size%3Doriginal');
		$consumer  = new OAuthConsumer('dpf43f3p2l4k3l03', '__unused__');
		$token     = NULL;
		$signature = 'jvTp/wX1TYtByB1m+Pbyo0lnCOLIsyGCH7wke8AUs3BpnwZJtAuEJkvQL2/9n4s5wUmUl4aCI4BwpraNx4RtEXMe5qg5T1LVTGliMRpKasKsW//e+RinhejgCuzoH26dyF8iY2ZZ/5D1ilgeijhV/vBka5twt399mXwaYdCwFYE=';
		$this->assertTrue($this->method->check_signature( $request, $consumer, $token, $signature) );
	}
}
