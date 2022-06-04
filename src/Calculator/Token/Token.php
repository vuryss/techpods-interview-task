<?php

declare(strict_types=1);

namespace App\Calculator\Token;

interface Token
{
    public function value(): string;
}
