<?php

/**
* @package   s9e\RegexpBuilder
* @copyright Copyright (c) 2016 The s9e Authors
* @license   http://www.opensource.org/licenses/mit-license.php The MIT License
*/
namespace s9e\RegexpBuilder\Output;

abstract class PrintableAscii implements OutputInterface
{
	/**
	* {@inheritdoc}
	*/
	public function output($value)
	{
		if ($value < 32)
		{
			return $this->escapeControlCode($value);
		}

		if ($value < 127)
		{
			return chr($value);
		}

		return ($value > 255) ? $this->escapeUnicode($value) : $this->escapeAscii($value);
	}

	/**
	* Escape given ASCII codepoint
	*
	* @param  integer $cp
	* @return string
	*/
	protected function escapeAscii($cp)
	{
		return '\\x' . sprintf('%02X', $cp);
	}

	/**
	* Escape given control code
	*
	* @param  integer $cp
	* @return string
	*/
	protected function escapeControlCode($cp)
	{
		$table = [9 => '\\t', 10 => '\\n', 13 => '\\r'];

		return (isset($table[$cp])) ? $table[$cp] : $this->escapeAscii($cp);
	}

	/**
	* Output the representation of a unicode character
	*
	* @param  integer $cp Unicode codepoint
	* @return string
	*/
	abstract protected function escapeUnicode($cp);
}