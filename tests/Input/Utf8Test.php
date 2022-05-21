<?php declare(strict_types=1);

namespace s9e\RegexpBuilder\Tests\Input;

use InvalidArgumentException;
use s9e\RegexpBuilder\Input\InputInterface;

/**
* @covers s9e\RegexpBuilder\Input\Utf8
*/
class Utf8Test extends AbstractTest
{
	public function getInputTests()
	{
		return [
			[
				"\xFF",
				new InvalidArgumentException('Invalid UTF-8 string')
			],
			[
				'',
				[]
			],
			[
				'foo',
				[102, 111, 111]
			],
			[
				'Pokémon',
				[80, 111, 107, 233, 109, 111, 110]
			],
			[
				'Pokémon',
				[80, 111, 107, 233, 109, 111, 110],
				function (InputInterface $input)
				{
					$input->useSurrogates = true;
				}
			],
			[
				' 🔆 ',
				[32, 0x1F506, 32]
			],
			[
				' 🔆 ',
				[32, 0x1F506, 32],
				function (InputInterface $input)
				{
					$input->useSurrogates = false;
				}
			],
			[
				' 🔆 ',
				[32, 55357, 56582, 32],
				function (InputInterface $input)
				{
					$input->useSurrogates = true;
				}
			],
			[
				'☺',
				[0x263A]
			]
		];
	}
}