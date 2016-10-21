<?php

namespace s9e\RegexpBuilder\Tests\Input;

/**
* @covers s9e\RegexpBuilder\Input\BaseImplementation
* @covers s9e\RegexpBuilder\Input\Utf8ToSurrogates
*/
class Utf8ToSurrogatesTest extends AbstractTest
{
	public function getInputTests()
	{
		return [
			[
				'',
				[]
			],
			[
				'foo',
				[102, 111, 111]
			],
			[
				'Pokémon',
				[80, 111, 107, 233, 109, 111, 110]
			],
			[
				' 🔆 ',
				[32, 55357, 56582, 32]
			],
		];
	}
}