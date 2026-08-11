<?php

namespace MediaWiki\Extension\XAnalytics\Hooks;

use MediaWiki\Output\OutputPage;

/**
 * This is a hook handler interface, see docs/Hooks.md in core.
 * Use the hook name "XAnalyticsSetHeader" to register handlers implementing this interface.
 *
 * @stable to implement
 * @ingroup Hooks
 */
interface XAnalyticsSetHeaderHook {
	/**
	 * @param OutputPage $out
	 * @param array &$headerItems
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onXAnalyticsSetHeader( OutputPage $out, array &$headerItems );
}
