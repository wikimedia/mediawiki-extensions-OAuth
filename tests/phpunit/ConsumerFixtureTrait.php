<?php

namespace MediaWiki\Extension\OAuth\Tests;

use MediaWiki\Extension\OAuth\Backend\Consumer;
use MediaWiki\Extension\OAuth\Entity\ClientEntity;
use MediaWiki\Extension\OAuth\Repository\DatabaseConsumerRepository;
use MediaWiki\User\UserIdentity;
use MediaWiki\Utils\MWRestrictions;
use Wikimedia\Timestamp\ConvertibleTimestamp;

/**
 * Saves consumers straight to the database, bypassing ConsumerSubmitControl, so that tests
 * need neither CentralAuth nor log entries.
 *
 * Tests using this must set MWOAuthSharedUserSource to 'local', so that the owner's central
 * user ID is their local user ID.
 */
trait ConsumerFixtureTrait {

	/**
	 * @param UserIdentity $owner
	 * @param string $name Must be unique per owner
	 * @param bool $ownerOnly
	 * @param int $stage One of the Consumer::STAGE_* constants
	 * @return Consumer
	 */
	private function createConsumer(
		UserIdentity $owner,
		string $name,
		bool $ownerOnly,
		int $stage = Consumer::STAGE_APPROVED
	): Consumer {
		$now = ConvertibleTimestamp::now();
		$consumer = Consumer::newFromArray( [
			Consumer::FIELD_ID => null,
			Consumer::FIELD_CONSUMER_KEY => bin2hex( random_bytes( 16 ) ),
			Consumer::FIELD_NAME => $name,
			Consumer::FIELD_USER_ID => $owner->getId(),
			Consumer::FIELD_VERSION => '1.0',
			Consumer::FIELD_CALLBACK_URL => 'https://example.com/callback',
			Consumer::FIELD_CALLBACK_IS_PREFIX => false,
			Consumer::FIELD_DESCRIPTION => 'A test consumer',
			Consumer::FIELD_EMAIL => 'test@example.com',
			Consumer::FIELD_EMAIL_AUTHENTICATED => $now,
			Consumer::FIELD_OAUTH_VERSION => Consumer::OAUTH_VERSION_2,
			Consumer::FIELD_DEVELOPER_AGREEMENT => true,
			Consumer::FIELD_OWNER_ONLY => $ownerOnly,
			Consumer::FIELD_WIKI => '*',
			Consumer::FIELD_GRANTS => [ 'editpage' ],
			Consumer::FIELD_REGISTRATION => $now,
			Consumer::FIELD_SECRET_KEY => bin2hex( random_bytes( 16 ) ),
			Consumer::FIELD_RSA_KEY => '',
			Consumer::FIELD_RESTRICTIONS => MWRestrictions::newDefault(),
			Consumer::FIELD_STAGE => $stage,
			Consumer::FIELD_STAGE_TIMESTAMP => $now,
			Consumer::FIELD_DELETED => false,
			Consumer::FIELD_OAUTH2_IS_CONFIDENTIAL => true,
			Consumer::FIELD_OAUTH2_GRANT_TYPES => [
				ClientEntity::GRANT_TYPE_AUTHORIZATION_CODE,
				ClientEntity::GRANT_TYPE_REFRESH_TOKEN,
			],
		] );
		( new DatabaseConsumerRepository() )->save( $consumer );
		return $consumer;
	}
}
