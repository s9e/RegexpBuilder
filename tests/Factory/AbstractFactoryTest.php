<?php declare(strict_types=1);

namespace s9e\RegexpBuilder\Tests\Factory;

use PHPUnit\Framework\TestCase;

abstract class AbstractFactoryTest extends TestCase
{
	/**
	* @dataProvider getGetBuilderTests
	*/
	public function testGetBuilder(array $input, string $expected, array $args = [])
	{
		$className = strtr(get_class($this), ['\\Tests' => '', 'Test' => '']);
		$builder   = call_user_func_array($className . '::getBuilder', $args);

		$this->assertInstanceOf('s9e\\RegexpBuilder\\Builder', $builder);

		$this->assertSame($expected, $builder->build($input));
	}
}