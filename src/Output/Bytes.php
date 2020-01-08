<?php

/**
* @package   s9e\RegexpBuilder
* @copyright Copyright (c) 2016-2020 The s9e authors
* @license   http://www.opensource.org/licenses/mit-license.php The MIT License
*/
namespace s9e\RegexpBuilder\Output;

class Bytes extends BaseImplementation
{
	/** {@inheritdoc} */
	protected $maxValue = 255;

	/**
	* {@inheritdoc}
	*/
	protected function outputValidValue($value)
	{
		return chr($value);
	}
}