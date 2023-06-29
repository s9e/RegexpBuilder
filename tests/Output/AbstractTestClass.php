<?php declare(strict_types=1);

namespace s9e\RegexpBuilder\Tests\Output;

use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use Throwable;
use s9e\RegexpBuilder\Output\Context;

abstract class AbstractTestClass extends TestCase
{
	protected function runOutputTest(Context $context, $original, $expected, ?callable $setup = null)
	{
		$className = 's9e\\RegexpBuilder\\Output\\' . preg_replace('(.*\\\\(\\w+)Test$)', '$1', static::class);
		$output = new $className;

		if (isset($setup))
		{
			$setup($output);
		}

		if ($expected instanceof Throwable)
		{
			$this->expectException(get_class($expected), $expected->getMessage());
		}

		$this->assertSame($expected, $output->output($original, $context));
	}

	#[DataProvider('getOutputBodyTests')]
	public function testOutputBody($original, $expected, ?callable $setup = null)
	{
		$this->runOutputTest(Context::Body, $original, $expected, $setup);
	}

	#[DataProvider('getOutputClassAtomTests')]
	public function testOutputClassAtom($original, $expected, ?callable $setup = null)
	{
		$this->runOutputTest(Context::ClassAtom, $original, $expected, $setup);
	}

	abstract public static function getOutputBodyTests();
	abstract public static function getOutputClassAtomTests();
}