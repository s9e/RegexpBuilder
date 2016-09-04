<?php

/**
* @package   s9e\RegexpBuilder
* @copyright Copyright (c) 2016 The s9e Authors
* @license   http://www.opensource.org/licenses/mit-license.php The MIT License
*/
namespace s9e\RegexpBuilder\Passes;

class CoalesceSingleCharacterPrefix extends AbstractPass
{
	/**
	* {@inheritdoc}
	*/
	protected function processStrings(array $strings)
	{
		$newStrings = [];
		foreach ($this->getEligibleStrings($strings) as $suffix => $keys)
		{
			if (!isset($keys[1]))
			{
				continue;
			}

			// Create a new string to hold the merged strings and replace the first element with
			// an empty character class
			$newString    = $strings[$keys[0]];
			$newString[0] = [];
			foreach ($keys as $key)
			{
				$newString[0][] = [$strings[$key][0]];
				unset($strings[$key]);
			}
			$newStrings[] = $newString;
		}

		return array_merge($newStrings, $strings);
	}

	/**
	* Get a list of keys of strings eligible to be merged together, grouped by suffix
	*
	* @param  array[] $strings
	* @return array[]
	*/
	protected function getEligibleStrings(array $strings)
	{
		$eligibleStrings = [];
		foreach ($strings as $k => $string)
		{
			if (is_array($string[0]) || !isset($string[1]))
			{
				continue;
			}

			$suffix = serialize(array_slice($string, 1));
			$eligibleStrings[$suffix][] = $k;
		}

		return $eligibleStrings;
	}
}