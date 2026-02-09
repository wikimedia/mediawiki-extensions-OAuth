<?php

namespace MediaWiki\Extension\OAuth\Tests\Integration\Repository;

use MediaWiki\Extension\OAuth\Entity\ScopeEntity;
use MediaWiki\Extension\OAuth\Repository\ScopeRepository;
use MediaWikiIntegrationTestCase;

/**
 * @covers \MediaWiki\Extension\OAuth\Repository\ScopeRepository
 * @group OAuth
 */
class ScopeRepositoryTest extends MediaWikiIntegrationTestCase {
	public function testScopes() {
		$repo = new ScopeRepository();

		$this->assertInstanceOf(
			ScopeEntity::class, $repo->getScopeEntityByIdentifier( 'editpage' ),
			'Scope \"editpage\" should be a valid scope'
		);
		$this->assertInstanceOf(
			ScopeEntity::class, $repo->getScopeEntityByIdentifier( 'mwoauth-authonlyprivate' ),
			'Scope \"mwoauth-authonlyprivate\" should be a valid scope'
		);

		$this->assertNotInstanceOf(
			ScopeEntity::class, $repo->getScopeEntityByIdentifier( 'dummynonexistent' ),
			'Scope \"dummynonexistent\" should not be a valid scope'
		);
	}
}
