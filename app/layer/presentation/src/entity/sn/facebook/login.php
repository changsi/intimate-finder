<?php
require getLibFilePath('sn.util.facebook_data_wrapper');

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
     
	updateUserInfo();
	
	//echo HOST_PREFIX;
	//echo "<br>";
	header("Location: ".HOST_PREFIX."/sn/facebook/welcome");
	die();
		
}
else{
 	$loginUrl = $SNFacebookService->login();
}

function updateUserInfo() {
	global $SNFacebookService, $UserService;
	$user_profile = $SNFacebookService->getUserProfile(array("user_id" => $_SESSION["user_id"]));
	$data = get_data_from_profile($user_profile);
	$data['access_token'] = $_SESSION['access_token'];

	$UserService->insertUser($data);
}

?>
