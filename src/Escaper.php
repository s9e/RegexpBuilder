<?php

/**
* @package   s9e\RegexpBuilder
* @copyright Copyright (c) 2016-2020 The s9e authors
* @license   http://www.opensource.org/licenses/mit-license.php The MIT License
*/
namespace s9e\RegexpBuilder;

class Escaper
{
	/**
	* @var array Characters to escape in a character class
	*/
	public $inCharacterClass = ['-' => '\\-', '\\' => '\\\\', ']' => '\\]', '^' => '\\^'];

	/**
	* @var array Characters to escape outside of a character class
	*/
	public $inLiteral = [
		'$'  => '\\$',  '(' => '\\(', ')' => '\\)', '*' => '\\*',
		'+'  => '\\+',  '.' => '\\.', '?' => '\\?', '[' => '\\[',
		'\\' => '\\\\', '^' => '\\^', '{' => '\\{', '|' => '\\|'
	];

	/**
	* @param string $delimiter Delimiter used in the final regexp
	*/
	public function __construct($delimiter = '/')
	{
		foreach (str_split($delimiter, 1) as $char)
		{
			$this->inCharacterClass[$char] = '\\' . $char;
			$this->inLiteral[$char]        = '\\' . $char;
		}
	}

	/**
	* Escape given character to be used in a character class
	*
	* @param  string $char Original character
	* @return string       Escaped character
	*/
	public function escapeCharacterClass($char)
	{
		return (isset($this->inCharacterClass[$char])) ? $this->inCharacterClass[$char] : $char;
	}

	/**
	* Escape given character to be used outside of a character class
	*
	* @param  string $char Original character
	* @return string       Escaped character
	*/
	public function escapeLiteral($char)
	{
		return (isset($this->inLiteral[$char])) ? $this->inLiteral[$char] : $char;
	}
}