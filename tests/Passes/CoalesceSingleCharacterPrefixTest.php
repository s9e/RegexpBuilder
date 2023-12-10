<?php declare(strict_types=1);

namespace s9e\RegexpBuilder\Tests\Passes;

use PHPUnit\Framework\Attributes\CoversClass;

#[CoversClass('s9e\RegexpBuilder\Passes\AbstractPass')]
#[CoversClass('s9e\RegexpBuilder\Passes\CoalesceSingleCharacterPrefix')]
class CoalesceSingleCharacterPrefixTest extends AbstractTestClass
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
					[1, 2],
					[2, 2],
					[3]
				],
				[
					[[[1], [2]], 2],
					[3]
				]
			],
			[
				[
					[1, 1],
					[2, 2],
					[3, 3, 3],
					[4, 2],
					[5, 3, 3]
				],
				[
					[[[2], [4]], 2],
					[[[3], [5]], 3, 3],
					[1, 1]
				]
			],
		];
	}
}