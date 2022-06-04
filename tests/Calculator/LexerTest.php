<?php

/** @noinspection PhpUnhandledExceptionInspection */

declare(strict_types=1);

namespace App\Tests\Calculator;

use App\Calculator\Errors\SyntaxError;
use App\Calculator\Lexer;
use App\Calculator\Token\NumberToken;
use App\Calculator\Token\OperatorToken;
use App\Calculator\Token\TokenList;
use PHPUnit\Framework\TestCase;

class LexerTest extends TestCase
{
    public function validExpressionsProvider(): iterable
    {
        $expression = ' ';
        $tokenList = new TokenList([]);

        yield 'empty expression' => [
            'expression' => $expression,
            'expectedTokenList' => $tokenList
        ];

        $expression = '5';
        $tokenList = new TokenList([
            new NumberToken('5'),
        ]);

        yield 'single digit' => [
            'expression' => $expression,
            'expectedTokenList' => $tokenList
        ];

        $expression = '35 45';
        $tokenList = new TokenList([
            new NumberToken('35'),
            new NumberToken('45'),
        ]);

        yield 'multiple numbers' => [
            'expression' => $expression,
            'expectedTokenList' => $tokenList
        ];

        $expression = '/*-';
        $tokenList = new TokenList([
            new OperatorToken('/'),
            new OperatorToken('*'),
            new OperatorToken('-'),
        ]);

        yield 'multiple operators' => [
            'expression' => $expression,
            'expectedTokenList' => $tokenList
        ];

        $expression = '5 + 8';
        $tokenList = new TokenList([
            new NumberToken('5'),
            new OperatorToken('+'),
            new NumberToken('8'),
        ]);

        yield 'simple expression' => [
            'expression' => $expression,
            'expectedTokenList' => $tokenList
        ];

        $expression = '-15.5 
        / 7 
        * 15 
        - 23 
        + 5.12';
        $tokenList = new TokenList([
            new OperatorToken('-'),
            new NumberToken('15.5'),
            new OperatorToken('/'),
            new NumberToken('7'),
            new OperatorToken('*'),
            new NumberToken('15'),
            new OperatorToken('-'),
            new NumberToken('23'),
            new OperatorToken('+'),
            new NumberToken('5.12'),
        ]);

        yield 'complex expression with strange formatting' => [
            'expression' => $expression,
            'expectedTokenList' => $tokenList
        ];
    }

    /**
     * @dataProvider validExpressionsProvider
     */
    public function testValidExpressionTokenization(string $expression, TokenList $expectedTokenList): void
    {
        $lexer = new Lexer();
        $tokenList = $lexer->tokenize($expression);

        $this->assertEquals($expectedTokenList->empty(), $tokenList->empty());

        while ($expectedTokenList->current()) {
            $this->assertEquals($expectedTokenList->current()->value(), $tokenList->current()->value());
            $this->assertEquals($expectedTokenList->next(), $tokenList->next());
        }
    }

    public function invalidExpressionProvider(): array
    {
        return [
            'invalid character' => ['a'],
            'invalid character 2' => ['%'],
            'invalid character 3' => ['('],
            'invalid character 4' => ['@'],
            'invalid character 5' => [','],
            'expression with invalid character' => ['5 + 7a'],
        ];
    }

    /**
     * @dataProvider invalidExpressionProvider
     */
    public function testInvalidExpressions(string $expression): void
    {
        $this->expectException(SyntaxError::class);

        $lexer = new Lexer();
        $lexer->tokenize($expression);
    }
}
