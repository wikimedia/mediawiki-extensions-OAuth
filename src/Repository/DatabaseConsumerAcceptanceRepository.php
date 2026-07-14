<?php

namespace MediaWiki\Extension\OAuth\Repository;

use DBAccessObjectUtils;
use MediaWiki\Extension\OAuth\Backend\Consumer;
use MediaWiki\Extension\OAuth\Backend\ConsumerAcceptance;
use MediaWiki\Extension\OAuth\Backend\Utils;
use Wikimedia\Rdbms\IDatabase;
use Wikimedia\Rdbms\IDBAccessObject;
use Wikimedia\Rdbms\IReadableDatabase;

/**
 * Standard implementation of ConsumerAcceptanceRepositoryInterface, using the
 * oauth_accepted_consumer table in the database determined by the virtual-oauth
 * virtual domain.
 *
 * For now it proxies to the ConsumerAcceptance class, but eventually that
 * should be switched around.
 */
class DatabaseConsumerAcceptanceRepository implements ConsumerAcceptanceRepositoryInterface {

	/** @inheritDoc */
	public function getById( int $id, int $flags = 0 ): ConsumerAcceptance|false {
		return ConsumerAcceptance::newFromId( $this->getDb( $flags ), $id, $flags );
	}

	/** @inheritDoc */
	public function getByToken(
		string $token,
		int $flags = 0
	): ConsumerAcceptance|false {
		return ConsumerAcceptance::newFromToken( $this->getDb( $flags ), $token, $flags );
	}

	/** @inheritDoc */
	public function getByUserConsumerWiki(
		int $centralUserId,
		Consumer $consumer,
		string $wiki,
		int $flags = 0
	): ConsumerAcceptance|false {
		return ConsumerAcceptance::newFromUserConsumerWiki(
			$this->getDb( $flags ), $centralUserId, $consumer, $wiki, $flags
		);
	}

	/** @inheritDoc */
	public function save( ConsumerAcceptance $acceptance ): bool {
		return $acceptance->save( Utils::getOAuthDB( DB_PRIMARY ) );
	}

	/** @inheritDoc */
	public function delete( ConsumerAcceptance $acceptance ): bool {
		return $acceptance->delete( Utils::getOAuthDB( DB_PRIMARY ) );
	}

	private function getDb( int $flags = 0 ): IDatabase|IReadableDatabase {
		$index = DBAccessObjectUtils::hasFlags( $flags, IDBAccessObject::READ_LATEST )
			? DB_PRIMARY : DB_REPLICA;
		return Utils::getOAuthDB( $index );
	}

}
