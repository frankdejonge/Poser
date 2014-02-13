# WARNING WARNING, DEPRECATED PACKAGE!

I've made a way better and optimizable version: [FuelPHP/Alias](http://github.com/fuelphp/alias). I strongly recommend using that package instead of this one.

# Poser

[![Build Status](https://secure.travis-ci.org/FrenkyNet/Poser.png)](http://travis-ci.org/FrenkyNet/Poser)

Poser appends itself to the autoloading stack and resolves class aliasses. This all so your classes can pretend to be someone else.

## Usage

```php
<?php

use Poser\Poser;

Poser::alias(array(
	// Rename a class
	'Namespaced\Alias' => 'Namespaced\Classname',
	
	// Rename namespace
	'Other\Namespaced\Classname' => 'Real\Namespaced\Classname'
	
	// Alias to global
	'Classname' => 'Namespaced\Classname',
));
```

## Wildcards

With a wildcard like;

```php
Poser\Poser::alias(array(
	'NotSo\Deep\*' => 'ACLass\InA\Deep\And\Hidden\Namespace\$1',
));
```
Poser wil act like:
```php
$obj = new NotSo\Deep\Model();
// ACLass\InA\Deep\And\Hidden\Namespace\Model;
```

You can also have multiple wildcards:

```php
Poser\Poser::alias(array(
	'*\Dude\*\Is\*\Man' => '$1\$2\$3',
));

$obj = new Hey\Dude\What\Is\Up\Man()
// Wil stranslate to Hey\WHat\Up
```

## Closure translations

You can also modify the translation in a closure.

```php
Poser\Poser::alias(array(
	'*\*\Strange' => function($one, $two)
	{
		return $one.'\\'.$two.'\\Weird';
	}
));

$instance = new That\Stuffs\Strange();
// Will be a new instance of That\Stuffs\Weird
```

## Registering the autoloader

```php
Poser\Poser::register();

// Unregister
Poser\Poser::unregister();
```

## Multiton usage

You can also register more Posers to the autoloader.

```php

$poser = Poser\Poser::instance('justin_bieber');

// Alias some classes
$poser->alias(array(
	'Justin\Bieber' => 'InvalidArgumentException',
));

// Register the autoloader
$poser->register();

```
