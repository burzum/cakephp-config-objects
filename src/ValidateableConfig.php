<?php
namespace Burzum\ObjectConfig;

/**
 * Concrete base class for configuration objects
 *
 * Config classes should extend this class and implement their own setters with
 * the required checks on the values.
 *
 * The configuration object can optionally be validated to ensure the integrity
 * of the configuration data.
 */
class ValidateableConfig extends Config implements ConfigObjectInterface, ValidateableConfigInterface
{
	use ValidatorTrait;

	/**
	 * Debug information about internal states of the object
	 *
	 * @return array
	 */
	public function __debugInfo()
	{
		$info = parent::__debugInfo();

		return array_merge($info, [
			'[_defaultValidatorClass]' => $this->_errors,
			'[_validator]' => $this->_errors,
			'[_errors]' => $this->_errors,
		]);
	}
}
