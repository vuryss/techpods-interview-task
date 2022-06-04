<?php

declare(strict_types=1);

namespace App\Calculator\Token;

use App\Calculator\Errors\SyntaxError;

class NumberToken implements Token
{
    /**
     * @param numeric-string $value
     *
     * @throws SyntaxError
     */
    public function __construct(private readonly string $value)
    {
        $this->validate();
    }

    /**
     * @return numeric-string
     */
    public function value(): string
    {
        return $this->value;
    }

    /**
     * @throws SyntaxError
     */
    private function validate(): void
    {
        if (!preg_match('/^\d+(\.\d+)?$/', $this->value)) {
            throw new SyntaxError(sprintf('Invalid number %s', $this->value));
        }

        // Whole numbers should not start with 0
        if (ctype_digit($this->value)) {
            if ($this->value[0] === '0' && mb_strlen($this->value) > 1) {
                throw new SyntaxError(sprintf('Invalid number %s', $this->value));
            }

            return;
        }

        // Numbers with decimal separator can start with single 0
        if ($this->value[0] === '0' && $this->value[1] !== '.') {
            throw new SyntaxError(sprintf('Invalid number %s', $this->value));
        }
    }
}
