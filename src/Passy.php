<?php
declare(strict_types=1);

namespace Codename\Passy;

class Passy
{
    const NUMBERS = 'n';             // 0 - 9
    const LOWERCASE_LETTERS = 'll';  // a - z
    const UPPERCASE_LETTERS = 'ul';  // A - Z
    const SYMBOLS = 's';             // other

    const ORDS = [
        self::NUMBERS => [48, 57],
        self::UPPERCASE_LETTERS => [65, 90],
        self::LOWERCASE_LETTERS => [97, 122],
    ];

    public function stringToOrd(string $s): int
    {
        if (mb_strlen($s) !== 1) {
            throw new \RuntimeException('Use only one char strings');
        }
        return mb_ord($s);
    }

    public function ordToCat(int $ord)
    {
        foreach (self::ORDS as $cat => $limits) {
            list($min, $max) = $limits;
            if ($ord >= $min && $ord <= $max) {
                return $cat;
            }
        }
        return self::SYMBOLS;
    }

    public function score(string $password): int
    {
        $categories = [];
        $letterCount = mb_strlen($password);
        $uniqueCharacters = [];
        $uniqueSymbols = [];

        foreach (mb_str_split($password) as $character) {
            $uniqueCharacters[(string)$character] = true;
            $c = $this->ordToCat($this->stringToOrd($character));
            if (!isset($categories[$c])) {
                $categories[$c] = 0;
            }
            if ($c === self::SYMBOLS) {
                $uniqueSymbols[(string)$character] = true;
            }
            $categories[$c]++;
        }

        $score = $letterCount;
        $score += count($categories) * 10;
        $score += count($uniqueCharacters) * 0.5;
        $score += count($uniqueSymbols) * 2;

        return (int)floor(max(min($score, 100), 0));
    }
}