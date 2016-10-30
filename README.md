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

For PHP regular expressions that do not use the `u` flag. PHP output is most suitable for regexps that are copied into PHP sources. The output itself is ASCII, with non-ASCII and non-printable characters escaped.

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
