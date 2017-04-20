<?php
namespace Burzum\ObjectConfig\TestCase;

use Burzum\ObjectConfig\Config;
use Cake\TestSuite\TestCase;

class TestConfig extends Config
{

	protected $_defaultConfig = [
		'test' => 'test1'
	];

	public function setMyValue($value)
	{
		$this->_config['test'] = $value;
	}

	public function getMyValue()
	{
		$this->_get('test');
	}
}

class ConfigTest extends TestCase
{

	public function testConstructor()
	{
		$config = new TestConfig([
			'foo' => 1,
			'bar' => 'two'
		]);

		$this->assertEquals(1, $config->get('foo'));
		$this->assertEquals('two', $config->get('bar'));
	}

	public function testGettersAndSetters() {
		$config = new TestConfig();
		$this->assertEquals('test1', $config->get('test'));
		$this->assertEquals('test1', $config->getMyValue());

		$this->assertEquals('test1', $config->setMyValue('test', 'test2'));
		$this->assertEquals('test2', $config->getMyValue());
	}
}
