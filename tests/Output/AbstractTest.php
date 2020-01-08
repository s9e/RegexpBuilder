<?php declare(strict_types=1);

namespace s9e\RegexpBuilder\Tests\Output;

use Exception;
use PHPUnit\Framework\TestCase;

abstract class AbstractTest extends TestCase
{
	/**
	* @dataProvider getOutputTests
	*/
	public function test($original, $expected, $options = [])
	{
		$className = 's9e\\RegexpBuilder\\Output\\' . preg_replace('(.*\\\\(\\w+)Test$)', '$1', get_class($this));
		$output = new $className($options);

		if ($expected instanceof Exception)
		{
			$this->expectException(get_class($expected), $expected->getMessage());
		}

		$this->assertSame($expected, $output->output($original));
	}

	abstract public function getOutputTests();
}