<?php declare(strict_types=1);

namespace s9e\RegexpBuilder\Tests\Output;

use InvalidArgumentException;

/**
* @covers s9e\RegexpBuilder\Output\AbstractOutput
* @covers s9e\RegexpBuilder\Output\PrintableAscii
* @covers s9e\RegexpBuilder\Output\RE2
*/
class RE2Test extends AbstractTest
{
	public function getOutputBodyTests()
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
			[0xC3, '\\xC3', fn($output) => $output->useUpperCaseHex()],
			[0xC3, '\\xc3', fn($output) => $output->useLowerCaseHex()],
			[0x2026, '\\x{2026}'],
			[0xFE0F, '\\x{FE0F}'],
			[0xFE0F, '\\x{FE0F}', fn($output) => $output->useUpperCaseHex()],
			[0xFE0F, '\\x{fe0f}', fn($output) => $output->useLowerCaseHex()],
			[0x1F600, '\\x{1F600}'],
			[0x1F600, '\\x{1F600}', fn($output) => $output->useUpperCaseHex()],
			[0x1F600, '\\x{1f600}', fn($output) => $output->useLowerCaseHex()],
			[0x120000, new InvalidArgumentException('Value 1179648 is out of bounds (0..1114111)')]
		];
	}

	public function getOutputClassAtomTests()
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
			[0xC3, '\\xC3', fn($output) => $output->useUpperCaseHex()],
			[0xC3, '\\xc3', fn($output) => $output->useLowerCaseHex()],
			[0x2026, '\\x{2026}'],
			[0xFE0F, '\\x{FE0F}'],
			[0xFE0F, '\\x{FE0F}', fn($output) => $output->useUpperCaseHex()],
			[0xFE0F, '\\x{fe0f}', fn($output) => $output->useLowerCaseHex()],
			[0x1F600, '\\x{1F600}'],
			[0x1F600, '\\x{1F600}', fn($output) => $output->useUpperCaseHex()],
			[0x1F600, '\\x{1f600}', fn($output) => $output->useLowerCaseHex()],
			[0x120000, new InvalidArgumentException('Value 1179648 is out of bounds (0..1114111)')]
		];
	}
}