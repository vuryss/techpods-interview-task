<?php

declare(strict_types=1);

namespace App\Calculator\Node;

interface Node
{
    /**
     * @return numeric-string
     */
    public function evaluate(): string;
}
