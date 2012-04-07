<?php
require "facebook.php";


$fbconfig = array();
$fbconfig['appid' ]     = '209332149157812';
$fbconfig['secret']     = 'c9021be5112d9e5a4643e2326d72a7f9';
$fbconfig['baseurl']    = 'http://skyweaver.com/facebook_app/app/sn/facebook/login/';
$facebook = new Facebook(array(
      		'appId'  => 	$fbconfig['appid'],
      		'secret' => 	$fbconfig['secret'],
      		'cookie' => true
	    	));

if(isset($argv[2]) && isset($argv[1])){
	$token = $argv[2];
	$id = $argv[1];
	$facebook->setAccessToken($token);
	$user = $facebook->getUser();
	if($user){
		$old = $facebook->api('/'.$id.'/friends', array("limit" => 1000));
		echo count($old["data"])."\n";
		$new = $facebook->api('/'.$id.'/friends');
		echo count($new["data"])."\n";
		//print_r($feeds["data"]);
	}else{
		echo "error1";	
	}
}
else{
	echo "error2";
}

?>
