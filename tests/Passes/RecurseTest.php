<?php declare(strict_types=1);

namespace s9e\RegexpBuilder\Tests\Passes;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;
use s9e\RegexpBuilder\Passes\Recurse;

#[CoversClass('s9e\RegexpBuilder\Passes\AbstractPass')]
#[CoversClass('s9e\RegexpBuilder\Passes\Recurse')]
class RecurseTest extends TestCase
{
	public function test()
	{
		$mock = $this->getMockBuilder('s9e\\RegexpBuilder\\Runner')
		             ->disableOriginalConstructor()
		             ->getMock();

		$mock->expects($this->exactly(2))
		     ->method('run')
		     ->willReturnCallback(fn($in) => array_slice($in, 1));

		$pass = new Recurse($mock);
		$this->assertSame(
			[[[1, 2], [3, 4]]],
			$pass->run([[[0, 1, 2], [2, 3, 4]]])
		);
	}
}