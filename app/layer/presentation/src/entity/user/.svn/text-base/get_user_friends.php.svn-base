<?php
/*
Receives an USER ID and returns the user friends from the table network_friend (FB DB) or friend table (DP DB).

Uses: UserService->getUserFriends(...) and UserService->getUserNetworkFriends(...)
*/

$network_id = 2; //(Facebook)


$uid = isset($_GET['uid']) && $_GET['uid'] ? $_GET['uid'] : false;
$type = isset($_GET['type']) && $_GET['type'] ? $_GET['type'] : false;


$friends = array();

if ($uid && $type) {
	$userInfo = array (
		"network_user_id" => $uid,
		"network_id" => $network_id 
	);
	
	switch($type) {
		case 'info':
			$friends = $UserService->getUserNetworkFriendsAndTheirInfo($userInfo);
		break;
		case 'affinity':
			$uidFromCP = $UserService->getCPUserIdFromNetworkUserId($userInfo);
			
			if ($uidFromCP) {
				$data = array (
					"hash_user_id" => $uidFromCP,
				);
				$friends = $UserService->getUserNetworkFriendsAffinity($data);
				
				if (!empty($friends)) {
					foreach ($friends as $key => $friend) {
						$data = array(
							"hash_user_id" => $friend['friend_id']
						);
						$friendInfo = $UserService->getUserInfoFromLiveSystem($data);
						if (!empty($friendInfo)) {
							$friend = array_merge($friend, $friendInfo[0]);//We take only the first elmt of the array since the array will return always only one row
							$friends[$key] = $friend;
						}
					}
				}
			}
			else {
				//TODO file error 
				echo "No user in CP with this network_user_id : ".$uid; 
			}
		break;

	}
}
else {
 //TODO file error
}

?>
