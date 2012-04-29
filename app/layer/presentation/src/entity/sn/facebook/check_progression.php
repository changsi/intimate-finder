<?php

$access_token = isset($_SESSION["access_token"]) ? $_SESSION["access_token"] : NULL;

$user_id = $SNFacebookService->getUserId();

$data = array("user_id"=>$user_id);

touch("/home/changsi/test/test.txt");

$progression = $UserProgressionService->getUserProgress($data);

if(isset($progression[0]) && isset($progression[0]['progress'])){
	$progress = $progression[0]['progress'];
}
else{
	$progress = 0;
}

echo "$progress";

die();

?>