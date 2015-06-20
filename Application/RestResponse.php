<?php


namespace Application;


use Application\Exception\ResponseException;

/**
 * Class which handles the Rest response.
 *
 * @package Application
 */
class RestResponse {

	/**
	 * Stores the headers to be sent out in the response.
	 *
	 * @var array
	 */
	protected $headers = array();

	/**
	 * Stores the status code
	 *
	 * @var int
	 */
	protected $statusCode = 200;

	/**
	 * Stores the status message
	 *
	 * @var string
	 */
	protected $statusMessage = 'OK';

	/**
	 * The response to render.
	 *
	 * @var mixed
	 */
	protected $content;

	/**
	 * Standard HTTP status codes
	 *
	 * @var array
	 */
	protected static $statusCodes = array(
		200 => 'OK',
		201 => 'Created',
		204 => 'No Content',
		404 => 'Not Found',
		500 => 'Internal Server Error',
	);

	/**
	 * Constructor
	 */
	public function __construct() {
		$this->startOutputBuffer();
		$this->addHeader('Content-type: application/json');
	}

	/**
	 * Sets the status code for the response
	 *
	 * @param int    $statusCode      The status code for the response.
	 * @param string $statusMessage   The message for the status code.
	 */
	public function setStatusCode($statusCode, $statusMessage = '') {
		if (!$statusMessage && array_key_exists($statusCode, self::$statusCodes)) {
			$statusMessage = self::$statusCodes[$statusCode];
		}

		$this->statusCode = $statusCode;
		$this->statusMessage = $statusMessage;
		$this->addHeader('HTTP/1.1 ' . (int)$this->statusCode . ' ' . $this->statusMessage);
	}

	/**
	 * Sets the content of the response.
	 *
	 * @param mixed $content
	 */
	public function setContent($content) {
		$this->content = $content;
	}

	/**
	 * Sets an HTTP header.
	 *
	 * @param string $name    Name of the header
	 * @param string $value   Value of the header
	 *
	 * @throws ResponseException   If invalid header given.
	 */
	public function addHeader($name, $value = null) {
		if (empty($name)) {
			throw new ResponseException('Header name is empty');
		}

		if (is_null($value)) {
			$this->headers[] = $name;
		}
		else {
			$this->headers[] = $name . ': ' . $value;
		}
	}

	/**
	 * Sends the response
	 *
	 * @throws \Application\Exception\ResponseException   If the response is already sent.
	 */
	public function send() {
		$this->sendHeaders();
		$this->sendContent();
		$this->flushOutputBuffer();
	}

	/**
	 * @return array
	 */
	public function getHeaders() {
		return $this->headers;
	}

	/**
	 * @return int
	 */
	public function getStatusCode() {
		return $this->statusCode;
	}

	/**
	 * Sends all headers (including cookie headers).
	 */
	protected function sendHeaders() {
		foreach ($this->headers as $header) {
			header($header);
		}
	}

	/**
	 * Sends the content of the response.
	 */
	protected function sendContent() {
		echo json_encode($this->content);
	}

	/**
	 * Starts the output buffer.
	 */
	protected function startOutputBuffer() {
		ob_start();
	}

	/**
	 * Flushes the content of the output buffer.
	 */
	protected function flushOutputBuffer() {
		ob_end_flush();
	}
}