<?php

/** @noinspection PhpUnhandledExceptionInspection */

declare(strict_types=1);

namespace App\Tests\Calculator\Token;

use App\Calculator\Errors\SyntaxError;
use App\Calculator\Token\NumberToken;
use PHPUnit\Framework\TestCase;

class NumberTokenTest extends TestCase
{
    /**
     * @return string[][]
     */
    public function validNumberProvider(): array
    {
        return [
            'Single digit' => ['3'],
            'Large number' => ['1234567891234567812312321'],
            'Decimal number' => ['24.3'],
            'Decimal number less than 1' => ['0.123'],
            'High precision' => ['12.123456789'],
        ];
    }

    /**
     * @dataProvider validNumberProvider
     */
    public function testValidNumbers(string $value): void
    {
        $number = new NumberToken($value);
        $this->assertEquals($value, $number->value());
    }

    /**
     * @return string[][]
     */
    public function invalidNumbersProvider(): array
    {
        return [
            'Leading zeroes' => ['07'],
            'Invalid characters' => ['a'],
            'Invalid characters 2' => ['5g'],
            'Decimal number without precision' => ['1.'],
            'Decimal number too many leading zeroes' => ['00.5'],
        ];
    }

    /**
     * @dataProvider invalidNumbersProvider
     */
    public function testInvalidNumbers(string $value): void
    {
        $this->expectException(SyntaxError::class);
        new NumberToken($value);
    }
}
