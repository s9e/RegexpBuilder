<?php declare(strict_types=1);

/**
* @package   s9e\RegexpBuilder
* @copyright Copyright (c) The s9e authors
* @license   https://opensource.org/licenses/mit-license.php The MIT License
*/
namespace s9e\RegexpBuilder;

use function array_filter, base64_encode, dechex, random_int;
use s9e\RegexpBuilder\Input\Bytes as BytesInput;
use s9e\RegexpBuilder\Input\InputInterface;
use s9e\RegexpBuilder\Output\Bytes as BytesOutput;
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
	public    readonly Runner     $runner;
	public    readonly Serializer $serializer;
	protected readonly string     $uniqid;

	/**
	* @var bool Whether the expression generated is meant to be used whole. If not, alternations
	*           will be put into a non-capturing group
	*/
	public bool $standalone = true;

	public function __construct(
		public readonly InputInterface  $input  = new BytesInput,
		public readonly Meta            $meta   = new Meta,
		public readonly OutputInterface $output = new BytesOutput
	)
	{
		$this->uniqid     = dechex(random_int(0, 0x7FFFFFFF));
		$this->serializer = new Serializer($this->meta, $this->output);
		$this->setRunner();
	}

	/**
	* Build and return a regular expression that matches all of the given strings
	*
	* @param  array  $strings Strings to be matched, passed as strings or arrays of strings|Expression
	* @return string          Regular expression (without delimiters)
	*/
	public function build(array $strings): string
	{
		$strings = $this->replaceExpressions($strings);
		$strings = $this->getInputSplitter()->splitStrings($strings);
		$strings = $this->getStringSorter()->getUniqueSortedStrings($strings);
		if ($this->isEmpty($strings))
		{
			return '';
		}
		$strings = $this->runner->run($strings);

		return $this->serializer->serializeStrings($strings, !$this->standalone);
	}

	protected function getInputSplitter(): InputSplitter
	{
		return new InputSplitter($this->input, $this->meta);
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
	* Replace expressions in strings to be matched
	*
	* @param  array    $strings List of string|array<string|Expression>
	* @return string[]
	*/
	protected function replaceExpressions(array $strings): array
	{
		foreach (array_filter($strings, 'is_array') as $k => $string)
		{
			$strings[$k] = '';
			foreach ($string as $element)
			{
				if ($element instanceof Expression)
				{
					$expression = (string) $element;
					$sequence   = '$' . $this->uniqid . ':' . base64_encode($expression) . '$';
					$element    = $sequence;

					$this->meta->set($sequence, $expression);
				}

				$strings[$k] .= $element;
			}
		}

		return $strings;
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