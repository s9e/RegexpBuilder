<?php declare(strict_types=1);

/**
* @package   s9e\RegexpBuilder
* @copyright Copyright (c) 2016-2022 The s9e authors
* @license   https://opensource.org/licenses/mit-license.php The MIT License
*/
namespace s9e\RegexpBuilder\Output;

use function chr, sprintf;

abstract class PrintableAscii extends AbstractOutput
{
	/**
	* @var string 'x' for lowercase hexadecimal symbols, 'X' for uppercase
	*/
	protected string $hexCase = 'X';

	/**
	* Use lower case lowercase in hexadecimal representations
	*/
	public function useLowerCaseHex(): void
	{
		$this->hexCase = 'x';
	}

	/**
	* Use upper case lowercase in hexadecimal representations
	*/
	public function useUpperCaseHex(): void
	{
		$this->hexCase = 'X';
	}

	/**
	* Escape given ASCII codepoint
	*
	* @param  int    $cp
	* @return string
	*/
	protected function escapeAscii(int $cp): string
	{
		return '\\x' . sprintf('%02' . $this->hexCase, $cp);
	}

	/**
	* Escape given control code
	*
	* @param  int    $cp
	* @return string
	*/
	protected function escapeControlCode(int $cp): string
	{
		$table = [9 => '\\t', 10 => '\\n', 13 => '\\r'];

		return $table[$cp] ?? $this->escapeAscii($cp);
	}

	/**
	* Output the representation of a unicode character
	*
	* @param  int    $cp Unicode codepoint
	* @return string
	*/
	abstract protected function escapeUnicode(int $cp): string;

	/**
	* {@inheritdoc}
	*/
	protected function outputValidValue(int $value): string
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