<?php declare(strict_types=1);

namespace s9e\RegexpBuilder\Tests\Factory;

class RawBytesTest extends AbstractFactoryTestClass
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
				"\xE2\x98[\xB9\xBA]"
			],
		];
	}
}