<?php

declare(strict_types=1);

namespace App\Calculator\Token;

use App\Calculator\Errors\SyntaxError;
use App\Calculator\Operator;

use function in_array;

class OperatorToken implements Token
{
    /**
     * @throws SyntaxError
     */
    public function __construct(private readonly string $value)
    {
        $this->validate();
    }

    public function value(): string
    {
        return $this->value;
    }

    /**
     * @throws SyntaxError
     */
    private function validate(): void
    {
        if (!in_array($this->value, Operator::ALL, true)) {
            throw new SyntaxError(sprintf('Unsupported operator %s.', $this->value));
        }
    }
}
