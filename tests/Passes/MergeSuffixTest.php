<?php

namespace s9e\RegexpBuilder\Tests\Passes;

/**
* @covers s9e\RegexpBuilder\Passes\AbstractPass
* @covers s9e\RegexpBuilder\Passes\MergeSuffix
*/
class MergeSuffixTest extends AbstractTest
{
	public function getPassTests()
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
					[0, 1, 2],
					[0, 1, 3]
				],
			],
			[
				[
					[0, 4, 5, 5],
					[1, 4, 5, 5]
				],
				[
					[[[0], [1]], 4, 5, 5]
				],
			],
			[
				[
					[4, 5, 5],
					[1, 4, 5, 5]
				],
				[
					[[[], [1]], 4, 5, 5]
				],
			],
			[
				[
					[0, 0, 4, 5, 5],
					[1, 1, 4, 5, 5]
				],
				[
					[[[0, 0], [1, 1]], 4, 5, 5]
				],
			],
			[
				[
					[0, [[0], [1]]],
					[1, [[0], [1]]]
				],
				[
					[[[0], [1]], [[0], [1]]]
				],
			],
			[
				[
					[],
					[0],
					[1]
				],
				[
					[],
					[0],
					[1]
				],
			],
			[
				[
					[],
					[0, 2],
					[1, 2]
				],
				[
					[],
					[[[0], [1]], 2]
				],
			],
		];
	}
}