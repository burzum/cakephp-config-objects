<?php
namespace Burzum\ObjectConfig;

interface ConfigObjectInterface
{

	public function get($key);

	public function set($key, $value);
}
