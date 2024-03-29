<?php declare(strict_types=1);

namespace s9e\RegexpBuilder\Tests\Output;

use PHPUnit\Framework\Attributes\CoversClass;
use ValueError;

#[CoversClass('s9e\RegexpBuilder\Output\AbstractOutput')]
#[CoversClass('s9e\RegexpBuilder\Output\Bytes')]
class BytesTest extends AbstractTestClass
{
	public static function getOutputBodyTests()
	{
		return [
			[92, '\\\\'],
			[42, '\\*'],
			[102, 'f'],
			[0xC3, "\xC3"],
			[0xA9, "\xA9"],
			[0xFF, "\xFF"],
			[-1, new ValueError('Value -1 is out of bounds (0..255)')],
			[0x100, new ValueError('Value 256 is out of bounds (0..255)')]
		];
	}

	public static function getOutputClassAtomTests()
	{
		return [
			[92, '\\\\'],
			[42, '*'],
			[102, 'f'],
			[0xC3, "\xC3"],
			[0xA9, "\xA9"],
			[0xFF, "\xFF"],
			[-1, new ValueError('Value -1 is out of bounds (0..255)')],
			[0x100, new ValueError('Value 256 is out of bounds (0..255)')]
		];
	}
}