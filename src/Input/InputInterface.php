<?php

/**
* @package   s9e\RegexpBuilder
* @copyright Copyright (c) 2016 The s9e Authors
* @license   http://www.opensource.org/licenses/mit-license.php The MIT License
*/
namespace s9e\RegexpBuilder\Input;

interface InputInterface
{
	/**
	* Split given string into a list of values
	*
	* @param  string    $string
	* @return integer[]
	*/
	public function split($string);
}