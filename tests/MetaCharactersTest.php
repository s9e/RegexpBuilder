<?php declare(strict_types=1);

namespace s9e\RegexpBuilder\Tests;

use PHPUnit\Framework\TestCase;
use s9e\RegexpBuilder\Input\Utf8;
use s9e\RegexpBuilder\MetaCharacters;

/**
* @covers s9e\RegexpBuilder\MetaCharacters
*/
class MetaCharactersTest extends TestCase
{
	protected function getMeta(array $map = [])
	{
		$meta = new MetaCharacters(new Utf8, $map);
		foreach ($map as $char => $expr)
		{
			$meta->add($char, $expr);
		}

		return $meta;
	}

	/**
	* @testdox Using multiple chars as meta-character throws an exception
	*/
	public function testMultipleCharsException()
	{
		$this->expectException('InvalidArgumentException', 'Meta-characters must be represented by exactly one character');
		$this->getMeta(['xx' => 'x']);
	}

	/**
	* @testdox Invalid expressions throw an exception
	*/
	public function testInvalidExceptionException()
	{
		$this->expectException('InvalidArgumentException', "Invalid expression '+++'");
		$this->getMeta(['x' => '+++']);
	}

	/**
	* @testdox getExpression() returns the original expression that matches the given meta value
	*/
	public function testGetExpression()
	{
		$meta    = $this->getMeta(["\0" => 'foo', "\1" => 'bar']);
		$strings = $meta->replaceMeta([[0, 1]]);

		$this->assertEquals('foo', $meta->getExpression($strings[0][0]));
		$this->assertEquals('bar', $meta->getExpression($strings[0][1]));
	}

	/**
	* @testdox getExpression() throws an exception on unknown meta values
	*/
	public function testGetExpressionException()
	{
		$this->expectException('InvalidArgumentException', 'Invalid meta value -1');
		$this->getMeta([])->getExpression(-1);
	}

	/**
	* @testdox Meta-characters properties
	* @dataProvider getPropertiesTests
	*/
	public function testProperties($properties, $expr)
	{
		$meta    = $this->getMeta(["\0" => $expr]);
		$strings = $meta->replaceMeta([[0]]);

		$map = [
			'c' => 'isChar',
			'q' => 'isQuantifiable'
		];
		foreach ($map as $c => $methodName)
		{
			$assertMethod = (strpos($properties, $c) === false) ? 'assertFalse' : 'assertTrue';
			$msg          = $methodName . '(' . var_export($expr, true) . ')';

			$this->$assertMethod(MetaCharacters::$methodName($strings[0][0]), $msg);
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
}