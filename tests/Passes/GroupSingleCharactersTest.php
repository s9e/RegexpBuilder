<?php declare(strict_types=1);

namespace s9e\RegexpBuilder\Tests\Passes;

/**
* @covers s9e\RegexpBuilder\Passes\AbstractPass
* @covers s9e\RegexpBuilder\Passes\GroupSingleCharacters
*/
class GroupSingleCharactersTest extends AbstractTestClass
{
	public static function getPassTests()
	{
		return [
			[
				[
					[1, 2],
					[3, 4]
				],
				[
					[1, 2],
					[3, 4]
				]
			],
			[
				[
					[1, 2],
					[3]
				],
				[
					[1, 2],
					[3]
				]
			],
			[
				[
					[1],
					[3]
				],
				[
					[1],
					[3]
				]
			],
			[
				[
					[1],
					[2, 2],
					[3]
				],
				[
					[[[1], [3]]],
					[2, 2]
				]
			],
			[
				[
					[1],
					[2, 2],
					[self::getMetaValue('\\w')]
				],
				[
					[[[1], [self::getMetaValue('\\w')]]],
					[2, 2]
				]
			],
			[
				[
					[1],
					[2, 2],
					[self::getMetaValue('.*')]
				],
				[
					[1],
					[2, 2],
					[self::getMetaValue('.*')]
				]
			],
		];
	}
}