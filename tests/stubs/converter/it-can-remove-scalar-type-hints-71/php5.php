<?php

class Test
{
    /**
     * @param \MyClass $class
     * @param string   $string
     * @param int      $int
     * @param int      $int2
     * @param bool     $bool
     * @param bool     $bool2
     * @param float    $float
     *
     * @return int
     */
    public function myFunction(MyClass $class = null, $string, $int, $int2, $bool, $bool2, $float = null)
    {
        //HI I AM A COMMENT
        return 5;
    }
}
