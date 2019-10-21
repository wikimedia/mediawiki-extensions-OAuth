<?php

namespace MediaWiki\Extensions\OAuth\Entity;

use League\OAuth2\Server\Entities\RefreshTokenEntityInterface;
use League\OAuth2\Server\Entities\Traits\EntityTrait;
use League\OAuth2\Server\Entities\Traits\RefreshTokenTrait;
use JsonSerializable;

class RefreshTokenEntity implements RefreshTokenEntityInterface, JsonSerializable {
	use RefreshTokenTrait;
	use EntityTrait;

	public function jsonSerialize() {
		return [
			'identifier' => $this->getIdentifier(),
			'accessToken' => $this->getAccessToken()->getIdentifier(),
			'expires' => $this->getExpiryDateTime()->getTimestamp()
		];
	}
}
