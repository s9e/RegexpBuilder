This document contains some notes about the implementation, in no particular order.


#### Meta expressions are ordered last

When sorting strings, literals are ordered before meta expressions. The order is not very important but it should be consistent so that a given input produces the same output regardless of the order. Because meta expressions are expected to be mostly used for some kind of joker such as `.*?` and those expressions are more likely to backtrack than literals, they should be tried last. On the other hand, simple assertions such as `^` or `\b` would probably be better tried first, before literals or expressions that consume characters.
