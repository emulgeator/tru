<?php

namespace Application\Controller;


use Application\Application;
use Application\RestRequest;
use Application\RestResponse;
use Application\RestRouter;

/**
 * Common ancestor of the Controller classes.
 *
 * @package Application\Controller
 */
abstract class ControllerAbstract implements IController {

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
	 * Construct
	 */
	function __construct() {
		$application = Application::getInstance();

		$this->router = $application->getRouter();
		$this->request = $application->getRequest();
		$this->response = $application->getResponse();
	}

	/**
	 * Returns the requested Entity Handler.
	 *
	 * @return \Application\EntityHandler\AddressHandler
	 */
	protected function getAddressHandler() {
		return Application::getInstance()->getDependencyContainer()->getEntityHandler('AddressHandler');
	}
}