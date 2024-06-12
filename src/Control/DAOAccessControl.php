<?php
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

namespace MediaWiki\Extension\OAuth\Control;

use LogicException;
use MediaWiki\Context\ContextSource;
use MediaWiki\Context\IContextSource;
use MediaWiki\Extension\OAuth\Backend\MWOAuthDAO;
use MediaWiki\Message\Message;

/**
 * Wrapper of an MWOAuthDAO that handles authorization to view fields
 */
class DAOAccessControl extends ContextSource {
	/** @var MWOAuthDAO */
	protected $dao;

	/**
	 * @param MWOAuthDAO $dao
	 * @param IContextSource $context
	 */
	final protected function __construct( MWOAuthDAO $dao, IContextSource $context ) {
		$this->dao = $dao;
		$this->setContext( $context );
	}

	/**
	 * @param MWOAuthDAO|false|null $dao
	 * @param IContextSource $context
	 * @throws LogicException
	 * @return static|null|false
	 */
	final public static function wrap( $dao, IContextSource $context ) {
		if ( $dao instanceof MWOAuthDAO ) {
			return new static( $dao, $context );
		} elseif ( $dao === null || $dao === false ) {
			return $dao;
		} else {
			throw new LogicException( "Expected MWOAuthDAO object, null, or false." );
		}
	}

	/**
	 * @return MWOAuthDAO
	 */
	public function getDAO() {
		return $this->dao;
	}

	/**
	 * Helper to make return value of get() safe for wikitext
	 *
	 * @param Message|string $value
	 * @return string For use in wikitext
	 * @param-taint $value escapes_escaped
	 */
	final public function escapeForWikitext( $value ) {
		if ( $value instanceof Message ) {
			return wfEscapeWikiText( $value->plain() );
		} else {
			return wfEscapeWikiText( $value );
		}
	}

	/**
	 * Helper to make return value of get() safe for HTML
	 *
	 * @param Message|string $value
	 * @return string HTML escaped
	 * @param-taint $value escapes_escaped
	 */
	final public function escapeForHtml( $value ) {
		if ( $value instanceof Message ) {
			return $value->parse();
		} else {
			return htmlspecialchars( $value );
		}
	}

	/**
	 * Get the value of a field, taking into account user permissions.
	 * An appropriate Message will be returned if access is denied.
	 *
	 * @param string $name
	 * @param callback|null $sCallback Optional callback to apply to result on access success
	 * @return mixed Returns a Message on access failure
	 */
	final public function get( $name, $sCallback = null ) {
		$msg = $this->dao->userCanAccess( $name, $this->getContext() );
		if ( $msg !== true ) {
			// should be a Message object
			return $msg;
		} else {
			$value = $this->dao->get( $name );
			return $sCallback ? call_user_func( $sCallback, $value ) : $value;
		}
	}

	/**
	 * Check whether the user can access the given field(s).
	 * @param string|array $names A field name or a list of names.
	 * @return bool
	 */
	final public function userCanAccess( $names ) {
		foreach ( (array)$names as $name ) {
			if ( !$this->dao->userCanAccess( $name, $this->getContext() ) ) {
				return false;
			}
		}
		return true;
	}
}
