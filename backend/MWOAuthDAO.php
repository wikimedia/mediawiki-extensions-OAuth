<?php
/*
 (c) Aaron Schulz 2013, GPL

 This program is free software; you can redistribute it and/or modify
 it under the terms of the GNU General Public License as published by
 the Free Software Foundation; either version 2 of the License, or
 (at your option) any later version.

 This program is distributed in the hope that it will be useful,
 but WITHOUT ANY WARRANTY; without even the implied warranty of
 MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 GNU General Public License for more details.

 You should have received a copy of the GNU General Public License along
 with this program; if not, write to the Free Software Foundation, Inc.,
 59 Temple Place - Suite 330, Boston, MA 02111-1307, USA.
 http://www.gnu.org/copyleft/gpl.html
*/

/**
 * Representation of a Data Access Object
 */
abstract class MWOAuthDAO implements IDBAccessObject {
	/**
	 * @throws Exception
	 */
	final protected function __construct() {
		$fields = array_keys( static::getFieldPermissionChecks() );
		if ( array_diff( $fields, $this->getFieldNames() ) ) {
			throw new Exception( "Invalid field(s) defined in access check methods." );
		}
	}

	/**
	 * @param array $values (field => value) map
	 * @return MWOAuthDAO
	 */
	final public static function newFromArray( array $values ) {
		$consumer = new static();
		$consumer->loadFromValues( $values );
		return $consumer;
	}

	/**
	 * @param DatabaseBase $db
	 * @param array|stdClass $row
	 * @return MWOAuthDAO
	 */
	final public static function newFromRow( DatabaseBase $db, $row ) {
		$consumer = new static();
		$consumer->loadFromRow( $db, $row );
		return $consumer;
	}

