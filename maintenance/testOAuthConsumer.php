<?php

namespace MediaWiki\Extension\OAuth;

use MediaWiki\Extension\OAuth\Lib\OAuthConsumer;
use MediaWiki\Extension\OAuth\Lib\OAuthException;
use MediaWiki\Extension\OAuth\Lib\OAuthRequest;
use MediaWiki\Extension\OAuth\Lib\OAuthSignatureMethodHmacSha1;
use MediaWiki\Extension\OAuth\Lib\OAuthSignatureMethodRsaSha1;
use MediaWiki\Maintenance\Maintenance;

/**
 * @ingroup Maintenance
 */
if ( getenv( 'MW_INSTALL_PATH' ) ) {
	$IP = getenv( 'MW_INSTALL_PATH' );
} else {
	$IP = __DIR__ . '/../../..';
}

require_once "$IP/maintenance/Maintenance.php";

class TestOAuthConsumer extends Maintenance {
	public function __construct() {
		parent::__construct();
		$this->addDescription( "Test an OAuth consumer" );
		$this->addOption( 'consumerKey', 'Consumer key', true, true );
		$this->addOption( 'consumerSecret', 'Consumer secret', false, true );
		$this->addOption( 'RSAKeyFile',
			'File containing the RSA private key for the consumer', false, true
		);
		$this->addOption( 'useSSL', 'Use SSL' );
		$this->addOption( 'verbose', 'Verbose output (e.g. HTTP request/response headers)' );
		$this->requireExtension( "OAuth" );
	}

	public function execute() {
		global $wgServer, $wgScriptPath;

		$consumerKey = $this->getOption( 'consumerKey' );
		$consumerSecret = $this->getOption( 'consumerSecret' );
		$rsaKeyFile = $this->getOption( 'RSAKeyFile' );
		$baseurl = wfExpandUrl(
			"{$wgServer}{$wgScriptPath}/index.php?title=Special:OAuth", PROTO_CANONICAL );
		$endpoint = "{$baseurl}/initiate&format=json&oauth_callback=oob";

		$endpoint_acc = "{$baseurl}/token&format=json";

		if ( !$consumerSecret && !$rsaKeyFile ) {
			$this->error( "Either consumerSecret or RSAKeyFile required!" );
			$this->maybeHelp( true );
		}

		$c = new OAuthConsumer( $consumerKey, $consumerSecret );
		$parsed = parse_url( $endpoint );
		$params = [];
		// @phan-suppress-next-line PhanTypePossiblyInvalidDimOffset
		parse_str( $parsed['query'], $params );
		$req_req = OAuthRequest::from_consumer_and_token( $c, null, "GET", $endpoint, $params );
		if ( $rsaKeyFile ) {
			try {
				$sig_method = new class ( $rsaKeyFile ) extends OAuthSignatureMethodRsaSha1 {
					/** @var string */
					private $privKey;
					/** @var string */
					private $pubKey;

					public function __construct( $privKeyFile ) {
						$key = file_get_contents( $privKeyFile );
						if ( !$key ) {
							throw new OAuthException( "Could not read private key file $privKeyFile" );
						}

						$privKey = openssl_pkey_get_private( $key );
						if ( !$privKey ) {
							throw new OAuthException( "File $privKeyFile does not contain a private key" );
						}

						$details = openssl_pkey_get_details( $privKey );
						if ( $details['type'] !== OPENSSL_KEYTYPE_RSA ) {
							throw new OAuthException( "Key is not an RSA key" );
						}
						if ( !$details['key'] ) {
							throw new OAuthException( "Could not get public key from private key" );
						}

						$this->privKey = $key;
						$this->pubKey = $details['key'];
					}

					protected function fetch_public_cert( &$request ) {
						return $this->pubKey;
					}

					protected function fetch_private_cert( &$request ) {
						return $this->privKey;
					}
				};
			} catch ( OAuthException $ex ) {
				$this->fatalError( $ex->getMessage() );
			}
		} else {
			$sig_method = new OAuthSignatureMethodHmacSha1();
		}
		$req_req->sign_request( $sig_method, $c, null );

		$this->output( "Calling: $req_req\n" );

		$ch = curl_init();
		curl_setopt( $ch, CURLOPT_URL, (string)$req_req );
		if ( $this->hasOption( 'useSSL' ) ) {
			curl_setopt( $ch, CURLOPT_PORT, 443 );
		}
		if ( $this->hasOption( 'verbose' ) ) {
			curl_setopt( $ch, CURLOPT_VERBOSE, true );
		}
		curl_setopt( $ch, CURLOPT_SSL_VERIFYPEER, 0 );
		curl_setopt( $ch, CURLOPT_HEADER, 0 );
		curl_setopt( $ch, CURLOPT_RETURNTRANSFER, 1 );
		$data = curl_exec( $ch );

		if ( !$data ) {
			$this->output( 'Curl error: ' . curl_error( $ch ) );
		}

		$this->output( "Returned: $data\n\n" );

		$token = json_decode( $data );
		if ( !$token || !isset( $token->key ) ) {
			$this->fatalError( 'Could not fetch token' );
		}

		$this->output( "Visit $baseurl/authorize" .
			"&oauth_token={$token->key}&oauth_consumer_key=$consumerKey\n" );

		// ACCESS TOKEN
		$this->output( "Enter the verification code:\n" );
		$fh = fopen( "php://stdin", "r" );
		$line = fgets( $fh );

		$rc = new OAuthConsumer( $token->key, $token->secret );
		$parsed = parse_url( $endpoint_acc );
		// @phan-suppress-next-line PhanTypePossiblyInvalidDimOffset
		parse_str( $parsed['query'], $params );
		$params['oauth_verifier'] = trim( $line );

		$acc_req = OAuthRequest::from_consumer_and_token( $c, $rc, "GET", $endpoint_acc, $params );
		$acc_req->sign_request( $sig_method, $c, $rc );

		$this->output( "Calling: $acc_req\n" );

		unset( $ch );
		$ch = curl_init();
		curl_setopt( $ch, CURLOPT_URL, (string)$acc_req );
		if ( $this->hasOption( 'useSSL' ) ) {
			curl_setopt( $ch, CURLOPT_PORT, 443 );
		}
		if ( $this->hasOption( 'verbose' ) ) {
			curl_setopt( $ch, CURLOPT_VERBOSE, true );
		}
		curl_setopt( $ch, CURLOPT_SSL_VERIFYPEER, 0 );
		curl_setopt( $ch, CURLOPT_HEADER, 0 );
		curl_setopt( $ch, CURLOPT_RETURNTRANSFER, 1 );
		$data = curl_exec( $ch );
		if ( !$data ) {
			$this->output( 'Curl error: ' . curl_error( $ch ) );
		}

		$this->output( "Returned: $data\n\n" );
	}
}

$maintClass = TestOAuthConsumer::class;
require_once RUN_MAINTENANCE_IF_MAIN;
