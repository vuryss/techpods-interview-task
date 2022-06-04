<?php

declare(strict_types=1);

namespace App\Tests\Calculator\Node;

use App\Calculator\Config;
use App\Calculator\Errors\SyntaxError;
use App\Calculator\Node\Node;
use App\Calculator\Node\NumberNode;
use App\Calculator\Node\UnaryNode;
use App\Calculator\Operator;
use PHPUnit\Framework\TestCase;

class UnaryNodeTest extends TestCase
{
    public function validUnaryValuesProvider(): array
    {
        return [
            'negating positive number' => [
                'operator' => Operator::SUBTRACTION,
                'target' => new NumberNode('5'),
                'expectedResult' => '-5',
            ],
            'negative of negative number' => [
                'operator' => Operator::SUBTRACTION,
                'target' => new UnaryNode(Operator::SUBTRACTION, new NumberNode('5')),
                'expectedResult' => '5',
            ]
        ];
    }

    /**
     * @dataProvider validUnaryValuesProvider
     */
    public function testValidUnaryNode(string $operator, Node $target, string $expectedResult): void
    {
        $node = new UnaryNode($operator, $target);
        $expectedResult = bcadd($expectedResult, '0', Config::PRECISION);
        $this->assertEquals($expectedResult, $node->evaluate());
    }

    public function invalidUnaryValuesProvider(): array
    {
        return [
            'invalid operator' => [
                'operator' => Operator::MULTIPLICATION,
                'target' => new NumberNode('5'),
            ]
        ];
    }

    /**
     * @dataProvider invalidUnaryValuesProvider
     */
    public function testInvalidUnaryNode(string $operator, Node $target): void
    {
        $this->expectException(SyntaxError::class);
        (new UnaryNode($operator, $target))->evaluate();
    }
}
