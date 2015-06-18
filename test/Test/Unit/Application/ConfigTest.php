<?php

use Application\Config;

/**
 * Class RestRequestTest
 */
class RestRequestTest extends PHPUnit_Framework_TestCase {

	/**
	 * @var \Application\Config
	 */
	protected $config;

	public function __construct() {
		$this->config = Config::getInstance();
	}

	protected function setUp() {
		parent::setUp();
		$this->config->clear();
	}

	/**
	 * @test
	 */
	public function whenSettingAndGettingSingleKey_shouldReturnSetValue() {
		$key = 'key';
		$value = 'value';
		$this->config->set($key, $value);

		$result = $this->config->get($key);

		$this->assertEquals($value, $result);
	}

	/**
	 * @test
	 */
	public function whenSettingAndGettingMultipleKeys_shouldReturnSetValues() {
		$values = array(
			'test.a'     => 'a',
			'test.b'     => 'b',
			'irrelevant' => 'c'
		);
		$this->config->set($values);

		$result = $this->config->get('test.*');

		$this->assertEquals(
			array(
				'a' => 'a',
				'b' => 'b',
			),
			$result);
	}

	/**
	 * @test
	 */
	public function whenGettingWithEmptyKey_shouldThrowException() {
		$this->setExpectedException('\Application\Exception\ConfigException',
			'Getting a configuration with an empty name');
		$this->config->get('');
	}

	/**
	 * @test
	 */
	public function whenGettingNonexistentKeyWithoutDefault_shouldThrowException() {
		$this->expectConfigExceptionForNonExistentKey();
		$this->config->get('nonExistent');
	}

	/**
	 * @test
	 */
	public function whenGettingNonexistentKeyWithDefault_shouldReturnDefault() {
		$defaultValue = 'default';
		$result = $this->config->get('nonExistent', $defaultValue);

		$this->assertEquals($defaultValue, $result);
	}

	/**
	 * @test
	 */
	public function whenDeletingKey_shouldRemoveDeleteKey() {
		$key = 'test';
		$this->config->set($key, 'test');

		$this->config->delete($key);

		$this->expectConfigExceptionForNonExistentKey();
		$this->config->get($key);
	}

	/**
	 * @test
	 */
	public function whenClearing_shouldRemoveEverySetKey() {
		$this->config->set(array('test1' => 1, 'test2' => 2));

		$this->config->clear();

		$result = $this->config->toArray();

		$this->assertEquals(array(), $result);
	}

	protected function expectConfigExceptionForNonExistentKey() {
		$this->setExpectedException('\Application\Exception\ConfigException', 'Configuration option not found');
	}
}