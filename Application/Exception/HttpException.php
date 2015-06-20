<?php

namespace Application\Exception;


use Exception;

/**
 * Exception class which represents an HTTP status.
 *
 * @package Application\Exception
 */
class HttpException extends \Exception {

	/**
	 * Constructor.
	 *
	 * @param int $httpStatusCode   HTTP Status code.
	 */
	public function __construct($httpStatusCode) {
		parent::__construct('', $httpStatusCode);
	}
}