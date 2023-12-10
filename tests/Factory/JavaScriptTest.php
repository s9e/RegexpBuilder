<?php declare(strict_types=1);

namespace s9e\RegexpBuilder\Tests\Factory;

use PHPUnit\Framework\Attributes\CoversClass;

#[CoversClass('s9e\RegexpBuilder\Factory\JavaScript')]
class JavaScriptTest extends AbstractFactoryTestClass
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
				'[\\u2639\\u263A]'
			],
			[
				["\u{1F601}", "\u{1F602}"],
				'\\uD83D[\\uDE01\\uDE02]'
			],
			[
				["\u{1F601}", "\u{1F602}"],
				'[\\u{1F601}\\u{1F602}]',
				['flags' => 'u']
			],
			[
				['(', ')', '/'],
				'[()\\/]'
			],
		];
	}
}