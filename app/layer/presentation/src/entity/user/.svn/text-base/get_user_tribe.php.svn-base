<?php
/*Receives an USER ID and returns his correspondent tribe from user_tribe table.
The result should be returned in JSON

Uses: TribeService->getTribesByUserId(...)
*/
$network_id = 2; //(Facebook)

$user_network_id = isset($_GET['uid']) && $_GET['uid'] ? $_GET['uid'] : false;
$tribes = array();
if ($user_network_id) {
	$data = array("network_user_id" => $user_network_id, "network_id" => $network_id );
	$tribes = $UserService->getTribeByUserId($data);
}
?>
