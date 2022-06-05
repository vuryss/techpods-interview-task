<?php

declare(strict_types=1);

namespace App\Calculator;

use App\Calculator\Errors\SyntaxError;
use App\Calculator\Node\BinaryNode;
use App\Calculator\Node\Node;
use App\Calculator\Node\NumberNode;
use App\Calculator\Node\UnaryNode;
use App\Calculator\Token\NumberToken;
use App\Calculator\Token\OperatorToken;
use App\Calculator\Token\TokenList;

use function assert;

class Parser
{
    private const BINARY_OPERATOR_PRECEDENCE = [
        Operator::SUBTRACTION => 1,
        Operator::ADDITION => 1,
        Operator::MULTIPLICATION => 2,
        Operator::DIVISION => 2,
    ];

    public function __construct(private readonly TokenList $tokens)
    {
    }

    /**
     * @throws SyntaxError
     */
    public function parse(): Node
    {
        if ($this->tokens->empty()) {
            return new NumberNode('0');
        }

        return $this->parseWithPrecedence(0);
    }

    /**
     * @throws SyntaxError
     */
    private function parseWithPrecedence(int $operatorPrecedence): Node
    {
        $leftNode = $this->parseLeading();

        $token = $this->tokens->next();

        while ($token instanceof OperatorToken) {
            if (self::BINARY_OPERATOR_PRECEDENCE[$token->value()] > $operatorPrecedence) {
                $this->tokens->next();
                $rightNode = $this->parseWithPrecedence(self::BINARY_OPERATOR_PRECEDENCE[$token->value()]);

                $leftNode = new BinaryNode($token->value(), $leftNode, $rightNode);
                $token = $this->tokens->current();
                continue;
            }

            return $leftNode;
        }

        if ($token === null) {
            return $leftNode;
        }

        throw new SyntaxError(sprintf('Unexpected %s, expecting operator', $token->value()));
    }

    /**
     * @throws SyntaxError
     */
    private function parseLeading(): Node
    {
        $token = $this->tokens->current();

        if ($token instanceof OperatorToken) {
            if ($token->value() === Operator::SUBTRACTION) {
                $nextToken = $this->tokens->next();

                if (false === ($nextToken instanceof NumberToken)) {
                    throw new SyntaxError(sprintf('Expecting number after urinary operator %s', $token->value()));
                }

                return new UnaryNode($token->value(), new NumberNode($nextToken->value()));
            }

            throw new SyntaxError(sprintf('Unexpected operator %s', $token->value()));
        }

        if ($token instanceof NumberToken) {
            return new NumberNode($token->value());
        }

        assert($token === null);

        throw new SyntaxError('Unexpected end of expression! Please provide complete arithmetic expression.');
    }
}
