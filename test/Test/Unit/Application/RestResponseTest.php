<?php

use Application\RestResponse;


/**
 * Class RestResponseTest
 */
class RestResponseTest extends PHPUnit_Framework_TestCase {

	/**
	 * @var RestResponse
	 */
	protected $response;


	protected function setUp() {
		parent::setUp();

		$this->response = new RestResponse();
	}

	/**
	 * @test
	 *
	 * @covers RestResponse::__construct()
	 */
	public function jsonContentTYpeHeaderShouldBeSet() {
		$this->assertHeaderExists('Content-type: application/json');
	}

	/**
	 * @test
	 */
	public function whenSettingStatusCode_shouldSetTheHttpStatusHeader() {
		$this->response->setStatusCode(404);

		$this->assertEquals(404, $this->response->getStatusCode());
		$this->assertHeaderExists('HTTP/1.1 404 Not Found');
	}

	/**
	 * @test
	 */
	public function whenAddingHeaders_theyShouldBeStored() {
		$this->response->addHeader('test', 'value');

		$this->assertHeaderExists('test: value');
	}


	protected function assertHeaderExists($expectedHeader) {
		$headers = $this->response->getHeaders();

		if (!in_array($expectedHeader, $headers)) {
			throw new PHPUnit_Framework_ExpectationFailedException('Expected header "'
				. $expectedHeader . '" does not exist in list :' . var_export($headers, true));
		}
	}
}