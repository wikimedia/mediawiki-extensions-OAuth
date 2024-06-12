<?php

namespace MediaWiki\Extension\OAuth\Frontend;

use MediaWiki\Extension\Notifications\AttributeManager;
use MediaWiki\Extension\Notifications\Formatters\EchoEventPresentationModel;
use MediaWiki\Extension\OAuth\Backend\Consumer;
use MediaWiki\Extension\OAuth\Backend\Utils;
use MediaWiki\SpecialPage\SpecialPage;
use MediaWiki\User\User;
use MWException;

class EchoOAuthStageChangePresentationModel extends EchoEventPresentationModel {
	/** @var User[] OAuth admins who should be notified about additions to the review queue */
	protected static $oauthAdmins;

	/** @var Consumer|false */
	protected $consumer;

	/** @var User|false The owner of the OAuth consumer */
	protected $owner;

	/**
	 * Helper function for $wgEchoNotifications
	 * @param string $action One of the actions from ConsumerSubmitControl::$actions
	 * @return array
	 */
	public static function getDefinition( $action ) {
		if ( $action === 'propose' ) {
			// notify admins
			$category = 'oauth-admin';
		} else {
			// notify owner
			$category = 'oauth-owner';
		}

		return [
			AttributeManager::ATTR_LOCATORS => [
				[ [ Utils::class, 'locateUsersToNotify' ] ]
			],
			'category' => $category,
			'presentation-model' => self::class,
			'icon' => 'oauth',
		];
	}

	/**
	 * Hide notifications that were already handled by another OAuth admin
	 * @inheritDoc
	 */
	public function canRender() {
		$action = $this->event->getExtraParam( 'action' );
		return !( $action === 'propose' && $this->getConsumerStage() !== Consumer::STAGE_PROPOSED );
	}

	public function getHeaderMessage() {
		$action = $this->event->getExtraParam( 'action' );
		return $this->msg( "notification-oauth-app-$action-title",
			$this->event->getAgent(), $this->getConsumerName(), $this->getOwner() );
	}

	public function getSubjectMessage() {
		$action = $this->event->getExtraParam( 'action' );
		return $this->msg( "notification-oauth-app-$action-subject",
			$this->event->getAgent(), $this->getConsumerName(), $this->getOwner() );
	}

	public function getBodyMessage() {
		$comment = $this->event->getExtraParam( 'comment' );
		return $comment ? $this->msg( 'notification-oauth-app-body', $comment ) : false;
	}

	public function getIconType() {
		return 'oauth';
	}

	public function getPrimaryLink() {
		$consumerKey = $this->event->getExtraParam( 'app-key' );
		$action = $this->event->getExtraParam( 'action' );

		if ( $action === 'propose' ) {
			// show management interface
			$page = SpecialPage::getSafeTitleFor( 'OAuthManageConsumers', $consumerKey );
		} else {
			// show public view
			$page = SpecialPage::getSafeTitleFor( 'OAuthListConsumers', "view/$consumerKey" );
		}
		if ( $page === null ) {
			throw new MWException( "Invalid app ID: $consumerKey" );
		}

		return [
			'url' => $page->getLocalURL(),
			'label' => $this->msg( "notification-oauth-app-$action-primary-link" )->text(),
		];
	}

	public function getSecondaryLinks() {
		return [ $this->getAgentLink() ];
	}

	/**
	 * @return Consumer|false
	 */
	protected function getConsumer() {
		if ( $this->consumer === null ) {
			$dbr = Utils::getCentralDB( DB_REPLICA );
			$this->consumer =
				Consumer::newFromKey( $dbr, $this->event->getExtraParam( 'app-key' ) );
		}
		return $this->consumer;
	}

	/**
	 * @return User|false
	 */
	protected function getOwner() {
		if ( $this->owner === null ) {
			$this->owner = Utils::getLocalUserFromCentralId(
				$this->event->getExtraParam( 'owner-id' ) );
		}
		return $this->owner;
	}

	/**
	 * @return string|false
	 */
	protected function getConsumerName() {
		$consumer = $this->getConsumer();
		return $consumer ? $consumer->getName() : false;
	}

	/**
	 * @return int The stage, or -1 if the consumer doesn't exist
	 */
	protected function getConsumerStage() {
		$consumer = $this->getConsumer();
		return $consumer ? $consumer->getStage() : -1;
	}
}
