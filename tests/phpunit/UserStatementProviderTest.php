<?php
/**
 * @section LICENSE
 * © 2017 Wikimedia Foundation and contributors
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License along
 * with this program; if not, write to the Free Software Foundation, Inc.,
 * 51 Franklin Street, Fifth Floor, Boston, MA 02110-1301, USA.
 * http://www.gnu.org/copyleft/gpl.html
 *
 * @file
 */

namespace MediaWiki\Extension\OAuth\Tests;

use MediaWiki\Config\Config;
use MediaWiki\Config\HashConfig;
use MediaWiki\Extension\OAuth\Backend\Consumer;
use MediaWiki\Extension\OAuth\UserStatementProvider;
use MediaWiki\MainConfigNames;
use MediaWiki\User\User;
use MediaWiki\Utils\MWTimestamp;
use MediaWikiIntegrationTestCase;
use Wikimedia\TestingAccessWrapper;

/**
 * @covers \MediaWiki\Extension\OAuth\UserStatementProvider
 * @group OAuth
 * @group Database
 * @license GPL-2.0-or-later
 */
class UserStatementProviderTest extends MediaWikiIntegrationTestCase {

	public function testGetUserStatement() {
		$this->overrideConfigValues( [
			MainConfigNames::EmailAuthentication => true,
		] );
		$time = wfTimestamp();
		MWTimestamp::setFakeTime( $time );
		$config = new HashConfig( [
			'CanonicalServer' => 'https://example.com/',
			'HiddenPrefs' => [],
		] );

		$user = $this->getMutableTestUser()->getUser();
		$user->setEmail( 'test@example.com' );
		$user->setRealName( 'John Doe' );
		$consumer = $this->getConsumer( 'key' );
		$grants = [ 'mwoauth-authonlyprivate' ];

		$userStatementProvider = $this->getUserStatementProvider( $config, $user, $consumer, $grants );
		$userStatement = $userStatementProvider->getUserStatement();
		$userProfile = $userStatementProvider->getUserProfile();

		foreach ( [ $userStatement, $userProfile ] as $data ) {
			$this->assertSame( (string)$user->getId(), $data['sub'] );
			$this->assertSame( $user->getName(), $data['username'] );
			$this->assertSame( 0, $data['editcount'] );
			$this->assertSame( false, $data['confirmed_email'] );
			$this->assertSame( false, $userProfile['email_verified'] );
			$this->assertSame( false, $data['blocked'] );
			$this->assertEqualsWithDelta( $time, 100, wfTimestamp( TS_UNIX, $data['registered'] ) );
			$this->assertContains( 'user', $data['groups'] );
			$this->assertContains( 'edit', $data['rights'] );
			$this->assertSame( 'John Doe', $data['realname'] );
			$this->assertSame( '', $data['email'] );
		}
		$this->assertSame( 'https://example.com/', $userStatement['iss'] );
		$this->assertSame( 'key', $userStatement['aud'] );
		$this->assertEqualsWithDelta( $time, $userStatement['exp'], 3600 );
		$this->assertSame( (int)$time, $userStatement['iat'] );

		$user->setEmailAuthenticationTimestamp( $time - 100 );
		$userStatementProvider = $this->getUserStatementProvider( $config, $user, $consumer, $grants );
		$userProfile = $userStatementProvider->getUserProfile();
		$this->assertSame( true, $userProfile['confirmed_email'] );
		$this->assertSame( true, $userProfile['email_verified'] );
		$this->assertSame( 'test@example.com', $userProfile['email'] );
	}

	private function getConsumer( string $key ): Consumer {
		$fieldColumnMap = TestingAccessWrapper::newFromClass( Consumer::class )->getFieldColumnMap();
		$fields = array_fill_keys( array_keys( $fieldColumnMap ), null );
		$fields['consumerKey'] = $key;
		return Consumer::newFromArray( $fields );
	}

	private function getUserStatementProvider(
		Config $config, User $user, Consumer $consumer, array $grants
	): UserStatementProvider {
		$userGroupManager = $this->getServiceContainer()->getUserGroupManager();
		$grantsInfo = $this->getServiceContainer()->getGrantsInfo();
		// use a subclass as constructor is not public and PHPUnit not intelligent enough
		// to override it
		return new class(
			$config, $user, $consumer, $grants, $userGroupManager, $grantsInfo
		) extends UserStatementProvider {
			public function __construct( $config, $user, $consumer, $grants, $userGroupManager, $grantsInfo	) {
				parent::__construct( $config, $user, $consumer, $grants, $userGroupManager, $grantsInfo	);
			}
		};
	}

}
