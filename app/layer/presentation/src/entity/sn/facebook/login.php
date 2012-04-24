<?php
/*
Logs in to FaceBook and save the access_token to the DB, so the scripts can run later.
Additionally starts the script: start_fetching_facebook_user_data.php

Uses: SNFacebookService->login(...)
*/

$access_token = isset($_SESSION["access_token"]) ? $_SESSION["access_token"] : NULL;

$user_id = $SNFacebookService->getUserId();

if($user_id) {
	//echo $user_id."\n";
	$_SESSION["user_id"] = $user_id;
    $_SESSION["access_token"] = $SNFacebookService->getAccessToken();
     
	//1. Check if the user has a tribe
	$data = array(
		'user_id'	=>	$user_id
     );
     
	$user_exist =  $UserService->getUserInfoFromLiveSystem($data);
	if(!empty($user_exist)){
		
	}else{
		getUserInfo();		
	}
	echo HOST_PREFIX;
	echo "<br>";
	header("Location: ".HOST_PREFIX."/sn/facebook/welcome");
	die();
		
}
else{
 	$loginUrl = $SNFacebookService->login();
}

function getUserInfo() {
	global $SNFacebookService, $UserService;
	$user_profile = $SNFacebookService->getUserProfile(array("user_id" => $_SESSION["user_id"]));
	$birthday= date_parse($user_profile['birthday']);
	$birth_date = $birthday['year'].'-'.$birthday['month'].'-'.$birthday['day'];
	$data = array(
		'access_token'  => $_SESSION['access_token'],
		'user_id'	=>	$_SESSION["user_id"],
		'name'	=>	$user_profile['name'],
		'birth_date' => $birth_date,
		'gender' => (isset($user_profile['gender'])?2:$user_profile['gender'])=='male'?0:1,
		'picture_url' => "",
		'expiry_date' => ''
     );

	$UserService->insertUser($data);
}

?>
