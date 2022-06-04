<?php

/** @noinspection PhpUnhandledExceptionInspection */

declare(strict_types=1);

namespace App\Tests\Calculator;

use App\Calculator\Config;
use App\Calculator\Errors\SyntaxError;
use App\Calculator\Operator;
use App\Calculator\Parser;
use App\Calculator\Token\NumberToken;
use App\Calculator\Token\OperatorToken;
use App\Calculator\Token\TokenList;
use PHPUnit\Framework\TestCase;

class ParserTest extends TestCase
{
    public function validParserProvider(): array
    {
        return [
            'addition' => [
                'tokenList' => new TokenList([
                    new NumberToken('5'),
                    new OperatorToken(Operator::ADDITION),
                    new NumberToken('7'),
                ]),
                'expectedResult' => '12',
            ],
            'subtraction' => [
                'tokenList' => new TokenList([
                    new NumberToken('5'),
                    new OperatorToken(Operator::SUBTRACTION),
                    new NumberToken('7'),
                ]),
                'expectedResult' => '-2',
            ],
            'multiplication' => [
                'tokenList' => new TokenList([
                    new NumberToken('5'),
                    new OperatorToken(Operator::MULTIPLICATION),
                    new NumberToken('7'),
                ]),
                'expectedResult' => '35',
            ],
            'division' => [
                'tokenList' => new TokenList([
                    new NumberToken('5'),
                    new OperatorToken(Operator::DIVISION),
                    new NumberToken('7'),
                ]),
                'expectedResult' => '0.71428571428',
            ],
            'multiplication precedence over addition' => [
                'tokenList' => new TokenList([
                    new NumberToken('5'),
                    new OperatorToken(Operator::ADDITION),
                    new NumberToken('7'),
                    new OperatorToken(Operator::MULTIPLICATION),
                    new NumberToken('2'),
                ]),
                'expectedResult' => '19',
            ],
            'multiplication precedence over subtraction' => [
                'tokenList' => new TokenList([
                    new NumberToken('5'),
                    new OperatorToken(Operator::MULTIPLICATION),
                    new NumberToken('7'),
                    new OperatorToken(Operator::SUBTRACTION),
                    new NumberToken('2'),
                ]),
                'expectedResult' => '33',
            ],
            'left to right when operators have same precedence' => [
                'tokenList' => new TokenList([
                    new NumberToken('6'),
                    new OperatorToken(Operator::DIVISION),
                    new NumberToken('2'),
                    new OperatorToken(Operator::MULTIPLICATION),
                    new NumberToken('3'),
                ]),
                'expectedResult' => '9',
            ],
        ];
    }

    /**
     * @dataProvider validParserProvider
     */
    public function testValidParsing(TokenList $tokenList, string $expectedResult): void
    {
        $expectedResult = bcadd($expectedResult, '0', Config::PRECISION);

        $parser = new Parser($tokenList);
        $result = $parser->parse();
        $this->assertEquals($expectedResult, $result->evaluate());
    }

    public function testWithEmptyTokenList(): void
    {
        $parser = new Parser(new TokenList([]));
        $result = $parser->parse();

        $this->assertEquals('0', $result->evaluate());
    }

    public function invalidParserData(): array
    {
        return [
            'operator only' => [
                'tokenList' => new TokenList([
                    new OperatorToken('-'),
                ]),
            ],
            'consecutive numbers' => [
                'tokenList' => new TokenList([
                    new NumberToken('5'),
                    new NumberToken('6'),
                ]),
            ],
            'consecutive operators' => [
                'tokenList' => new TokenList([
                    new OperatorToken('-'),
                    new OperatorToken('-'),
                    new NumberToken('5'),
                ]),
            ],
        ];
    }

    /**
     * @dataProvider invalidParserData
     */
    public function testInvalidValues(TokenList $tokenList): void
    {
        $this->expectException(SyntaxError::class);
        (new Parser($tokenList))->parse();
    }
}
