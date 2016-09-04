<?php

/**
* @package   s9e\RegexpBuilder
* @copyright Copyright (c) 2016 The s9e Authors
* @license   http://www.opensource.org/licenses/mit-license.php The MIT License
*/
namespace s9e\RegexpBuilder\Passes;

abstract class AbstractPass implements PassInterface
{
	/**
	* {@inheritdoc}
	*/
	public function run(array $strings)
	{
		$isOptional = (isset($strings[0]) && $strings[0] === []);
		if ($isOptional)
		{
			array_shift($strings);
		}
		$strings = $this->processStrings($strings);
		if ($isOptional && $strings[0] !== [])
		{
			array_unshift($strings, []);
		}

		return $strings;
	}

	/**
	* Process a given list of strings
	*
	* @param  array[] $strings
	* @return array[]
	*/
	abstract protected function processStrings(array $strings);
}