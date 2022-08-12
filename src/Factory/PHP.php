<?php declare(strict_types=1);

/**
* @package   s9e\RegexpBuilder
* @copyright Copyright (c) 2016-2022 The s9e authors
* @license   https://opensource.org/licenses/mit-license.php The MIT License
*/
namespace s9e\RegexpBuilder\Factory;

use function str_contains;
use s9e\RegexpBuilder\Builder;

class PHP implements FactoryInterface
{
	public static function getBuilder(string $modifiers = '', string $delimiter = '/'): Builder
	{
		$builder = new Builder(
			input:  str_contains($modifiers, 'u') ? 'Utf8' : 'Bytes',
			output: 'PHP'
		);
		$builder->output->setDelimiter($delimiter);

		if (str_contains($modifiers, 'n'))
		{
			$builder->serializer->useNonCapturingGroups = false;
		}
		if (str_contains($modifiers, 'x'))
		{
			$builder->output->enableExtended();
		}

		return $builder;
	}
}