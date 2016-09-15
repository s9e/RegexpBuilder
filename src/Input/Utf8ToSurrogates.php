<?php

/**
* @package   s9e\RegexpBuilder
* @copyright Copyright (c) 2016 The s9e Authors
* @license   http://www.opensource.org/licenses/mit-license.php The MIT License
*/
namespace s9e\RegexpBuilder\Input;

class Utf8ToSurrogates extends Utf8
{
	/**
	* {@inheritdoc}
	*/
	protected function charsToCodepoints(array $chars)
	{
		$codepoints = [];
		foreach ($chars as $char)
		{
			$cp = $this->cp($char);
			if ($cp < 0x10000)
			{
				$codepoints[] = $cp;
			}
			else
			{
				$codepoints[] = 0xD7C0 + ($cp >> 10);
				$codepoints[] = 0xDC00 + ($cp & 0x3FF);
			}
		}

		return $codepoints;
	}
}