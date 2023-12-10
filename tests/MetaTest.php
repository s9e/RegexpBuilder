<?php declare(strict_types=1);

namespace s9e\RegexpBuilder\Tests;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use ReflectionClass;
use s9e\RegexpBuilder\Meta;

#[CoversClass('s9e\RegexpBuilder\Meta')]
class MetaTest extends TestCase
{
	/**
	* @testdox Invalid expressions throw an exception
	*/
	public function testInvalidExceptionException()
	{
		$this->expectException('ValueError', "Invalid expression '++'");
		(new Meta)->set('++', '++');
	}

	/**
	* @testdox Identical expressions get the same value regardless of input
	*/
	public function testIdenticalOutput()
	{
		$meta  = new Meta;
		$meta->set('*',  '.*');
		$meta->set('.*', '.*');

		$map = $meta->getInputMap();
		$this->assertEquals($map['*'], $map['.*']);
	}

	public function testGetExpression()
	{
		$meta  = new Meta;
		$meta->set('*', '.*');

		$this->assertEquals($meta->getExpression($meta->getInputMap()['*']), '.*');
	}

	#[DataProvider('getPropertiesTests')]
	public function testProperties($properties, $expr)
	{
		$meta  = new Meta;
		$meta->set('x', $expr);

		$value = $meta->getInputMap()['x'];

		$map = [
			'c' => 'isChar',
			'q' => 'isQuantifiable'
		];
		foreach ($map as $c => $methodName)
		{
			$assertMethod = (strpos($properties, $c) === false) ? 'assertFalse' : 'assertTrue';
			$msg          = $methodName . '(' . var_export($expr, true) . ')';

			$this->$assertMethod(Meta::$methodName($value), $msg);
		}
	}

	public static function getPropertiesTests()
	{
		return [
			['cq', '\\w'      ],
			['cq', '\\d'      ],
			['cq', '\\x{2600}'],
			['',   '\\X{2600}'],
			['cq', '\\x41'    ],
			['',   '\\X41'    ],
			['cq', '\\pL'     ],
			['cq', '\\p{^L}'  ],
			['q',  '.'        ],
			['q',  '\\R'      ],
			['q',  '[0-9]'    ],
			['',   '[0-9]+'   ],
			['',   '.*'       ],
			['',   'xx'       ],
			['',   '^'        ],
			['',   '$'        ],
		];
	}

	#[DataProvider('getSingleCharacterTests')]
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