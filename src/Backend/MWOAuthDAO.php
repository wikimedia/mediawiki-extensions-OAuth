<?php

namespace MediaWiki\Extension\OAuth\Backend;

use Exception;
use LogicException;
use MediaWiki\Context\IContextSource;
use MediaWiki\Logger\LoggerFactory;
use MediaWiki\Message\Message;
use MWException;
use Psr\Log\LoggerInterface;
use stdClass;
use Wikimedia\Rdbms\DBError;
use Wikimedia\Rdbms\DBReadOnlyError;
use Wikimedia\Rdbms\IDatabase;
use Wikimedia\Rdbms\IDBAccessObject;

/**
 * (c) Aaron Schulz 2013, GPL
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
 * 59 Temple Place - Suite 330, Boston, MA 02111-1307, USA.
 * http://www.gnu.org/copyleft/gpl.html
 */

/**
 * Representation of a Data Access Object
 */
abstract class MWOAuthDAO {
	/** @var string object construction origin */
	private $daoOrigin = 'new';
	/** @var bool whether fields changed or the field is new */
	private $daoPending = true;

	/** @var LoggerInterface */
	protected $logger;

	/**
	 * @throws LogicException
	 */
	final protected function __construct() {
		$fields = array_keys( static::getFieldPermissionChecks() );
		if ( array_diff( $fields, $this->getFieldNames() ) ) {
			throw new LogicException( "Invalid field(s) defined in access check methods." );
		}
		$this->logger = LoggerFactory::getInstance( 'OAuth' );
	}

	/**
	 * @param array $values (field => value) map
	 * @return static
	 */
	final public static function newFromArray( array $values ) {
		$class = static::getConsumerClass( $values );
		$consumer = new $class();

		// Make sure oauth_version is set - for backwards compat
		$values['oauth_version'] ??= Consumer::OAUTH_VERSION_1;
		$consumer->loadFromValues( $values );
		return $consumer;
	}

	/**
	 * Determine and return the correct consumer class
	 *
	 * @param array $data
	 * @return string
	 */
	protected static function getConsumerClass( array $data ) {
		return static::class;
	}

	/**
	 * @param IDatabase $db
	 * @param array|stdClass $row
	 * @return static
	 */
	final public static function newFromRow( IDatabase $db, $row ) {
		$class = static::getConsumerClass( (array)$row );
		$consumer = new $class();
		$consumer->loadFromRow( $db, $row );
		return $consumer;
	}

	/**
	 * @param IDatabase $db
	 * @param int $id
	 * @param int $flags IDBAccessObject::READ_* bitfield
	 * @return static|bool Returns false if not found
	 * @throws DBError
	 */
	final public static function newFromId( IDatabase $db, $id, $flags = 0 ) {
		$queryBuilder = $db->newSelectQueryBuilder()
			->select( array_values( static::getFieldColumnMap() ) )
			->from( static::getTable() )
			->where( [ static::getIdColumn() => (int)$id ] )
			->caller( __METHOD__ );
		if ( $flags & IDBAccessObject::READ_LOCKING ) {
			$queryBuilder->forUpdate();
		}
		$row = $queryBuilder->fetchRow();

		if ( $row ) {
			$class = static::getConsumerClass( (array)$row );
			$consumer = new $class();
			$consumer->loadFromRow( $db, $row );
			return $consumer;
		} else {
			return false;
		}
	}

	/**
	 * Get the value of a field
	 *
	 * @param string $name
	 * @return mixed
	 * @throws LogicException
	 */
	final public function get( $name ) {
		if ( !static::hasField( $name ) ) {
			throw new LogicException( "Object has no '$name' field." );
		}
		return $this->$name;
	}

	/**
	 * Set the value of a field
	 *
	 * @param string $name
	 * @param mixed $value
	 * @return mixed The old value
	 * @throws Exception
	 */
	final public function setField( $name, $value ) {
		$old = $this->setFields( [ $name => $value ] );
		return $old[$name];
	}

	/**
	 * Set the values for a set of fields
	 *
	 * @param array $values (field => value) map
	 * @throws LogicException
	 * @return array Map of old values
	 */
	final public function setFields( array $values ) {
		$old = [];
		foreach ( $values as $name => $value ) {
			if ( !static::hasField( $name ) ) {
				throw new LogicException( "Object has no '$name' field." );
			}
			$old[$name] = $this->$name;
			$this->$name = $value;
			if ( $old[$name] !== $value ) {
				$this->daoPending = true;
			}
		}
		$this->normalizeValues();
		return $old;
	}

	/**
	 * @return string[]
	 */
	final public function getFieldNames() {
		return array_keys( static::getFieldColumnMap() );
	}

