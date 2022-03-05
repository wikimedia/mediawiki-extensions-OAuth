<?php

namespace MediaWiki\Extension\OAuth\Entity;

use League\OAuth2\Server\Entities\ScopeEntityInterface;
use League\OAuth2\Server\Entities\Traits\EntityTrait;

class ScopeEntity implements ScopeEntityInterface {
	use EntityTrait;

	/**
	 * Create generic scope entity
	 *
	 * @param string $identifier
	 */
	public function __construct( $identifier ) {
		$this->identifier = $identifier;
	}

	/**
	 * @return string
	 */
	public function jsonSerialize() {
		return $this->getIdentifier();
	}
}
