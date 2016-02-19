<?php

class Test
{
    function myFunction($input, $input2, $input3)
    {
        $result = $input ?? 'fixed-value';

        $result = $input ?? $input2 ?? $input3;

        return $result;
    }

}