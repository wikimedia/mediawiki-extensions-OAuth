<?php

namespace MediaWiki\Extension\OAuth\Repository;

use MediaWiki\Extension\OAuth\Backend\Utils;
use Wikimedia\ObjectCache\BagOStuff;

abstract class CacheRepository {

	/**
	 * @var BagOStuff
	 */
	protected $cache;

	/**
	 * @return static
	 */
	public static function factory() {
		$cache = Utils::getSessionCache();

		// @phan-suppress-next-line PhanTypeInstantiateAbstractStatic
		return new static( $cache );
	}

	/**
	 * @param BagOStuff $cache
	 */
	protected function __construct( BagOStuff $cache ) {
		$this->cache = $cache;
	}

	/**
	 * Get object type for session key
	 *
	 * @return string
	 */
	abstract protected function getCacheKeyType(): string;

	/**
	 * Get the cache key based on unique identifier
	 *
	 * @param string $id
	 * @return string
	 */
	protected function getCacheKey( $id ) {
		return Utils::getCacheKey( $this->getCacheKeyType(), $id );
	}

	/**
	 * @param string $identifier
	 * @param int $flags
	 * @return mixed
	 */
	protected function get( $identifier, $flags = 0 ) {
		return $this->cache->get( $this->getCacheKey( $identifier ), $flags );
	}

	/**
	 * @param string $identifier
	 * @param mixed $value
	 * @param int $expires
	 * @param int $flags
	 */
	protected function set( $identifier, $value, $expires = 0, $flags = 0 ) {
		$this->cache->add( $this->getCacheKey( $identifier ), $value, $expires, $flags );
	}

	/**
	 * @param string $identifier
	 * @param int $flags
	 */
	protected function delete( $identifier, $flags = 0 ) {
		$this->cache->delete( $this->getCacheKey( $identifier ), $flags );
	}

	/**
	 * Convenience method to determine if given key exists in cache
	 *
	 * @param string $identifier
	 * @return bool
	 */
	protected function has( $identifier ) {
		return $this->cache->get( $this->getCacheKey( $identifier ) ) !== false;
	}
}
