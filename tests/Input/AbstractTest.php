<?php declare(strict_types=1);

namespace s9e\RegexpBuilder\Tests\Input;

use Exception;
use PHPUnit\Framework\TestCase;

abstract class AbstractTest extends TestCase
{
	/**
	* @dataProvider getInputTests
	*/
	public function test($original, $expected, callable $setup = null)
	{
		$className = 's9e\\RegexpBuilder\\Input\\' . preg_replace('(.*\\\\(\\w+)Test$)', '$1', get_class($this));
		$input = new $className;
		if (isset($setup))
		{
			$setup($input);
		}

		if ($expected instanceof Exception)
		{
			$this->expectException(get_class($expected), $expected->getMessage());
		}

		$this->assertSame($expected, $input->split($original));
	}

	abstract public function getInputTests();
}