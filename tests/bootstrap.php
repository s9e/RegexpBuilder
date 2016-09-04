<?php

include __DIR__ . '/../vendor/autoload.php';

spl_autoload_register(
	function($className)
	{
		if (preg_match('#^s9e\\\\RegexpBuilder\\\\Tests(\\\\[\\w\\\\]+)$#D', $className, $m))
		{
			$path = __DIR__ . str_replace('\\', '/', $m[1]) . '.php';

			if (file_exists($path))
			{
				include $path;
			}
		}
	}
);