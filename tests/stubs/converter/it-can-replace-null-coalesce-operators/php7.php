<?php

function test1() {return null;}
function test2() {return null;}

class Test {public function testMethod() {return null;}}

$test = new Test();

$result = $input ?? 'fixed-value';

$result = $input ?? $input2 ?? $input3;

$result = test1() ?? test2() ?? 0;

if (null === $input ?? null) {
}

$result = $test->testMethod() ?? 0;