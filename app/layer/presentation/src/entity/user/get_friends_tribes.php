<?php
/*
Receives an USER ID and returns all the friend's tribes for that user. 
The result shold be encoded in JSON.

Uses: UserService->getTribesForUserFriends(...)
*/

$uid = isset($_GET['uid']) && $_GET['uid'] ? $_GET['uid'] : false ;

$friends_tribes = array();

if ($uid) {
	$userInfo = array (
		"network_user_id" => $uid,
		"network_id" => 2 //(Facebook)
	);
	$friends_tribes = $UserService->getTribesForUserFriends($userInfo);

}
else {
 //TODO file error
}

?>
