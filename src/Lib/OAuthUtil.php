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

use MediaWiki\Logger\LoggerFactory;

class OAuthUtil {
	public static function urlencode_rfc3986( $input ) {
		if ( is_array( $input ) ) {
			return array_map(
				array(
					self::class,
					'urlencode_rfc3986'
				),
				$input
			);
		} else {
			if ( is_scalar( $input ) ) {
				return str_replace(
					'+',
					' ',
					str_replace( '%7E', '~', rawurlencode( $input ) )
				);
			} else {
				return '';
			}
		}
	}


	// This decode function isn't taking into consideration the above
	// modifications to the encoding process. However, this method doesn't
	// seem to be used anywhere so leaving it as is.
	public static function urldecode_rfc3986( $string ) {
		return urldecode( $string );
	}

	// Utility function for turning the Authorization: header into
	// parameters, has to do some unescaping
	// Can filter out any non-oauth parameters if needed ( default behaviour )
	// May 28th, 2010 - method updated to tjerk.meesters for a speed improvement.
	// see http://code.google.com/p/oauth/issues/detail?id = 163
	public static function split_header( $header, $only_allow_oauth_parameters = true ) {
		$logger = LoggerFactory::getInstance( 'OAuth' );
		$logger->debug( __METHOD__ . ": pulling headers from '$header'" );
		$params = array();
		if ( preg_match_all(
			'/(' . ( $only_allow_oauth_parameters ? 'oauth_' : '' ) . '[a-z_-]*)=(:?"([^"]*)"|([^,]*))/',
			$header,
			$matches
		)
		) {
			foreach ( $matches[1] as $i => $h ) {
				$params[$h] = OAuthUtil::urldecode_rfc3986(
					empty( $matches[3][$i] ) ? $matches[4][$i] : $matches[3][$i]
				);
			}
			if ( isset( $params['realm'] ) ) {
				unset( $params['realm'] );
			}
		}

		return $params;
	}

	// helper to try to sort out headers for people who aren't running apache
	public static function get_headers() {
		if ( function_exists( 'apache_request_headers' ) ) {
			// we need this to get the actual Authorization: header
			// because apache tends to tell us it doesn't exist
			$headers = apache_request_headers();

			// sanitize the output of apache_request_headers because
			// we always want the keys to be Cased-Like-This and arh()
			// returns the headers in the same case as they are in the
			// request
			$out = array();
			foreach ( $headers as $key => $value ) {
				$key = str_replace(
					" ",
					"-",
					ucwords( strtolower( str_replace( "-", " ", $key ) ) )
				);
				$out[$key] = $value;
			}
		} else {
			// otherwise we don't have apache and are just going to have to hope
			// that $_SERVER actually contains what we need
			$out = array();
			if ( isset( $_SERVER['CONTENT_TYPE'] ) ) {
				$out['Content-Type'] = $_SERVER['CONTENT_TYPE'];
			}
			if ( isset( $_ENV['CONTENT_TYPE'] ) ) {
				$out['Content-Type'] = $_ENV['CONTENT_TYPE'];
			}

			foreach ( $_SERVER as $key => $value ) {
				if ( substr( $key, 0, 5 ) == "HTTP_" ) {
					// this is chaos, basically it is just there to capitalize the first
					// letter of every word that is not an initial HTTP and strip HTTP
					// code from przemek
					$key = str_replace(
						" ",
						"-",
						ucwords( strtolower( str_replace( "_", " ", substr( $key, 5 ) ) ) )
					);
					$out[$key] = $value;
				}
			}
		}

		return $out;
	}

	// This function takes a input like a=b&a=c&d=e and returns the parsed
	// parameters like this
	// array( 'a' => array( 'b','c' ), 'd' => 'e' )
	public static function parse_parameters( $input ) {
		if ( !$input ) {
			return array();
		}

		$pairs = explode( '&', $input );

		$parsed_parameters = array();
		foreach ( $pairs as $pair ) {
			$split = explode( '=', $pair, 2 );
			$parameter = OAuthUtil::urldecode_rfc3986( $split[0] );
			$value = isset( $split[1] ) ? OAuthUtil::urldecode_rfc3986( $split[1] ) : '';

			if ( isset( $parsed_parameters[$parameter] ) ) {
				// We have already recieved parameter( s ) with this name, so add to the list
				// of parameters with this name

				if ( is_scalar( $parsed_parameters[$parameter] ) ) {
					// This is the first duplicate, so transform scalar ( string ) into an array
					// so we can add the duplicates
					$parsed_parameters[$parameter] = array( $parsed_parameters[$parameter] );
				}

				$parsed_parameters[$parameter][] = $value;
			} else {
				$parsed_parameters[$parameter] = $value;
			}
		}

		return $parsed_parameters;
	}

	public static function build_http_query( $params ) {
		LoggerFactory::getInstance( 'OAuth' )->debug(
			__METHOD__ . " called with params:\n" . print_r( $params, true )
		);
		if ( !$params ) {
			return '';
		}

		// Urlencode both keys and values
		$keys = OAuthUtil::urlencode_rfc3986( array_keys( $params ) );
		$values = OAuthUtil::urlencode_rfc3986( array_values( $params ) );
		$params = array_combine( $keys, $values );

		// Parameters are sorted by name, using lexicographical byte value ordering.
		// Ref: Spec: 9.1.1 ( 1 )
		uksort( $params, 'strcmp' );

		$pairs = array();
		foreach ( $params as $parameter => $value ) {
			if ( is_array( $value ) ) {
				// If two or more parameters share the same name, they are sorted by their value
				// Ref: Spec: 9.1.1 ( 1 )
				// June 12th, 2010 - changed to sort because of issue 164 by hidetaka
				sort( $value, SORT_STRING );
				foreach ( $value as $duplicate_value ) {
					$pairs[] = $parameter . '=' . $duplicate_value;
				}
			} else {
				$pairs[] = $parameter . '=' . $value;
			}
		}
		// For each parameter, the name is separated from the corresponding value by an ' = ' character ( ASCII code 61 )
		// Each name-value pair is separated by an '&' character ( ASCII code 38 )
		return implode( '&', $pairs );
	}
}
