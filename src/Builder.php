<?php declare(strict_types=1);

/**
* @package   s9e\RegexpBuilder
* @copyright Copyright (c) 2016-2022 The s9e authors
* @license   http://www.opensource.org/licenses/mit-license.php The MIT License
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
	public InputSplitter $inputSplitter;
	public Meta $meta;
	public Runner $runner;
	public Serializer $serializer;

	/**
	* @var StringSorter
	*/
	public StringSorter $stringSorter;

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
			'delimiter'     => '/',
			'input'         => 'Bytes',
			'inputOptions'  => [],
			'meta'          => [],
			'output'        => 'Bytes',
			'outputOptions' => []
		];

		$this->stringSorter = new StringSorter;

		$this->setMeta($config['meta']);
		$this->setInputSplitter($config['input'], $config['inputOptions']);
		$this->setSerializer($config['output'], $config['outputOptions'], $config['delimiter']);
		$this->setRunner();
	}

	/**
	* Build and return a regular expression that matches all of the given strings
	*
	* @param  string[] $strings Literal strings to be matched
	* @return string            Regular expression (without delimiters)
	*/
	public function build(array $strings): string
	{
		$strings = $this->inputSplitter->splitStrings($strings);
		$strings = $this->stringSorter->getUniqueSortedStrings($strings);
		if ($this->isEmpty($strings))
		{
			return '';
		}
		$strings = $this->runner->run($strings);

		return $this->serializer->serializeStrings($strings, !$this->standalone);
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
	* Set the InputSplitter instance in $this->inputSplitter
	*
	* @param  string $inputType
	* @param  array  $inputOptions
	* @return void
	*/
	protected function setInputSplitter(string $inputType, array $inputOptions): void
	{
		$className = __NAMESPACE__ . '\\Input\\' . $inputType;
		$input     = new $className;
		foreach ($inputOptions as $k => $v)
		{
			$input->$k = $v;
		}

		$this->inputSplitter = new InputSplitter($input, $this->meta);
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

	/**
	* Set the Serializer instance in $this->serializer
	*
	* @param  string $outputType
	* @param  array  $outputOptions
	* @param  string $delimiter
	* @return void
	*/
	protected function setSerializer(string $outputType, array $outputOptions, string $delimiter): void
	{
		$className = __NAMESPACE__ . '\\Output\\' . $outputType;
		$output    = new $className($outputOptions);
		$escaper   = new Escaper($delimiter);

		$this->serializer = new Serializer($escaper, $this->meta, $output);
	}
}