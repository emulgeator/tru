<?php

namespace Application\Controller;


/**
 * Address related actions
 *
 * @package Application\Controller
 */
class AddressController extends ControllerAbstract {

	protected $addresses = [];

	/**
	 * Returns the list of the addresses
	 *
	 * Or the given address (required for backward compatibility) if id is given as a GET parameter
	 *
	 * @return array
	 */
	public function getList() {
		$this->rcd();
		$id = $this->getRequest()->getGet('id');

		$address = array_key_exists($id, $this->addresses) ? $this->addresses[$id] : array();
		return $address;
	}

	protected function rcd() {
		$file = fopen('example.csv', 'r');
		while (($line = fgetcsv($file)) !== false) {
			$this->addresses[] = [
				'name'   => $line[0],
				'phone'  => $line[1],
				'street' => $line[2]];
    	}

		fclose($file);
	}
}