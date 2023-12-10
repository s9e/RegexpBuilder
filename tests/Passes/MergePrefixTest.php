<?php declare(strict_types=1);

namespace s9e\RegexpBuilder\Tests\Passes;

use PHPUnit\Framework\Attributes\CoversClass;

#[CoversClass('s9e\RegexpBuilder\Passes\AbstractPass')]
#[CoversClass('s9e\RegexpBuilder\Passes\MergePrefix')]
class MergePrefixTest extends AbstractTestClass
{
	public static function getPassTests()
	{
		return [
			[
				[],
				[]
			],
			[
				[
					[0, 1, 2],
					[0, 1, 3]
				],
				[
					[0, 1, [[2], [3]]]
				],
			],
			[
				[
					[0, 1, 2],
					[0, 1, 3],
					[4, 5, 5]
				],
				[
					[0, 1, [[2], [3]]],
					[4, 5, 5]
				],
			],
			[
				[
					[0],
					[0, 1, 2],
					[0, 1, 3]
				],
				[
					[
						0,
						[
							[],
							[1, 2],
							[1, 3]
						]
					]
				],
			],
			[
				[
					[102, 111, 111],
					[102, 111, 111, 108]
				],
				[
					[102, 111, 111, [[], [108]]]
				]
			],
			[
				[
					[0, 1, 2],
					[0, 3]
				],
				[
					[0, [[1, 2], [3]]]
				],
			],
		];
	}
}