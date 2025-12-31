<?php

namespace MediaWiki\Extension\OAuth\Tests;

use GuzzleHttp\Psr7\ServerRequest;
use Lcobucci\JWT\Configuration;
use Lcobucci\JWT\Signer\Blake2b;
use Lcobucci\JWT\Signer\Key\InMemory;
use Lcobucci\JWT\Validation\Constraint\IssuedBy;
use League\OAuth2\Server\Exception\OAuthServerException;
use League\OAuth2\Server\Repositories\AccessTokenRepositoryInterface;
use MediaWiki\Extension\OAuth\BearerTokenValidator;
use MediaWiki\User\CentralId\CentralIdLookup;
use MediaWiki\User\CentralId\CentralIdLookupFactory;
use MediaWikiIntegrationTestCase;
use Wikimedia\TestingAccessWrapper;

/**
 * @covers \MediaWiki\Extension\OAuth\BearerTokenValidator
 */
class BearerTokenValidatorTest extends MediaWikiIntegrationTestCase {

	/**
	 * @dataProvider provideValidateAuthorization
	 */
	public function testValidateAuthorization( string $jwtSubject, ?string $expectedUserId ) {
		$accessTokenRepository = $this->createNoOpAbstractMock( AccessTokenRepositoryInterface::class,
			[ 'isAccessTokenRevoked' ] );
		$accessTokenRepository->method( 'isAccessTokenRevoked' )->willReturn( false );
		$validator = new BearerTokenValidator( $accessTokenRepository );
		$wrapper = TestingAccessWrapper::newFromObject( $validator );
		// https://lcobucci-jwt.readthedocs.io/en/stable/upgrading/#removal-of-none-algorithm
		$wrapper->jwtConfiguration = Configuration::forSymmetricSigner(
			new Blake2b(),
			InMemory::base64Encoded( 'MpQd6dDPiqnzFSWmpUfLy4+Rdls90Ca4C8e0QD0IxqY=' )
		);
		// TODO: setValidationConstraints is deprecated...
		$wrapper->jwtConfiguration->setValidationConstraints( new IssuedBy( 'https://example.org' ) );
		$lookup = $this->createNoOpMock( CentralIdLookup::class, [ 'getScope' ] );
		$lookup->method( 'getScope' )->willReturn( 'mock:scope' );
		$lookupFactory = $this->createNoOpMock( CentralIdLookupFactory::class, [ 'getLookup' ] );
		$lookupFactory->method( 'getLookup' )->willReturn( $lookup );
		$this->setService( 'CentralIdLookupFactory', $lookupFactory );

		$jwt = $wrapper->jwtConfiguration->builder()
			->issuedBy( 'https://example.org' )
			->permittedFor( 'xyz' )
			->relatedTo( $jwtSubject )
			->identifiedBy( '123' )
			->getToken( $wrapper->jwtConfiguration->signer(), $wrapper->jwtConfiguration->signingKey() );
		$request = new ServerRequest( 'GET', 'https://example.org', headers: [
			'Authorization' => 'Bearer ' . $jwt->toString(),
		] );
		if ( $expectedUserId === null ) {
			$this->expectException( OAuthServerException::class );
		}
		$validatedRequest = $validator->validateAuthorization( $request );
		if ( $expectedUserId !== null ) {
			$this->assertSame( $expectedUserId, $validatedRequest->getAttribute( 'oauth_user_id' ) );
			$this->assertSame( 'xyz', $validatedRequest->getAttribute( 'oauth_client_id' ) );
			$this->assertSame( '123', $validatedRequest->getAttribute( 'oauth_access_token_id' ) );
		}
	}

	public static function provideValidateAuthorization() {
		return [
			'legacy' => [ '1000', '1000' ],
			'modern' => [ 'mw:mock:scope:1000', '1000' ],
			'wrong scope' => [ 'mw:bad:scope:1000', null ],
		];
	}

}
