<?php declare(strict_types=1);

/**
* @package   s9e\RegexpBuilder
* @copyright Copyright (c) 2016-2022 The s9e authors
* @license   http://www.opensource.org/licenses/mit-license.php The MIT License
*/
namespace s9e\RegexpBuilder\Output;

use function sprintf;

/**
* @link https://pcre.org/current/doc/html/pcre2syntax.html#SEC3
*/
class PCRE2 extends PrintableAscii
{
	/**
	* {@inheritdoc}
	*/
	protected function escapeUnicode(int $cp): string
	{
		return sprintf('\\x{%04' . $this->hexCase . '}', $cp);
	}
}