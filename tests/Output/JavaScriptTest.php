<?php

namespace s9e\RegexpBuilder\Tests\Output;

use InvalidArgumentException;

/**
* @covers s9e\RegexpBuilder\Output\BaseImplementation
* @covers s9e\RegexpBuilder\Output\JavaScript
* @covers s9e\RegexpBuilder\Output\PrintableAscii
*/
class JavaScriptTest extends AbstractTest
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
			[0x2026, '\\u2026'],
			[0x1F600, '\\u{1F600}'],
			[0x110000, new InvalidArgumentException('Value 1114112 is out of bounds (0..1114111)')]
		];
	}
}