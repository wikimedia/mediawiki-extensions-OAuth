<?php

namespace MediaWiki\Extension\OAuth\Tests\Unit;

use MediaWiki\Extension\OAuth\HookRunner;
use MediaWiki\Tests\HookContainer\HookRunnerTestBase;

/**
 * @covers \MediaWiki\Extension\OAuth\HookRunner
 */
class HookRunnerTest extends HookRunnerTestBase {

	public static function provideHookRunners() {
		yield HookRunner::class => [ HookRunner::class ];
	}
}
