<?php

declare(strict_types=1);

namespace SpeedPuzzling\Web\Tests\Value;

use PHPUnit\Framework\Attributes\DataProvider;
use SpeedPuzzling\Web\Value\SolvingTime;
use PHPUnit\Framework\TestCase;

class SolvingTimeTest extends TestCase
{
    #[DataProvider('provideFromInputData')]
    public function testFromInput(string $input, int $expected): void
    {
        $time = SolvingTime::fromUserInput($input);

        self::assertSame($expected, $time->seconds);
    }

    public static function provideFromInputData(): \Generator
    {
        yield ['1:01:06', 3666];

        yield ['01:06', 66];
    }
}
