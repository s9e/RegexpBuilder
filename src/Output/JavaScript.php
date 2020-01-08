<?php

/**
* @package   s9e\RegexpBuilder
* @copyright Copyright (c) 2016-2020 The s9e authors
* @license   http://www.opensource.org/licenses/mit-license.php The MIT License
*/
namespace s9e\RegexpBuilder\Output;

class JavaScript extends PrintableAscii
{
	/** {@inheritdoc} */
	protected $maxValue = 0x10FFFF;

	/**
	* {@inheritdoc}
	*/
	protected function escapeUnicode($cp)
	{
		$format = ($cp > 0xFFFF) ? '\\u{%' . $this->hexCase . '}' : '\\u%04' . $this->hexCase;

		return sprintf($format, $cp);
	}
}