	/**
	 * @param IDatabase $dbw
	 * @return bool
	 * @throws DBReadOnlyError
	 */
	public function save( IDatabase $dbw ) {
		global $wgMWOAuthReadOnly;

		$uniqueId = $this->getIdValue();
		$idColumn = static::getIdColumn();
		if ( $wgMWOAuthReadOnly ) {
			throw new DBReadOnlyError( $dbw, __CLASS__ . ": tried to save while db is read-only" );
		}
		if ( $this->daoOrigin === 'db' ) {
			if ( $this->daoPending ) {
				$this->logger->debug( get_class( $this ) . ': performing DB update; object changed.' );
				$dbw->newUpdateQueryBuilder()
					->update( static::getTable() )
					->set( $this->getRowArray( $dbw ) )
					->where( [ $idColumn => $uniqueId ] )
					->caller( __METHOD__ )
					->execute();
				$this->daoPending = false;
				return $dbw->affectedRows() > 0;
			} else {
				$this->logger->debug( get_class( $this ) . ': skipping DB update; object unchanged.' );
				return false;
			}
		} else {
			$this->logger->debug( get_class( $this ) . ': performing DB update; new object.' );
			$afield = static::getAutoIncrField();
			$acolumn = $afield !== null ? static::getColumn( $afield ) : null;
			$row = $this->getRowArray( $dbw );
			if ( $acolumn !== null && $row[$acolumn] === null ) {
				// auto-increment field should be omitted, not set null, for
				// auto-incrementing behavior
				unset( $row[$acolumn] );
			}
			$dbw->newInsertQueryBuilder()
				->insertInto( static::getTable() )
				->row( $row )
				->caller( __METHOD__ )
				->execute();
			if ( $afield !== null ) {
				// update field for auto-increment field
				$this->$afield = $dbw->insertId();
			}
			$this->daoPending = false;
			return true;
		}
	}

	/**
	 * @param IDatabase $dbw
	 * @return bool
	 * @throws DBReadOnlyError
	 */
	public function delete( IDatabase $dbw ) {
		global $wgMWOAuthReadOnly;

		$uniqueId = $this->getIdValue();
		$idColumn = static::getIdColumn();
		if ( $wgMWOAuthReadOnly ) {
			throw new DBReadOnlyError( $dbw, __CLASS__ . ": tried to delete while db is read-only" );
		}
		if ( $this->daoOrigin === 'db' ) {
			$dbw->newDeleteQueryBuilder()
				->deleteFrom( static::getTable() )
				->where( [ $idColumn => $uniqueId ] )
				->caller( __METHOD__ )
				->execute();
			$this->daoPending = true;
			return $dbw->affectedRows() > 0;
		} else {
			return false;
		}
	}

	/**
	 * Get the schema information for this object type
	 *
	 * This should return an associative array with:
	 *   - idField        : a field with an int/hex UNIQUE identifier
	 *   - autoIncrField  : a field that auto-increments in the DB (or NULL if none)
	 *   - table          : a table name
	 *   - fieldColumnMap : a map of field names to column names
	 *
	 * @return array
	 */
	protected static function getSchema() {
		// Note: declaring this abstract raises E_STRICT
		throw new MWException( "getSchema() not defined in " . self::class );
	}

	/**
	 * Get the access control check methods for this object type
	 *
	 * This returns a map of field names to method names.
	 * The methods check if a context user has access to the field,
	 * returning true if they do and a Message object otherwise.
	 * The methods take (field name, IContextSource) as arguments.
	 *
	 * @see MWOAuthDAO::userCanAccess()
	 * @see MWOAuthDAOAccessControl
	 *
	 * @throws LogicException Subclasses must override
	 * @return array<string,string> Map of (field name => name of method that checks access)
	 */
	protected static function getFieldPermissionChecks() {
		// Note: declaring this abstract raises E_STRICT
		throw new LogicException( "getFieldPermissionChecks() not defined in " . self::class );
	}

	/**
	 * @return string
	 */
	final protected static function getTable() {
		$schema = static::getSchema();
		return $schema['table'];
	}

	/**
	 * @return array<string,string>
	 */
	final protected static function getFieldColumnMap() {
		$schema = static::getSchema();
		return $schema['fieldColumnMap'];
	}

	/**
	 * @param string $field
	 * @return string
	 */
	final protected static function getColumn( $field ) {
		$schema = static::getSchema();
		return $schema['fieldColumnMap'][$field];
	}

	/**
	 * @param string $field
	 * @return bool
	 */
	final protected static function hasField( $field ) {
		$schema = static::getSchema();
		return isset( $schema['fieldColumnMap'][$field] );
	}

