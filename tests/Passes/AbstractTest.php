<?php declare(strict_types=1);

namespace s9e\RegexpBuilder\Tests\Passes;

use PHPUnit\Framework\TestCase;

abstract class AbstractTest extends TestCase
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