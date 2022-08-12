<?php declare(strict_types=1);

namespace s9e\RegexpBuilder\Tests\Output;

use ValueError;

/**
* @covers s9e\RegexpBuilder\Output\AbstractOutput
* @covers s9e\RegexpBuilder\Output\Bytes
*/
class BytesTest extends AbstractTest
{
	public function getOutputBodyTests()
	{
		return [
			[92, '\\\\'],
			[42, '\\*'],
			[102, 'f'],
			[0xC3, "\xC3"],
			[0xA9, "\xA9"],
			[0xFF, "\xFF"],
			[0x100, new ValueError('Value 256 is out of bounds (0..255)')]
		];
	}

	public function getOutputClassAtomTests()
	{
		return [
			[92, '\\\\'],
			[42, '*'],
			[102, 'f'],
			[0xC3, "\xC3"],
			[0xA9, "\xA9"],
			[0xFF, "\xFF"],
			[0x100, new ValueError('Value 256 is out of bounds (0..255)')]
		];
	}
}