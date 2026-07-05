<?php

namespace MediaWiki\Extension\OAuth;

use MediaWiki\Extension\OAuth\Backend\Consumer;
use MediaWiki\Extension\OAuth\Backend\Utils;
use MediaWiki\Extension\OAuth\Control\ConsumerValidator;
use MediaWiki\Extension\OAuth\Lib\OAuthException;
use MediaWiki\Extension\OAuth\Repository\ConsumerRepositoryInterface;
use MediaWiki\Maintenance\Maintenance;
use MediaWiki\MediaWikiServices;
use MediaWiki\User\CentralId\CentralIdLookup;
use MediaWiki\User\User;
use MediaWiki\Utils\MWCryptRand;
use Wikimedia\Timestamp\ConvertibleTimestamp;

/**
 * @ingroup Maintenance
 */

/**
 * Maintenance script for keeping configuration-based apps ($wgOAuthStaticApps) in sync with
 * the DB, so various list interfaces show them as expected.
 */
class ManageConfigurationBasedClient extends Maintenance {

	public const SYSTEM_USER = 'OAuth system app';

	private CentralIdLookup $centralIdLookup;
	private ConsumerRepositoryInterface $repository;
	private ConsumerRepositoryInterface $dbRepository;
	private ConsumerValidator $consumerValidator;

	public function __construct() {
		parent::__construct();
		$this->addDescription( 'Manage configuration-based OAuth clients' );
		$this->addOption( 'create', 'Create a new (invalid) OAuth entry, to reserve the ID. '
			. 'Run this before adding the app to $wgOAuthStaticApps.' );
		$this->addOption( 'update', 'Update an OAuth entry to match the configuration. '
			. 'Run this after adding the app to $wgOAuthStaticApps, or changing an existing entry.' );
		$this->addOption( 'key', 'The consumer key, to be used with --update to identify the target.',
			withArg: true );
	}

	public function execute() {
		if ( $this->hasOption( 'create' ) && $this->hasOption( 'update' ) ) {
			$this->fatalError( 'Only one of --create and --update can be used' );
		} elseif ( !$this->hasOption( 'create' ) && !$this->hasOption( 'update' ) ) {
			$this->fatalError( 'Either --create or --update is required' );
		}
		if ( $this->hasOption( 'create' ) && $this->hasOption( 'key' ) ) {
			$this->fatalError( '--key cannot be used with --create (it will create a new random key)' );
		} elseif ( $this->hasOption( 'update' ) && !$this->hasOption( 'key' ) ) {
			$this->fatalError( '--key is required when using --update' );
		}

		$this->initialize();

		if ( $this->hasOption( 'create' ) ) {
			$this->createNewClient();
		} else {
			$this->updateClient( $this->getOption( 'key' ) );
		}
	}

	private function initialize(): void {
		$services = MediaWikiServices::getInstance();
		$oauthServices = OAuthServices::wrap( $services );
		$this->centralIdLookup = Utils::getCentralIdLookup();
		$this->repository = $oauthServices->getConsumerRepository();
		$this->dbRepository = $services->get( '_OAuthConsumerRepository_DB' );
		$this->consumerValidator = $oauthServices->getConsumerValidator();
	}

