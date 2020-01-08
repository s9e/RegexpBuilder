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
	unset($m[0]);
	ob_start();
	eval($m[2]);
	$m[4] = ob_get_clean();

	return implode('', $m);
}