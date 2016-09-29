<?php

// Integers
echo 1 <=> 1; // 0
echo 1 <=> 2; // -1
echo 2 <=> 1; // 1

// Floats
echo 1.5 <=> 1.5; // 0
echo 1.5 <=> 2.5; // -1
echo 2.5 <=> 1.5; // 1

// Strings
echo 'a' <=> 'a'; // 0
echo 'a' <=> 'b'; // -1
echo 'b' <=> 'a'; // 1

echo 'a' <=> 'aa'; // -1
echo 'zz' <=> 'aa'; // 1

// Arrays
echo [] <=> []; // 0
echo [1, 2, 3] <=> [1, 2, 3]; // 0
echo [1, 2, 3] <=> []; // 1
echo [1, 2, 3] <=> [1, 2, 1]; // 1
echo [1, 2, 3] <=> [1, 2, 4]; // -1

// Objects
$a = (object) ['a' => 'b'];
$b = (object) ['a' => 'b'];
echo $a <=> $b; // 0

$c = (object) ['a' => 'b'];
$d = (object) ['a' => 'c'];
echo $c <=> $d; // -1

$e = (object) ['a' => 'c'];
$f = (object) ['a' => 'b'];
echo $e <=> $f; // 1

// only values are compared
$g = (object) ['a' => 'b'];
$h = (object) ['b' => 'b'];
echo $g <=> $h; // 1

