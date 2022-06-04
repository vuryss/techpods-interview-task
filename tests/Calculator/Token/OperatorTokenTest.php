<?php

/** @noinspection PhpUnhandledExceptionInspection */

declare(strict_types=1);

namespace App\Tests\Calculator\Token;

use App\Calculator\Errors\SyntaxError;
use App\Calculator\Token\OperatorToken;
use PHPUnit\Framework\TestCase;

class OperatorTokenTest extends TestCase
{
    /**
     * @return string[][]
     */
    public function validOperatorProvider(): array
    {
        return [
            'minus' => ['-'],
            'plus' => ['+'],
            'star' => ['*'],
            'forward slash' => ['/'],
        ];
    }

    /**
     * @dataProvider validOperatorProvider
     */
    public function testValidOperators(string $value): void
    {
        $operator = new OperatorToken($value);
        $this->assertEquals($value, $operator->value());
    }

    /**
     * @return string[][]
     */
    public function invalidOperatorProvider(): array
    {
        return [
            'number' => ['5'],
            'double operators' => ['++'],
            'letter' => ['a'],
            'symbol' => ['&'],
        ];
    }

    /**
     * @dataProvider invalidOperatorProvider
     */
    public function testInvalidOperators(string $value): void
    {
        $this->expectException(SyntaxError::class);
        new OperatorToken($value);
    }
}
