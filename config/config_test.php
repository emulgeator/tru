<?php
/**
 * Test configuration
 */

use Application\Config;

$config = Config::getInstance();

$config->set(array(
	'application.url' => 'http://tru.dev/',

	'resource.database.tru.host'     => 'localhost',
	'resource.database.tru.user'     => 'tru_test',
	'resource.database.tru.password' => 'tru_test',
	'resource.database.tru.database' => 'tru_test',
));