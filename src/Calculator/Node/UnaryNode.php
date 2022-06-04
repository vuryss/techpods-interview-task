<?php

declare(strict_types=1);

namespace App\Calculator\Node;

use App\Calculator\Config;
use App\Calculator\Errors\SyntaxError;
use App\Calculator\Operator;

class UnaryNode implements Node
{
    public function __construct(
        private readonly string $operator,
        private readonly Node $target
    ) {
    }

    /**
     * @throws SyntaxError
     */
    public function evaluate(): string
    {
        if ($this->operator === Operator::SUBTRACTION) {
            return bcmul($this->target->evaluate(), '-1', Config::PRECISION);
        }

        throw new SyntaxError(sprintf('Unsupported unary operator: %s', $this->operator));
    }
}
