<?php

/**
* @package   s9e\RegexpBuilder
* @copyright Copyright (c) 2016 The s9e Authors
* @license   http://www.opensource.org/licenses/mit-license.php The MIT License
*/
namespace s9e\RegexpBuilder\Passes;

class MergeSuffix extends AbstractPass
{
	/**
	* {@inheritdoc}
	*/
	protected function processStrings(array $strings)
	{
		if (count($strings) < 2)
		{
			return $strings;
		}

		$newString = [];
		while ($this->hasMatchingSuffix($strings))
		{
			array_unshift($newString, end($strings[0]));
			$i = count($strings);
			while (--$i >= 0)
			{
				array_pop($strings[$i]);
			}
		}
		if (empty($newString))
		{
			return $strings;
		}

		array_unshift($newString, $strings);

		return [$newString];
	}

	/**
	* Test whether all given strings have the same last element
	*
	* @param  array[] $strings
	* @return bool
	*/
	protected function hasMatchingSuffix(array $strings)
	{
		$suffix = end($strings[1]);
		foreach ($strings as $string)
		{
			if (end($string) !== $suffix)
			{
				return false;
			}
		}

		return ($suffix !== false);
	}
}