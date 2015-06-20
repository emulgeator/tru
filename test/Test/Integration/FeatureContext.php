<?php

namespace Test\Integration;

require_once realpath(__DIR__ . '/../../../config/bootstrap.php');
require_once getPath('config/config_test.php');

use Application\Entity\Address;
use Application\EntityHandler\AddressHandler;
use Application\Config;
use Application\RestRequest;
use Behat\Gherkin\Node\TableNode;
use Behat\Behat\Context\Context;
use Behat\Behat\Context\SnippetAcceptingContext;
use Behat\Gherkin\Node\PyStringNode;
use PHPUnit_Framework_Assert;


/**
 * Defines application features from the specific context.
 */
class FeatureContext implements Context, SnippetAcceptingContext {

	/**
	 * @var string
	 */
	protected $responseBody;

	/**
	 * @var string
	 */
	protected $responseHeaders;

	/**
	 * @var AddressHandler
	 */
	protected $addressHandler;

	/**
	 * Stores the addresses
	 *
	 * @var Address[]
	 */
	protected $addresses = array();

    /**
     * Initializes context.
     *
     * Every scenario gets its own context instance.
     * You can also pass arbitrary arguments to the
     * context constructor through behat.yml.
     */
    public function __construct() {
		$this->addressHandler = new AddressHandler();
    }

	/**
	 * Runs before every scenario and initializes the environment.
	 *
	 * @BeforeScenario
	 */
	public function initScenario() {
		DbInitializer::initDatabase();
	}

	/**
	 * @Given The following addresses exist:
	 */
	public function theFollowingAddressesExist(TableNode $table) {
		foreach ($table->getHash() as $address) {
			$addressId = $this->addressHandler->create($address['name'], $address['phone'], $address['street']);

			$this->addresses[$addressId] = $this->addressHandler->getById($addressId);
		}
	}

	/**
	 * @When I call the list addresses with the id :addressId
	 */
	public function iCallTheListAddressesWithTheId($addressId) {
		$this->callUri('address', RestRequest::METHOD_HTTP_GET, array('id' => $addressId));
	}

	/**
	 * @When I call the list addresses
	 */
	public function iCallTheListAddresses() {
		$this->callUri('address');
	}

	/**
	 * Calls the given URI with the given parameters.
	 *
	 * @param string $uri           URI to call.
	 * @param array  $getParams     Parameters to send with
	 * @param string $method        HTTP method to use
	 * @param array  $extraParams   Parameters to send with the given method.
	 *
	 * @throws \Application\Exception\ConfigException
	 * @throws \Exception
	 */
	protected function callUri($uri, $method = RestRequest::METHOD_HTTP_GET, $params = array()) {
		$getParams = array('isTestingMode' => 1);

		$options = array(
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_HEADER         => true,
		);

		switch ($method) {
			case RestRequest::METHOD_HTTP_GET:
				$options[CURLOPT_HTTPGET] = true;
				$getParams = array_merge($params, $getParams);
				break;

			case RestRequest::METHOD_HTTP_POST:
				$options[CURLOPT_POST] = true;
				$formattedParameters = array();
				$this->formatDataForPost($params, $formattedParameters);
				$options[CURLOPT_POSTFIELDS] = $formattedParameters;
				break;

			case RestRequest::METHOD_HTTP_PUT:
				$options[CURLOPT_CUSTOMREQUEST] = RestRequest::METHOD_HTTP_PUT;
				$formattedParameters = array();
				$this->formatDataForPost($params, $formattedParameters);
				$options[CURLOPT_POSTFIELDS] = $formattedParameters;
				break;

			case RestRequest::METHOD_HTTP_DELETE:
				$options[CURLOPT_CUSTOMREQUEST] = RestRequest::METHOD_HTTP_DELETE;
				$formattedParameters = array();
				$this->formatDataForPost($params, $formattedParameters);
				$options[CURLOPT_POSTFIELDS] = $formattedParameters;
				break;

			default:
				throw new \Exception('Invalid method given: ' . $method);
				break;

		}

		$url = Config::getInstance()->get('application.url') . $uri .'?' . http_build_query($getParams);
		$curl = curl_init($url);
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
	 * Formats the given data for posting.
	 *
	 * @param array  $post              The post data.
	 * @param array  $output            The result will be populated here.
	 * @param string $paramNamePrefix   Prefix for the parameter name.
	 *
	 * @return void
	 */
	protected function formatDataForPost(array $post, array &$output, $paramNamePrefix = null) {
		foreach ($post as $key => $value) {
			$currentKey = !empty($paramNamePrefix)
				? $paramNamePrefix . '[' . $key . ']'
				: $key;

			if (is_array($value) || is_object($value)) {
				$this->formatDataForPost($value, $output, $currentKey);
			} else {
				$output[$currentKey] = $value;
			}
		}
	}

	/**
	 * @Then the result of the call should be
	 */
	public function theResultOfTheCallShouldBe(PyStringNode $string) {
		$expectedResult = json_decode($string, true);
		$result = json_decode($this->responseBody, true);

		PHPUnit_Framework_Assert::assertEquals($expectedResult, $result);
	}

	/**
	 * @Then the http status of the response should be :statusCode
	 */
	public function theHttpStatusOfTheResponseShouldBe($statusCode) {
		PHPUnit_Framework_Assert::assertContains(
			'HTTP/1.1 ' . $statusCode . ' ',
			$this->responseHeaders,
			'Expected status code not received'
		);
	}

	/**
	 * @Then the result should be the address with the name :name and without extra details
	 */
	public function theResultShouldBeTheAddressWithTheNameWithoutExtraDetails($name) {
		$expectedAddress = null;
		foreach ($this->addresses as $address) {
			if ($address->name == $name) {
				$expectedAddress = $address;
				break;
			}
		}

		if (empty($expectedAddress)) {
			throw new \Exception('Expected address with name "' . $name . '" does not exist!');
		}
		$expectedAddress->id = null;
		$expectedAddress->created_at = null;

		$address = new Address(json_decode($this->responseBody, true));
		PHPUnit_Framework_Assert::assertEquals($expectedAddress, $address);
	}

	/**
	 * @Then the result of the call should be all the addresses
	 */
	public function theResultOfTheCallShouldBeAllTheAddresses() {
		ksort($this->addresses);

		$addresses = array();
		foreach (json_decode($this->responseBody, true) as $address) {
			$addresses[] = new Address($address);
		}

		PHPUnit_Framework_Assert::assertEquals(array_values($this->addresses), $addresses);
	}
}
