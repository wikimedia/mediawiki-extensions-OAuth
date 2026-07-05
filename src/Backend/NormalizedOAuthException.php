<?php

namespace MediaWiki\Extension\OAuth\Backend;

use MediaWiki\Extension\OAuth\Lib\OAuthException;
use Wikimedia\NormalizedException\INormalizedException;
use Wikimedia\NormalizedException\NormalizedExceptionTrait;

class NormalizedOAuthException extends OAuthException implements INormalizedException {

	use NormalizedExceptionTrait {
		NormalizedExceptionTrait::normalizedConstructor as __construct;
	}

}
