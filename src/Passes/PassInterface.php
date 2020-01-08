<?php

/**
* @package   s9e\RegexpBuilder
* @copyright Copyright (c) 2016-2020 The s9e authors
* @license   http://www.opensource.org/licenses/mit-license.php The MIT License
*/
namespace s9e\RegexpBuilder\Passes;

interface PassInterface
{
	/**
	* Run this pass
	*
	* @param  array[] $strings Original strings
	* @return array[]          Modified strings
	*/
	public function run(array $strings);
}