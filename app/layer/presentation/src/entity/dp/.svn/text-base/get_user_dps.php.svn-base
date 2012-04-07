<?php
/*
Receives an USER ID via GET and returns the dynamic dps from the dp_user table (CP DB). 
The result should be encoded in JSON.

Uses: DPService->getDPsByUser(...)
*/

$uid = isset($_GET['uid']) && $_GET['uid'] ? $_GET['uid'] : false ;

$dynamic_dps = array();

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
		$dynamic_dps = $DPService->getDPSByUSer($data);
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
