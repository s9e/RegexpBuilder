<?php declare(strict_types=1);

namespace s9e\RegexpBuilder\Tests;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use s9e\RegexpBuilder\InputSplitter;
use s9e\RegexpBuilder\Input\InputInterface;
use s9e\RegexpBuilder\Input\Utf8;
use s9e\RegexpBuilder\Meta;

#[CoversClass('s9e\RegexpBuilder\InputSplitter')]
class InputSplitterTest extends TestCase
{
	#[DataProvider('getSplitStringsTests')]
	public function testSplitStrings(array $strings, array $expected, Meta $meta = new Meta, InputInterface $input = new Utf8)
	{
		$actual = (new InputSplitter($input, $meta))->splitStrings($strings);
		$this->assertEquals($expected, $actual);
	}

	public static function getSplitStringsTests()
	{
		return [
			[
				['abc'],
				[[97, 98, 99]]
			],
			[
				['abc', 'bcd'],
				[[97, 98, 99], [98, 99, 100]]
			],
			[
				['abc'],
				[[97, -4, 99]],
				(
					function ()
					{
						$meta = new Meta;
						$meta->set('b', '\\b');

						return $meta;
					}
				)()
			],
			[
				['foolfoofoobar115'],
				[[-4, -8, -12, -16, -20]],
				(
					function ()
					{
						$meta = new Meta;

						$meta->set('fool',   '(*:fool)');
						$meta->set('foo',    '(*:foo)');
						$meta->set('foobar', '(*:foobar)');

						// Make sure that strings made entirely made of digits are handled correctly
						$meta->set('11',     '(*:_11)');
						$meta->set('5',      '(*:_5)');

						return $meta;
					}
				)()
			],
		];
	}
}