<?php

namespace MediaWiki\Extension\OAuth\Repository;

use MediaWiki\Extension\OAuth\Backend\Consumer;
use MediaWiki\Extension\OAuth\Backend\ConsumerAcceptance;
use Wikimedia\Rdbms\DBReadOnlyError;

/**
 * Service for loading and saving consumer acceptances.
 *
 * Does not handle list-related queries (ManageMyGrants), which involve a pager
 * rather than directly returning ConsumerAcceptance objects.
 */
interface ConsumerAcceptanceRepositoryInterface {

	/**
	 * Get a consumer acceptance by its database primary key (oaac_id).
	 *
	 * @param int $id Database ID.
	 * @param int $flags IDBAccessObject::READ_* bitfield
	 */
	public function getById( int $id, int $flags = 0 ): ConsumerAcceptance|false;

	/**
	 * Get a consumer acceptance by its access token.
	 *
	 * @param string $token Access token
	 * @param int $flags IDBAccessObject::READ_* bitfield
	 */
	public function getByToken(
		string $token,
		int $flags = 0
	): ConsumerAcceptance|false;

	/**
	 * Get a consumer acceptance by user ID, consumer, and wiki.
	 *
	 * @param int $centralUserId Central user ID of the authorizing user.
	 * @param Consumer $consumer
	 * @param string $wiki Wiki ID or '*' for all.
	 * @param int $flags IDBAccessObject::READ_* bitfield
	 */
	public function getByUserConsumerWiki(
		int $centralUserId,
		Consumer $consumer,
		string $wiki,
		int $flags = 0
	): ConsumerAcceptance|false;

	/**
	 * Insert or update the consumer acceptance in the DB.
	 *
	 * After an insert, the ConsumerAcceptance object's id property will be set.
	 *
	 * @return bool Whether any DB changes happened.
	 * @throws DBReadOnlyError
	 */
	public function save( ConsumerAcceptance $acceptance ): bool;

	/**
	 * Delete the given consumer acceptance from the DB.
	 *
	 * @return bool Whether anything was deleted.
	 * @throws DBReadOnlyError
	 */
	public function delete( ConsumerAcceptance $acceptance ): bool;

}
