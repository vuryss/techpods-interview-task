<?php

declare(strict_types=1);

namespace App\Calculator\Token;

use RuntimeException;

use function count;

class TokenList
{
    private int $cursor = 0;
    private int $size;

    /**
     * @param Token[] $tokens
     */
    public function __construct(private readonly array $tokens)
    {
        foreach ($this->tokens as $token) {
            if (false === ($token instanceof Token)) {
                throw new RuntimeException(sprintf(
                    '%s supports only %s items',
                    self::class,
                    Token::class
                ));
            }
        }

        $this->size = count($this->tokens);
    }

    public function current(): ?Token
    {
        return $this->tokens[$this->cursor] ?? null;
    }

    public function empty(): bool
    {
        return $this->size === 0;
    }

    public function next(): ?Token
    {
        $this->cursor++;

        return $this->current();
    }
}
