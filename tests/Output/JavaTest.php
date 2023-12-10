<?php declare(strict_types=1);

namespace s9e\RegexpBuilder\Tests\Output;

use PHPUnit\Framework\Attributes\CoversClass;
use ValueError;
use s9e\RegexpBuilder\Output\HexFormat;

#[CoversClass('s9e\RegexpBuilder\Output\AbstractOutput')]
#[CoversClass('s9e\RegexpBuilder\Output\Java')]
#[CoversClass('s9e\RegexpBuilder\Output\PrintableAscii')]
class JavaTest extends AbstractTestClass
{
	public static function getOutputBodyTests()
	{
		return [
			[ord("\n"), '\\n'],
			[ord("\r"), '\\r'],
			[ord("\t"), '\\t'],
			[ord("\f"), '\\f'],
			[ord("\e"), '\\e'],
			[0x07, '\\a'],
			[92, '\\\\'],
			[42, '\\*'],
			[102, 'f'],
			[0xC3, '\\xC3'],
			[0xC3, '\\xC3', fn($output) => $output->hexFormat = HexFormat::UpperCase],
			[0xC3, '\\xc3', fn($output) => $output->hexFormat = HexFormat::LowerCase],
			[0x2026, '\\u2026'],
			[0xFE0F, '\\uFE0F'],
			[0xFE0F, '\\uFE0F', fn($output) => $output->hexFormat = HexFormat::UpperCase],
			[0xFE0F, '\\ufe0f', fn($output) => $output->hexFormat = HexFormat::LowerCase],
			[0x1F600, '\\x{1F600}'],
			[0x1F600, '\\x{1F600}', fn($output) => $output->hexFormat = HexFormat::UpperCase],
			[0x1F600, '\\x{1f600}', fn($output) => $output->hexFormat = HexFormat::LowerCase],
			[0x110000, new ValueError('Value 1114112 is out of bounds (0..1114111)')]
		];
	}

	public static function getOutputClassAtomTests()
	{
		return [
			[ord("\n"), '\\n'],
			[ord("\r"), '\\r'],
			[ord("\t"), '\\t'],
			[ord("\f"), '\\f'],
			[ord("\e"), '\\e'],
			[0x07, '\\a'],
			[92, '\\\\'],
			[42, '*'],
			[102, 'f'],
			[0xC3, '\\xC3'],
			[0xC3, '\\xC3', fn($output) => $output->hexFormat = HexFormat::UpperCase],
			[0xC3, '\\xc3', fn($output) => $output->hexFormat = HexFormat::LowerCase],
			[0x2026, '\\u2026'],
			[0xFE0F, '\\uFE0F'],
			[0xFE0F, '\\uFE0F', fn($output) => $output->hexFormat = HexFormat::UpperCase],
			[0xFE0F, '\\ufe0f', fn($output) => $output->hexFormat = HexFormat::LowerCase],
			[0x1F600, '\\x{1F600}'],
			[0x1F600, '\\x{1F600}', fn($output) => $output->hexFormat = HexFormat::UpperCase],
			[0x1F600, '\\x{1f600}', fn($output) => $output->hexFormat = HexFormat::LowerCase],
			[0x110000, new ValueError('Value 1114112 is out of bounds (0..1114111)')]
		];
	}
}