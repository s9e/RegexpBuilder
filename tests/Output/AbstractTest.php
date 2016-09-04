<?php

namespace s9e\RegexpBuilder\Tests\Output;

use Exception;
use PHPUnit_Framework_TestCase;

abstract class AbstractTest extends PHPUnit_Framework_TestCase
{
	/**
	* @dataProvider getOutputTests
	*/
	public function test($original, $expected)
	{
		$className = 's9e\\RegexpBuilder\\Output\\' . preg_replace('(.*\\\\(\\w+)Test$)', '$1', get_class($this));
		$output = new $className;

		if ($expected instanceof Exception)
		{
			$this->setExpectedException(get_class($expected), $expected->getMessage());
		}

		$this->assertSame($expected, $output->output($original));
	}

	abstract public function getOutputTests();
}