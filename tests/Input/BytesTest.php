<?php declare(strict_types=1);

namespace s9e\RegexpBuilder\Tests\Input;

/**
* @covers s9e\RegexpBuilder\Input\BaseImplementation
* @covers s9e\RegexpBuilder\Input\Bytes
*/
class BytesTest extends AbstractTest
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
				[80, 111, 107, 195, 169, 109, 111, 110]
			],
			[
				' 🔆 ',
				[32, 240, 159, 148, 134, 32]
			],
		];
	}
}