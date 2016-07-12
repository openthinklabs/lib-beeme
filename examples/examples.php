<?php

/**
 * Everything is relative to the application root now.
 */
chdir(dirname(__DIR__));

require 'vendor/autoload.php';

$parser = new \oat\beeme\Parser();
$expression = '1 + 2 * 3 * ( 7 * 8 ) - ( 45 - 10 )';
$result = $parser->evaluate($expression);

printf('%s => %f;%s', $expression, $result,  PHP_EOL);

$expression = '11 - -2 * -3 * ( 17 * 81 ) - ( -45 - 10 )';
$result = $parser->evaluate($expression);

printf('%s => %f;%s', $expression, $result,  PHP_EOL);

$expression = '-1 + -2 * 13 * ( 7 * 8 ) - ( 415 - 0.1 )';
$result = $parser->evaluate($expression);

printf('%s => %f;%s', $expression, $result,  PHP_EOL);

$expression = '3 + x';
$result = $parser->evaluate(
    $expression,
    ['x' => 3]
);

printf('%s => %f;%s', $expression, $result, PHP_EOL);

$expression = '1 + abs(x)';
$result = $parser->evaluate(
    $expression,
    ['x' => -10]
);

printf('%s => %f;%s', $expression, $result, PHP_EOL);
