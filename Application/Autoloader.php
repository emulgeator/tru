<?php

namespace Application;


use Application\Exception\AutoloadException;

/**
 * Autoloader class.
 *
 * @package Application
 */
class Autoloader {

	/**
	 * @var string
	 */
	protected $includePath;

	/**
	 * Constructor
	 *
	 * @param string $includePath   The include path to use. Every generated path is relative to this.
	 */
	public function __construct($includePath) {
		$this->includePath = rtrim($includePath, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR;
	}

	/**
	 * Registers this class as an Autoloader.
	 */
	public function register() {
		spl_autoload_register(array($this, 'loadClass'));
	}

	/**
	 * Unregisters this class as an Autoloader.
	 */
	public function unregister() {
		spl_autoload_unregister(array($this, 'loadClass'));
	}

	/**
	 * Loads the given class
	 *
	 * @param string $className   Full name of the class.
	 */
	public function loadClass($className) {
		$className = ltrim($className, '\\');
		$fileName  = '';

		if ($lastNsPos = strrpos($className, '\\')) {
			$namespace = substr($className, 0, $lastNsPos);
			$className = substr($className, $lastNsPos + 1);
			$fileName  = str_replace('\\', DIRECTORY_SEPARATOR, $namespace) . DIRECTORY_SEPARATOR;
		}
		$fileName .= str_replace('_', DIRECTORY_SEPARATOR, $className) . '.php';
		$fileName = $this->includePath . $fileName;

		if (!file_exists($fileName)) {
			throw new AutoloadException('File does not exist: ' . $fileName);
		}

		require $fileName;
	}

}