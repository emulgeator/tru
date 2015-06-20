<?php

namespace Application\EntityHandler;


use Application\Application;
use Application\Entity\Address;

/**
 * Handler class for addresses.
 *
 * @package Application\EntityHandler
 */
class AddressHandler implements IEntityHandler {

	/**
	 * Returns the requested address
	 *
	 * @param int $addressId   Id of the address.
	 *
	 * @return Address|bool   The address or FALSE if it does not exist.
	 */
	public function getById($addressId) {
		return $this->getAddressDao()->getById($addressId);
	}

	/**
	 * Returns all the addresses
	 *
	 * @return Address[]
	 */
	public function getList() {
		return $this->getAddressDao()->getList();
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
		return $this->getAddressDao()->create($name, $phone, $street);
	}

	/**
	 * Returns the AddressDao object.
	 *
	 * @return \Application\Dao\AddressDao
	 */
	protected function getAddressDao() {
		return Application::getInstance()->getDependencyContainer()->getDao('AddressDao');
	}
}