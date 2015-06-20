<?php
/**
 * Bootstraps (initializes) the application.
 */

/** The repo root directory */
define('ROOT_DIR', realpath(__DIR__ . '/../'));

require_once getPath('Application/Autoloader.php');


$autoloader = new \Application\Autoloader(ROOT_DIR);
$autoloader->register();

unset($autoloader);

function getPath($relativeFilePath) {
	return ROOT_DIR . '/' . ltrim($relativeFilePath, DIRECTORY_SEPARATOR);
}