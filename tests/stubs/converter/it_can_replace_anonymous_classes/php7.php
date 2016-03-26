<?php

use Import;

class Test
{
    public function test()
    {
        $class = new class() {
            public function method(string $parameter = '') : string {
                return $parameter ?? 'no parameter set';
            }
        };
        
        $class->method();

        $anotherClass = new class() {
            public function anotherMethod(int $integer) {
                return $integer;
            }
        };
    }
            
}
