<?php

namespace oat\beeme\tests;

use PHPUnit_Framework_TestCase;
use oat\beeme\Parser;

class ParserTest extends PHPUnit_Framework_TestCase
{
    public function testCanCreateParser()
    {
        $this->assertInstanceOf('oat\beeme\Parser', new Parser());
    }
    
    /**
     * @dataProvider evaluateProvider
     */
    public function testEvaluate($input, $expected)
    {
        $parser = new Parser();
        $result = $parser->evaluate($input);
        $this->assertSame($expected, $result, "Expression Evaluation: 'result of expression = '${input}' must be equal to '${expected}' but is '${result}'.");
    }
    
    public function evaluateProvider()
    {
        return array(
            array('0', 0.),
            array('9', 9.),
            array('0 + 0', 0.),
            array('3 + 4 * 2', 11.),
            array('2 ^ 3', 8.),
            array('2 ^ 3 * 2', 16.),
            array('(3 * 4) ^ 2 + 3', 147.),
            array('2 ^ 2 ^ 3', 256.),
            array('2 ^ 2 * 3', 12.),
            array('-1 + 0', -1.),
            array('0 + -1', -1.),
            array('+0 + 1', 1.),
            array('9 + +10', 19.),
            array('+1 - -1', 2.),
            
            array('1 = 1', true),
            array('0 = 1', false),
        );
    }
    
    /**
     * @dataProvider divisionByZeroProvider
     */
    public function testDivisionByZero($input)
    {
        $this->setExpectedException('\RangeException');
        
        $parser = new Parser();
        $parser->evaluate($input);
    }
    
    public function divisionByZeroProvider()
    {
        return array(
            array('1 / 0'),
            array('0 / 0'),
            array('21 + 3 / ( 1 - 1 )')
        );
    }
}
