<?php declare(strict_types=1);

namespace s9e\RegexpBuilder\Tests\Output;

use InvalidArgumentException;

/**
* @covers s9e\RegexpBuilder\Output\BaseImplementation
* @covers s9e\RegexpBuilder\Output\PHP
* @covers s9e\RegexpBuilder\Output\PrintableAscii
*/
class PHPTest extends AbstractTest
{
	public function getOutputTests()
	{
		return [
			[ord("\n"), '\\n'],
			[ord("\r"), '\\r'],
			[ord("\t"), '\\t'],
			[92, '\\'],
			[42, '*'],
			[102, 'f'],
			[0xC3, '\\xC3'],
			[0xC3, '\\xC3', ['case' => 'upper']],
			[0xC3, '\\xc3', ['case' => 'lower']],
			[0x2026, '\\x{2026}'],
			[0xFE0F, '\\x{FE0F}'],
			[0xFE0F, '\\x{FE0F}', ['case' => 'upper']],
			[0xFE0F, '\\x{fe0f}', ['case' => 'lower']],
			[0x1F600, '\\x{1F600}'],
			[0x1F600, '\\x{1F600}', ['case' => 'upper']],
			[0x1F600, '\\x{1f600}', ['case' => 'lower']],
			[0x120000, new InvalidArgumentException('Value 1179648 is out of bounds (0..1114111)')]
		];
	}
}