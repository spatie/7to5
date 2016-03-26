<?php

use Import;

class Test
{
    public function test()
    {
        $class = new class('test') {
            
            public function method() {
                return true;
            }
        };
        
        $class->method();
    }
            
}
