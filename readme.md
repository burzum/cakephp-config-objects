# Object Config for CakePHP

**This plugin is work in progress!**

## Objects!? Why not arrays?

You should use objects instead of arrays because:

* An array can't be type checked if it really is the intended config
* It is easy to have typos in config arrays
* The configuration is separated from the class unlike when using `InstanceConfigTrait`
* Optional bonus: An array doesn't bring easy validation of config data with it

So instead of doing something like 

```php
class Foo(array $config);
```

do this

```php
class Foo(FooConfig $config);
```

## How to use it

Create your configuration object:

```php
use Burzum\ObjectConfig\Config;

class FooConfig extends Config {

	protected $_defaultConfig = [
		// Set your default values here
	];

	/* Your setter / getter methods go here */
}
```

Then just use it:

```php
$config = new FooConfig();
$config->setBar('some-value);

class Foo {

	protected $config;

	public function __construct(FooConfig $config)
	{
		$this->config = $config;
	}
}

$foo = new Foo($config);
````

For a soft migration path you can still do this:

```php
class Foo {

	protected $config;

	public function __construct(array $config = [])
	{
		$this->config = FooConfig::createFromArray($config);
	}
}
```


## License

Copyright 2013 - 2017 Florian Krämer

Licensed under the [MIT](http://www.opensource.org/licenses/mit-license.php) License. Redistributions of the source code included in this repository must retain the copyright notice found in each file.

