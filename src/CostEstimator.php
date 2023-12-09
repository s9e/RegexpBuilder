<?php declare(strict_types=1);

/**
* @package   s9e\RegexpBuilder
* @copyright Copyright (c) The s9e authors
* @license   https://opensource.org/licenses/mit-license.php The MIT License
*/
namespace s9e\RegexpBuilder;

use function array_map, array_sum, count, is_array, is_int;

class CostEstimator
{
	public function estimateString(array $string): int
	{
		$cost = 0;
		foreach ($string as $element)
		{
			if (is_array($element))
			{
				$cost += $this->estimateStrings($element);
			}
			else
			{
				$cost += ($element < 0) ? $this->estimateMeta($element) : $this->estimateLiteral($element);
			}
		}

		return $cost;
	}

	public function estimateStrings(array $strings): int
	{
		$cost = array_sum(array_map($this->estimateString(...), $strings));
		$cnt  = count($strings);
		if ($cnt > 1)
		{
			// Add the expected overhead for [] or (?:) plus the alternation characters |
			$cost += $this->isCharacterClass($strings) ? 2 : 3 + $cnt;
		}

		return $cost;
	}

	protected function estimateLiteral(int $value): int
	{
		if ($value < 0)
		{
			return $this->estimateMeta($value);
		}
		if ($value < 32)
		{
			// Control codes take 1 bytes in Raw output, and either 2 or 4 in PrintableAscii
			return 2;
		}
		if ($value < 128)
		{
			return 1;
		}
		if ($value < 2048)
		{
			// Characters up to U+800 are encoded on 2 bytes in UTF-8
			return 2;
		}

		return ($value < 65536) ? 3 : 4;
	}

	protected function estimateMeta(int $value): int
	{
		// We estimate character sequences to cost 2 (e.g. \w or \d) and anything else to cost 4
		return Meta::isChar($value) ? 2 : 4;
	}

	protected function isCharacterClass(array $strings): bool
	{
		foreach ($strings as $string)
		{
			if (isset($string[1]) || !is_int($string[0]) || !Meta::isChar($string[0]))
			{
				return false;
			}
		}

		return true;
	}
}