<?php

namespace s9e\RegexpBuilder\Tests\Passes;

use PHPUnit\Framework\TestCase;
use s9e\RegexpBuilder\Passes\Recurse;

/**
* @covers s9e\RegexpBuilder\Passes\AbstractPass
* @covers s9e\RegexpBuilder\Passes\Recurse
*/
class RecurseTest extends TestCase
{
	public function test()
	{
		$mock = $this->getMockBuilder('s9e\\RegexpBuilder\\Runner')
		             ->disableOriginalConstructor()
		             ->getMock();

		$mock->expects($this->at(0))
		     ->method('run')
		     ->with([0, 1, 2])
		     ->will($this->returnValue([0, 1]));

		$mock->expects($this->at(1))
		     ->method('run')
		     ->with([1, 2, 3])
		     ->will($this->returnValue([2, 3]));

		$pass = new Recurse($mock);
		$this->assertSame(
			[[[0, 1], [2, 3]]],
			$pass->run([[[0, 1, 2], [1, 2, 3]]])
		);
	}
}