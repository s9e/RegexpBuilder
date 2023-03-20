#!/usr/bin/php
<?php declare(strict_types=1);

include __DIR__ . '/../vendor/autoload.php';

$filepath = __DIR__ . '/../README.md';
file_put_contents(
	$filepath,
	preg_replace_callback(
		'((```php)(.*?)(```\\s+```\\s+)(.*?)(\\s+```))s',
		'patchOutput',
		file_get_contents($filepath)
	)
);

function patchOutput($m)
{
	$m[4] = evalScript($m[2]);
	unset($m[0]);

	return implode('', $m);
}

function evalScript()
{
	ob_start();
	eval(func_get_arg(0));

	return trim(ob_get_clean());
}