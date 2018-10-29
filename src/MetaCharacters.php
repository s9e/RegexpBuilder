<?php

/**
* @package   s9e\RegexpBuilder
* @copyright Copyright (c) 2016-2018 The s9e Authors
* @license   http://www.opensource.org/licenses/mit-license.php The MIT License
*/
namespace s9e\RegexpBuilder;

use InvalidArgumentException;
use s9e\RegexpBuilder\Input\InputInterface;

class MetaCharacters
{
	/**
	* @const Bit value that indicates whether a meta-character represents a single character usable
	*        in a character class
	*/
	const IS_CHAR = 1;

	/**
	* @const Bit value that indicates whether a meta-character represents a quantifiable expression
	*/
	const IS_QUANTIFIABLE = 2;

	/**
	* @var array Map of meta values and the expression they represent
	*/
	protected $exprs = [];

	/**
	* @var InputInterface
	*/
	protected $input;

	/**
	* @var array Map of meta-characters' codepoints and their value
	*/
	protected $meta = [];

	/**
	* @param InputInterface $input
	*/
	public function __construct(InputInterface $input)
	{
		$this->input = $input;
	}

	/**
	* Add a meta-character to the list
	*
	* @param  string $char Meta-character
	* @param  string $expr Regular expression
	* @return void
	*/
	public function add($char, $expr)
	{
		$split = $this->input->split($char);
		if (count($split) !== 1)
		{
			throw new InvalidArgumentException('Meta-characters must be represented by exactly one character');
		}
		if (@preg_match('(' . $expr . ')u', '') === false)
		{
			throw new InvalidArgumentException("Invalid expression '" . $expr . "'");
		}

		$inputValue = $split[0];
		$metaValue  = $this->computeValue($expr);

		$this->exprs[$metaValue] = $expr;
		$this->meta[$inputValue] = $metaValue;
	}

	/**
	* Get the expression associated with a meta value
	*
	* @param  integer $metaValue
	* @return string
	*/
	public function getExpression($metaValue)
	{
		if (!isset($this->exprs[$metaValue]))
		{
			throw new InvalidArgumentException('Invalid meta value ' . $metaValue);
		}

		return $this->exprs[$metaValue];
	}

	/**
	* Return whether a given value represents a single character usable in a character class
	*
	* @param  integer $value
	* @return bool
	*/
	public static function isChar($value)
	{
		return ($value >= 0 || ($value & self::IS_CHAR));
	}

	/**
	* Return whether a given value represents a quantifiable expression
	*
	* @param  integer $value
	* @return bool
	*/
	public static function isQuantifiable($value)
	{
		return ($value >= 0 || ($value & self::IS_QUANTIFIABLE));
	}

	/**
	* Replace values from meta-characters in a list of strings with their meta value
	*
	* @param  array[] $strings
	* @return array[]
	*/
	public function replaceMeta(array $strings)
	{
		foreach ($strings as &$string)
		{
			foreach ($string as &$value)
			{
				if (isset($this->meta[$value]))
				{
					$value = $this->meta[$value];
				}
			}
		}

		return $strings;
	}

	/**
	* Compute and return a value for given expression
	*
	* Values are meant to be a unique negative integer. The last 2 bits indicate whether the
	* expression is quantifiable and/or represents a single character.
	*
	* @param  string  $expr Regular expression
	* @return integer
	*/
	protected function computeValue($expr)
	{
		$properties = [
			'exprIsChar'         => self::IS_CHAR,
			'exprIsQuantifiable' => self::IS_QUANTIFIABLE
		];
		$value = (1 + count($this->meta)) * -pow(2, count($properties));
		foreach ($properties as $methodName => $bitValue)
		{
			if ($this->$methodName($expr))
			{
				$value |= $bitValue;
			}
		}

		return $value;
	}

	/**
	* Test whether given expression represents a single character usable in a character class
	*
	* @param  string $expr
	* @return bool
	*/
	protected function exprIsChar($expr)
	{
		$regexps = [
			// Escaped literal or escape sequence such as \w but not \R
			'(^\\\\[adefhnrstvwDHNSVW\\W]$)D',

			// Unicode properties such as \pL or \p{Lu}
			'(^\\\\p(?:.|\\{[^}]+\\})$)Di',

			// An escape sequence such as \x1F or \x{2600}
			'(^\\\\x(?:[0-9a-f]{2}|\\{[^}]+\\})$)Di'
		];

		return $this->matchesAny($expr, $regexps);
	}

	/**
	* Test whether given expression is quantifiable
	*
	* @param  string $expr
	* @return bool
	*/
	protected function exprIsQuantifiable($expr)
	{
		$regexps = [
			// A dot or \R
			'(^(?:\\.|\\\\R)$)D',

			// A character class
			'(^\\[\\^?(?:([^\\\\\\]]|\\\\.)(?:-(?-1))?)++\\]$)D'
		];

		return $this->matchesAny($expr, $regexps) || $this->exprIsChar($expr);
	}

	/**
	* Test whether given expression matches any of the given regexps
	*
	* @param  string   $expr
	* @param  string[] $regexps
	* @return bool
	*/
	protected function matchesAny($expr, array $regexps)
	{
		foreach ($regexps as $regexp)
		{
			if (preg_match($regexp, $expr))
			{
				return true;
			}
		}

		return false;
	}
}