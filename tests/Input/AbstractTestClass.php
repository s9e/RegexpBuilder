<?php declare(strict_types=1);

namespace s9e\RegexpBuilder\Tests\Input;

use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use Throwable;

abstract class AbstractTestClass extends TestCase
{
	#[DataProvider('getInputTests')]
	public function test($original, $expected, ?callable $setup = null)
	{
		$className = 's9e\\RegexpBuilder\\Input\\' . preg_replace('(.*\\\\(\\w+)Test$)', '$1', static::class);
		$input = new $className;
		if (isset($setup))
		{
			$setup($input);
		}

		if ($expected instanceof Throwable)
		{
			$this->expectException(get_class($expected), $expected->getMessage());
		}

		$this->assertSame($expected, $input->split($original));
	}

	abstract public static function getInputTests();
}