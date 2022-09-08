<?php declare(strict_types=1);

namespace s9e\RegexpBuilder\Tests;

use PHPUnit\Framework\TestCase;
use s9e\RegexpBuilder\Builder;
use s9e\RegexpBuilder\Factory\PHP;
use s9e\RegexpBuilder\Factory\Java;
use s9e\RegexpBuilder\Factory\JavaScript;
use s9e\RegexpBuilder\Factory\RE2;

/**
* @coversNothing
*/
class EmojiTest extends TestCase
{
	protected string $emojiDir = __DIR__ . '/node_modules/emoji-test-regex-pattern/dist/latest';

	protected function getEmoji(): array
	{
		if (!file_exists($this->emojiDir))
		{
			$this->markTestSkipped('Missing NPM module in tests directory');
		}

		return file($this->emojiDir . '/index-strings.txt', FILE_IGNORE_NEW_LINES);
	}

	public function testEmojiBytes()
	{
		$this->runEmojiTest('D');
	}

	public function testEmojiUnicode()
	{
		$this->runEmojiTest('Du');
	}

	protected function runEmojiTest($modifiers)
	{
		$emoji   = $this->getEmoji();
		$builder = PHP::getBuilder(delimiter: '/', modifiers: $modifiers);
		$regexp  = '/^(?:' . $builder->build($emoji) . ')$/' . $modifiers;

		// Ensure that each emoji is matched fully
		foreach ($emoji as $string)
		{
			$errorMsg = "'$regexp' does not match '$string'";
			$this->assertSame(1, preg_match($regexp, $string, $m), $errorMsg);
			$this->assertSame($string, $m[0], $errorMsg);
		}
	}

	/**
	* @dataProvider getEmojiTestRegexPatternTests
	*/
	public function testEmojiTestRegexPattern(Builder $builder, string $filename)
	{
		$regexp = $builder->build($this->getEmoji());
		$reference = file_get_contents($this->emojiDir . '/' . $filename);

		$this->assertLessThanOrEqual(strlen($reference), strlen($regexp));
	}

	public function getEmojiTestRegexPatternTests(): array
	{
		return [
			[
				RE2::getBuilder(),
				'cpp-re2.txt'
			],
			[
				Java::getBuilder(),
				'java.txt'
			],
			[
				JavaScript::getBuilder(),
				'javascript.txt'
			],
			[
				JavaScript::getBuilder(flags: 'u'),
				'javascript-u.txt'
			],
		];
	}
}