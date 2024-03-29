<?php declare(strict_types=1);

namespace s9e\RegexpBuilder\Tests\Factory;

use PHPUnit\Framework\Attributes\CoversClass;

#[CoversClass('s9e\RegexpBuilder\Factory\RE2')]
class RE2Test extends AbstractFactoryTestClass
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
				'[\\x1F\\x{2639}\\x{1F600}]'
			],
		];
	}
}