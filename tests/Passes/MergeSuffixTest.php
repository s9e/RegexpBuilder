<?php declare(strict_types=1);

namespace s9e\RegexpBuilder\Tests\Passes;

use PHPUnit\Framework\Attributes\CoversClass;

#[CoversClass('s9e\RegexpBuilder\Passes\AbstractPass')]
#[CoversClass('s9e\RegexpBuilder\Passes\MergeSuffix')]
class MergeSuffixTest extends AbstractTestClass
{
	public static function getPassTests()
	{
		return [
//			[
//				[],
//				[]
//			],
//			[
//				[
//					[0, 1, 2],
//					[0, 1, 3]
//				],
//				[
//					[0, 1, 2],
//					[0, 1, 3]
//				],
//			],
//			[
//				[
//					[0, 4, 5, 5],
//					[1, 4, 5, 5]
//				],
//				[
//					[[[0], [1]], 4, 5, 5]
//				],
//			],
//			[
//				[
//					[4, 5, 5],
//					[1, 4, 5, 5]
//				],
//				[
//					[[[], [1]], 4, 5, 5]
//				],
//			],
//			[
//				[
//					[0, 0, 4, 5, 5],
//					[1, 1, 4, 5, 5]
//				],
//				[
//					[[[0, 0], [1, 1]], 4, 5, 5]
//				],
//			],
//			[
//				[
//					[0, [[0], [1]]],
//					[1, [[0], [1]]]
//				],
//				[
//					[[[0], [1]], [[0], [1]]]
//				],
//			],
//			[
//				[
//					[],
//					[0],
//					[1]
//				],
//				[
//					[],
//					[0],
//					[1]
//				],
//			],
//			[
//				[
//					[],
//					[0, 2],
//					[1, 2]
//				],
//				[
//					[],
//					[[[0], [1]], 2]
//				],
//			],
			[
				[
					[0, [[4], [5]]],
					[1],
					[2, [[4], [5]]],
					[3],
					[4],
					[5],
					[6]
				],
				[
					[[[], [0], [2]], [[4], [5]]],
					[1],
					[3],
					[6]
				]
			],
		];
	}
}