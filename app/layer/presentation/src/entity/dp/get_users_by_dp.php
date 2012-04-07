<?php
/*
Receives a DP ID and gets all the users for that specific DP.
Use the dp_user table (CP DB).
The result should be encoded in a JSON format.

Uses: DPService->getDPUsers(...)
*/
$dpid = isset($_GET['dpid']) && $_GET['dpid'] ? $_GET['dpid'] : false ;

$users = array();

if ($dpid) {
	$data = array (
		"dp_id" => $dpid,
	);
	$users_dps = $DPService->getDPUsers($data);
	foreach ($users_dps as $user) {
		$userInfo = $UserService->getUserInfoFromLiveSystem(array('hash_user_id' => $user['user_id']));
		if (!empty($userInfo)) {
			$users[] = $userInfo[0];
		}
	}
}
else {
 //TODO file error
}


?>
