<?php

// Integers
echo 1 < 1 ? -1 : (1 == 1 ? 0 : 1);
// 0
echo 1 < 2 ? -1 : (1 == 2 ? 0 : 1);
// -1
echo 2 < 1 ? -1 : (2 == 1 ? 0 : 1);
// 1
// Floats
echo 1.5 < 1.5 ? -1 : (1.5 == 1.5 ? 0 : 1);
// 0
echo 1.5 < 2.5 ? -1 : (1.5 == 2.5 ? 0 : 1);
// -1
echo 2.5 < 1.5 ? -1 : (2.5 == 1.5 ? 0 : 1);
// 1
// Strings
echo 'a' < 'a' ? -1 : ('a' == 'a' ? 0 : 1);
// 0
echo 'a' < 'b' ? -1 : ('a' == 'b' ? 0 : 1);
// -1
echo 'b' < 'a' ? -1 : ('b' == 'a' ? 0 : 1);
// 1
echo 'a' < 'aa' ? -1 : ('a' == 'aa' ? 0 : 1);
// -1
echo 'zz' < 'aa' ? -1 : ('zz' == 'aa' ? 0 : 1);
// 1
// Arrays
echo array() < array() ? -1 : (array() == array() ? 0 : 1);
// 0
echo array(1, 2, 3) < array(1, 2, 3) ? -1 : (array(1, 2, 3) == array(1, 2, 3) ? 0 : 1);
// 0
echo array(1, 2, 3) < array() ? -1 : (array(1, 2, 3) == array() ? 0 : 1);
// 1
echo array(1, 2, 3) < array(1, 2, 1) ? -1 : (array(1, 2, 3) == array(1, 2, 1) ? 0 : 1);
// 1
echo array(1, 2, 3) < array(1, 2, 4) ? -1 : (array(1, 2, 3) == array(1, 2, 4) ? 0 : 1);
// -1
// Objects
$a = (object) array('a' => 'b');
$b = (object) array('a' => 'b');
echo $a < $b ? -1 : ($a == $b ? 0 : 1);
// 0
$a = (object) array('a' => 'b');
$b = (object) array('a' => 'c');
echo $a < $b ? -1 : ($a == $b ? 0 : 1);
// -1
$a = (object) array('a' => 'c');
$b = (object) array('a' => 'b');
echo $a < $b ? -1 : ($a == $b ? 0 : 1);
// 1
// only values are compared
$a = (object) array('a' => 'b');
$b = (object) array('b' => 'b');
echo $a < $b ? -1 : ($a == $b ? 0 : 1);
