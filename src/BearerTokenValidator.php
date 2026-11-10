<?php

namespace MediaWiki\Extension\OAuth;

use DateInterval;
use DateTimeImmutable;
use DateTimeZone;
use Lcobucci\JWT\Configuration;
use Lcobucci\JWT\Exception;
use Lcobucci\JWT\Signer\Key\InMemory;
use Lcobucci\JWT\Signer\Rsa\Sha256;
use Lcobucci\JWT\UnencryptedToken;
use Lcobucci\JWT\Validation\Constraint\LooseValidAt;
use Lcobucci\JWT\Validation\Constraint\SignedWith;
use Lcobucci\JWT\Validation\RequiredConstraintsViolated;
use League\OAuth2\Server\AuthorizationValidators\BearerTokenValidator as LeagueBearerTokenValidator;
use League\OAuth2\Server\CryptKeyInterface;
use League\OAuth2\Server\Exception\OAuthServerException;
use League\OAuth2\Server\Repositories\AccessTokenRepositoryInterface;
use MediaWiki\Extension\OAuth\Backend\Utils;
use Psr\Clock\ClockInterface;
use Psr\Http\Message\ServerRequestInterface;
use RuntimeException;

/**
 * Validates the access token.
 *
 * This class reimplements all methods of League\OAuth2\Server\AuthorizationValidators\BearerTokenValidator;
 * this is necessary because that class doesn't expose the token, and uses private variables / methods so
 * partial subclassing is not possible. It still needs to be a subclass, otherwise ResourceServer won't
 * inject the public key.
 *
 * Changes (other than code style):
 * - the 'sub' claim is expected to contain a string returned by SessionManager::getSubjectIdentifier(),
 *   rather than a numeric ID (but numeric ID is still accepted, to support legacy owner-only tokens)
 * - the 'grant_type' claim is extracted
 * - most of the original validateAuthorization method's logic is extracted into getJwtFromRequest
 *   for readability
 */
class BearerTokenValidator extends LeagueBearerTokenValidator {

	private Configuration $jwtConfiguration;

	public function __construct(
		private AccessTokenRepositoryInterface $accessTokenRepository,
		private ?DateInterval $jwtValidAtDateLeeway = null,
	) {
	}

	public function setPublicKey( CryptKeyInterface $key ): void {
		$this->publicKey = $key;
		$this->initJwtConfiguration();
	}

	private function initJwtConfiguration(): void {
		$this->jwtConfiguration = Configuration::forSymmetricSigner(
			new Sha256(),
			InMemory::plainText( 'empty', 'empty' ),
		);

		$clock = new class () implements ClockInterface {
			public function now(): DateTimeImmutable {
				return new DateTimeImmutable( 'now', new DateTimeZone( date_default_timezone_get() ) );
			}
		};

		$publicKeyContents = $this->publicKey->getKeyContents();

		if ( $publicKeyContents === '' ) {
			throw new RuntimeException( 'Public key is empty' );
		}

		// TODO: next major release: replace deprecated method and remove phpstan ignored error
		$this->jwtConfiguration->setValidationConstraints(
			new LooseValidAt( $clock, $this->jwtValidAtDateLeeway ),
			new SignedWith(
				new Sha256(),
				InMemory::plainText( $publicKeyContents, $this->publicKey->getPassPhrase() ?? '' )
			)
		);
	}

	/**
	 * @inheritDoc
	 * @throws OAuthServerException
	 */
	public function validateAuthorization( ServerRequestInterface $request ): ServerRequestInterface {
		$token = $this->getJwtFromRequest( $request );
		$claims = $token->claims();

		// convert 'sub' back into a user ID
		$sub = $claims->get( 'sub' );
		if ( str_starts_with( $sub, 'mw:' ) ) {
			// convert JWT subject to raw user ID
			$parts = explode( ':', $sub );
			$centralId = array_pop( $parts );
			$lookupScope = Utils::getCentralIdLookup()->getScope();
			if ( implode( ':', $parts ) === "mw:$lookupScope" && is_numeric( $centralId ) ) {
				$sub = $centralId;
			} else {
				throw OAuthServerException::accessDenied( 'Invalid subject scope' );
			}
		}

		// Return the request with additional attributes
		return $request
			->withAttribute( 'oauth_access_token_id', $claims->get( 'jti' ) )
			->withAttribute( 'oauth_client_id', $claims->get( 'aud' )[0] )
			->withAttribute( 'oauth_user_id', $sub )
			->withAttribute( 'oauth_scopes', $claims->get( 'scopes' ) )
			->withAttribute( 'oauth_grant_type', $claims->get( 'grant_type' ) );
	}

	/**
	 * Returns the parsed JWT from the Authorization header, fully validated. Throws on errors.
	 * @throws OAuthServerException
	 */
	private function getJwtFromRequest( ServerRequestInterface $request ): UnencryptedToken {
		// This is identical to the first part of the parent's validateAuthorization() method
		// (which annoyingly doesn't expose the token in any way).

		if ( !$request->hasHeader( 'authorization' ) ) {
			throw OAuthServerException::accessDenied( 'Missing "Authorization" header' );
		}

		$header = $request->getHeader( 'authorization' );
		$jwt = trim( (string)preg_replace( '/^\s*Bearer\s/i', '', $header[0] ) );

		if ( $jwt === '' ) {
			throw OAuthServerException::accessDenied( 'Missing "Bearer" token' );
		}

		try {
			// Attempt to parse the JWT
			$token = $this->jwtConfiguration->parser()->parse( $jwt );
		} catch ( Exception $exception ) {
			throw OAuthServerException::accessDenied( $exception->getMessage(), null, $exception );
		}

		try {
			// Attempt to validate the JWT
			$constraints = $this->jwtConfiguration->validationConstraints();
			$this->jwtConfiguration->validator()->assert( $token, ...$constraints );
		} catch ( RequiredConstraintsViolated $exception ) {
			throw OAuthServerException::accessDenied( 'Access token could not be verified', null, $exception );
		}

		if ( !$token instanceof UnencryptedToken ) {
			throw OAuthServerException::accessDenied( 'Access token is not an instance of UnencryptedToken' );
		}

		// Check if token has been revoked
		$claims = $token->claims();
		if ( $this->accessTokenRepository->isAccessTokenRevoked( $claims->get( 'jti' ) ) ) {
			throw OAuthServerException::accessDenied( 'Access token has been revoked' );
		}

		return $token;
	}

}
