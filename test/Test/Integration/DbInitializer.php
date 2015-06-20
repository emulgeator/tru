<?php

namespace Test\Integration;

use Application\Config;

/**
 * Responsible for initializing the Database for integration testing.
 */
class DbInitializer extends \Application\Dao\DaoAbstract {

	/**
	 * Initializes the test database.
	 *
	 * @throws \Application\Exception\ConfigException
	 */
	public static function initDatabase() {
		$dbConfig = Config::getInstance()->get('resource.database.tru.*');
		$schemaFile = getPath('db/tru_schema.sql');

		$charset = empty($dbConfig['charset']) ? 'utf8' : $dbConfig['charset'];

		$importCommand = 'mysql -h ' . $dbConfig['host']
			. ' -u ' . $dbConfig['user']
			. ' --default-character-set ' . $charset
			. ' -p' . $dbConfig['password']
			. ' ' . $dbConfig['database']
			. ' < ' . escapeshellarg(realpath($schemaFile)) . ' 2>&1';

		$output = array();

		exec($importCommand, $output, $returnValue);

		if ($returnValue != 0) {
			echo 'Error while importing file: ' . $schemaFile . "\n" . ' Error message: '
				. implode('\n', $output) . "\n";
			exit;
		}
	}
}
