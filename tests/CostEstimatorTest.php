<?php declare(strict_types=1);

namespace s9e\RegexpBuilder\Tests;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use s9e\RegexpBuilder\CostEstimator;

#[CoversClass('s9e\RegexpBuilder\CostEstimator')]
class CostEstimatorTest extends TestCase
{
	#[DataProvider('getEstimateStringTests')]
	public function testEstimateString(int $cost, array $string)
	{
		$this->assertEquals($cost, (new CostEstimator)->estimateString($string));
	}

	public static function getEstimateStringTests()
	{
		return [
			// B
			[1, [66]],
			// BBB
			[3, [66, 66, 66]],
			// BB\n
			[4, [66, 66, 10]],
			// Pokémon
			[8, [80, 111, 107, 233, 109, 111, 110]],
			// √
			[3, [0x221A]],
			// 😀
			[4, [0x1F600]],
		];
	}

	#[DataProvider('getEstimateStringsTests')]
	public function testEstimateStrings(int $cost, array $string)
	{
		$this->assertEquals($cost, (new CostEstimator)->estimateStrings($string));
	}

	public static function getEstimateStringsTests()
	{
		return [
			// B
			[1, [[66]]],
			// [AB]
			[4, [[65], [66]]],
		];
	}
}