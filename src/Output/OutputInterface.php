<?php

/**
* @package   s9e\RegexpBuilder
* @copyright Copyright (c) 2016-2018 The s9e Authors
* @license   http://www.opensource.org/licenses/mit-license.php The MIT License
*/
namespace s9e\RegexpBuilder\Output;

interface OutputInterface
{
	/**
	* Serialize a value into a character
	*
	* @param  integer $value
	* @return string
	*/
	public function output($value);
}