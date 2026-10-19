<?php

namespace MediaWiki\Extension\OAuth\Tests\Integration\Frontend;

use MediaWiki\Extension\OAuth\Frontend\SpecialPages\SpecialMWOAuth;
use MediaWiki\MainConfigNames;
use MediaWiki\Request\FauxRequest;
use MediaWiki\Title\Title;
use MediaWikiIntegrationTestCase;
use Wikimedia\TestingAccessWrapper;

/**
 * @covers \MediaWiki\Extension\OAuth\Frontend\SpecialPages\SpecialMWOAuth
 * @group OAuth
 */
class SpecialMWOAuthTest extends MediaWikiIntegrationTestCase {

	private function newSpecialPage( array $params = [] ): SpecialMWOAuth {
		$services = $this->getServiceContainer();
		$specialPage = new SpecialMWOAuth(
			$services->getGrantsLocalization(),
			$services->getSkinFactory(),
			$services->getUrlUtils()
		);
		$context = $specialPage->getContext();
		$context->setRequest( new FauxRequest( $params, true ) );
		$context->setTitle( Title::newMainPage() );

		return $specialPage;
	}

	/**
	 * @dataProvider provideReturnto
	 */
	public function testRedirectToRESTKeepsReturntoOnWiki( string $returnto, bool $expectLocalRedirect ): void {
		$this->overrideConfigValues( [
			MainConfigNames::Server => 'https://wiki.example.org',
			MainConfigNames::CanonicalServer => 'https://wiki.example.org',
		] );
		$specialPage = $this->newSpecialPage( [ 'returnto' => $returnto ] );

		TestingAccessWrapper::newFromObject( $specialPage )->redirectToREST();

		$location = (string)$specialPage->getContext()->getRequest()->response()->getHeader( 'Location' );
		if ( $expectLocalRedirect ) {
			$this->assertStringStartsWith( 'https://wiki.example.org/', $location );
		} else {
			$this->assertTrue(
				$location === '' || str_starts_with( $location, 'https://wiki.example.org/' ),
				"Off-wiki returnto must not become the redirect Location; got: $location"
			);
		}
	}

	public static function provideReturnto(): array {
		return [
			'off-site absolute' => [ 'https://evil.example/', false ],
			'protocol-relative' => [ '//evil.example/', false ],
			'backslash authority' => [ '\\\\evil.example', false ],
			'userinfo at host' => [ 'https://wiki.example.org@evil.example/', false ],
			'backslash userinfo bypass' => [ 'https://evil.example\\@wiki.example.org/', false ],
			'host-prefix lookalike' => [ 'https://wiki.example.org.evil.example/', false ],
			'leading whitespace' => [ "\t//evil.example", false ],
			'local path' => [ '/w/rest.php/oauth2/authorize', true ],
			'local absolute' => [ 'https://wiki.example.org/wiki/Main_Page', true ],
		];
	}
}
