<?php
require getLibFilePath('sn.util.facebook_data_wrapper');

$access_token = isset($_SESSION["access_token"]) ? $_SESSION["access_token"] : NULL;

$user_id = $SNFacebookService->getUserId();

if($user_id) {
	//echo $user_id."\n";
	$_SESSION["user_id"] = $user_id;
	$_SESSION["access_token"] = $SNFacebookService->getAccessToken();	
	$data = array("user_id"=>$_SESSION["user_id"]);
	$user_download_status = $UserFBDownloadProgressionService->getUserProgress($data);
	if(!empty($user_download_status)){
		$user_download_status = $user_download_status[0];
		if($user_download_status['control_flag']==1){
			echo "false";
			die();
		}
	}
	$data = array("user_id"=>$_SESSION["user_id"], "progress"=>0, "control_flag"=>1);
	$UserFBDownloadProgressionService->insertUserProgress($data);
	$command = "/usr/bin/php ".getScriptFilePath("fetching_facebook_checkin")." ".$_SESSION["user_id"]." ".$_SESSION["access_token"].' > /dev/null &';
		
	$handler = exec($command);
	echo "true";
	
	
	//$data = array("user_id"=>$_SESSION["user_id"], "progress"=>100, "control_flag"=>0);
	//$UserProgressionService->insertUserProgress($data);
	//die();
}
else{
	header("Location: ".HOST_PREFIX."/sn/facebook/login");
	die();
}


?>