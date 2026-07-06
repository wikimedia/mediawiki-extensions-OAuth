<?php

namespace MediaWiki\Extension\OAuth\Repository;

use DBAccessObjectUtils;
use MediaWiki\Extension\OAuth\Backend\Consumer;
use MediaWiki\Extension\OAuth\Backend\Utils;
use Wikimedia\Rdbms\IDatabase;
use Wikimedia\Rdbms\IDBAccessObject;
use Wikimedia\Rdbms\IReadableDatabase;

/**
 * Standard implementation of ConsumerRepositoryInterface, using the oauth_registered_composer
 * table in the database determined by the virtual-oauth virtual domain.
 *
 * For now it proxies to the Consumer class, but eventually that should be switched around.
 */
class DatabaseConsumerRepository implements ConsumerRepositoryInterface {

	/** @inheritDoc */
	public function getById( int $id, int $flags = 0 ): Consumer|false {
		return Consumer::newFromId( $this->getDb( $flags ), $id, $flags );
	}

	/** @inheritDoc */
	public function getByKey(
		string $consumerKey,
		int $flags = 0
	): Consumer|false {
		return Consumer::newFromKey( $this->getDb( $flags ), $consumerKey, $flags );
	}

	/** @inheritDoc */
	public function getByNameVersionUser(
		string $name,
		string $version,
		int $centralUserId,
		int $flags = 0
	): Consumer|false {
		return Consumer::newFromNameVersionUser( $this->getDb( $flags ), $name, $version, $centralUserId, $flags );
	}

	/** @inheritDoc */
	public function save( Consumer $consumer ): bool {
		return $consumer->save( Utils::getOAuthDB( DB_PRIMARY ) );
	}

	/** @inheritDoc */
	public function delete( Consumer $consumer ): bool {
		return $consumer->delete( Utils::getOAuthDB( DB_PRIMARY ) );
	}

	private function getDb( int $flags = 0 ): IDatabase|IReadableDatabase {
		$index = DBAccessObjectUtils::hasFlags( $flags, IDBAccessObject::READ_LATEST )
			? DB_PRIMARY : DB_REPLICA;
		return Utils::getOAuthDB( $index );
	}

}
