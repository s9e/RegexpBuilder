<?php declare(strict_types=1);

/**
* @package   s9e\RegexpBuilder
* @copyright Copyright (c) 2016-2022 The s9e authors
* @license   https://opensource.org/licenses/mit-license.php The MIT License
*/
namespace s9e\RegexpBuilder\Output;

use s9e\RegexpBuilder\OutputContext as Context;

interface OutputInterface
{
	/**
	* Serialize a value into a character used in the body of the regexp
	*
	* @param  int     $value
	* @param  Context $context
	* @return string
	*/
	public function output(int $value, Context $context): string;
}