<?php

namespace s9e\RegexpBuilder\Tests;

use PHPUnit_Framework_TestCase;
use s9e\RegexpBuilder\Builder;

/**
* @covers s9e\RegexpBuilder\Builder
*/
class BuilderTest extends PHPUnit_Framework_TestCase
{
	/**
	* @dataProvider getBuilderTests
	*/
	public function test($original, $expected, $config = [])
	{
		$builder = new Builder($config);
		$this->assertSame($expected, $builder->build($original));
	}

	public function getBuilderTests()
	{
		return [
			[
				[''],
				''
			],
			[
				[
					'foo',
					'bar'
				],
				'(?:bar|foo)'
			],
			[
				[
					"\xF0",
					"\xFF"
				],
				'[\\xF0\\xFF]',
				['output' => 'PHP']
			],
			[
				[
					"\xF0\x9F\x98\x80",
					"\xF0\x9F\x98\x81"
				],
				'\\xF0\\x9F\\x98[\\x80\\x81]',
				['output' => 'PHP']
			],
			[
				[
					"\xF0\x9F\x98\x80",
					"\xF0\x9F\x98\x81"
				],
				'[\x{1F600}\x{1F601}]',
				['input' => 'Utf8', 'output' => 'PHP']
			],
			[
				[
					"\xF0\x9F\x98\x80",
					"\xF0\x9F\x98\x81"
				],
				"[\xF0\x9F\x98\x80\xF0\x9F\x98\x81]",
				['input' => 'Utf8', 'output' => 'Utf8']
			],
			[
				[
					"\xF0\x9F\x98\x80",
					"\xF0\x9F\x98\x81"
				],
				'\\uD83D[\\uDE00\\uDE01]',
				['input' => 'Utf8ToSurrogates', 'output' => 'JavaScript']
			],
		];
	}
}