<?php

namespace s9e\RegexpBuilder\Tests;

use PHPUnit_Framework_TestCase;
use s9e\RegexpBuilder\Escaper;

/**
* @covers s9e\RegexpBuilder\Escaper
*/
class EscaperTest extends PHPUnit_Framework_TestCase
{
	/**
	* @dataProvider getEscapeCharacterClassTests
	*/
	public function testEscapeCharacterClass($original, $expected, $delimiter = null)
	{
		$escaper = (isset($delimiter)) ? new Escaper($delimiter) : new Escaper;
		$this->assertSame($expected, $escaper->escapeCharacterClass($original));
	}

	public function getEscapeCharacterClassTests()
	{
		return [
			['-', '\\-'],
			['\\', '\\\\'],
			['[', '['],
			['^', '\\^'],
			[']', '\\]'],
			['/', '\\/'],
			['/', '/', '#'],
			['#', '#'],
			['#', '\\#', '#'],
			['(', '('],
			[')', ')'],
			['(', '\\(', '()'],
			[')', '\\)', '()'],
			['|', '|'],
		];
	}

	/**
	* @dataProvider getEscapeLiteralTests
	*/
	public function testEscapeLiteral($original, $expected, $delimiter = null)
	{
		$escaper = (isset($delimiter)) ? new Escaper($delimiter) : new Escaper;
		$this->assertSame($expected, $escaper->escapeLiteral($original));
	}

	public function getEscapeLiteralTests()
	{
		return [
			['$', '\\$'],
			['(', '\\('],
			[')', '\\)'],
			['*', '\\*'],
			['+', '\\+'],
			['.', '\\.'],
			['?', '\\?'],
			['[', '\\]'],
			['\\', '\\\\'],
			['^', '\\^'],
			['{', '\\{'],
			['|', '\\|'],
			['/', '\\/'],
			['/', '/', '#'],
			['#', '#'],
			['#', '\\#', '#'],
		];
	}
}