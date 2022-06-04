<?php

/** @noinspection PhpUnhandledExceptionInspection */

declare(strict_types=1);

namespace App\Tests\Calculator\Node;

use App\Calculator\Config;
use App\Calculator\Errors\SyntaxError;
use App\Calculator\Node\BinaryNode;
use App\Calculator\Node\Node;
use App\Calculator\Node\NumberNode;
use App\Calculator\Operator;
use PHPUnit\Framework\TestCase;

class BinaryNodeTest extends TestCase
{
    public function validBinaryNodeProvider(): array
    {
        return [
            'addition' => [
                'operator' => Operator::ADDITION,
                'leftNode' => new NumberNode('5'),
                'rightNode' => new NumberNode('7'),
                'expectedResult' => '12',
            ],
            'subtraction' => [
                'operator' => Operator::SUBTRACTION,
                'leftNode' => new NumberNode('5'),
                'rightNode' => new NumberNode('7'),
                'expectedResult' => '-2',
            ],
            'multiplication' => [
                'operator' => Operator::MULTIPLICATION,
                'leftNode' => new NumberNode('5'),
                'rightNode' => new NumberNode('7'),
                'expectedResult' => '35',
            ],
            'division' => [
                'operator' => Operator::DIVISION,
                'leftNode' => new NumberNode('5'),
                'rightNode' => new NumberNode('7'),
                'expectedResult' => '0.71428571428',
            ],
        ];
    }

    /**
     * @dataProvider validBinaryNodeProvider
     */
    public function testValidBinaryNodeEvaluation(
        string $operator,
        Node $leftNode,
        Node $rightNode,
        string $expectedResult,
    ): void {
        $node = new BinaryNode($operator, $leftNode, $rightNode);
        $expectedResult = bcadd($expectedResult, '0', Config::PRECISION);
        $this->assertEquals($expectedResult, $node->evaluate());
    }

    public function invalidBinaryNodeProvider(): array
    {
        return [
            'division by zero' => [
                'operator' => Operator::DIVISION,
                'leftNode' => new NumberNode('5'),
                'rightNode' => new NumberNode('0'),
            ],
            'unsupported operator' => [
                'operator' => '%',
                'leftNode' => new NumberNode('5'),
                'rightNode' => new NumberNode('2'),
            ],
        ];
    }

    /**
     * @dataProvider invalidBinaryNodeProvider
     */
    public function testInvalidBinaryNodeEvaluation(string $operator, Node $leftNode, Node $rightNode): void
    {
        $this->expectException(SyntaxError::class);
        (new BinaryNode($operator, $leftNode, $rightNode))->evaluate();
    }
}
