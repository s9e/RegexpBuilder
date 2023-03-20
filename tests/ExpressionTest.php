<?php declare(strict_types=1);

namespace s9e\RegexpBuilder\Tests;

use PHPUnit\Framework\TestCase;
use s9e\RegexpBuilder\Expression;

/**
* @covers s9e\RegexpBuilder\Expression
*/
class ExpressionTest extends TestCase
{
	public function testIsStringable()
	{
		$this->assertSame('string', (string) new Expression('string'));
	}
}