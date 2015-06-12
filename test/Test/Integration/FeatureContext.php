<?php

namespace Test\Integration;

require_once realpath(__DIR__ . '/../../../config/bootstrap.php');
require_once getPath('config/config.php');


use Application\Config;
use Behat\Behat\Context\Context;
use Behat\Behat\Context\SnippetAcceptingContext;
use Behat\Gherkin\Node\PyStringNode;

/**
 * Defines application features from the specific context.
 */
class FeatureContext implements Context, SnippetAcceptingContext
{

	/**
	 * @var string
	 */
	protected $responseBody;

	/**
	 * @var string
	 */
	protected $responseHeaders;

    /**
     * Initializes context.
     *
     * Every scenario gets its own context instance.
     * You can also pass arbitrary arguments to the
     * context constructor through behat.yml.
     */
    public function __construct() {
    }

	/**
	 * @When I call the get address with the id :addressId
	 */
	public function iCallTheGetAddressWithTheId($addressId) {
		$url = Config::getInstance()->get('application.url') . 'address?' . http_build_query(array('id' => $addressId));

		$curl = curl_init($url);
		$options = array(
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_HEADER         => true,
			CURLOPT_HTTPGET        => true,
		);
		curl_setopt_array($curl, $options);
		$result = curl_exec($curl);

		if (false === $result) {
			$this->error = curl_error($curl);

			throw new \Exception('Curl Error:' . curl_error($curl));
		}

		$info = curl_getinfo($curl);
		curl_close($curl);

		$this->responseBody = (string)substr($result, $info['header_size']);
		$this->responseHeaders = (string)substr($result, 0, $info['header_size']);
	}

	/**
	 * @Then the result of the call should be
	 */
	public function theResultOfTheCallShouldBe(PyStringNode $string) {
		$expectedResult = json_decode($string, true);
		$result = json_decode($this->responseBody, true);

		\PHPUnit_Framework_Assert::assertEquals($expectedResult, $result);
	}
}
