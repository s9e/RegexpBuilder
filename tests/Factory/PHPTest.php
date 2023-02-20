<?php declare(strict_types=1);

namespace s9e\RegexpBuilder\Tests\Factory;

class PHPTest extends AbstractFactoryTestClass
{
	public static function getGetBuilderTests()
	{
		return [
			[
				['foo', 'bar'],
				'bar|foo'
			],
			[
				["\u{2639}", "\u{263A}"],
				'\\xE2\\x98[\\xB9\\xBA]'
			],
			[
				["\u{2639}", "\u{263A}"],
				'[\\x{2639}\\x{263A}]',
				['modifiers' => 'u']
			],
			[
				['(', ')', '/'],
				'[()\\/]'
			],
			[
				['(', ')', '/'],
				'[\\(\\)/]',
				['delimiter' => '()']
			],
			[
				['x x'],
				'x x'
			],
			[
				['x x', 'xxx'],
				'x[ x]x',
				['modifiers' => 'x']
			],
			[
				['x #x'],
				'x\\ \\#x',
				['modifiers' => 'x']
			],
			[
				['x #x'],
				'x #x'
			],
			[
				['axx', 'ayy'],
				'a(xx|yy)',
				['modifiers' => 'n']
			],
		];
	}
}