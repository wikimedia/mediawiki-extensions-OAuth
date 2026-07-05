<?php

namespace MediaWiki\Extension\OAuth\Repository;

use MediaWiki\Extension\OAuth\Backend\Consumer;
use stdClass;

/**
 * A repository that combines an ArrayConsumerRepository and a DatabaseConsumerRepository.
 * The array-based repo takes precedence for reading, and Consumer objects track which one
 * they came from so writes go to the right place.
 */
class CompositeConsumerRepository implements ConsumerRepositoryInterface {

	public function __construct(
		private ArrayConsumerRepository $arrayRepository,
		private DatabaseConsumerRepository $databaseRepository,
	) {
	}

	/** @inheritDoc */
	public function newFromRow( array|stdClass $row ): Consumer {
		$consumer = $this->databaseRepository->newFromRow( $row );
		// configuration takes priority over the DB
		return $this->arrayRepository->getByKey( $consumer->getConsumerKey() ) ?: $consumer;
	}

	/** @inheritDoc */
	public function getById( int $id, int $flags = 0 ): Consumer|false {
		return $this->arrayRepository->getById( $id, $flags )
			?: $this->databaseRepository->getById( $id, $flags );
	}

	/** @inheritDoc */
	public function getByKey( string $consumerKey, int $flags = 0 ): Consumer|false {
		return $this->arrayRepository->getByKey( $consumerKey, $flags )
			?: $this->databaseRepository->getByKey( $consumerKey, $flags );
	}

	/** @inheritDoc */
	public function getByNameVersionUser(
		string $name,
		string $version,
		int $centralUserId,
		int $flags = 0
	): Consumer|false {
		return $this->arrayRepository->getByNameVersionUser( $name, $version, $centralUserId, $flags )
			?: $this->databaseRepository->getByNameVersionUser( $name, $version, $centralUserId, $flags );
	}

	/** @inheritDoc */
	public function save( Consumer $consumer ): bool {
		return $consumer->isConfigurationBased()
			? $this->arrayRepository->save( $consumer )
			: $this->databaseRepository->save( $consumer );
	}

	/** @inheritDoc */
	public function delete( Consumer $consumer ): bool {
		return $consumer->isConfigurationBased()
			? $this->arrayRepository->delete( $consumer )
			: $this->databaseRepository->delete( $consumer );
	}

}
