<?php

namespace MediaWiki\Extension\OAuth\Entity;

use League\OAuth2\Server\Entities\ClientEntityInterface;

/**
 * Interface MWClientEntityInterface
 *
 * Empty interface used by ClientEntity and OAuthClaimStoreGetClaimsHook to not leak ClientEntityInterface to
 * other extensions that depend on this OAuth extension.
 */
interface MWClientEntityInterface extends ClientEntityInterface {

}

class_alias( MWClientEntityInterface::class, 'MediaWiki\Extensions\OAuth\Entity\MWClientEntityInterface' );
