# 2.0.0

 - The Java output uses `\uHHHH` instead of `\u{HHHH}`.

 - Expressions created from meta-characters are moved to the end of a group.
     - Old: `(?:.*|x)`
     - New: `(?:x|.*)`

 - Strings are sorted *after* surrogate pairs are created.
     - Old: `\uD83C\uDD90(?:\uFE0F|\uD83C[\uDFFB-\uDFFF])?`
     - New: `\uD83C\uDD90(?:\uD83C[\uDFFB-\uDFFF]|\uFE0F)?`

