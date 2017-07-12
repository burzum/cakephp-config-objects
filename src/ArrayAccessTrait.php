<?php
namespace Burzum\ObjectConfig;

use InvalidArgumentException;

trait ArrayAccessTrait
{

	public function offsetSet($offset, $value)
	{
		$this->set($offset, $value);
	}

	public function offsetExists($offset)
	{
		try {
			$result = $this->get($offset);

			return $result !== null;
		} catch (InvalidArgumentException $e) {
			return false;
		}
	}

	public function offsetUnset($offset)
	{
		$this->set($offset, null);
	}

	public function offsetGet($offset)
	{
		try {
			return $this->get($offset);
		} catch (InvalidArgumentException $e) {
			return null;
		}
	}
}