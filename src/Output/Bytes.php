<?php

/**
* @package   s9e\RegexpBuilder
* @copyright Copyright (c) 2016 The s9e Authors
* @license   http://www.opensource.org/licenses/mit-license.php The MIT License
*/
namespace s9e\RegexpBuilder\Output;

use InvalidArgumentException;

class Bytes implements OutputInterface
{
	/**
	* {@inheritdoc}
	*/
	public function output($value)
	{
		if ($value > 255)
		{
			throw new InvalidArgumentException('Invalid byte value ' . $value);
		}

		return chr($value);
	}
}