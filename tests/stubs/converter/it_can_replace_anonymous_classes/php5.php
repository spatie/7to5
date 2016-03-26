<?php

use Import;
class AnonymousClass0
{
    public function method()
    {
        return true;
    }
}
class AnonymousClass1
{
    public function anotherMethod()
    {
        return false;
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
