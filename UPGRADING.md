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

Current, verbose API:

```php
$builder = new s9e\RegexpBuilder\Builder(
	input:  new s9e\RegexpBuilder\Input\Utf8,
	output: new s9e\RegexpBuilder\Output\JavaScript
);
$builder->input->useSurrogates = true;
$builder->output->hexFormat = s9e\RegexpBuilder\Output\HexFormat::LowerCase;
```

Current, JavaScript factory:

```php
$builder = new s9e\RegexpBuilder\Factory\JavaScript::getBuilder();
$builder->output->hexFormat = s9e\RegexpBuilder\Output\HexFormat::LowerCase;
```
