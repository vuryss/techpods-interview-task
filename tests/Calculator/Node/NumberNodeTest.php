<?php

/** @noinspection PhpUnhandledExceptionInspection */

declare(strict_types=1);

namespace App\Tests\Calculator\Node;

use App\Calculator\Errors\SyntaxError;
use App\Calculator\Node\NumberNode;
use PHPUnit\Framework\TestCase;

class NumberNodeTest extends TestCase
{
    public function validNumberProvider(): array
    {
        return [
            'zero' => ['0'],
            'valid' => ['123'],
            'valid decimal' => ['12.54'],
        ];
    }

    /**
     * @dataProvider validNumberProvider
     */
    public function testValidNumberNode(string $number): void
    {
        $node = new NumberNode($number);
        $this->assertEquals($number, $node->evaluate());
    }

    public function invalidNumberProvider(): array
    {
        return [
            'negative number' => ['-5'],
            'number without whole part' => ['.43'],
            'not a number' => ['asd'],
        ];
    }

    /**
     * @dataProvider invalidNumberProvider
     */
    public function testInvalidNumberNode(string $number): void
    {
        $this->expectException(SyntaxError::class);
        new NumberNode($number);
    }
}
