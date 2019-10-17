<?php

namespace MediaWiki\Extensions\OAuth;

/**
 * (c) Dejan Savuljesku 2019, GPL
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
 * This class mainly exists to enable clean separation
 * of OAuth 1.0a and OAuth 2.0 code
 *
 * Representation of an OAuth 1.0a consumer.
 */
class OAuth1Consumer extends MWOAuthConsumer {

	/**
	 * @return string
	 */
	public function getOAuthVersion() {
		return static::OAUTH_VERSION_1;
	}
}
