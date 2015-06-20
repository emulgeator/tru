<?php
/**
 * Contains the configuration of the application.
 */

use Application\Config;

Config::getInstance()->set(array(
	'resource.database.tru.host'     => 'localhost',
	'resource.database.tru.user'     => 'tru',
	'resource.database.tru.password' => 'tru',
	'resource.database.tru.database' => 'tru',
));