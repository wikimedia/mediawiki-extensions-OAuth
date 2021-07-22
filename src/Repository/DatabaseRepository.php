<?php

namespace MediaWiki\Extensions\OAuth\Repository;

use MediaWiki\Extensions\OAuth\Backend\Utils;
use Wikimedia\Rdbms\DBConnRef;

abstract class DatabaseRepository {

	/**
	 * @param int $index
	 * @return DBConnRef
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
		return $this->getDB()->selectRow(
			$this->getTableName(),
			[ $this->getIdentifierField() ],
			[ $this->getIdentifierField() => $identifier ],
			__METHOD__
		) !== false;
	}

	abstract protected function getTableName(): string;

	abstract protected function getIdentifierField(): string;
}
