<?php

namespace Application\Controller;


use Application\Application;

/**
 * Common ancestor of the Controller classes.
 *
 * @package Application\Controller
 */
abstract class ControllerAbstract implements IController {

	/**
	 * Returns the request.
	 *
	 * @return \Application\RestRequest
	 */
	protected function getRequest() {
		return Application::getInstance()->getRequest();
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