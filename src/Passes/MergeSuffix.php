<?php declare(strict_types=1);

/**
* @package   s9e\RegexpBuilder
* @copyright Copyright (c) The s9e authors
* @license   https://opensource.org/licenses/mit-license.php The MIT License
*/
namespace s9e\RegexpBuilder\Passes;

use function array_filter, array_intersect, array_key_first, array_keys, array_pop, array_unshift, count, end, is_array, json_encode, ksort;
use s9e\RegexpBuilder\CostEstimator;

/**
* Replaces (?:aax|bbx) with (?:aa|bb)x
*/
class MergeSuffix extends AbstractPass
{
	/**
	* {@inheritdoc}
	*/
	protected function canRun(array $strings): bool
	{
		return (count($strings) > 1);
	}

	/**
	* {@inheritdoc}
	*/
	protected function runPass(array $strings): array
	{
		$suffixGroups = $this->getSuffixGroups($strings);
		$suffixGroups = $this->filterSuffixGroups($suffixGroups);
		$suffixGroups = $this->expandSuffixes($strings, $suffixGroups);

		print_r($suffixGroups);exit;
	}

	protected function expandGroupSuffix(array $strings, array $suffixGroup): array
	{
		$cnt = count($suffixGroup['suffix']);
		$len = $cnt;
		while ($this->stringsMatchAtLen($strings, $suffixGroup['keys'], $len + 1))
		{
			++$len;
		}

		if ($len > $cnt)
		{
			$suffixGroup['suffix'] = array_slice($strings[array_key_first($suffixGroup['keys'])], -$len);
		}

		return $suffixGroup;
	}

	protected function expandSuffixes(array $strings, array $suffixGroups): array
	{
		foreach ($suffixGroups as $suffixId => $suffixGroup)
		{
			$suffixGroups[$suffixId] = $this->expandGroupSuffix($strings, $suffixGroup);
		}

		return $suffixGroups;
	}

	protected function filterSuffixGroups(array $suffixGroups): array
	{
		return array_filter(
			$suffixGroups,
			fn($suffixGroup) => count($suffixGroup['keys']) > 1
		);
	}

	protected function getSuffixGroups(array $strings): array
	{
		// Collect the indexes of strings that share the same suffix
		$suffixGroups = [];
		foreach ($strings as $k => $string)
		{
			$suffix    = end($string);
			$suffixId  = json_encode($suffix);
			if (!isset($suffixGroups[$suffixId]))
			{
				$suffixGroups[$suffixId] = [
					'keys'   => [],
					'suffix' => [$suffix]
				];
			}
			$suffixGroups[$suffixId]['keys'][$k] = $k;
		}

		// If a suffix is an alternation group, test whether the content of each alternation can
		// be found individually in the list of strings
		foreach ($suffixGroups as $suffixId => &$suffixGroup)
		{
			if (!is_array($suffixGroup['suffix'][0]))
			{
				continue;
			}

			// Test whether all of the elements of the suffix can be found in the list of strings
			// and record their keys
			$suffix          = $suffixGroup['suffix'][0];
			$matchingStrings = array_intersect($strings, $suffix);
			if (count($suffix) === count($matchingStrings))
			{
				$suffixGroup['keys'][array_key_first($matchingStrings)] = array_keys($matchingStrings);
				ksort($suffixGroup['keys']);
			}
		}
		unset($suffixGroup);

		return $suffixGroups;
	}

	/**
	* Remove the last element of every string
	*
	* @param  array[] $strings Original strings
	* @return array[]          Processed strings
	*/
	protected function pop(array $strings): array
	{
		$cnt = count($strings);
		$i   = $cnt;
		while (--$i >= 0)
		{
			array_pop($strings[$i]);
		}

		// Remove empty elements then prepend one back at the start of the array if applicable
		$strings = array_filter($strings);
		if (count($strings) < $cnt)
		{
			array_unshift($strings, []);
		}

		return $strings;
	}

	/**
	* Test whether all strings match at given suffix length
	*/
	protected function stringsMatchAtLen(array $strings, array $keys, int $len)
	{
		// Make sure that each key points to a single string, and that the string is long enough
		foreach ($keys as $key)
		{
			if (!is_int($key))
			{
				return false;
			}
			$elementIdx = count($strings[$key]) - $len;
			if (!isset($strings[$key][$elementIdx]))
			{
				return false;
			}
		}

		// Keep a copy of the last element we've examined as a reference
		$element = $strings[$key][$elementIdx];

		foreach ($keys as $key)
		{
			$elementIdx = count($strings[$key]) - $len;
			if ($element !== $strings[$key][$elementIdx])
			{
				return false;
			}
		}

		return true;
	}

	protected function updateSuffixGroups(array $strings, array $suffixGroups): array
	{
		foreach ($suffixGroups as $suffixId => $suffixGroup)
		{
			$suffixGroup['keys'] = array_filter(
				$suffixGroup['keys'],
				function (array|int $key) use ($strings)
				{
					return (is_int($key))
					     ? isset($strings[$key])
					     : (array_intersect_key($key, $strings) === $key);
				}
			);
		}

		return $this->filterSuffixGroups($suffixGroups);
	}
}