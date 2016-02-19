<?php

$result = isset($input) ? $input : 'fixed-value';
$result = isset($input) ? $input : (isset($input2) ? $input2 : $input3);