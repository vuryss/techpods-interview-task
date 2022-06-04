<?php

declare(strict_types=1);

namespace App\Calculator;

use App\Calculator\Errors\SyntaxError;

class Calculator
{
    /**
     * @throws SyntaxError
     */
    public function evaluateExpression(string $expression): string
    {
        $lexer = new Lexer();
        $tokenList = $lexer->tokenize($expression);

        $parser = new Parser($tokenList);
        $node = $parser->parse();

        $result = $node->evaluate();

        if (str_contains($result, '.')) {
            $result = rtrim($result, '0');
            $result = rtrim($result, '.');
        }

        return $result;
    }
}
