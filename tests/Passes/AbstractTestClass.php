<?php declare(strict_types=1);

namespace s9e\RegexpBuilder\Tests\Passes;

use PHPUnit\Framework\TestCase;
use s9e\RegexpBuilder\Meta;

abstract class AbstractTestClass extends TestCase
{
	/**
	* @dataProvider getPassTests
	*/
	public function test($original, $expected)
	{
		$this->assertSame($expected, $this->getPassInstance()->run($original));
	}

	public static function getPassInstance()
	{
		$className = 's9e\\RegexpBuilder\\Passes\\' . preg_replace('(.*\\\\(\\w+)Test$)', '$1', static::class);

		return new $className;
	}

	abstract public static function getPassTests();

	protected static function getMetaValue(string $expr): int
	{
		$meta = new Meta;
		$meta->set('x', $expr);

		return $meta->getInputMap()['x'];
	}
}