# 1.x to 2.0

Old, unsupported parameter bags:

```php
$builder = new s9e\RegexpBuilder\Builder([
	'input'         => 'Utf8',
	'inputOptions'  => ['useSurrogates' => true],
	'output'        => 'JavaScript',
	'outputOptions' => ['case' => 'lower']
]);
```

Current, explicit API:

```php
$builder = new s9e\RegexpBuilder\Builder(
	input:  'Utf8',
	output: 'JavaScript'
);
$builder->input->useSurrogates = true;
$builder->output->hexFormat = s9e\RegexpBuilder\Output\HexFormat::LowerCase;
```
