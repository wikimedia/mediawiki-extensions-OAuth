<?php

namespace MediaWiki\Extension\OAuth\Entity;

use JsonSerializable;
use League\OAuth2\Server\Entities\RefreshTokenEntityInterface;
use League\OAuth2\Server\Entities\Traits\EntityTrait;
use League\OAuth2\Server\Entities\Traits\RefreshTokenTrait;

class RefreshTokenEntity implements RefreshTokenEntityInterface, JsonSerializable {
	use RefreshTokenTrait;
	use EntityTrait;

	private ?int $graceExpiry = null;

	/**
	 * When not null, the token has been invalidated but will remain usable until this point
	 * in time, to prevent disruption during refresh operations when the client is unable to
	 * receive the new refresh token e.g. due to network error.
	 */
	public function getGraceExpiry(): ?int {
		// This method is for documentation purposes only. We only ever interact with grace expiry
		// directly in the serialized format.
		return $this->graceExpiry;
	}

	/**
	 * @phan-return array{identifier:string,accessToken:string,expires:int,graceExpires?:?int}
	 */
	public function jsonSerialize(): array {
		return [
			'identifier' => $this->getIdentifier(),
			'accessToken' => $this->getAccessToken()->getIdentifier(),
			'expires' => $this->getExpiryDateTime()->getTimestamp(),
			// added in 1.47
			'graceExpires' => $this->getGraceExpiry(),
		];
	}
}
