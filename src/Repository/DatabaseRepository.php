<?php

namespace MediaWiki\Extension\OAuth\Repository;

use MediaWiki\Extension\OAuth\Backend\Utils;
use Wikimedia\Rdbms\IDatabase;

abstract class DatabaseRepository {

	/**
	 * @param int $index
	 * @return IDatabase
	 */
	public function getDB( $index = DB_REPLICA ) {
		return Utils::getCentralDB( $index );
	}

	/**
	 * Is given identifier stored in the DB
	 *
	 * @param string $identifier
	 * @return bool
	 */
	public function identifierExists( $identifier ) {
		return $this->getDB()->newSelectQueryBuilder()
			->select( $this->getIdentifierField() )
			->from( $this->getTableName() )
			->where( [ $this->getIdentifierField() => $identifier ] )
			->caller( __METHOD__ )
			->fetchRow() !== false;
	}

	abstract protected function getTableName(): string;

	abstract protected function getIdentifierField(): string;
}