	/**
	 * @param DatabaseBase $db
	 * @param integer $id
	 * @param integer $flags MWOAuthDAO::READ_* bitfield
	 * @return MWOAuthDAO|bool Returns false if not found
	 * @throws DBError
	 */
	final public static function newFromId( DatabaseBase $db, $id, $flags = 0 ) {
		$row = $db->selectRow( static::getTable(),
			array_values( static::getFieldColumnMap() ),
			array( static::getIdColumn() => $id ),
			__METHOD__,
			( $flags & self::READ_LOCKING ) ? array( 'FOR UPDATE' ) : array()
		);

		if ( $row ) {
			$consumer = new static();
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
	 * @throws Exception
	 */
	final public function get( $name ) {
		if ( !static::hasField( $name ) ) {
			throw new Exception( "Object has no '$name' field." );
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
		$old = $this->setFields( array( $name => $value ) );
		return $old[$name];
	}

	/**
	 * Set the values for a set of fields
	 *
	 * @param array $values (field => value) map
	 * @return array Map of old values
	 */
	final public function setFields( array $values ) {
		$old = array();
		foreach ( $values as $name => $value ) {
			if ( !static::hasField( $name )  ) {
				throw new Exception( "Object has no '$name' field." );
			}
			$old[$name] = $this->$name;
			$this->$name = $value;
		}
		$this->normalizeValues();
		return $old;
	}

	/**
	 * @return array
	 */
	final public function getFieldNames() {
		return array_keys( static::getFieldColumnMap() );
	}

	/**
	 * @param DatabaseBase $dbw
	 * @return bool
	 * @throws DBError
	 */
	public function save( DatabaseBase $dbw ) {
		$uniqueId = $this->getIdValue();
		$idColumn = static::getIdColumn();
		if ( $uniqueId ) {
			$dbw->update(
				static::getTable(),
				$this->getRowArray( $dbw ),
				array( $idColumn => $uniqueId ),
				__METHOD__
			);
			return $dbw->affectedRows() > 0;
		} else {
			$dbw->insert(
				static::getTable(),
				$this->getRowArray( $dbw ),
				__METHOD__
			);
			return true;
		}
	}

	/**
	 * @param DatabaseBase $dbw
	 * @return bool
	 * @throws DBError
	 */
	public function delete( DatabaseBase $dbw ) {
		$uniqueId = $this->getIdValue();
		$idColumn = static::getIdColumn();
		if ( $uniqueId ) {
			$dbw->delete(
				static::getTable(),
				array( $idColumn => $uniqueId ),
				__METHOD__
			);
			return $dbw->affectedRows() > 0;
		} else {
			return false;
		}
	}

	/**
	 * Check if a user (from the context) can view a field
	 *
	 * @see MWOAuthDAO::userCanAccess()
	 * @see MWOAuthDAOAccessControl
	 *
	 * @param string $name
	 * @param RequestContext $context
	 * @return Message|true Returns on success or a Message if the user lacks access
	 * @throws Exception
	 */
	final public function userCanAccess( $name, RequestContext $context ) {
		$map = static::getFieldPermissionChecks();
		if ( isset( $map[$name] ) ) {
			$method = $map[$name];
			return $this->$method( $name, $context );
		} else {
			return true;
		}
	}

	/**
	 * Get the schema information for this object type
	 *
	 * This should return an associative array with:
	 *   - idField        : a field with an integer/hex UNIQUE identifier
	 *   - table          : a table name
	 *   - fieldColumnMap : a map of field names to column names
	 *
	 * @return array
	 */
	protected static function getSchema() {
		// Note: declaring this abstract raises E_STRICT
		throw new Exception( "getSchema() not defined in " . get_class() );
	}

	/**
	 * Get the access control check methods for this object type
	 *
	 * This returns a map of field names to method names.
	 * The methods check if a context user has access to the field,
	 * returning true if they do and a Message object otherwise.
	 * The methods take (field name, RequestContext) as arguments.
	 *
	 * @see MWOAuthDAO::userCanAccess()
	 * @see MWOAuthDAOAccessControl
	 *
	 * @return array Map of (field name => name of method that checks access)
	 */
	protected static function getFieldPermissionChecks() {
		// Note: declaring this abstract raises E_STRICT
		throw new Exception( "getFieldPermissionChecks() not defined in " . get_class() );
	}

	/**
	 * @return string
	 */
	final protected static function getTable() {
		$schema = static::getSchema();
		return $schema['table'];
	}

	/**
	 * @return array
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
	 * @return void
	 */
	final protected function loadFromValues( array $values ) {
		foreach ( static::getFieldColumnMap() as $field => $column ) {
			if ( !array_key_exists( $field, $values ) ) {
				throw new Exception( get_class( $this ) . " requires '$field' field." );
			}
			$this->$field = $values[$field];
		}
		$this->normalizeValues();
	}

	/**
	 * Subclasses should make this normalize fields (e.g. timestamps)
	 *
	 * @return void
	 */
	abstract protected function normalizeValues();

	/**
	 * @param DatabaseBase $db
	 * @param stdClass|array $row
	 * @return void
	 */
	final protected function loadFromRow( DatabaseBase $db, $row ) {
		$row = $this->decodeRow( $db, (array)$row );
		$values = array();
		foreach ( static::getFieldColumnMap() as $field => $column ) {
			$values[$field] = $row[$column];
		}
		$this->loadFromValues( $values );
	}

	/**
	 * Subclasses should make this to encode DB fields (e.g. timestamps).
	 * This must also flatten any PHP data structures into flat values.
	 *
	 * @param DatabaseBase $db
	 * @param array $row
	 * @return array
	 */
	abstract protected function encodeRow( DatabaseBase $db, $row );

	/**
	 * Subclasses should make this to decode DB fields (e.g. timestamps).
	 * This can also expand some flat values (e.g. JSON) into PHP data structures.
	 * Note: this does not need to handle what normalizeValues() already does.
	 *
	 * @param DatabaseBase $db
	 * @param array $row
	 * @return array
	 */
	abstract protected function decodeRow( DatabaseBase $db, $row );

	/**
	 * @param DatabaseBase $db
	 * @return array
	 */
	final protected function getRowArray( DatabaseBase $db ) {
		$row = array();
		foreach ( static::getFieldColumnMap() as $field => $column ) {
			$row[$column] = $this->$field;
		}
		return $this->encodeRow( $db, $row );
	}
}
