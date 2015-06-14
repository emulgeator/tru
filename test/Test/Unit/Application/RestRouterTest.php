<?php

use Application\RestRouter;
use Application\RestRequest;

/**
 * Class RestRouterTest
 */
class RestRouterTest extends PHPUnit_Framework_TestCase {

	/**
	 * @var RestRequest
	 */
	protected $restRequest;

	/**
	 * @test
	 */
	public function whenGettingRouteForEmptyRoute_shouldThrowException() {
		$this->setExpectedException('\Application\Exception\RouterException', 'Invalid URI');
		$this->getRouter('/')->getRoute();
	}

	/**
	 * @test
	 */
	public function whenGettingRouteForInvalidMethod_shouldThrowException() {
		$this->setExpectedException('\Application\Exception\RouterException', 'Invalid Method');
		$this->getRouter('/test', 'invalid')->getRoute();
	}

	public function methodWithRequiredParameterProvider() {
		return array(
			'Deleting an item'  => array(RestRequest::METHOD_HTTP_DELETE),
			'Modifying an item' => array(RestRequest::METHOD_HTTP_PUT),
		);
	}

	/**
	 * @test
	 *
	 * @dataProvider methodWithRequiredParameterProvider
	 */
	public function whenGettingRouteWithParameterNotGivenButRequired_shouldThrowException($requestMethod) {
		$this->setExpectedException('\Application\Exception\RouterException', 'Operation only permitted on a specified item');
		$this->getRouter('/test', $requestMethod)->getRoute();
	}


	public function validUriProvider() {
		$controllerClassName = 'TestController';

		return array(
			'get action'    => array(RestRequest::METHOD_HTTP_GET,    '/test/1', $controllerClassName, 'get',    1),
			'list action'   => array(RestRequest::METHOD_HTTP_GET,    '/test',   $controllerClassName, 'list',   null),
			'delete action' => array(RestRequest::METHOD_HTTP_DELETE, '/test/1', $controllerClassName, 'delete', 1),
			'put action'    => array(RestRequest::METHOD_HTTP_PUT,    '/test/1', $controllerClassName, 'update', 1),
			'post action'   => array(RestRequest::METHOD_HTTP_POST,   '/test',   $controllerClassName, 'create', null),
		);
	}

	/**
	 * @test
	 *
	 * @dataProvider validUriProvider
	 */
	public function whenGettingRouteForGetWithoutParameter_shouldReturnListAction($requestMethod, $requestUri,
		$expectedController, $expectedAction, $expectedRouteParameter
	) {
		$controller = $action = null;
		$this->getRouter($requestUri, $requestMethod)->getRoute($controller, $action);

		$this->assertEquals($expectedController, $controller);
		$this->assertEquals($expectedAction, $action);
		$this->assertEquals($expectedRouteParameter, $this->restRequest->getRouteParam());
	}

	/**
	 * @return \Application\RestRouter
	 */
	protected function getRouter($requestUri, $requestMethod = RestRequest::METHOD_HTTP_GET) {
		$_SERVER['REQUEST_URI'] = $requestUri;
		$_SERVER['REQUEST_METHOD'] = $requestMethod;
		$this->restRequest = new RestRequest();
		return new RestRouter($this->restRequest);
	}
}