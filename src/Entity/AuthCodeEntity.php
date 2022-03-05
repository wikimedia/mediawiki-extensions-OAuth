<?php

namespace MediaWiki\Extension\OAuth\Entity;

use JsonSerializable;
use League\OAuth2\Server\Entities\AuthCodeEntityInterface;
use League\OAuth2\Server\Entities\Traits\AuthCodeTrait;
use League\OAuth2\Server\Entities\Traits\EntityTrait;
use League\OAuth2\Server\Entities\Traits\TokenEntityTrait;

class AuthCodeEntity implements AuthCodeEntityInterface, JsonSerializable {
	use TokenEntityTrait;
	use EntityTrait;
	use AuthCodeTrait;

	public function jsonSerialize() {
		return [
			'user' => $this->getUserIdentifier(),
			'client' => $this->getClient()->getIdentifier(),
			'identifier' => $this->getIdentifier(),
			'redirectUri' => $this->getRedirectUri(),
			'scopes' => $this->getScopes(),
			'expires' => $this->getExpiryDateTime()->getTimestamp()
		];
	}
}
