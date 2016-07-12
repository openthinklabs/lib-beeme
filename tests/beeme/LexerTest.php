<?php

namespace oat\beeme\tests;

use PHPUnit_Framework_TestCase;
use oat\beeme\Lexer;
use oat\beeme\Token;
use oat\beeme\FunctionToken;
use oat\beeme\Operator;

class LexerTest extends PHPUnit_Framework_TestCase
{
    public function testCanCreateParser()
    {
        $this->assertInstanceOf('oat\beeme\Lexer', new Lexer());
    }
    
    /**
     * @dataProvider tokenizeProvider
     */
    public function testTokenize(array $expected, $expression)
    {
        $lexer = new Lexer();
        $tokens = $lexer->tokenize($expression);
        
        $expectedCount = count($expected);
        $tokenCount = count($tokens);
        
        $this->assertEquals($expectedCount, $tokenCount, "${expectedCount} tokens are expected but ${tokenCount} were built for expression '${expression}'.");
        
        for ($i = 0; $i < $tokenCount; $i++) {
            $expectedToken = $expected[$i];
            $token = $tokens[$i];
            
            $this->assertEquals(get_class($expectedToken), get_class($token), "Token ${i} issue.");
            
            if ($token instanceof Token) {
                $this->assertSame($expectedToken->getValue(), $token->getValue());
                $this->assertSame($expectedToken->getType(), $token->getType());
            }
        }
    }
    
    public function tokenizeProvider()
    {
        return array(
            array(
                array(
                    new Token(1., Token::T_OPERAND)
                ),
                '1'
            ),
            array(
                array(
                    new Token(1., Token::T_OPERAND),
                    new Operator('+', 0, Operator::O_LEFT_ASSOCIATIVE),
                    new Token(1., Token::T_OPERAND),
                ),
                '1 + 1'
            ),
            array(
                array(
                    new Token(1., Token::T_OPERAND),
                    new Operator('+', 0, Operator::O_LEFT_ASSOCIATIVE),
                    new Token('(', Token::T_LEFT_BRACKET),
                    new Token(1., Token::T_OPERAND),
                    new Operator('*', 1, Operator::O_LEFT_ASSOCIATIVE),
                    new Token(3., Token::T_OPERAND),
                    new Token(')', Token::T_RIGHT_BRACKET),
                ),
                '1 + ( 1 * 3 )'
            ),
            array(
                array(
                    new FunctionToken('abs'),
                    new Token('(', Token::T_LEFT_BRACKET),
                    new Token(1., Token::T_OPERAND),
                    new Token(')', Token::T_RIGHT_BRACKET)
                ),
                'abs(1)'
            ),
            array(
                array(
                    new FunctionToken('abs'),
                    new Token('(', Token::T_LEFT_BRACKET),
                    new Token(')', Token::T_RIGHT_BRACKET)
                ),
                'abs()'
            ),
            array(
                array(
                    new Token(1., Token::T_OPERAND),
                    new Operator('+', 0, Operator::O_LEFT_ASSOCIATIVE),
                    new Token('(', Token::T_LEFT_BRACKET),
                    new FunctionToken('abs'),
                    new Token('(', Token::T_LEFT_BRACKET),
                    new Token(1., Token::T_OPERAND),
                    new Token(')', Token::T_RIGHT_BRACKET),
                    new Operator('+', 0, Operator::O_LEFT_ASSOCIATIVE),
                    new Token(4., Token::T_OPERAND),
                    new Token(')', Token::T_RIGHT_BRACKET),
                ),
                '1 + (abs(1) + 4)'
            ),
        );
    }
}
