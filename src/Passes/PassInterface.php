<?php declare(strict_types=1);

/**
* @package   s9e\RegexpBuilder
* @copyright Copyright (c) The s9e authors
* @license   https://opensource.org/licenses/mit-license.php The MIT License
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
	public function run(array $strings): array;
}