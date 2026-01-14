<?php

namespace MediaWiki\Extension\OAuth\Control;

use LogicException;
use MediaWiki\Api\ApiMessage;
use MediaWiki\Context\ContextSource;
use MediaWiki\Context\IContextSource;
use MediaWiki\Exception\MWException;
use MediaWiki\Extension\OAuth\Backend\Consumer;
use MediaWiki\HTMLForm\HTMLForm;
use MediaWiki\MediaWikiServices;
use MediaWiki\Message\Message;
use MediaWiki\Status\Status;
use StatusValue;
use Wikimedia\Message\MessageParam;
use Wikimedia\Message\MessageSpecifier;

/**
 * (c) Aaron Schulz 2013, GPL
 *
 * @license GPL-2.0-or-later
 */

/**
 * Handle the logic of submitting a client request
 */
abstract class SubmitControl extends ContextSource {
	/** @var string[]|null */
	private static $irrevocableGrants = null;

	/** @var string[] */
	public const AUTH_ONLY_GRANTS = [ 'mwoauth-authonlyprivate', 'mwoauth-authonly' ];

	/** @var array (field name => value) */
	protected $vals;

	/**
	 * @param IContextSource $context
	 * @param array $params
	 */
	public function __construct( IContextSource $context, array $params ) {
		$this->setContext( $context );
		$this->vals = $params;
	}

	/**
	 * @param array $params
	 */
	public function setInputParameters( array $params ) {
		$this->vals = $params;
	}

	/**
	 * Attempt to validate and submit this data
	 *
	 * This will check basic permissions, validate the action and parameters
	 * and route the submission handling to the internal subclass function.
	 *
	 * @throws MWException
	 * @return Status
	 */
	public function submit() {
		$status = $this->checkBasePermissions();
		if ( !$status->isOK() ) {
			return $status;
		}

		$action = $this->vals['action'];
		$required = $this->getRequiredFields();
		if ( !isset( $required[$action] ) ) {
			// @TODO: check for field-specific message first
			return $this->failure( 'invalid_field_action', 'mwoauth-invalid-field', 'action' );
		}

		$status = $this->validateFields( $required[$action] );
		if ( !$status->isOK() ) {
			return $status;
		}

		$status = $this->processAction( $action );
		if ( $status instanceof Status ) {
			return $status;
		} else {
			throw new MWException( "Submission action '$action' not handled." );
		}
	}

	/**
	 * Add the validators from getRequiredFields() to the given HTMLForm descriptor.
	 * Existing validators are not overridden.
	 *
	 * It also adds a checkbox to override warnings when necessary.
	 *
	 * @param array[] $descriptors
	 * @return array[]
	 */
	public function registerValidators( array $descriptors ) {
		foreach ( $descriptors as $field => &$description ) {
			if ( array_key_exists( 'validation-callback', $description ) ) {
				// already set to something
				continue;
			}
			$description['validation-callback'] =
				function ( $value, $allValues, $form ) use ( $field ) {
					return $this->validateFieldInternal( $field, $value, $allValues, $form );
				};
		}
		$descriptors['ignorewarnings'] = [
			'type' => 'check',
			'label-message' => 'mwoauth-ignorewarnings',
			'cssclass' => 'mw-oauth-form-ignorewarnings-hidden',
		];
		return $descriptors;
	}

	/**
	 * Do some basic checks and call the validator provided by getRequiredFields().
	 * This method should not be called outside SubmitControl.
	 *
	 * @param string $field
	 * @param mixed $value
	 * @param array $allValues
	 * @param HTMLForm $form
	 * @throws MWException
	 * @return true|string
	 */
	public function validateFieldInternal( string $field, $value, array $allValues, HTMLForm $form ) {
		if ( !isset( $allValues['action'] ) && isset( $this->vals['action'] ) ) {
			// The action may be derived, especially for multi-button forms.
			// Such an HTMLForm will not have an action key set in $allValues.
			$allValues['action'] = $this->vals['action'];
		}
		if ( !isset( $allValues['action'] ) ) {
			throw new MWException( "No form action defined; cannot validate fields." );
		}
		$validators = $this->getRequiredFields();
		if ( !isset( $validators[$allValues['action']][$field] ) ) {
			// nothing to check
			return true;
		}
		$validator = $validators[$allValues['action']][$field];
		$validationResult = $this->getValidationResult( $validator, $value, $allValues, $form );
		if ( $validationResult === false ) {
			return $this->getDefaultValidationError( $field, $value, $form )->text();
		} elseif ( $validationResult instanceof ApiMessage ) {
			return $validationResult->parse();
		}
		return true;
	}

	/**
	 * Generate an error message for a field. Used when the validator returns false.
	 *
	 * @param string $field
	 * @param mixed $value
	 * @param HTMLForm|null $form
	 * @return Message Error message (to be rendered via text()).
	 */
	private function getDefaultValidationError( string $field, $value, ?HTMLForm $form = null ): Message {
		$errorMessage = $this->msg( 'mwoauth-invalid-field-' . $field );
		if ( !$errorMessage->isDisabled() ) {
			return $errorMessage;
		}

		$generic = '';
		if ( $form && $form->getField( $field )->canDisplayErrors() ) {
			// error can be shown right next to the field so no need to mention the field name
			$generic = '-generic';
		}

		$problem = 'invalid';
		if ( $value === '' && !$generic ) {
			$problem = 'missing';
		}

		// messages: mwoauth-missing-field, mwoauth-invalid-field, mwoauth-invalid-field-generic
		return $this->msg( "mwoauth-$problem-field$generic", $field );
	}

