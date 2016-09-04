<?php

/**
* @package   s9e\RegexpBuilder
* @copyright Copyright (c) 2016 The s9e Authors
* @license   http://www.opensource.org/licenses/mit-license.php The MIT License
*/
namespace s9e\RegexpBuilder\Output;

use InvalidArgumentException;

class JavaScript extends PrintableAscii
{
	/**
	* {@inheritdoc}
	*/
	public function escapeUnicode($cp)
	{
		if ($cp > 0xFFFF)
		{
			throw new InvalidArgumentException('Invalid JavaScript codepoint 0x' . dechex($cp));
		}

		return sprintf('\\u%04X', $cp);
	}
}