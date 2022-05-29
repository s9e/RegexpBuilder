<?php declare(strict_types=1);

namespace s9e\RegexpBuilder\Tests;

use PHPUnit\Framework\TestCase;
use ReflectionClass;
use s9e\RegexpBuilder\Meta;

/**
* @covers s9e\RegexpBuilder\Meta
*/
class MetaTest extends TestCase
{
	/**
	* @testdox Invalid expressions throw an exception
	*/
	public function testInvalidExceptionException()
	{
		$this->expectException('InvalidArgumentException', "Invalid expression '++'");
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

	/**
	* @testdox Meta-characters properties
	* @dataProvider getPropertiesTests
	*/
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

	public function getPropertiesTests()
	{
		return [
			['cq', '\\w'      ],
			['cq', '\\d'      ],
			['cq', '\\x{2600}'],
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

	public function getSingleCharacterTests()
	{
		return [
			['b',   ord('b')],
			['\\.', ord('.')],
			['\\b', -1 * (1 << count((new ReflectionClass(Meta::class))->getConstants()))],
		];
	}
}