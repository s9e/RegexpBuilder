<?php declare(strict_types=1);

namespace s9e\RegexpBuilder\Tests\Output;

use ValueError;
use s9e\RegexpBuilder\Output\HexFormat;

/**
* @covers s9e\RegexpBuilder\Output\AbstractOutput
* @covers s9e\RegexpBuilder\Output\PrintableAscii
* @covers s9e\RegexpBuilder\Output\RE2
*/
class RE2Test extends AbstractTestClass
{
	public static function getOutputBodyTests()
	{
		return [
			[ord("\n"), '\\n'],
			[ord("\r"), '\\r'],
			[ord("\t"), '\\t'],
			[0o07, '\\a'],
			[0o14, '\\f'],
			[0o13, '\\v'],
			[92, '\\\\'],
			[42, '\\*'],
			[102, 'f'],
			[0xC3, '\\xC3'],
			[0xC3, '\\xC3', fn($output) => $output->hexFormat = HexFormat::UpperCase],
			[0xC3, '\\xc3', fn($output) => $output->hexFormat = HexFormat::LowerCase],
			[0x2026, '\\x{2026}'],
			[0xFE0F, '\\x{FE0F}'],
			[0xFE0F, '\\x{FE0F}', fn($output) => $output->hexFormat = HexFormat::UpperCase],
			[0xFE0F, '\\x{fe0f}', fn($output) => $output->hexFormat = HexFormat::LowerCase],
			[0x1F600, '\\x{1F600}'],
			[0x1F600, '\\x{1F600}', fn($output) => $output->hexFormat = HexFormat::UpperCase],
			[0x1F600, '\\x{1f600}', fn($output) => $output->hexFormat = HexFormat::LowerCase],
			[0x120000, new ValueError('Value 1179648 is out of bounds (0..1114111)')]
		];
	}

	public static function getOutputClassAtomTests()
	{
		return [
			[ord("\n"), '\\n'],
			[ord("\r"), '\\r'],
			[ord("\t"), '\\t'],
			[0o07, '\\a'],
			[0o14, '\\f'],
			[0o13, '\\v'],
			[92, '\\\\'],
			[42, '*'],
			[102, 'f'],
			[0xC3, '\\xC3'],
			[0xC3, '\\xC3', fn($output) => $output->hexFormat = HexFormat::UpperCase],
			[0xC3, '\\xc3', fn($output) => $output->hexFormat = HexFormat::LowerCase],
			[0x2026, '\\x{2026}'],
			[0xFE0F, '\\x{FE0F}'],
			[0xFE0F, '\\x{FE0F}', fn($output) => $output->hexFormat = HexFormat::UpperCase],
			[0xFE0F, '\\x{fe0f}', fn($output) => $output->hexFormat = HexFormat::LowerCase],
			[0x1F600, '\\x{1F600}'],
			[0x1F600, '\\x{1F600}', fn($output) => $output->hexFormat = HexFormat::UpperCase],
			[0x1F600, '\\x{1f600}', fn($output) => $output->hexFormat = HexFormat::LowerCase],
			[0x120000, new ValueError('Value 1179648 is out of bounds (0..1114111)')]
		];
	}
}