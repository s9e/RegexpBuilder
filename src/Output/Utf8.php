<?php

/**
* @package   s9e\RegexpBuilder
* @copyright Copyright (c) 2016-2020 The s9e authors
* @license   http://www.opensource.org/licenses/mit-license.php The MIT License
*/
namespace s9e\RegexpBuilder\Output;

use InvalidArgumentException;

class Utf8 extends BaseImplementation
{
	/** {@inheritdoc} */
	protected $maxValue = 0x10FFFF;

	/**
	* {@inheritdoc}
	*/
	protected function outputValidValue($value)
	{
		if ($value < 0x80)
		{
			return chr($value);
		}
		if ($value < 0x800)
		{
			return chr(0xC0 | ($value >> 6)) . chr(0x80 | ($value & 0x3F));
		}
		if ($value < 0x10000)
		{
			return chr(0xE0 | ($value >> 12))
			     . chr(0x80 | (($value >> 6) & 0x3F))
			     . chr(0x80 | ($value & 0x3F));
		}
		return chr(0xF0 | ($value >> 18))
		     . chr(0x80 | (($value >> 12) & 0x3F))
		     . chr(0x80 | (($value >> 6) & 0x3F))
		     . chr(0x80 | ($value & 0x3F));
	}

	/**
	* {@inheritdoc}
	*/
	protected function validate($value)
	{
		if ($value >= 0xD800 && $value <= 0xDFFF)
		{
			throw new InvalidArgumentException(sprintf('Surrogate 0x%X is not a valid UTF-8 character', $value));
		}

		parent::validate($value);
	}
}