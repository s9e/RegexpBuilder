s9e\RegexpBuilder is a single-purpose library that generates regular expressions that match a list of literal strings.

[![Build Status](https://api.travis-ci.org/s9e/RegexpBuilder.svg?branch=master)](https://travis-ci.org/s9e/RegexpBuilder)
[![Code Coverage](https://scrutinizer-ci.com/g/s9e/RegexpBuilder/badges/coverage.png?b=master)](https://scrutinizer-ci.com/g/s9e/RegexpBuilder/?branch=master)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/s9e/RegexpBuilder/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/s9e/RegexpBuilder/?branch=master)

## Usage

```php
$builder = new s9e\RegexpBuilder\Builder;
echo $builder->build(['foo', 'bar', 'baz']);
```
```
(?:ba[rz]|foo)
```

## Examples

### UTF-8 input with UTF-8 output

```php
$builder = new s9e\RegexpBuilder\Builder([
	'input'  => 'Utf8',
	'output' => 'Utf8'
]);
echo $builder->build(['☺', '☹']);
```
```
[☹☺]
```

### Raw input with raw output

Note that the output is shown here MIME-encoded as it is not possible to display raw bytes in UTF-8. Raw output is most suitable when the result is saved in binary form, e.g. in a data cache.

```php
$builder = new s9e\RegexpBuilder\Builder([
	'input'  => 'Bytes',
	'output' => 'Bytes'
]);
echo quoted_printable_encode($builder->build(['☺', '☹']));
```
```
=E2=98[=B9=BA]
```

### Raw input with PHP output

For PHP regular expressions that do not use the `u` flag. PHP output is most suitable for regexps that are used into PHP sources, in conjunction with `var_export()`. The output itself is ASCII, with non-ASCII and non-printable characters escaped.

```php
$builder = new s9e\RegexpBuilder\Builder([
	'input'  => 'Bytes',
	'output' => 'PHP'
]);
echo $builder->build(['☺', '☹']);
```
```
\xE2\x98[\xB9\xBA]
```

### UTF-8 input with PHP output

For PHP regular expressions that use the `u` flag.

```php
$builder = new s9e\RegexpBuilder\Builder([
	'input'  => 'Utf8',
	'output' => 'PHP'
]);
echo $builder->build(['☺', '☹']);
```
```
[\x{2639}\x{263A}]
```

### UTF-8 input with JavaScript output

For JavaScript regular expressions that do not use the `u` flag and need the higher codepoints to be split into surrogates. The regexp itself uses only ASCII characters, with non-ASCII and non-printable characters escaped.

```php
$builder = new s9e\RegexpBuilder\Builder([
	'input'        => 'Utf8',
	'inputOptions' => ['useSurrogates' => true],
	'output'       => 'JavaScript'
]);
echo $builder->build(['☺', '☹']), "\n";
echo $builder->build(['😁', '😂']);
```
```
[\u2639\u263A]
\uD83D[\uDE01\uDE02]
```

### UTF-8 input with Unicode-aware JavaScript output

For JavaScript regular expressions that use the `u` flag introduced in ECMAScript 6.

```php
$builder = new s9e\RegexpBuilder\Builder([
	'input'  => 'Utf8',
	'output' => 'JavaScript'
]);
echo $builder->build(['☺', '☹']), "\n";
echo $builder->build(['😁', '😂']);
```
```
[\u2639\u263A]
[\u{1F601}\u{1F602}]
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
	'output'        => 'PHP',
	'outputOptions' => ['case' => 'lower']
]);
echo $builder->build(['☺', '☹']),"\n";

$builder = new s9e\RegexpBuilder\Builder([
	'input'         => 'Utf8',
	'output'        => 'JavaScript',
	'outputOptions' => ['case' => 'lower']
]);
echo $builder->build(['☺', '☹']);
```
```
\xe2\x98[\xb9\xba]
[\u2639\u263a]
```

### Adding meta-characters

Some individual characters can be used to represent arbitrary expressions in the input strings. The requirements are that:

 1. Only single characters (as per the input encoding) can be used. For example, `?` is allowed but not `??`.
 2. The regular expression must be valid on its own. For example, `.*` is valid but not `+`.

In the following example, we emulate Bash-style jokers by mapping `?` to `.` and `*` to `.*`.

```php
$builder = new s9e\RegexpBuilder\Builder([
	'meta' => ['?' => '.', '*' => '.*']
]);
echo $builder->build(['foo?', 'bar*']);
```
```
(?:bar.*|foo.)
```

In the following example, we map `X` to `\d`. Note that sequences produced by meta-characters may appear in character classes if the result is valid.

```php
$builder = new s9e\RegexpBuilder\Builder([
	'meta' => ['X' => '\\d']
]);
echo $builder->build(['a', 'b', 'X']);
```
```
[\dab]
```
