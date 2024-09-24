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

/**
 * The HMAC-SHA1 signature method uses the HMAC-SHA1 signature algorithm as defined in [RFC2104]
 * where the Signature Base String is the text and the key is the concatenated values ( each first
 * encoded per Parameter Encoding ) of the Consumer Secret and Token Secret, separated by an '&'
 * character ( ASCII code 38 ) even if empty.
 *     - Chapter 9.2 ( "HMAC-SHA1" )
 */
class OAuthSignatureMethodHmacSha1 extends OAuthSignatureMethod {
	function get_name() {
		return "HMAC-SHA1";
	}

	public function build_signature( $request, $consumer, $token ) {
		$base_string = $request->get_signature_base_string();
		$this->logger->debug( __METHOD__ . ": Base string: '$base_string'" );
		$request->base_string = $base_string;

		$key_parts = array(
			$consumer->secret,
			( $token ) ? $token->secret : ""
		);

		$key_parts = OAuthUtil::urlencode_rfc3986( $key_parts );
		$key = implode( '&', $key_parts );
		$this->logger->debug( __METHOD__ . ": HMAC Key: '$key'" );

		return base64_encode( hash_hmac( 'sha1', $base_string, $key, true ) );
	}
}
