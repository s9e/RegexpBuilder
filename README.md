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

A factory is a static class that creates a `Builder` instance configured for a specific use case. All of the factories have a static `getBuilder()` method. Some of them accept optional arguments.

The following factories can be used to generate regular expressions for the corresponding programming language. The `Builder` instance will generate a regexp using only printable ASCII characters, while other characters will be escaped according to the regexp engine's syntax. The list of factories along with their optional arguments (with their default value) is as follows:

 - `PHP`
     - `modifiers: ''` - [Pattern modifiers](https://www.php.net/manual/reference.pcre.pattern.modifiers.php) used for the regexp, e.g. `isu`
     - `delimiter: '/'` - [Delimiter(s)](https://www.php.net/manual/en/regexp.reference.delimiters.php) used for the regexp, e.g. `#` or `()`
 - `Java`
 - `JavaScript`
     - `flags: ''` - [Flags](https://developer.mozilla.org/en-US/docs/Web/JavaScript/Reference/Global_Objects/RegExp/flags#description) used for the RegExp object
 - `RE2`

In addition, two factories `RawBytes` and `RawUTF8` exist. They can be used to generate smaller regexps without any restrictions on the characters used, respectively using bytes and UTF-8 characters as base unit. The resulting regexp should be treated as binary and is not recommended for use in human-readable code.


## Examples


### Create a PHP (PCRE2) regexp

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


### Create a JavaScript regexp

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


### Using meta sequences

User-defined sequences can be used to represent arbitrary expressions in the input strings. The sequence can be composed of one or more characters. The expression it represents must be valid on its own. For example, `.*` is valid but not `+`.

In the following example, we emulate Bash-style jokers by mapping `?` to `.` and `*` to `.*`.

```php
$builder = new s9e\RegexpBuilder\Builder;
$builder->meta->set('?', '.');
$builder->meta->set('*', '.*');

echo '/', $builder->build(['foo?', 'bar*']), '/';
```
```
/bar.*|foo./
```

In the following example, we map `\d` (in the input) to `\d` (in the output) to emulate the escape sequence of a regular expression. Note that they do not have to be identical and we may choose to map `*` to `\d` or `\d` to `[0-9]` instead.

```php
$builder = new s9e\RegexpBuilder\Builder;
$builder->meta->set('\\d', '\\d');

echo '/', $builder->build(['a', 'b', '\\d']), '/';
```
```
/[ab\d]/
```

Alternatively, the `meta` property can also be set as a promoted constructor parameter as follows.
```php
$builder = new s9e\RegexpBuilder\Builder(
	meta: new s9e\RegexpBuilder\Meta(['?' => '.', '*' => '.*'])
);

echo '/', $builder->build(['foo?', 'bar*']), '/';
```
```
/bar.*|foo./
```


### Using regular expressions in input

As an alternative to meta sequences, it is possible to identify parts of the input that are meant to be interpreted as a regular expression rather than a literal. This is done by passing an array instead of a string literal when building a regexp. The array must contain 0 or more string literals for the literal parts, and 0 or more instances of `s9e\RegexpBuilder\Expression` for the regular expressions.

In the following example, we build a regexp to be used for URL routing. We want to match the following routes, expressed here as regexps:

 - `/(*:home)`
 - `/admin(*:admin_index)`
 - `/admin/login(*:admin_login)`
 - `/admin/logout(*:admin_logout)`
 - `/admin/product(*:admin_product_store)`
 - `/admin/product/(\d+)(*:admin_product_show)`
 - `/admin/product/(\d+)/edit(*:admin_product_edit)`
 - `/shop(*:shop_index)`
 - `/shop/product(*:shop_product_index)`
 - `/shop/product/(\d+)(*:shop_product_show)`

```php
// Use the PHP factory to generate a PHP regexp with ~ as a delimiter
$delimiter = '~';
$builder   = s9e\RegexpBuilder\Factory\PHP::getBuilder(delimiter: $delimiter);

// In this example, we want to use non-capture groups that reset group numbers
// As per https://www.pcre.org/current/doc/html/pcre2syntax.html#TOC1
$builder->serializer->groupType = s9e\RegexpBuilder\GroupType::NonCaptureReset;

// Syntactic sugar
function expr(string $expr)
{
	return new s9e\RegexpBuilder\Expression($expr);
}

// Here we split each route into a mix of literals and regular expressions
$regexp = $builder->build([
	['/', expr('(*:home)')],
	['/admin', expr('(*:admin_index)')],
	['/admin/login', expr('(*:admin_login)')],
	['/admin/logout', expr('(*:admin_logout)')],
	['/admin/product', expr('(*:admin_product_store)')],
	['/admin/product/', expr('(\d+)'), expr('(*:admin_product_show)')],
	['/admin/product/', expr('(\d+)'), '/edit', expr('(*:admin_product_edit)')],
	['/shop', expr('(*:shop_index)')],
	['/shop/product', expr('(*:shop_product_index)')],
	['/shop/product/', expr('(\d+)'), expr('(*:shop_product_show)')]
]);
$regexp = $delimiter . '^' . $regexp . '$' . $delimiter;

// Let's see what the regexp looks like
echo "$regexp\n\n";

// Let's test our new regexp
preg_match($regexp, '/admin/product/123', $m);
print_r($m);
```
```
~^/(?|admin(?|/(?|log(?|in(*:admin_login)|out(*:admin_logout))|product(?|/(\d+)(?|/edit(*:admin_product_edit)|(*:admin_product_show))|(*:admin_product_store)))|(*:admin_index))|shop(?|/product(?|/(\d+)(*:shop_product_show)|(*:shop_product_index))|(*:shop_index))|(*:home))$~

Array
(
    [0] => /admin/product/123
    [1] => 123
    [MARK] => admin_product_show
)
```
