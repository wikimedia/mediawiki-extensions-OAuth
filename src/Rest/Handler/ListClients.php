<?php

namespace MediaWiki\Extensions\OAuth\Rest\Handler;

use MediaWiki\Extensions\OAuth\Backend\Consumer;
use MediaWiki\Extensions\OAuth\Backend\Utils;
use MediaWiki\Extensions\OAuth\Control\ConsumerAccessControl;
use MediaWiki\Rest\LocalizedHttpException;
use MediaWiki\Rest\ResponseInterface;
use MediaWiki\Rest\SimpleHandler;
use RequestContext;
use Wikimedia\Message\MessageValue;
use Wikimedia\ParamValidator\ParamValidator;
use Wikimedia\Rdbms\ILoadBalancer;
use Wikimedia\Rdbms\IResultWrapper;

/**
 * Handles the oauth2/consumers endpoint, which returns
 * a list of registered consumers for the user
 */
class ListClients extends SimpleHandler {

	/** @var string[] */
	protected $propertyMapping = [
		'id' => 'oarc_id',
		'client_key' => 'oarc_consumer_key',
		'name' => 'oarc_name',
		'version' => 'oarc_version',
		'email' => 'oarc_email',
		'callback_url' => 'oarc_callback_url',
		'scopes' => 'oarc_grants',
		'registration' => 'oarc_registration',
		'stage' => 'oarc_stage',
		'oauth_version' => 'oarc_oauth_version',
		'description' => 'oarc_description',
		'allowed_grants' => 'oarc_oauth2_allowed_grants',
		'restrictions' => 'oarc_restrictions',
		'user_id' => 'oarc_user_id',
		'callback_is_prefix' => 'oarc_callback_is_prefix',
		'email_authenticated' => 'oarc_email_authenticated',
		'developer_agreement' => 'oarc_developer_agreement',
		'owner_only' => 'oarc_owner_only',
		'wiki' => 'oarc_wiki',
		'secret_key' => 'oarc_secret_key',
		'rsa_key' => 'oarc_rsa_key',
		'stage_timestamp' => 'oarc_stage_timestamp',
		'deleted' => 'oarc_deleted',
		'oauth2_is_confidential' => 'oarc_oauth2_is_confidential',
	];

	/**
	 *
	 * @var ILoadBalancer
	 */
	private $loadBalancer;

	/**
	 * @param ILoadBalancer $loadBalancer
	 */
	public function __construct( ILoadBalancer $loadBalancer ) {
		$this->loadBalancer = $loadBalancer;
	}

	/**
	 * @return bool
	 */
	public function needsWriteAccess() {
		return false;
	}

	/**
	 * @return ResponseInterface
	 * @throws LocalizedHttpException
	 */
	public function run(): ResponseInterface {
		// @todo Inject this, when there is a good way to do that, see T239753
		$user = RequestContext::getMain()->getUser();

		$centralId = Utils::getCentralIdFromUserName( $user->getName() );
		$responseFactory = $this->getResponseFactory();

		if ( !$centralId ) {
			throw new LocalizedHttpException(
				new MessageValue( 'rest-nonexistent-user', [ $user->getName() ] ), 404
			);
		}
		$response = $this->getDbResults( $centralId );

		return $responseFactory->createJson( $response );
	}

	/**
	 *
	 * @return array
	 */
	public function getParamSettings() {
		return [
			'limit' => [
				self::PARAM_SOURCE => 'query',
				ParamValidator::PARAM_TYPE => 'integer',
				ParamValidator::PARAM_REQUIRED => false,
				ParamValidator::PARAM_DEFAULT => 25
			],
			'offset' => [
				self::PARAM_SOURCE => 'query',
				ParamValidator::PARAM_TYPE => 'integer',
				ParamValidator::PARAM_REQUIRED => false,
				ParamValidator::PARAM_DEFAULT => 0
			],
			'oauth_version' => [
				self::PARAM_SOURCE => 'query',
				ParamValidator::PARAM_TYPE => [ '1', '2' ],
				ParamValidator::PARAM_REQUIRED => false,
				ParamValidator::PARAM_DEFAULT => '2'
			]
		];
	}

	/**
	 * @param int $centralId the user id of calling user
	 * @return array the results
	 */
	private function getDbResults( int $centralId ) {
		$dbr = $this->loadBalancer->getConnectionRef( DB_REPLICA );

		$params = $this->getValidatedParams();
		$limit = $params['limit'];
		$offset = $params['offset'];

		$options = [
			'LIMIT' => $limit,
			'OFFSET' => $offset
		];

		$oauthVersion = $params['oauth_version'];
		$conds = [ 'oarc_user_id' => $centralId ];
		if ( $oauthVersion !== null ) {
			$conds['oarc_oauth_version'] = (int)$oauthVersion;
		}

		$options['ORDER BY'] = 'oarc_id DESC';

		$res = $dbr->select(
			'oauth_registered_consumer',
			array_values( $this->propertyMapping ),
			$conds,
			__METHOD__,
			$options
		);

		$total = $dbr->selectRowCount(
			'oauth_registered_consumer',
			'oarc_consumer_key',
			$conds
		);

		return [
			'clients' => $this->processDbResults( $res ),
			'total' => $total
		];
	}

	/**
	 * @param IResultWrapper $res database results, or an empty array if none
	 * @return array consumer data
	 */
	private function processDbResults( $res ) {
		$consumers = [];
		$requestContext = RequestContext::getMain();
		$user = $requestContext->getUser();

		foreach ( $res as $row ) {

			$consumer = [];

			$cmrAc = ConsumerAccessControl::wrap(
				Consumer::newFromRow( Utils::getCentralDB( DB_REPLICA ), $row ),
				$requestContext
			);

			if ( !$cmrAc ) {
				continue;
			}

			$consumer['email'] = $cmrAc->getEmail();
			$consumer['name'] = $cmrAc->getName();
			$consumer['version'] = $cmrAc->getVersion();
			$consumer['callback_url'] = $cmrAc->getCallbackUrl();
			$consumer['description'] = $cmrAc->getDescription();
			$consumer['client_key'] = $cmrAc->getConsumerKey();
			$consumer['owner_only'] = $cmrAc->getOwnerOnly();

			$consumer['stage'] = (int)$cmrAc->getStage();
			$consumer['oauth_version'] = $cmrAc->getOAuthVersion();
			$consumer['registration_formatted'] = $requestContext->getLanguage()->userTimeAndDate(
				$cmrAc->getRegistration(),
				$user
			);

			if ( $consumer['oauth_version'] === Consumer::OAUTH_VERSION_2 ) {
				$consumer['allowed_grants'] = $cmrAc->get( 'oauth2GrantTypes' );
			}

			$consumer['scopes'] = $cmrAc->getGrants();
			$consumer['restrictions'] = $cmrAc->getRestrictions();

			foreach ( $consumer as $key => $value ) {
				if ( is_object( $consumer[$key] ) ) {
					unset( $consumer[$key] );
				}
			}

			$consumers[] = $consumer;
		}

		return $consumers;
	}
}
