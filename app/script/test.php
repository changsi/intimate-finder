<?php

$a = array("f"=>5);

$b = array();
$b[0] = $a;
$b[0]['f'] = 6;

$c = array();
$c[0] = $a;
$c[0]['f'] = 111;

print_r($a);
print_r($b);
print_r($c);
?>