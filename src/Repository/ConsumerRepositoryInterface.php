<?php

namespace MediaWiki\Extension\OAuth\Repository;

use MediaWiki\Extension\OAuth\Backend\Consumer;
use stdClass;
use Wikimedia\Rdbms\DBReadOnlyError;

/**
 * Service for loading and saving consumers.
 *
 * Does not handle the list-related queries (ConsumerRegistration / ListConsumers /
 * ManageConsumers / ManageMyGrants), which involve a pager rather than directly
 * returning Consumer objects.
 */
interface ConsumerRepositoryInterface {

	/**
	 * Create a consumer from the result of a DB query
	 *
	 * @param array|stdClass $row DB row data, from SelectQueryBuilder::fetchRow() or similar
	 */
	public function newFromRow( array|stdClass $row ): Consumer;

	/**
	 * Get a consumer by its database primary key (oarc_id).
	 *
	 * @param int $id Database ID.
	 * @param int $flags IDBAccessObject::READ_* bitfield
	 */
	public function getById( int $id, int $flags = 0 ): Consumer|false;

	/**
	 * Get a consumer by its consumer key (the 32 character hex string).
	 *
	 * This is the primary user-facing identifier of consumers.
	 *
	 * @param string $consumerKey Consumer key / client key
	 * @param int $flags IDBAccessObject::READ_* bitfield
	 */
	public function getByKey(
		string $consumerKey,
		int $flags = 0
	): Consumer|false;

	/**
	 * Get a consumer by consumer name + consumer version string + user ID.
	 *
	 * @param string $name App name
	 * @param string $version App version
	 * @param int $centralUserId Central user ID from CentralIdLookup
	 * @param int $flags IDBAccessObject::READ_* bitfield
	 */
	public function getByNameVersionUser(
		string $name,
		string $version,
		int $centralUserId,
		int $flags = 0
	): Consumer|false;

	/**
	 * Insert or update the consumer in the DB.
	 *
	 * After an insert, the Consumer object's id property will be set.
	 *
	 * @return bool Whether any DB changes happened.
	 * @throws DBReadOnlyError
	 */
	public function save( Consumer $consumer ): bool;

	/**
	 * Delete the given consumer from the DB.
	 *
	 * Note this is SQL-level deletion, unrelated to the `oarc_deleted` flag. Soft-deletion is
	 * handled via save().
	 *
	 * @return bool Whether anything was deleted.
	 * @throws DBReadOnlyError
	 */
	public function delete( Consumer $consumer ): bool;

}
