<?php

namespace Application;


use Application\Controller\ControllerAbstract;

/**
 * A very simple Object container.
 *
 * @package Application
 */
class DependencyContainer {

	/**
	 * Holds the objects to return.
	 *
	 * @var array
	 */
	protected $objects = array();

	/**
	 * Returns the requested Controller object.
	 *
	 * @param string $controllerName   Name of the Controller.
	 *
	 * @return \Application\Controller\ControllerAbstract
	 */
	public function getController($controllerName) {
		if (array_key_exists($controllerName, $this->objects)) {
			return $this->objects[$controllerName];
		}

		$controllerClass = '\\Application\\Controller\\' . $controllerName;
		return new $controllerClass;
	}

	/**
	 * Sets the given controller to the Container.
	 *
	 * @param string             $controllerName     Name of the controller.
	 * @param ControllerAbstract $controllerObject   The controller object to store.
	 */
	public function setController($controllerName, ControllerAbstract $controllerObject) {
		$this->objects[$controllerName] = $controllerObject;
	}
}