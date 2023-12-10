<?php declare(strict_types=1);

namespace s9e\RegexpBuilder\Tests\Factory;

use PHPUnit\Framework\Attributes\CoversClass;

#[CoversClass('s9e\RegexpBuilder\Factory\RawUTF8')]
class RawUTF8Test extends AbstractFactoryTestClass
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
				"[\u{2639}\u{263A}]"
			],
		];
	}
}