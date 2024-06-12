<?php

namespace MediaWiki\Extension\OAuth\Backend;

use ILocalizedException;
use MediaWiki\Extension\OAuth\Lib\OAuthException;
use MediaWiki\Message\Message;
use Wikimedia\NormalizedException\INormalizedException;

/**
 * Exception class for human-readable OAuth errors.
 */
class MWOAuthException extends OAuthException implements INormalizedException, ILocalizedException {
	/** @var string */
	protected $msg;
	/** @var array */
	protected $params;

	/**
	 * Exception that may be shown to an end user.
	 * @param string $msg i18n message key for error text.
	 * @param array $params Error parameters. These double as i18n message parameters and PSR-3
	 *   log context. The array keys are used as log context keys; the position is used for i18n
	 *   (the first array element is $1 etc). Array items with numeric keys are omitted from PSR-3
	 *   logging.
	 */
	public function __construct( $msg, $params = [] ) {
		$this->msg = $msg;
		$this->params = $params;
		parent::__construct(
			$this->getMessageObject()->inLanguage( 'en' )->useDatabase( false )->plain()
		);
	}

	/**
	 * @inheritDoc
	 */
	public function getNormalizedMessage(): string {
		$paramsWithPsr3Placeholders = array_map( static function ( $val, $key ) {
			return is_numeric( $key ) ? $val : "{{$key}}";
		}, $this->params, array_keys( $this->params ) );
		return wfMessage( $this->msg, $paramsWithPsr3Placeholders )->inLanguage( 'en' )
			->useDatabase( false )->plain();
	}

	/**
	 * @inheritDoc
	 */
	public function getMessageContext(): array {
		return array_filter( $this->params, fn ( $key ) => !is_numeric( $key ), ARRAY_FILTER_USE_KEY );
	}

	/**
	 * @inheritDoc
	 */
	public function getMessageObject() {
		return new Message( $this->msg, array_values( $this->params ) );
	}

}
