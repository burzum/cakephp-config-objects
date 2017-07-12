<?php
namespace Burzum\ObjectConfig;

use Cake\Validation\Validator;
use RuntimeException;

trait ValidatorTrait {

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
	 * Gets the current validator instance
	 *
	 * @return \Cake\Validation\Validator;
	 */
	public function getValidator()
	{
		if (empty($this->_validator)) {
			$class = $this->_defaultValidatorClass;
			$this->_validator = new $class();
		}

		return $this->_validator;
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
}
