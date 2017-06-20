<?php
namespace Burzum\ObjectConfig;

use Cake\Validation\Validator;

interface ValidateableConfigInterface
{

	public function getValidator();

	public function setValidator(Validator $validator);

	/**
	 * Validates the configuration integrity
	 */
	public function validate();

	public function getErrors();
}
