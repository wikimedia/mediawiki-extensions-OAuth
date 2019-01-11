<?php
/**
 * Example:
 *
 * createOAuthConsumer.php
 *   --callbackIsPrefix
 *   --callbackUrl="https://foourl"
 *   --description="Application description"
 *   --grants="editprotected"
 *   --grants="createaccount"
 *   --name="Application name"
 *   --user="Admin"
 *   --version="0.2"
 *   --wiki=default
 */

namespace MediaWiki\Extensions\OAuth;

/**
 * @ingroup Maintenance
 */
if ( getenv( 'MW_INSTALL_PATH' ) ) {
	$IP = getenv( 'MW_INSTALL_PATH' );
} else {
	$IP = __DIR__ . '/../../..';
}

require __DIR__ . '/../lib/OAuth.php';
require_once "$IP/maintenance/Maintenance.php";

class CreateOAuthConsumer extends \Maintenance {
	public function __construct() {
		parent::__construct();
		$this->mDescription = "Create an OAuth consumer";
		$this->addOption( 'user', 'User to run the script as', true, true );
		$this->addOption( 'name', 'Application name', true, true );
		$this->addOption( 'description', 'Application description', true, true );
		$this->addOption( 'version', 'Application version', true, true );
		$this->addOption( 'callbackUrl', 'Callback URL', true, true );
		$this->addOption(
			'callbackIsPrefix',
			'Allow a consumer to specify a callback in requests',
			true
		);
		$this->addOption( 'grants', 'Grants', true, true, false, true );
		$this->requireExtension( "OAuth" );
	}

	public function execute() {
		$user = \User::newFromName( $this->getOption( 'user' ) );
		if ( $user->isAnon() ) {
			$this->fatalError( 'User must be registered' );
		}
		if ( $user->getEmail() === '' ) {
			$this->fatalError( 'User must have an email' );
		}

		$data = [
			'name'         => $this->getOption( 'name' ),
			'version'      => $this->getOption( 'version' ),
			'description'  => $this->getOption( 'description' ),
			'callbackUrl'  => $this->getOption( 'callbackUrl' ),
			'callbackIsPrefix' => $this->hasOption( 'callbackIsPrefix' ),
			'grants' => '["' . implode( '","', $this->getOption( 'grants' ) ) . '"]',
			'granttype' => 'normal',
			'ownerOnly' => false,
			'email' => $user->getEmail(),
			'wiki' => '*', // All wikis
			'rsaKey' => '', // Generate a key
			'agreement' => true,
			'action' => 'propose',
			'restrictions' => \MWRestrictions::newDefault(),
		];

		$context = \RequestContext::getMain();
		$context->setUser( $user );

		$dbw = MWOAuthUtils::getCentralDB( DB_MASTER );
		$control = new MWOAuthConsumerSubmitControl( $context, $data, $dbw );
		$status = $control->submit();

		if ( !$status->isOK() ) {
			$this->fatalError( $status->getMessage() );
		}

		/** @var MWOAuthConsumer $cmr */
		$cmr = $status->value['result']['consumer'];

		$this->output( 'Id: ' . $cmr->getId() . PHP_EOL );
		$this->output( 'Name: ' . $cmr->getName() . PHP_EOL );
		$this->output( 'Key: ' . $cmr->getConsumerKey() . PHP_EOL );
		$this->output( 'Secret: ' . MWOAuthUtils::hmacDBSecret( $cmr->getSecretKey() ) . PHP_EOL );
	}
}

$maintClass = CreateOAuthConsumer::class;
require_once RUN_MAINTENANCE_IF_MAIN;
