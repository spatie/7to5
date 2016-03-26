<?php

use Import;

class Test
{
    public function test()
    {
        $class = new class() {
            public function method() {
                return true;
            }
        };
        
        $class->method();

        $anotherClass = new class() {
            public function anotherMethod() {
                return false;
            }
        };
    }
            
}
