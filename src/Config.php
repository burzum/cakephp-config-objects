<?php
namespace Burzum\ObjectConfig;

use BadMethodCallException;
use Cake\Core\InstanceConfigTrait;
use Cake\Validation\Validator;
use InvalidArgumentException;
use RuntimeException;

/**
 * Concrete base class for configuration objects
 *
 * Config classes should extend this class and implement their own setters with
 * the required checks on the values.
 *
 * The configuration object can optionally be validated to ensure the integrity
 * of the configuration data.
 */
abstract class Config implements ConfigObjectInterface, ValidateableConfigInterface
{

	use InstanceConfigTrait;

	protected $_strictSetters = false;

	protected $_strictGetters = false;

	protected $_defaultValidatorClass = Validator::class;

	/**
	 * The errors if any
	 *
	 * @var array
	 */
	protected $_errors = [];

	/**
	 * The validator used by this form.
	 *
	 * @var \Cake\Validation\Validator;
	 */
	protected $_validator;

	/**
	 * Default config
	 *
	 * @var array
	 */
	protected $_defaultConfig = [];

	/**
	 * Constructor
	 *
	 * @param array $config Config data
	 */
	public function __construct(array $config = [], $validate = true)
	{
		$this->_createFromArray($config, $validate);
	}

	/**
	 * Creates a new object from a config array
	 *
	 * @param array
	 * @return $this
	 */
	public static function createFromArray(array $array, $validate = true)
	{
		return new static($array, $validate);
	}

	protected function _createFromArray(array $config, $validate = true)
	{
		if (empty($config)) {
			$this->setConfig($this->_defaultConfig);

			return;
		}

		foreach ($config as $key => $value) {
			$method = 'set' . $key;
			if ($this->_strictSetters) {
				$this->{$method}($value);
				continue;
			}

			if (method_exists($this, $method)) {
				$this->{$method}($value);
			}

			$this->setConfig($key, $value);
		}

		if ($validate === true) {
			$this->validate();
		}
	}

	/**
	 * Gets the current validator instance
	 *
	 * @return \Cake\Validation\Validator;
	 */
	public function getValidator()
	{
		if (empty($this->_validator)) {
			$this->_validator = new $this->_defaultValidatorClass();
		}

		return $this->_buildValidator($this->_validator);
	}

	/**
	 * Sets the validator
	 *
	 * @return $this
	 */
	public function setValidator(Validator $validator)
	{
		$this->_validator = $validator;

		return $this;
	}

	/**
	 * Validates the configuration integrity
	 */
	public function validate()
	{
		$validator = $this->getValidator();
		$this->_errors = $validator->errors($this->_config);

		if (count($this->_errors) > 0) {
			throw new RuntimeException(__CLASS__ . ' has invalid configuration options');
		}
	}

	/**
	 * A hook method intended to be implemented by subclasses.
	 *
	 * You can use this method to define the validator using
	 * the methods on Cake\Validation\Validator or loads a pre-defined
	 * validator from a concrete class.
	 *
	 * @param \Cake\Validation\Validator $validator The validator to customize.
	 * @return \Cake\Validation\Validator The validator to use.
	 */
	protected function _buildValidator(Validator $validator)
	{
		return $validator;
	}

	/**
	 * Get the errors
	 *
	 * @return array Last set validation errors.
	 */
	public function getErrors()
	{
		return $this->_errors;
	}

	/**
	 * Gets a config option
	 *
	 * @return get
	 */
	public function get($key)
	{
		if ($this->_strictGetters) {
			throw new BadMethodCallException();
		}

		if (!isset($this->_config[$key])) {
			throw new InvalidArgumentException(sprintf(
				'Config option `%s` does not exist',
				$key
			));
		}

		return $this->getConfig($key);
	}

	/**
	 * Sets an option
	 *
	 * @param string $key Key
	 * @param mixed $value Value
	 * @return void
	 */
	public function set($key, $value)
	{
		if ($this->_strictSetters) {
			throw new BadMethodCallException();
		}

		$this->setConfig($key, $value);
	}

	/**
	 * Turns the config object into an array
	 *
	 * @return array
	 */
	public function toArray()
	{
		if ($this->_strictGetters) {
			throw new BadMethodCallException();
		}

		return $this->_config;
	}

	/**
	 * {@inheritdoc}
	 */
	public function __call($name, $arguments)
	{
		if (substr($name, 0, 3) === 'get') {
			return $this->get(lcfirst(substr($name, 3)));
		}

		if (substr($name, 0, 3) === 'set' && isset($arguments[0])) {
			return $this->set(lcfirst(substr($name, 3)), $arguments[0]);
		}
	}

	/**
	 * Debug information about internal states of the object
	 *
	 * @return array
	 */
	public function __debugInfo()
	{
		return [
			'[_config]' => $this->_config,
			'[_defaultConfig]' => $this->_defaultConfig,
			'[_errors]' => $this->_errors
		];
	}
}
