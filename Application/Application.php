<?php

namespace Application;


use Application\Exception\HttpException;
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
	 * The response instance for the application.
	 *
	 * @var RestResponse
	 */
	protected $response;

	/**
	 * The dependency container.
	 *
	 * @var DependencyContainer
	 */
	protected $dependencyContainer;

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
		$this->dependencyContainer = new DependencyContainer();
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
	 * @param RestRequest $request
	 *
	 * @return Application
	 */
	public function setRequest(RestRequest $request) {
		$this->request = $request;
		return $this;
	}

	/**
	 * @param RestRouter $router
	 *
	 * @return Application
	 */
	public function setRouter(RestRouter $router) {
		$this->router = $router;
		return $this;
	}

	/**
	 * @param RestRouter $router
	 *
	 * @return Application
	 */
	public function setResponse(RestResponse $response) {
		$this->response = $response;
		return $this;
	}

	/**
	 * Returns the Dependency Container.
	 *
	 * @return DependencyContainer
	 */
	public function getDependencyContainer() {
		return $this->dependencyContainer;
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
	 * Runs the request on the application
	 *
	 * @return void
	 */
	public function run() {
		try {
			$controller = null;
			$action = null;

			$this->router->getRoute($controller, $action);

			$this->dispatchedController = $controller;
			$this->dispatchedAction = $action;

			$result = $this->dependencyContainer->getController($controller)->$action();

			$this->response->setContent($result);
		}
		catch (RouterException $exception) {
			$this->response->setStatusCode('404');
		}
		catch (HttpException $exception) {
			$this->response->setStatusCode($exception->getCode());
		}
		catch (\Exception $exception) {
			$this->response->setStatusCode('500');
			// TODO: Handle exceptions
		}

		$this->response->send();
	}
}