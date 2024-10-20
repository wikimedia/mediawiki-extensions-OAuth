<?php

namespace MediaWiki\Extension\OAuth\Frontend;

use LogEntry;
use LogFormatter;
use MediaWiki\Linker\Linker;
use MediaWiki\Linker\LinkRenderer;
use MediaWiki\Message\Message;
use MediaWiki\Registration\ExtensionRegistry;
use MediaWiki\Title\Title;
use MediaWiki\Title\TitleFactory;
use MediaWiki\User\UserEditTracker;
use MediaWiki\User\UserIdentity;

/**
 * Formatter for OAuth log events
 */
class OAuthLogFormatter extends LogFormatter {
	protected ExtensionRegistry $extensionRegistry;
	protected LinkRenderer $linkRenderer;
	protected TitleFactory $titleFactory;
	protected UserEditTracker $userEditTracker;

	public function __construct(
		LogEntry $entry,
		LinkRenderer $linkRenderer,
		TitleFactory $titleFactory,
		UserEditTracker $userEditTracker
	) {
		parent::__construct( $entry );
		$this->extensionRegistry = ExtensionRegistry::getInstance();
		$this->linkRenderer = $linkRenderer;
		$this->titleFactory = $titleFactory;
		$this->userEditTracker = $userEditTracker;
	}

	protected function getMessageParameters() {
		$params = parent::getMessageParameters();
		if ( isset( $params[3] ) ) {
			$params[3] = $this->getConsumerLink( $params[3] );
		}
		return $params;
	}

	protected function getConsumerLink( $consumerKey ) {
		$title = Title::newFromText( 'Special:OAuthListConsumers/view/' . $consumerKey );
		if ( $this->plaintext ) {
			return '[[' . $title->getPrefixedText() . '|' . $consumerKey . ']]';
		} else {
			return Message::rawParam( $this->linkRenderer->makeLink( $title, $consumerKey ) );
		}
	}

	/**
	 * Add a link to the user's global account list to make review more convenient.
	 * @param UserIdentity $user
	 * @param int $toolFlags
	 * @return string
	 */
	protected function makeUserLink( UserIdentity $user, $toolFlags = 0 ) {
		// Only add custom links if a new consumer is being proposed and we can show tool links.
		if ( $this->entry->getSubtype() !== 'propose'
			|| $this->plaintext
			|| !$this->linkFlood
			|| !$this->extensionRegistry->isLoaded( 'CentralAuth' )
		) {
			return parent::makeUserLink( $user, $toolFlags );
		}

		$userLink = Linker::userLink(
			$user->getId(),
			$user->getName()
		);
		$editCount = $user->getId()
			? $this->userEditTracker->getUserEditCount( $user )
			: null;

		$toolLinkArray = Linker::userToolLinkArray(
			$user->getId(),
			$user->getName(),
			true,
			$toolFlags,
			$editCount
		);
		$toolLinkArray[] = $this->linkRenderer->makePreloadedLink(
			$this->titleFactory->newFromTextThrow( 'Special:CentralAuth/' . $user->getName() ),
			wfMessage( 'mwoauth-centralauth-account-link' )->text(),
			'mw-usertoollinks-oauth-globalaccount'
		);

		return $userLink . Linker::renderUserToolLinksArray( $toolLinkArray, false );
	}

}