	/**
	 * @return string|null
	 */
	final protected static function getAutoIncrField() {
		$schema = static::getSchema();
		return $schema['autoIncrField'] ?? null;
	}

	/**
	 * @return string
	 */
	final protected static function getIdColumn() {
		$schema = static::getSchema();
		return $schema['fieldColumnMap'][$schema['idField']];
	}

	/**
	 * @return int|string
	 */
	final protected function getIdValue() {
		$schema = static::getSchema();
		$field = $schema['idField'];
		return $this->$field;
	}

	/**
	 * @param array $values
	 */
	final protected function loadFromValues( array $values ) {
		foreach ( static::getFieldColumnMap() as $field => $column ) {
			if ( !array_key_exists( $field, $values ) ) {
				throw new MWException( get_class( $this ) . " requires '$field' field." );
			}
			$this->$field = $values[$field];
		}
		$this->normalizeValues();
		$this->daoOrigin = 'new';
		$this->daoPending = true;
	}

	/**
	 * Subclasses should make this normalize fields (e.g. timestamps)
	 *
	 * @return void
	 */
	abstract protected function normalizeValues();

	/**
	 * @param IDatabase $db
	 * @param stdClass|array $row
	 * @return void
	 */
	final protected function loadFromRow( IDatabase $db, $row ) {
		$row = $this->decodeRow( $db, (array)$row );
		$values = [];
		foreach ( static::getFieldColumnMap() as $field => $column ) {
			$values[$field] = $row[$column];
		}
		$this->loadFromValues( $values );
		$this->daoOrigin = 'db';
		$this->daoPending = false;
	}

	/**
	 * Subclasses should make this to encode DB fields (e.g. timestamps).
	 * This must also flatten any PHP data structures into flat values.
	 *
	 * @param IDatabase $db
	 * @param array $row
	 * @return array
	 */
	abstract protected function encodeRow( IDatabase $db, $row );

	/**
	 * Subclasses should make this to decode DB fields (e.g. timestamps).
	 * This can also expand some flat values (e.g. JSON) into PHP data structures.
	 * Note: this does not need to handle what normalizeValues() already does.
	 *
	 * @param IDatabase $db
	 * @param array $row
	 * @return array
	 */
	abstract protected function decodeRow( IDatabase $db, $row );

	/**
	 * @param IDatabase $db
	 * @return array
	 */
	final protected function getRowArray( IDatabase $db ) {
		$row = [];
		foreach ( static::getFieldColumnMap() as $field => $column ) {
			$row[$column] = $this->$field;
		}
		return $this->encodeRow( $db, $row );
	}

	/**
	 * Check if a user (from the context) can view a field
	 *
	 * @see MWOAuthDAO::userCanAccess()
	 * @see MWOAuthDAOAccessControl
	 *
	 * @param string $name
	 * @param IContextSource $context
	 * @return Message|true Returns on success or a Message if the user lacks access
	 */
	final public function userCanAccess( $name, IContextSource $context ) {
		$map = static::getFieldPermissionChecks();
		if ( isset( $map[$name] ) ) {
			$method = $map[$name];
			return $this->$method( $name, $context );
		} else {
			return true;
		}
	}

	/**
	 * Get the current conflict token value for a user
	 *
	 * @param IContextSource $context
	 * @return string Hex token
	 */
	final public function getChangeToken( IContextSource $context ) {
		$map = [];
		foreach ( $this->getFieldNames() as $field ) {
			if ( $this->userCanAccess( $field, $context ) ) {
				$map[$field] = $this->$field;
			} else {
				// don't convey this information
				$map[$field] = null;
			}
		}
		return hash_hmac(
			'sha1',
			serialize( $map ),
			$context->getUser()->getId() . '#' . $this->getIdValue()
		);
	}

	/**
	 * Compare an old change token to the current one
	 *
	 * @param IContextSource $context
	 * @param string $oldToken
	 * @return bool Whether the current is unchanged
	 */
	final public function checkChangeToken( IContextSource $context, $oldToken ) {
		return ( $this->getChangeToken( $context ) === $oldToken );
	}

	/**
	 * Update whether this object should be written to the data store
	 * @param bool $pending set to true to mark this object as needing to write its data
	 */
	public function setPending( $pending ) {
		$this->daoPending = $pending;
	}

	/**
	 * Update the origin of this object
	 * @param string $source source of the object
	 * 	'new': Treat this as a new object to the datastore (insert on save)
	 * 	'db': Treat this as already in the datastore (update on save)
	 */
	public function updateOrigin( $source ) {
		$this->daoOrigin = $source;
	}
}
