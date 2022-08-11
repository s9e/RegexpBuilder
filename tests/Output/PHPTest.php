<?php declare(strict_types=1);

namespace s9e\RegexpBuilder\Tests\Output;

use InvalidArgumentException;
use s9e\RegexpBuilder\OutputContext as Context;
use s9e\RegexpBuilder\Output\PHP;

/**
* @covers s9e\RegexpBuilder\Output\AbstractOutput
* @covers s9e\RegexpBuilder\Output\PCRE2
* @covers s9e\RegexpBuilder\Output\PHP
* @covers s9e\RegexpBuilder\Output\PrintableAscii
*/
class PHPTest extends AbstractTest
{
	public function testEnableExtendedMore()
	{
		$output = new PHP;
		$this->assertEquals(' ', $output->output(ord(' '), Context::Body));
		$this->assertEquals('#', $output->output(ord('#'), Context::Body));
		$this->assertEquals(' ', $output->output(ord(' '), Context::ClassAtom));
		$this->assertEquals('#', $output->output(ord('#'), Context::ClassAtom));

		$output->enableExtendedMore();
		$this->assertEquals('\\ ', $output->output(ord(' '), Context::Body));
		$this->assertEquals('\\#', $output->output(ord('#'), Context::Body));
		$this->assertEquals('\\ ', $output->output(ord(' '), Context::ClassAtom));
		$this->assertEquals('\\#', $output->output(ord('#'), Context::ClassAtom));
	}

	/**
	* @dataProvider getInvalidDelimiterTests
	*/
	public function testInvalidDelimiter($delimiter)
	{
		$this->expectException('ValueError');
		$this->expectExceptionMessage('Delimiter must not be alphanumeric, backslash, or NUL');

		(new PHP)->setDelimiter($delimiter);
	}

	public function getInvalidDelimiterTests()
	{
		return [
			["\0"],
			['a'],
			['A'],
			['\\'],
		];
	}

	public function getOutputBodyTests()
	{
		return [
			[ord("\n"), '\\n'],
			[ord("\r"), '\\r'],
			[ord("\t"), '\\t'],
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