	private function createNewClient(): void {
		$consumerKey = MWCryptRand::generateHex( 32 );
		$consumerData = $this->consumerValidator->expandConsumerData( [
			// placeholder because expandConsumerData() requires it
			Consumer::FIELD_ID => -1,
			Consumer::FIELD_CONSUMER_KEY => $consumerKey,
			// name + version + userid must be unique; shouldn't be a problem, but just in case include the key
			Consumer::FIELD_NAME => "Internal placeholder for system app $consumerKey",
			Consumer::FIELD_USER_ID => $this->getSystemUser(),
			Consumer::FIELD_VERSION => '0.1.0',
			Consumer::FIELD_DESCRIPTION => '-',
			Consumer::FIELD_OAUTH_VERSION => Consumer::OAUTH_VERSION_1,
			Consumer::FIELD_OWNER_ONLY => true,
			Consumer::FIELD_SECRET_KEY => MWCryptRand::generateHex( 32 ),
			Consumer::FIELD_GRANTS => [ 'mwoauth-authonly' ],
		] );
		$consumerData[Consumer::FIELD_ID] = null;
		$placeholder = Consumer::newFromArray( $consumerData );
		// Make sure the shadow entry in the database is always disabled. On load this will be
		// overridden by the configuration entry so its value doesn't matter; if the configuration
		// entry somehow gets lost, this makes the app automatically disable itself, which is
		// probably the least bad thing to do in that situation.
		$placeholder->setField( Consumer::FIELD_STAGE, Consumer::STAGE_CONFIGURATION_BASED );
		$success = $this->repository->save( $placeholder );
		if ( !$success ) {
			$this->fatalError( 'Failed to save placeholder client with key ' . $placeholder->getConsumerKey() );
		}

		$this->output( "Saved placeholder client, add it to \$wgOAuthStaticApps and "
			. "re-run this script with --update\n" );
		$this->output( "    Database ID ('id' field in \$wgOAuthStaticApps): {$placeholder->getId()}\n" );
		$this->output( "    Client key ('consumerKey', --key parameter of this script): "
			. $placeholder->getConsumerKey() . "\n" );
		$this->output( "    Unhashed secret key ('secretKey'): {$placeholder->getSecretKey()}\n" );
		$this->output( "    Hashed secret key (should be given to the client): {$placeholder->secret}\n" );
		$this->output( "    User ID of owner (`userId`): {$placeholder->getUserId()}\n" );
		$this->output( "You can change these except for the DB ID and the client key, if you want\n" );
	}

	private function updateClient( string $consumerKey ): void {
		$dbConsumer = $this->dbRepository->getByKey( $consumerKey );
		try {
			// this will also validate the configuration
			$configConsumer = $this->repository->getByKey( $consumerKey );
		} catch ( OAuthException $e ) {
			$this->fatalError( $e->getMessage() );
		}
		if ( !$dbConsumer ) {
			$this->fatalError( 'No client with this key exists in the database' );
		} elseif ( !$configConsumer->isConfigurationBased() ) {
			$this->fatalError( 'No client with this key exists in configuration' );
		} elseif ( $dbConsumer->getId() !== $configConsumer->getId() ) {
			$this->fatalError( 'ID mismatch: DB: ' . $dbConsumer->getId()
				. ', config: ' . $configConsumer->getId() );
		}

		$oldSecretKey = $dbConsumer->getSecretKey();

		$dbConsumer->setFields( [
			Consumer::FIELD_STAGE => Consumer::STAGE_CONFIGURATION_BASED,
			Consumer::FIELD_STAGE_TIMESTAMP => ConvertibleTimestamp::now(),
		] + $configConsumer->toArray() );

		$success = $this->dbRepository->save( $dbConsumer );
		// TODO generate acceptance / access token for owner-only consumers
		$success ? $this->output( "Consumer updated\n" ) : $this->fatalError( 'Failed to update consumer' );
		if ( $success && $oldSecretKey !== $dbConsumer->getSecretKey() ) {
			$this->output( "    New hashed secret key: {$dbConsumer->secret}\n" );
		}
	}

	private function getSystemUser(): int {
		$defaultUser = User::newSystemUser( self::SYSTEM_USER, [ 'create' => false ] );
		if ( !$defaultUser ) {
			if ( $this->centralIdLookup->getProviderId() === 'local' ) {
				$defaultUser = User::newSystemUser( self::SYSTEM_USER, [ 'steal' => true ] );
			} else {
				$this->fatalError( 'Unsupported central ID lookup method. Please create a '
					. 'central system user called "' . self::SYSTEM_USER . '" manually and re-run this script.' );
			}
		}
		// User::newSystemUser( create: true, steal: true ) always returns a user
		// @phan-suppress-next-line PhanTypeMismatchArgumentNullable
		$centralId = $this->centralIdLookup->centralIdFromLocalUser( $defaultUser );
		if ( !$centralId ) {
			$this->fatalError( 'Something went wrong obtaining the ID for user ' . self::SYSTEM_USER );
		}
		return $centralId;
	}

}

$maintClass = ManageConfigurationBasedClient::class;
