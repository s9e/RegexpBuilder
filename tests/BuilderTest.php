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
					'foo',
					'fool',
					'bar'
				],
				'(?:bar|fool?)'
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
				[
					'input'        => 'Utf8',
					'inputOptions' => ['useSurrogates' => true],
					'output'       => 'JavaScript'
				]
			],
			[
				[
					"\xED\x9F\xBB\xED\x9F\xBB",
					"\xEF\xA4\x80\xEF\xA4\x80",
					"\xF0\x9F\x98\x80\xF0\x9F\x98\x80"
				],
				'(?:\\x{D7FB}\\x{D7FB}|\\x{F900}\\x{F900}|\\x{1F600}\\x{1F600})',
				['input' => 'Utf8', 'output' => 'PHP']
			],
			[
				[
					"\xED\x9F\xBB\xED\x9F\xBB",
					"\xEF\xA4\x80\xEF\xA4\x80",
					"\xF0\x9F\x98\x80"
				],
				'(?:\\uD7FB\\uD7FB|\\uD83D\\uDE00|\\uF900\\uF900)',
				[
					'input'        => 'Utf8',
					'inputOptions' => ['useSurrogates' => true],
					'output'       => 'JavaScript'
				]
			],
			[
				['x?'],
				'x.',
				['meta' => ['?' => '.']]
			],
			[
				['x', 'x?'],
				'x.?',
				['meta' => ['?' => '.']]
			],
			[
				['x?', 'xa', 'xb'],
				'x[\\dab]',
				['meta' => ['?' => '\\d']]
			],
			[
				['b', 'bX'],
				'b(?:xx)?',
				['meta' => ['X' => 'xx']]
			],
			[
				["\n", '.'],
				'(?:\\n|.)',
				['meta' => ['.' => '.'], 'output' => 'PHP']
			],
			[
				['^', '_'],
				'[\\^_]'
			],
		];
	}
}