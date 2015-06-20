<?php

namespace Application\Dao;


use PDO;
use Application\Config;

/**
 * Class DaoAbstract
 *
 * @package Application\Dao
 */
abstract class DaoAbstract implements IDao {

	/**
	 * The db connection.
	 *
	 * @var PDO
	 */
	protected $connection;

	/**
	 * Returns a connection to the Database.
	 *
	 * @throws \Application\Exception\ConfigException
	 */
	protected function connect() {
		if (!empty($this->connection)) {
			return;
		}

		$dbConfig = Config::getInstance()->get('resource.database.tru.*');

		$dsn = 'mysql:host=' . $dbConfig['host'] . ';dbname=' . $dbConfig['database'];
		$this->connection = new PDO($dsn, $dbConfig['user'], $dbConfig['password']);
	}

	/**
	 * Prepares a statement and executes it
	 *
	 * @param string $query    The query to execute.
	 * @param array  $params   The parameters for the query.
	 *
	 * @return \PDOStatement
	 */
	protected function prepareAndExecuteStatement($query, array $params = array()) {
		$this->connect();
		$statement = $this->connection->prepare($query);

		foreach ($params as $key => $value) {
			$statement->bindValue(':' . $key, $value, $this->getParamType($value));
		}
		$statement->execute();

		return $statement;
	}

	/**
	 * Returns the PDO data type for the specified value.
	 *
	 * Also casts the specified value if it's necessary.
	 *
	 * @param mixed $value   The value to examine.
	 *
	 * @return int   The PDO data type.
	 */
	protected function getParamType(&$value) {
		if (is_integer($value) || is_float($value)) {
			return PDO::PARAM_INT;
		}
		elseif (is_null($value)) {
			return PDO::PARAM_NULL;
		}
		elseif (is_bool($value)) {
			return PDO::PARAM_BOOL;
		}
		else {
			$value = (string)$value;
			return PDO::PARAM_STR;
		}
	}
}