<?php

namespace s9e\RegexpBuilder\Tests;

use PHPUnit_Framework_TestCase;
use s9e\RegexpBuilder\Builder;

/**
* @coversNothing
*/
class ValidationTest extends PHPUnit_Framework_TestCase
{
	/**
	* @dataProvider getValidationTests
	*/
	public function test($expected, $original, $config = [])
	{
		$builder = new Builder($config);
		$this->assertSame($expected, $builder->build($original));
	}

	public function getValidationTests()
	{
		return [
			[
				// CoalesceSingleCharacterPrefix
				'(?:[ab]b|c)',
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
				':[()\\/[-\\]|]',
				[':)', ':(', ':]', ':[', ':|', ':/', ':\\']
			],
			[
				':[()[\\]]',
				[':)', ':(', ':]', ':[']
			],
			[
				':[\\/\\\\|]',
				[':|', ':/', ':\\']
			],
			[
				'[ab]',
				['a', 'b']
			],
			[
				'[♠♣♥♦]',
				['♠', '♣', '♥', '♦'],
				['input' => 'Utf8', 'output' => 'Utf8']
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
				'(?:b(?:oo)?st|cool)',
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
				'(?:a(?:xx|yy)|bb(?:xx|yy)|c)',
				['axx', 'ayy', 'bbxx', 'bbyy', 'c']
			],
			[
				// Ensure it doesn't become (?:c|(?:a|bb)(?:xx|yy)|azz) even though it would be
				// shorter, because having fewer alternations at the top level is more important
				'(?:a(?:xx|yy|zz)|bb(?:xx|yy)|c)',
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
				'(?:[ab][xy]|c)',
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
				'(?:a[xy]|bb[xy]|c)',
				['ax', 'ay', 'bbx', 'bby', 'c']
			],
			[
				'(?:[ab][xy]|c|dd[xy])',
				['ax', 'ay', 'bx', 'by', 'c', 'ddx', 'ddy']
			],
			[
				'(?:[ab][xy]|[cd][XY]|[ef]|gg)',
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
				'(?:[yz]|bar|foo)',
				['foo', 'bar', 'y', 'z']
			],
			[
				'(?:[yz]|ba[rz]|foo)',
				['foo', 'bar', 'baz', 'y', 'z']
			],
			[
				'(?:a(?:a(?:cc|dd))?|bb(?:cc|dd))',
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
				'(?:[1-7][0-7]?|0)',
				array_map('decoct', range(0, 63))
			],
			[
				'[0-9a-f][0-9a-f]',
				array_map('bin2hex', array_map('chr', range(0, 255)))
			],
		];
	}
}