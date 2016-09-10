<?php

namespace s9e\RegexpBuilder\Tests\Output;

use InvalidArgumentException;

/**
* @covers s9e\RegexpBuilder\Output\BaseImplementation
* @covers s9e\RegexpBuilder\Output\Bytes
*/
class BytesTest extends AbstractTest
{
	public function getOutputTests()
	{
		return [
			[92, '\\'],
			[42, '*'],
			[102, 'f'],
			[0xC3, "\xC3"],
			[0xA9, "\xA9"],
			[0xFF, "\xFF"],
			[0x100, new InvalidArgumentException('Value 256 is out of bounds (0..255)')]
		];
	}
}