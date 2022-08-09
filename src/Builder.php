<?php declare(strict_types=1);

/**
* @package   s9e\RegexpBuilder
* @copyright Copyright (c) 2016-2022 The s9e authors
* @license   https://opensource.org/licenses/mit-license.php The MIT License
*/
namespace s9e\RegexpBuilder;

use function array_map;
use s9e\RegexpBuilder\Input\InputInterface;
use s9e\RegexpBuilder\Output\OutputInterface;
use s9e\RegexpBuilder\Passes\CoalesceOptionalStrings;
use s9e\RegexpBuilder\Passes\CoalesceSingleCharacterPrefix;
use s9e\RegexpBuilder\Passes\GroupSingleCharacters;
use s9e\RegexpBuilder\Passes\MergePrefix;
use s9e\RegexpBuilder\Passes\MergeSuffix;
use s9e\RegexpBuilder\Passes\PromoteSingleStrings;
use s9e\RegexpBuilder\Passes\Recurse;

class Builder
{
	public readonly InputInterface  $input;
	public readonly Meta            $meta;
	public readonly OutputInterface $output;
	public readonly Runner          $runner;

	/**
	* @var bool Whether the expression generated is meant to be used whole. If not, alternations
	*           will be put into a non-capturing group
	*/
	public bool $standalone = true;

	/**
	* @param array $config
	*/
	public function __construct(array $config = [])
	{
		$config += [
			'input'         => 'Bytes',
			'inputOptions'  => [],
			'meta'          => [],
			'output'        => 'Bytes',
			'outputOptions' => []
		];

		$this->setMeta($config['meta']);
		$this->setInput($config['input'], $config['inputOptions']);
		$this->setOutput($config['output'], $config['outputOptions']);
		$this->setRunner();

		if (isset($config['delimiter']))
		{
			$this->output->setDelimiter($config['delimiter']);
		}
	}

	/**
	* Build and return a regular expression that matches all of the given strings
	*
	* @param  string[] $strings Literal strings to be matched
	* @return string            Regular expression (without delimiters)
	*/
	public function build(array $strings): string
	{
		$strings = $this->getInputSplitter()->splitStrings($strings);
		$strings = $this->getStringSorter()->getUniqueSortedStrings($strings);
		if ($this->isEmpty($strings))
		{
			return '';
		}
		$strings = $this->runner->run($strings);

		return $this->getSerializer()->serializeStrings($strings, !$this->standalone);
	}

	protected function getInputSplitter(): InputSplitter
	{
		return new InputSplitter($this->input, $this->meta);
	}

	protected function getSerializer(): Serializer
	{
		return new Serializer($this->meta, $this->output);
	}

	public function getStringSorter(): StringSorter
	{
		return new StringSorter;
	}

	/**
	* Test whether the list of strings is empty
	*
	* @param  array<array> $strings
	* @return bool
	*/
	protected function isEmpty(array $strings): bool
	{
		return (empty($strings) || $strings === [[]]);
	}

	/**
	* Set the InputInterface instance in $this->input
	*
	* @param  string $inputType
	* @param  array  $inputOptions
	* @return void
	*/
	protected function setInput(string $inputType, array $inputOptions): void
	{
		$className   = __NAMESPACE__ . '\\Input\\' . $inputType;
		$this->input = new $className;
		foreach ($inputOptions as $k => $v)
		{
			$this->input->$k = $v;
		}
	}

	/**
	* Set the Meta instance in $this->meta
	*
	* @param  array $map
	* @return void
	*/
	protected function setMeta(array $map): void
	{
		$this->meta = new Meta;
		foreach ($map as $sequence => $expression)
		{
			$this->meta->set($sequence, $expression);
		}
	}

	/**
	* Set the OutputInterface instance in $this->output
	*
	* @param  string $outputType
	* @param  array  $outputOptions
	* @return void
	*/
	protected function setOutput(string $outputType, array $outputOptions): void
	{
		$className    = __NAMESPACE__ . '\\Output\\' . $outputType;
		$this->output = new $className($outputOptions);
	}

	/**
	* Set the Runner instance $in this->runner
	*
	* @return void
	*/
	protected function setRunner(): void
	{
		$this->runner = new Runner;
		$this->runner->addPass(new MergePrefix);
		$this->runner->addPass(new GroupSingleCharacters);
		$this->runner->addPass(new Recurse($this->runner));
		$this->runner->addPass(new PromoteSingleStrings);
		$this->runner->addPass(new CoalesceOptionalStrings);
		$this->runner->addPass(new MergeSuffix);
		$this->runner->addPass(new CoalesceSingleCharacterPrefix);
	}
}