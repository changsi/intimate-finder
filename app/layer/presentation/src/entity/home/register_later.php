<?php

function getUserInfo() {
	global $SNFacebookService, $UserService;
	
	$screenName = $SNFacebookService->getUserPseudo(array("network_user_id" => $_SESSION["network_user_id"]));
	$data = array(
		'access_token'  => $_SESSION['access_token'],
		'network_user_id'	=>	$_SESSION["network_user_id"],
		'network_id' => $_SESSION["network_id"],
		'name'	=>	$SNFacebookService->getUserName(array("network_user_id" => $_SESSION["network_user_id"])),
		'screen_name' => $screenName ? $screenName : '' ,
		'invitation_pending' => 1
     );

	$UserService->insertUserNetwork($data);
}

if(isset($_SESSION["network_user_id"]) && isset($_SESSION['access_token']) && isset($_SESSION["network_id"])) {

	$var = $UserService->getUserTypeFromLiveSystem($_SESSION["network_user_id"]);
	if(!empty($var)) {
		if($var[0]['invitation_pending'] == 1) {
			$register = 1;
			echo "You Are already Registered Come Back Later";
		}	
	}
	if (isset($_POST['submit'])) {
		//Mandatory, to initialize facebook
		$SNFacebookService->getUserId();
		
		getUserInfo();
		$register = 2;
		$command = "php ".getScriptFilePath("start_fetching_facebook_user_data_by_batch")." ".$_SESSION["network_user_id"].' &';
		
		$handler = popen($command, "r");
		
	}
}

?>

