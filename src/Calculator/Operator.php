<?php

declare(strict_types=1);

namespace App\Calculator;

interface Operator
{
    public const SUBTRACTION = '-';
    public const ADDITION = '+';
    public const MULTIPLICATION = '*';
    public const DIVISION = '/';

    public const ALL = [
        self::SUBTRACTION,
        self::ADDITION,
        self::MULTIPLICATION,
        self::DIVISION,
    ];
}
