<?php

namespace s9e\RegexpBuilder\Tests\Passes;

use PHPUnit_Framework_TestCase;

abstract class AbstractTest extends PHPUnit_Framework_TestCase
{
	/**
	* @dataProvider getPassTests
	*/
	public function test($original, $expected)
	{
		$this->assertSame($expected, $this->getPassInstance()->run($original));
	}

	public function getPassInstance()
	{
		$className = 's9e\\RegexpBuilder\\Passes\\' . preg_replace('(.*\\\\(\\w+)Test$)', '$1', get_class($this));

		return new $className;
	}

	abstract public function getPassTests();
}