<?php declare(strict_types=1);

namespace s9e\RegexpBuilder\Tests\Output;

use InvalidArgumentException;

/**
* @covers s9e\RegexpBuilder\Output\BaseImplementation
* @covers s9e\RegexpBuilder\Output\Java
* @covers s9e\RegexpBuilder\Output\PrintableAscii
*/
class JavaTest extends AbstractTest
{
	public function getOutputTests()
	{
		return [
			[ord("\n"), '\\n'],
			[ord("\r"), '\\r'],
			[ord("\t"), '\\t'],
			[ord("\f"), '\\f'],
			[ord("\e"), '\\e'],
			[0x07, '\\a'],
			[92, '\\'],
			[42, '*'],
			[102, 'f'],
			[0xC3, '\\xC3'],
			[0xC3, '\\xC3', ['case' => 'upper']],
			[0xC3, '\\xc3', ['case' => 'lower']],
			[0x2026, '\\u2026'],
			[0xFE0F, '\\uFE0F'],
			[0xFE0F, '\\uFE0F', ['case' => 'upper']],
			[0xFE0F, '\\ufe0f', ['case' => 'lower']],
			[0x1F600, '\\x{1F600}'],
			[0x1F600, '\\x{1F600}', ['case' => 'upper']],
			[0x1F600, '\\x{1f600}', ['case' => 'lower']],
			[0x110000, new InvalidArgumentException('Value 1114112 is out of bounds (0..1114111)')]
		];
	}
}