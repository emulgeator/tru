<?php

namespace Application\Controller;


use Application\Application;
use Application\Entity\Address;
use Application\Exception\HttpException;

/**
 * Address related actions
 *
 * @package Application\Controller
 */
class AddressController extends ControllerAbstract {

	/**
	 * Indicates if the current request is a legacy one.
	 *
	 * @var bool
	 */
	protected $isLegacyRequest = false;

	/**
	 * Returns the list of the addresses
	 *
	 * Or the given address (required for backward compatibility) if id is given as a GET parameter
	 *
	 * @return array
	 */
	public function getList() {
		if ($this->getRequest()->hasGet('id')) {
			$addressId = (int)$this->getRequest()->getGet('id');
			$this->isLegacyRequest = true;
			$this->getRequest()->setRouteParam($addressId);

			return $this->get();
		}

		return $this->getAddressHandler()->getList();
	}

	/**
	 * Returns one address by id.
	 *
	 * @return array
	 */
	public function get() {
		$addressId = (int)$this->getRequest()->getRouteParam();

		$address = $this->getAddressHandler()->getById($addressId);

		if (empty($address)) {
			throw new HttpException(404);
		}

		if ($this->isLegacyRequest) {
			$this->cleanAddress($address);
		}

		return $address;
	}


	/**
	 * Removes the id and created_at from the address.
	 *
	 * @param \Application\Entity\Address $address
	 */
	protected function cleanAddress(Address $address) {
		unset($address->id, $address->created_at);
	}
}