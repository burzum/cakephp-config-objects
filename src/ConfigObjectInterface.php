<?php
namespace Burzum\ObjectConfig;

interface ConfigObjectInterface
{

	/**
	 * Getter
	 *
	 * @param string $key Config key to get
	 * @return mixed
	 */
	public function get($key);

	/**
	 * Setter
	 *
	 * @param string $key Config key
	 * @param mixed $value Value to set
	 * @return void
	 */
	public function set($key, $value);
}
