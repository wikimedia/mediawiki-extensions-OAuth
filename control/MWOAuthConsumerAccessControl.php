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
 * MWOAuthConsumer wrapper for field view authorization
 */
class MWOAuthConsumerAccessControl extends MWOAuthDAOAccessControl {
	protected static $accessors = array(
		'name'            => 'getSafe',
		'userId'          => 'getSafe',
		'version'         => 'getSafe',
		'callbackUrl'     => 'getSafe',
		'description'     => 'getSafe',
		'email'           => 'getSafePrivate',
		'secretKey'       => 'getSafePrivate',
		'rsaKey'          => 'getSafePrivate',
		'restrictions'    => 'getSafePrivate',
	);

	protected function getSafe( $name ) {
		if ( $this->dao->get( 'deleted' )
			&& !$this->getUser()->isAllowed( 'mwoauthviewsuppressed' ) )
		{
			return null;
		} else {
			return $this->dao->get( $name );
		}
	}

	protected function getSafePrivate( $name ) {
		if ( !$this->getUser()->isAllowed( 'mwoauthviewprivate' ) ) {
			return null;
		} else {
			return $this->getSafe( $name );
		}
	}
}
