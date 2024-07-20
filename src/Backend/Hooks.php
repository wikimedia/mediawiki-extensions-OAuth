<?php

namespace MediaWiki\Extension\OAuth\Backend;

use MediaWiki\Api\Hook\ApiRsdServiceApisHook;
use MediaWiki\ChangeTags\Hook\ChangeTagCanCreateHook;
use MediaWiki\ChangeTags\Hook\ChangeTagsListActiveHook;
use MediaWiki\ChangeTags\Hook\ListDefinedTagsHook;
use MediaWiki\Extension\OAuth\Frontend\OAuthLogFormatter;
use MediaWiki\Hook\SetupAfterCacheHook;
use MediaWiki\Status\Status;
use MediaWiki\Storage\NameTableAccessException;
use MediaWiki\Storage\NameTableStore;
use MediaWiki\User\User;
use MediaWiki\WikiMap\WikiMap;
use Wikimedia\Rdbms\IConnectionProvider;

/**
 * Class containing hooked functions for an OAuth environment
 */
class Hooks implements
	ApiRsdServiceApisHook,
	ChangeTagsListActiveHook,
	ChangeTagCanCreateHook,
	ListDefinedTagsHook,
	SetupAfterCacheHook
{

	/** @var NameTableStore */
	private $changeTagDefStore;

	/** @var IConnectionProvider */
	private $connectionProvider;

	/**
	 * @param NameTableStore $changeTagDefStore
	 * @param IConnectionProvider $connectionProvider
	 */
	public function __construct( NameTableStore $changeTagDefStore, IConnectionProvider $connectionProvider
	) {
		$this->changeTagDefStore = $changeTagDefStore;
		$this->connectionProvider = $connectionProvider;
	}

	/**
	 * Called right after configuration variables have been set.
	 */
	public static function onRegistration() {
		global $wgOAuth2PrivateKey, $wgOAuth2PublicKey;

		// Set $wgOAuth2PrivateKey and $wgOAuth2PublicKey for Wikimedia Jenkins, PHPUnit.
		if ( defined( 'MW_PHPUNIT_TEST' ) || defined( 'MW_QUIBBLE_CI' ) ) {
			$wgOAuth2PrivateKey = <<<EOK
-----BEGIN RSA PRIVATE KEY-----
MIIBOwIBAAJBAMBGXQYJ2lXzLuQkRlWoqYJvSnNGfRvPBUVsbHfFPyCr8i6jBPcO
vtMLFMRAaq4quRDFgQ7YQLvKTqjpN+bo7RECAwEAAQJBAKP3XTzZCihhyYskpBZI
TsW8wnCrm+UrFgOuApHg04S3oeUXpNApxxGy+EX0aBsVoPBuisyBjiJDIFssdgJa
IwECIQDuMipv8QOzA9qJPPpXZCQQN6znXjSE3jZhrBH879SDBQIhAM6lgY0lWB0N
lhQZWtM8jRcxtJUFrApEizE6WFxj/LedAiEAyINgaAVqiMror3iugNyi4ygLHGWY
LnVlMAmKxvMZYQUCIAYTeb6ztWaNSrdmk3QYmLFw5bVoCEn4//q/k2+MBRdFAiA2
MJWJuom6IpoP0UrM/gJbwGxwgZymb4jL+sKFoIqGmA==
-----END RSA PRIVATE KEY-----
EOK;
			$wgOAuth2PublicKey = <<<EOK
-----BEGIN PUBLIC KEY-----
MFwwDQYJKoZIhvcNAQEBBQADSwAwSAJBAMBGXQYJ2lXzLuQkRlWoqYJvSnNGfRvP
BUVsbHfFPyCr8i6jBPcOvtMLFMRAaq4quRDFgQ7YQLvKTqjpN+bo7RECAwEAAQ==
-----END PUBLIC KEY-----
EOK;
		}
	}

	public static function onExtensionFunctions() {
		global $wgLogTypes, $wgLogNames,
			$wgLogHeaders, $wgLogActionsHandlers, $wgActionFilteredLogs;

		if ( Utils::isCentralWiki() ) {
			$wgLogTypes[] = 'mwoauthconsumer';
			$wgLogNames['mwoauthconsumer'] = 'mwoauthconsumer-consumer-logpage';
			$wgLogHeaders['mwoauthconsumer'] = 'mwoauthconsumer-consumer-logpagetext';
			$wgLogActionsHandlers['mwoauthconsumer/*'] = [
				'class' => OAuthLogFormatter::class,
				'services' => [
					'LinkRenderer',
					'TitleFactory',
					'UserEditTracker',
				],
			];
			$wgActionFilteredLogs['mwoauthconsumer'] = [
				'approve' => [ 'approve' ],
				'create-owner-only' => [ 'create-owner-only' ],
				'disable' => [ 'disable' ],
				'propose' => [ 'propose' ],
				'propose-autoapproved' => [ 'propose-autoapproved' ],
				'reenable' => [ 'reenable' ],
				'reject' => [ 'reject' ],
				'update' => [ 'update' ],
			];
		}
	}

	/**
	 * Reserve change tags that look like an OAuth change tag.
	 *
	 * @param string $tag
	 * @param User|null $user
	 * @param Status &$status
	 */
	public function onChangeTagCanCreate( $tag, $user, &$status ) {
		if ( Utils::isReservedTagName( $tag ) ) {
			$status->fatal( 'mwoauth-tag-reserved' );
		}
	}

	public static function onMergeAccountFromTo( User $oUser, User $nUser ) {
		global $wgMWOAuthSharedUserIDs;

		if ( !$wgMWOAuthSharedUserIDs ) {
			$oldid = $oUser->getId();
			$newid = $nUser->getId();
			if ( $oldid && $newid ) {
				self::doUserIdMerge( $oldid, $newid );
			}
		}

		return true;
	}

	protected static function doUserIdMerge( $oldid, $newid ) {
		$dbw = Utils::getCentralDB( DB_PRIMARY );
		// Merge any consumers register to this user
		$dbw->newUpdateQueryBuilder()
			->update( 'oauth_registered_consumer' )
			->set( [ 'oarc_user_id' => $newid ] )
			->where( [ 'oarc_user_id' => $oldid ] )
			->caller( __METHOD__ )
			->execute();
		// Delete any acceptance tokens by the old user ID
		$dbw->newDeleteQueryBuilder()
			->deleteFrom( 'oauth_accepted_consumer' )
			->where( [ 'oaac_user_id' => $oldid ] )
			->caller( __METHOD__ )
			->execute();
	}

	public function onListDefinedTags( &$tags ) {
		return $this->getUsedConsumerTags( false, $tags );
	}

	public function onChangeTagsListActive( &$tags ) {
		return $this->getUsedConsumerTags( true, $tags );
	}

	/**
	 * List tags that should show as defined/active on Special:Tags
	 *
	 * Handles both the ChangeTagsListActive and ListDefinedTags hooks. Only
	 * lists those tags that are actually in use on the local wiki, to avoid
	 * flooding Special:Tags with tags for consumers that will never be making
	 * logged actions.
	 *
	 * @param bool $activeOnly true for ChangeTagsListActive, false for ListDefinedTags
	 * @param array &$tags
	 * @return bool
	 */
	private function getUsedConsumerTags( $activeOnly, &$tags ) {
		// Step 1: Get the list of (active) consumers' tags for this wiki
		$db = Utils::getCentralDB( DB_REPLICA );
		$conds = [
			$db->expr( 'oarc_wiki', '=', [ '*', WikiMap::getCurrentWikiId() ] ),
			'oarc_deleted' => 0,
		];
		if ( $activeOnly ) {
			$conds[] = $db->expr( 'oarc_stage', '=', [ Consumer::STAGE_APPROVED, Consumer::STAGE_PROPOSED ] );
		}
		$res = $db->newSelectQueryBuilder()
			->select( 'oarc_id' )
			->from( 'oauth_registered_consumer' )
			->where( $conds )
			->caller( __METHOD__ )
			->fetchResultSet();
		$allTags = [];
		foreach ( $res as $row ) {
			$allTags[] = Utils::getTagName( $row->oarc_id );
		}

		// Step 2: Return only those that are in use.
		foreach ( $allTags as $tag ) {
			try {
				$this->changeTagDefStore->getId( $tag );
			} catch ( NameTableAccessException $ex ) {
				continue;
			}
			// if it has an ID, it's in use
			$tags[] = $tag;
		}

		return true;
	}

	public function onSetupAfterCache() {
		global $wgMWOAuthCentralWiki, $wgMWOAuthSharedUserIDs;

		if ( $wgMWOAuthCentralWiki === false ) {
			// Treat each wiki as its own "central wiki" as there is no actual one
			$wgMWOAuthCentralWiki = WikiMap::getCurrentWikiId();
		} else {
			// There is actually a central wiki, requiring global user IDs via hook
			$wgMWOAuthSharedUserIDs = true;
		}
	}

	public function onApiRsdServiceApis( &$apis ) {
		$apis['MediaWiki']['settings']['OAuth'] = true;
	}
}
