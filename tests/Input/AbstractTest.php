<?php

namespace s9e\RegexpBuilder\Tests\Input;

use Exception;
use PHPUnit_Framework_TestCase;

abstract class AbstractTest extends PHPUnit_Framework_TestCase
{
	/**
	* @dataProvider getInputTests
	*/
	public function test($original, $expected)
	{
		$className = 's9e\\RegexpBuilder\\Input\\' . preg_replace('(.*\\\\(\\w+)Test$)', '$1', get_class($this));
		$input = new $className;

		if ($expected instanceof Exception)
		{
			$this->setExpectedException(get_class($expected), $expected->getMessage());
		}

		$this->assertSame($expected, $input->split($original));
	}

	abstract public function getInputTests();
}