<?php

use Import;
class AnonymousClass0
{
    public function method()
    {
        return true;
    }
}
class Test
{
    public function test()
    {
        $class = new AnonymousClass0();
        $class->method();
    }
}
