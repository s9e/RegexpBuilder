<?php declare(strict_types=1);

namespace s9e\RegexpBuilder\Tests;

use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use s9e\RegexpBuilder\CostEstimator;

/**
* @covers s9e\RegexpBuilder\CostEstimator
*/
class CostEstimatorTest extends TestCase
{
	/**
	* @dataProvider getEstimateStringTests
	*/
	public function testEstimateString(int $cost, array $string)
	{
		$this->assertEquals($cost, (new CostEstimator)->estimateString($string));
	}

	public static function getPropertiesTests()
	{
		return [
			// B
			[1, [66]],
			// BBB
			[3, [66, 66, 66]],
			// BB\n
			[4, [66, 66, 10]],
		];
	}

	/**
	* @testdox Single character expressions are mapped to the character's codepoint directly if possible
	* @dataProvider getSingleCharacterTests
	*/
	public function testSingleCharacter(string $expr, int $value)
	{
		$meta  = new Meta;
		$meta->set('x', $expr);

		$this->assertEquals(['x' => $value], $meta->getInputMap());
	}

	public static function getSingleCharacterTests()
	{
		return [
			['b',   ord('b')],
			['\\.', ord('.')],
			['\\b', -1 * (1 << count((new ReflectionClass(Meta::class))->getConstants()))],
		];
	}

	/**
	* @testdox Constructor accepts an iterable map
	*/
	public function testConstructor()
	{
		$meta = new Meta(['x' => '\\b']);

		$this->assertArrayHasKey('x', $meta->getInputMap());
	}

	/**
	* @testdox Constructor accepts numbers as keys
	*/
	public function testConstructorNumbersAsKeys()
	{
		$meta = new Meta(['0' => '\\b']);

		$this->assertArrayHasKey('0', $meta->getInputMap());
	}
}