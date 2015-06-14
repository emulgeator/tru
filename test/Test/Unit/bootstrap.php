<?php
/**
 * Bootstraps (initializes) the Unit test.
 */

/** The repo root directory */
define('ROOT_DIR', realpath(__DIR__ . '/../../../'));

require_once ROOT_DIR . '/vendor/autoload.php';

$errorHandler = new \Application\ErrorHandler\ExceptionCreatorErrorHandler();
$errorHandler->register();
unset($errorHandler);
