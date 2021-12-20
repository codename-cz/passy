<?php
declare(strict_types=1);

namespace Codename\Passy;

use PHPUnit\Framework\TestCase;

class PassyTest extends TestCase
{


    public function provideOrd()
    {
        return [
            [97, 'a'],
            [65, 'A'],
            [382, 'ž'],
            [32, ' '],
            [190, '¾'],
            [48, '0'],
            [57, '9'],
        ];
    }

    /**
     * @dataProvider provideOrd
     */
    public function testBasicOrdWorks($expected, $input): void
    {
        $passy = new Passy();
        $this->assertSame(
            $expected,
            $passy->stringToOrd($input)
        );
    }

    public function provideCat()
    {
        return [
            [Passy::LOWERCASE_LETTERS, 'a'],
            [Passy::UPPERCASE_LETTERS, 'A'],
            [Passy::SYMBOLS, 'ž'],
            [Passy::SYMBOLS, ' '],
            [Passy::SYMBOLS, '¾'],
            [Passy::NUMBERS, '0'],
            [Passy::NUMBERS, '9'],
        ];
    }

    /**
     * @dataProvider provideCat
     */
    public function testBasicCatWorks($expected, $input): void
    {
        $passy = new Passy();
        $this->assertSame(
            $expected,
            $passy->ordToCat($passy->stringToOrd($input))
        );
    }

    public function provideScores()
    {
        /*
         * | Password                                | Categories | Length | Unique | Unique Symbols | Sum   | Floor
         *   a                                         10           1        0.5      0                11.5    11
         *   hello                                     10           5        2        0                17      17
         *   LetMeKno*w2                               40           11       5        2                58      58
         *   e2787544-c374-4b71-ac9d-872f22167190      30           36       8.5      2                76.5    76
         *   ------------------aaaaaaaaaaaaaaaaaa      20           36       1        2                59      59
         *   *-+                                       10           3        1.5      6                20.5    20
         */
        return [
            [11, 'a'], // 10 for cat, 1 for length, 1 for unique
            [17, 'hello'],
            [58, 'LetMeKno*w2'], // 40 for cat, 11 for length, 10 for unique,  2 for *
            [76, 'e2787544-c374-4b71-ac9d-872f22167190'], // 30 for cat, 36 for lenght, 2 for -, 16 for unique
            [59, '------------------aaaaaaaaaaaaaaaaaa'], // long but stupid
            [20, '*-+'], // 10 for cat, 3 for lenght, 3 for unique, 6 for unique symbols
        ];
    }

    /**
     * @dataProvider provideScores
     */
    public function testScore($expected, $input): void
    {
        $passy = new Passy();
        $this->assertSame(
            $expected,
            $passy->score($input)
        );
    }

    public function testScoreMaxLimit(): void
    {
        $passy = new Passy();
        $this->assertSame(
            100,
            $passy->score('+-*/d98s98dfs98989sd999ššččščš4444-----********++8d98dsf989')
        );
    }
}