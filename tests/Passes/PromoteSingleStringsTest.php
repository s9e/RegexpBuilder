<?php declare(strict_types=1);

namespace s9e\RegexpBuilder\Tests\Passes;

use PHPUnit\Framework\Attributes\CoversClass;

#[CoversClass('s9e\RegexpBuilder\Passes\AbstractPass')]
#[CoversClass('s9e\RegexpBuilder\Passes\PromoteSingleStrings')]
class PromoteSingleStringsTest extends AbstractTestClass
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
					[0, 1, 2],
					[0, 1, 3]
				],
			],
			[
				[
					[[[4, 5, 5]]]
				],
				[
					[4, 5, 5]
				],
			],
			[
				[
					[
						98,
						[
							[97, [[114], [122]]]
						]
					],
					[102, 111, 111]
				],
				[
					[
						98,
						97,
						[[114], [122]]
					],
					[102, 111, 111]
				]
			]
		];
	}
}