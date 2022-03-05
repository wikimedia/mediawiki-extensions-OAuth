<?php
/**
 * @section LICENSE
 * © 2017 Wikimedia Foundation and contributors
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
 * 51 Franklin Street, Fifth Floor, Boston, MA 02110-1301, USA.
 * http://www.gnu.org/copyleft/gpl.html
 *
 * @file
 */

namespace MediaWiki\Extension\OAuth\Tests\Backend;

class StubConsumer {
	/** @var array */
	public $data;

	public function __construct( $data ) {
		$this->data = $data;
	}

	public function get( $key ) {
		return $this->data[$key];
	}

	public function getId() {
		return $this->get( 'id' );
	}

	public function getConsumerKey() {
		return $this->get( 'consumerKey' );
	}

	public function getName() {
		return $this->get( 'name' );
	}

	public function getUserId() {
		return $this->get( 'userId' );
	}

	public function getVersion() {
		return $this->get( 'version' );
	}

	public function getCallbackUrl() {
		return $this->get( 'callbackUrl' );
	}

	public function getCallbackIsPrefix() {
		return $this->get( 'callbackIsPrefix' );
	}

	public function getDescription() {
		return $this->get( 'description' );
	}

	public function getEmail() {
		return $this->get( 'email' );
	}

	public function getEmailAuthenticated() {
		return $this->get( 'emailAuthenticated' );
	}

	public function getDeveloperAgreement() {
		return $this->get( 'developerAgreement' );
	}

	public function getOwnerOnly() {
		return $this->get( 'ownerOnly' );
	}

	public function getWiki() {
		return $this->get( 'wiki' );
	}

	public function getGrants() {
		return $this->get( 'grants' );
	}

	public function getRegistration() {
		return $this->get( 'registration' );
	}

	public function getSecretKey() {
		return $this->get( 'secretKey' );
	}

	public function getRsaKey() {
		return $this->get( 'rsaKey' );
	}

	public function getRestrictions() {
		return $this->get( 'restrictions' );
	}

	public function getStage() {
		return $this->get( 'stage' );
	}

	public function getStageTimestamp() {
		return $this->get( 'stageTimestamp' );
	}

	public function getDeleted() {
		return $this->get( 'deleted' );
	}

}
