<?php

/**
* @package   s9e\RegexpBuilder
* @copyright Copyright (c) 2016 The s9e Authors
* @license   http://www.opensource.org/licenses/mit-license.php The MIT License
*/
namespace s9e\RegexpBuilder\Output;

use InvalidArgumentException;

abstract class BaseImplementation implements OutputInterface
{
	/**
	* @var integer
	*/
	protected $maxValue = 0;

	/**
	* @var integer
	*/
	protected $minValue = 0;

	/**
	* @param array $options
	*/
	public function __construct(array $options = [])
	{
	}

	/**
	* {@inheritdoc}
	*/
	public function output($value)
	{
		if ($value < $this->minValue || $value > $this->maxValue)
		{
			throw new InvalidArgumentException('Value ' . $value . ' is out of bounds (' . $this->minValue . '..' . $this->maxValue . ')');
		}

		return $this->outputValidValue($value);
	}

	/**
	* Serialize a valid value into a character
	*
	* @param  integer $value
	* @return string
	*/
	abstract protected function outputValidValue($value);
}