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
 * Wrapper of an MWOAuth Data Access Object to handle authorization to view fields
 *
 * A field to method name map allows for access to certain fields to be
 * handled by custom methods for private or potentially private data.
 * The name of the field is passed to the method so fields that need
 * common permission checks can use the same function if desired.
 */
abstract class MWOAuthDAOAccessControl extends ContextSource {
	/** @var MWOAuthDAO */
	protected $dao;
	/** @var RequestContext */
	protected $context;

	/** Array (field name => accessor method name) map */
	protected static $accessors;

	/**
	 * @param MWOAuthDAO $dao
	 * @param RequestContext $context
	 */
	final public function __construct( MWOAuthDAO $dao, RequestContext $context ) {
		$this->dao = $dao;
		$this->context = $context;
		// Detect mismatched DAOs or typos in the accessor map
		if ( !is_array( static::$accessors ) ) {
			throw new Exception( "Accessor map is not defined." );
		} elseif ( array_diff( array_keys( static::$accessors ), $this->dao->getFieldNames() ) ) {
			throw new Exception( "Field(s) are defined in " . get_class( $this ) .
				"that do not exist in " . get_class( $dao ) . "." );
		}
	}

	/**
	 * @param MWOAuthDAO $dao
	 * @param RequestContext $context
	 * @return MWOAuthDAOAccessControl
	 */
	final public static function wrap( $dao, RequestContext $context ) {
		if ( $dao instanceof MWOAuthDAO ) {
			return new static( $dao, $context );
		} elseif ( $dao === null || $dao === false ) {
			return $dao;
		} else {
			throw new Exception( "Expected MWOAuthDAO object, null, or false." );
		}
	}

	/**
	 * @return MWOAuthDAO
	 */
	final public function getDAO() {
		return $this->dao;
	}

	/**
	 * Get the value of a field, taking into account user permissions.
	 * An appropriate dummy value might be returned if access is denied.
	 *
	 * @param string $name
	 * @return mixed
	 * @throws Exception
	 */
	final public function get( $name ) {
		if ( isset( static::$accessors[$name] ) ) {
			$method = static::$accessors[$name];
			return $this->$method( $name );
		} else {
			return $this->dao->get( $name );
		}
	}
}
