<?php declare(strict_types=1);

namespace s9e\RegexpBuilder\Tests;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;
use s9e\RegexpBuilder\Runner;

#[CoversClass('s9e\RegexpBuilder\Runner')]
class RunnerTest extends TestCase
{
	public function testRun()
	{
		$original = [[1, 2], [1, 3]];
		$expected = [[1, [[2], [3]]]];

		$mock = $this->getMockBuilder('s9e\RegexpBuilder\Passes\PassInterface')->getMock();
		$mock->expects($this->once())
		     ->method('run')
		     ->with($original)
		     ->will($this->returnValue($expected));

		$runner = new Runner;
		$runner->addPass($mock);

		$this->assertSame($expected, $runner->run($original));
	}
}