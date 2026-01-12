<?php

namespace MediaWiki\Extension\UserMerge\Hooks;

use MediaWiki\User\User;

/**
 * This is a hook handler interface, see docs/Hooks.md in core.
 * Use the hook name "MergeAccountFromTo" to register handlers implementing this interface.
 *
 * @stable to implement
 * @ingroup Hooks
 */
interface MergeAccountFromToHook {
	/**
	 * Merge from one user to another user
	 *
	 * @param User &$oldUser
	 * @param User &$newUser
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onMergeAccountFromTo( User &$oldUser, User &$newUser );
}
