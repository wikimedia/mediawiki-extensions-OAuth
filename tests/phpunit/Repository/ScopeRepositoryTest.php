<?php

namespace MediaWiki\Extensions\OAuth\Tests\Repository;

use MediaWiki\Extensions\OAuth\Entity\ScopeEntity;
use MediaWiki\Extensions\OAuth\Repository\ScopeRepository;
use MediaWikiTestCase;

/**
 * @covers \MediaWiki\Extensions\OAuth\Repository\ScopeRepository
 */
class ScopeRepositoryTest extends MediaWikiTestCase {
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
