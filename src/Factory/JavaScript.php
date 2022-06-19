<?php declare(strict_types=1);

/**
* @package   s9e\RegexpBuilder
* @copyright Copyright (c) 2016-2022 The s9e authors
* @license   http://www.opensource.org/licenses/mit-license.php The MIT License
*/
namespace s9e\RegexpBuilder\Factory;

use function str_contains;
use s9e\RegexpBuilder\Builder;

class JavaScript
{
	public static function getBuilder(string $flags = ''): Builder
	{
		$builder = new Builder([
			'input'  => 'Utf8',
			'output' => 'JavaScript'
		]);
		$builder->inputSplitter->input->useSurrogates = !str_contains($flags, 'u');

		return $builder;
	}
}