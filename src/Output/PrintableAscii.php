<?php

/**
* @package   s9e\RegexpBuilder
* @copyright Copyright (c) 2016-2020 The s9e authors
* @license   http://www.opensource.org/licenses/mit-license.php The MIT License
*/
namespace s9e\RegexpBuilder\Output;

abstract class PrintableAscii extends BaseImplementation
{
	/**
	* @var string 'x' for lowercase hexadecimal symbols, 'X' for uppercase
	*/
	protected $hexCase;

	/**
	* {@inheritdoc}
	*/
	public function __construct(array $options = [])
	{
		$this->hexCase = (isset($options['case']) && $options['case'] === 'lower') ? 'x' : 'X';
	}

	/**
	* Escape given ASCII codepoint
	*
	* @param  integer $cp
	* @return string
	*/
	protected function escapeAscii($cp)
	{
		return '\\x' . sprintf('%02' . $this->hexCase, $cp);
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

	/**
	* {@inheritdoc}
	*/
	protected function outputValidValue($value)
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
}