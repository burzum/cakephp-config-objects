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
abstract class Config implements ConfigObjectInterface
{

    use InstanceConfigTrait;

    protected $_strictSetters = false;

    protected $_strictGetters = false;

    protected $_defaultValidatorClass = '\Cake\Validation\Validator';

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

    protected $_defaultConfig = [];

    /**
     * Constructor
     *
     * @param array $config Config data
     */
    public function __construct(array $config = [], $validate = true)
    {
        $this->_setFromArray($config, $validate);
    }

    protected function _setFromArray(array $config, $validate = true)
    {
        foreach ($config as $key => $value) {
            $method = 'set' . $key;
            if ($this->_strictSetters) {
                $this->{$method}($value);
                continue;
            }

            if (method_exists($this, $method)) {
                $this->{$method}($value);
            }

            $this->set($key, $value);
        }

        if ($validate === true) {
            $this->validate();
        }
    }

    public function getValidator()
    {
        if (empty($this->_validator)) {
            $this->_validator = new $this->_defaultValidatorClass();
        }

        return $this->_buildValidator($this->_validator);
    }

    public function setValidator(Validator $validator)
    {
        $this->_validator = $validator;
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
     * @return get
     */
    public function get($key)
    {
        if ($this->_strictGetters) {
            throw new BadMethodCallException();
        }

        $method = '_get' . $key;
        if (method_exists($this, $method)) {
            return $this->{$method}();
        }

        return $this->_get($key);
    }

    protected function _get($key)
    {
        if (!isset($this->_config[$key])) {
            throw new InvalidArgumentException(sprintf('Config option `%s` does not exist', $key));
        }
        return $this->_config[$key];
    }

    /**
     * @return void
     */
    public function set($key, $value)
    {
        if ($this->_strictSetters) {
            throw new BadMethodCallException();
        }

        $method = '_set' . $key;
        if (method_exists($this, $method)) {
            return $this->{$method}($key, $value);
        }

        $this->setConfig($key, $value);
    }

    public function toArray()
    {
        if ($this->_strictGetters) {
            throw new BadMethodCallException();
        }

        return $this->_config;
    }

    public function __debugInfo()
    {
        return [
            '[config]' => $this->_config,
            '[errors]' => $this->_errors
        ];
    }
}
