<?php

declare(strict_types=1);

namespace App\Calculator\Node;

use App\Calculator\Config;
use App\Calculator\Errors\SyntaxError;
use App\Calculator\Operator;

class BinaryNode implements Node
{
    public function __construct(
        private readonly string $operator,
        private readonly Node $leftNode,
        private readonly Node $rightNode
    ) {
    }

    /**
     * @throws SyntaxError
     */
    public function evaluate(): string
    {
        $leftValue = $this->leftNode->evaluate();
        $rightValue = $this->rightNode->evaluate();

        return match ($this->operator) {
            Operator::SUBTRACTION => bcsub($leftValue, $rightValue, Config::PRECISION),
            Operator::ADDITION => bcadd($leftValue, $rightValue, Config::PRECISION),
            Operator::MULTIPLICATION => bcmul($leftValue, $rightValue, Config::PRECISION),
            Operator::DIVISION => $this->performDivision($leftValue, $rightValue),
            default => throw new SyntaxError(sprintf('Unsupported binary operator %s', $this->operator)),
        };
    }

    /**
     * @param numeric-string $dividend
     * @param numeric-string $divisor
     *
     * @throws SyntaxError
     * @return numeric-string
     */
    private function performDivision(string $dividend, string $divisor): string
    {
        if (bccomp($divisor, '0', Config::PRECISION) === 0) {
            throw new SyntaxError('Division by zero is not allowed in this universe. Try in another.');
        }

        return bcdiv($dividend, $divisor, Config::PRECISION);
    }
}
