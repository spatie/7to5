<?php

function test1()
{
    return null;
}
function test2()
{
    return null;
}
class Test
{
    public function testMethod()
    {
        return null;
    }
}
$test = new Test();
$result = isset($input) ? $input : 'fixed-value';
$result = isset($input) ? $input : (isset($input2) ? $input2 : $input3);
$result = !empty(test1()) ? test1() : (!empty(test2()) ? test2() : 0);
if (null === (isset($input) ? $input : null)) {
}
$result = !empty($test->testMethod()) ? $test->testMethod() : 0;