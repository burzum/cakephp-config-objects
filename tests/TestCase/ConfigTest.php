<?php
namespace Burzum\ObjectConfig\TestCase;

use Burzum\ObjectConfig\ValidateableConfig;
use Cake\TestSuite\TestCase;

// @codingStandardsIgnoreStart
class TestConfig extends ValidateableConfig
{
	protected $_defaultConfig = [
		'test' => 'test1'
	];

	public function setMyValue($value)
	{
		$this->set('test', $value);
	}

	public function getMyValue()
	{
		return $this->get('test');
	}
}
// @codingStandardsIgnoreEnd

class ConfigTest extends TestCase
{

	/**
	 * testConstructor
	 *
	 * @return void
	 */
	public function testConstructor()
	{
		$config = new TestConfig([
			'foo' => 1,
			'bar' => 'two'
		]);

		$this->assertEquals(1, $config->get('foo'));
		$this->assertEquals('two', $config->get('bar'));
	}

	/**
	 * testGettersAndSetters
	 *
	 * @return void
	 */
	public function testGettersAndSetters()
	{
		$config = new TestConfig();

		$this->assertEquals('test1', $config->get('test'));
		$this->assertEquals('test1', $config->getMyValue());

		$config->setMyValue('changed');
		$this->assertEquals('changed', $config->getMyValue());

		$config->setOther('other');
		$this->assertEquals('other', $config->getOther());

		$expected = [
			'test' => 'changed',
			'other' => 'other'
		];
		$result = $config->toArray();
		$this->assertEquals($expected, $result);
	}

	/**
	 * testArrayAccess
	 */
	public function testArrayAccess()
	{
		$config = new TestConfig();

		$config['array'] = 'value';
		$this->assertEquals('value', $config['array']);
		unset($config['array']);
		$this->assertFalse(isset($config['array']));
	}
}
