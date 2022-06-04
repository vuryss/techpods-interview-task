<?php

declare(strict_types=1);

namespace App\Calculator;

use App\Calculator\Errors\SyntaxError;
use App\Calculator\Token\NumberToken;
use App\Calculator\Token\OperatorToken;
use App\Calculator\Token\TokenList;

use function in_array;
use function mb_strlen;

class Lexer
{
    /**
     * @throws SyntaxError
     */
    public function tokenize(string $expression): TokenList
    {
        $expression = str_replace(["\n", "\r", "\t"], '', $expression);
        $length = mb_strlen($expression);
        $tokens = [];
        $cursor = 0;

        while ($cursor < $length) {
            $unTokenizedPart = substr($expression, $cursor);

            if ($unTokenizedPart[0] === ' ') {
                $cursor++;
                continue;
            }

            if (preg_match('/^\d+(\.\d+)?/', $unTokenizedPart, $matches)) {
                /** @var numeric-string $number */
                $number = $matches[0];
                $tokens[] = new NumberToken($number);
                $cursor += mb_strlen($matches[0]);
            } elseif (in_array($unTokenizedPart[0], Operator::ALL, true)) {
                $tokens[] = new OperatorToken($unTokenizedPart[0]);
                $cursor++;
            } else {
                throw new SyntaxError(sprintf(
                    'Unexpected %s character at position %s',
                    $unTokenizedPart[0],
                    $cursor
                ));
            }
        }

        return new TokenList($tokens);
    }
}
