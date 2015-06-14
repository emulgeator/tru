<?php

namespace Application;


use Application\Exception\RouterException;

/**
 * Application singleton class.
 *
 * @package Application
 */
class Application {

	/**
	 * Singleton instance
	 *
	 * @var Application
	 */
	protected static $instance;

	/**
	 * The router instance
	 *
	 * @var RestRouter
	 */
	protected $router;

	/**
	 * The request instance for the application.
	 *
	 * @var RestRequest
	 */
	protected $request;

	/**
	 * Stores the name of the currently dispatched Controller.
	 *
	 * @var string
	 */
	protected $dispatchedController;

	/**
	 * Stores the name of the currently dispatched Action.
	 *
	 * @var string
	 */
	protected $dispatchedAction;

	/**
	 * Singleton constructor
	 */
	protected function __construct() {
		$this->request = new RestRequest();
		$this->router = new RestRouter($this->request);
	}

	/**
	 * Singleton __clone() method
	 */
	protected function __clone() {}

	/**
	 * Singleton getter
	 *
	 * @return Application
	 */
	public static function getInstance() {
		if (is_null(static::$instance)) {
			static::$instance = new static();
		}
		return static::$instance;
	}

	/**
	 * Returns the router used by the application.
	 *
	 * @return RestRouter
	 */
	public function getRouter() {
		return $this->router;
	}

	/**
	 * Returns the request object used by the application.
	 *
	 * @return RestRequest
	 */
	public function getRequest() {
		return $this->request;
	}

	/**
	 * Returns the name of the currently dispatched Controller and Action.
	 *
	 * @param string $controllerName   The name of the Controller will be populated here (Outgoing parameter).
	 * @param string $actionName       The name of the Action will be populated here (Outgoing parameter).
	 *
	 * @return void
	 */
	public function getDispatchedAction(&$controllerName, &$actionName) {
		$controllerName = $this->dispatchedController;
		$actionName = $this->dispatchedAction;
	}

	/**
	 * Sets the name of the currently dispatched Controller and Action.
	 *
	 * @param string $controllerName   Name of the controller.
	 * @param string $actionName       Name of the action.
	 *
	 * @return void
	 */
	public function setDispatchedAction($controllerName, $actionName) {
		$this->dispatchedController = $controllerName;
		$this->dispatchedAction = $actionName;
	}

	/**
	 * Runs the request on the application
	 *
	 * @return void
	 */
	public function run() {
		try {
			$controllerName = null;
			$action = null;
			try {
				$this->router->getRoute($controllerName, $action);

				$this->dispatchedController = $controllerName;
				$this->dispatchedAction = $action;
			}
			catch (RouterException $exception) {
				// TODO: return 404
			}

			$controllerClass = '\\Application\\Controller\\' . $controllerName;
			(new $controllerClass)->$action();
		}
		catch (\Exception $exception) {
			// TODO: Handle exceptions
		}

	}
}