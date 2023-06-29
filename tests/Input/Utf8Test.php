<?php declare(strict_types=1);

namespace s9e\RegexpBuilder\Tests\Input;

use ValueError;
use s9e\RegexpBuilder\Input\InputInterface;

/**
* @covers s9e\RegexpBuilder\Input\Utf8
*/
class Utf8Test extends AbstractTestClass
{
	public static function getInputTests()
	{
		return [
			[
				"\xFF",
				new ValueError('Invalid UTF-8 string')
			],
			[
				'',
				[]
			],
			[
				'',
				[],
				function (InputInterface $input)
				{
					$input->useSurrogates = true;
				}
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
			],
			[
				'☺',
				[0x263A],
				function (InputInterface $input)
				{
					$input->useSurrogates = true;
				}
			],
			[
				"\u{1F601}\u{1F602}",
				[0x1F601, 0x1F602],
				function (InputInterface $input)
				{
					$input->useSurrogates = false;
				}
			],
			[
				"\u{1F601}\u{1F602}",
				[0xD83D, 0xDE01, 0xD83D, 0xDE02],
				function (InputInterface $input)
				{
					$input->useSurrogates = true;
				}
			],
		];
	}
}