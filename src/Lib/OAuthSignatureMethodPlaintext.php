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
 * The PLAINTEXT method does not provide any security protection and SHOULD only be used
 * over a secure channel such as HTTPS. It does not use the Signature Base String.
 *     - Chapter 9.4 ( "PLAINTEXT" )
 */
class OAuthSignatureMethodPlaintext extends OAuthSignatureMethod {
	public function get_name() {
		return "PLAINTEXT";
	}

	/**
	 * oauth_signature is set to the concatenated encoded values of the Consumer Secret and
	 * Token Secret, separated by a '&' character ( ASCII code 38 ), even if either secret is
	 * empty. The result MUST be encoded again.
	 *     - Chapter 9.4.1 ( "Generating Signatures" )
	 *
	 * Please note that the second encoding MUST NOT happen in the SignatureMethod, as
	 * OAuthRequest handles this!
	 */
	public function build_signature( $request, $consumer, $token ) {
		$key_parts = array(
			$consumer->secret,
			( $token ) ? $token->secret : ""
		);

		$key_parts = OAuthUtil::urlencode_rfc3986( $key_parts );
		$key = implode( '&', $key_parts );
		$request->base_string = $key;

		return $key;
	}
}
