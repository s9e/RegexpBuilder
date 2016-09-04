<?php

namespace s9e\RegexpBuilder\Tests\Output;

/**
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
			[0x2026, '\\x{2026}'],
			[0x1F600, '\\x{1F600}']
		];
	}
}