<?php

namespace s9e\RegexpBuilder\Tests\Output;

use InvalidArgumentException;

/**
* @covers s9e\RegexpBuilder\Output\Utf8
*/
class Utf8Test extends AbstractTest
{
	public function getOutputTests()
	{
		return [
			[ord("\n"), "\n"],
			[ord("\r"), "\r"],
			[ord("\t"), "\t"],
			[92, '\\'],
			[42, '*'],
			[102, 'f'],
			[0xC3, utf8_encode("\xC3")],
			[0x2026, "\xE2\x80\xA6"],
			[0x1F600, "\xF0\x9F\x98\x80"],
			[0x120000, new InvalidArgumentException('Invalid UTF-8 codepoint 0x120000')]
		];
	}
}