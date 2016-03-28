<?php

use Import;
class AnonymousClass0
{
    public function method($parameter = '')
    {
        return isset($parameter) ? $parameter : 'no parameter set';
    }
}
class AnonymousClass1
{
    public function anotherMethod($integer)
    {
        return $integer < 3 ? -1 : ($integer == 3 ? 0 : 1);
    }
}
class Test
{
    public function test()
    {
        $class = new AnonymousClass0();
        $class->method();
        $anotherClass = new AnonymousClass1();
    }
}
