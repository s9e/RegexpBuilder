<?php declare(strict_types=1);

/**
* @package   s9e\RegexpBuilder
* @copyright Copyright (c) 2016-2022 The s9e authors
* @license   http://www.opensource.org/licenses/mit-license.php The MIT License
*/
namespace s9e\RegexpBuilder\Factory;

use function str_contains;
use s9e\RegexpBuilder\Builder;

class PHP
{
	public static function getBuilder(string $modifiers = '', string $delimiter = '/'): Builder
	{
		return new Builder([
			'delimiter' => $delimiter,
			'input'     => str_contains($modifiers, 'u') ? 'Utf8' : 'Bytes',
			'output'    => 'PHP'
		]);
	}
}