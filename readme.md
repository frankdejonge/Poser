# Poser

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