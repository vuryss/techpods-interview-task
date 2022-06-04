<?php

declare(strict_types=1);

namespace App\Tests\Calculator\Token;

use App\Calculator\Token\NumberToken;
use App\Calculator\Token\OperatorToken;
use App\Calculator\Token\TokenList;
use PHPUnit\Framework\TestCase;
use RuntimeException;
use stdClass;

class TokenListTest extends TestCase
{
    public function testHappyPath(): void
    {
        $tokens = [
            new NumberToken('5'),
            new OperatorToken('+'),
            new NumberToken('7'),
        ];

        $tokenList = new TokenList($tokens);

        $this->assertFalse($tokenList->empty());
        $this->assertEquals($tokens[0], $tokenList->current());
        $this->assertEquals($tokens[1], $tokenList->next());
        $this->assertEquals($tokens[2], $tokenList->next());
        $this->assertNull($tokenList->next());
    }

    public function testEmpty(): void
    {
        $tokenList = new TokenList([]);

        $this->assertTrue($tokenList->empty());
        $this->assertNull($tokenList->current());
        $this->assertNull($tokenList->next());
    }

    public function testInvalidContents(): void
    {
        $this->expectException(RuntimeException::class);
        /** @noinspection PhpParamsInspection */
        new TokenList([new stdClass()]);
    }
}
