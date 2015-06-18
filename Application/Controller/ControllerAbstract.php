<?php

namespace Application\Controller;


use Application\Application;

/**
 * Common ancestor of the Controller classes.
 *
 * @package Application\Controller
 */
abstract class ControllerAbstract {

	/**
	 * Returns the request.
	 *
	 * @return \Application\RestRequest
	 */
	protected function getRequest() {
		return Application::getInstance()->getRequest();
	}
}