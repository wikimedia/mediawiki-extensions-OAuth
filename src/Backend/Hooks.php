<?php

namespace MediaWiki\Extension\OAuth\Backend;

use MediaWiki\Api\Hook\ApiRsdServiceApisHook;
use MediaWiki\ChangeTags\Hook\ChangeTagCanCreateHook;
use MediaWiki\ChangeTags\Hook\ChangeTagsListActiveHook;
use MediaWiki\ChangeTags\Hook\ListDefinedTagsHook;
use MediaWiki\Extension\OAuth\Frontend\OAuthLogFormatter;
use MediaWiki\Hook\SetupAfterCacheHook;
use MediaWiki\Status\Status;
use MediaWiki\Storage\NameTableStore;
use MediaWiki\User\User;
use MediaWiki\WikiMap\WikiMap;

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

	public function __construct(
		private readonly NameTableStore $changeTagDefStore,
	) {
	}

	/**
	 * Called right after configuration variables have been set.
	 */
	public static function onRegistration() {
		global $wgOAuth2PrivateKey, $wgOAuth2PublicKey,
			$wgJwtPublicKey, $wgJwtPrivateKey;

		// Set $wgOAuth2PrivateKey and $wgOAuth2PublicKey for Wikimedia Jenkins, PHPUnit.
		if ( defined( 'MW_PHPUNIT_TEST' ) || defined( 'MW_QUIBBLE_CI' ) ) {
			// Key needs to be >= 2048 bits
			$wgOAuth2PrivateKey = <<<EOK
-----BEGIN PRIVATE KEY-----
MIIEvgIBADANBgkqhkiG9w0BAQEFAASCBKgwggSkAgEAAoIBAQDs3fgcI/GbxNX0
jBANSuFdMfTnXd4V4aGwoz0EKBUdDH5O5sELJyDEo72x5HdPGqsesGsJf6Y15qAy
oYJAwG1OmWPt4yg+ezD/btkI19+LiGW6GIL7lzo27PuymM/Ga+7fKfgEGmBro1Dv
k+CdXcGiztUxlYEmwu3zDR1AbS2xw9XTIRvp9CQTU0TyGr2+Yn2Nqsp0/SNOnBIx
GuEFJnV2xe8RIu7qZMS6HdldNqutWeUl6p8NIn7pRHHCEyiEds7kk4ouFBcz61i8
08XONjozfqc/iMAQdjYwNuHqyyFi7XoESxRPDUOnd8fEUYognRXXpxyYoQ6UqcvY
9UItiVbHAgMBAAECggEAEB03tF5joPe+oIDo1Kar90WfRiA2LCHp+JTaYU6CxTOk
4iRDrMkQKyCClrgWv8xuKMvStFY5TgBvFJK1REdzCD5aNIRYKAwEdNQrMrVQ8XKp
jQP+4TPUE4mCxA8uT27nVMpLo6fRuHDnYC0cwkfvFO7iRRnJLARl4LubKldjHO02
vJGAoC8D5FlIOKhf43XAsEeMwvt6EfBCxPNLZG4tZxYMAgCvyttDNUDb0bZVcfJa
6TxlkvcHFZbUZbjXNfsENSf8JCpmL69yrRqYI87CHdEKNv2qge6XLvobhCSIxR+u
n/XW2GQ68mBu7FrRjH4unWIusJoGGq6WtYD1I3oqxQKBgQD4lVdUHoImRAXap0zF
nTrmJUFIMzuSY5XjCq2GTp/zGuzKkvzVjVQQ2WDeCiIJmUrG/P3LD7/c45zqvs/0
HNa6hT4VpXbDRtYJfcdWkwffhAWqSZOvMw6JeolYPMUnp+9NVENHCeBuvgyW7Ofr
Re+5vP2gA2KpZJ0K4I9XiqzKWwKBgQDz7yPUgJBGewfn/Ay5lObuBY16paaE3XRU
WFlLr2bLjViFGK3nx77ZV603WfZHSKMX3giogNbCQX1Eqa0WjrbVzhyQsFErunYR
SfFKUReQQKVmQ64RbvR8ltPWFt3Q+6YUl6iOTDRc31lCTw8FEd8qALh3+SO2bEVJ
2bokJPuZBQKBgQDfOxV6SA9ul6V+LsElsUWkSY5vbPqxQlbm1b7wnojLAsHkRM1i
ZRE6NEvl+cmJPyzEt4qeIR2WGpzevc33lTJLu73+KGIXiPRK/7XUDCOE2IVR39MN
AVero+vU3nXaX9fpZKMqFzeBm4+otUSrpllaPdqxKHkgT2crzm9LGRCEgwKBgQCd
khZVCI8p2ANtaTVXE8ZwbonLdgGwxdSFP2S/LRh72Fwb7as4k6DGiIpNvQEHXvZr
TnPNVRxk8yTWG7zBW4LjbXaqSBrG4nWuCVOiK+vKtNeizYk9nay0ZkGEg9TZUBUi
LC0nbjZM38GqwaL0JW1AlqKSbQ8SobHIWKqS+ojDeQKBgDT6qzcOjDreEgrsvDiQ
eyNzatzxeqfPaJulPW2kHQkHUFZjF8rQTsi1t5tHNNgRSy5GYq4LgM4unKO3YR4I
lQ5yYIBMJMa122duZA33fD8pmHIO3QilDjQVpGQBoEoHFNW86HSeRhwNGlbjsFA5
jkDFvq6aiIDc5ChjZDwG8LYI
-----END PRIVATE KEY-----

EOK;
			$wgOAuth2PublicKey = <<<EOK
-----BEGIN PUBLIC KEY-----
MIIBIjANBgkqhkiG9w0BAQEFAAOCAQ8AMIIBCgKCAQEA7N34HCPxm8TV9IwQDUrh
XTH0513eFeGhsKM9BCgVHQx+TubBCycgxKO9seR3TxqrHrBrCX+mNeagMqGCQMBt
Tplj7eMoPnsw/27ZCNffi4hluhiC+5c6Nuz7spjPxmvu3yn4BBpga6NQ75PgnV3B
os7VMZWBJsLt8w0dQG0tscPV0yEb6fQkE1NE8hq9vmJ9jarKdP0jTpwSMRrhBSZ1
dsXvESLu6mTEuh3ZXTarrVnlJeqfDSJ+6URxwhMohHbO5JOKLhQXM+tYvNPFzjY6
M36nP4jAEHY2MDbh6sshYu16BEsUTw1Dp3fHxFGKIJ0V16ccmKEOlKnL2PVCLYlW
xwIDAQAB
-----END PUBLIC KEY-----
EOK;
		} elseif ( !$wgOAuth2PrivateKey && $wgJwtPrivateKey ) {
			$wgOAuth2PrivateKey = $wgJwtPrivateKey;
			$wgOAuth2PublicKey = $wgJwtPublicKey;
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

	/** @inheritDoc */
	public function onListDefinedTags( &$tags ) {
		return $this->getUsedConsumerTags( false, $tags );
	}

	/** @inheritDoc */
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
		// Step 1: Get a list of all change tags that have ever been used on this wiki
		// that look like our tags, and extract the consumer IDs from them
		$consumerIds = [];
		foreach ( $this->changeTagDefStore->getMap() as $tagName ) {
			$consumerId = Utils::parseTagName( $tagName );
			if ( $consumerId ) {
				$consumerIds[] = $consumerId;
			}
		}
		if ( !$consumerIds ) {
			return true;
		}

		// Step 2: Verify that the consumers actually exist and are still valid
		$db = Utils::getOAuthDB( DB_REPLICA );
		$conds = [
			'oarc_id' => $consumerIds,
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

		foreach ( $res as $row ) {
			$tags[] = Utils::getTagName( $row->oarc_id );
		}

		return true;
	}

	/** @inheritDoc */
	public function onSetupAfterCache() {
		global $wgMWOAuthCentralWiki, $wgMWOAuthSharedUserIDs, $wgMWOAuthSharedUserSource;

		if ( $wgMWOAuthCentralWiki === false ) {
			// Treat each wiki as its own "central wiki" as there is no actual one
			$wgMWOAuthCentralWiki = WikiMap::getCurrentWikiId();
		} else {
			// There is actually a central wiki, requiring global user IDs via hook
			$wgMWOAuthSharedUserIDs = true;
		}

		if ( $wgMWOAuthSharedUserIDs === false ) {
			if ( !defined( 'MW_PHPUNIT_TEST' ) && !defined( 'MW_QUIBBLE_CI' ) ) {
				wfDeprecatedMsg( '$wgMWOAuthSharedUserIDs=false is deprecated, set '
					. '$wgMWOAuthSharedUserIDs=true, $wgMWOAuthSharedUserSource=\'local\' instead', '1.45', 'OAuth' );
			}
			$wgMWOAuthSharedUserSource = 'local';
		}
	}

	/** @inheritDoc */
	public function onApiRsdServiceApis( &$apis ) {
		$apis['MediaWiki']['settings']['OAuth'] = true;
	}
}
