<?php

namespace MediaWiki\Extension\OAuth\Entity;

use League\OAuth2\Server\Entities\ClaimEntityInterface;
use League\OAuth2\Server\Entities\Traits\ClaimEntityTrait;

class ClaimEntity implements ClaimEntityInterface {
	use ClaimEntityTrait;

	/**
	 * ClaimEntity constructor.
	 * @param string $name
	 * @param mixed $value
	 */
	public function __construct( string $name, $value ) {
		$this->name = $name;
		$this->value = $value;
	}
}
