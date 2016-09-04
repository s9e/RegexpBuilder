<?php

/**
* @package   s9e\RegexpBuilder
* @copyright Copyright (c) 2016 The s9e Authors
* @license   http://www.opensource.org/licenses/mit-license.php The MIT License
*/
namespace s9e\RegexpBuilder\Passes;

class MergePrefix extends AbstractPass
{
	/**
	* {@inheritdoc}
	*/
	protected function processStrings(array $strings)
	{
		$newStrings = [];
		foreach ($this->getStringsByPrefix($strings) as $prefix => $strings)
		{
			$newStrings[] =  (isset($strings[1])) ? $this->mergeStrings($strings) : $strings[0];
		}

		return $newStrings;
	}

	/**
	* Get the number of leading elements common to all given strings
	*
	* @param  array[] $strings
	* @return integer
	*/
	protected function getPrefixLength(array $strings)
	{
		$len = 0;
		$cnt = count($strings[0]);
		while (++$len < $cnt)
		{
			$value = $strings[0][$len];
			foreach ($strings as $string)
			{
				if (!isset($string[$len]) || $string[$len] !== $value)
				{
					break 2;
				}
			}
		}

		return $len;
	}

	/**
	* Return given strings grouped by their first element
	*
	* NOTE: assumes that this pass is run before the first element of any string could be replaced
	*
	* @param  array[] $strings
	* @return array[]
	*/
	protected function getStringsByPrefix(array $strings)
	{
		$byPrefix = [];
		foreach ($strings as $string)
		{
			$byPrefix[$string[0]][] = $string;
		}

		return $byPrefix;
	}

	/**
	* Merge given strings into a new single string
	*
	* @param  array[] $strings
	* @return array
	*/
	protected function mergeStrings(array $strings)
	{
		$len       = $this->getPrefixLength($strings);
		$newString = array_slice($strings[0], 0, $len);
		foreach ($strings as $string)
		{
			$newString[$len][] = array_slice($string, $len);
		}

		return $newString;
	}
}