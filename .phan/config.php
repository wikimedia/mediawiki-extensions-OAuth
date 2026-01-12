<?php

$cfg = require __DIR__ . '/../vendor/mediawiki/mediawiki-phan-config/src/config.php';

// TODO Fix these issues, suppressed to allow upgrading
$cfg['suppress_issue_types'][] = 'PhanThrowTypeAbsent';

$cfg['directory_list'] = array_merge(
	$cfg['directory_list'],
	[
		'../../extensions/AbuseFilter',
		'../../extensions/Echo',
		'../../extensions/UserMerge',
	]
);

$cfg['exclude_analysis_directory_list'] = array_merge(
	$cfg['exclude_analysis_directory_list'],
	[
		'../../extensions/AbuseFilter',
		'../../extensions/Echo',
		'../../extensions/UserMerge',
	]
);

return $cfg;
