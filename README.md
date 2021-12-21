Passy
=====

Library for scoring password complexity. 
We have created it to be simpler than `zxcvbn` (that currently throws warnings in php 8.1).

We do not provide any warranties, use at your own responsibility.

### Limitations
- Tested only in PHP 8.1
- No dictionary, or leaked passwords checks

Usage
-----

~~~php
$p = new Passy();
$score = $p->score($password);
if ($score <= 65) {
    die('Password is unsecure, try adding more symbols or use longer passphrase.')
}
~~~

How score is calculated?
------------------------

- String characters are sorted by categories: numbers, uppercase letters, lowercase letters and symbols (any other character).
- Unique characters are counted
- Unique symbols are counted

Final score is calculated as: `passwordLength + (10 * nonEmptyCategories) + (uniqueCharacters * 0.5) + (2 * uniqueSymbols)` 
floored and lowered to 100.

For example `LetMeKno*w2*` gets 58.

### How did we found to this formula?

We have eyeballed it.

## License

MIT

## Contributing

Fill an issue, we will talk about it and then let's code!

