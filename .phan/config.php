<?php

$cfg = require __DIR__ . '/../vendor/mediawiki/mediawiki-phan-config/src/config.php';

$cfg['scalar_implicit_cast'] = true;

// Database->daoReadOnly and MWOAuthToken->oauth_callback_confirmed
$cfg['suppress_issue_types'][] = 'PhanUndeclaredProperty';

$cfg['directory_list'] = array_merge(
	$cfg['directory_list'],
	[
		'../../extensions/AbuseFilter',
		'../../extensions/Echo',
	]
);

$cfg['exclude_analysis_directory_list'] = array_merge(
	$cfg['exclude_analysis_directory_list'],
	[
		'src/Lib/',
		'../../extensions/AbuseFilter',
		'../../extensions/Echo',
	]
);

return $cfg;
