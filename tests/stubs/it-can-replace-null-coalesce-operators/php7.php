<?php

class Test
{
    function myFunction($input = null) : string
    {
        $result = $input ?? 'fixed-value';

        return $result;
    }

}