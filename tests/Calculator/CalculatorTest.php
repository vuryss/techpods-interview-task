<?php

/** @noinspection PhpUnhandledExceptionInspection */

declare(strict_types=1);

namespace App\Tests\Calculator;

use App\Calculator\Calculator;
use App\Calculator\Errors\SyntaxError;
use PHPUnit\Framework\TestCase;

class CalculatorTest extends TestCase
{
    public function validExpressionProvider(): array
    {
        return [
            'precedence test' => [
                'expression' => '5+3*7-2*3+82/2',
                'expectedResult' => '61',
            ],
            'complex expression 1' => [
                'expression' => '147/395*-0.2-947/7',
                'expectedResult' => '-135.3601446654',
            ],
            'complex expression 2' => [
                'expression' => '-937+54/1024+3717/9',
                'expectedResult' => '-523.947265625',
            ],
        ];
    }

    /**
     * @dataProvider validExpressionProvider
     */
    public function testValidCalculations(string $expression, string $expectedResult): void
    {
        $calculator = new Calculator();
        $result = $calculator->evaluateExpression($expression);
        $this->assertEquals($expectedResult, $result);
    }

    public function invalidExpressionProvider(): array
    {
        return [
            'division by zero' => [
                'expression' => '5+7/0',
                'expectedError' => 'Division by zero is not allowed in this universe. Try in another.',
            ],
            'consecutive operators' => [
                'expression' => '5+*7',
                'expectedError' => 'Unexpected operator *',
            ],
            'unexpected expression end' => [
                'expression' => '25/',
                'expectedError' => 'Unexpected end of expression! Please provide complete arithmetic expression.',
            ],
        ];
    }

    /**
     * @dataProvider invalidExpressionProvider
     */
    public function testInvalidCalculations(string $expression, string $expectedError): void
    {
        $this->expectException(SyntaxError::class);
        $this->expectExceptionMessage($expectedError);
        $calculator = new Calculator();
        $calculator->evaluateExpression($expression);
    }
}
