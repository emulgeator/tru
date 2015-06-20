<?php

namespace Application\EntityHandler;


use Application\Application;
use Application\Entity\Address;
use Application\Exception\ParameterException;

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
	 * @throws ParameterException
	 *
	 * @return int   Id of the newly created entry.
	 */
	public function create($name, $phone, $street) {
		$this->validateName($name);
		$this->validatePhoneNumber($phone);
		$this->validateStreet($street);

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

	/**
	 * Validates the given name
	 *
	 * @param string $name
	 */
	protected function validateName($name) {
		if (!preg_match('#^[\s\p{L}]{1,255}$#iu', $name)) {
			throw new ParameterException('Invalid name');
		}
	}

	/**
	 * Validates the given phone number
	 *
	 * @param string $phoneNumber
	 */
	protected function validatePhoneNumber($phoneNumber) {
		if (!preg_match('#^[\d\s+_-]{1,20}$#iu', $phoneNumber)) {
			throw new ParameterException('Invalid phone number');
		}
	}

	/**
	 * Validates the given street address.
	 *
	 * @param string $street
	 */
	protected function validateStreet($street) {
		if (!preg_match('#^.{1,255}$#iu', $street)) {
			throw new ParameterException('Invalid street address');
		}
	}
}