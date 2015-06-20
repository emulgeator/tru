<?php

use Application\RestRequest;

/**
 * Class RestRequestTest
 */
class RestRequestTest extends PHPUnit_Framework_TestCase {

	/**
	 * @var string
	 */
	protected $requestUri = 'test/';


	protected function setUp() {
		parent::setUp();

		$_SERVER['REQUEST_URI'] = $this->requestUri;
		$_SERVER['REQUEST_METHOD'] = RestRequest::METHOD_HTTP_GET;
	}

	/**
	 * @test
	 */
	public function whenGettingExistentGetParameter_shouldReturnItsValue() {
		$paramName = 'test';
		$expectedResult = 1;
		$_GET[$paramName] = $expectedResult;

		$result = (new RestRequest())->getGet($paramName);

		$this->assertEquals($expectedResult, $result);
	}

	/**
	 * @test
	 */
	public function whenGettingNonExistentGetParameter_shouldReturnDefault() {
		$paramName = 'test';
		$expectedResult = 2;

		$result = (new RestRequest())->getGet($paramName, $expectedResult);

		$this->assertEquals($expectedResult, $result);
	}

	/**
	 * @test
	 */
	public function whenCheckingExistentGetParameter_shouldReturnTrue() {
		$paramName = 'test';
		$_GET[$paramName] = 1;

		$result = (new RestRequest())->hasGet($paramName);

		$this->assertTrue($result);
	}

	/**
	 * @test
	 */
	public function whenCheckingNonExistentGetParameter_shouldReturnFalse() {
		$paramName = 'test';

		$result = (new RestRequest())->hasGet($paramName);

		$this->assertFalse($result);
	}

	/**
	 * @test
	 */
	public function whenGettingExistentPostParameter_shouldReturnItsValue() {
		$paramName = 'test';
		$expectedResult = 1;
		$_POST[$paramName] = $expectedResult;
		$_SERVER['REQUEST_METHOD'] = RestRequest::METHOD_HTTP_POST;

		$result = (new RestRequest())->getPost($paramName);

		$this->assertEquals($expectedResult, $result);
	}

	/**
	 * @test
	 */
	public function whenGettingNonExistentPostParameter_shouldReturnDefault() {
		$paramName = 'test';
		$expectedResult = 2;
		$_SERVER['REQUEST_METHOD'] = RestRequest::METHOD_HTTP_POST;

		$result = (new RestRequest())->getPost($paramName, $expectedResult);

		$this->assertEquals($expectedResult, $result);
	}


	/**
	 * @test
	 */
	public function whenCheckingExistentPostParameter_shouldReturnTrue() {
		$paramName = 'test';
		$_POST[$paramName] = 1;
		$_SERVER['REQUEST_METHOD'] = RestRequest::METHOD_HTTP_POST;

		$result = (new RestRequest())->hasPost($paramName);

		$this->assertTrue($result);
	}

	/**
	 * @test
	 */
	public function whenCheckingNonExistentPostParameter_shouldReturnFalse() {
		$paramName = 'test';
		$_SERVER['REQUEST_METHOD'] = RestRequest::METHOD_HTTP_POST;

		$result = (new RestRequest())->hasPost($paramName);

		$this->assertFalse($result);
	}

	/**
	 * @test
	 */
	public function whenSettingAndGettingUriParameter_shouldReturnSetValue() {
		$expectedResult = 'test';
		$request = new RestRequest();
		$request->setRouteParam($expectedResult);

		$result = $request->getRouteParam();

		$this->assertEquals($expectedResult, $result);
	}

	/**
	 * @test
	 */
	public function whenSettingAndCheckingUriParameter_shouldReturnTrue() {
		$request = new RestRequest();
		$request->setRouteParam('test');

		$result = $request->hasRouteParam();

		$this->assertTrue($result);
	}

	/**
	 * @test
	 */
	public function whenCheckingNonExistentUriParameter_shouldReturnFalse() {
		$request = new RestRequest();

		$result = $request->hasRouteParam();

		$this->assertFalse($result);
	}

	/**
	 * @test
	 */
	public function whenGettingExistentServerParameter_shouldReturnItsValue() {
		$paramName = 'test';
		$expectedResult = 1;
		$_SERVER[$paramName] = $expectedResult;

		$result = (new RestRequest())->getServer($paramName);

		$this->assertEquals($expectedResult, $result);
	}

	/**
	 * @test
	 */
	public function whenGettingNonExistentServerParameter_shouldReturnDefault() {
		$paramName = 'test';
		$expectedResult = 2;

		$result = (new RestRequest())->getServer($paramName, $expectedResult);

		$this->assertEquals($expectedResult, $result);
	}

	/**
	 * @test
	 */
	public function whenGettingTheUri_theCurrentUriShouldBeReturned() {
		$this->assertEquals($this->requestUri, (new RestRequest())->getCurrentUri());
	}

	/**
	 * @test
	 */
	public function whenGettingTheMethod_shouldReturnValueFromServer() {
		$requestMethod = RestRequest::METHOD_HTTP_GET;
		$_SERVER['REQUEST_METHOD'] = $requestMethod;

		$this->assertEquals($requestMethod, (new RestRequest())->getMethod());
	}

}