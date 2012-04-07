<?php
require_once dirname(__FILE__) . '/URLNormalizer.php';
//require_once getLibFilePath("web.normalizer.URLNormalizer");

$url = 'eXAMPLE://a/./b/../b/%63/%7bfoo%7d';
$url = '  http://www.google.com:802/f/asd/4/5/6/6#asd=asd  ';

$un = new URLNormalizer();
$un->setUrl( $url );

echo $un->normalize();
echo "\n";

//result: "example://a/b/c/%7Bfoo%7D"
