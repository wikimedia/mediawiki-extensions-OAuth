<?php

namespace MediaWiki\Extension\OAuth\Backend;

use MediaWiki\Exception\ILocalizedException;
use MediaWiki\Extension\OAuth\Lib\OAuthException;
use MediaWiki\Message\Message;
use Wikimedia\Message\MessageParam;
use Wikimedia\Message\MessageValue;
use Wikimedia\NormalizedException\INormalizedException;

/**
 * Exception class for human-readable OAuth errors.
 */
class MWOAuthException extends OAuthException implements INormalizedException, ILocalizedException {

	/**
	 * Exception that may be shown to an end user.
	 * @param MessageValue $msg
	 * @param array $context PSR-3 log context
	 */
	public function __construct(
		private readonly MessageValue $msg,
		private readonly array $context = [],
	) {
		parent::__construct(
			$this->getMessageObject()->inLanguage( 'en' )->useDatabase( false )->plain()
		);
	}

	/**
	 * @inheritDoc
	 */
	public function getNormalizedMessage(): string {
		$paramsPlaceholders = array_map( static function ( $i ) {
			return "{parameter" . ( $i + 1 ) . "}";
		}, array_keys( $this->msg->getParams() ) );
		return wfMessage( $this->msg->getKey(), $paramsPlaceholders )->inLanguage( 'en' )
			->useDatabase( false )->plain();
	}

	/**
	 * @inheritDoc
	 */
	public function getMessageContext(): array {
		$paramsPlaceholders = array_map( static function ( $i ) {
			return "parameter" . ( $i + 1 );
		}, array_keys( $this->msg->getParams() ) );
		return array_combine( $paramsPlaceholders, array_map( static function ( MessageParam $param ) {
			return $param->getValue();
		}, $this->msg->getParams() ) ) + $this->context;
	}

	/**
	 * @inheritDoc
	 */
	public function getMessageObject() {
		return Message::newFromSpecifier( $this->msg );
	}

}
