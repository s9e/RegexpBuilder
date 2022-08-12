<?php declare(strict_types=1);

namespace s9e\RegexpBuilder\Tests;

use PHPUnit\Framework\TestCase;
use s9e\RegexpBuilder\Builder;

/**
* @covers s9e\RegexpBuilder\Builder
*/
class BuilderTest extends TestCase
{
	public function testStandalone()
	{
		$builder = new Builder;
		$this->assertEquals('bar|foo', $builder->build(['foo', 'bar']));

		$builder->standalone = false;
		$this->assertEquals('(?:bar|foo)', $builder->build(['foo', 'bar']));
	}

	/**
	* @dataProvider getBuilderTests
	*/
	public function test($original, $expected, $config = [], callable $setup = null)
	{
		$builder = new Builder(...$config);
		if (isset($setup))
		{
			$setup($builder);
		}
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
				'bar|foo'
			],
			[
				[
					'foo',
					'fool',
					'bar'
				],
				'bar|fool?'
			],
			[
				[
					"\xF0",
					"\xFF"
				],
				'[\\xF0\\xFF]',
				['output' => 'PCRE2']
			],
			[
				[
					"\xF0\x9F\x98\x80",
					"\xF0\x9F\x98\x81"
				],
				'\\xF0\\x9F\\x98[\\x80\\x81]',
				['output' => 'PCRE2']
			],
			[
				[
					"\xF0\x9F\x98\x80",
					"\xF0\x9F\x98\x81"
				],
				'[\x{1F600}\x{1F601}]',
				['input' => 'Utf8', 'output' => 'PCRE2']
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
					'output'       => 'JavaScript'
				],
				function (Builder $builder)
				{
					$builder->input->useSurrogates = true;
				}
			],
			[
				[
					"\xED\x9F\xBB\xED\x9F\xBB",
					"\xEF\xA4\x80\xEF\xA4\x80",
					"\xF0\x9F\x98\x80\xF0\x9F\x98\x80"
				],
				'\\x{D7FB}\\x{D7FB}|\\x{F900}\\x{F900}|\\x{1F600}\\x{1F600}',
				['input' => 'Utf8', 'output' => 'PCRE2']
			],
			[
				[
					"\xED\x9F\xBB\xED\x9F\xBB",
					"\xEF\xA4\x80\xEF\xA4\x80",
					"\xF0\x9F\x98\x80"
				],
				'\\uD7FB\\uD7FB|\\uD83D\\uDE00|\\uF900\\uF900',
				[
					'input'        => 'Utf8',
					'output'       => 'JavaScript'
				],
				function (Builder $builder)
				{
					$builder->input->useSurrogates = true;
				}
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
				'x[ab\\d]',
				['meta' => ['?' => '\\d']]
			],
			[
				['b', 'bX'],
				'b(?:xx)?',
				['meta' => ['X' => 'xx']]
			],
			[
				["\n", '.'],
				'\\n|.',
				['meta' => ['.' => '.'], 'output' => 'PCRE2']
			],
			[
				['^', '_'],
				'[\\^_]'
			],
			[
				['[foo]', '[bar]'],
				'\\[(?:bar|foo)]'
			],
		];
	}
}