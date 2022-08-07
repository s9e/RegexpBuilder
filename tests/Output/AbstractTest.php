<?php declare(strict_types=1);

namespace s9e\RegexpBuilder\Tests\Output;

use Exception;
use PHPUnit\Framework\TestCase;
use s9e\RegexpBuilder\OutputContext as Context;

abstract class AbstractTest extends TestCase
{
	protected function runOutputTest(Context $context, $original, $expected, $options = [])
	{
		$className = 's9e\\RegexpBuilder\\Output\\' . preg_replace('(.*\\\\(\\w+)Test$)', '$1', get_class($this));
		$output = new $className($options);

		if ($expected instanceof Exception)
		{
			$this->expectException(get_class($expected), $expected->getMessage());
		}

		$this->assertSame($expected, $output->output($original, $context));
	}

	/**
	* @dataProvider getOutputBodyTests
	*/
	public function testOutputBody($original, $expected, $options = [])
	{
		$this->runOutputTest(Context::Body, $original, $expected, $options);
	}

	/**
	* @dataProvider getOutputClassAtomTests
	*/
	public function testOutputClassAtom($original, $expected, $options = [])
	{
		$this->runOutputTest(Context::ClassAtom, $original, $expected, $options);
	}

	abstract public function getOutputBodyTests();
	abstract public function getOutputClassAtomTests();
}