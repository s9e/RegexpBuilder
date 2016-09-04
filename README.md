s9e\RegexpBuilder is a single-purpose library that generates regular expressions that match a list of literal strings.

### Usage

```php
$builder = new s9e\RegexpBuilder\Builder;
echo $builder->build(['foo', 'bar', 'baz']);
```
```
(?:ba[rz]|foo)
```