<?php declare(strict_types=1);

namespace s9e\RegexpBuilder\Tests\Factory;

use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

abstract class AbstractFactoryTestClass extends TestCase
{
	#[DataProvider('getGetBuilderTests')]
	public function testGetBuilder(array $input, string $expected, array $args = [])
	{
		$className = strtr(static::class, ['\\Tests' => '', 'Test' => '']);
		$builder   = call_user_func_array($className . '::getBuilder', $args);

		$this->assertInstanceOf('s9e\\RegexpBuilder\\Builder', $builder);

		$this->assertSame($expected, $builder->build($input));
	}
}