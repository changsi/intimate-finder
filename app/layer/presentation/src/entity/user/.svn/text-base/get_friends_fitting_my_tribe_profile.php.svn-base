<?php
/*
Receives an USER ID or/and tribe id and returns the user friends that fits the profile of the user tribe but don't have a tribe themselves + the friends in the same tribe
*/

$network_id = 2; //(Facebook)

$uid = isset($_GET['uid']) && $_GET['uid'] ? $_GET['uid'] : false;
$tid = isset($_GET['tid']) && $_GET['tid'] ? $_GET['tid'] : false;
if (!$tid) {
	$tid = isset($_SESSION['tribe_id']) ? $_SESSION['tribe_id'] : false;
}

$friends = array();

if ($uid) {
	$userInfo = array (
		"network_user_id" => $uid,
		"network_id" => $network_id 
	);
	
	//get tribe of the user if we dont have it already
	if (!$tid) {
		$tribe = $UserService->getTribeByUserId($userInfo);
		$tid = isset($tribe[0]['tribe_id']) ? $tribe[0]['tribe_id'] : false;
		if (!$tid) {
			echo "An error occurred the no tribe could be found for the user";
			die();
		}
	}
	
	$userInfo["tribe_id"] = $tid;
	
	//get the friends in the same tribe
	$friends = $TribeService->getUserFriendsInThatTribe($userInfo);
	
	//get the friends that don't have a tribe (hash_user_id)
	$friendsWithoutATribe = $UserFriendService->getUserFriendsThatDontHaveATribeInLive($userInfo);
	
	//get the friends where dp_id that has the highest affinity for each friend without a tribe
	$friends_fitting_my_tribe_profile = array();
	if (!empty($friendsWithoutATribe)) {
		$userInfo["friends"] = $friendsWithoutATribe; 
		$friends_fitting_my_tribe_profile = $UserFriendService->getFriendsHavingTheSameTribeInCP($userInfo);
	}
	if (!empty($friends_fitting_my_tribe_profile)) {
		foreach ($friends_fitting_my_tribe_profile as $key => $friend) {
			$friend_hash_user_id = $UserService->getNetworkUserFromHashUserId($friend["hash_user_id"]);
			if ($friend_hash_user_id) {
				$friends_fitting_my_tribe_profile[$key]["network_user_id"] = $friend_hash_user_id;
			}
			else {
				unset($friends_fitting_my_tribe_profile[$key]);
			}
		}
		$friends = array_merge($friends, $friends_fitting_my_tribe_profile);
	}
	
}
else {
 //TODO file error
}

?>
