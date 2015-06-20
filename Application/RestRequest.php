<?php

namespace Application;


/**
 * Class which represents the current HTTP request.
 *
 * @package Application
 */
class RestRequest {

	/** GET HTTP method. */
	const METHOD_HTTP_GET = 'GET';
	/** POST HTTP method. */
	const METHOD_HTTP_POST = 'POST';
	/** PUT HTTP method. */
	const METHOD_HTTP_PUT = 'PUT';
	/** DELETE HTTP method. */
	const METHOD_HTTP_DELETE = 'DELETE';

	/**
	 * The GET parameters received with the request.
	 *
	 * @var array
	 */
	protected $getParams;

	/**
	 * The POST parameters received with the request.
	 *
	 * @var array
	 */
	protected $postParams;

	/**
	 * The route param
	 *
	 * @var mixed
	 */
	protected $routeParam;

	/**
	 * The server array.
	 *
	 * @var array
	 */
	protected $server;

	/**
	 * The target URI
	 *
	 * @var string
	 */
	protected $currentUri;


	/**
	 * Constructor.
	 */
	public function __construct() {
		$this->server = $_SERVER;
		$this->getParams = $_GET;

		if ($this->getMethod() == self::METHOD_HTTP_PUT) {
			parse_str(file_get_contents('php://input'), $this->postParams);
		}
		else {
			$this->postParams = $_POST;
		}

		list($this->currentUri) = explode('?', $this->server['REQUEST_URI'], 2);
	}

	/**
	 * Returns the GET parameter specified, or the default value, if it's not set.
	 *
	 * @param string $name      The name of the parameter.
	 * @param mixed  $default   The default value, if the parameter is not set.
	 *
	 * @return mixed
	 */
	public function getGet($name, $default = null) {
		if (isset($this->getParams[$name])) {
			return $this->getParams[$name];
		}
		return $default;
	}

	/**
	 * Returns TRUE if there is a GET parameter set in the request with the specified name.
	 *
	 * @param string $name   The name of the get param.
	 *
	 * @return bool
	 */
	public function hasGet($name) {
		return array_key_exists($name, $this->getParams);
	}

	/**
	 * Returns the POST parameter specified, or the default value, if it's not set.
	 *
	 * @param string $name      The name of the parameter.
	 * @param mixed  $default   The default value, if the parameter is not set.
	 *
	 * @return mixed
	 */
	public function getPost($name, $default = null) {
		if (isset($this->postParams[$name])) {
			return $this->postParams[$name];
		}
		return $default;
	}

	/**
	 * Returns TRUE if there is a POST parameter set in the request with the specified name.
	 *
	 * @param string $name   Name of the post param.
	 *
	 * @return bool
	 */
	public function hasPost($name) {
		return array_key_exists($name, $this->postParams);
	}

	/**
	 * Sets the route param
	 *
	 * @param mixed $value   Value of the param.
	 *
	 * @return void
	 */
	public function setRouteParam($value) {
		$this->routeParam = $value;
	}

	/**
	 * Returns the sent Route Param.
	 *
	 * @return mixed
	 */
	public function getRouteParam() {
		return $this->routeParam;
	}

	/**
	 * Returns TRUE if there is a Route param.
	 *
	 * @return bool
	 */
	public function hasRouteParam() {
		return !is_null($this->routeParam);
	}

	/**
	 * Returns a value from the PHP server array.
	 *
	 * @param string $name      The key of the value to return.
	 * @param mixed  $default   The default value, if the key is not set.
	 *
	 * @return mixed   The value, or the provided default, if the key is not found.
	 */
	public function getServer($name, $default = null) {
		if (isset($this->server[$name])) {
			return $this->server[$name];
		}
		return $default;
	}

	/**
	 * Returns the target of the request.  (eg the URI for HTTP requests)
	 *
	 * @return string   The target of the request.
	 */
	public function getCurrentUri() {
		return $this->currentUri;
	}

	/**
	 * Returns the method of the request
	 *
	 * @return string   {@uses self::METHOD_*}
	 */
	public function getMethod() {
		return $this->server['REQUEST_METHOD'];
	}
}