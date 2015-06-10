<?php

$path = $_SERVER['REQUEST_URI'];

if (strpos($path, '/address') === 0) {
	$controller = new \Controller();
	$return = $controller->ex();
	echo $return;
}

class Controller {

	protected $addresses = [];

	public function ex() {
		$this->rcd();

		$id = isset($_GET['id']) ? (int)$_GET['id'] : null;

		$address = array_key_exists($id, $this->addresses) ? $this->addresses[$id] : array();
		return json_encode($address);
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