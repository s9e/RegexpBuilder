<?php declare(strict_types=1);

/**
* @package   s9e\RegexpBuilder
* @copyright Copyright (c) 2016-2022 The s9e authors
* @license   https://opensource.org/licenses/mit-license.php The MIT License
*/
namespace s9e\RegexpBuilder\Factory;

use s9e\RegexpBuilder\Builder;

class RE2
{
	public static function getBuilder(): Builder
	{
		return new Builder([
			'input'  => 'Utf8',
			'output' => 'RE2'
		]);
	}
}