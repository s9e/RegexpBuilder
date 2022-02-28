### Process outline

 0. Sort the input strings and remove duplicates.
 1. Split each input string into a numerically-indexed array of numeric values, which are either byte values or codepoints depending the configuration.
 2. Execute each pass in order. Each pass receives the whole list of strings.
 3. Serialize the array of values into a PHP string.


### Terms used in the codebase

<dl>
	<dt>Alternation</dt>
	<dd>A numerically-indexed array of `strings`. The term `alternation` used in the codebase refers to the whole group of `strings`.</dd>

	<dt>String</dt>
	<dd>A numerically-indexed array of `elements`.</dd>

	<dt>Element</dt>
	<dd>Either a numeric value, or an `alternation`.</dd>
</dl>


### Data structure

The main data structure is a numerically-indexed array and contains only two types of values: integers and numerically-indexed arrays. The main structure is a big alternation, each string contains a list of numeric values or other alternations. Positive numeric values come from the original input strings, and negative numeric values are assigned to meta characters, if any were defined.

For example, when processing the input `["bar", "baz"]` the main data structure will start as the following:
```
// Main alternation
[
	// First string
	[98, 97, 114], // "b", "a", "r"
	// Second string
	[98, 97, 122]  // "b", "a", "z"
]
```
After optimization, it becomes:
```
// Main alternation
[
	// First string
	[
		98, // "b"
		97, // "a"
		// New alternation, composed of two strings
		[
			[114], // "r"
			[122]  // "z"
		]
	]
]
```

An alternation may start with an empty string. It means the whole group is optional. For instance, the expression `(?:abc)?` is internally represented as `(?:|abc)` with the following data:
```
// Main alternation
[
	// First string
	[]
	// Second string
	[97, 98, 99]  // "a", "b", "c"
]
```
Empty strings are always found at the beginning of a group because strings are sorted (and remain) in lexicographical order and empty strings naturally appear before non-empty strings. Note that the expressions `(?:abc)?` and `(?:|abc)` are executed differently by regexp engines. The expression `(?:abc|)` would be more semantically correct and future versions may correct this implementation.


### Passes

 - **CoalesceOptionalStrings** replaces `(?:ab?|b)?` with `a?b?`
 - **CoalesceSingleCharacterPrefix** replaces `(?:ab|bb|c)` with `(?:[ab]b|c)`
 - **GroupSingleCharacters** replaces `(?:aa|b|cc|d)` with `(?:[bd]|aa|cc)`
 - **MergePrefix** replaces `(?:axx|ayy)` with `a(?:xx|yy)`
 - **MergeSuffix** replaces `(?:aax|bbx)` with `(?:aa|bb)x`
 - **PromoteSingleStrings** replaces `(?:ab)` with `ab`
 - **Recurse** runs all passes on each alternation in each string
