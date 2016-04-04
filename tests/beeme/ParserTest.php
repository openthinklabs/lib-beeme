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
    public function testEvaluate($expected, $input, array $variables = array())
    {
        $parser = new Parser();
        $result = $parser->evaluate($input, $variables);
        $this->assertSame($expected, $result, "Expression Evaluation: 'result of expression = '${input}' must be equal to '${expected}' but is '${result}'.");
    }
    
    public function evaluateProvider()
    {
        return array(
            array(0., '0'),
            array(9., '9'),
            array(0., '0 + 0'),
            array(11., '3 + 4 * 2'),
            array(8., '2 ^ 3'),
            array(16., '2 ^ 3 * 2'),
            array(147., '(3 * 4) ^ 2 + 3'),
            array(256., '2 ^ 2 ^ 3'),
            array(12., '2 ^ 2 * 3'),
            array(-1., '-1 + 0'),
            array(-1., '0 + -1'),
            array(1., '+0 + 1'),
            array(19., '9 + +10'),
            array(2., '+1 - -1'),
            
            array(true, '1 = 1'),
            array(false, '0 = 1'),
            array(true, '1 = 1 = (1 + 1 - 1)'),
            array(true, 'y + 3*x = 5', array('x' => 5, 'y' => -10)),
            
            array(2., 'a + b', array('a' => 1., 'b' => 1.)),
            array(2., '_A + _B', array('_A' => 1., '_B' => 1.)),
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
