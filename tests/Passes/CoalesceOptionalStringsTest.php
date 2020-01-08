<?php declare(strict_types=1);

namespace s9e\RegexpBuilder\Tests\Passes;

/**
* @covers s9e\RegexpBuilder\Passes\AbstractPass
* @covers s9e\RegexpBuilder\Passes\CoalesceOptionalStrings
*/
class CoalesceOptionalStringsTest extends AbstractTest
{
	public function getPassTests()
	{
		return [
			[
				[
					[],
					[0, [[], [1]]],
					[1]
				],
				[
					[
						[[], [0]],
						[[], [1]]
					]
				]
			],
			[
				[
					[],
					[0, [[], [2]]],
					[1]
				],
				[
					[],
					[0, [[], [2]]],
					[1]
				]
			],
			[
				[
					[],
					[0, [[], [1]]],
					[1],
					[2]
				],
				[
					[],
					[0, [[], [1]]],
					[1],
					[2]
				]
			],
			[
				[
					[],
					[
						1,
						[[], [2]],
						[[], [3]]
					],
					[
						2,
						[[], [3]]
					],
					[3]
				],
				[
					[
						[[], [1]],
						[[], [2]],
						[[], [3]]
					]
				]
			],
			[
				[
					[],
					[0, [[], [1], [2]]],
					[1]
				],
				[
					[],
					[0, [[], [1], [2]]],
					[1]
				]
			],
			[
				[
					[],
					[0, [[], [2]]],
					[1, [[], [2]]],
					[2]
				],
				[
					[
						[[], [0], [1]],
						[[], [2]]
					]
				]
			],
			[
				[
					[],
					[
						0,
						[[], [1, 1]],
						[[], [2], [3], [4, 4]]
					],
					[
						1,
						1,
						[[], [2], [3], [4, 4]]
					],
					[[[2], [3]]],
					[4, 4]
				],
				[
					[
						[[], [0]],
						[[], [1, 1]],
						[[], [2], [3], [4, 4]]
					]
				]
			],
		];
	}
}