<?php

namespace Application;


use Application\Controller\IController;;
use Application\Dao\IDao;
use Application\EntityHandler\IEntityHandler;

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
	 * @return IController
	 */
	public function getController($controllerName) {
		return $this->getObject($controllerName, 'Controller');
	}

	/**
	 * Sets the given controller to the Container.
	 *
	 * @param string      $controllerName     Name of the controller.
	 * @param IController $controllerObject   The controller object to store.
	 */
	public function setController($controllerName, IController $controllerObject) {
		$this->objects[$controllerName] = $controllerObject;
	}

	/**
	 * Returns the requested Entity Handler.
	 *
	 * @param string $handlerName   Name of the Entity Handler.
	 *
	 * @return IEntityHandler
	 */
	public function getEntityHandler($handlerName) {
		return $this->getObject($handlerName, 'EntityHandler');
	}

	/**
	 * Sets the given Entity Handler to the Container.
	 *
	 * @param string         $handlerName     Name of the EntityHandler.
	 * @param IEntityHandler $entityHandler   The Entity Handler object to store.
	 */
	public function setEntityHandler($handlerName, IEntityHandler $entityHandler) {
		$this->objects[$handlerName] = $entityHandler;
	}

	/**
	 * Returns the requested DAO object.
	 *
	 * @param string $daoName   Name of the DAO.
	 *
	 * @return IDao
	 */
	public function getDao($daoName) {
		return $this->getObject($daoName, 'Dao');
	}

	/**
	 * Sets the given DAO to the Container.
	 *
	 * @param string $daoName   Name of the DAO.
	 * @param IDao   $dao       The DAO object to store.
	 */
	public function setDao($daoName, IDao $dao) {
		$this->objects[$daoName] = $dao;
	}

	/**
	 * Returns the requested object.
	 *
	 * @param string $className                The name of the class (without the namespace)
	 * @param string $namespaceInApplication   The namespace of the class in the Application.
	 *
	 * @return mixed
	 */
	protected function getObject($className, $namespaceInApplication) {
		if (array_key_exists($className, $this->objects)) {
			return $this->objects[$className];
		}

		$fullClassName = '\\Application\\' . $namespaceInApplication . '\\' . $className;
		return new $fullClassName;
	}
}