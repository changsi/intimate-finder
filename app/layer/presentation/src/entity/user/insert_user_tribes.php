
<?php
/*Receives an USER ID and returns his correspondent tribes.
This is done by querying the tables: dp_user and dp_dp (CP DB)
The result should be returned in JSON

Uses: TribeService->getTribesByUserId(...)
*/
$network_id = 2; //(Facebook)

$user_network_id = isset($_GET['uid']) && $_GET['uid'] ? $_GET['uid'] : false;
$tribe_id = isset($_GET['tribe_id']) && $_GET['tribe_id'] ? $_GET['tribe_id'] : false;
$user_tribes = false;
if ($user_network_id && $tribe_id) {
	$data = array(	"network_user_id" => $user_network_id, 
					"tribe_id" => $tribe_id,
					"network_id" => $network_id
			);
	$user_tribes = $UserService->insertUserTribe($data);
}

?>
