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
	* @var bool Whether the current set of strings is optional
	*/
	protected $isOptional;

	/**
	* {@inheritdoc}
	*/
	public function run(array $strings)
	{
		$strings = $this->beforeRun($strings);
		$strings = $this->processStrings($strings);
		$strings = $this->afterRun($strings);

		return $strings;
	}

	/**
	* Process the list of strings after the pass is run
	*
	* @param  array[] $strings
	* @return array[]
	*/
	protected function afterRun(array $strings)
	{
		if ($this->isOptional && $strings[0] !== [])
		{
			array_unshift($strings, []);
		}

		return $strings;
	}

	/**
	* Prepare the list of strings before the pass is run
	*
	* @param  array[] $strings
	* @return array[]
	*/
	protected function beforeRun(array $strings)
	{
		$this->isOptional = (isset($strings[0]) && $strings[0] === []);
		if ($this->isOptional)
		{
			array_shift($strings);
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