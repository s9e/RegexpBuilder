<?php declare(strict_types=1);

namespace s9e\RegexpBuilder\Tests\Output;

use Exception;
use PHPUnit\Framework\TestCase;
use s9e\RegexpBuilder\OutputContext as Context;

abstract class AbstractTest extends TestCase
{
	protected function runOutputTest(Context $context, $original, $expected, ?callable $setup = null)
	{
		$className = 's9e\\RegexpBuilder\\Output\\' . preg_replace('(.*\\\\(\\w+)Test$)', '$1', get_class($this));
		$output = new $className;

		if (isset($setup))
		{
			$setup($output);
		}

		if ($expected instanceof Exception)
		{
			$this->expectException(get_class($expected), $expected->getMessage());
		}

		$this->assertSame($expected, $output->output($original, $context));
	}

	/**
	* @dataProvider getOutputBodyTests
	*/
	public function testOutputBody($original, $expected, ?callable $setup = null)
	{
		$this->runOutputTest(Context::Body, $original, $expected, $setup);
	}

	/**
	* @dataProvider getOutputClassAtomTests
	*/
	public function testOutputClassAtom($original, $expected, ?callable $setup = null)
	{
		$this->runOutputTest(Context::ClassAtom, $original, $expected, $setup);
	}

	abstract public function getOutputBodyTests();
	abstract public function getOutputClassAtomTests();
}