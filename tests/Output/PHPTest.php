<?php declare(strict_types=1);

namespace s9e\RegexpBuilder\Tests\Output;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use ValueError;
use s9e\RegexpBuilder\Output\Context;
use s9e\RegexpBuilder\Output\HexFormat;
use s9e\RegexpBuilder\Output\PHP;

#[CoversClass('s9e\RegexpBuilder\Output\AbstractOutput')]
#[CoversClass('s9e\RegexpBuilder\Output\PCRE2')]
#[CoversClass('s9e\RegexpBuilder\Output\PHP')]
#[CoversClass('s9e\RegexpBuilder\Output\PrintableAscii')]
class PHPTest extends AbstractTestClass
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

	public function testSetDelimiter()
	{
		$output = new PHP;
		
		$output->setDelimiter('');
		$this->assertEquals('\\(', $output->output(ord('('), Context::Body));
		$this->assertEquals('\\)', $output->output(ord(')'), Context::Body));
		$this->assertEquals('(',   $output->output(ord('('), Context::ClassAtom));
		$this->assertEquals(')',   $output->output(ord(')'), Context::ClassAtom));

		$output->setDelimiter('()');
		$this->assertEquals('\\(', $output->output(ord('('), Context::Body));
		$this->assertEquals('\\)', $output->output(ord(')'), Context::Body));
		$this->assertEquals('\\(', $output->output(ord('('), Context::ClassAtom));
		$this->assertEquals('\\)', $output->output(ord(')'), Context::ClassAtom));
	}

	#[DataProvider('getInvalidDelimiterTests')]
	public function testInvalidDelimiter($delimiter)
	{
		$this->expectException('ValueError');
		$this->expectExceptionMessage('Delimiter must not be alphanumeric, backslash, or NUL');

		(new PHP)->setDelimiter($delimiter);
	}

	public static function getInvalidDelimiterTests()
	{
		return [
			["\0"],
			['a'],
			['A'],
			['6'],
			['\\'],
		];
	}

	public static function getOutputBodyTests()
	{
		return [
			[ord("\n"), '\\n'],
			[ord("\r"), '\\r'],
			[ord("\t"), '\\t'],
			[92, '\\\\'],
			[42, '\\*'],
			[102, 'f'],
			[0xC3, '\\xC3'],
			[0xC3, '\\xC3', fn($output) => $output->hexFormat = HexFormat::UpperCase],
			[0xC3, '\\xc3', fn($output) => $output->hexFormat = HexFormat::LowerCase],
			[0x2026, '\\x{2026}'],
			[0xFE0F, '\\x{FE0F}'],
			[0xFE0F, '\\x{FE0F}', fn($output) => $output->hexFormat = HexFormat::UpperCase],
			[0xFE0F, '\\x{fe0f}', fn($output) => $output->hexFormat = HexFormat::LowerCase],
			[0x1F600, '\\x{1F600}'],
			[0x1F600, '\\x{1F600}', fn($output) => $output->hexFormat = HexFormat::UpperCase],
			[0x1F600, '\\x{1f600}', fn($output) => $output->hexFormat = HexFormat::LowerCase],
			[0x120000, new ValueError('Value 1179648 is out of bounds (0..1114111)')]
		];
	}

	public static function getOutputClassAtomTests()
	{
		return [
			[ord("\n"), '\\n'],
			[ord("\r"), '\\r'],
			[ord("\t"), '\\t'],
			[92, '\\\\'],
			[42, '*'],
			[102, 'f'],
			[0xC3, '\\xC3'],
			[0xC3, '\\xC3', fn($output) => $output->hexFormat = HexFormat::UpperCase],
			[0xC3, '\\xc3', fn($output) => $output->hexFormat = HexFormat::LowerCase],
			[0x2026, '\\x{2026}'],
			[0xFE0F, '\\x{FE0F}'],
			[0xFE0F, '\\x{FE0F}', fn($output) => $output->hexFormat = HexFormat::UpperCase],
			[0xFE0F, '\\x{fe0f}', fn($output) => $output->hexFormat = HexFormat::LowerCase],
			[0x1F600, '\\x{1F600}'],
			[0x1F600, '\\x{1F600}', fn($output) => $output->hexFormat = HexFormat::UpperCase],
			[0x1F600, '\\x{1f600}', fn($output) => $output->hexFormat = HexFormat::LowerCase],
			[0x120000, new ValueError('Value 1179648 is out of bounds (0..1114111)')]
		];
	}
}