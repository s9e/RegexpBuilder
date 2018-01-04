<?php

/**
* @package   s9e\RegexpBuilder
* @copyright Copyright (c) 2016-2018 The s9e Authors
* @license   http://www.opensource.org/licenses/mit-license.php The MIT License
*/
namespace s9e\RegexpBuilder\Input;

abstract class BaseImplementation implements InputInterface
{
	/**
	* {@inheritdoc}
	*/
	public function __construct(array $options = [])
	{
	}

	/**
	* {@inheritdoc}
	*/
	abstract public function split($string);
}