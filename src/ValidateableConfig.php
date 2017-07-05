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
}
