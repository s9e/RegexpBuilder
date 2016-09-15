<?php

/**
* @package   s9e\RegexpBuilder
* @copyright Copyright (c) 2016 The s9e Authors
* @license   http://www.opensource.org/licenses/mit-license.php The MIT License
*/
namespace s9e\RegexpBuilder\Input;

use InvalidArgumentException;

class Utf8 implements InputInterface
{
	/**
	* {@inheritdoc}
	*/
	public function split($string)
	{
		if (preg_match_all('(.)us', $string, $matches) === false)
		{
			throw new InvalidArgumentException('Invalid UTF-8 string');
		}

		return $this->charsToCodepoints($matches[0]);
	}

	/**
	* Convert a list of UTF-8 characters to a list of Unicode codepoint
	*
	* @param  string[]  $chars
	* @return integer[]
	*/
	protected function charsToCodepoints(array $chars)
	{
		return array_map([$this, 'cp'], $chars);
	}

	/**
	* Compute and return the Unicode codepoint for given UTF-8 char
	*
	* @param  string  $char UTF-8 char
	* @return integer
	*/
	protected function cp($char)
	{
		$cp = ord($char[0]);
		if ($cp >= 0xF0)
		{
			$cp = ($cp << 18) + (ord($char[1]) << 12) + (ord($char[2]) << 6) + ord($char[3]) - 0x3C82080;
		}
		elseif ($cp >= 0xE0)
		{
			$cp = ($cp << 12) + (ord($char[1]) << 6) + ord($char[2]) - 0xE2080;
		}
		elseif ($cp >= 0xC0)
		{
			$cp = ($cp << 6) + ord($char[1]) - 0x3080;
		}

		return $cp;
	}
}