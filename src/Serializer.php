<?php

/**
* @package   s9e\RegexpBuilder
* @copyright Copyright (c) 2016 The s9e Authors
* @license   http://www.opensource.org/licenses/mit-license.php The MIT License
*/
namespace s9e\RegexpBuilder;

use s9e\RegexpBuilder\Output\OutputInterface;

class Serializer
{
	/**
	* @var Escaper
	*/
	protected $escaper;

	/**
	* @var OutputInterface
	*/
	protected $output;

	/**
	* @param OutputInterface $output
	* @param Escaper         $escaper
	*/
	public function __construct(OutputInterface $output, Escaper $escaper)
	{
		$this->escaper = $escaper;
		$this->output  = $output;
	}

	/**
	* Serialize given strings into a regular expression
	*
	* @param  array[] $strings
	* @return string
	*/
	public function serializeStrings(array $strings)
	{
		$info = $this->analyzeStrings($strings);
		$alternations = $this->buildAlternations($info);
		$expr = implode('|', $alternations);

		if (count($alternations) > 1 || $this->isOneOptionalString($info))
		{
			$expr = '(?:' . $expr . ')';
		}

		return $expr . $info['quantifier'];
	}

	/**
	* Analyze given strings to determine how to serialize them
	*
	* The returned array may contains any of the following elements:
	*
	*  - (string) quantifier Either '' or '?'
	*  - (array)  chars      List of values from single-char strings
	*  - (array)  strings    List of multi-char strings
	*
	* @param  array[] $strings
	* @return array
	*/
	protected function analyzeStrings(array $strings)
	{
		$info  = ['quantifier' => ''];
		$chars = [];
		foreach ($strings as $k => $string)
		{
			if (empty($string))
			{
				$info['quantifier'] = '?';
				unset($strings[$k]);
			}
			elseif (!isset($string[1]))
			{
				$chars[$k] = $string[0];
			}
		}

		if (count($chars) > 1)
		{
			$info['chars'] = array_values($chars);
			$strings = array_diff_key($strings, $chars);
		}

		$info['strings'] = array_values($strings);

		return $info;
	}

	/**
	* Build the list of alternations based on given info
	*
	* @param  array    $info
	* @return string[]
	*/
	protected function buildAlternations(array $info)
	{
		$alternations = [];
		if (!empty($info['chars']))
		{
			$alternations[] = $this->serializeCharacterClass($info['chars']);
		}
		foreach ($info['strings'] as $string)
		{
			$alternations[] = $this->serializeString($string);
		}

		return $alternations;
	}

	/**
	* Get the list of ranges that cover all given values
	*
	* @param  integer[] $values Ordered list of values
	* @return array[]           List of ranges in the form [start, end]
	*/
	protected function getRanges(array $values)
	{
		$i     = 0;
		$cnt   = count($values);
		$start = $values[0];
		$end   = $start;
		while (++$i < $cnt)
		{
			if ($values[$i] === $end + 1)
			{
				++$end;
			}
			else
			{
				$ranges[] = [$start, $end];
				$start = $end = $values[$i];
			}
		}
		$ranges[] = [$start, $end];

		return $ranges;
	}

	/**
	* Test whether a string is optional and has more than one character
	*
	* @param  array $info
	* @return bool
	*/
	protected function isOneOptionalString(array $info)
	{
		// Test whether the first string has a quantifier and more than one element
		return (!empty($info['quantifier']) && isset($info['strings'][0][1]));
	}

	/**
	* Serialize a given list of values into a character class
	*
	* @param  integer[] $values
	* @return string
	*/
	protected function serializeCharacterClass(array $values)
	{
		$expr = '[';
		foreach ($this->getRanges($values) as list($start, $end))
		{
			$expr .= $this->escaper->escapeCharacterClass($this->output->output($start));
			if ($end > $start)
			{
				if ($end > $start + 1)
				{
					$expr .= '-';
				}
				$expr .= $this->escaper->escapeCharacterClass($this->output->output($end));
			}
		}
		$expr .= ']';

		return $expr;
	}

	/**
	* Serialize a given string into a regular expression
	*
	* @param  array  $string
	* @return string
	*/
	protected function serializeString(array $string)
	{
		$expr = '';
		foreach ($string as $element)
		{
			$expr .= (is_array($element)) ? $this->serializeStrings($element) : $this->escaper->escapeLiteral($this->output->output($element));
		}

		return $expr;
	}
}