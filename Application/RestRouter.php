<?php

namespace Application;


use Application\Exception\RouterException;

/**
 * RestRouter class.
 *
 * Generates the controller and action name based on the received target.
 *
 * @package Application
 */
class RestRouter {

	/**
	 * The request instance
	 *
	 * @var RestRequest
	 */
	protected $request;

	/**
	 * Constructor
	 *
	 * @param RestRequest $request   The request instance
	 */
	public function __construct(RestRequest $request) {
		$this->request = $request;
	}

	/**
	 * Returns a controller and an action for the request's target.
	 *
	 * @param string $controller   $he controller class name. (Outgoing parameter)
	 * @param string $action       The action name in the controller class. (Outgoing parameter)
	 * @param string $uri          The uri to check. If not given, the current uri will be used.
	 *
	 * @return string   The controller and action separated by a '/' character.
	 *
	 * @throws RouterException   On errors. (Including if the route is not found)
	 */
	public function getRoute(&$controller = null, &$action = null, $uri = null) {
		$uri = empty($uri)
			? $this->request->getCurrentUri()
			: $uri;

		$target = explode('/', trim($uri, '/ '));
		$controller = array_shift($target);

		if (empty($controller)) {
			throw new RouterException('Invalid URI: ' . $uri);
		}
		else {
			$controller = $this->getControllerNameByUri($controller);
		}

		if (!empty($target)) {
			$this->request->setRouteParam($target[0]);
		}

		$action = $this->getAction();

		return $controller . '/' . $action;
	}

	/**
	 * Returns the Controller name based on the given URI part.
	 *
	 * @param string $uri   Relevant part of the URI.
	 *
	 * @return string
	 */
	protected function getControllerNameByUri($uri) {
		$parts = preg_split('/[-_ A-Z]/', preg_replace('/[^-_a-zA-Z0-9]/', '', $uri));
		foreach ($parts as $key => $value) {
			$parts[$key] = ucfirst($value);
		}
		return implode('', $parts) . 'Controller';
	}

	/**
	 * Checks if the current URI contains a parameter and throws an Exception if not.
	 *
	 * @throws \Application\Exception\RouterException
	 */
	protected function requireUriParam() {
		if (!$this->request->hasRouteParam()) {
			throw new RouterException('Operation only permitted on a specified item');
		}
	}

	/**
	 * Returns the action to call.
	 *
	 * @throws \Application\Exception\RouterException
	 *
	 * @return string
	 */
	protected function getAction() {
		$method = $this->request->getMethod();

		switch ($method) {
			case RestRequest::METHOD_HTTP_GET:
				if ($this->request->hasRouteParam()) {
					$action = 'get';
				}
				else {
					$action = 'list';
				}
				break;

			case RestRequest::METHOD_HTTP_DELETE:
				$this->requireUriParam();
				$action = 'delete';
				break;

			case RestRequest::METHOD_HTTP_POST:
				$action = 'create';
				break;

			case RestRequest::METHOD_HTTP_PUT:
				$this->requireUriParam();
				$action = 'update';
				break;

			default:
				throw new RouterException('Invalid Method: ' . $method);
				break;
		}

		return $action;
	}
}