<?php declare(strict_types=1);

namespace s9e\RegexpBuilder\Tests\Factory;

class RawUTF8Test extends AbstractFactoryTest
{
	public function getGetBuilderTests()
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