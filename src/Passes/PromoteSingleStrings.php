<?php

/**
* @package   s9e\RegexpBuilder
* @copyright Copyright (c) 2016-2018 The s9e Authors
* @license   http://www.opensource.org/licenses/mit-license.php The MIT License
*/
namespace s9e\RegexpBuilder\Passes;

/**
* Replaces alternations that only contain one string to allow other passes to replace
* (?:a0?x|bx) with (?:a0?|b)x
*/
class PromoteSingleStrings extends AbstractPass
{
	/**
	* {@inheritdoc}
	*/
	protected function runPass(array $strings)
	{
		return array_map([$this, 'promoteSingleStrings'], $strings);
	}

	/**
	* Promote single strings found inside given string
	*
	* @param  array $string Original string
	* @return array         Modified string
	*/
	protected function promoteSingleStrings(array $string)
	{
		$newString = [];
		foreach ($string as $element)
		{
			if (is_array($element) && count($element) === 1)
			{
				$newString = array_merge($newString, $element[0]);
			}
			else
			{
				$newString[] = $element;
			}
		}

		return $newString;
	}
}