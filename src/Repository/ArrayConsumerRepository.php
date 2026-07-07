<?php

namespace MediaWiki\Extension\OAuth\Repository;

use DomainException;
use InvalidArgumentException;
use LogicException;
use MediaWiki\Extension\OAuth\Backend\Consumer;
use MediaWiki\Extension\OAuth\Control\ConsumerValidator;
use MediaWiki\Utils\MWRestrictions;
use stdClass;

/**
 * Simple array based implementation of ConsumerRepositoryInterface.
 * Useful for unit tests, and for managing consumers as configuration.
 *
 * To facilitate using configuration-based consumers in production, it allows storing consumers
 * as data, omitting keys when they are not relevant to the behavior of the app, and
 * lazy-instantiating consumers on retrieval so they don't need to be validated on every request.
 */
class ArrayConsumerRepository implements ConsumerRepositoryInterface {

	private const FIELD_EXPAND_AND_VALIDATE = '#expand-and-validate#';

	/**
	 * This implementation assumes that Consumer::$consumerKey doesn't change over time.
	 * @var array<string,Consumer|array>
	 */
	private array $consumersByKey = [];

	/**
	 * @param ConsumerValidator $consumerValidator
	 * @param bool $allowWrites Allow using save() and delete() (otherwise they will throw an
	 *   exception). Should only be used for tests.
	 */
	public function __construct(
		private ConsumerValidator $consumerValidator,
		private bool $allowWrites = true,
	) {
	}

	/** @inheritDoc */
	public function newFromRow( array|stdClass $row ): never {
		throw new LogicException( __CLASS__ . ' cannot work with DB queries' );
	}

	/** @inheritDoc */
	public function getByKey( string $consumerKey, int $flags = 0 ): Consumer|false {
		$consumer = $this->consumersByKey[$consumerKey] ?? false;
		if ( is_array( $consumer ) ) {
			$this->consumersByKey[$consumerKey]
				= $this->initializeConsumer( $this->consumersByKey[$consumerKey] );
		}
		return $this->consumersByKey[$consumerKey] ?? false;
	}

	/** @inheritDoc */
	public function getById( int $id, int $flags = 0 ): Consumer|false {
		foreach ( $this->iterateConsumerData() as $consumerKey => $consumerData ) {
			if ( $id === $consumerData[Consumer::FIELD_ID] ) {
				return $this->getByKey( $consumerKey, $flags );
			}
		}
		return false;
	}

	/** @inheritDoc */
	public function getByNameVersionUser(
		string $name,
		string $version,
		int $centralUserId,
		int $flags = 0
	): Consumer|false {
		foreach ( $this->iterateConsumerData() as $consumerKey => $consumerData ) {
			if ( $name === $consumerData[Consumer::FIELD_NAME]
				 && $version === $consumerData[Consumer::FIELD_VERSION]
				 && $centralUserId === $consumerData[Consumer::FIELD_USER_ID]
			) {
				return $this->getByKey( $consumerKey, $flags );
			}
		}
		return false;
	}

	/**
	 * {@inheritDoc}
	 *
	 * Note that the behavior differs from DatabaseConsumerRepository::save() in some
	 * small ways:
	 * - there's no duplicate key checking for the name/version/user key
	 * - it always returns true, even if the consumer was already stored and calling save() didn't
	 *   result in any changes
	 * - it doesn't serialize Consumer objects, so if they get saved, then the original object is
	 *   changed, then reloaded, the changes will remain
	 * Since this method is only used by tests, that shouldn't be a problem.
	 */
	public function save( Consumer $consumer ): bool {
		if ( !$this->allowWrites ) {
			throw new LogicException( __METHOD__ . ' called with $allowWrites=false' );
		}
		// getId() would error out when ID is null
		if ( !$consumer->get( 'id' ) ) {
			// Consumers need to have an ID, even if they are not stored in the database, because that's
			// how consumer acceptance records (which will always be database-based) reference them.
			$this->assignId( $consumer );
		}

		unset( $this->consumersByKey[$consumer->getConsumerKey()] );
		$this->addConsumer( $consumer );
		return true;
	}

	/** @inheritDoc */
	public function delete( Consumer $consumer ): bool {
		if ( !$this->allowWrites ) {
			throw new LogicException( __METHOD__ . ' called with $allowWrites=false' );
		}
		$consumerKey = $consumer->getConsumerKey();
		if ( $this->consumersByKey[$consumerKey] ?? false ) {
			unset( $this->consumersByKey[$consumerKey] );
			return true;
		} else {
			return false;
		}
	}

