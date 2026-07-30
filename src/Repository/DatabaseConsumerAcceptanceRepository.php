<?php

namespace MediaWiki\Extension\OAuth\Repository;

use DBAccessObjectUtils;
use MediaWiki\Extension\OAuth\Backend\Consumer;
use MediaWiki\Extension\OAuth\Backend\ConsumerAcceptance;
use MediaWiki\Extension\OAuth\Backend\Utils;
use MediaWiki\Extension\OAuth\Entity\UserEntity;
use stdClass;
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
	public function newFromRow( array|stdClass $row ): ConsumerAcceptance {
		return ConsumerAcceptance::newFromRow( $this->getDb(), $row );
	}

	/** @inheritDoc */
	public function getById( int $id, int $flags = 0 ): ConsumerAcceptance|false {
		$queryBuilder = $this->getDb( $flags )->newSelectQueryBuilder()
			->select( array_values( ConsumerAcceptance::getFieldColumnMap() ) )
			->from( ConsumerAcceptance::getTable() )
			->where( [ ConsumerAcceptance::getIdColumn() => $id ] )
			->caller( __METHOD__ );
		if ( $flags & IDBAccessObject::READ_LOCKING ) {
			$queryBuilder->forUpdate();
		}
		$row = $queryBuilder->fetchRow();
		return $row ? $this->newFromRow( $row ) : false;
	}

	/** @inheritDoc */
	public function getByToken(
		string $token,
		int $flags = 0
	): ConsumerAcceptance|false {
		$queryBuilder = $this->getDb( $flags )->newSelectQueryBuilder()
			->select( array_values( ConsumerAcceptance::getFieldColumnMap() ) )
			->from( ConsumerAcceptance::getTable() )
			->where( [ 'oaac_access_token' => $token ] )
			->caller( __METHOD__ );
		if ( $flags & IDBAccessObject::READ_LOCKING ) {
			$queryBuilder->forUpdate();
		}
		$row = $queryBuilder->fetchRow();
		return $row ? $this->newFromRow( $row ) : false;
	}

	/** @inheritDoc */
	public function getByUserConsumerWiki(
		UserEntity $user,
		Consumer $consumer,
		string $wiki,
		int $flags = 0
	): ConsumerAcceptance|false {
		$queryBuilder = $this->getDb( $flags )->newSelectQueryBuilder()
			->select( array_values( ConsumerAcceptance::getFieldColumnMap() ) )
			->from( ConsumerAcceptance::getTable() )
			->where( [
				'oaac_user_id' => $user->getCentralId(),
				'oaac_consumer_id' => $consumer->getId(),
				'oaac_oauth_version' => $consumer->getOAuthVersion(),
				'oaac_wiki' => $wiki
			] )
			->caller( __METHOD__ );
		if ( $flags & IDBAccessObject::READ_LOCKING ) {
			$queryBuilder->forUpdate();
		}
		$row = $queryBuilder->fetchRow();
		return $row ? $this->newFromRow( $row ) : false;
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
