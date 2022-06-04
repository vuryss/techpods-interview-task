<?php

declare(strict_types=1);

namespace App\Calculator\Node;

use App\Calculator\Errors\SyntaxError;

class NumberNode implements Node
{
    /**
     * @param numeric-string $value
     *
     * @throws SyntaxError
     */
    public function __construct(private readonly string $value)
    {
        if (!preg_match('/^\d+(\.\d+)?$/', $this->value)) {
            throw new SyntaxError('Invalid number node: ' . $this->value);
        }
    }

    public function evaluate(): string
    {
        return $this->value;
    }
}
