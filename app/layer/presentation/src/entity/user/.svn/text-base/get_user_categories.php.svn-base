<?php
/*
Receives an USER ID and returns the correspondent categories and affinities from the user_category table (CP DB)

Uses: UserCategoryService->getUserCategories(...)
*/

$uid = isset($_GET['uid']) && $_GET['uid'] ? $_GET['uid'] : false ;

$categories = array();

if ($uid) {
	$userInfo = array (
		"network_user_id" => $uid,
		"network_id" => 2 //(Facebook)
	);
	$uidFromCP = $UserService->getCPUserIdFromNetworkUserId($userInfo);
	
	if ($uidFromCP) {
		$data = array (
			"user_id" => $uidFromCP,
		);
		$categories = $UserCategoryService->getUserCategories($data);
	}
	else {
		//TODO file error 
		echo "No user in CP with the network_user_id : ".$uid; 
	}
}
else {
 //TODO file error
}


?>
