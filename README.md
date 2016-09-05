s9e\RegexpBuilder is a single-purpose library that generates regular expressions that match a list of literal strings.

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

Note that the output is shown here MIME-encoded as it is not possible to display raw bytes in UTF-8.

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

```php
$builder = new s9e\RegexpBuilder\Builder([
	'input'  => 'Utf8ToSurrogates',
	'output' => 'JavaScript'
]);
echo $builder->build(['☺', '☹']);
```
```
[\u2639\u263A]
```
