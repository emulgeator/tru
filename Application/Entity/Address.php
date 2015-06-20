<?php

namespace Application\Entity;


/**
 * Class which represents an address.
 *
 * @package Application\Entity
 */
class Address {
	/** @var int   Id of the address. */
	public $id;

	/** @var string   Name of the person. */
	public $name;

	/** @var string   Phone number of the person. */
	public $phone;

	/** @var string   Street address of the person. */
	public $street;

	/** @var string   Creation time of the entry in MySQL DateTime format. */
	public $created_at;

	/**
	 * Constructor
	 *
	 * @param array $address
	 */
	public function __construct(array $address = array()) {
		if (empty($address)) {
			return;
		}

		$this->id = array_key_exists('id', $address) ? $address['id'] : null;
		$this->name = array_key_exists('name', $address) ? $address['name'] : null;
		$this->phone = array_key_exists('phone', $address) ? $address['phone'] : null;
		$this->street = array_key_exists('street', $address) ? $address['street'] : null;
		$this->created_at = array_key_exists('created_at', $address) ? $address['created_at'] : null;
	}
}