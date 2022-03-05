<?php

namespace MediaWiki\Extension\OAuth\Tests\Entity;

use MediaWiki\Extension\OAuth\Entity\UserEntity;
use MediaWikiIntegrationTestCase;

/**
 * @covers \MediaWiki\Extension\OAuth\Entity\UserEntity
 * @group OAuth
 */
class UserEntityTest extends MediaWikiIntegrationTestCase {

	public function testProperties() {
		$userEntity = UserEntity::newFromMWUser(
			$this->getTestUser()->getUser()
		);

		$this->assertSame(
			$this->getTestUser()->getUser()->getId(),
			$userEntity->getIdentifier(),
			'User identifier should be the same as the id of the user it represents'
		);
	}
}
