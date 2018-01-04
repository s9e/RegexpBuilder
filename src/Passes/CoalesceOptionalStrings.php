<?php

/**
* @package   s9e\RegexpBuilder
* @copyright Copyright (c) 2016-2018 The s9e Authors
* @license   http://www.opensource.org/licenses/mit-license.php The MIT License
*/
namespace s9e\RegexpBuilder\Passes;

/**
* Replaces (?:ab?|b)? with a?b?
*/
class CoalesceOptionalStrings extends AbstractPass
{
	/**
	* {@inheritdoc}
	*/
	protected function canRun(array $strings)
	{
		return ($this->isOptional && count($strings) > 1);
	}

	/**
	* {@inheritdoc}
	*/
	protected function runPass(array $strings)
	{
		foreach ($this->getPrefixGroups($strings) as $suffix => $prefixStrings)
		{
			$suffix        = unserialize($suffix);
			$suffixStrings = array_diff_key($strings, $prefixStrings);
			if ($suffix === $this->buildSuffix($suffixStrings))
			{
				$this->isOptional = false;

				return $this->buildCoalescedStrings($prefixStrings, $suffix);
			}
		}

		return $strings;
	}

	/**
	* Build the final list of coalesced strings
	*
	* @param  array[] $prefixStrings
	* @param  array   $suffix
	* @return array[]
	*/
	protected function buildCoalescedStrings(array $prefixStrings, array $suffix)
	{
		$strings = $this->runPass($this->buildPrefix($prefixStrings));
		if (count($strings) === 1 && $strings[0][0][0] === [])
		{
			// If the prefix has been remerged into a list of strings which contains only one string
			// of which the first element is an optional alternations, we only need to append the
			// suffix
			$strings[0][] = $suffix;
		}
		else
		{
			// Put the current list of strings that form the prefix into a new list of strings, of
			// which the only string is composed of our optional prefix followed by the suffix
			array_unshift($strings, []);
			$strings = [[$strings, $suffix]];
		}

		return $strings;
	}

	/**
	* Build the list of strings used as prefix
	*
	* @param  array[] $strings
	* @return array[]
	*/
	protected function buildPrefix(array $strings)
	{
		$prefix = [];
		foreach ($strings as $string)
		{
			// Remove the last element (suffix) of each string before adding it
			array_pop($string);
			$prefix[] = $string;
		}

		return $prefix;
	}

	/**
	* Build a list of strings that matches any given strings or nothing
	*
	* Will unpack groups of single characters
	*
	* @param  array[] $strings
	* @return array[]
	*/
	protected function buildSuffix(array $strings)
	{
		$suffix = [[]];
		foreach ($strings as $string)
		{
			if ($this->isCharacterClassString($string))
			{
				foreach ($string[0] as $element)
				{
					$suffix[] = $element;
				}
			}
			else
			{
				$suffix[] = $string;
			}
		}

		return $suffix;
	}

	/**
	* Get the list of potential prefix strings grouped by identical suffix
	*
	* @param  array[] $strings
	* @return array
	*/
	protected function getPrefixGroups(array $strings)
	{
		$groups = [];
		foreach ($strings as $k => $string)
		{
			if ($this->hasOptionalSuffix($string))
			{
				$groups[serialize(end($string))][$k] = $string;
			}
		}

		return $groups;
	}
}