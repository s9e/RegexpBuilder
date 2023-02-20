<?php declare(strict_types=1);

namespace s9e\RegexpBuilder\Tests\Factory;

class JavaTest extends AbstractFactoryTestClass
{
	public static function getGetBuilderTests()
	{
		return [
			[
				['foo', 'bar'],
				'bar|foo'
			],
			[
				["\x1F", "\u{2639}", "\u{1F600}"],
				'[\\x1F\\u2639\\x{1F600}]'
			],
		];
	}
}