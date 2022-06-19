s9e\RegexpBuilder is a library that generates a regular expression that matches a given list of strings. It is best suited for efficiently finding a list of keywords inside of a text.

In practical terms, given `['foo', 'bar', 'baz']` as input, the library will generate `ba[rz]|foo`, a regular expression that can match any of the strings `foo`, `bar`, or `baz`. It can generate regular expressions for different regexp engines used in various programming languages such as PHP, JavaScript, and others.

[![Build status](https://github.com/s9e/RegexpBuilder/actions/workflows/build.yml/badge.svg)](https://github.com/s9e/RegexpBuilder/actions/workflows/build.yml)


## Installation

Add `s9e/regexp-builder` to your Composer dependencies.

```bash
composer require s9e/regexp-builder
```


## Usage

The simplest way to use the library is to obtain a `Builder` instance from one of the existing factories. The builder's `build()` method accepts a list of strings as input and returns a regular expression that matches them.

```php
// Use the PHP factory to generate a PHP regexp
$builder = s9e\RegexpBuilder\Factory\PHP::getBuilder();
echo '/', $builder->build(['foo', 'bar', 'baz']), '/';
```
```
/ba[rz]|foo/
```


### Factories

Different factories exist to create and configure a `Builder` instance for a specific regexp engine or programming language. All of the factories have a static `getBuilder()` method. Some of them accept optional arguments.

The list of factories, their optional arguments with their default value is as follows:

 - `PHP`
     - `modifiers: ''` - [Pattern modifiers](https://www.php.net/manual/reference.pcre.pattern.modifiers.php) used for the regexp, e.g. `isu`
     - `delimiter: '/'` - [Delimiter](https://www.php.net/manual/en/regexp.reference.delimiters.php) used for the regexp, e.g. `#` or `()`
 - `Java`
 - `JavaScript`
     - `flags: ''` - [Flags](https://developer.mozilla.org/en-US/docs/Web/JavaScript/Reference/Global_Objects/RegExp/flags#description) used for the RegExp object
 - `RE2`


## Examples

The following example shows how to create a PHP regexp that matches `☺` (U+263A) or `☹` (U+2639), with or without the `u` flag.

```php
// Without any modifiers, PCRE operates on bytes
$builder = s9e\RegexpBuilder\Factory\PHP::getBuilder();
echo '/', $builder->build(['☺', '☹']), "/\n";

// The 'u' flag enables Unicode mode in PCRE
$builder = s9e\RegexpBuilder\Factory\PHP::getBuilder(modifiers: 'u');
echo '/', $builder->build(['☺', '☹']), '/u';
```
```
/\xE2\x98[\xB9\xBA]/
/[\x{2639}\x{263A}]/u
```

The following example shows that you can replace the factory with the JavaScript factory to create JavaScript regexps, with or without the `u` flag.

```php
$builder = s9e\RegexpBuilder\Factory\JavaScript::getBuilder();
echo '/', $builder->build(['😁', '😂']), "/\n";

// The 'u' flag enables Unicode mode in JavaScript RegExp
$builder = s9e\RegexpBuilder\Factory\JavaScript::getBuilder(flags: 'u');
echo '/', $builder->build(['😁', '😂']), '/u';
```
```
/\uD83D[\uDE01\uDE02]/
/[\u{1F601}\u{1F602}]/u
```


## Advanced usage

The following section covers cases that are not covered by a default configuration.


### UTF-8 input with UTF-8 output

```php
$builder = new s9e\RegexpBuilder\Builder([
	'input'  => 'Utf8',
	'output' => 'Utf8'
]);
echo '/', $builder->build(['☺', '☹']), '/u';
```
```
/[☹☺]/u
```


### Raw input with raw output

Note that the output is shown here MIME-encoded as it is not possible to display raw bytes in UTF-8. Raw output is most suitable when the result is saved in binary form, e.g. in a data cache.

```php
$builder = new s9e\RegexpBuilder\Builder([
	'input'  => 'Bytes',
	'output' => 'Bytes'
]);
echo '/', quoted_printable_encode($builder->build(['☺', '☹'])), '/';
```
```
/=E2=98[=B9=BA]/
```


### Raw input with PCRE2 output

For PCRE2 regular expressions that do not use the `u` flag. PCRE2 output is most suitable for regexps that are used into PHP sources, in conjunction with `var_export()`. The output itself is ASCII, with non-ASCII and non-printable characters escaped.

```php
$builder = new s9e\RegexpBuilder\Builder([
	'input'  => 'Bytes',
	'output' => 'PCRE2'
]);
echo '/', $builder->build(['☺', '☹']), '/';
```
```
/\xE2\x98[\xB9\xBA]/
```


### UTF-8 input with PCRE2 output

For PCRE2 regular expressions that use the `u` flag.

```php
$builder = new s9e\RegexpBuilder\Builder([
	'input'  => 'Utf8',
	'output' => 'PCRE2'
]);
echo '/', $builder->build(['☺', '☹']), '/u';
```
```
/[\x{2639}\x{263A}]/u
```


### UTF-8 input with JavaScript output

For JavaScript regular expressions that do not use the `u` flag and need the higher codepoints to be split into surrogates. The regexp itself uses only ASCII characters, with non-ASCII and non-printable characters escaped.

```php
$builder = new s9e\RegexpBuilder\Builder([
	'input'        => 'Utf8',
	'inputOptions' => ['useSurrogates' => true],
	'output'       => 'JavaScript'
]);
echo '/', $builder->build(['☺', '☹']), "/\n";
echo '/', $builder->build(['😁', '😂']), '/';
```
```
/[\u2639\u263A]/
/\uD83D[\uDE01\uDE02]/
```


### UTF-8 input with Unicode-aware JavaScript output

For JavaScript regular expressions that use the `u` flag introduced in ECMAScript 6. In that case, you can simply forgo using surrogates.

```php
$builder = new s9e\RegexpBuilder\Builder([
	'input'  => 'Utf8',
	'output' => 'JavaScript'
]);
echo '/', $builder->build(['☺', '☹']), "/u\n";
echo '/', $builder->build(['😁', '😂']), '/u';
```
```
/[\u2639\u263A]/u
/[\u{1F601}\u{1F602}]/u
```


### Custom delimiters

```php
$strings = ['/', '(', ')', '#'];

$builder = new s9e\RegexpBuilder\Builder;
echo '/', $builder->build($strings), "/\n";

$builder = new s9e\RegexpBuilder\Builder(['delimiter' => '#']);
echo '#', $builder->build($strings), "#\n";

$builder = new s9e\RegexpBuilder\Builder(['delimiter' => '()']);
echo '(', $builder->build($strings), ')';
```
```
/[#()\/]/
#[\#()/]#
([#\(\)/])
```


### Lowercase hexadecimal representation

By default, the `PHP` and `JavaScript` output uses uppercase hexadecimal symbols, e.g. `\xAB`. This can be changed to lowercase using the `outputOptions` setting.

```php
$builder = new s9e\RegexpBuilder\Builder([
	'input'         => 'Bytes',
	'output'        => 'PCRE2',
	'outputOptions' => ['case' => 'lower']
]);
echo '/', $builder->build(['☺', '☹']), "/\n";

$builder = new s9e\RegexpBuilder\Builder([
	'input'         => 'Utf8',
	'output'        => 'JavaScript',
	'outputOptions' => ['case' => 'lower']
]);
echo '/', $builder->build(['☺', '☹']), '/';
```
```
/\xe2\x98[\xb9\xba]/
/[\u2639\u263a]/
```


### Using meta sequences

User-defined sequences can be used to represent arbitrary expressions in the input strings. The sequence can be composed of one or more characters. The expression it represents must be valid on its own. For example, `.*` is valid but not `+`.

In the following example, we emulate Bash-style jokers by mapping `?` to `.` and `*` to `.*`.

```php
$builder = new s9e\RegexpBuilder\Builder([
	'meta' => ['?' => '.', '*' => '.*']
]);
echo '/', $builder->build(['foo?', 'bar*']), '/';
```
```
/bar.*|foo./
```

In the following example, we map `\d` to `\d` to emulate the escape sequence of a regular expression. Note that they do not have to be identical and we may choose to map `*` to `\d` or `\d` to `[0-9]` instead.

```php
$builder = new s9e\RegexpBuilder\Builder([
	'meta' => ['\\d' => '\\d']
]);
echo '/', $builder->build(['a', 'b', '\\d']), '/';
```
```
/[ab\d]/
```
