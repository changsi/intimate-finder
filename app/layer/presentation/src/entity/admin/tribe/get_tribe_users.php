<?php
/*
Receives a TRIBE ID and gets all the users related with that tribe in the user_tribe table

Uses: TribeService->getTribeUsers(...)
*/


$tid = isset($_GET['tid']) && $_GET['tid'] ? $_GET['tid'] : false ;

$users = array();

if ($tid) {
	$data = array (
		"tribe_id" => $tid,
	);
	$users = $TribeService->getTribeUsersInLive($data);

}
else {
 //TODO file error
}

?>
