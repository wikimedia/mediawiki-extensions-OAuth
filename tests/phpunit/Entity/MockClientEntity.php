<?php

namespace MediaWiki\Extension\OAuth\Tests\Entity;

use MediaWiki\Extension\OAuth\Backend\Consumer;
use MediaWiki\Extension\OAuth\Entity\ClientEntity;
use MediaWiki\User\User;
use MWRestrictions;

class MockClientEntity extends ClientEntity {
	public static function newMock( User $user, $values = [] ) {
		$now = wfTimestampNow();
		return ClientEntity::newFromArray( array_merge( [
			'id'                   => null,
			'consumerKey'          => '123456789',
			'userId'               => $user->getId(),
			'name'                 => 'Test client',
			'description'          => 'Test application',
			'wiki'                 => 'TestWiki',
			'version'              => '1.0',
			'email'                => $user->getEmail(),
			'emailAuthenticated'   => $now,
			'callbackUrl'          => 'https://example.com',
			'callbackIsPrefix'     => true,
			'developerAgreement'   => 1,
			'secretKey'            => 'secretKey',
			'registration'         => $now,
			'stage'                => Consumer::STAGE_APPROVED,
			'stageTimestamp'       => $now,
			'grants'               => [ 'editpage', 'highvolume' ],
			'restrictions'         => MWRestrictions::newDefault(),
			'deleted'              => 0,
			'rsaKey'               => '',
			'oauthVersion'         => Consumer::OAUTH_VERSION_2,
			'ownerOnly'            => false,
			'oauth2IsConfidential' => true,
			'oauth2GrantTypes'     => [ 'authorization_code', 'refresh_token' ]
		], $values ) );
	}
}

/** @deprecated class alias since 1.43 */
class_alias( MockClientEntity::class, 'MediaWiki\\Extension\\OAuth\\Tests\\Entity\\Mock_ClientEntity' );
