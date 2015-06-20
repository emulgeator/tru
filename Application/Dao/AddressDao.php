<?php

namespace Application\Dao;


use PDO;
use Application\Entity\Address;


/**
 * Class Address related Database handling.
 *
 * @package Application\Dao
 */
class AddressDao extends DaoAbstract {

	/** Class name of the Address entity. */
	const ADDRESS_ENTITY_CLASS_NAME = '\\Application\\Entity\\Address';

	/**
	 * Returns the requested address
	 *
	 * @param int $addressId   Id of the address.
	 *
	 * @return Address|bool   The address or FALSE if it does not exist.
	 */
	public function getById($addressId) {
		$query = '
			SELECT
				id, name, phone,
				street, created_at
			FROM
				address
			WHERE
				id = :addressId
		';

		$preparedStatement = $this->prepareAndExecuteStatement($query, array('addressId' => $addressId));
		$preparedStatement->setFetchMode(PDO::FETCH_CLASS, self::ADDRESS_ENTITY_CLASS_NAME);

		return $preparedStatement->fetch();
	}

	/**
	 * Returns all the addresses
	 *
	 * @return Address[]
	 */
	public function getList() {
		$query = '
			SELECT
				id, name, phone,
				street, created_at
			FROM
				address
			ORDER BY
				id
		';

		$preparedStatement = $this->prepareAndExecuteStatement($query);
		$preparedStatement->setFetchMode(PDO::FETCH_CLASS, self::ADDRESS_ENTITY_CLASS_NAME);

		return $preparedStatement->fetchAll();
	}

	/**
	 * Creates a new address entry.
	 *
	 * @param string $name     Name of the person.
	 * @param string $phone    Phone number.
	 * @param string $street   Street address.
	 *
	 * @return int   Id of the newly created entry.
	 */
	public function create($name, $phone, $street) {
		$insert = '
			INSERT INTO
				address
				(name, phone, street, created_at)
			VALUES
				(:name, :phone, :street, NOW())
		';
		$queryParams = array(
			'name'   => $name,
			'phone'  => $phone,
			'street' => $street
		);

		$this->prepareAndExecuteStatement($insert, $queryParams);

		return (int)$this->connection->lastInsertId();
	}

	/**
	 * Deletes the given address.
	 *
	 * @param int $addressId   Id of the address to delete.
	 *
	 * @return bool
	 */
	public function delete($addressId) {
		$delete = '
			DELETE FROM
				address
			WHERE
				id = :addressId
		';

		$statement = $this->prepareAndExecuteStatement($delete, array('addressId' => $addressId));

		return (bool)$statement->rowCount();
	}
}