	/**
	 * @param mixed $validator One of the callbacks registered via registerValidator.
	 * @param mixed $value The value of the field being validated.
	 * @param array $allValues All field values, keyed by field name.
	 * @param HTMLForm|null $form
	 * @return bool|ApiMessage
	 * @phan-param string|callable(mixed,array):(bool|StatusValue) $validator
	 */
	private function getValidationResult( $validator, $value, array $allValues, ?HTMLForm $form = null ) {
		if ( is_string( $validator ) ) {
			return preg_match( $validator, $value ?? '' );
		}
		$result = $validator( $value, $allValues );
		if ( $result instanceof StatusValue ) {
			if ( $result->isGood() ) {
				return true;
			} elseif ( count( $result->getMessages() ) !== 1 ) {
				throw new LogicException( 'Validator return status has too many errors: '
					. $result );
			}
			[ $errors, $warnings ] = $result->splitByErrorType();
			if ( $errors->isOK() ) {
				// $result is a warning -  if the user checked "ignore warnings", ignore;
				// otherwise show the checkbox
				if ( $form ) {
					// This is a horrible hack. There doesn't seem to be a way to modify a form's
					// CSS classes or other display properties between validation and rendering.
					$form->setId( 'oauth-form-with-warnings' );
				}

				if ( $allValues['ignorewarnings'] ?? false ) {
					return true;
				}
			}
			$result = $result->getMessages()[0];
		}
		if ( is_bool( $result ) || $result instanceof ApiMessage ) {
			return $result;
		}

		$type = get_debug_type( $result );
		throw new LogicException( 'Invalid validator return type: ' . $type );
	}

	/**
	 * Get the field names and their validation methods. Fields can be omitted.
	 *
	 * A validation method is either a regex string or a callable.
	 * Callables take (field value, field/value map) as params and must return a boolean or a
	 * StatusValue with a single ApiMessage in it. If that is a warning, the user will be allowed
	 * to override it. A StatusValue with an error or boolean false will prevent submission.
	 *
	 * When false is returned, the error message will be 'mwoauth-invalid-field-<fieldname>'
	 * if it exists, or a generic message otherwise (see getDefaultValidationError()).
	 *
	 * @return array (action => (field name => validation regex or function))
	 * @phan-return array<string,array<string,string|callable(mixed):(bool|StatusValue)|callable(mixed,array):(bool|StatusValue)>>
	 */
	abstract protected function getRequiredFields();

	/**
	 * Check action-independent permissions against the user for this submission
	 *
	 * @return Status
	 */
	abstract protected function checkBasePermissions();

	/**
	 * Check that the action is valid and that the required fields are valid
	 *
	 * @param array $required (field => regex or callback)
	 * @phan-param array<string,string|callable(mixed,array):bool|StatusValue> $required
	 * @return Status
	 */
	protected function validateFields( array $required ) {
		foreach ( $required as $field => $validator ) {
			if ( !isset( $this->vals[$field] ) ) {
				return $this->failure( "missing_field_$field", 'mwoauth-missing-field', $field );
			} elseif ( !is_scalar( $this->vals[$field] )
				&& !in_array( $field, [ 'restrictions', 'oauth2GrantTypes' ], true )
			) {
				return $this->failure( "invalid_field_$field", 'mwoauth-invalid-field', $field );
			}
			if ( is_string( $this->vals[$field] ) ) {
				$this->vals[$field] = trim( $this->vals[$field] );
			}
			$validationResult = $this->getValidationResult( $validator, $this->vals[$field], $this->vals );
			if ( $validationResult === false ) {
				$message = $this->getDefaultValidationError( $field, $this->vals[$field] );
				return $this->failure( "invalid_field_$field", $message );
			} elseif ( $validationResult instanceof ApiMessage ) {
				return $this->failure( $validationResult->getApiCode(), $validationResult );
			}
		}
		return $this->success();
	}

	/**
	 * Attempt to validate and submit this data for the given action
	 *
	 * @param string $action
	 * @return Status
	 */
	abstract protected function processAction( $action );

	/**
	 * @param string $error API error key
	 * @param string|MessageSpecifier $msg Message
	 * @param MessageParam|MessageSpecifier|string|int|float ...$params Additional arguments used as message parameters
	 * @return Status
	 */
	protected function failure( $error, $msg, ...$params ) {
		$status = Status::newFatal( $msg, ...$params );
		$status->value = [ 'error' => $error, 'result' => null ];
		return $status;
	}

	/**
	 * @param mixed|null $value
	 * @return Status
	 */
	protected function success( $value = null ) {
		return Status::newGood( [ 'error' => null, 'result' => $value ] );
	}

	public static function getIrrevocableGrants(): array {
		if ( self::$irrevocableGrants === null ) {
			self::$irrevocableGrants = array_merge(
				MediaWikiServices::getInstance()->getGrantsInfo()->getHiddenGrants(),
				self::AUTH_ONLY_GRANTS
			);
		}
		return self::$irrevocableGrants;
	}

	/**
	 * Given a list of accepted grants (in OAuth 1 terminology; scopes in OAuth 2 terminology),
	 * assumed to be from user input, filter them to those allowed by the consumer,
	 * and make sure that irrevocable grants needed by the consumer are included.
	 */
	protected function getAcceptedConsumerGrants( array $grants, Consumer $cmr ): array {
		return array_values(
			array_unique(
				array_intersect(
					array_merge( self::getIrrevocableGrants(), $grants ),
					// Only keep the applicable ones
					$cmr->getGrants()
				)
			)
		);
	}
}
