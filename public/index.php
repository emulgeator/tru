<?php

use Application\Application;
use Application\RestRequest;
use Application\RestResponse;
use Application\RestRouter;

require realpath(__DIR__ . '/../config/bootstrap.php');

if (!empty($_GET['isTestingMode'])) {
	require getPath('config/config_test.php');
}
else {
	require getPath('config/config.php');
}

$request = new RestRequest();
$response = new RestResponse();
$router = new RestRouter($request);

Application::getInstance()
	->setRequest($request)
	->setResponse($response)
	->setRouter($router)
	->run();