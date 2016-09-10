<?php

/**
* @package   s9e\RegexpBuilder
* @copyright Copyright (c) 2016 The s9e Authors
* @license   http://www.opensource.org/licenses/mit-license.php The MIT License
*/
namespace s9e\RegexpBuilder\Output;

class JavaScript extends PrintableAscii
{
	/** {@inheritdoc} */
	protected $maxValue = 0xFFFF;

	/**
	* {@inheritdoc}
	*/
	protected function escapeUnicode($cp)
	{
		return sprintf('\\u%04X', $cp);
	}
}