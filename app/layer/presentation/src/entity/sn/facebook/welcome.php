<?php
$access_token = isset($_SESSION["access_token"]) ? $_SESSION["access_token"] : NULL;
$user_id = isset($_SESSION["user_id"]) ? $_SESSION["user_id"] : NULL;

if($user_id) {
	$login = true;
	$my_profile = $UserService->getUserInfoFromLiveSystem(array('user_id'=>$user_id));
	$my_profile = $my_profile[0];
	$my_user_name = $my_profile['name'];
		
}
else{
	$login = false;
}



?>