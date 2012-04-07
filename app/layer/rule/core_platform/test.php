<?php
define('APP_PATH', "/home/envio/projects/targeted_advertising/trunk/client/facebook_app/app");

require "/home/envio/projects/targeted_advertising/trunk/client/facebook_app/app/lib/cms/init.php";
require "ObjectCPRule.php";

$file = file_get_contents('urls.txt');
$lines = explode("\n", $file);


$object = new ObjectCPRule();

foreach( $lines as $url){
	if(trim($url) != ""){
		$url = $object->prepareUrl($url);
		echo $url."\n";
	}
}


?>
