<?php

class Test
{
    function myFunction($input, $input2, $input3)
    {
        $result = isset($input) ? $input : 'fixed-value';
        $result = isset($input) ? $input : (isset($input2) ? $input2 : $input3);
        return $result;
    }
}