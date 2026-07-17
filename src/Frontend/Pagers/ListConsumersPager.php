<?php

namespace MediaWiki\Extension\OAuth\Frontend\Pagers;

use MediaWiki\Extension\OAuth\Backend\Utils;
use MediaWiki\Extension\OAuth\Entity\ClientEntity;
use MediaWiki\Extension\OAuth\Frontend\SpecialPages\SpecialMWOAuthListConsumers;
use MediaWiki\MediaWikiServices;
use MediaWiki\Pager\AlphabeticPager;
use MediaWiki\Title\Title;
use stdClass;

/**
 * (c) Aaron Schulz 2013, GPL
 *
 * @license GPL-2.0-or-later
 */

/**
 * Query to list out consumers
 */
class ListConsumersPager extends AlphabeticPager {
	/** @var SpecialMWOAuthListConsumers */
	public $mForm;

	/** @var array */
	public $mConds;

	private ?string $grantType;

	/**
	 * @param SpecialMWOAuthListConsumers $form
	 * @param array $conds
	 * @param string|null $name
	 * @param int|null $centralUserID
	 * @param int $stage
	 * @param string|null $grantType
	 */
	public function __construct( $form, $conds, $name, $centralUserID, $stage, ?string $grantType = null ) {
		$this->mForm = $form;
		$this->mConds = $conds;
		$this->grantType = in_array( $grantType, [
			ClientEntity::GRANT_TYPE_CLIENT_CREDENTIALS,
			ClientEntity::GRANT_TYPE_AUTHORIZATION_CODE,
		], true ) ? $grantType : null;

		$indexField = null;
		if ( $name !== '' ) {
			$this->mConds['oarc_name'] = $name;
			$indexField = 'oarc_id';
		}
		if ( $centralUserID !== null ) {
			$this->mConds['oarc_user_id'] = $centralUserID;
			$indexField = 'oarc_id';
		}
		if ( $stage >= 0 ) {
			$this->mConds['oarc_stage'] = $stage;
			if ( !$indexField ) {
				$indexField = 'oarc_stage_timestamp';
			}
		}
		if ( !$indexField ) {
			$indexField = 'oarc_id';
		}
		$this->mIndexField = $indexField;

		$permissionManager = MediaWikiServices::getInstance()->getPermissionManager();
		if ( !$permissionManager->userHasRight( $this->getUser(), 'mwoauthviewsuppressed' ) ) {
			$this->mConds['oarc_deleted'] = 0;
		}

		$this->mDb = Utils::getOAuthDB( DB_REPLICA );
		parent::__construct();

		# Treat 20 as the default limit, since each entry takes up 5 rows.
		$urlLimit = $this->mRequest->getInt( 'limit' );
		$this->mLimit = $urlLimit ?: 20;
	}

	/**
	 * @return Title
	 */
	public function getTitle() {
		return $this->mForm->getFullTitle();
	}

	/**
	 * @param stdClass $row
	 * @return string
	 */
	public function formatRow( $row ) {
		return $this->mForm->formatRow( $this->mDb, $row );
	}

	/**
	 * @return string
	 */
	public function getStartBody() {
		if ( $this->getNumRows() ) {
			return '<ul>';
		} else {
			return '';
		}
	}

	/**
	 * @return string
	 */
	public function getEndBody() {
		if ( $this->getNumRows() ) {
			return '</ul>';
		} else {
			return '';
		}
	}

	/**
	 * @return array
	 */
	public function getQueryInfo() {
		$conds = $this->mConds;
		if ( $this->grantType !== null ) {
			$conds['oarc_oauth_version'] = 2;
			$conds[] = 'oarc_oauth2_allowed_grants ' . $this->mDb->buildLike(
				$this->mDb->anyString(),
				'"' . $this->grantType . '"',
				$this->mDb->anyString()
			);
		}
		return [
			'tables' => [ 'oauth_registered_consumer' ],
			'fields' => [ '*' ],
			'conds'  => $conds
		];
	}

	/**
	 * @return string
	 */
	public function getIndexField() {
		return $this->mIndexField;
	}
}
