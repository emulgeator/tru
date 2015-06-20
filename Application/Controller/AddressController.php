<?php

namespace Application\Controller;


use Application\Application;
use Application\Entity\Address;
use Application\Exception\HttpException;
use Application\Exception\ParameterException;

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
		if ($this->request->hasGet('id')) {
			$addressId = (int)$this->request->getGet('id');
			$this->isLegacyRequest = true;
			$this->request->setRouteParam($addressId);

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
		$addressId = (int)$this->request->getRouteParam();

		$address = $this->getAddressHandler()->getById($addressId);

		if (empty($address)) {
			throw new HttpException('Address does not exist', 404);
		}

		if ($this->isLegacyRequest) {
			$this->cleanAddress($address);
		}

		return $address;
	}

	/**
	 * Stores the given address in the DB if valid.
	 *
	 * @return void
	 */
	public function create() {
		$name = (string)$this->request->getPost('name');
		$phone = (string)$this->request->getPost('phone');
		$street = (string)$this->request->getPost('street');

		try {
			$addressId = $this->getAddressHandler()->create($name, $phone, $street);
		}
		catch (ParameterException $e) {
			throw new HttpException($e->getMessage(), 400);
		}

		$this->response->setStatusCode('201');
		$this->response->addHeader('Location', 'address/' . $addressId);
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