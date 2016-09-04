<?php

/**
* @package   s9e\RegexpBuilder
* @copyright Copyright (c) 2016 The s9e Authors
* @license   http://www.opensource.org/licenses/mit-license.php The MIT License
*/
namespace s9e\RegexpBuilder;

use s9e\RegexpBuilder\Input\InputInterface;
use s9e\RegexpBuilder\Output\OutputInterface;
use s9e\RegexpBuilder\Passes\CoalesceSingleCharacterPrefix;
use s9e\RegexpBuilder\Passes\GroupSingleCharacters;
use s9e\RegexpBuilder\Passes\MergePrefix;
use s9e\RegexpBuilder\Passes\MergeSuffix;
use s9e\RegexpBuilder\Passes\PromoteSingleStrings;
use s9e\RegexpBuilder\Passes\Recurse;

class Builder
{
	/**
	* @var InputInterface
	*/
	protected $input;

	/**
	* @var Runner
	*/
	protected $runner;

	/**
	* @var Serializer
	*/
	protected $serializer;

	/**
	* @param array $config
	*/
	public function __construct(array $config = [])
	{
		$config = $this->getConfig($config);

		$this->input      = $config['input'];
		$this->runner     = $config['runner'];
		$this->serializer = new Serializer($config['output'], $config['escaper']);
	}

	/**
	* Build and return a regular expression that matches all of the given strings
	*
	* @param  string[] $strings Literal strings to be matched
	* @return string            Regular expression (without delimiters)
	*/
	public function build(array $strings)
	{
		$strings = array_unique($strings);
		if ($strings === [''])
		{
			return '';
		}
		sort($strings);

		$strings = $this->splitStrings($strings);
		$strings = $this->runner->run($strings);

		return $this->serializer->serializeStrings($strings);
	}

	/**
	* Build the full config array based on given input
	*
	* @param  array $config Sparse config
	* @return array         Full config
	*/
	protected function getConfig(array $config)
	{
		$config += [
			'delimiter' => '/',
			'input'     => 'Bytes',
			'output'    => 'Bytes'
		];
		if (!isset($config['escaper']))
		{
			$config['escaper'] = new Escaper($config['delimiter']);
		}
		if (!($config['input'] instanceof InputInterface))
		{
			$className = __NAMESPACE__ . '\\Input\\' . $config['input'];
			$config['input'] = new $className;
		}
		if (!($config['output'] instanceof OutputInterface))
		{
			$className = __NAMESPACE__ . '\\Output\\' . $config['output'];
			$config['output'] = new $className;
		}
		if (!isset($config['runner']))
		{
			$config['runner'] = new Runner;
			$config['runner']->addPass(new MergePrefix);
			$config['runner']->addPass(new GroupSingleCharacters);
			$config['runner']->addPass(new Recurse($config['runner']));
			$config['runner']->addPass(new PromoteSingleStrings);
			$config['runner']->addPass(new MergeSuffix);
			$config['runner']->addPass(new CoalesceSingleCharacterPrefix);
		}

		return $config;
	}

	/**
	* Split all given strings by character
	*
	* @param  string[] $strings List of strings
	* @return array[]           List of arrays
	*/
	protected function splitStrings(array $strings)
	{
		return array_map([$this->input, 'split'], $strings);
	}
}