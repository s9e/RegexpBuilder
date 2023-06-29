<?php declare(strict_types=1);

namespace s9e\RegexpBuilder\Tests;

use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use s9e\RegexpBuilder\Builder;
use s9e\RegexpBuilder\Input\Utf8 as Utf8Input;
use s9e\RegexpBuilder\Output\JavaScript;
use s9e\RegexpBuilder\Output\PCRE2;
use s9e\RegexpBuilder\Output\Utf8 as Utf8Output;
use s9e\RegexpBuilder\Expression;

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

	#[DataProvider('getBuilderTests')]
	public function test($original, $expected, $config = [], callable $setup = null)
	{
		$builder = new Builder(...$config);
		if (isset($setup))
		{
			$setup($builder);
		}
		$this->assertSame($expected, $builder->build($original));
	}

	public static function getBuilderTests()
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
				['output' => new PCRE2]
			],
			[
				[
					"\xF0\x9F\x98\x80",
					"\xF0\x9F\x98\x81"
				],
				'\\xF0\\x9F\\x98[\\x80\\x81]',
				['output' => new PCRE2]
			],
			[
				[
					"\xF0\x9F\x98\x80",
					"\xF0\x9F\x98\x81"
				],
				'[\x{1F600}\x{1F601}]',
				['input' => new Utf8Input, 'output' => new PCRE2]
			],
			[
				[
					"\xF0\x9F\x98\x80",
					"\xF0\x9F\x98\x81"
				],
				"[\xF0\x9F\x98\x80\xF0\x9F\x98\x81]",
				['input' => new Utf8Input, 'output' => new Utf8Output]
			],
			[
				[
					"\xF0\x9F\x98\x80",
					"\xF0\x9F\x98\x81"
				],
				'\\uD83D[\\uDE00\\uDE01]',
				[
					'input'  => new Utf8Input,
					'output' => new JavaScript
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
				['input' => new Utf8Input, 'output' => new PCRE2]
			],
			[
				[
					"\xED\x9F\xBB\xED\x9F\xBB",
					"\xEF\xA4\x80\xEF\xA4\x80",
					"\xF0\x9F\x98\x80"
				],
				'\\uD7FB\\uD7FB|\\uD83D\\uDE00|\\uF900\\uF900',
				[
					'input'  => new Utf8Input,
					'output' => new JavaScript
				],
				function (Builder $builder)
				{
					$builder->input->useSurrogates = true;
				}
			],
			[
				['x?'],
				'x.',
				[],
				function (Builder $builder)
				{
					$builder->meta->set('?', '.');
				}
			],
			[
				['x', 'x?'],
				'x.?',
				[],
				function (Builder $builder)
				{
					$builder->meta->set('?', '.');
				}
			],
			[
				['x?', 'xa', 'xb'],
				'x[ab\\d]',
				[],
				function (Builder $builder)
				{
					$builder->meta->set('?', '\\d');
				}
			],
			[
				['b', 'bX'],
				'b(?:xx)?',
				[],
				function (Builder $builder)
				{
					$builder->meta->set('X', 'xx');
				}
			],
			[
				["\n", '.'],
				'\\n|.',
				['output' => new PCRE2],
				function (Builder $builder)
				{
					$builder->meta->set('.', '.');
				}
			],
			[
				['^', '_'],
				'[\\^_]'
			],
			[
				['[foo]', '[bar]'],
				'\\[(?:bar|foo)]'
			],
			[
				['[foo]', '[bar]', ['[ba', 'z', ']']],
				'\\[(?:ba[rz]|foo)]'
			],
			[
				['[foo]', '[bar]', ['[ba', new Expression('z'), ']']],
				'\\[(?:ba[rz]|foo)]'
			],
			[
				// Differentiate between \\d as a literal and \\d as an expression
				['x\\dx', ['x', new Expression('\\d'), 'x']],
				'x(?:\\\\d|\\d)x'
			],
		];
	}
}