	/**
	 * Add a Consumer object to the repository.
	 *
	 * Unlike save() this doesn't set the ID, and ignores the allowWrites flag; it's intended
	 * for setting up the initial state of the repository.
	 *
	 * @param Consumer $consumer
	 * @return void
	 */
	public function addConsumer( Consumer $consumer ): void {
		if ( !$consumer->get( 'id' ) ) {
			throw new DomainException( 'Consumer ' . $consumer->getConsumerKey()
				. 'does not have an ID' );
		}
		if ( $this->consumersByKey[$consumer->getConsumerKey()] ?? false ) {
			throw new DomainException( 'Consumer key ' . $consumer->getConsumerKey()
				. ' already exists in this repository' );
		}
		// Sanity check. A bit inefficient but this method will only be used in tests.
		if ( $this->getById( $consumer->getId() ) ) {
			throw new DomainException( 'Consumer ID ' . $consumer->getId() . ' already exists in this repository' );
		}
		$this->consumersByKey[$consumer->getConsumerKey()] = $consumer;
	}

	/**
	 * Add a Consumer object represented as data to the repository.
	 *
	 * Similar to addConsumer() but takes an array of data as returned by Consumer::toArray().
	 * Optionally, the 'restrictions' field of the array can be the result of MWRestrictions::toArray()
	 * rather than an MWRestrictions object.
	 *
	 * @param array $consumerData
	 * @param int|string|null $index Index to include in the logs, to identify which consumer
	 *   caused the error.
	 * @return void
	 */
	public function addConsumerArray( array $consumerData, int|string|null $index = null ): void {
		// Validate just enough to make sure the get* functions will work.
		$searchKeys = [
			Consumer::FIELD_ID,
			Consumer::FIELD_CONSUMER_KEY,
			Consumer::FIELD_NAME,
			Consumer::FIELD_VERSION,
			Consumer::FIELD_USER_ID,
		];
		$missingKeys = array_diff( $searchKeys, array_keys( array_filter( $consumerData ) ) );
		if ( $missingKeys ) {
			throw new InvalidArgumentException( __METHOD__ . " missing required keys for item $index: "
				. implode( ', ', $missingKeys ) );
		}

		if ( $this->consumersByKey[$consumerData[Consumer::FIELD_CONSUMER_KEY]] ?? false ) {
			throw new DomainException( 'Consumer key ' . $consumerData[Consumer::FIELD_CONSUMER_KEY]
				. ' already exists in this repository' );
		}
		$this->consumersByKey[$consumerData[Consumer::FIELD_CONSUMER_KEY]] = $consumerData;
	}

	/**
	 * Add a Consumer object represented as configuration to the repository.
	 *
	 * Similar to addConsumerArray() but optimized for a readable data format, and for production use:
	 * - allows omitting fields that do not influence the consumer's behavior (e.g. OAuth 2 specific
	 *   fields on an OAuth 1 consumer); these fields will be set to safe defaults
	 * - validates consumer data and returns developer-friendly error messages
	 * - pushes as much work as possible to initializeConsumer() so apps are only validated in
	 *   requests that use them
	 *
	 * @param array $consumerData
	 * @param int|string|null $index Index to include in the logs, to identify which consumer
	 *   caused the error.
	 * @return void
	 */
	public function addConfigurationArray( array $consumerData, int|string|null $index = null ): void {
		$consumerData[self::FIELD_EXPAND_AND_VALIDATE] = true;
		$this->addConsumerArray( $consumerData, $index );
	}

	/**
	 * @return iterable<array>
	 */
	private function iterateConsumerData(): iterable {
		foreach ( $this->consumersByKey as $key => $consumer ) {
			if ( $consumer instanceof Consumer ) {
				$consumerData = $consumer->toArray();
			} else {
				$consumerData = $consumer;
			}
			yield $key => $consumerData;
		}
	}

	private function initializeConsumer( array $consumerData ): Consumer {
		if ( isset( $consumerData[Consumer::FIELD_RESTRICTIONS] )
			&& is_array( $consumerData[Consumer::FIELD_RESTRICTIONS] )
		) {
			$consumerData[Consumer::FIELD_RESTRICTIONS]
				= MWRestrictions::newFromArray( $consumerData[Consumer::FIELD_RESTRICTIONS] );
		}

		if ( $consumerData[self::FIELD_EXPAND_AND_VALIDATE] ?? false ) {
			unset( $consumerData[self::FIELD_EXPAND_AND_VALIDATE] );
			$consumerData = $this->consumerValidator->expandConsumerData( $consumerData );
			$this->consumerValidator->validateFieldsAndThrow( $consumerData );
		}

		$consumer = Consumer::newFromArray( $consumerData );
		$consumer->updateOrigin( Consumer::ORIGIN_CONFIG );
		$consumer->setPending( false );
		return $consumer;
	}

	private function assignId( Consumer $consumer ): void {
		static $id = -1;
		$consumer->setId( $id-- );
	}

}
