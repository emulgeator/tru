<?php

use Application\Application;


/**
 * Class ApplicationTest
 */
class ApplicationTest extends PHPUnit_Framework_TestCase {

	/**
	 * @var \Application\Application
	 */
	protected $application;

	/**
	 * @var \Mockery\MockInterface
	 */
	protected $request;

	/**
	 * @var \Mockery\MockInterface
	 */
	protected $response;

	/**
	 * @var \Mockery\MockInterface
	 */
	protected $router;

	/**
	 * Sets up the environment for the test
	 */
	protected function setUp() {
		parent::setUp();

		$this->request = Mockery::mock('\Application\RestRequest');
		$this->response = Mockery::mock('\Application\RestResponse');
		$this->router = Mockery::mock('\Application\RestRouter');

		$this->application = Application::getInstance();
	}

	/**
	 * @test
	 */
	public function whenRunningNonExistentPage_shouldSendOut404() {
		$this->expectGetRouteAndThrowRouterException();
		$this->expectResponseStatusCode(404);

		$this->application->run();
	}

	/**
	 * @test
	 */
	public function whenRunningAndUnhandledExceptionThrown_shouldSendOut500() {
		$this->expectGetRouteAndThrowException();
		$this->expectResponseStatusCode(500);

		$this->application->run();
	}

	/**
	 * @test
	 */
	public function whenRunning_shouldRunTheActionAndSendItsResult() {
		$expectedResult = array('test');
		$controllerName = 'TestController';
		$actionName = 'get';

		$this->expectGetRoute($controllerName, $actionName);
		$this->expectRunGetAction($controllerName, $actionName, $expectedResult);
		$this->expectSetContentToResponse($expectedResult);
		$this->expectSendResponse();

		$this->application->run();
	}


	protected function expectRunGetAction($controllerName, $actionName,  $expectedResult) {
		$controllerMock = Mockery::mock('\Application\Controller\ControllerAbstract')
			->shouldReceive($actionName)
				->once()
				->andReturn($expectedResult)
				->getMock();

		$this->application->getDependencyContainer()->setController($controllerName, $controllerMock);
	}


	protected function expectSetContentToResponse($content) {
		$this->response
			->shouldReceive('setContent')
				->once()
				->with($content);

		$this->application->setResponse($this->response);
	}

	protected function expectSendResponse() {
		$this->response
			->shouldReceive('send')
				->once();

		$this->application->setResponse($this->response);
	}


	protected function expectGetRoute($expectedController, $expectedAction) {
		$this->router
			->shouldReceive('getRoute')
				->once()
				->with(
					Mockery::on(function (&$controller) use ($expectedController) {
						$controller = $expectedController;
						return true;
					}),
					Mockery::on(function (&$action) use ($expectedAction) {
						$action = $expectedAction;
						return true;
					})
				);

		$this->application->setRouter($this->router);
	}


	protected function expectGetRouteAndThrowRouterException() {
		$this->router
			->shouldReceive('getRoute')
			->andThrow('\Application\Exception\RouterException');

		$this->application->setRouter($this->router);
	}


	protected function expectGetRouteAndThrowException() {
		$this->router
			->shouldReceive('getRoute')
			->andThrow('\Exception');

		$this->application->setRouter($this->router);
	}


	protected function expectResponseStatusCode($statusCode) {
		$this->response
			->shouldReceive('setStatusCode')
				->once()
				->with($statusCode)
				->getMock()
			->shouldReceive('send')
				->once();

		$this->application->setResponse($this->response);
	}

}
