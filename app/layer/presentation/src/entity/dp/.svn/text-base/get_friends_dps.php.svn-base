<?php
/*
Receives an USER ID and returns all the friend's DPs for that user. You should make a sql to the 2 tables: friend and dp_user tables (CP DB).
The result shold be encoded in JSON.

Uses: DPService->getDPsByUserFriends(...)
*/

$uid = isset($_GET['uid']) && $_GET['uid'] ? $_GET['uid'] : false ;

$friends_dps = array();

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
		$friends_dps = $DPService->getDPsByUserFriends($data);

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
