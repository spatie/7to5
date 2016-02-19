<?php

class Test
{
    function myFunction($input = null)
    {
        $result = isset($input) ? $input : 'fixed-value';
        return $result;
    }
}