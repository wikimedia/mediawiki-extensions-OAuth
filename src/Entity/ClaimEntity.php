<?php

namespace MediaWiki\Extension\OAuth\Entity;

class ClaimEntity {

	public function __construct(
		protected string $name,
		protected mixed $value
	) {
	}

	public function getName(): string {
		return $this->name;
	}

	public function getValue(): mixed {
		return $this->value;
	}

}
