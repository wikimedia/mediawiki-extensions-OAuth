<?php

namespace MediaWiki\Extension\OAuth\Repository;

use DBAccessObjectUtils;
use MediaWiki\Extension\OAuth\Backend\Consumer;
use MediaWiki\Extension\OAuth\Backend\Utils;
use MediaWiki\Extension\OAuth\Entity\UserEntity;
use stdClass;
use Wikimedia\ObjectCache\MapCacheLRU;
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

	private MapCacheLRU $cacheById;
	private MapCacheLRU $cacheByKey;

	public function __construct() {
		// Cache the results of SQL queries for consumers.
		// Since Consumer objects are mutable, we only cache the rows.
		$this->cacheById = new MapCacheLRU( 10 );
		$this->cacheByKey = new MapCacheLRU( 10 );
	}

	/** @inheritDoc */
	public function newFromRow( array|stdClass $row ): Consumer {
		return Consumer::newFromRow( $this->getDb(), $row );
	}

	/** @inheritDoc */
	public function getById( int $id, int $flags = 0 ): Consumer|false {
		$db = $this->getDb( $flags );
		if ( $flags === IDBAccessObject::READ_NORMAL && $this->cacheById->has( $id ) ) {
			$row = $this->cacheById->get( $id );
		} else {
			$row = Consumer::fetchRowFromId( $db, $id, $flags );
			if ( $flags === IDBAccessObject::READ_NORMAL ) {
				$this->cacheById->set( $id, $row );
			}
		}
		if ( !$row ) {
			return false;
		}
		$cmr = Consumer::newFromRow( $db, $row );
		if ( $flags === IDBAccessObject::READ_NORMAL ) {
			$this->cacheByKey->set( $cmr->getConsumerKey(), $row );
		}
		return $cmr;
	}

	/** @inheritDoc */
	public function getByKey(
		string $consumerKey,
		int $flags = 0
	): Consumer|false {
		$db = $this->getDb( $flags );
		if ( $flags === IDBAccessObject::READ_NORMAL && $this->cacheByKey->has( $consumerKey ) ) {
			$row = $this->cacheByKey->get( $consumerKey );
		} else {
			$row = Consumer::fetchRowFromKey( $db, $consumerKey, $flags );
			if ( $flags === IDBAccessObject::READ_NORMAL ) {
				$this->cacheByKey->set( $consumerKey, $row );
			}
		}
		if ( !$row ) {
			return false;
		}
		$cmr = Consumer::newFromRow( $db, $row );
		if ( $flags === IDBAccessObject::READ_NORMAL ) {
			$this->cacheById->set( $cmr->getId(), $row );
		}
		return $cmr;
	}

	/** @inheritDoc */
	public function getByNameVersionUser(
		string $name,
		string $version,
		UserEntity $user,
		int $flags = 0
	): Consumer|false {
		return Consumer::newFromNameVersionUser( $this->getDb( $flags ), $name, $version, $user, $flags );
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
