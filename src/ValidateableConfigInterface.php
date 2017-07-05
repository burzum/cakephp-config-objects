<?php
namespace Burzum\ObjectConfig;

use Cake\Validation\Validator;

interface ValidateableConfigInterface
{

	/**
	 * Gets the validator instance
	 *
	 * @return \Cake\Validation\Validator
	 */
	public function getValidator();

	/**
	 * Sets the validator instance
	 *
	 * @param \Cake\Validation\Validator
	 */
	public function setValidator(Validator $validator);

	/**
	 * Validates the configuration integrity
	 *
	 * @return bool
	 */
	public function validate();

	/**
	 * Get the errors
	 *
	 * @return array
	 */
	public function getErrors();
}
