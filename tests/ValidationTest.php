<?php declare(strict_types=1);

namespace s9e\RegexpBuilder\Tests;

use PHPUnit\Framework\TestCase;
use s9e\RegexpBuilder\Builder;
use s9e\RegexpBuilder\Input\Utf8 as Utf8Input;
use s9e\RegexpBuilder\Output\Utf8 as Utf8Output;

/**
* @coversNothing
*/
class ValidationTest extends TestCase
{
	/**
	* @dataProvider getValidationTests
	*/
	public function test($expected, $strings, $config = [], callable $setup = null)
	{
		$builder = new Builder(...$config);
		if (isset($setup))
		{
			$setup($builder);
		}

		$actual = $builder->build($strings);
		$this->assertSame($expected, $actual);

		if (!isset($config['meta']) && !isset($config['output']))
		{
			$regexp = '@^' . $actual . '$@';
			foreach ($strings as $string)
			{
				$this->assertMatchesRegularExpression($regexp, $string);
			}
		}
	}

	public function getValidationTests()
	{
		return [
			[
				// CoalesceOptionalStrings
				'a?b?',
				['', 'a', 'ab', 'b']
			],
			[
				// CoalesceSingleCharacterPrefix
				'[ab]b|c',
				['ab', 'bb', 'c']
			],
			[
				// GroupSingleCharacters
				'a?[xy]',
				['ax', 'ay', 'x', 'y']
			],
			[
				// MergePrefix
				'a(?:xx|yy)',
				['axx', 'ayy']
			],
			[
				// MergeSuffix
				'(?:aa|bb)x',
				['aax', 'bbx']
			],
			[
				// PromoteSingleStrings
				'(?:a0?|b)x',
				['a0x', 'ax', 'bx']
			],
			[
				// Recurse
				'a[xy][01]',
				['ax0', 'ax1', 'ay0', 'ay1']
			],
			[
				'ba[rz]',
				['bar', 'baz']
			],
			[
				'fool?',
				['foo', 'fool']
			],
			[
				'ax(?:ed)?',
				['ax', 'axed']
			],
			[
				':[()/[-\\]|]',
				[':)', ':(', ':]', ':[', ':|', ':/', ':\\']
			],
			[
				':[()[\\]]',
				[':)', ':(', ':]', ':[']
			],
			[
				':[/\\\\|]',
				[':|', ':/', ':\\']
			],
			[
				'[ab]',
				['a', 'b']
			],
			[
				'[♠♣♥♦]',
				['♠', '♣', '♥', '♦'],
				['input' => new Utf8Input, 'output' => new Utf8Output]
			],
			[
				'[ls]ock',
				['lock', 'sock']
			],
			[
				'bo[ao]st',
				['boast', 'boost']
			],
			[
				'pe?st',
				['pest', 'pst']
			],
			[
				'bo[ao]?st',
				['boast', 'boost', 'bost']
			],
			[
				'b(?:e|oo)st',
				['boost', 'best']
			],
			[
				'b(?:oo)?st',
				['boost', 'bst']
			],
			[
				'b(?:[eu]|oo)st',
				['best', 'boost', 'bust']
			],
			[
				'b(?:oo)?st|cool',
				['boost', 'bst', 'cool']
			],
			[
				'(?:b(?:oo)?|co)st',
				['boost', 'bst', 'cost']
			],
			[
				'aa[xy]',
				['aax', 'aay', 'aax', 'aay']
			],
			[
				'[ab]aa[xy]',
				['aaax', 'aaay', 'baax', 'baay']
			],
			[
				'(?:a|bb)aa[xy]',
				['aaax', 'aaay', 'bbaax', 'bbaay']
			],
			[
				'aa?[xy]',
				['aax', 'aay', 'ax', 'ay']
			],
			[
				'aaa?[xy]',
				['aaax', 'aaay', 'aax', 'aay']
			],
			[
				'(?:ab|cd)[xy]',
				['abx', 'aby', 'cdx', 'cdy']
			],
			[
				'(?:a|bb)(?:xx|yy)',
				['axx', 'ayy', 'bbxx', 'bbyy']
			],
			[
				'a(?:xx|yy)|bb(?:xx|yy)|c',
				['axx', 'ayy', 'bbxx', 'bbyy', 'c']
			],
			[
				// Ensure it doesn't become (?:c|(?:a|bb)(?:xx|yy)|azz) even though it would be
				// shorter, because having fewer alternations at the top level is more important
				'a(?:xx|yy|zz)|bb(?:xx|yy)|c',
				['axx', 'ayy', 'azz', 'bbxx', 'bbyy', 'c']
			],
			[
				'x(?:ixi|oxo)x',
				['xixix', 'xoxox']
			],
			[
				'x[io]x[io]x',
				['xixix', 'xixox', 'xoxox', 'xoxix']
			],
			[
				'(?:a|bb)(?:bar|foo)?',
				['afoo', 'abar', 'bbfoo', 'bbbar', 'a', 'bb']
			],
			[
				'[ab][xy]',
				['ax', 'ay', 'bx', 'by']
			],
			[
				'[ab][xy]|c',
				['ax', 'ay', 'bx', 'by', 'c']
			],
			[
				'[ab]?[xy]',
				['ax', 'ay', 'bx', 'by', 'x', 'y']
			],
			[
				'[01]?[34]',
				['03', '04', '13', '14', '3', '4']
			],
			[
				'a[xy]|bb[xy]|c',
				['ax', 'ay', 'bbx', 'bby', 'c']
			],
			[
				'[ab][xy]|c|dd[xy]',
				['ax', 'ay', 'bx', 'by', 'c', 'ddx', 'ddy']
			],
			[
				'[ab][xy]|[cd][XY]|[ef]|gg',
				['ax', 'ay', 'bx', 'by', 'cX', 'cY', 'dX', 'dY', 'e', 'f', 'gg']
			],
			[
				'[ab](?:xx|yy)[12]',
				[
					'axx1', 'ayy1', 'bxx1', 'byy1',
					'axx2', 'ayy2', 'bxx2', 'byy2'
				]
			],
			[
				'',
				['']
			],
			[
				'',
				['', '']
			],
			[
				'',
				[]
			],
			[
				'[yz]|bar|foo',
				['foo', 'bar', 'y', 'z']
			],
			[
				'[yz]|ba[rz]|foo',
				['foo', 'bar', 'baz', 'y', 'z']
			],
			[
				'a(?:a(?:cc|dd))?|bb(?:cc|dd)',
				['a', 'aacc', 'aadd', 'bbcc', 'bbdd']
			],
			[
				'(?:aa|bb)(?:cc|dd)?',
				['aa', 'bb', 'aacc', 'aadd', 'bbcc', 'bbdd']
			],
			[
				'(?:aa|bb)(?:(?:cc|dd)(?:xx|yy))?',
				[
					'aa', 'bb',
					'aaccxx', 'aaddxx', 'bbccxx', 'bbddxx',
					'aaccyy', 'aaddyy', 'bbccyy', 'bbddyy'
				]
			],
			[
				'[1-7][0-7]?|0',
				array_map('decoct', range(0, 63))
			],
			[
				'[0-9a-f][0-9a-f]',
				array_map('bin2hex', array_map('chr', range(0, 255)))
			],
			[
				// This shouldn't be Gooo?o?o?o?o?gle because it would backtrack exponentially
				'Goo(?:o(?:o(?:o(?:oo?)?)?)?)?gle',
				[
					'Google',
					'Gooogle',
					'Goooogle',
					'Gooooogle',
					'Goooooogle',
					'Gooooooogle'
				]
			],
			[
				'12?3?|23?|3',
				['1', '12', '123', '13', '2', '23', '3']
			],
			[
				'1?2?3?',
				['', '1', '12', '123', '13', '2', '23', '3']
			],
			[
				'J[0-4]?(?:Z[MW])?',
				[
					'J',   'J0',   'J1',   'J2',   'J3',   'J4',
					'JZM', 'J0ZM', 'J1ZM', 'J2ZM', 'J3ZM', 'J4ZM',
					'JZW', 'J0ZW', 'J1ZW', 'J2ZW', 'J3ZW', 'J4ZW'
				]
			],
			[
				'J[0-4]?(?:Z[MW]V?)?',
				[
					'J',    'J0',    'J1',    'J2',    'J3',    'J4',
					'JZM',  'J0ZM',  'J1ZM',  'J2ZM',  'J3ZM',  'J4ZM',
					'JZW',  'J0ZW',  'J1ZW',  'J2ZW',  'J3ZW',  'J4ZW',
					'JZMV', 'J0ZMV', 'J1ZMV', 'J2ZMV', 'J3ZMV', 'J4ZMV',
					'JZWV', 'J0ZWV', 'J1ZWV', 'J2ZWV', 'J3ZWV', 'J4ZWV'
				]
			],
			[
				// Pressure the CoalesceOptionalStrings pass with 2^10 strings
				'0?1?2?3?4?5?6?7?8?9?',
				array_map(
					function ($n)
					{
						$str = '';
						for ($i = 0; $i < 10; ++$i)
						{
							if ($n & (2 ** $i))
							{
								$str .= (string) $i;
							}
						}

						return $str;
					},
					range(0, 2 ** 10)
				)
			],
			[
				// Ensure that strings are sorted lexicographically, not in "natural" sort order
				'8?9?a?',
				['', '8', '9', '89', 'a', '8a', '9a', '89a']
			],
			[
				// CoalesceSingleCharacterPrefix should ignore expressions that do not represent a
				// single character
				'[ab\\d]x|zz|\\d+x|\\bx',
				['ax', 'bx', '?x', '*x', '#x', 'zz'],
				[],
				function (Builder $builder)
				{
					$builder->meta->set('*', '\\d+');
					$builder->meta->set('#', '\\b');
					$builder->meta->set('?', '\\d');
				}
			],
			[
				'[!#$*1]|.*?',
				['!', '#', '$', '\\*', '*', '1'],
				[],
				function (Builder $builder)
				{
					$builder->meta->set('*',   '.*?');
					$builder->meta->set('\\*', '\\*');
				}
			],
		];
	}
}