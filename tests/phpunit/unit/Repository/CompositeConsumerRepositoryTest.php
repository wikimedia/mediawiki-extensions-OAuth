<?php

namespace MediaWiki\Extension\OAuth\Tests\Repository;

use MediaWiki\Extension\OAuth\Backend\Consumer;
use MediaWiki\Extension\OAuth\Repository\ArrayConsumerRepository;
use MediaWiki\Extension\OAuth\Repository\CompositeConsumerRepository;
use MediaWiki\Extension\OAuth\Repository\DatabaseConsumerRepository;
use MediaWikiUnitTestCase;
use Wikimedia\Rdbms\IDBAccessObject;

/**
 * @covers \MediaWiki\Extension\OAuth\Repository\CompositeConsumerRepository
 */
class CompositeConsumerRepositoryTest extends MediaWikiUnitTestCase {

	private function newConsumer(
		int $id,
		string $consumerKey,
		string $name,
		string $version,
		int $userId,
		bool $isConfigurationBased = false
	): Consumer {
		$consumer = $this->createNoOpMock( Consumer::class,
			[ 'getId', 'getConsumerKey', 'getName', 'getVersion', 'getUserId', 'isConfigurationBased' ] );
		$consumer->method( 'getId' )->willReturn( $id );
		$consumer->method( 'getConsumerKey' )->willReturn( $consumerKey );
		$consumer->method( 'getName' )->willReturn( $name );
		$consumer->method( 'getVersion' )->willReturn( $version );
		$consumer->method( 'getUserId' )->willReturn( $userId );
		$consumer->method( 'isConfigurationBased' )->willReturn( $isConfigurationBased );
		return $consumer;
	}

	public function testNewFromRowPrefersConfiguredConsumer() {
		$row = (object)[ 'oarc_id' => 1 ];
		$databaseConsumer = $this->newConsumer( 1, 'somekey', 'Test Consumer', '0.1.0', 9 );
		$configuredConsumer = $this->newConsumer( 1, 'somekey', 'Test Consumer', '0.1.0', 9, true );

		$databaseRepository = $this->createNoOpMock( DatabaseConsumerRepository::class, [ 'newFromRow' ] );
		$databaseRepository->expects( $this->once() )->method( 'newFromRow' )
			->with( $row )->willReturn( $databaseConsumer );
		$arrayRepository = $this->createNoOpMock( ArrayConsumerRepository::class, [ 'getByKey' ] );
		$arrayRepository->method( 'getByKey' )->with( 'somekey' )->willReturn( $configuredConsumer );

		$repository = new CompositeConsumerRepository( $arrayRepository, $databaseRepository );
		$this->assertSame( $configuredConsumer, $repository->newFromRow( $row ) );
	}

	public function testNewFromRowFallsBackToDatabaseConsumer() {
		$row = (object)[ 'oarc_id' => 1 ];
		$databaseConsumer = $this->newConsumer( 1, 'somekey', 'Test Consumer', '0.1.0', 9 );

		$databaseRepository = $this->createNoOpMock( DatabaseConsumerRepository::class, [ 'newFromRow' ] );
		$databaseRepository->method( 'newFromRow' )->with( $row )->willReturn( $databaseConsumer );
		$arrayRepository = $this->createNoOpMock( ArrayConsumerRepository::class, [ 'getByKey' ] );
		$arrayRepository->method( 'getByKey' )->with( 'somekey' )->willReturn( false );

		$repository = new CompositeConsumerRepository( $arrayRepository, $databaseRepository );
		$this->assertSame( $databaseConsumer, $repository->newFromRow( $row ) );
	}

	public static function provideGetters() {
		yield 'getById' => [ 'getById', [ 1 ] ];
		yield 'getByKey' => [ 'getByKey', [ 'somekey' ] ];
		yield 'getByNameVersionUser' => [ 'getByNameVersionUser', [ 'Test Consumer', '0.1.0', 9 ] ];
	}

	/**
	 * @dataProvider provideGetters
	 */
	public function testGetterPrefersConfiguredConsumer( string $method, array $args ) {
		$args[] = IDBAccessObject::READ_LATEST;
		$configuredConsumer = $this->newConsumer( 1, 'somekey', 'Test Consumer', '0.1.0', 9, true );

		$arrayRepository = $this->createNoOpMock( ArrayConsumerRepository::class, [ $method ] );
		$arrayRepository->expects( $this->once() )->method( $method )
			->with( ...$args )->willReturn( $configuredConsumer );
		// The database repository must not be queried at all.
		$databaseRepository = $this->createNoOpMock( DatabaseConsumerRepository::class );

		$repository = new CompositeConsumerRepository( $arrayRepository, $databaseRepository );
		$this->assertSame( $configuredConsumer, $repository->$method( ...$args ) );
	}

	/**
	 * @dataProvider provideGetters
	 */
	public function testGetterFallsBackToDatabase( string $method, array $args ) {
		$args[] = IDBAccessObject::READ_LATEST;
		$databaseConsumer = $this->newConsumer( 1, 'somekey', 'Test Consumer', '0.1.0', 9 );

		$arrayRepository = $this->createNoOpMock( ArrayConsumerRepository::class, [ $method ] );
		$arrayRepository->method( $method )->with( ...$args )->willReturn( false );
		$databaseRepository = $this->createNoOpMock( DatabaseConsumerRepository::class, [ $method ] );
		$databaseRepository->expects( $this->once() )->method( $method )
			->with( ...$args )->willReturn( $databaseConsumer );

		$repository = new CompositeConsumerRepository( $arrayRepository, $databaseRepository );
		$this->assertSame( $databaseConsumer, $repository->$method( ...$args ) );
	}

	/**
	 * @dataProvider provideGetters
	 */
	public function testGetterReturnsFalseWhenNotFound( string $method, array $args ) {
		$arrayRepository = $this->createNoOpMock( ArrayConsumerRepository::class, [ $method ] );
		$arrayRepository->method( $method )->willReturn( false );
		$databaseRepository = $this->createNoOpMock( DatabaseConsumerRepository::class, [ $method ] );
		$databaseRepository->method( $method )->willReturn( false );

		$repository = new CompositeConsumerRepository( $arrayRepository, $databaseRepository );
		$this->assertFalse( $repository->$method( ...$args ) );
	}

	public static function provideWriteMethods() {
		yield 'save configuration-based consumer' => [ 'save', true ];
		yield 'save database-based consumer' => [ 'save', false ];
		yield 'delete configuration-based consumer' => [ 'delete', true ];
		yield 'delete database-based consumer' => [ 'delete', false ];
	}

	/**
	 * @dataProvider provideWriteMethods
	 */
	public function testWriteDelegatesByConsumerOrigin( string $method, bool $isConfigurationBased ) {
		$consumer = $this->newConsumer( 1, 'somekey', 'Test Consumer', '0.1.0', 9, $isConfigurationBased );

		$arrayRepository = $this->createNoOpMock(
			ArrayConsumerRepository::class,
			$isConfigurationBased ? [ $method ] : []
		);
		$databaseRepository = $this->createNoOpMock(
			DatabaseConsumerRepository::class,
			$isConfigurationBased ? [] : [ $method ]
		);
		$targetRepository = $isConfigurationBased ? $arrayRepository : $databaseRepository;
		$targetRepository->expects( $this->once() )->method( $method )
			->with( $consumer )->willReturn( true );

		$repository = new CompositeConsumerRepository( $arrayRepository, $databaseRepository );
		$this->assertTrue( $repository->$method( $consumer ) );
	}

}
