<?php declare(strict_types=1);

namespace s9e\RegexpBuilder\Tests;

use PHPUnit\Framework\TestCase;
use s9e\RegexpBuilder\StringSorter;

/**
* @covers s9e\RegexpBuilder\StringSorter
*/
class StringSorterTest extends TestCase
{
	/**
	* @dataProvider getGetUniqueSortedStringsTests
	*/
	public function testEscapeCharacterClass(array $strings, array $expected)
	{
		$sorter = new StringSorter;
		$this->assertSame($expected, $sorter->getUniqueSortedStrings($strings));
	}

	public function getGetUniqueSortedStringsTests()
	{
		return [
			[
				[],
				[]
			],
			[
				[
					[],
					[]
				],
				[
					[]
				],
			],
			[
				[
					[0, 2],
					[0, 1]
				],
				[
					[0, 1],
					[0, 2]
				]
			],
			[
				[
					[0, 2],
					[0, 2],
					[0, 2],
					[0, 1]
				],
				[
					[0, 1],
					[0, 2]
				]
			],
			[
				[
					[0, 2],
					[0],
					[12],
					[2]
				],
				[
					[0],
					[0, 2],
					[2],
					[12]
				]
			],
			[
				// Sort meta expressions *after* regular codepoints
				[
					[0, -2],
					[0, 1],
					[0, 2]
				],
				[
					[0, 1],
					[0, 2],
					[0, -2]
				]
			],
			[
				// Sort meta expressions in descending order
				[
					[0, -2],
					[0, -1],
					[0, 2]
				],
				[
					[0, 2],
					[0, -1],
					[0, -2]
				]
			],
		];
	}
}