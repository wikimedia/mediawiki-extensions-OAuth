<?php
/*
 (c) Aaron Schulz 2013, GPL

 This program is free software; you can redistribute it and/or modify
 it under the terms of the GNU General Public License as published by
 the Free Software Foundation; either version 2 of the License, or
 (at your option) any later version.

 This program is distributed in the hope that it will be useful,
 but WITHOUT ANY WARRANTY; without even the implied warranty of
 MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 GNU General Public License for more details.

 You should have received a copy of the GNU General Public License along
 with this program; if not, write to the Free Software Foundation, Inc.,
 59 Temple Place - Suite 330, Boston, MA 02111-1307, USA.
 http://www.gnu.org/copyleft/gpl.html
*/

/**
 * Handle the logic of submitting a client request
 */
abstract class MWOAuthSubmitControl extends ContextSource {
	/** @var RequestContext */
	protected $context;
	/** @var Array (field name => value) */
	protected $vals;

	/**
	 * @param IContextSource $context
	 * @param array $params
	 */
	public function __construct( IContextSource $context, array $params ) {
		$this->context = $context;
		$this->vals = $params;
	}

	/**
	 * Attempt to validate and submit this data
	 *
	 * This will check basic permissions, validate the action and paramters
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
	 * Get the field names and their validation regexes or functions
	 * (which return a boolean) for each action that this controller handles
	 *
	 * @return Array (action => (field name => validation regex or function))
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
	 * @return Status
	 */
	protected function validateFields( array $required ) {
		foreach ( $required as $field => $validator ) {
			if ( !isset( $this->vals[$field] ) ) {
				// @TODO: check for field-specific message first
				return $this->failure( "missing_field_$field", 'mwoauth-missing-field', $field );
			} elseif ( !is_scalar( $this->vals[$field] ) ) {
				// @TODO: check for field-specific message first
				return $this->failure( "invalid_field_$field", 'mwoauth-invalid-field', $field );
			}
			if ( is_string( $this->vals[$field] ) ) {
				$this->vals[$field] = trim( $this->vals[$field] ); // trim all input
			}
			$valid = is_string( $validator ) // regex
				? preg_match( $validator, $this->vals[$field] )
				: $validator( $this->vals[$field] );
			if ( !$valid ) {
				// @TODO: check for field-specific message first
				return $this->failure( "invalid_field_$field", 'mwoauth-invalid-field', $field );
			}
		}
		return $this->success();
	}

	/**
	 * Attempt to validate and submit this data for the given action
	 *
	 * @param string $action
	 * @return array Status
	 */
	abstract protected function processAction( $action );

	/**
	 * @param string $error API error key
	 * @param string $msg Message key
	 * @param mixed ... Additional arguments used as message parameters
	 * @return Status
	 */
	protected function failure( $error, $msg /*, params */ ) {
		$params = array_slice( func_get_args(), 2 );
		$status = Status::newFatal( $this->context->msg( $msg, $params ) );
		$status->value = array( 'error' => $error, 'result' => null );
		return $status;
	}

	/**
	 * @param mixed $value
	 * @return Status
	 */
	protected function success( $value = null ) {
		return Status::newGood( array( 'error' => null, 'result' => $value ) );
	}
}
