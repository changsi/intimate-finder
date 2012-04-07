<?php
/*
Receives a TRIBE ID/MANUAL DP ID and gets all the users related with that tribe.
Use the dp_user and dp_dp tables (CP DB)

Uses: TribeService->getTribeUsers(...)
*/


$tid = isset($_GET['tid']) && $_GET['tid'] ? $_GET['tid'] : false ;

$users = array();

if ($tid) {
	$data = array (
		"tribe_id" => $tid,
		"min_affinity" => MIN_AFFINITY_TRIBE_DP
	);
	$usersFromCP = $TribeService->getTribeUsers($data);
	foreach ($usersFromCP as $user) {
			$user = $UserService->getUserInfoFromLiveSystem(array("hash_user_id" => $user["user_id"]));
			if (isset($user[0])) {
				$users[] = $user[0];
			}
	}
	//echo "<pre>";
	//print_r($users);
	//echo "</pre>";
}
else {
 //TODO file error
}

?>
