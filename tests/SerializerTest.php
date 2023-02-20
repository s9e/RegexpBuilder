<?php declare(strict_types=1);

namespace s9e\RegexpBuilder\Tests;

use PHPUnit\Framework\TestCase;
use s9e\RegexpBuilder\Input\Bytes as Input;
use s9e\RegexpBuilder\Meta;
use s9e\RegexpBuilder\Output\Bytes as Output;
use s9e\RegexpBuilder\Serializer;

/**
* @covers s9e\RegexpBuilder\Serializer
*/
class SerializerTest extends TestCase
{
	/**
	* @dataProvider getSerializerTests
	*/
	public function test($original, $expected, bool $groupAlternations = null)
	{
		$args = [$original];
		if (isset($groupAlternations))
		{
			$args[] = $groupAlternations;
		}

		$serializer = new Serializer(new Meta, new Output);
		$this->assertSame($expected, $serializer->serializeStrings(...$args));
	}

	public static function getSerializerTests()
	{
		return [
			[
				[
					[97],
					[108],
					[109],
					[111],
					[115],
					[116]
				],
				'[almost]'
			],
			[
				[
					[],
					[97],
					[108],
					[109],
					[111],
					[115],
					[116]
				],
				'[almost]?'
			],
			[
				[
					[98, 97, [[114], [122]]],
					[102, 111, 111]
				],
				'(?:ba[rz]|foo)'
			],
			[
				[
					[102, 111, 111, [[], [108]]]
				],
				'fool?'
			],
			[
				[
					[102, 111, 111, [[], [108], [116]]]
				],
				'foo[lt]?'
			],
			[
				[
					[102, 111, 111, [[], [108], [116, 115]]]
				],
				'foo(?:l|ts)?'
			],
			[
				[
					[115, 117, 112, 101, 114, [[98, 111, 121], [109, 97, 110]]]
				],
				'super(?:boy|man)'
			],
			[
				[
					[40],
					[41]
				],
				'[()]'
			],
			[
				[
					[42],
					[43],
					[45],
					[46]
				],
				'[*+\\-.]'
			],
			[
				[
					[47],
					[63],
					[91],
					[92]
				],
				'[/?[\\\\]'
			],
			[
				[
					[93],
					[94],
					[123]
				],
				'[\\]\\^{]'
			],
			[
				[
					[124],
					[125]
				],
				'[|}]'
			],
			[
				[
					[97],
					[98],
					[99],
					[100],
					[101]
				],
				'[a-e]'
			],
			[
				[
					[97, 97],
					[98, 98]
				],
				'(?:aa|bb)',
				true
			],
			[
				[
					[97, 97],
					[98, 98]
				],
				'aa|bb',
				false
			],
			[
				[
					[],
					[97, 97],
					[98, 98]
				],
				'(?:aa|bb)?',
				false
			],
		];
	}
}